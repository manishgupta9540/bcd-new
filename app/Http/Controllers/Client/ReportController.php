<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Http\Request;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $business_id = Auth::user()->business_id; 
        
        $candidates = DB::table('reports as r')->select('u.id','u.first_name','u.last_name','u.name','u.client_emp_code') 
        ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
        ->where(['r.business_id' => $business_id])->get();
        
        // dd($candidates);
        $data = DB::table('reports as r') 
                ->select('r.*','cl.title','u.client_emp_code','u.id as user_id','u.name','u.first_name','u.email','u.display_id','u.phone','u.phone_code','u.phone_iso','u.created_at as candidate_creation_date','j.tat','j.client_tat','j.days_type','u.parent_id as user_parent_id') 
                ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                ->join('customer_sla as cl','cl.id','=','r.sla_id')
                ->join('job_items as j','j.candidate_id','=','u.id')
                ->where(['r.business_id' => $business_id,'r.is_report_complete'=>'1']);

                if($request->get('candidate_id') !=""){
                    $data->where('r.candidate_id','=',$request->get('candidate_id'));
                }

                if($request->get('from_date') !=""){
                    $data->whereDate('r.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                if($request->get('to_date') !=""){
                    $data->whereDate('r.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                if($request->get('email')){
                    $data->where('u.email',$request->get('email'));
                  }
                  if($request->get('mob')){
                    $data->where('u.phone',$request->get('mob'));
                  }
                  if($request->get('ref')){
                    $data->where('u.display_id',$request->get('ref'));
                  }
                  if($request->get('r_status')){
                    $data->where('r.status',$request->get('r_status'));
                  }
                  if ($request->get('search')) {
                    // $searchQuery = '%' . $request->search . '%';
                  // echo($request->input('search'));
                    $data->where('u.first_name',$request->get('search'))->orWhere('u.name',$request->get('search'))->orWhere('u.email',$request->get('search'))->orWhere('u.display_id',$request->get('search'))->orWhere('u.phone',$request->get('search'))->orWhere('u.client_emp_code',$request->get('search'));
                  }
                  if($request->get('emp_code') !=""){
                    $data->where('r.candidate_id','=',$request->get('emp_code'));
                  }
                  if($request->get('report_status') !=""){
                    $data->where('r.status','=',$request->get('report_status'));
                  }
                  if($request->get('report_status1') !="" || $request->get('report_status2') !=""){
                    $value=$request->get('report_status1').','.$request->get('report_status2');
                    $data->whereIn('r.status',explode(',',$value));
                  }
               $data =  $data->orderBy('r.created_at','desc')
                ->paginate(20); 


            $incomplete = $request->get('report_status');
            $completed=$request->get('report_status1');
            $interim=$request->get('report_status2');
             
                //dd($data);
            // dd($reviews);
            if ($request->ajax())
                return view('clients.reports.ajax', compact('data','candidates','incomplete','completed','interim'));
            else
                return view('clients.reports.index', compact('data','candidates','incomplete','completed','interim'));  
        
    }

    /**
     * set the session data
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function setSessionData( Request $request)
    {   
        //clear session data 
        Session()->forget('report_id');
        Session()->forget('candidate_id'); 
        Session()->forget('reportType');

        if( is_numeric($request->get('report_id')) ){             
            session()->put('report_id', $request->get('report_id'));
        }
        if( is_numeric($request->get('candidate_id')) ){             
          session()->put('candidate_id', $request->get('candidate_id'));
        }
        // both date is selected 
        if($request->get('reportType') !="" ){
            session()->put('reportType', $request->get('reportType'));
        }

        //store log of report exporting data
        $report_data = DB::table('reports')->select('*')->where(['id'=>base64_decode($request->get('report_id'))])->first(); 

        $data= ['report_id'=>base64_decode($request->get('report_id')),'report_type'=>$request->get('reportType'),'candidate_id'=>$report_data->candidate_id,'created_at'=>date('Y-m-d H:i:s'),'created_by'=>Auth::user()->id];
        DB::table('report_exports')->insert($data);

        echo "1";
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function feedback(Request $request)
    {

        $rules = [
            'rating' => 'required',
            
            ];
    
      
               $validator = Validator::make($request->all(), $rules);
                
               if ($validator->fails()){
                   return response()->json([
                       'success' => false,
                       'errors' => $validator->errors()
                   ]);
               }
      
       
            // dd($business_id);

            $new_feedback = new Feedback();
            $new_feedback->business_id = $request->business_id;
            $new_feedback->candidate_id = $request->candidate_id;
            $new_feedback->report_id =$request->report_id;
            $new_feedback->stars = $request->rating;
            $new_feedback->comments = $request->comments;
            $new_feedback->commented_by = Auth::user()->id;
            $new_feedback->save();

            return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'errors'  =>[]
            ]);
        //   return redirect('my/reports')
        //           ->with('success', '');
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
    public function destroy($id)
    {
        //
    }

    public function export(Request $request)
    {
        $parent_id=Auth::user()->parent_id;
        $business_id=Auth::user()->business_id;
        $user_id=Auth::user()->id;

        $id =$request->report_id;

        // dd($id);
        DB::beginTransaction();
        try
        {
            $reports=DB::table('reports')
            ->whereIn('id',$id)
            ->where('status','<>','incomplete')
            ->get();

            if(count($reports)>0)
            {
                $zipname = 'reports-'.date('Ymdhis').'.zip';
                $zip = new \ZipArchive();      
                $zip->open(public_path().'/zip/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                $report_array=$candidate_array=[];
                foreach($reports as $report)
                {
                    $report_data = DB::table('reports')->select('id','candidate_id','status','verifier_name','verifier_email','verifier_designation','generated_at','created_at','is_manual_mark')->where(['id'=>$report->id])->first(); 

                    // $report_data=DB::table('reports')->where(['id'=>$report_id,'status'=>'incomplete'])->first();
                    // if($report_data!=NULL)
                    // {
                    //     return response()->json([ 
                    //       'success' => false,
                    //       'status' => 'no'
                    //     ]);      
                    // }
                    
                    if($report_data->status=='completed'|| $report_data->status=='interim'){
                        
                        $data = [];
                        $pdf =new PDF;
                        $report_items = DB::table('report_items as ri')
                        ->select('ri.*','s.name as service_name','s.id as service_id','s.type_name')  
                        ->join('services as s','s.id','=','ri.service_id')
                        ->where(['ri.report_id'=>$report->id,'is_report_output'=>'1']) 
                        ->orderBy('s.sort_number','asc')
                        ->orderBy('ri.service_item_order','asc')
                        ->get(); 

                        // get candidate_id
                        // dd($report_data);
                        $candidate = DB::table('candidate_reinitiates as u')
                                    ->select('u.id','u.display_id','u.business_id','u.client_emp_code','u.entity_code','u.first_name','u.last_name','u.name','u.email','u.phone','r.id as report_id','r.created_at','r.approval_status_id','r.sla_id','cs.title as sla_name','u.parent_id','u.gender','u.dob','u.name','r.status as report_status','r.is_report_complete','r.report_complete_created_at','r.is_manual_mark','r.revised_date')  
                                    ->leftjoin('reports as r','r.candidate_id','=','u.id')
                                    ->join('customer_sla as cs','cs.id','=','r.sla_id')
                                    ->where(['r.id'=>$report->id]) 
                                    ->first();

                        $jaf = DB::table('jaf_form_data')->where(['candidate_id'=>$report_data->candidate_id])->first(); 
                        
                        $file_name = "myBCD-Report-".date('d-m-Y').'-'.$report_data->candidate_id.".pdf";

                        // Check for Report File Renaming

                        $customer = DB::table('user_businesses')
                                                ->where(['business_id'=>$candidate->business_id,'is_report_file_config'=>1])
                                                ->whereNotNull('report_file_config_details')
                                                ->first();

                        if($customer!=NULL)
                        {
                            $file_detail = $customer->report_file_config_details;

                            $file_detail_arr = json_decode($file_detail,true);

                            if($file_detail_arr!=NULL && count($file_detail_arr)>0)
                            {
                                $file_name = '';

                                asort($file_detail_arr);

                                $i=0;

                                $count = count($file_detail_arr);

                                foreach($file_detail_arr as $key => $item)
                                {
                                    if(stripos($key,'reference_no')!==false)
                                    {
                                        $file_name.=$candidate->display_id;
                                    }
                                    else if(stripos($key,'emp_code')!==false)
                                    {
                                        if($candidate->client_emp_code!='' && $candidate->client_emp_code!=null)
                                            $file_name.=$candidate->client_emp_code;
                                    }
                                    else if(stripos($key,'candidate_name')!==false)
                                    {
                                        $file_name.=$candidate->name;
                                    }
                                    else if(stripos($key,'status')!==false)
                                    {
                                        $status = '';

                                        if(stripos($candidate->report_status,'interim')!==false)
                                        {
                                            $status = 'Interim Report';
                                        }
                                        else if(stripos($candidate->report_status,'completed')!==false)
                                        {
                                            $status = 'Final Report';
                                        }
                                        
                                        $file_name.=$status;
                                    }
                                    else if(stripos($key,'date')!==false)
                                    {
                                        $file_name.=date('d-F-Y');
                                    }

                                    if(++$i!=$count)
                                    {
                                        $file_name.=' - ';
                                    }
                                }

                                $file_name.='.pdf';
                            }
                        }

                        $pdf = PDF::loadView('clients.candidates.pdf.report', compact('report_data','report_items','data','jaf','candidate','parent_id','business_id','user_id'),[],[
                            'title' => 'Report',
                            'margin_top' => 20,
                            'margin-header'=>20,
                            'margin_bottom' =>25,
                            'margin_footer'=>5,
                            
                        ])->save(public_path()."/pdf/".$file_name); 

                        $path = public_path()."/pdf/".$file_name;
                        // return $pdf->download("report-".$candidate->id.date('d-m-Y').".pdf");
                        $zip->addFile($path, '/reports/'.basename($path));  
                        $report_array[]=$report_data->id;
                        $candidate_array[]=$report_data->candidate_id;
                    }
                }

                $zip->close();
                $path=public_path().'/pdf/';
                if (File::exists($path)) 
                {
                    File::cleanDirectory($path);
                }

                $zip_id=DB::table('zip_logs')->insertGetId([
                    'parent_id' => $parent_id,
                    'business_id'  => $business_id,
                    'user_id'     =>  $user_id,
                    'report_id'   =>  count($report_array)>0?json_encode($report_array):NULL,
                    'candidate_id'  => count($candidate_array)>0?json_encode($candidate_array):NULL,
                    'zip_name' => $zipname!=""?$zipname:NULL,
                    'created_at'    =>  date('Y-m-d H:i:s'),
                    'updated_at'    =>  date('Y-m-d H:i:s')
                ]);
        
        
                $zip_data=DB::table('zip_logs')->where(['id'=>$zip_id])->first();
                $email=Auth::user()->email;
                $name=Auth::user()->name;
                $date=$zip_data->created_at;
                $sender = DB::table('users')->where(['id'=>$parent_id])->first();
                $data  = array('name'=>$name,'email'=>$email,'date' => $date,'zip_id'=>base64_encode($zip_id),'sender'=>$sender);
        
                Mail::send(['html'=>'mails.zip-download'], $data, function($message) use($email,$name) {
                    $message->to($email, $name)->subject
                        ('myBCD System - Zip Download Notification');
                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                });

                    //    echo url('/').'/'.$zipname;

                    $zip_path=public_path().'/zip/'.$zipname;
                    if (File::exists($zip_path)){
                        DB::commit();
                        return response()->json([
                            'success' => true,
                            'email' => $email
                        ]);
                    }
                    
                    return response()->json([
                        'success' => false,
                    ]);
            }
            else
            {
                return response()->json([
                'success' => false,
                'status' => 'no'
                ]);
            }            
            // return response()->json([
            //     'report' => $request->report_id
            // ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }
}
