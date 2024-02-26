<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Admin\BatchItemAttachement;
use App\Models\Admin\BatchMaster;
use App\Models\Admin\BatchSlaItem;
use App\Models\Admin\KeyAccountManager;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use ZipArchive;
use Illuminate\Support\Facades\Mail;

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
        $batches = BatchMaster::where('business_id',$business_id)->whereIn('status',['1','2'])->get();
        return view('clients.batch.index',compact('batches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $business_id = Auth::user()->business_id;

        $slas = DB::table('customer_sla as sla')
        ->select('sla.*')
        ->where(['sla.business_id'=>$business_id])
        ->get();
        return view('clients.batch.create', compact('slas')); 
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
        $parent_id = Auth::user()->parent_id;
        $rules= [
         
            'sla'         => 'required',
            'no_of_candidates'  => 'required|numeric|min:1',
            // 'tat'       => 'required|numeric|min:1',
            'files'        => 'required',
            'batch'       => 'required',
            'services'    => 'required|array|min:1',
        
         ];
        
         $customMessages = [
            'services.required' => 'Select atleast one Check or Service item.',
            'no_of_candidates.required' => 'Please fill the numbers of Candidates.',
            'files.required'=>'Select atleast One File.'
        ];
  
         $validator = Validator::make($request->all(), $rules,$customMessages);
          
         if ($validator->fails()){
             return response()->json([
                 'success' => false,
                 'errors' => $validator->errors()
             ]);
         }



    


        //  $file = $request->file('file');
        // //  dd($request->file('file'));
        // $filename = $file->getClientOriginalName();
        // $fileext = $file->getClientOriginalExtension();
        // strtolower($fileext);
        // $path = public_path().'/uploads/batch-file/';
        // $timefile = time().$filename;
        // $request->file->move($path,$timefile);
         
         DB::beginTransaction();
         try
         {

            $new_batch = new BatchMaster();
            $new_batch->business_id = $business_id;
            $new_batch->user_id = Auth::user()->id;
            $new_batch->batch_name = $request->batch;
            $new_batch->parent_id = $parent_id;
            $new_batch->sla_id  =  $request->sla;
            $new_batch->no_of_candidates = $request->no_of_candidates;
            $new_batch->created_by = Auth::user()->id;
            //  $new_batch->file_name = $timefile;
            $new_batch->tat = 3;
            $new_batch->save();

            // batch attachment strat
            $attach_on_select=[];
            $allowedextension=['zip','pdf','JPG','PDF','JPEG','PNG','ZIP'];
            $zip_name="";
            $now= Carbon::parse($new_batch->created_at)->format('Ymdhis');
            if($request->hasFile('files') && $request->file('files') !="")
            {
                $filePath = public_path('/uploads/batch-file/'); 
                $files= $request->file('files');
                foreach($files as $file)
                {
                        $extension = $file->getClientOriginalExtension();
    
                        $check = in_array($extension,$allowedextension);
    
                        if(!$check)
                        {
                            return response()->json([
                            'fail' => true,
                            'errors' => ['files' => 'Only zip,pdf are allowed !'],
                            'error_type'=>'validation'
                            ]);                        
                        }
                }
    
                $zipname = 'batch_file-'.$now.'-'.$new_batch->id.'.zip';
                $zip = new \ZipArchive();      
                $zip->open(public_path().'/uploads/batch-file/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
    
                foreach($files as $file)
                {
                    $file_data = $file->getClientOriginalName();
                    $tmp_data  = $new_batch->id.'-'.$now.'-'.$file_data; 
                    $data = $file->move($filePath, $tmp_data);       
                    $attach_on_select[]=$tmp_data;
    
                    $path=public_path()."/uploads/batch-file/".$tmp_data;
                    $zip->addFile($path, '/batch-file/'.basename($path));  
                }
    
                $zip->close();
            }

            if(count($attach_on_select)>0)
            {
                $i=0;
                foreach($attach_on_select as $item)
                {
                    $batch_item = new BatchItemAttachement();
                    $batch_item->business_id = $business_id;
                    $batch_item->batch_id= $new_batch->id;
                    $batch_item->file_name = $attach_on_select[$i];
                    $batch_item->zip_file = $zipname!=""?$zipname:NULL;
                    $batch_item->save();
                
                    $i++;
                }
            }
            if( count($request->services) > 0 ){
                foreach($request->services as $item){

                    $batch_sla_item = new BatchSlaItem();
                    $batch_sla_item->business_id = $business_id;
                    $batch_sla_item->batch_id= $new_batch->id;
                    $batch_sla_item->sla_id = $request->sla;
                    $batch_sla_item->service_id = $item;
                    $batch_sla_item->save();
                
                }
            }

            $kams  = KeyAccountManager::where('business_id',$business_id)->get();
            // dd($kams);
            if (count($kams)>0) {
                foreach ($kams as $kam) {

                    $user= User::where('id',$kam->user_id)->first();
                
                    $email = $user->email;
                    $name  = $user->name;
                    $batch_name = $request->batch;
                    $msg = "New JAF Batch Task Created in Batch Menu with Batch name";
                    $sender = DB::table('users')->where(['id'=>$parent_id])->first();
                    $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$batch_name,'msg'=>$msg,'sender'=>$sender);

                    Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                        $message->to($email, $name)->subject
                            ('myBCD System - Notification for Batch task');
                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    });

                }
            
            }
            
            DB::commit();
            return response()->json([
                'success' => true,
                'errors' => []
            ]);

            // return redirect('my/batches')
            // ->with('success', 'Batch Successfully submitted.');
         }
         catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }
    }


    //Download Zip
    public function zipDownload($id)
    {
        $batch_id = base64_decode($id);
        $zip = new ZipArchive;
        $file_name = BatchItemAttachement::select('zip_file','created_at','file_name')->where('batch_id',$batch_id)->first();
        // $fileName = 'myNewFile.zip';
        $file_count = BatchItemAttachement::select('zip_file','created_at','file_name')->where('batch_id',$batch_id)->count();
        // $date = Carbon::parse($file_name->created_at)->format('Ymdhis');
        //    dd($file_name);
       
        if ($file_name->zip_file==null) {
            return response()->download(public_path().'/uploads/batch-file/'.$file_name->file_name);
        }elseif($file_count > 1)
        {
            return response()->download(public_path().'/uploads/batch-file/'.$file_name->zip_file);
        }
        else{
            return response()->download(public_path().'/uploads/batch-file/'.$file_name->file_name);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        try{
            $status =  DB::table('batch_masters')
            ->where('id', $batch_id)
            ->update(['status' => '2','deletion_request_at'=>date('Y-m-d H:i:s'),'deletion_request_by'=>Auth::user()->id]);
        
            //return result 
            if($status){  
                DB::commit(); 
                return response()->json([
                'status'=>'ok',
                'message' => 'request sent',                
                ], 200);
            }
            else{
                return response()->json([
                'status' =>'no',
                ], 200);
            }
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }  
    }
   
  

    
}
