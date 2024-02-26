<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
Use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
use PDF;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Traits\EmailConfigTrait;
use Illuminate\Support\Facades\Config;
use Imagick;
use App\Models\Admin\JafFormData;
use App\Models\Admin\ReportItem;
use Illuminate\Support\Facades\Validator;
use App\Traits\S3ConfigTrait;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Helper;
use App\Models\Admin\Task;
use App\Models\Admin\TaskAssignment;

class ReportController extends Controller
{
   
      //report list
      public function index(Request $request)
      {
        $user_id = Auth::user()->id;
        $kams = DB::table('key_account_managers')
                    ->select('*')
                    ->where('user_id',$user_id)
                    ->get()->pluck('business_id');

          $business_id = Auth::user()->business_id; 
          $rows=10;
          $data = DB::table('reports as r')
                  ->select('r.*','cl.title','u.phone','u.phone_code','u.phone_iso','u.display_id','u.email') 
                  ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                  ->join('customer_sla as cl','cl.id','=','r.sla_id')
                  ->where(['r.parent_id' => $business_id]);
                  // ->orderBy('r.created_at','desc');
                  
                  if($request->get('report_card_url')){
                    $data->whereIn('u.business_id',$kams);
                  }
                  
                  if(is_numeric($request->get('customer_id'))){
                    $data->where('r.business_id',$request->get('customer_id'));
                  }
                  if(is_numeric($request->get('candidate_id'))){
                    $data->where('r.candidate_id',$request->get('candidate_id'));
                  }
                  if($request->get('from_date') !=""){
                    $data->whereDate('r.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                  }
                  if($request->get('to_date') !=""){
                    $data->whereDate('r.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                  }
                  if($request->get('mob')){
                    $data->where('u.phone',$request->get('mob'));
                  }
                  if($request->get('ref')){
                    $data->where('u.display_id',$request->get('ref'));
                  }
                  if($request->get('email')){
                    $data->where('u.email',$request->get('email'));
                  }
                  if($request->get('r_status')){
                    $data->where('r.status',$request->get('r_status'));
                  }
                  if($request->get('report_status') !=""){
                    $data->where('r.status','=',$request->get('report_status'));
                  }
                  if($request->get('color_code') !=""){
                    //echo($request->get('color_code'));
                    $data->where('r.approval_status_id','=',$request->get('color_code'));
                  }
                  if($request->get('report_status1') !="" || $request->get('report_status2') !=""){
                    $value=$request->get('report_status1').','.$request->get('report_status2');
                    $data->whereIn('r.status',explode(',',$value));
                  }
                  if ($request->get('search')) {
                    // $searchQuery = '%' . $request->search . '%';
                  // echo($request->input('search'));
                    $data->where(DB::raw('CONCAT_WS(" ", u.first_name, u.last_name)'), 'like', '%'.$request->get('search').'%')->orWhere('u.name',$request->get('search'))->orWhere('u.email',$request->get('search'))->orWhere('u.display_id',$request->get('search'))->orWhere('u.phone',$request->get('search'))->orWhere('u.client_emp_code',$request->get('search'));
                  }
                  if($request->get('rows')!='') {
                    $rows = $request->get('rows');
                  }
                  $data->orderBy('r.created_at','desc');
                  // dd($data);
                  $items =    $data->paginate($rows); 
                  
                  $incomplete = $request->get('report_status');
                  $completed=$request->get('report_status1');
                  $interim=$request->get('report_status2');
                  $report_card_url=$request->get('report_card_url');

          $customers = DB::table('users as u')
          ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
          ->join('user_businesses as b','b.business_id','=','u.id')
          ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
          ->get();

          $tota_candidates = $data->count();       
          if ($request->ajax())
            return view('admin.reports.ajax', compact('customers','items','incomplete','completed','interim','tota_candidates','kams','report_card_url'));
          else
            return view('admin.reports.index', compact('customers','items','incomplete','completed','interim','tota_candidates','kams','report_card_url'));   

          // return view('admin.reports.index', compact('data','customers'));
      }
  
      //create report
      public function create(Request $request)
      {
          $business_id = Auth::user()->business_id;

          $customers = DB::table('users as u')
                  ->select('u.id','u.first_name','u.middle_name','u.last_name','b.company_name')
                  ->join('user_businesses as b','b.business_id','=','u.id')
                  ->where(['u.parent_id'=>$business_id])
                  ->get();    

                  return view('admin.reports.create', compact('customers'));
      }


      public function getCandidatesList(Request $request)
      {
          $business_id = $request->input('customer_id');

          $candidates = DB::table('candidate_reinitiates')
                  ->select('id','display_id','first_name','middle_name','last_name','phone','name')
                  ->where(['business_id'=>$business_id,'user_type'=>'candidate'])
                  ->get();
          
          return response()->json([
              'success'   =>true,
              'data'      =>$candidates 
          ]);

      }

      /**
       * Get the candidates.
       *
       * @return \Illuminate\Contracts\Support\Renderable
       */
      public function getCandidatesSlaList(Request $request)
      {
          $business_id = $request->input('customer_id');

          $candidates = DB::table('candidate_reinitiates')
                  ->select('id','first_name','middle_name','last_name','phone')
                  ->where(['business_id'=>$business_id,'user_type'=>'candidate'])
                  ->get();

          $customer_sla = DB::table('customer_sla')
                          ->select('id','title')
                          ->where(['business_id'=>$business_id])
                          ->get();
          
          return response()->json([
              'success'   =>true,
              'data'      =>$candidates,
              'data1'     =>$customer_sla
          ]);

      }

      //show the report details
      public function show(Request $request){
        
          $job_id = $request->id;

          $business_id = Auth::user()->business_id;

          $candidate_details = Db::table('candidate_reinitiates as c')
                            ->select('c.id','j.title','c.business_id','c.name','c.email','c.phone','itm.created_at')  
                            ->leftjoin('job_items as itm','c.id','=','itm.candidate_id')
                            ->leftjoin('jobs AS j','itm.job_id','=','j.id')
                            ->where(['itm.job_id'=>$job_id]) 
                            ->get(); 

          return view('admin.qcs.ajax_job_details', compact('candidate_details'));
      }


      public function store(Request $request){
        // dd($request);
        $business_id = Auth::user()->business_id;

        
        DB::beginTransaction();
        try{
        
            $data = 
              [
                'parent_id'     =>$business_id,
                'business_id'   =>$request->input('customer'),
                'candidate_id'  =>$request->input('candidate'),
                'sla_id'        =>$request->input('sla'),       
                'created_at'    =>date('Y-m-d H:i:s')
              ];
              
              $report_id = DB::table('reports')->insertGetId($data);

              // add service items
              foreach($request->input('services') as $item){

                $get_id_number = $request->input('check_number-'.$item);

                $data = 
                  [
                    'report_id'     =>$report_id,
                    'service_id'    =>$item,
                    'id_number'     =>$get_id_number,
                    'candidate_id'  =>$request->input('candidate'),      
                    'created_at'    =>date('Y-m-d H:i:s')
                  ];
                  
                $report_item_id = DB::table('report_items')->insertGetId($data);
              }

                $files = $request->input('fileID');
                  if($request->has('fileID')){
                    if(count($files) > 0)
                    {
                        foreach ($files as $key => $ids) {  
                          
                          $raw_ids = explode("-",$ids);
                          $service_id = $raw_ids[0];
                          $file_id = $raw_ids[1];
                          
                          // echo $report_id.' -'.$service_id;
                          $report_item_data = $this->get_item_id($report_id, $service_id);

                          $data = [                                             
                            'report_id'     => $report_id,           
                            'created_at'    => date('Y-m-d H:i:s'),
                            'created_by'    => Auth::user()->id,
                          ];                
                                  
                          DB::table('report_item_attachments')
                                          ->where(['id'=>$file_id])
                                          ->update(['report_id'=>$report_id,'report_item_id'=>$report_item_data,'is_temp'=>0]);

                        }
                            
                      }
                  }
                  DB::commit();
                return redirect()
                    ->route('/reports')
                    ->with('success', 'Report Saved Successfully.');
            }
            catch (\Exception $e) {
              DB::rollback();
              // something went wrong
              return $e;
          } 

      }
  
      //generate a report of caniddate 
      public function generateCandidateReport($id)
      {

        $id = base64_decode($id);
        $business_id = Auth::user()->business_id;
        $report_id ="";

        DB::beginTransaction();
        try{
            $job = DB::table('job_items')->where(['candidate_id'=>$id])->first(); 
            $sla_id = $job->sla_id;
            //check report items created or not
            $report_count = DB::table('reports')->where(['candidate_id'=>$id])->count(); 
            if($report_count == 0){
              
              $job = DB::table('job_items')->where(['candidate_id'=>$id])->first(); 
            
              $data = 
                [
                  'parent_id'     =>$business_id,
                  'business_id'   =>$job->business_id,
                  'candidate_id'  =>$id,
                  'sla_id'        =>$job->sla_id,       
                  'created_at'    =>date('Y-m-d H:i:s')
                ];
                
                $report_id = DB::table('reports')->insertGetId($data);
                
                // add service items
                $jaf_items = DB::table('jaf_form_data')->where(['candidate_id'=>$id])->get(); 

                foreach($jaf_items as $item){
                  if ($item->verification_status == 'success') {
                    $data = 
                    [
                      'report_id'     =>$report_id,
                      'service_id'    =>$item->service_id,
                      'service_item_number'=>$item->check_item_number,
                      'candidate_id'  =>$id,      
                      'jaf_data'      =>$item->form_data,
                      'jaf_id'        =>$item->id,
                      'created_at'    =>date('Y-m-d H:i:s')
                    ];
                  } else {
                    $data = 
                    [
                      'report_id'     =>$report_id,
                      'service_id'    =>$item->service_id,
                      'service_item_number'=>$item->check_item_number,
                      'candidate_id'  =>$id,      
                      'jaf_data'      =>$item->form_data,
                      'jaf_id'        =>$item->id,
                      'is_report_output' => '0',
                      'created_at'    =>date('Y-m-d H:i:s')
                    ]; 
                  }
                  
                
                    
                  $report_item_id = DB::table('report_items')->insertGetId($data);
                }

                DB::commit();
            }
            
              $report = DB::table('reports')->where(['candidate_id'=>$id])->first(); 
              $report_id = $report->id;
              $report_status = $report->status;

              $candidate = [];
              $report_items = [];
              $candidate =  DB::table('candidate_reinitiates as u')
                                ->select('u.id','u.business_id','u.first_name','u.middle_name','u.last_name','u.name','u.email','u.phone','u.phone_code','r.created_at')  
                                ->leftjoin('reports as r','r.candidate_id','=','u.id')
                                ->where(['u.id'=>$id]) 
                                ->first(); 
              
              $report_items = DB::table('report_items as ri')
                                ->select('ri.*','s.name as service_name','s.id as service_id','s.type_name')  
                                ->join('services as s','s.id','=','ri.service_id')
                                ->where(['ri.report_id'=>$report_id]) 
                                ->orderBy('s.sort_number','asc')
                                ->orderBy('ri.service_item_order','asc')
                                ->get(); 
              // dd($report_items);
                  //get JAF data - 
                  $jaf = DB::table('jaf_form_data')->select('form_data_all')->where(['candidate_id'=>$id])->first(); 

                  $status_list = DB::table('report_status_masters')->get();             

                  return view('admin.reports.candidate-generate-report', compact('candidate','report_items','jaf','report_id','report_status','sla_id','status_list'));
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
      }

      //report to be complete
      public function outputProcessSave(Request $request){
        // dd($request);
        $business_id = Auth::user()->business_id;
        $report_id = base64_decode($request->input('report_id'));

          if ($request->type == 'formtype') {
            $incomplete = DB::table('reports')->select('candidate_id','business_id','parent_id','candidate_id')->where(['id'=>$report_id,'status'=>'incomplete'])->first();
            if($incomplete)
            {
              $report_page_status=DB::table('report_add_page_statuses')->where(['coc_id' => $incomplete->business_id,'status'=>'enable'])->first();
              //get report items
              $report_items = DB::table('report_items as ri')
                                ->select('ri.*','r.business_id')
                                ->join('reports as r','r.id','=','ri.report_id')
                                ->where(['ri.report_id'=>$report_id])
                                ->get();
              // echo "<pre>";
              // print_r($report_items); die;

              // dd(1);

              $i = 0;
              $test_date= NULL;
              foreach($report_items as $item){

                  //update report
                  $verified_by  = $request->input('verified_by-'.$item->id);
                  $annexure_value  = $request->input('annexure_value-'.$item->id);
                  $comments     = $request->input('comments-'.$item->id);
                  $additional_comments  = $request->input('additional-comments-'.$item->id);
                  $status_id            = $request->input('approval-status-'.$item->id);
                  // dd($status_id);
                  $district_court_name  = $request->input('district_court_name-'.$item->id);
                  $district_court_result  = $request->input('district_court_result-'.$item->id);
                  $high_court_name      = $request->input('high_court_name-'.$item->id);
                  $high_court_result    = $request->input('high_court_result-'.$item->id);
                  $supreme_court_name   = $request->input('supreme_court_name-'.$item->id);
                  $supreme_court_result = $request->input('supreme_court_result-'.$item->id);
                  $test_date  = $request->input('test_date-'.$item->id);
                  
                  $verified_data = $request->input('verified-input-checkbox-'.$item->id);

                  $report_output ='0';
                  if($request->has('report-output-'.$item->id)){
                    $report_output     = '1';
                  }
                  
                  $input_items = DB::table('service_form_inputs as sfi')
                                  ->select('sfi.*')            
                                  ->where(['sfi.service_id'=>$item->service_id,'status'=>1])
                                  ->whereNull('sfi.reference_type')
                                  ->whereNotIn('label_name',['Mode of Verification','Remarks'])
                                  ->get();

                    //dd($input_items);
                    $input_data = [];
                    $j=0;
                    $reference_type = NULL;
                    foreach($input_items as $input){

                      $remarks     = '-';
                      if($request->has('remarks-input-checkbox-'.$item->id.'-'.$j)){
                        $remarks     = 'Yes';
                      }

                      if($input->service_id==17)
                      {
                            $input_data[] = [
                              $request->input('service-input-label-'.$item->id.'-'.$j)=>$request->input('service-input-value-'.$item->id.'-'.$j),
                              'remarks'=>$remarks,
                              'is_report_output'=>$input->is_report_output 
                            ];
                            // dd($request->input('service-input-label-'.$service->id.'-'.'2'));
                            if(stripos($request->input('service-input-label-'.$item->id.'-'.$j),'Reference Type (Personal / Professional)')!==false)
                            {
                                $reference_type = $request->input('service-input-value-'.$item->id.'-'.$j);
                            }

                      }
                      else if($input->service_id==10){
                        if($input->label_name=='Date of Joining (Employee Tenure)' || $input->label_name=='Date of Relieving (Employee Tenure)')
                        {
                            if($request->has('service-input-value-'.$item->id.'-'.$j))
                            {
                              $input_data[] = [
                                  $request->input('service-input-label-'.$item->id.'-'.$j)=>$request->input('service-input-value-'.$item->id.'-'.$j),
                                  'is_report_output'=>$input->is_report_output,
                                  'input_type' => 'date' 
                              ];
                            }
                            else
                            {
                                $in_type = '';
                                $val = null;
                                if($request->input('service-input-value-'.$item->id.'-'.$j.'-2')!=null)
                                {
                                    $in_type = 'string';
                                    $val = $request->input('service-input-value-'.$item->id.'-'.$j.'-2');
                                }
                                else
                                {
                                  $in_type = 'date';
                                  $val = $request->input('service-input-value-'.$item->id.'-'.$j.'-1');
                                }

                                $input_data[] = [
                                    $request->input('service-input-label-'.$item->id.'-'.$j)=>$val,
                                    'is_report_output'=>$input->is_report_output,
                                    'input_type' => $in_type 
                                ];
                            }
                            
                        }
                        else{
                          $input_data[] = [
                            $request->input('service-input-label-'.$item->id.'-'.$j)=>$request->input('service-input-value-'.$item->id.'-'.$j),
                            'is_report_output'=>$input->is_report_output 
                          ];
                        }
                    }
                      else
                      {
                        $input_data[] = [
                          $request->input('service-input-label-'.$item->id.'-'.$j)=>$request->input('service-input-value-'.$item->id.'-'.$j),
                          'remarks'=>$remarks,
                          // 'is_report_output'=>$report_output,
                          'is_report_output'=>$input->is_report_output,
                        ];
                      }
                        $j++;
                    }

                    // dd($reference_type);

                    $jaf_data = json_encode($input_data); 

                    $reference_jaf_data = NULL;
                    if($item->service_id==17)
                    {
                        $reference_input_data=[];
                        $l=0;

                        $reference_input_items = DB::table('service_form_inputs as sfi')
                              ->select('sfi.*')            
                              ->where(['sfi.service_id'=>$item->service_id,'status'=>1,'reference_type'=>$reference_type])
                              ->orWhereIn('label_name',['Mode of Verification','Remarks'])
                              ->orderBy('reference_type','desc')
                              ->get();

                        // dd($reference_input_items);
                        if(($reference_type!='' && $reference_type!=NULL) && count($reference_input_items)>0)
                        {
                          foreach($reference_input_items as $ref_input)
                          {
                            $reference_input_data[] = [
                              $request->input('reference-input-label-'.$item->id.'-'.$l)=>$request->input('reference-input-value-'.$item->id.'-'.$l),
                              'is_report_output'=>$input->is_report_output 
                            ];
                            $l++;
                          }
  
                          $reference_jaf_data = json_encode($reference_input_data);
                        }
                    }

                    // dd($jaf_data);
                    $insuf_notes = NULL;
                    if($request->has('insuf_notes-'.$item->id)){
                      // dd($request->has('insuf_notes-'.$item->id);
                      $insuf_notes     = $request->input('insuf_notes-'.$item->id);
                    }

                    if ($report_page_status) {
                      $service = DB::table('services')->select('name')->where(['id'=>$item->service_id,'name'=>'Address'])->first();
                      if ($service) {
                        $additional_data =null;
                        // dd($report_id);
                        $additional_data = DB::table('additional_address_verifications')->where(['report_item_id'=>$item->id,'candidate_id'=>$incomplete->candidate_id])->first();
                        
                        if ($additional_data==null) {
                      
                      
                          $contact_person_name =$request->input('contact_person_name-'.$item->id);
                          $contact_person_no=$request->input('contact_person_no-'.$item->id);
                          $relation_with_associate=$request->input('relation_with_associate-'.$item->id);
                          $residence_status=$request->input('residence_status-'.$item->id);
                          $locality=$request->input('locality-'.$item->id);
                          $verification_mode=$request->input('verification_mode-'.$item->id);
                          $additional_remarks=$request->input('additional_remark-'.$item->id);
                          $additional_address_comment=$request->input('additional_verification_comments-'.$item->id);
                          $additional_verified_by=$request->input('Additional_verified_by-'.$item->id);
            
            
                            $additional = 
                            [
                              'parent_id'     =>$incomplete->parent_id,
                              'business_id'   =>$incomplete->business_id,
                              'candidate_id'  =>$incomplete->candidate_id,
                              'report_item_id' =>$item->id, 
                              'contact_person_name' =>  $contact_person_name, 
                              'contact_contact_no' => $contact_person_no,
                              'relation_with_associate'=>$relation_with_associate,
                              'residence_status'=>$residence_status,
                              'locality'  => $locality,
                              'mode_of_verification'=> $verification_mode,
                              'comments' => $additional_address_comment,
                              'remarks' => $additional_remarks,
                              'verified_by'=>$additional_verified_by,
                              'created_by'=>Auth::user()->id,
                              'created_at'    =>date('Y-m-d H:i:s')
                            ];
                            
                            DB::table('additional_address_verifications')->insertGetId($additional);
                        }
                      }
                      
                    }
                  $is_updated = DB::table('report_items')
                                ->where(['report_id'=>$report_id,'id'=>$item->id])
                                ->update(['jaf_data'=>$jaf_data,
                                          'verified_by'=>$verified_by,
                                          'test_date' => $test_date!=NULL ? date('Y-m-d',strtotime($test_date)) : NULL,
                                          'annexure_value'=>$annexure_value,
                                          'comments'=>$comments,
                                          'additional_comments'=>$additional_comments,
                                          'report_insufficiency_notes'=>$insuf_notes,
                                          'is_report_output'  =>$report_output,
                                          'approval_status_id'=>$status_id,
                                          'district_court_name'=>$district_court_name,
                                          'district_court_result'=>$district_court_result,
                                          'high_court_name'=>$high_court_name,
                                          'high_court_result'=>$high_court_result,
                                          'supreme_court_name'=>$supreme_court_name,
                                          'supreme_court_result'=>$supreme_court_result,
                                          'reference_type' => $reference_type!='' && $reference_type!=NULL ? $reference_type : NULL,
                                          'reference_form_data' => $reference_jaf_data!=NULL ? $reference_jaf_data : NULL,
                                          ]
                                        );

                            $check_is_verified = JafFormData::find($item->jaf_id);
                            // var_dump('(/' .$check_is_verified->is_data_verified.'/'.$verified_data.')');
                            if ($check_is_verified) {
                              if ($check_is_verified->is_data_verified=='0' &&  $verified_data == '1') {
                                
                                $check_is_verified->update(['is_data_verified'=>'1','data_verified_date'=> date('Y-m-d H:i:s'),'verified_data_submitted_by'=>Auth::user()->id]);
                              }
                            
                            }

                            $check_is_verified = ReportItem::find($item->id);
                            // var_dump('(/' .$check_is_verified->is_data_verified.'/'.$verified_data.')');
                            if ($check_is_verified) {
                              if ($check_is_verified->is_data_verified=='0' &&  $verified_data == true) {
                                
                                $check_is_verified->update(['is_data_verified'=>'1','data_verified_date'=> date('Y-m-d H:i:s'),'verified_data_submitted_by'=>Auth::user()->id]);
                              }
                            
                            }

                              // update jaf form data 
                              //  DB::table('jaf_form_data')
                              //  ->where(['candidate_id'=>$candidate->candidate_id,'service_id'=>$item->service_id])
                              //  ->update(['verification_status'=>'success']);
                  $i++;
              } 
    
              // $task = Task::where(['business_id'=>$incomplete->business_id,'candidate_id'=>$incomplete->candidate_id,'is_completed'=>0,'description'=>'Report generation'])->first();
              // $task_id='';
              // if ($task) {
              //   # code...
              //   $task_id = $task->id;

              //     $task_update = Task::find($task_id);
              //     $task_update->update(['is_completed'=> 1]);
              //     // $task->save();
              
              //   //Change status of old task 
              //   $task_assgn = TaskAssignment::where(['business_id'=>$incomplete->business_id,'candidate_id'=>$incomplete->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
              //   // dd($task_assgn);
              //   if($task_assgn)
              //   {
              //     $task_assign_update = TaskAssignment::find($task_assgn->id);
              //     $task_assign_update->update(['status'=> '2']);
              //     // $task_assgn->status= '2';
              //     // $task_assgn->save();
              //   }
              // }

              //color status
              // $approval_status_id= NULL;
              // $report_item_data = DB::table('report_items')->where(['report_id'=>$report_id])->whereNotNull('approval_status_id')->orderBy('approval_status_id','asc')->first();
              
              // if($report_item_data !=null){
              //   $approval_status_id= $report_item_data->approval_status_id;
              // }
              return response()->json([
                'success' =>true,
                'custom'  =>'pending',
                'errors'  =>[]
              ]);

            }else {

              //color status
              $approval_status_id= NULL;
              $report_item_data = DB::table('report_items')->where(['report_id'=>$report_id])->whereNotNull('approval_status_id')->orderBy('approval_status_id','asc')->first();
              
              if($report_item_data !=null){
                $approval_status_id= $report_item_data->approval_status_id;
              }
              // dd($report_item_data);
              //update report status

              // Check the Mark Color Code Manually Or Not

              $is_manual_mark = 0;

              if($request->input('manual_check')!=null)
              {
                $is_manual_mark = $request->input('manual_check');
              }

              // //report update
              // $jaf_items_failed = DB::table('jaf_form_data')->where(['candidate_id'=>$report_item_data->candidate_id])->whereIn('verification_status',['failed',NULL])->get(); 
              // // dd($jaf_items_failed);
              // if (count($jaf_items_failed)>0) {
              //   //update report status
              //   DB::table('reports')
              //   ->where(['id'=>$report_id])
              //   ->update(['report_type'=>'auto','is_verified'=>'1','status'=>'interim','manual_input_status'=>'completed','approval_status_id'=>$approval_status_id,'report_jaf_data'=>NULL,'created_by'=>Auth::user()->id,'generated_by'=>Auth::user()->id,'generated_at'=>date('Y-m-d H:i:s')]);
              
              // } else {
                    
              //   //update report status
              //   DB::table('reports')
              //   ->where(['id'=>$report_id])
              //   ->update(['report_type'=>'auto','is_verified'=>'1','status'=>'completed','manual_input_status'=>'completed','approval_status_id'=>$approval_status_id,'report_jaf_data'=>NULL,'created_by'=>Auth::user()->id,'generated_by'=>Auth::user()->id,'generated_at'=>date('Y-m-d H:i:s')]);
            
              // }
              return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'errors'  =>[]
              ]);

            }
            // dd($incomplete);
          }else {
          
           
            // $user_id = Auth::user()->id;
            // dd($report_id);
            // DB::beginTransaction();
            // try{

            $candidate = DB::table('reports')->select('candidate_id','business_id','parent_id','candidate_id')->where(['id'=>$report_id])->first();
            $report_page_status=DB::table('report_add_page_statuses')->where(['coc_id' => $candidate->business_id,'status'=>'enable'])->first();
            //get report items
            $report_items = DB::table('report_items')->where(['report_id'=>$report_id])->get();
            // echo "<pre>";
            // print_r($report_items); die;

             // Validation
             foreach($report_items as $service)
             {
               $ref_type = '';
               //  dd($request->input('service-input-value-'.$service->id.'-2'));
                 // if($service->service_id=='17')
                 // {
                 //   $i=2;
                 //   $this->validate($request,[
                 //       'service'.'-'.'input'.'-'.'value'.'-'.$service->id.'-'.$i => 'required',
                 //     ]
                 //   );

                 // }

                 // dd(1);

                 $input_items = DB::table('service_form_inputs as sfi')
                         ->select('sfi.*')            
                         ->where(['sfi.service_id'=>$service->service_id,'status'=>1])
                         ->whereNull('sfi.reference_type')
                         ->whereNotIn('label_name',['Mode of Verification','Remarks'])
                         ->get();
                 $j=0;
                 foreach($input_items as $item)
                 {
                   $name=$item->label_name;
                   if($service->service_id == 17 && stripos($request->input('service-input-label-'.$service->id.'-'.$j),'Reference Type (Personal / Professional)')!==false)
                   {
                       $ref_type = $request->input('service-input-value-'.$service->id.'-'.$j);
                   }
                   
                   $check_control_masters=DB::table('check_control_masters')->where(['check_control_coc_id'=>$candidate->business_id,'service_input_id'=>$item->id])->first();

                   if($check_control_masters!=NULL){
                     $rules=[
                       'service-input-value-'.$service->id.'-'.$j=> 'required',
                     ];

                     $custom=[
                       'service-input-value-'.$service->id.'-'.$j.'.required' => $name.' Field is required'
                     ];

                     $validator = Validator::make($request->all(), $rules,$custom);
                 
                     if ($validator->fails()){
                         return response()->json([
                             'success' => false,
                             'errors' => $validator->errors(),
                             'error_type'=>'validation'
                         ]);
                     }
                   }

                   $j++;
                 }

                 if($service->service_id=='17')
                 {
                     // $i=2;
                     // $rules=[
                     //   'service-input-value-'.$service->id.'-'.$i => 'required|in:personal,professional',
                     // ];

                     // $custom = [
                     //   'service-input-value-'.$service->id.'-'.$i.'.required' => 'Reference Type Field is Required',
                     //   'service-input-value-'.$service->id.'-'.$i.'.in' => 'Reference Type Must be personal / professional',
                     // ];
               
                     // $validator = Validator::make($request->all(), $rules, $custom);
                 
                     // if ($validator->fails()){
                     //       return response()->json([
                     //           'success' => false,
                     //           'errors' => $validator->errors(),
                     //           'error_type'=>'validation'
                     //       ]);
                     // }

                     // Check for Reference type Validation

                     $ref_input_items = DB::table('service_form_inputs')
                     ->where(['service_id'=>$service->service_id,'reference_type'=>$ref_type,'status'=>1])
                     ->orWhereIn('label_name',['Mode of Verification','Remarks'])
                     ->orderBy('reference_type','desc')
                     ->get();

                     if(($ref_type!='' && $ref_type!=NULL) && count($ref_input_items)>0)
                     {
                         $j=0;
                         foreach($ref_input_items as $item)
                         {
                             $name=str_replace('?','',$item->label_name);
                             $check_control_masters=DB::table('check_control_masters')->where(['check_control_coc_id'=>$candidate->business_id,'service_input_id'=>$item->id])->first();

                             if($check_control_masters!=NULL){
                                 $rules=[
                                   'reference-input-value-'.$service->id.'-'.$j=> 'sometimes|required',
                                 ];

                                 $custom=[
                                   'reference-input-value-'.$service->id.'-'.$j.'.required' => $name.' field is required'
                                 ];

                                 $validator = Validator::make($request->all(), $rules,$custom);
                             
                                 if ($validator->fails()){
                                     return response()->json([
                                         'success' => false,
                                         'errors' => $validator->errors(),
                                         'error_type'=>'validation'
                                     ]);
                                 }
                             }

                             $j++;
                         }
                     }
                 }

             }

            // dd(1);

            $i = 0;
            foreach($report_items as $item){

                //update report
                $verified_by  = $request->input('verified_by-'.$item->id);
                $annexure_value  = $request->input('annexure_value-'.$item->id);
                $comments     = $request->input('comments-'.$item->id);
                $additional_comments  = $request->input('additional-comments-'.$item->id);
                $status_id            = $request->input('approval-status-'.$item->id);
                // dd($status_id);
                $district_court_name  = $request->input('district_court_name-'.$item->id);
                $district_court_result  = $request->input('district_court_result-'.$item->id);
                $high_court_name      = $request->input('high_court_name-'.$item->id);
                $high_court_result    = $request->input('high_court_result-'.$item->id);
                $supreme_court_name   = $request->input('supreme_court_name-'.$item->id);
                $supreme_court_result = $request->input('supreme_court_result-'.$item->id);
                $test_date  = $request->input('test_date-'.$item->id);
                
                $verified_data = $request->input('verified-input-checkbox-'.$item->id);

                $report_output ='0';
                if($request->has('report-output-'.$item->id)){
                  $report_output     = '1';
                }

                
                
                $input_items = DB::table('service_form_inputs as sfi')
                                ->select('sfi.*','s.type_name')            
                                ->where(['sfi.service_id'=>$item->service_id,'sfi.status'=>1])
                                ->join('services as s','s.id','=','sfi.service_id')
                                ->whereNull('sfi.reference_type')
                                ->whereNotIn('label_name',['Mode of Verification','Remarks'])
                                ->get();

                  //dd($input_items);
                  $input_data = [];
                  $j=0;
                  $reference_type = NULL;
                  foreach($input_items as $input){

                    $remarks     = '-';
                    if($request->has('remarks-input-checkbox-'.$item->id.'-'.$j)){
                      $remarks     = 'Yes';
                    }

                    if($input->service_id==17)
                    {
                          $input_data[] = [
                            $request->input('service-input-label-'.$item->id.'-'.$j)=>$request->input('service-input-value-'.$item->id.'-'.$j),
                            'remarks'=>$remarks,
                            'is_report_output'=>$input->is_report_output 
                          ];
                          // dd($request->input('service-input-label-'.$service->id.'-'.'2'));
                          if(stripos($request->input('service-input-label-'.$item->id.'-'.$j),'Reference Type (Personal / Professional)')!==false)
                          {
                              $reference_type = $request->input('service-input-value-'.$item->id.'-'.$j);
                          }

                    }
                    else if($input->service_id==10){
                      if($input->label_name=='Date of Joining (Employee Tenure)' || $input->label_name=='Date of Relieving (Employee Tenure)')
                      {
                          if($request->has('service-input-value-'.$item->id.'-'.$j))
                          {
                            $input_data[] = [
                                $request->input('service-input-label-'.$item->id.'-'.$j)=>$request->input('service-input-value-'.$item->id.'-'.$j),
                                'is_report_output'=>$input->is_report_output,
                                'input_type' => 'date' 
                            ];
                          }
                          else
                          {
                              $in_type = '';
                              $val = null;
                              if($request->input('service-input-value-'.$item->id.'-'.$j.'-2')!=null)
                              {
                                  $in_type = 'string';
                                  $val = $request->input('service-input-value-'.$item->id.'-'.$j.'-2');
                              }
                              else
                              {
                                $in_type = 'date';
                                $val = $request->input('service-input-value-'.$item->id.'-'.$j.'-1');
                              }

                              $input_data[] = [
                                  $request->input('service-input-label-'.$item->id.'-'.$j)=>$val,
                                  'is_report_output'=>$input->is_report_output,
                                  'input_type' => $in_type 
                              ];
                          }
                          
                      }
                      else{
                        $input_data[] = [
                          $request->input('service-input-label-'.$item->id.'-'.$j)=>$request->input('service-input-value-'.$item->id.'-'.$j),
                          'is_report_output'=>$input->is_report_output 
                        ];
                      }
                    }
                    else if($input->type_name=='vital_4')
                    {
                    
                      if($input->label_name == 'Vital Categoryes')
                      {
                          if($request->has('service-input-value-'.$service->id.'-'.$i))
                          {
                            $vital_cate_selected = $request->input('service-input-value-'.$service->id.'-'.$i);
                            
                            $key = array_search("all", $vital_cate_selected);

                            if ($key !== false) {
                                unset($vital_cate_selected[$key]);
                            }
                            // dd($vital_cate_selected);
                            $slected_value = implode(',',$vital_cate_selected);
                            
                            $input_data[] = [
                                $request->input('service-input-label-'.$service->id.'-'.$i)=>$slected_value,
                                'is_report_output'=>$input->is_report_output 
                            ];
                          }
                      }
                      else{
                        $input_data[] = [
                          $request->input('service-input-label-'.$service->id.'-'.$i)=>$request->input('service-input-value-'.$service->id.'-'.$i),
                          'is_report_output'=>$input->is_report_output 
                        ];
                      }
                    }
                    else
                    {
                      $input_data[] = [
                        $request->input('service-input-label-'.$item->id.'-'.$j)=>$request->input('service-input-value-'.$item->id.'-'.$j),
                        'remarks'=>$remarks,
                        // 'is_report_output'=>$report_output,
                        'is_report_output'=>$input->is_report_output,
                      ];


                    }
                      $j++;
                  }

                  // dd($reference_type);

                  $jaf_data = json_encode($input_data); 

                  $reference_jaf_data = NULL;
                  if($item->service_id==17)
                  {
                      $reference_input_data=[];
                      $l=0;

                      $reference_input_items = DB::table('service_form_inputs as sfi')
                            ->select('sfi.*')            
                            ->where(['sfi.service_id'=>$item->service_id,'status'=>1,'reference_type'=>$reference_type])
                            ->orWhereIn('label_name',['Mode of Verification','Remarks'])
                            ->orderBy('reference_type','desc')
                            ->get();

                      // dd($reference_input_items);
                      if(($reference_type!='' && $reference_type!=NULL) && count($reference_input_items)>0)
                      {
                        foreach($reference_input_items as $ref_input)
                        {
                          $reference_input_data[] = [
                            $request->input('reference-input-label-'.$item->id.'-'.$l)=>$request->input('reference-input-value-'.$item->id.'-'.$l),
                            'is_report_output'=>$input->is_report_output 
                          ];
                          $l++;
                        }
  
                        $reference_jaf_data = json_encode($reference_input_data);
                      }
                  }

                  // dd($jaf_data);
                  $insuf_notes = NULL;
                  if($request->has('insuf_notes-'.$item->id)){
                    // dd($request->has('insuf_notes-'.$item->id);
                    $insuf_notes     = $request->input('insuf_notes-'.$item->id);
                  }

                  if ($report_page_status) {
                    $service = DB::table('services')->select('name')->where(['id'=>$item->service_id,'name'=>'Address'])->first();
                    if ($service) {
                      $additional_data =null;
                      // dd($report_id);
                      $additional_data = DB::table('additional_address_verifications')->where(['report_item_id'=>$item->id,'candidate_id'=>$candidate->candidate_id])->first();
                      
                      if ($additional_data==null) {
                    
                    
                        $contact_person_name =$request->input('contact_person_name-'.$item->id);
                        $contact_person_no=$request->input('contact_person_no-'.$item->id);
                        $relation_with_associate=$request->input('relation_with_associate-'.$item->id);
                        $residence_status=$request->input('residence_status-'.$item->id);
                        $locality=$request->input('locality-'.$item->id);
                        $verification_mode=$request->input('verification_mode-'.$item->id);
                        $additional_remarks=$request->input('additional_remark-'.$item->id);
                        $additional_address_comment=$request->input('additional_verification_comments-'.$item->id);
                        $additional_verified_by=$request->input('Additional_verified_by-'.$item->id);
          
          
                          $additional = 
                          [
                            'parent_id'     =>$candidate->parent_id,
                            'business_id'   =>$candidate->business_id,
                            'candidate_id'  =>$candidate->candidate_id,
                            'report_item_id' =>$item->id, 
                            'contact_person_name' =>  $contact_person_name, 
                            'contact_contact_no' => $contact_person_no,
                            'relation_with_associate'=>$relation_with_associate,
                            'residence_status'=>$residence_status,
                            'locality'  => $locality,
                            'mode_of_verification'=> $verification_mode,
                            'comments' => $additional_address_comment,
                            'remarks' => $additional_remarks,
                            'verified_by'=>$additional_verified_by,
                            'created_by'=>Auth::user()->id,
                            'created_at'    =>date('Y-m-d H:i:s')
                          ];
                          
                          DB::table('additional_address_verifications')->insertGetId($additional);
                      }
                    }
                    
                  }
                $is_updated = DB::table('report_items')
                              ->where(['report_id'=>$report_id,'id'=>$item->id])
                              ->update(['jaf_data'=>$jaf_data,
                                        'verified_by'=>$verified_by,
                                        'test_date' => $test_date!=NULL ? date('Y-m-d',strtotime($test_date)) : NULL,
                                        'annexure_value'=>$annexure_value,
                                        'comments'=>$comments,
                                        'additional_comments'=>$additional_comments,
                                        'report_insufficiency_notes'=>$insuf_notes,
                                        'is_report_output'  =>$report_output,
                                        'approval_status_id'=>$status_id,
                                        'district_court_name'=>$district_court_name,
                                        'district_court_result'=>$district_court_result,
                                        'high_court_name'=>$high_court_name,
                                        'high_court_result'=>$high_court_result,
                                        'supreme_court_name'=>$supreme_court_name,
                                        'supreme_court_result'=>$supreme_court_result,
                                        'reference_type' => $reference_type!='' && $reference_type!=NULL ? $reference_type : NULL,
                                        'reference_form_data' => $reference_jaf_data!=NULL ? $reference_jaf_data : NULL,
                                        ]
                                      );

                          $check_is_verified = JafFormData::find($item->jaf_id);
                          // var_dump('(/' .$check_is_verified->is_data_verified.'/'.$verified_data.')');
                          if ($check_is_verified) {
                            if ($check_is_verified->is_data_verified=='0' &&  $verified_data == '1') {
                              
                              $check_is_verified->update(['is_data_verified'=>'1','data_verified_date'=> date('Y-m-d H:i:s'),'verified_data_submitted_by'=>Auth::user()->id]);
                            }
                          
                          }

                          $check_is_verified = ReportItem::find($item->id);
                          // var_dump('(/' .$check_is_verified->is_data_verified.'/'.$verified_data.')');
                          if ($check_is_verified) {
                            if ($check_is_verified->is_data_verified=='0' &&  $verified_data == true) {
                              
                              $check_is_verified->update(['is_data_verified'=>'1','data_verified_date'=> date('Y-m-d H:i:s'),'verified_data_submitted_by'=>Auth::user()->id]);
                            }
                          
                          }

                           $approval_status_id = DB::table('report_items')->where(['report_id'=>$report_id,'id'=>$item->id])->first();
                        
                            if($approval_status_id->approval_status_id !=null){
                              $is_data_verified = ReportItem::where(['id'=>$item->id,'is_data_verified'=>'1'])->first();
                             
                              if($is_data_verified!=null)
                              {
                                  $task = Task::where(['business_id'=>$candidate->business_id,'candidate_id'=>$candidate->candidate_id,'is_completed'=>0,'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_item_number])->first();
                                
                                  if ($task) {
                                    $task->is_completed= 1;
                                    $task->updated_at = date('Y-m-d H:i:s');
                                    $task->save();
                                  }
                                  //Change status of old task 
                                  $task_assgn = TaskAssignment::where(['business_id'=>$candidate->business_id,'candidate_id'=>$candidate->candidate_id,'status'=>"1",'service_id'=>$item->service_id,'number_of_verifications'=>$item->service_item_number])->first();
                                  // dd($task_assgn);
                                  if ($task_assgn) {
                                    
                                    $task_assgn->status= '2';
                                    $task_assgn->updated_at =date('Y-m-d H:i:s');
                                    $task_assgn->save();
                                  }
                              }
                              
                            }
                            // update jaf form data 
                            //  DB::table('jaf_form_data')
                            //  ->where(['candidate_id'=>$candidate->candidate_id,'service_id'=>$item->service_id])
                            //  ->update(['verification_status'=>'success']);
                $i++;
            } 

            $task = Task::where(['business_id'=>$candidate->business_id,'candidate_id'=>$candidate->candidate_id,'is_completed'=>0,'description'=>'Report generation'])->first();
            $task_id='';
            if ($task) {
              # code...
              $task_id = $task->id;

                $task_update = Task::find($task_id);
                $task_update->update(['is_completed'=> 1]);
                // $task->save();
            
              //Change status of old task 
              $task_assgn = TaskAssignment::where(['business_id'=>$candidate->business_id,'candidate_id'=>$candidate->candidate_id,'status'=>"1",'task_id'=>$task_id])->first();
              // dd($task_assgn);
              if($task_assgn)
              {
                $task_assign_update = TaskAssignment::find($task_assgn->id);
                $task_assign_update->update(['status'=> '2']);
                // $task_assgn->status= '2';
                // $task_assgn->save();
              }
            }
            //color status
            $approval_status_id= NULL;
            $report_item_data = DB::table('report_items')->where(['report_id'=>$report_id])->whereNotNull('approval_status_id')->orderBy('approval_status_id','asc')->first();
            
            if($report_item_data !=null){
              $approval_status_id= $report_item_data->approval_status_id;
            }
            // dd($report_item_data);
            //update report status

            // Check the Mark Color Code Manually Or Not

            $is_manual_mark = 0;

            if($request->input('manual_check')!=null)
            {
              $is_manual_mark = $request->input('manual_check');
            }

            //report update
            $jaf_items_failed = DB::table('jaf_form_data')->where(['candidate_id'=>$report_item_data->candidate_id])->whereIn('verification_status',['failed',NULL])->get(); 
            // dd($jaf_items_failed);
            if (count($jaf_items_failed)>0) {
            //update report status
              DB::table('reports')
              ->where(['id'=>$report_id])
              ->update(['report_type'=>'auto','is_verified'=>'1','status'=>'interim','manual_input_status'=>'completed','approval_status_id'=>$approval_status_id,'report_jaf_data'=>NULL,'created_by'=>Auth::user()->id,'generated_by'=>Auth::user()->id,'generated_at'=>date('Y-m-d H:i:s')]);
            
            } else {
                  
              //update report status
              DB::table('reports')
              ->where(['id'=>$report_id])
              ->update(['report_type'=>'auto','is_verified'=>'1','status'=>'completed','manual_input_status'=>'completed','approval_status_id'=>$approval_status_id,'report_jaf_data'=>NULL,'created_by'=>Auth::user()->id,'generated_by'=>Auth::user()->id,'generated_at'=>date('Y-m-d H:i:s')]);
          
            }

            if($is_manual_mark==1 || $is_manual_mark==2)
            {
              DB::table('reports')
              ->where(['id'=>$report_id])
              ->update(['is_manual_mark'=>$is_manual_mark,'manual_mark_created_by'=>Auth::user()->id,'manual_mark_created_at'=>date('Y-m-d H:i:s')]);
            }

            // Notification for Insufficiency Exist

            $jaf_form_insuff = DB::table('jaf_form_data as j')
                                ->where('j.candidate_id',$candidate->candidate_id)
                                ->where('j.is_insufficiency',1)
                                ->get();

            $notification_control = DB::table('notification_control_configs as nc')
                                  ->select('nc.*')
                                  ->join('notification_controls as n','n.business_id','=','nc.business_id')
                                  ->where(['n.business_id'=>$candidate->business_id,'n.type'=>'insuff-report-generate','nc.type'=>'insuff-report-generate','n.status'=>1,'nc.status'=>1])
                                  ->get();

            if(count($jaf_form_insuff)>0 && count($notification_control)>0)
            {
              $jaf_id = [];

              $jaf_id = $jaf_form_insuff->pluck('id')->all();

              $client = DB::table('users')->where('id',$candidate->business_id)->first();

              $email = $client->email;
              $name = $client->first_name;
              $msg= "Insufficiency Raised For Candidate";
              $sender = DB::table('users')->where(['id'=>$business_id])->first();
              $candidates = DB::table('candidate_reinitiates')->where('id',$candidate->candidate_id)->first();
              $data = array('name'=>$name,'email'=>$email,'msg'=>$msg,'sender'=>$sender,'candidate'=>$candidates,'jaf_id'=>$jaf_id);

              // Mail::send(['html'=>'mails.insuff-report-notify'], $data, function($message) use($email,$name) {
              //     $message->to($email, $name)->subject
              //         ('myBCD System - Insufficiency Notification');
              //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
              // });

              dispatch(new \App\Jobs\Admin\Report\InsuffNotify($data,$email,$name));
            }
            
            // DB::table('reports') 
            //   ->where(['id'=>$report_id])
            //   ->update(['is_verified'=>'1','status'=>'completed','manual_input_status'=>'completed','approval_status_id'=>$approval_status_id,'created_by'=>Auth::user()->id,'report_jaf_data'=>json_encode($request->all())]);

            //redirect to reports

            // DB::commit();
            // return redirect()
            // ->route('/reports')
            // ->with('success', 'Report updated Successfully. download now');

            return response()->json([
              'success' =>true,
              'custom'  =>'yes',
              'errors'  =>[]
            ]);
          }
          // }
          // catch (\Exception $e) {
          //     DB::rollback();
          //     // something went wrong
          //     return $e;
          // } 

      }

      //report to be complete
      public function outputProcess($id){
        $id = base64_decode($id);

        $candidate =    DB::table('candidate_reinitiates as u')
                          ->select('u.id','u.business_id','u.first_name','u.last_name','u.name','u.email','u.phone','r.created_at','u.dob','u.display_id','u.gender')  
                          ->leftjoin('reports as r','r.candidate_id','=','u.id')
                          ->where(['r.id'=>$id]) 
                          ->first(); 
        
        $report_items = DB::table('report_items as ri')
                          ->select('ri.*','s.name as service_name','s.id as service_id')  
                          ->join('services as s','s.id','=','ri.service_id')
                          ->where(['ri.report_id'=>$id]) 
                          ->get(); 

          //get JAF data - 
          $jaf = DB::table('jaf_form_data')->select('form_data_all')->where(['candidate_id'=>$candidate->id])->first(); 


          // echo "<pre>";
          // print_r(json_decode($jaf->form_data_all,true));

          return view('admin.reports.report-output-input', compact('candidate','report_items','jaf'));
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
 

      //Edit caniddate's report
      public function candidateReportEdit($id){

        $id = base64_decode($id);
        $business_id = Auth::user()->business_id;
        $report_id ="";
        $job = DB::table('job_items')->where(['candidate_id'=>$id])->first(); 
        $sla_id = $job->sla_id;
        //check report items created or not
          $report = DB::table('reports')->where(['candidate_id'=>$id])->first();
          //dd($report); 
          $report_id = $report->id;
          $report_status = $report->status;
          $report_revised_date = $report->revised_date;

        $candidate = [];
        $report_items = [];
        $candidate =    DB::table('candidate_reinitiates as u')
                          ->select('u.id','u.business_id','u.middle_name','u.first_name','u.last_name','u.name','u.email','u.phone','u.phone_code','r.created_at')  
                          ->leftjoin('reports as r','r.candidate_id','=','u.id')
                          ->where(['u.id'=>$id]) 
                          ->first(); 
        
        $report_items = DB::table('report_items as ri')
                          ->select('ri.*','s.name as service_name','s.id as service_id','s.type_name')  
                          ->join('services as s','s.id','=','ri.service_id')
                          ->where(['ri.report_id'=>$report_id]) 
                          ->orderBy('s.sort_number','asc')
                          ->orderBy('ri.service_item_order','asc')
                          ->get();
        
        //dd($report_items);
          //get JAF data - 
          $jaf = DB::table('jaf_form_data')->select('form_data_all')->where(['candidate_id'=>$id])->first(); 

          $status_list = DB::table('report_status_masters')->get();      
          
        // dd($additional_data);
          return view('admin.reports.candidate-edit-report', compact('candidate','report','report_items','report_revised_date','jaf','report_id','report_status','sla_id','status_list'));
      }

      //
      public function get_item_id($report_id, $service_id)
      { 
        $res = NULL;   
        $data = DB::table('report_items')->select('*')->where(['report_id'=>$report_id,'service_id'=>$service_id])->first();
        if($data !=null){
            $res = $data->id;
        }
        return $res;
      }

      //report to be complete
      public function reportItemUpdate(Request $request){
        // dd($request->insuff_raised_date);

        // dd($request->all());
        $current_date = date('d-m-Y');
        $report_id = base64_decode($request->input('report_id'));

        DB::beginTransaction();
        try{

              $rules=[
                'revised_date' => 'nullable|date',
                'insuff_raised_date' => 'nullable|date|before_or_equal:'.$current_date,
                'insuff_cleared_date'       => 'nullable|date|before_or_equal:'.$current_date.'|after_or_equal:insuff_raised_date',
                'initiated_date' => 'nullable|date',
              ];

              $custom = [
                'revised_date'.'.date' => 'Revised Date Must Be in Date Format',
                'initiated_date'.'.date' => 'Initiated Date Must Be in Date Format',
              ];

              $validator = Validator::make($request->all(), $rules, $custom);
          
              if ($validator->fails()){
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors(),
                        'error_type'=>'validation'
                    ]);
              }

              //store Verifier name
              DB::table('reports')
              ->where(['id'=>$report_id])
              ->update(['verifier_name'=>$request->verifier_name,'verifier_email'=>$request->verifier_email,'verifier_designation'=>$request->verifier_designation,'revised_date'=> $request->revised_date!=NULL && date('Y-m-d',strtotime($request->revised_date))!='1970-01-01' ? date('Y-m-d',strtotime($request->revised_date)) : NULL,'revised_date_updated_by'=>Auth::user()->id,'insuff_raised_date'=> $request->insuff_raised_date!=NULL && date('Y-m-d',strtotime($request->insuff_raised_date))!='1970-01-01' ? date('Y-m-d',strtotime($request->insuff_raised_date)) : NULL,'insuff_cleared_date'=>$request->insuff_cleared_date!=NULL && date('Y-m-d',strtotime($request->insuff_cleared_date))!='1970-01-01' ? date('Y-m-d',strtotime($request->insuff_cleared_date)) : NULL,'initiated_date'=>$request->initiated_date!=NULL && date('Y-m-d',strtotime($request->initiated_date))!='1970-01-01' ? date('Y-m-d',strtotime($request->initiated_date)) : NULL]);
            
              $candidate = DB::table('reports')->select('candidate_id','business_id','parent_id','candidate_id')->where(['id'=>$report_id])->first();
              $report_page_status=DB::table('report_add_page_statuses')->where(['coc_id' => $candidate->business_id,'status'=>'enable'])->first();

              //get report items
              $report_items = DB::table('report_items')
                              ->where(['report_id'=>$report_id])
                              ->get();

              // Validation
              foreach($report_items as $service)
              {
                  $ref_type = '';
                  //  dd($request->input('service-input-value-'.$service->id.'-2'));
                  // if($service->service_id=='17')
                  // {
                  //   $i=2;
                  //   $this->validate($request,[
                  //       'service'.'-'.'input'.'-'.'value'.'-'.$service->id.'-'.$i => 'required',
                  //     ]
                  //   );

                  // }

                  $input_items = DB::table('service_form_inputs as sfi')
                      ->select('sfi.*')            
                      ->where(['sfi.service_id'=>$service->service_id,'status'=>1])
                      ->whereNull('sfi.reference_type')
                      ->whereNotIn('label_name',['Mode of Verification','Remarks'])
                      ->get();

                  $j=0;
                  foreach($input_items as $item)
                  {
                      $name=$item->label_name;
                      if($service->service_id == 17 && stripos($request->input('service-input-label-'.$service->id.'-'.$j),'Reference Type (Personal / Professional)')!==false)
                      {
                          $ref_type = $request->input('service-input-value-'.$service->id.'-'.$j);
                      }

                      $check_control_masters=DB::table('check_control_masters')->where(['check_control_coc_id'=>$candidate->business_id,'service_input_id'=>$item->id])->first();

                      if($check_control_masters!=NULL){
                        if($service->service_id==10)
                          { 
                              if($name=='Date of Joining (Employee Tenure)' || $name=='Date of Relieving (Employee Tenure)')
                              {
                                  $rules=[
                                    'service-input-value-'.$service->id.'-'.$j.'-1'=> 'required_without:service-input-value-'.$service->id.'-'.$j.'-2',
                                    'service-input-value-'.$service->id.'-'.$j.'-2'=> 'required_without:service-input-value-'.$service->id.'-'.$j.'-1',
                                  ];
    
                                  $custom=[
                                    'service-input-value-'.$service->id.'-'.$j.'-1'.'.required_without' => $name.' Field is required',
                                    'service-input-value-'.$service->id.'-'.$j.'-2'.'.required_without' => $name.' Field is required'
                                  ];
                              }
                              else
                              {
                                  $rules=[
                                    'service-input-value-'.$service->id.'-'.$j=> 'required',
                                  ];
    
                                  $custom=[
                                    'service-input-value-'.$service->id.'-'.$j.'.required' => $name.' Field is required'
                                  ];
                              }
                  
                              $validator = Validator::make($request->all(), $rules,$custom);
                          
                              if ($validator->fails()){
                                  return response()->json([
                                      'fail' => true,
                                      'errors' => $validator->errors(),
                                      'error_type'=>'validation'
                                  ]);
                              }
                          } 
                          else
                          {
                            $rules=[
                              'service-input-value-'.$service->id.'-'.$j=> 'required',
                            ];
          
                            $custom=[
                              'service-input-value-'.$service->id.'-'.$j.'.required' => $name.' Field is required'
                            ];
                
                            $validator = Validator::make($request->all(), $rules,$custom);
                        
                            if ($validator->fails()){
                                return response()->json([
                                    'success' => false,
                                    'errors' => $validator->errors(),
                                    'error_type'=>'validation'
                                ]);
                            }
                          }
                      }

                      
        
                      $j++;
                  }

                  // dd(1);
                  if($service->service_id=='17')
                  {
                      // $i=2;
                      // $rules=[
                      //   'service-input-value-'.$service->id.'-'.$i => 'required|in:personal,professional',
                      // ];

                      // $custom = [
                      //   'service-input-value-'.$service->id.'-'.$i.'.required' => 'Reference Type Field is Required',
                      //   'service-input-value-'.$service->id.'-'.$i.'.in' => 'Reference Type Must be personal / professional',
                      // ];
                
                      // $validator = Validator::make($request->all(), $rules, $custom);
                  
                      // if ($validator->fails()){
                      //       return response()->json([
                      //           'success' => false,
                      //           'errors' => $validator->errors(),
                      //           'error_type'=>'validation'
                      //       ]);
                      // }

                      // Check for Reference type Validation

                      $ref_input_items = DB::table('service_form_inputs')
                      ->where(['service_id'=>$service->service_id,'reference_type'=>$ref_type,'status'=>1])
                      ->orWhereIn('label_name',['Mode of Verification','Remarks'])
                      ->orderBy('reference_type','desc')
                      ->get();

                      if(($ref_type!='' && $ref_type!=NULL) && count($ref_input_items)>0)
                      {
                          $j=0;
                          foreach($ref_input_items as $item)
                          {
                              $name=str_replace('?','',$item->label_name);
                              $check_control_masters=DB::table('check_control_masters')->where(['check_control_coc_id'=>$candidate->business_id,'service_input_id'=>$item->id])->first();
          
                              if($check_control_masters!=NULL){
                                  $rules=[
                                    'reference-input-value-'.$service->id.'-'.$j=> 'sometimes|required',
                                  ];
                
                                  $custom=[
                                    'reference-input-value-'.$service->id.'-'.$j.'.required' => $name.' field is required'
                                  ];
                      
                                  $validator = Validator::make($request->all(), $rules,$custom);
                              
                                  if ($validator->fails()){
                                      return response()->json([
                                          'success' => false,
                                          'errors' => $validator->errors(),
                                          'error_type'=>'validation'
                                      ]);
                                  }
                              }
          
                              $j++;
                          }

                      }
                  }

              }

              // dd(1);
              $i = 0;
              foreach($report_items as $item){
                  $report_output = 0;
                  $check_order = 1;
                  //update report
                  $verified_by          = $request->input('verified_by-'.$item->id);
                  $annexure_value          = $request->input('annexure_value-'.$item->id);
                  $comments             = $request->input('comments-'.$item->id);
                  $additional_comments  = $request->input('additional-comments-'.$item->id);
                  $status_id            = $request->input('approval-status-'.$item->id);
                  $district_court_name  = $request->input('district_court_name-'.$item->id);
                  $district_court_result= $request->input('district_court_result-'.$item->id);
                  $high_court_name      = $request->input('high_court_name-'.$item->id);
                  $high_court_result    = $request->input('high_court_result-'.$item->id);
                  $supreme_court_name   = $request->input('supreme_court_name-'.$item->id);
                  $supreme_court_result = $request->input('supreme_court_result-'.$item->id);
                  $verified_data = $request->input('verified-input-checkbox-'.$item->id);
                  $test_date  = $request->input('test_date-'.$item->id);
                  //

                  $check_order = $request->input('check-order-'.$item->id);

                  if($request->has('report-output-'.$item->id)){
                    $report_output     = '1';
                  }
                  
                  $input_items = DB::table('service_form_inputs as sfi')
                                    ->select('sfi.*','s.type_name')            
                                    ->where(['sfi.service_id'=>$item->service_id,'sfi.status'=>1])
                                    ->join('services as s','s.id','=','sfi.service_id')
                                    ->whereNull('sfi.reference_type')
                                    ->whereNotIn('label_name',['Mode of Verification','Remarks'])
                                    ->get();

                    //dd($input_items);
                    $input_data = [];
                    $j=0;
                    $reference_type=NULL;
                    foreach($input_items as $input){
                      
                      $remarks_message= "";
                      $remarks_custom_message= "";
                      $remarks     = '-';
                      $is_executive_summary = 0;
                      $table_output = 0;

                      if($request->has('remarks-input-checkbox-'.$item->id.'-'.$j)){
                        $remarks     = 'Yes';
                      }
                      if($request->has('remarks-input-value-'.$item->id.'-'.$j)){
                        $remarks_message = $request->input('remarks-input-value-'.$item->id.'-'.$j);

                        if ($remarks_message=='custom') {
                        $remarks_custom_message=$request->input('remarks-msg-'.$item->id.'-'.$j);
                        }

                      }
                      if($request->has('table-output-'.$item->id.'-'.$j)){
                        $table_output = '1';
                      }
                      
                      if($request->has('executive-summary-'.$item->id.'-'.$j)){
                        $is_executive_summary   = '1';
                      }

                        if($input->service_id==17)
                        {
                              $input_data[] = [
                                $request->input('service-input-label-'.$item->id.'-'.$j)=>$request->input('service-input-value-'.$item->id.'-'.$j),
                                'remarks'=>$remarks,
                                'is_report_output'=>$table_output,
                                'remarks_message'=>$remarks_message,
                                'remarks_custom_message'=>$remarks_custom_message,
                                'is_executive_summary'=>$is_executive_summary 
                              ];
                              // dd($request->input('service-input-label-'.$service->id.'-'.'2'));
                              if(stripos($request->input('service-input-label-'.$item->id.'-'.$j),'Reference Type (Personal / Professional)')!==false)
                              {
                                  $reference_type = $request->input('service-input-value-'.$item->id.'-'.$j);
                              }
        
                        }
                        else if($input->service_id==10){

                          if($input->label_name=='Date of Joining (Employee Tenure)' || $input->label_name=='Date of Relieving (Employee Tenure)')
                          {
                              if($request->has('service-input-value-'.$item->id.'-'.$j))
                              {
                                $input_data[] = [
                                    $request->input('service-input-label-'.$item->id.'-'.$j)=>$request->input('service-input-value-'.$item->id.'-'.$j),
                                    'is_report_output'=>$table_output,
                                    'input_type' => 'date',
                                    'remarks_message'=>$remarks_message,
                                    'remarks_custom_message'=>$remarks_custom_message,
                                    'is_executive_summary'=>$is_executive_summary 
                                ];
                              }
                              else
                              {
                                  $in_type = '';
                                  $val = null;
                                  if($request->input('service-input-value-'.$item->id.'-'.$j.'-2')!=null)
                                  {
                                      $in_type = 'string';
                                      $val = $request->input('service-input-value-'.$item->id.'-'.$j.'-2');
                                  }
                                  else
                                  {
                                    $in_type = 'date';
                                    $val = $request->input('service-input-value-'.$item->id.'-'.$j.'-1');
                                  }

                                  $input_data[] = [
                                      $request->input('service-input-label-'.$item->id.'-'.$j)=>$val,
                                      'is_report_output'=>$table_output,
                                      'input_type' => $in_type ,
                                      'remarks_message'=>$remarks_message,
                                      'remarks_custom_message'=>$remarks_custom_message,
                                      'is_executive_summary'=>$is_executive_summary 

                                  ];
                              }
                              
                          }
                          else{
                            $input_data[] = [
                              $request->input('service-input-label-'.$item->id.'-'.$j)=>$request->input('service-input-value-'.$item->id.'-'.$j),
                              'is_report_output'=>$table_output,
                              'remarks_message'=>$remarks_message,
                              'remarks_custom_message'=>$remarks_custom_message,
                              'is_executive_summary'=>$is_executive_summary 
                            ];
                          }
                        }
                        else if($input->type_name=='vital_4')
                        {
                        
                          if($input->label_name == 'Vital Categoryes')
                          {
                              if($request->has('service-input-value-'.$service->id.'-'.$i))
                              {
                                $vital_cate_selected = $request->input('service-input-value-'.$service->id.'-'.$i);
                                
                                $key = array_search("all", $vital_cate_selected);

                                if ($key !== false) {
                                    unset($vital_cate_selected[$key]);
                                }
                                // dd($vital_cate_selected);
                                $slected_value = implode(',',$vital_cate_selected);
                                
                                $input_data[] = [
                                    $request->input('service-input-label-'.$service->id.'-'.$i)=>$slected_value,
                                    'remarks'=>$remarks,
                                    'is_report_output'=>$table_output,
                                    'remarks_message'=>$remarks_message,
                                    'remarks_custom_message'=>$remarks_custom_message,
                                    'is_executive_summary'=>$is_executive_summary
                                ];
                              }
                          }
              
                          else{
                            $input_data[] = [
                              $request->input('service-input-label-'.$service->id.'-'.$i)=>$request->input('service-input-value-'.$service->id.'-'.$i),
                              'remarks'=>$remarks,
                              'is_report_output'=>$table_output,
                              'remarks_message'=>$remarks_message,
                              'remarks_custom_message'=>$remarks_custom_message,
                              'is_executive_summary'=>$is_executive_summary
                            ];
                          }
                        }
                        else
                        {
                          $input_data[] = [
                                $request->input('service-input-label-'.$item->id.'-'.$j)=>$request->input('service-input-value-'.$item->id.'-'.$j),
                                'remarks'=>$remarks,
                                'is_report_output'=>$table_output,
                                'remarks_message'=>$remarks_message,
                                'remarks_custom_message'=>$remarks_custom_message,
                                'is_executive_summary'=>$is_executive_summary
                              ];
                        }
                        $j++;
                    }

                    // dd($reference_type);
                    $jaf_data = json_encode($input_data);
                    //dd($jaf_data);

                    $reference_jaf_data = NULL;
                    if($item->service_id==17)
                    {
                        $reference_input_data=[];
                        $l=0;

                        $reference_input_items = DB::table('service_form_inputs as sfi')
                              ->select('sfi.*')            
                              ->where(['sfi.service_id'=>$item->service_id,'status'=>1,'reference_type'=>$reference_type])
                              ->orWhereIn('label_name',['Mode of Verification','Remarks'])
                              ->orderBy('reference_type','desc')
                              ->get();
                        // dd($reference_input_items);

                        if(($reference_type!='' && $reference_type!=NULL) && count($reference_input_items)>0)
                        {
                          foreach($reference_input_items as $ref_input)
                          {
                            $reference_input_data[] = [
                              $request->input('reference-input-label-'.$item->id.'-'.$l)=>$request->input('reference-input-value-'.$item->id.'-'.$l),
                              'is_report_output'=>$input->is_report_output 
                            ];
                            $l++;
                          }
  
                          $reference_jaf_data = json_encode($reference_input_data);
                        }
                        
                    }


                    $insuf_notes = NULL;
                    if($request->has('insuf_notes-'.$item->id)){
                      // dd($request->input('insuf_notes-'.$item->id));
                      $insuf_notes     = $request->input('insuf_notes-'.$item->id);
                    }

                  $is_updated = DB::table('report_items')
                                ->where(['report_id'=>$report_id,'id'=>$item->id])
                                ->update(['jaf_data'          =>$jaf_data,
                                          'verified_by'       =>$verified_by,
                                          'test_date'         => $test_date!=NULL ? date('Y-m-d',strtotime($test_date)) : NULL, 
                                          'annexure_value'    =>$annexure_value,
                                          'is_report_output'  =>$report_output,
                                          'comments'          =>$comments,
                                          'additional_comments'=>$additional_comments,
                                          'approval_status_id' =>$status_id,
                                          'report_insufficiency_notes'=>$insuf_notes,
                                          'district_court_name'=>$district_court_name,
                                          'district_court_result'=>$district_court_result,
                                          'high_court_name'    =>$high_court_name,
                                          'high_court_result'   =>$high_court_result,
                                          'supreme_court_name'  =>$supreme_court_name,
                                          'supreme_court_result'=>$supreme_court_result,
                                          'reference_type' => $reference_type!='' && $reference_type!=NULL ? $reference_type : NULL,
                                          'reference_form_data' => $reference_jaf_data!=NULL ? $reference_jaf_data : NULL,
                                          'service_item_order' => $check_order
                                          ]
                                        );
                    
                    if ($report_page_status) {
                      $service = DB::table('services')->select('name')->where(['id'=>$item->service_id,'name'=>'Address'])->first();
                      if ($service) {
                        $additional_data =null;
                        // dd($report_id);
                        $additional_data = DB::table('additional_address_verifications')->where(['report_item_id'=>$item->id,'candidate_id'=>$candidate->candidate_id])->first();
                        
                        if ($additional_data!=null) {
                      
                      
                          $contact_person_name =$request->input('contact_person_name-'.$item->id);
                          $contact_person_no=$request->input('contact_person_no-'.$item->id);
                          $relation_with_associate=$request->input('relation_with_associate-'.$item->id);
                          $residence_status=$request->input('residence_status-'.$item->id);
                          $locality=$request->input('locality-'.$item->id);
                          $verification_mode=$request->input('verification_mode-'.$item->id);
                          $additional_remarks=$request->input('additional_remark-'.$item->id);
                          $additional_address_comment=$request->input('additional_verification_comments-'.$item->id);
                          $additional_verified_by=$request->input('Additional_verified_by-'.$item->id);
      
                          $additional=    DB::table('additional_address_verifications')
                                  ->where(['report_item_id'=>$item->id,'id'=>$additional_data->id])
                                  ->update([
                                            'contact_person_name' =>  $contact_person_name, 
                                            'contact_contact_no' => $contact_person_no,
                                            'relation_with_associate'=>$relation_with_associate,
                                            'residence_status'=>$residence_status,
                                            'locality'  => $locality,
                                            'mode_of_verification'=> $verification_mode,
                                            'comments' => $additional_address_comment,
                                            'remarks' => $additional_remarks,
                                            'verified_by'=>$additional_verified_by,
                                            'updated_by'=>Auth::user()->id,
                                            'updated_at'    =>date('Y-m-d H:i:s')
                              
                                            ]
                                          );
      
                          
                        }
                        else {
      
                          $contact_person_name =$request->input('contact_person_name-'.$item->id);
                          $contact_person_no=$request->input('contact_person_no-'.$item->id);
                          $relation_with_associate=$request->input('relation_with_associate-'.$item->id);
                          $residence_status=$request->input('residence_status-'.$item->id);
                          $locality=$request->input('locality-'.$item->id);
                          $verification_mode=$request->input('verification_mode-'.$item->id);
                          $additional_remarks=$request->input('additional_remark-'.$item->id);
                          $additional_address_comment=$request->input('additional_verification_comments-'.$item->id);
                          $additional_verified_by=$request->input('Additional_verified_by-'.$item->id);
      
                          $additional = 
                            [
                              'parent_id'     =>$candidate->parent_id,
                              'business_id'   =>$candidate->business_id,
                              'candidate_id'  =>$candidate->candidate_id,
                              'report_item_id' =>$item->id, 
                              'contact_person_name' =>  $contact_person_name, 
                              'contact_contact_no' => $contact_person_no,
                              'relation_with_associate'=>$relation_with_associate,
                              'residence_status'=>$residence_status,
                              'locality'  => $locality,
                              'mode_of_verification'=> $verification_mode,
                              'comments' => $additional_address_comment,
                              'remarks' => $additional_remarks,
                              'verified_by'=>$additional_verified_by,
                              'created_by'=>Auth::user()->id,
                              'created_at'    =>date('Y-m-d H:i:s')
                            ];
                            
                            DB::table('additional_address_verifications')->insertGetId($additional);
                        }
                      }
                      
                    }

                    $check_is_verified = JafFormData::find($item->jaf_id);
                    // var_dump('(/' .$check_is_verified->is_data_verified.'/'.$verified_data.')');
                    if ($check_is_verified) {
                      if ($check_is_verified->is_data_verified=='0' &&  $verified_data == '1') {
                        
                        $check_is_verified->update(['is_data_verified'=>'1','data_verified_date'=> date('Y-m-d H:i:s'),'verified_data_submitted_by'=>Auth::user()->id]);
                      }
                    
                    }

                    $check_is_verified = ReportItem::find($item->id);
                    // var_dump('(/' .$check_is_verified->is_data_verified.'/'.$verified_data.')');
                    if ($check_is_verified) {
                      if ($check_is_verified->is_data_verified=='0' &&  $verified_data == true) {
                        
                        $check_is_verified->update(['is_data_verified'=>'1','data_verified_date'=> date('Y-m-d H:i:s'),'verified_data_submitted_by'=>Auth::user()->id]);
                      }
                    
                    }
                            
                                
                  $i++;
              }

              //color status
              $approval_status_id= NULL;
              $report_item_data = DB::table('report_items')->where(['report_id'=>$report_id])->whereNotNull('approval_status_id')->orderBy('approval_status_id','asc')->first();
              if($report_item_data !=null){
                $approval_status_id= $report_item_data->approval_status_id;
              }

              //store Verifier name
              // DB::table('reports')
              // ->where(['id'=>$report_id])
              // ->update(['verifier_name'=>$request->verifier_name,'verifier_email'=>$request->verifier_email,'verifier_designation'=>$request->verifier_designation]);

              //update report status


              //report update
              // $jaf_items_failed = DB::table('jaf_form_data')->where(['candidate_id'=>$report_item_data->candidate_id,'verification_status'=>NULL])->orWhere('verification_status','failed')->get(); 
              // // dd($jaf_items_failed);
              // if (count($jaf_items_failed)>0) {
              //  //update report status
              // DB::table('reports')
              // ->where(['id'=>$report_id])
              // ->update(['report_type'=>'auto','is_verified'=>'1','status'=>'interim','manual_input_status'=>'completed','approval_status_id'=>$approval_status_id,'report_jaf_data'=>NULL]);
            
              // } else {
                  
              // //update report status
              // DB::table('reports')
              // ->where(['id'=>$report_id])
              // ->update(['report_type'=>'auto','is_verified'=>'1','status'=>'completed','manual_input_status'=>'completed','approval_status_id'=>$approval_status_id,'report_jaf_data'=>NULL]);

              // }
              $reports=DB::table('reports')->where(['id'=>$report_id])->first();

              if($reports->complete_created_at==NULL)
              {
                  DB::table('reports')
                  ->where(['id'=>$report_id])
                  ->update(['is_verified'=>'1','status'=>'completed','manual_input_status'=>'completed','approval_status_id'=>$approval_status_id,'report_jaf_data'=>json_encode($request->all()),'complete_created_at'=>date('Y-m-d H:i:s')]);
              }
              else
              {
                DB::table('reports')
                ->where(['id'=>$report_id])
                ->update(['is_verified'=>'1','status'=>'completed','manual_input_status'=>'completed','approval_status_id'=>$approval_status_id,'report_jaf_data'=>json_encode($request->all()),'complete_updated_at'=>date('Y-m-d H:i:s')]);
              }

              // Check the Mark as Green is Checked or Not

              $is_manual_mark = 0;

              if($request->input('manual_check')!=null)
              {
                $is_manual_mark = $request->input('manual_check');
              }
              

              $report_data = DB::table('reports')->where(['id'=>$report_id])->first();

              // Check Whether Changing the Color Code Manually
              if($report_data->is_manual_mark!=$is_manual_mark)
              {
                if($report_data->manual_mark_created_by==NULL)
                {
                  DB::table('reports')
                  ->where(['id'=>$report_id])
                  ->update(['is_manual_mark'=>$is_manual_mark==1 || $is_manual_mark==2 ? $is_manual_mark : 0,'manual_mark_created_by'=>Auth::user()->id,'manual_mark_created_at'=>date('Y-m-d H:i:s')]);
                }
                else
                {
                  DB::table('reports')
                  ->where(['id'=>$report_id])
                  ->update(['is_manual_mark'=>$is_manual_mark==1 || $is_manual_mark==2 ? $is_manual_mark : 0,'manual_mark_updated_by'=>Auth::user()->id,'manual_mark_updated_at'=>date('Y-m-d H:i:s')]);
                }
              }

              // Check the Mark as Report Completed or Not

              $is_report_complete = 0;

              if($request->input('report_complete')!=null)
              {
                $is_report_complete = 1;
              }

              if($report_data->is_report_complete==0 && $is_report_complete==1)
              {
                if($report_data->report_complete_created_by==NULL)
                {
                  DB::table('reports')
                  ->where(['id'=>$report_id])
                  ->update(['is_report_complete'=>$is_report_complete,'report_complete_created_by'=>Auth::user()->id,'report_complete_created_at'=>date('Y-m-d H:i:s')]);
                }
                else
                {
                  DB::table('reports')
                  ->where(['id'=>$report_id])
                  ->update(['is_report_complete'=>$is_report_complete,'report_complete_updated_by'=>Auth::user()->id,'report_complete_updated_at'=>date('Y-m-d H:i:s')]);
                }

                // Notification for Report Completion for Client

                $candidate = DB::table('candidate_reinitiates')->where('id',$report_data->candidate_id)->first();
                
                $sender = DB::table('users')->where(['id'=>$candidate->parent_id])->first();

                $notification_controls = DB::table('notification_control_configs as nc')
                                                ->select('nc.*')
                                                ->join('notification_controls as n','n.business_id','=','nc.business_id')
                                                ->where(['n.status'=>1,'nc.status'=>1,'n.business_id'=>$candidate->business_id,'n.type'=>'report-complete','nc.type'=>'report-complete'])
                                                ->get();

                if(count($notification_controls)>0)
                {
                    foreach($notification_controls as $item)
                    {
                      $name = $item->name;
                      $email = $item->email;
                      $msg= 'Report Has Been Completed Successfully for Candidate ('.$candidate->name.' - '.$candidate->display_id.') at '.date('d-M-y h:i A').'';

                      $data  = array('name'=>$name,'email'=>$email,'sender'=>$sender,'msg'=>$msg);

                      // Mail::send(['html'=>'mails.report-complete'], $data, function($message) use($email,$name) {
                      //     $message->to($email, $name)->subject
                      //         ('myBCD System - Report Notification');
                      //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                      // });

                      dispatch(new \App\Jobs\Admin\Report\CompleteNotify($data,$email,$name));
                    }
                }


              }


              DB::commit();
              //redirect to reports
              // return redirect()
              // ->route('/reports')
              // ->with('success', 'Report updated Successfully. download now');

              return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'errors'  =>[]
              ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 

      }

      /**
       * remove a resource .
       *
       * @param  \Illuminate\Http\Request  $request
       * @return \Illuminate\Http\Response
       */
      public function removeFile(Request $request)
      {        
        $id =  $request->input('file_id');

          $is_done = DB::table('report_item_attachments')->where('id',$id)->update(['is_deleted'=>'1','deleted_at'=>date('Y-m-d H:i:s'),'deleted_by'=>Auth::user()->id]);

          // Do something when it fails
          return response()->json([
              'fail' => false,
              'message' => 'File removed!'
          ]);
      }

      // add file.
      public function uploadFile(Request $request)
      {        
          //  echo count($request->file('files'));
          //  print_r($request->file('files')); 
          //  echo $request->input('service_id');
          //  die;
          // dd($request);
          $user_id = Auth::user()->id;
          $files=[];
          $t=0;
          $extensions = array("jpg","png","jpeg","PNG","JPG","JPEG",'pdf');
        
          if($request->hasFile('files')) {
              
            foreach( $request->file('files') as $item){
            
                $result = array($request->file('files')[$t]->getClientOriginalExtension());

                //$result = array($item->getClientOriginalExtension());

                if(in_array($result[0],$extensions))
                {                      
                    // $label_file_name  = $request->input('label_file_name');

                    $file_platform = 'web';

                    $s3_config = S3ConfigTrait::s3Config();
          
                    $attachment_file  = $request->file('files')[$t];
                    //$attachment_file  = $item;
                    $orgi_file_name   = $attachment_file->getClientOriginalName();
                    
                    $fileName = pathinfo($orgi_file_name,PATHINFO_FILENAME);
          
                    $filename         = date('YmdHis').'-'.base64_decode($request->input('report_id')).'-'.$t.'.'.$attachment_file->getClientOriginalExtension();
                    $dir              = public_path('/uploads/report-files/'); 

                    $request->file('files')[$t]->move($dir, $filename);

                    // $item->move($dir, $filename);
                      
                    $report_id       = NULL;
                    $report_item_id  = NULL;
                    $is_temp         = 1;
                    $type            = 'main';
                    //check if report id 
                    if($request->has('report_id')) {
                        $report_id       = base64_decode($request->input('report_id'));
                        $report_item_id  = $request->input('report_item_id');
                        $is_temp         = 0;
                        //get service item id
                        $type            = 'supporting';
                        if($request->has('type')){
                          $type           = $request->input('type');
                        }
                    }

                  // file type 
                      $extArray = explode('.', $filename);
                      $ext = end($extArray);

                        $file_id_array = [];
                        $file_name_array = [];
                        $file_url_array = [];

                      if(stripos($ext,'pdf')!==false)
                      {
                          $file_platform = 'web';
                          if(File::exists($dir.$filename))
                          {
                              $pdf_file_name = date('YmdHis').'-'.time();

                              // $report_path=public_path('/').'/uploads/report-data/'.$user_id.'/';

                              // if(!File::exists($report_path))
                              // {
                              //    File::makeDirectory($report_path, $mode = 0777, true, true);
                              // }

                              $imagick = new Imagick();

                              $imagick->setResolution(300, 300);

                              $imagick->readImage($dir.$filename);

                              $imagick->setImageFormat("png");

                              $pages = $imagick->getNumberImages();

                              $imagick->writeImages($dir.$pdf_file_name.'.png', false);

                              if($pages)
                              {
                                  if($pages==1)
                                  {
                                        if($s3_config!=NULL)
                                        {
                                            $file_platform = 's3';

                                            $file_name = $pdf_file_name.'.png';

                                            $path = 'uploads/report-files/';

                                            if(!Storage::disk('s3')->exists($path))
                                            {
                                                Storage::disk('s3')->makeDirectory($path,0777, true, true);
                                            }

                                            $file = Helper::createFileObject($path.$file_name);

                                            Storage::disk('s3')->put($path.$file_name, file_get_contents($file));

                                        }
                                      $rowID = DB::table('report_item_attachments')            
                                      ->insertGetId([
                                          'report_id'        => $report_id, 
                                          'report_item_id'   => $report_item_id,                      
                                          'file_name'        => $pdf_file_name.'.png',
                                          'attachment_type'  => $type,
                                          'file_platform'    => $file_platform,
                                          'created_by'       => Auth::user()->id,
                                          'created_at'       => date('Y-m-d H:i:s'),
                                          'is_temp'          => $is_temp,
                                      ]);

                                      $file_url = '';
                                      if(stripos($file_platform,'s3')!==false)
                                      {
                                        $filePath = 'uploads/report-files/';

                                        if(File::exists(public_path('/uploads/report-files/'.$pdf_file_name.'.png')))
                                        {
                                          File::delete(public_path('/uploads/report-files/'.$pdf_file_name.'.png'));
                                        }

                                        $disk = Storage::disk('s3');

                                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                            'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                            'Key'                        => $filePath.$pdf_file_name.'.png',
                                            'ResponseContentDisposition' => 'attachment;'//for download
                                        ]);

                                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                        $file_url = (string)$req->getUri();
                                      }
                                      else
                                      {
                                          $file_url = url('/').'/uploads/report-files/'.$pdf_file_name.'.png';
                                      }

                                      $file_id_array[] = $rowID;
                                      $file_name_array[] = $pdf_file_name.'.png';
                                      $file_url_array[] = $file_url;
                                  }
                                  else
                                  {
                                      for($i=0;$i<$pages;$i++)
                                      {
                                          $file_platform = 'web';

                                          if($s3_config!=NULL)
                                          {
                                              $file_platform = 's3';

                                              $file_name = $pdf_file_name.'-'.$i.'.png';

                                              $path = 'uploads/report-files/';

                                              if(!Storage::disk('s3')->exists($path))
                                              {
                                                  Storage::disk('s3')->makeDirectory($path,0777, true, true);
                                              }

                                              $file = Helper::createFileObject($path.$file_name);

                                              Storage::disk('s3')->put($path.$file_name, file_get_contents($file));

                                          }
                                          $rowID = DB::table('report_item_attachments')            
                                          ->insertGetId([
                                              'report_id'        => $report_id, 
                                              'report_item_id'   => $report_item_id,                      
                                              'file_name'        => $pdf_file_name.'-'.$i.'.png',
                                              'attachment_type'  => $type,
                                              'file_platform'    => $file_platform,
                                              'created_by'       => Auth::user()->id,
                                              'created_at'       => date('Y-m-d H:i:s'),
                                              'is_temp'          => $is_temp,
                                          ]);

                                          $file_url = '';
                                          if(stripos($file_platform,'s3')!==false)
                                          {
                                            $filePath = 'uploads/report-files/';

                                            if(File::exists(public_path('/uploads/report-files/'.$pdf_file_name.'-'.$i.'.png')))
                                            {
                                              File::delete(public_path('/uploads/report-files/'.$pdf_file_name.'-'.$i.'.png'));
                                            }

                                            $disk = Storage::disk('s3');

                                            $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                                'Key'                        => $filePath.$pdf_file_name.'-'.$i.'.png',
                                                'ResponseContentDisposition' => 'attachment;'//for download
                                            ]);

                                            $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                            $file_url = (string)$req->getUri();
                                          }
                                          else
                                          {
                                              $file_url = url('/').'/uploads/report-files/'.$pdf_file_name.'-'.$i.'.png';
                                          }
        
                                          $file_id_array[] = $rowID;
                                          $file_name_array[] = $pdf_file_name.'-'.$i.'.png';
                                          $file_url_array[] = $file_url;  
                                      }
                                  }
                              }

                              File::delete($dir.$filename);

                          }
                      }
                      else
                      {
                        $file_platform = 'web';
                        if($s3_config!=NULL)
                        {
                            $file_platform = 's3';

                            $path = 'uploads/report-files/';

                            if(!Storage::disk('s3')->exists($path))
                            {
                                Storage::disk('s3')->makeDirectory($path,0777, true, true);
                            }

                            $file = Helper::createFileObject($path.$filename);

                            Storage::disk('s3')->put($path.$filename, file_get_contents($file));

                        }

                        $rowID = DB::table('report_item_attachments')            
                        ->insertGetId([
                            'report_id'        => $report_id, 
                            'report_item_id'   => $report_item_id,                      
                            'file_name'        => $filename,
                            'attachment_type'  => $type,
                            'file_platform'     => $file_platform,
                            'created_by'       => Auth::user()->id,
                            'created_at'       => date('Y-m-d H:i:s'),
                            'is_temp'          => $is_temp,
                        ]); 

                        if(stripos($file_platform,'s3')!==false)
                        {
                            if(File::exists(public_path('/uploads/report-files/'.$filename)))
                            {
                              File::delete(public_path('/uploads/report-files/'.$filename));
                            }
                        }      
                      }                         
          
                        $type = url('/').'/admin/images/file.jpg';
                      
                        if($filename != NULL)
                        {
                            // if($ext == 'pdf')
                            // {
                            //   $type = url('/').'/admin/images/icon_pdf.png';
                            // } 
                            if($ext == 'doc' || $ext == 'docx')
                            {
                              $type = url('/').'/admin/images/icon_docx.png';
                            }
                            if($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv')
                            {
                              $type = url('/').'/admin/images/icon_xlsx.png';
                            }
                            if($ext == 'pptx' || $ext == 'ppt')
                            {
                              $type = url('/').'/admin/images/icon_pptx.png';
                            }
                            if($ext == 'psd' || $ext == 'PSD')
                            {
                              $type = url('/').'/admin/images/icon_psd.png';
                            }
                            if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
                            {    
                              if(stripos($file_platform,'s3')!==false)
                              {
                                  $filePath = 'uploads/report-files/';

                                  $disk = Storage::disk('s3');

                                  $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                      'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                      'Key'                        => $filePath.$filename,
                                      //'ResponseContentDisposition' => 'attachment;'//for download
                                  ]);

                                  $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                  $type = (string)$req->getUri();  
                              }
                              else
                              {
                                $type = url('/').'/uploads/report-files/'.$filename;
                              }            
                              
                            }
                            
                        }           
                        
                        if($ext=='pdf')
                        {
                          $files[] = ['file_type'=>$ext,'filePrev'=> $file_url_array,'file_id'=>$file_id_array,'file_name'=>$file_name_array];
                        }
                        else
                        {
                          $files[] = ['file_type'=>$ext,'filePrev'=> $type,'file_id'=>$rowID,'file_name'=>$filename];
                        }

                    $t++;
                
                }
                else
                {
                    // Do something when it fails
                    return response()->json([
                        'fail' => true,
                        'errors' => 'File type error!'
                    ]);
                }

            }

              //send file response
              return response()->json([
                'fail' => false,
                'errors' => 'no',
                'data'=>$files
              ]);
              
      
          }
      
      }


