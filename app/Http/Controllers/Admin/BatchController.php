<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Admin\BatchItemAttachement;
use App\Models\Admin\BatchMaster;
use App\Models\Admin\BatchSlaItem;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use ZipArchive;
use App\Traits\EmailConfigTrait;
use Illuminate\Support\Facades\Config;
use App\Traits\S3ConfigTrait;
use Illuminate\Support\Facades\Storage;
class BatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       

        $business_id = Auth::user()->business_id;
        $batches = DB::select("SELECT bm.* ,GROUP_CONCAT(DISTINCT bsi.service_id) AS alot_services FROM batch_masters AS bm JOIN batch_sla_items AS bsi ON bsi.batch_id=bm.id where bm.parent_id =$business_id AND (bm.status='1' OR bm.status='2') GROUP BY bm.id ORDER BY bm.id DESC ");

        // dd($batches);
        $customers = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['user_type'=>'client','parent_id'=>$business_id])
        ->get();

        $users = DB::table('users')->where('user_type','user')->where('business_id',Auth::user()->business_id)->get();

        $slas = DB::table('customer_sla as sla')
              ->select('sla.*')
              ->join('users as u','u.business_id','=','sla.business_id')
              ->where(['u.user_type'=>'client','sla.parent_id'=>$business_id])
              ->get();


        return view('admin.batches.index',compact('batches','customers','users','slas'));
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $business_id = Auth::user()->business_id;

        $customers = DB::table('users as u')
        ->select('u.id','u.name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['user_type'=>'client','parent_id'=>$business_id])
        ->get();
       return view('admin.batches.create',compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // dd($request);
         
         $business_id = Auth::user()->business_id;
         $rules= [
            'customer'  =>'required',
             'sla'         => 'required',
             'no_of_candidates'  => 'required|numeric|min:1',
             'tat'       => 'required|numeric|min:1',
             'file'       => 'required',
             'batch'       => 'required',
             'file'      =>      'required|mimes:zip',
             'services'    => 'required|array|min:1',
         
          ];
         
          $customMessages = [
            'services.required' => 'Select at least one Check or Service item.',
           'no_of_candidates.required' => 'Please fill the numbers of Candidates.'
         ];
   
          $validator = Validator::make($request->all(), $rules,$customMessages);
           
          if ($validator->fails()){
              return response()->json([
                  'success' => false,
                  'errors' => $validator->errors()
              ]);
          }
 
        DB::beginTransaction();
        try
        {
            $file_platform = 'web';
            $s3_config = S3ConfigTrait::s3Config();

            $file = $request->file('file');
            //  dd($request->file('file'));
            $filename = $file->getClientOriginalName();
            $fileext = $file->getClientOriginalExtension();
            strtolower($fileext);
            
            
            $path = public_path().'/uploads/batch-file/';

            $timefile = time().$filename;
            
            if($s3_config!=NULL)
            {
                $file_platform = 's3';

                $path = 'uploads/batch-file/';

                if(!Storage::disk('s3')->exists($path))
                {
                    Storage::disk('s3')->makeDirectory($path,0777, true, true);
                }

                Storage::disk('s3')->put($path.$timefile, file_get_contents($file));
            }
            else
            {
                $request->file->move($path,$timefile);
            }
            
            
    
            $new_batch = new BatchMaster();
            $new_batch->business_id = $request->customer;
            $new_batch->user_id = Auth::user()->id;
            $new_batch->batch_name = $request->batch;
            $new_batch->parent_id = $business_id;
            $new_batch->sla_id  =  $request->sla;
            $new_batch->no_of_candidates = $request->no_of_candidates;
            //  $new_batch->file_name = $timefile;
            $new_batch->tat = $request->tat;
            $new_batch->created_by = Auth::user()->id;
            $new_batch->save();
    
            //  if (count($file) > 0) {
            //     foreach($file as $f){
                
                    $batch_item = new BatchItemAttachement();
                    $batch_item->business_id =  $request->customer;
                    $batch_item->batch_id= $new_batch->id;
                    $batch_item->file_name = $timefile;
                    $batch_item->file_platform = $file_platform;
                    $batch_item->save();
                    
            //     }
            // }
            if( count($request->services) > 0 ){
                foreach($request->services as $item){
    
                    $batch_sla_item = new BatchSlaItem();
                    $batch_sla_item->business_id =  $request->customer;
                    $batch_sla_item->batch_id= $new_batch->id;
                    $batch_sla_item->sla_id = $request->sla;
                    $batch_sla_item->service_id = $item;
                    $batch_sla_item->save();
                    
                }
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'errors' => []
                ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
 
        //  return redirect('/batches')
        //  ->with('success', 'Batch Successfully submitted.');


    }


 
    //Download Zip
    public function zipDownload($id)
    {
        $s3_config = S3ConfigTrait::s3Config();

        $batch_id = base64_decode($id);
        $zip = new ZipArchive;
        $file_name = BatchItemAttachement::select('file_name','file_platform')->where('batch_id',$batch_id)->first();
        // $fileName = 'myNewFile.zip';

        if($file_name!=NULL)
        {
            if(stripos($file_name->file_platform,'s3')!==false)
            {
                $filePath='uploads/batch-file/'.$file_name->file_name;

                $disk = Storage::disk('s3');

                $result = $disk->getDriver()->getAdapter()->getClient()->getObject([
                    'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                    'Key'                        => $filePath,
                    //'ResponseContentDisposition' => 'attachment;'//for download
                ]);

                header('Content-type: ' . $result['ContentType']);
                header('Content-Disposition: attachment; filename="' . $file_name->file_name . '"');
                header('Content-length:' . $result['ContentLength']);

                echo $result['Body'];

            }
            else
            {
                return response()->download(public_path().'/uploads/batch-file/'.$file_name->file_name);
            }
        }
        else
        {
            return redirect('/error-404-data');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $rules = [
            'user' => 'required',
                // 'tat' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
                
               if ($validator->fails()){
                   return response()->json([
                       'fail' => true,
                       'errors' => $validator->errors()
                   ]);
               }

        DB::beginTransaction();
        try{
            $batch_id = $request->batch_id;
            // dd($request->customer_id);
            // $business_id = Auth::user()->business_id;
            $service = $request->service_id;
            $services = explode(',', $service);

            $batch_master =BatchMaster::where('id',$batch_id)->first();
            // $batch_master->business_id = $request->customer_id;
            // $batch_master->user_id = Auth::user()->id;
            $batch_master->batch_name = $request->batch_name;
            $batch_master->assign_to = $request->user;
            $batch_master->sla_id  =  $request->sla;
            $batch_master->no_of_candidates = $request->no_of_candidates;
            $batch_master->tat = $request->tat;
            $batch_master->assign_by = Auth::user()->id;
            $batch_master->save();
            

            if( count($services) > 0 ){
                foreach($services as $item){
                    $count = BatchSlaItem::where(['batch_id'=>$batch_id,'service_id'=>$item])->count();

                    // dd($count);
                    if ($count > 0) {
                    $batch_sla_item = BatchSlaItem::where(['batch_id'=>$batch_id,'service_id'=>$item])->first();
                    $batch_sla_item->business_id =  $request->customer;
                    $batch_sla_item->batch_id= $batch_master->id;
                    $batch_sla_item->sla_id = $request->sla;
                    $batch_sla_item->service_id = $item;
                    $batch_sla_item->save();
                    }else {
                        
                        $batch_sla_item = new BatchSlaItem();
                        $batch_sla_item->business_id =  $request->customer;
                        $batch_sla_item->batch_id= $batch_master->id;
                        $batch_sla_item->sla_id = $request->sla;
                        $batch_sla_item->service_id = $item;
                        $batch_sla_item->save();
                    }
                
                
                }
            }


            // Mail send to user

            $user= User::where('id',$request->user)->first();
            if ($user->email) {
             # code...
            
            $email = $user->email;
            $name  = $user->name;
            //$candidate_name =  Helper::user_name($request->candidate);
            $msg = "Batch Task Assign to you in Batches Menu with Batch name";
            $sender = DB::table('users')->where(['id'=>$business_id])->first();
            $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$request->batch_name,'msg'=>$msg,'sender'=>$sender);

            EmailConfigTrait::emailConfig();
            //get Mail config data
            //   $mail =null;
            $mail= Config::get('mail');
         // dd($mail['from']['address']);
            if (count($mail)>0) {
                Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                    $message->to($email, $name)->subject
                    ('myBCD System - Notification for Candidate Creation Task');
                    $message->from($mail['from']['address'],$mail['from']['name']);
                });
            } else {
                $email = $user->email;
                $name  = $user->name;
                //$candidate_name =  Helper::user_name($request->candidate);
                $msg = "Batch Task Assign to you in Batches Menu with Batch name";
                $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$request->batch_name,'msg'=>$msg,'sender'=>$sender);

                Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                    $message->to($email, $name)->subject
                        ('myBCD System - Notification for Candidate Creation Task');
                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                });
            }
            DB::commit();
            return response()->json([
                'fail' => false,
                'errors' => '',
                'success'      =>'batch updated successfully',
            ]);
        }
    }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;

        }  

    }

    /**
    * Get the Mix sla's items.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */
   public function getMixSlaItemList(Request $request)
   {

       $business_id=Auth::user()->business_id;
       $sla_id = $request->input('sla_id');

    //    dd($sla_id);
       $slaItems =[];
       $existItem=[];
        // dd($sla_id);
       try
       {
            $sla_items = DB::table('batch_sla_items as sla')
                    ->select('sla.id as sla_item_id','s.id as service_id','s.name as service_name','s.verification_type')
                    ->join('services as s','s.id','=','sla.service_id')
                    ->where(['sla.sla_id'=>$sla_id])
                    ->whereNotIn('s.name',['GSTIN'])
                    ->get();
            if(count($sla_items)==0)
            {
                $sla_items = DB::table('customer_sla_items as sla')
                    ->select('sla.id as sla_item_id','s.id as service_id','s.name as service_name','s.verification_type')
                    ->join('services as s','s.id','=','sla.service_id')
                    ->where(['sla.sla_id'=>$sla_id])
                    ->whereNotIn('s.name',['GSTIN'])
                    ->get();
            }
            // dd($sla_items);
            $slaItems = (array) json_decode(json_encode($sla_items),true);

            // dd($slaItems);
            // dd($slaItems);
            foreach($slaItems as $item){
                $existItem[]= $item['service_id'];
            }   
            
            // dd($existItem);

            $all_services = DB::table('services as s')
                            ->select('s.id as service_id','s.name as service_name','s.verification_type')
                            ->join('service_form_inputs as si','s.id','=','si.service_id')
                            ->where('s.business_id',NULL)
                            ->where('s.status',1)
                            ->whereNotIn('s.type_name',['gstin'])
                            ->orwhere('s.business_id',$business_id)
                            ->groupBy('si.service_id')
                            ->get();
            
            $all_service_items = (array) json_decode(json_encode($all_services),true);

            $accumulated_list = [];

            
            // dd($all_service_items);
            foreach($all_services as $item){
                $checked_atatus = FALSE;
                if(in_array($item->service_id,$existItem)){
                    $checked_atatus = TRUE;
                }            
                $accumulated_list[] = ['checked_atatus'=>$checked_atatus,'service_id'=>$item->service_id,'service_name'=>$item->service_name];
            }
            
            return response()->json([
                'success'   =>true,
                'data'      =>$accumulated_list 
            ]);
        }
        catch (\Exception $e) {
            // something went wrong
            return $e;
        }      

   }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteBatch(Request $request)
    {
        $batch_id = base64_decode($request->get('batch_id'));
        DB::beginTransaction();
        try
        {
            if ($request->type=='approve') {
                $status =  DB::table('batch_masters')
                ->where('id', $batch_id)
                ->update(['status' => '0','deletion_request_approve_at'=>date('Y-m-d H:i:s'),'deletion_request_approve_by'=>Auth::user()->id]);
                DB::commit();
                //return result 
                if($status){   
                    return response()->json([
                    'status'=>'ok',
                    'message' => 'Successfully deleted',                
                    ], 200);
                }
                else{
                    return response()->json([
                    'status' =>'no',
                    ], 200);
                }
            } else {

                $status =  DB::table('batch_masters')
                ->where('id', $batch_id)
                ->update(['status' => '1','deletion_request_cancel_at'=>date('Y-m-d H:i:s'),'deletion_request_cancel_by'=>Auth::user()->id]);
                DB::commit();
                if($status){   
                    return response()->json([
                    'status'=>'ok',
                    'message' => 'Deletion Request Cancel',                
                    ], 200);
                }
                else{
                    return response()->json([
                    'status' =>'no',
                    ], 200);
                }
            
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }      
       
    }
}