      //Candidate report list Tab
      public function candidateReport(Request $request)
      {
          $business_id = Auth::user()->business_id; 
        
          $data = Db::table('reports as r')
                  ->select('r.*','cl.title','u.phone','u.display_id','u.email') 
                  ->join('customer_sla as cl','cl.id','=','r.sla_id')
                  ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                  ->orderBy('r.created_at','desc');
                  
                  if(is_numeric($request->get('customer_id'))){
                    $data->where('r.business_id',$request->get('customer_id'));
                  }
                  if(is_numeric($request->get('candidate_id'))){
                    $data->where('r.candidate_id',$request->get('candidate_id'));
                  }
                  if($request->get('from_date') !=""){
                    $data->whereDate('r.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                  }
                  if($request->get('to_date') !=""){
                    $data->whereDate('r.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                  }
                  if ($request->get('search')) {
                    // $searchQuery = '%' . $request->search . '%';
                  // echo($request->input('search'));
                    $data->where('u.first_name',$request->get('search'))->orWhere('u.name',$request->get('search'))->orWhere('u.email',$request->get('search'))->orWhere('u.display_id',$request->get('search'))->orWhere('u.phone',$request->get('search'))->orWhere('u.client_emp_code',$request->get('search'));
                  }
                  
                  $data->orderBy('r.created_at','desc');

                  $items =    $data->paginate(10);  

          $customers = DB::table('users as u')
          ->select('u.id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
          ->join('user_businesses as b','b.business_id','=','u.id')
          ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
          ->get();
          
          if($request->ajax())
            return view('admin.reports.candidate-report-ajax', compact('items','customers'));
          else
            return view('admin.reports.candidate-report', compact('items','customers'));
      }


      //Candidate report list Tab
      public function slaReport(Request $request)
      {
          $business_id = Auth::user()->business_id; 
        
          $data = Db::table('reports as r')
                  ->select('r.*','cl.title','u.phone','u.display_id','u.email') 
                  ->join('customer_sla as cl','cl.id','=','r.sla_id')
                  ->join('candidate_reinitiates as u','u.id','=','r.candidate_id')
                  ->orderBy('r.created_at','desc');
                  
                  if(is_numeric($request->get('customer_id'))){
                    $data->where('r.business_id',$request->get('customer_id'));
                  }
                  if(is_numeric($request->get('candidate_id'))){
                    $data->where('r.candidate_id',$request->get('candidate_id'));
                  }
                  if($request->get('from_date') !=""){
                    $data->whereDate('r.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                  }
                  if($request->get('to_date') !=""){
                    $data->whereDate('r.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                  }
                  if ($request->get('search')) {
                    // $searchQuery = '%' . $request->search . '%';
                  // echo($request->input('search'));
                    $data->where('u.first_name',$request->get('search'))->orWhere('u.name',$request->get('search'))->orWhere('u.email',$request->get('search'))->orWhere('u.display_id',$request->get('search'))->orWhere('u.phone',$request->get('search'))->orWhere('u.client_emp_code',$request->get('search'));
                  }
                  
                  $data->orderBy('r.created_at','desc');
    
                  $items =    $data->paginate(10);  
    
          $customers = DB::table('users as u')
          ->select('u.id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
          ->join('user_businesses as b','b.business_id','=','u.id')
          ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
          ->get();
    
           
        if($request->ajax())
        return view('admin.reports.sla-report-ajax', compact('items','customers'));
        else
        return view('admin.reports.sla-report', compact('items','customers'));
      }

      public function export(Request $request)
      {
        
        $business_id=Auth::user()->business_id;

        $parent_id = Auth::user()->parent_id;

        if(stripos(Auth::user()->user_type,'user')!==false)
        {
            $user_d = DB::table('users')->where('id',$business_id)->first();

            $parent_id = $user_d->parent_id;
        }

        $user_id=Auth::user()->id;
        
        $id =$request->report_id;

        // foreach($id as $report_id)
        // {
        //     $report_data=DB::table('reports')->where(['id'=>$report_id,'status'=>'incomplete'])->first();
        //     if($report_data!=NULL)
        //     {
        //         return response()->json([
        //           'success' => false,
        //           'status' => 'no'
        //         ]);      
        //     }
        // }
        DB::beginTransaction();
        try
        {
          $reports=DB::table('reports')
          ->whereIn('id',$id)
          ->where('status','<>','incomplete')
          ->get();

          if(count($reports)>0)
          {
              $report_path = public_path().'/uploads/report-data/'.$user_id.'/';

              if (!File::exists($report_path)) {
                  File::makeDirectory($report_path, $mode = 0777, true, true);
              }

              if (File::exists($report_path)) 
              {
                  File::cleanDirectory($report_path);
              }

              $path = public_path().'/pdf/';

              if (!File::exists($path)) {
                  File::makeDirectory($path, $mode = 0777, true, true);
              }



              $zipname = 'reports-'.date('Ymdhis').'.zip';
              $zip = new \ZipArchive();      
              $zip->open(public_path().'/zip/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
              $report_array=$candidate_array=[];
              foreach($reports as $report)
              {
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
                  $report_data = DB::table('reports as r')->select('id','candidate_id','status','verifier_name','verifier_email','verifier_designation','generated_at','created_at','is_manual_mark','insuff_raised_date','insuff_cleared_date','initiated_date')->where(['id'=>$report->id])->first(); 
                  // dd($report_data);
                  $candidate = DB::table('candidate_reinitiates as u')
                              ->select('u.id','u.created_at as initiated_date','u.display_id','u.business_id','u.client_emp_code','u.entity_code','u.first_name','u.last_name','u.name','u.email','u.phone','r.id as report_id','r.created_at','r.approval_status_id','r.sla_id','cs.title as sla_name','u.gender','u.dob','r.created_by','u.parent_id','r.revised_date','u.name','r.is_manual_mark','r.status as report_status','r.is_report_complete','r.report_complete_created_at')  
                              ->leftjoin('reports as r','r.candidate_id','=','u.id')
                              ->join('customer_sla as cs','cs.id','=','r.sla_id')
                              ->where(['r.id'=>$report->id]) 
                              ->first();
      
                  $jaf = DB::table('jaf_form_data')->where(['candidate_id'=>$report_data->candidate_id])->first(); 
      
                  if($report_data->status=='completed'|| $report_data->status=='interim'){

                    $file_name = 'myBCD-Report-'.$candidate->id.'-'.date('Y-m-d').".pdf";

                    // Check for Report File Renaming

                    $customer = DB::table('user_businesses')
                                            ->where(['business_id'=>$candidate->business_id,'is_report_file_config'=>1])
                                            ->whereNotNull('report_file_config_details')
                                            ->first();

                   $template_type = DB::table('report_add_page_statuses')->select('status')->where(['coc_id' => $candidate->business_id,'template_type'=>'3'])->first();

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

                    if($template_type!=NULL && $template_type->status=='enable')
                    {
                          $pdf = PDF::loadView('admin.candidates.pdf.report-template3', compact('data','report_items','jaf','candidate','report_data','parent_id','business_id','user_id'),[],[
                            'title' => 'Report',
                            'margin_top' => 20,
                            'margin-header'=>20,
                            'margin_bottom' =>25,
                            'margin_footer'=>5,
                            
                        ])->save(public_path()."/pdf/".$file_name);
                    }
                    else
                    {
                        $pdf = PDF::loadView('admin.candidates.pdf.report', compact('report_data','report_items','data','jaf','candidate','parent_id','business_id','user_id'),[],[
                          'title' => 'Report',
                          'margin_top' => 20,
                          'margin-header'=>20,
                          'margin_bottom' =>25,
                          'margin_footer'=>5,
                          
                        ])->save(public_path()."/pdf/".$file_name); 
                    }
      
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
      
              // dd(count($report_array));
      
      
              
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
              // $sender = DB::table('users')->where(['id'=>$business_id])->first();
              // $data  = array('name'=>$name,'email'=>$email,'date' => $date,'zip_id'=>base64_encode($zip_id),'sender'=>$sender);
      
              //   Mail::send(['html'=>'mails.zip-download'], $data, function($message) use($email,$name) {
              //       $message->to($email, $name)->subject
              //           ('myBCD System - Zip Download Notification');
              //       $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
              //   });
      
            //new
            $sender = DB::table('users')->where(['id'=>$business_id])->first();
            $data  = array('name'=>$name,'email'=>$email,'date' => $date,'zip_id'=>base64_encode($zip_id),'sender'=>$sender);
            EmailConfigTrait::emailConfig();
            //get Mail config data
              //   $mail =null;
              $mail= Config::get('mail');
              // dd($mail['from']['address']);
              if (count($mail)>0) {
                  Mail::send(['html'=>'mails.zip-download'], $data, function($message) use($email,$name,$mail) {
                      $message->to($email, $name)->subject
                      ('myBCD System - Zip Download Notification');
                      $message->from($mail['from']['address'],$mail['from']['name']);
                  });
              }else {
                Mail::send(['html'=>'mails.zip-download'], $data, function($message) use($email,$name) {
                    $message->to($email, $name)->subject
                        ('myBCD System - Zip Download Notification');
                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                });
              }
              //  echo url('/').'/zip/'.$zipname;
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
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }      
        
      }

      /**
       * 
       * Get Report Reference Type Form
       * 
       */
      public function reportReferenceTypeForm(Request $request)
      {
          $report_item_id = base64_decode($request->id);

          $type = $request->type;

          $readonly='';

          $form='';

          $report_data = DB::table('report_items as ri')
                            ->where(['id'=> $report_item_id])
                            ->first();
          
          if($report_data !=NULL)
          {
              $report_reference_data = $report_data->reference_form_data;
              $form.= '<div class="row" style="border:1px solid #ddd; padding:10px; margin-bottom:10px;">';
              if($report_reference_data!=NULL)
              {
                $report=DB::table('reports')->where(['id'=>$report_data->report_id])->first();
                $form.='<h4 class="pt-2 pb-2">'.ucwords($type).' Details</h4>';
                if(stripos($report_data->reference_type,$type)!==false)
                {
                    $report_reference_data_array = json_decode($report_reference_data,true);
                    $k=0;
                    foreach($report_reference_data_array as $key => $input)
                    {
                      $key_val = array_keys($input); $input_val = array_values($input);
                      $check_input=Helper::check_item_input_name($report_data->service_id,$report->business_id,$key_val[0]);
                      $req = '';
                      if($check_input!=NULL)
                      {
                        $req='<span class="text-danger">*</span>';
                      }

                      $form.='<div class="col-sm-12">
                                <div class="form-group">
                                  <label> '.$key_val[0].' '.$req.'</label>
                                  <input type="hidden" name="reference-input-label-'.$report_data->id.'-'.$k.'" value="'.$key_val[0].'">
                                  <input class="form-control error-control" type="text" name="reference-input-value-'.$report_data->id.'-'.$k.'" value="'.$input_val[0].'">
                                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-reference-input-value-'.$report_data->id.'-'.$k.'"></p>
                                </div>
                              </div>';
                        $k++;
                    }

                }
                else
                {
                    $ref_service_inputs = DB::table('service_form_inputs')
                                          ->where(['service_id'=>$report_data->service_id,'reference_type'=>$type,'status'=>1])
                                          ->orWhereIn('label_name',['Mode of Verification','Remarks'])
                                          ->orderBy('reference_type','desc')
                                          ->get();
                      
                    $k=0;
                    foreach($ref_service_inputs as $input)
                    {
                      $check_input=Helper::check_item_input($input->id,$report->business_id);
                      $req = '';
                      if($check_input!=NULL)
                      {
                        $req='<span class="text-danger">*</span>';
                      }

                        $form.='<div class="col-sm-12">
                                  <div class="form-group">
                                    <label> '.$input->label_name.' '.$req.'</label>
                                    <input type="hidden" name="reference-input-label-'.$report_data->id.'-'.$k.'" value="'.$input->label_name.'">
                                    <input class="form-control error-control" type="text" name="reference-input-value-'.$report_data->id.'-'.$k.'">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-reference-input-value-'.$report_data->id.'-'.$k.'"></p>
                                  </div>
                                </div>';
                        $k++;
                    }
                  
                }

              }
              else
              {
                $form.='<h4 class="pt-2 pb-2">'.ucwords($type).' Details</h4>';
                $ref_service_inputs = DB::table('service_form_inputs')
                                      ->where(['service_id'=>$report_data->service_id,'reference_type'=>$type,'status'=>1])
                                      ->orWhereIn('label_name',['Mode of Verification','Remarks'])
                                      ->orderBy('reference_type','desc')
                                      ->get();
                 
                  
                  $k=0;
                  foreach($ref_service_inputs as $input)
                  {
                      $check_input=Helper::check_item_input($input->id,$report->business_id);
                      $req = '';
                      if($check_input!=NULL)
                      {
                        $req='<span class="text-danger">*</span>';
                      }
                      $form.='<div class="col-sm-12">
                                <div class="form-group">
                                  <label> '.$input->label_name.' '.$req.'</label>
                                  <input type="hidden" name="reference-input-label-'.$report_data->id.'-'.$k.'" value="'.$input->label_name.'">
                                  <input class="form-control error-control" type="text" name="reference-input-value-'.$report_data->id.'-'.$k.'">
                                  <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-reference-input-value-'.$report_data->id.'-'.$k.'"></p>
                                </div>
                              </div>';
                      $k++;
                  }
              }

              $form.='</div>';

              return $form;
          }
      }
      public function dragImage(Request $request)
      {
        // dd($request->order);
        if($request->imageType=='main'){
          $form='';
          $reportFiles = DB::table('report_item_attachments as rf')
                          ->select('rf.id','rf.file_name','rf.created_at','rf.attachment_type','rf.file_platform')                        
                          ->where(['rf.report_item_id'=>$request->imageId,'rf.is_deleted'=>0,'rf.attachment_type'=>'main'])  
                          ->orderBy('rf.img_order','ASC')  
                          ->get();           
              // dd($reportFiles);
          $docs = array();
          foreach ($reportFiles as $item) {
    
              $type = url('/').'/admin/images/icon_docx.png';
              $extArray = explode('.', $item->file_name);
              $ext = end($extArray);
            
              if($item->file_name != NULL)
              {
                  if($ext == 'pdf')
                  {
                    $type = url('/').'/admin/images/icon_pdf.png';
                  } 
                  if($ext == 'doc' || $ext == 'docx')
                  {
                    $type = url('/').'/admin/images/icon_docx.png';
                  }
                  if($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv')
                  {
                    $type = url('/').'/admin/images/icon_xlsx.png';
                  }
                  if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'  || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
                  {  
                    if(stripos($item->file_platform,'s3')!==false)
                    {
                        $filePath = 'uploads/report-files/';

                        $s3_config = S3ConfigTrait::s3Config();

                        $disk = Storage::disk('s3');

                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                            'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                            'Key'                        => $filePath.$item->file_name,
                            'ResponseContentDisposition' => 'attachment;'//for download
                        ]);

                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                        $type = (string)$req->getUri();  
                    }
                    else
                    {
                      $type = url('/').'/uploads/report-files/'.$item->file_name;
                    }
                  }
                  if($ext == 'pptx')
                  {
                    $type = url('/').'/admin/images/icon_pptx.png';
                  }
    
              }            
    
              $docs[]= array(
                  'file_id'=>$item->id, 
                  'file_name'=>$item->file_name,               
                  'attachment_type'=>$item->attachment_type, 
                  'filePath'=>url('/').'/uploads/report-files/'.$item->file_name,
                  'fileIcon'=>$type,
              );
          } 
    
          $form.= '<ul class="reorder_ul reorder-photos-list">';
          
            $i=0;
          foreach($docs as $file)
          {
            if($file['attachment_type'] == 'main')
            {
              $file_id=$file['file_id'];
              if(stripos($file['file_name'],'pdf')!==false){
                $path=url('/').'/admin/images/';
                $file_path=$path.$file->file_name;
                $file_name =$file['file_name'];
                $img='<img src="'.$file_path.'" alt="preview" style="height:100px;" title="'.$file_name.'">';
              }
              else{
                $file_path=$file['fileIcon'];
                $file_name =$file['file_name'];
                $img='<img src="'.$file_path.'" alt="preview" style="height:100px;"title="'.$file_name.'">';
              }
              
              $form.= '<li id="'.$file_id.'" class="ui-sortable-handle">
                    <a href="javascript:void(0);" style="float:none;" class="image_link">
                    '.$img.'
                    </a>
                </li>';
            }
          }
          $form.= '</ul>';
    
          return $form;
        }
        else{
          $form='';
          $reportFiles = DB::table('report_item_attachments as rf')
                          ->select('rf.id','rf.file_name','rf.created_at','rf.attachment_type','rf.file_platform')                        
                          ->where(['rf.report_item_id'=>$request->imageId,'rf.is_deleted'=>0,'rf.attachment_type'=>'supporting'])  
                          ->orderBy('rf.img_order','ASC')  
                          ->get();           
              // dd($reportFiles);
          $docs = array();
          foreach ($reportFiles as $item) {
    
              $type = url('/').'/admin/images/icon_docx.png';
              $extArray = explode('.', $item->file_name);
              $ext = end($extArray);
            
              if($item->file_name != NULL)
              {
                  if($ext == 'pdf')
                  {
                    $type = url('/').'/admin/images/icon_pdf.png';
                  } 
                  if($ext == 'doc' || $ext == 'docx')
                  {
                    $type = url('/').'/admin/images/icon_docx.png';
                  }
                  if($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv')
                  {
                    $type = url('/').'/admin/images/icon_xlsx.png';
                  }
                  if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'  || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
                  {  
                    if(stripos($item->file_platform,'s3')!==false)
                    {
                        $filePath = 'uploads/report-files/';

                        $s3_config = S3ConfigTrait::s3Config();

                        $disk = Storage::disk('s3');

                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                            'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                            'Key'                        => $filePath.$item->file_name,
                            'ResponseContentDisposition' => 'attachment;'//for download
                        ]);

                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                        $type = (string)$req->getUri();  
                    }
                    else
                    {
                      $type = url('/').'/uploads/report-files/'.$item->file_name;
                    }
                  }
                  if($ext == 'pptx')
                  {
                    $type = url('/').'/admin/images/icon_pptx.png';
                  }
    
              }            
    
              $docs[]= array(
                  'file_id'=>$item->id, 
                  'file_name'=>$item->file_name,               
                  'attachment_type'=>$item->attachment_type, 
                  'filePath'=>url('/').'/uploads/report-files/'.$item->file_name,
                  'fileIcon'=>$type,
              );
          } 
    
          $form.= '<ul class="reorder_ul reorder-photos-list">';
          
            $i=0;
          foreach($docs as $file)
          {
            if($file['attachment_type'] == 'supporting')
            {
              $file_id=$file['file_id'];
              if(stripos($file['file_name'],'pdf')!==false){
                $path=url('/').'/admin/images/';
                $file_path=$path.$file->file_name;
                $file_name =$file['file_name'];
                $img='<img src="'.$file_path.'" alt="preview" style="height:100px;" title="'.$file_name.'">';
              }
              else{
                $file_path=$file['fileIcon'];
                $file_name =$file['file_name'];
                $img='<img src="'.$file_path.'" alt="preview" style="height:100px;"title="'.$file_name.'">';
              }
              
              $form.= '<li id="'.$file_id.'" class="ui-sortable-handle">
                    <a href="javascript:void(0);" style="float:none;" class="image_link">
                    '.$img.'
                    </a>
                </li>';
            }
          }
          $form.= '</ul>';
    
          return $form;
        }
      }
      public function dragImageSave(Request $request)
      {
        // dd($request->imageIds);
        $order_number=$request->order_number;
        $jafImageType=$request->jafImageTypes;
        // $service_id=array_unique($service_id);
        // $new_arr[]=json_encode($order_number);
        // dd($order_number);
        $i=1;
        // $j=0;
        foreach($order_number as $order){
          // var_dump($order[$j]);
          // DB::table('jaf_item_attachments')->where('id',$order)->update(['img_order'=>$i]);
          DB::table('report_item_attachments')->where('id',$order)->update(['img_order'=>$i]);
          $i++;
        }
        if($jafImageType=='main'){
          $reportFiles = DB::table('report_item_attachments as rf')
                          ->select('rf.id','rf.report_item_id','rf.file_name','rf.created_at','rf.attachment_type','rf.file_platform')                        
                          ->where(['rf.report_item_id'=>$request->imageIds,'rf.is_deleted'=>0,'rf.attachment_type'=>'main'])  
                          ->orderBy('rf.img_order','ASC')  
                          ->get(); 
          $path = public_path().'/uploads/report-files/';
          $docs = array();
          $report_item_id=NULL;
          foreach ($reportFiles as $item) {
    
              $type = url('/').'/admin/images/icon_docx.png';
              $extArray = explode('.', $item->file_name);
              $ext = end($extArray);
              $report_item_id =$item->report_item_id;
              if($item->file_name != NULL)
              {
                  if($ext == 'pdf')
                  {
                    $type = url('/').'/admin/images/icon_pdf.png';
                  } 
                  if($ext == 'doc' || $ext == 'docx')
                  {
                    $type = url('/').'/admin/images/icon_docx.png';
                  }
                  if($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv')
                  {
                    $type = url('/').'/admin/images/icon_xlsx.png';
                  }
                  if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'  || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
                  { 
                    if(stripos($item->file_platform,'s3')!==false)
                    {
                        $filePath = 'uploads/report-files/';

                        $s3_config = S3ConfigTrait::s3Config();

                        $disk = Storage::disk('s3');

                        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                            'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                            'Key'                        => $filePath.$item->file_name,
                            'ResponseContentDisposition' => 'attachment;'//for download
                        ]);

                        $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                        $type = (string)$req->getUri();  
                    }
                    else
                    {
                      $type = url('/').'/uploads/report-files/'.$item->file_name;
                    } 
                  }
                  if($ext == 'pptx')
                  {
                    $type = url('/').'/admin/images/icon_pptx.png';
                  }
    
              }            
    
              $docs[]= array(
                  'file_id'=>$item->id, 
                  'file_name'=>$item->file_name,               
                  'attachment_type'=>$item->attachment_type, 
                  'filePath'=>url('/').'/uploads/report-files/'.$item->file_name,
                  'fileIcon'=>$type,
              );
          } 
          return response()->json([
            'fail' => false,
            'data'=>$docs,
            'report_item_id'=>$report_item_id,
            'attachment_type'=>$jafImageType
          ]);    
        }
        else{
          $reportFiles = DB::table('report_item_attachments as rf')
          ->select('rf.id','rf.report_item_id','rf.file_name','rf.created_at','rf.attachment_type','rf.file_platform')                        
          ->where(['rf.report_item_id'=>$request->imageIds,'rf.is_deleted'=>0,'rf.attachment_type'=>'supporting'])  
          ->orderBy('rf.img_order','ASC')  
                    ->get(); 
          $path = public_path().'/uploads/report-files/';
          $docs = array();
          $report_item_id=NULL;
          foreach ($reportFiles as $item) {

          $type = url('/').'/admin/images/icon_docx.png';
          $extArray = explode('.', $item->file_name);
          $ext = end($extArray);
          $report_item_id =$item->report_item_id;
          if($item->file_name != NULL)
          {
            if($ext == 'pdf')
            {
              $type = url('/').'/admin/images/icon_pdf.png';
            } 
            if($ext == 'doc' || $ext == 'docx')
            {
              $type = url('/').'/admin/images/icon_docx.png';
            }
            if($ext == 'xls' || $ext == 'xlsx' || $ext == 'csv')
            {
              $type = url('/').'/admin/images/icon_xlsx.png';
            }
            if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg'  || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
            { 
              if(stripos($item->file_platform,'s3')!==false)
              {
                  $filePath = 'uploads/report-files/';

                  $s3_config = S3ConfigTrait::s3Config();

                  $disk = Storage::disk('s3');

                  $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                      'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                      'Key'                        => $filePath.$item->file_name,
                      'ResponseContentDisposition' => 'attachment;'//for download
                  ]);

                  $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                  $type = (string)$req->getUri();  
              }
              else
              {
                $type = url('/').'/uploads/report-files/'.$item->file_name;
              } 
            }
            if($ext == 'pptx')
            {
              $type = url('/').'/admin/images/icon_pptx.png';
            }

          }            

          $docs[]= array(
            'file_id'=>$item->id, 
            'file_name'=>$item->file_name,               
            'attachment_type'=>$item->attachment_type, 
            'filePath'=>url('/').'/uploads/report-files/'.$item->file_name,
            'fileIcon'=>$type,
          );
          } 
          return response()->json([
          'fail' => false,
          'data'=>$docs,
          'report_item_id'=>$report_item_id,
          'attachment_type'=>$jafImageType
          ]);    
        }
      }
  

}
