<?php

namespace App\Http\Controllers\Vendor;

use App\Exports\VendorMultipleTask;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\Validator;
use App\Models\Vendor\VendorVerificationData;
use App\Models\Vendor\VendorVerificationStatus;
use Illuminate\Support\Facades\Mail;
use App\Models\Vendor\VendorTaskAssignment;
use App\User;
use App\Models\Vendor\VendorTask;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\S3ConfigTrait;
use Illuminate\Support\Facades\Storage;
use Mpdf\Tag\Span;
use ZipArchive;


class TaskController extends Controller
{
    //all vendor task
   public function index(Request $request)
   {
        $rows=10;
        
        $task_users = DB::table('users')->where(['business_id'=>Auth::user()->business_id,'user_type'=>'vendor_user','is_deleted'=>0,'status'=>1])->get();
        //dd($task_users);
        $services = DB::table('services')
                    ->select('name','id')
                    ->where(['business_id'=>NULL,'verification_type'=>'Manual'])
                    ->whereNotIn('type_name',['e_court'])
                    ->orwhere('business_id',Auth::user()->parent_id)
                    ->where(['status'=>'1'])
                    ->get();
                
        $vendor_tasks = DB::table('vendor_task_assignments')
                        ->whereIn('status',['1','2'])
                        ->where(['business_id'=>Auth::user()->business_id,'assigned_to'=>Auth::user()->id])->whereNull('reassigned_to')
                        ->orWhere(['reassigned_to'=>Auth::user()->id])
                        ->orderBy('id','DESC')->get();
        $user_name = DB::table('vendor_tasks as vt')->select('t.name','t.id')->join('tasks as t','t.id','=','vt.task_id')->where(['vt.business_id'=>Auth::user()->id])->whereIn('vt.status',['1','2'])->groupBy('name')->get();
        // dd($user_name);
        $tasks =  VendorTask::from('vendor_tasks as vt')
                    ->select('t.name','vt.*','s.name as servicename')
                    ->join('tasks as t','t.id','=','vt.task_id')
                    ->join('services as s','s.id','=','vt.service_id')
                    ->where(['vt.business_id'=>Auth::user()->id])
                    ->whereIn('vt.status',['1','2'])->orderBy('vt.id','DESC');
                    
                        if(is_numeric($request->get('completed_task'))!=''){
                             //echo ($request->get('completed_task'));
                            $tasks->where(['vt.status'=>$request->get('completed_task')]);
                        }
                        if(is_numeric($request->get('complete_status'))){
                            // echo($request->get('complete_status'));
                                $tasks->where('vt.status',$request->get('complete_status'));
                            }
                        if($request->get('from_date') !=""){
                            $tasks->whereDate('vt.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                        }
                        if($request->get('to_date') !=""){
                            $tasks->whereDate('vt.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                        }
                        // if(is_numeric($request->get('customer_id'))){
                        //      $tasks->where('t.business_id',$request->get('customer_id'));
                        // }
                        if($request->get('candidate_id')){
                            // echo($request->get('candidate_id'));
                             $tasks->where('t.name',$request->get('candidate_id'));
                        }
                        if(is_numeric($request->get('service_id'))){
                            // echo($request->get('service_id'));
                            $tasks->where('vt.service_id',$request->get('service_id'));
                        }
                        // if(is_numeric($request->get('user_id'))){
                        //     $tasks->where('ta.user_id',$request->get('user_id'));
                        // }
                        // if($request->get('task_type')){
                        //     $tasks->where('t.description',$request->get('task_type'));
                        // }
                        // if($request->get('assign_status')){
                           
                        //     if ($request->get('assign_status')=='assigned') {
                        //         $tasks->whereNotNull('assigned_to');
                        //     }
                        //     else{
                        //         $tasks->whereNull('assigned_to');
                        //     }
                           
                        // }
                        if(is_numeric($request->get('complete_status'))){
                        // echo($request->get('complete_status'));
                            $tasks->where('vt.status',$request->get('complete_status'));
                        }

                        if ($request->get('rows')!='') {
                            $rows = $request->get('rows');
                        }

                    $tasks=$tasks->get();
                   
                    if($request->get('raiseinsuff')!=''){
                        $tasks=$tasks->each(function ($task) {
                            $task->setAppends(['raiseinsuff']);
                        });

                       $tasks = $tasks->where('raiseinsuff','raise');
                    
                    }

                    if($request->get('in_tat')!='' || $request->get('out_tat')!='')
                    {
                        $tasks=$tasks->each(function ($task) {
                            $task->setAppends(['in_out_status']);
                        });

                        if($request->get('in_tat')!='')
                        {
                            $tasks = $tasks->where('in_out_status',1);
                        }
                        else if($request->get('out_tat')!='')
                        {
                            $tasks = $tasks->where('in_out_status',2);
                        }
                    }

                    //dd($tasks);

        $tasks=$tasks->paginate($rows);

        //dd($tasks);
        
        $in_tat = $request->get('in_tat');
        $out_tat = $request->get('out_tat');
        $completed_task=$request->get('completed_task');
        $raiseinsuff = $request->get('raiseinsuff');
       
        // dd($tasks);            
        if($request->ajax())
            return view('vendor.task.ajax',compact('tasks','task_users','vendor_tasks','services','user_name','completed_task','raiseinsuff','in_tat','out_tat'));
        else
            return view('vendor.task.index',compact('tasks','task_users','vendor_tasks','services','user_name','completed_task','raiseinsuff','in_tat','out_tat'));

   }

   public function generatePdf($id,$service_id,$check_number)
   {
       // $user_id = Auth::user()->id;

       $candidate_id=base64_decode($id);
       $service_id=base64_decode($service_id);
       $check_number=base64_decode($check_number);
        //  dd($candidate_id);

       $candidate = Db::table('candidate_reinitiates as u')
        ->select('u.id','u.business_id','u.parent_id','u.client_emp_code','u.entity_code','u.first_name','u.last_name','u.name','u.email','u.phone','u.dob','u.aadhar_number','u.father_name','u.gender','u.digital_signature')  
        ->where(['u.id'=>$candidate_id]) 
        ->first(); 
       
       $jaf_items = DB::table('jaf_form_data as jf')
       ->select('jf.id','jf.form_data_all','jf.form_data','jf.check_item_number','jf.address_type','jf.insufficiency_notes','jf.is_insufficiency','jf.is_api_checked','jf.verification_status','jf.verified_at','s.name as service_name','s.id as service_id','s.verification_type')
       ->join('services as s','s.id','=','jf.service_id')
       ->where(['jf.candidate_id'=>$candidate_id,'jf.service_id'=>$service_id,'jf.check_item_number'=>$check_number])
       ->first();
       // dd($jaf_items);
       // echo '<pre>';print_r($jaf_items);
       // die;
       // $sla_items = DB::select("SELECT sla_id, GROUP_CONCAT(DISTINCT service_id) AS alot_services FROM `job_sla_items` WHERE candidate_id = $candidate_id");
       if ($service_id==1) {
        //    dd($candidate);
            $pdf = PDF::loadView('vendor.task.pdf.address-pdf', compact('candidate','jaf_items'));
            return $pdf->download('jaf.pdf');
            // return view('vendor.task.pdf.address-pdf', compact('candidate','jaf_items'));
        }
        else {
            $pdf = PDF::loadView('vendor.task.pdf.jaf-pdf', compact('candidate','jaf_items'));
            return $pdf->download('jaf.pdf');
        }
   
   }

   public function uploadData(Request $request)
   {
        $business_id = Auth::user()->business_id;
            //   dd($request->vendor_task_id);
             $rules = [
                'attachment' => 'required',
                'verification_status' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
                
            if ($validator->fails()){
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors() 
                ]);
            }

         $vendor_task = DB::table('vendor_tasks')->where(['id'=>$request->vendor_task_id,'status'=>'1'])->first();
        if ($vendor_task) {
              
            $new_verification_status = new VendorVerificationStatus;
            $new_verification_status->parent_id =$vendor_task->parent_id;
            $new_verification_status->business_id =$vendor_task->business_id;
            $new_verification_status->candidate_id =$vendor_task->candidate_id;
            $new_verification_status->vendor_task_id=$vendor_task->id;
            $new_verification_status->service_id=$vendor_task->service_id;
            $new_verification_status->vendor_sla_id=$vendor_task->vendor_sla_id;
            $new_verification_status->no_of_verification =$vendor_task->no_of_verification;
            $new_verification_status->remarks = $request->remark;
            $new_verification_status->status =$request->verification_status;
            $new_verification_status->created_by=Auth::user()->id;
            $new_verification_status->save();

         
            // batch attachment strat
            $s3_config=NULL;
            $attach_on_select=[];
            $allowedextension=['jpg','jpeg','png','pdf','JPG','PDF','JPEG','PNG'];
            $zip_name="";
            $file_platform = 'web';
            //  $now= Carbon::parse($new_batch->created_at)->format('Ymdhis');
            if($request->hasFile('attachment') && $request->file('attachment') !="")
            {                                     
                $filePath = public_path('/uploads/verification-file/'); 
                $files= $request->file('attachment'); 
                foreach($files as $file)
                {
                        $extension = $file->getClientOriginalExtension();
                        $check = in_array($extension,$allowedextension);
                        if(!$check)
                        {
                            return response()->json([
                            'fail' => true,
                            'errors' => ['attachment' => 'Only jpg,jpeg,png,pdf are allowed !'],
                            'error_type'=>'validation'
                            ]);
                        }
                }
    
                $zipname = 'verification_file-'.$vendor_task->id.'.zip';
                $zip = new \ZipArchive();      
                $zip->open(public_path().'/uploads/verification-file/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                foreach($files as $file)
                {
                    $file_data = $file->getClientOriginalName();
                    $tmp_data  = $vendor_task->id.'-'.$file_data; 
                    $data = $file->move($filePath, $tmp_data);       
                    $attach_on_select[]=$tmp_data;

                    $path=public_path()."/uploads/verification-file/".$tmp_data;
                    $zip->addFile($path, '/verification-file/'.basename($path));  
                }
                
                $zip->close();
            }

              $file_platform = 'web';
              $s3_config = S3ConfigTrait::s3Config();

              $path=public_path('/uploads/verification-file/');

              if($s3_config!=NULL && $zipname!='')
              {
                if(File::exists($path.$zipname))
                {
                    $file_platform = 's3';

                    $s3filePath = 'uploads/verification-file/';
    
                    if(!Storage::disk('s3')->exists($s3filePath))
                    {
                        Storage::disk('s3')->makeDirectory($s3filePath,0777, true, true);
                    }
    
                    $file = Helper::createFileObject($path.$zipname);
    
                    Storage::disk('s3')->put($s3filePath.$zipname,file_get_contents($file));

                    File::delete($path.$zipname);
                }

              }

            if(count($attach_on_select)>0)
            {
                $i=0;
                foreach($attach_on_select as $item) 
                {
                    $new_verification_data= new VendorVerificationData;
                    $new_verification_data->parent_id =$vendor_task->parent_id;
                    $new_verification_data->business_id =$vendor_task->business_id;
                    $new_verification_data->candidate_id =$vendor_task->candidate_id;
                    $new_verification_data->vendor_task_id=$vendor_task->id;
                    $new_verification_data->service_id=$vendor_task->service_id;
                    $new_verification_data->vendor_sla_id=$vendor_task->vendor_sla_id;
                    $new_verification_data->vendor_verification_status_id =$new_verification_status->id;
                    $new_verification_data->no_of_verification =$vendor_task->no_of_verification;
                    $new_verification_data->file_name=$attach_on_select[$i];
                    $new_verification_data->zip_file = $zipname!=""?$zipname:NULL;
                    $new_verification_data->file_platform = $file_platform;
                    $new_verification_data->created_by = Auth::user()->id;
                    $new_verification_data->save();
                    $i++;
                }
            }

            $status = DB::table('vendor_verification_statuses')->select('status','remarks')->where('vendor_task_id',$request->vendor_task_id)->first();
            if ($status->status == 'done') {
                 DB::table('vendor_tasks')->where(['id'=>$request->vendor_task_id,'status'=>'1'])->update(['status'=>'2','completed_at'=>date('Y-m-d H:i:s'),'completed_by'=>Auth::user()->id]);
                 DB::table('vendor_task_assignments')->where(['vendor_task_id'=>$request->vendor_task_id,'status'=>'1'])->update(['status'=>'2']);
                 $completed_vendor_task=  DB::table('vendor_tasks')->where(['id'=>$request->vendor_task_id,'status'=>'2'])->first();
                 DB::table('task_assignments')->where(['task_id'=>$completed_vendor_task->task_id,'status'=>'1'])->update(['status'=>'2']);
                 DB::table('tasks')->where(['id'=>$completed_vendor_task->task_id,'is_completed'=>'0'])->update(['is_completed'=>'1']);

                $user= User::where('id',$completed_vendor_task->assigned_by)->first();
                if ($user->email) {
                    $email = $user->email;
                    $name  = $user->name;
                    $remarks  = $status->remarks;
                    $candidate_name =  Helper::candidate_user_name($completed_vendor_task->candidate_id);
                    $service_name = Helper::get_service_name($completed_vendor_task->service_id);
                    $msg = "Task has been completed of candidate";
                    $sender = DB::table('users')->where(['id'=>$business_id])->first();
                    $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name, 'remarks'=>$remarks ,'msg'=>$msg,'service_name'=>$service_name,'sender'=>$sender);
                    
                    Mail::send(['html'=>'mails.completed-task'], $data, function($message) use($email,$name) {
                        $message->to($email, $name)->subject
                            ('myBCD System - Task Completed Notification');
                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    });
                }

                $completed_notification_task=  DB::table('notification_control_configs')->where(['business_id'=>$completed_vendor_task->business_id,'status'=>'1'])->get();
                
                if(count($completed_notification_task)>0)
                {
                    foreach($completed_notification_task as $item)
                    {
                        $email = $item->email;
                        $name = $item->name;
                        $remarks  = $status->remarks;
                        $candidate_name =  Helper::candidate_user_name($completed_vendor_task->candidate_id);
                        $service_name = Helper::get_service_name($completed_vendor_task->service_id);
                        $msg = "Task has been completed of candidate";
                        $sender = DB::table('users')->where(['id'=>$business_id])->first();
                        $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name, 'remarks'=>$remarks ,'msg'=>$msg,'service_name'=>$service_name,'sender'=>$sender);

                        Mail::send(['html'=>'mails.completed-task'], $data, function($message) use($email,$name) {
                            $message->to($email, $name)->subject
                                ('myBCD System - Task Completed Notification');
                            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        });

                    }
                }
            }
                // dd($updated_vendor_task);
        }
        return response()->json([
            'success' => true,
            'errors' => []
        ]);
   }

    public function taskPreview(Request $request)
    {

        $form='';
        $task_id=$request->task_id;
        $vendor_task = DB::table('vendor_tasks')->where(['id'=>$task_id])->whereIn('status',['1','2'])->first();
        if ($vendor_task) {
            $upload_attach=DB::table('vendor_verification_data')
            ->where(['vendor_task_id'=>$task_id])->get();

            $data=DB::table('vendor_verification_statuses')
            ->where(['vendor_task_id'=>$task_id])->first();
            if($data->remarks==NULL){
                $comments='N/A';
                // $status = $data->status=='done'?'Done' :'Unable to verify';
             } else{
                $comments=$data->remarks;
                // $status = $data->status=='done'?'Done' :'Unable to verify';
                
             }
             if($data->status==NULL){
                $status='N/A';
                // $status = $data->status=='done'?'Done' :'Unable to verify';
             } else{
                // $comments=$data->remarks;
                $status = $data->status=='done'?'Done' :'Unable to verify';
                
             }
            // dd($data);
            if(count($upload_attach)>0)
            {
                $form.='<div class="form-group">
                        <label><strong>Remarks:</strong> '.$comments.'</label>
                    </div>';

                $form.='<div class="form-group">
                        <label for="label_name"> <strong>Status: </strong><span id="comments">'.$status.'</span></label>
                    </div>';
                $path=url('/').'/uploads/verification-file/';
                $form.='<div class="form-group">
                        <label><strong>Attachments: </strong></label>
                        <div class="row">';
                foreach($upload_attach as $upload)
                {
                   
                    $img='';
                    $file=$path.$upload->file_name;
                    $temp= explode('.',$upload->file_name);
                    $extension = end($temp);
                    // if ($extension=='pdf') {
                    $img='<img src="'.$file.'" alt="preview" style="height:100px;"/>';
                    // $form=
                    if ($extension=='pdf') {
                       
                          $type = url('/').'/admin/images/icon_pdf.png';
                          $img='<img src="'.$type.'" alt="preview" style="height:100px;"/>';
                        $form.='<div class="col-3">
                                <div class="image-area" style="width:110px;">
                                    <a href="'.$file.'" target="_blank">
                                        '.$img.'
                                        <p style="font-size:15px;">'.'<i class="fa fa-eye">'.' '.'<small>'.'Preview'.'</small>'.'</i>'.'</p>
                                    </a>
                                </div>
                                </div>';
                    } else {
                        $form.='<div class="col-3">
                                <div class="image-area" style="width:110px;">
                                    <a href="'.$file.'" download>
                                        '.$img.'
                                        <p style="font-size:15px;">'.'<i class="fas fa-file-download" >'.' '.'<small>'.'Download'.'</small>'.'</i>'.'</p>
                                    </a>
                                </div>
                                </div>';
                    }
                } 
                $form.='</div>
                        </div>';
            }
            return $form;
        }
        // dd($task_id);
    }
    
    //Assign task to user
    public function assignUser(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $rules = [
            'users' => 'required',
              //'user_status' => 'required'
            
          ];
          $validator = Validator::make($request->all(), $rules);
          if ($validator->fails()){
              return response()->json([
                  'success' => false,
                  'errors' => $validator->errors()
              ]);
          }
     //    dd($request->users);
        $task_id = $request->vendors_task_id;
        $vendor_task =DB::table('vendor_task_assignments')->where(['vendor_task_id'=>$task_id,'status'=>'1'])->first();
        // dd($vendor_task);
        if ($vendor_task) {
            
            DB::table('vendor_task_assignments')->where(['vendor_task_id'=>$task_id,'status'=>'1'])->update(['assigned_to'=>$request->users,'assigned_by'=>Auth::user()->id,'assigned_at'=>date('Y-m-d H:i:s')]);
           


            $user= User::where('id',$request->users)->first();
                if ($user->email) {
                    $email = $user->email;
                    $name  = $user->name;
                    $candidate_name =  Helper::candidate_user_name($vendor_task->candidate_id);
                    $service_name = Helper::get_service_name($vendor_task->service_id);
                    $vendor_id =  Helper::user_name($vendor_task->business_id);
                    $remarks = '';
                    $msg = "Task has been assigned to you with the name of candidate";
                    $sender = DB::table('users')->where(['id'=>$business_id])->first();
                    $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'remarks'=>$remarks,'msg'=>$msg,'service_name'=>$service_name,'vendor_id'=>$vendor_id,'sender'=>$sender);
        
                    Mail::send(['html'=>'mails.completed-task'], $data, function($message) use($email,$name) {
                        $message->to($email, $name)->subject
                            ('myBCD System - Task Verification Notification');
                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    });
                }
            return response()->json([
                'success' => true,
                'errors' => []
            ]);
        }
    }
       //Re-Assign task to user
    public function reassignUser(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $rules = [
            'users' => 'required',
              //'user_status' => 'required'
            
          ];
          $validator = Validator::make($request->all(), $rules);
          if ($validator->fails()){
              return response()->json([
                  'success' => false,
                  'errors' => $validator->errors()
              ]);
          }
        $task_id = $request->reassign_task_id;
        $vendor_task =DB::table('vendor_task_assignments')->where(['vendor_task_id'=>$task_id,'status'=>'1'])->whereNotNull('assigned_to')->first();

        if ($vendor_task) {
            
            DB::table('vendor_task_assignments')->where(['vendor_task_id'=>$task_id,'status'=>'1'])->whereNotNull('assigned_to')->update(['status'=>'0']);

            $new_vendor_task_assign = new VendorTaskAssignment;
            $new_vendor_task_assign->parent_id = Auth::user()->parent_id;
            $new_vendor_task_assign->business_id =  Auth::user()->business_id;
            $new_vendor_task_assign->candidate_id = $vendor_task->candidate_id;
            $new_vendor_task_assign->vendor_task_id = $task_id;
            $new_vendor_task_assign->service_id = $vendor_task->service_id;
            $new_vendor_task_assign->vendor_sla_id = $vendor_task->vendor_sla_id;
            $new_vendor_task_assign->status = '1';
            $new_vendor_task_assign->no_of_verification = $vendor_task->no_of_verification;
            $new_vendor_task_assign->assigned_to = $vendor_task->assigned_to;
            $new_vendor_task_assign->assigned_by =$vendor_task->assigned_by;
            $new_vendor_task_assign->assigned_at = $vendor_task->assigned_at;
            $new_vendor_task_assign->reassigned_to = $request->users;
            $new_vendor_task_assign->reassigned_by = Auth::user()->id;
            $new_vendor_task_assign->reassigned_at = date('Y-m-d H:i:s');
            $new_vendor_task_assign->updated_by = Auth::user()->id;
            $new_vendor_task_assign->save();


            $user= User::where('id',$request->users)->first();
            if ($user->email) {
                $email = $user->email;
                $name  = $user->name;
                $candidate_name =  Helper::candidate_user_name($vendor_task->candidate_id);
                $service_name = Helper::get_service_name($vendor_task->service_id);
                $vendor_id =  Helper::user_name($vendor_task->business_id);
                $msg = "Task has been assigned to you with the name of candidate";
                $remarks = '';
                $sender = DB::table('users')->where(['id'=>$business_id])->first();
                $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'remarks'=>$remarks,'msg'=>$msg,'service_name'=>$service_name,'vendor_id'=>$vendor_id,'sender'=>$sender);
    
                Mail::send(['html'=>'mails.completed-task'], $data, function($message) use($email,$name) {
                    $message->to($email, $name)->subject
                        ('myBCD System - Task Verification Notification');
                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                });
            }
            return response()->json([
                'success' => true,
                'errors' => []
            ]);
        }
       
    }

     /**
     * set the session data
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function setSessionData( Request $request)
    {   
        //clear session data 
        // Session()->forget('customer_id');
        Session()->forget('task_id');
        Session()->forget('to_date');
        Session()->forget('from_date');
        Session()->forget('export_type');

        // Session()->forget('jaf_id');
        // Session()->forget('service_id');
        // dd($request->get('export_type'));
        if( ($request->get('export_type')) ){             
            session()->put('export_type', $request->get('export_type'));
        }
        if( ($request->get('task_id')) ){             
          session()->put('task_id', $request->get('task_id'));
        }
        // both date is selected 
        if($request->get('report_date') !="" && $request->get('to_date') !=""){
            session()->put('report_from_date', $request->get('report_date'));
            session()->put('report_to_date', $request->get('to_date'));
        }
        else
        {
          if($request->get('from_date') !=""){
            session()->put('from_date', $request->get('from_date'));
          }
          if($request->get('to_date') !=""){
            session()->put('to_date', $request->get('to_date'));
          }
        }
        //
        // if($request->get('check_id') !=""){
        //   session()->put('check_id', $request->get('check_id'));
        // }

        // if($request->get('jaf_id')!="")
        // {
        //   session()->put('jaf_id', $request->get('jaf_id'));
        // }

        // if($request->get('service_id')!="")
        // {
        //   session()->put('service_id', $request->get('service_id'));
        // }

        echo '1';
    }

    public function pdfExport(Request $request)
    {
        // dd($request->session()->get('export_type'));
        // var_dump($request->task_id);
        $from_date = $to_date= $customer_id=$business_id = $check_id = "";

      if( $request->session()->has('from_date') && $request->session()->has('to_date'))
        {  
          $from_date     =  $request->session()->get('from_date');
          $to_date       =  $request->session()->get('to_date');
        }
        else
        {
          if($request->session()->has('from_date'))
          {
            $from_date     =  $request->session()->get('from_date');
          }
        }
        if($request->session()->has('task_id'))
          {
            $vendor_task_id  =  $request->session()->get('task_id');
          }
          if($request->session()->has('export_type'))
          {
            $export_type  =  $request->session()->get('export_type');
          }
        // $vendor_task_id = $request->session()->get('task_id');
        // $vendor_tasks = DB::table('vendor_tasks')->whereIn('id',$vendor_task_id)->get();
        $service_id=[];
        // $candidate_id=[];
        $task_id=[];
            if ($export_type=='details') {
                // dd($vendor_task_id);
                foreach ($vendor_task_id as $key => $task) {
                
                    $tasks =  DB::table('vendor_tasks')->where(['id'=>$task,'status'=>'1'])->whereNotIn('service_id',[1])->first();
                    // dd($tasks);
                    if ($tasks) {
                        
                        $task_id[]= $tasks->id;
                        $service_id[] = $tasks->service_id;
                        // $no_of_verifications[] =  $tasks->no_of_verification;
                    }
                    // else {
                    //     # code...
                    // }
                }
                
                    //$candidate_id=array_values($candidate_id);
                $task_id=array_values($task_id);
                    // $no_of_verifications=array_values($no_of_verifications);
                    // dd($task_id); 
                    $service= array_unique($service_id);
                    sort($service);
                    // dd($service);
                    //rsort($candidate_id);
                    // foreach ($candidate_id as $key => $id) {
                    //   $job_sla_items=  DB::table('job_sla_items')->select('service_id','number_of_verifications')->where('candidate_id',$id)->get();
                    // }
                //dd($candidate_id);
                return Excel::download(new VendorMultipleTask($from_date,$to_date,$service,$task_id,$export_type),'task-all-checks-data.xlsx');
            }
            elseif ($export_type=='attachment') {
                $login = Auth::user()->id;
                $path=public_path('/uploads/candidate_details/'.$login.'/');
                if(File::exists($path))
                  {
                      File::cleanDirectory($path);
                  }
                   
                if (count($vendor_task_id)>0) {
                    $filePath = public_path('/uploads/candidate_details/'.$login.'/' );
                    if(!File::exists($filePath))
                    {
                        File::makeDirectory($filePath, $mode = 0777, true, true);
                    }
                    foreach ($vendor_task_id as $vendor_task) {
                        $tasks =  DB::table('vendor_tasks')->where(['id'=>$vendor_task,'status'=>'1'])->first();
                        // dd($tasks);
                        if ($tasks) {
                            $user = DB::table('candidate_reinitiates')->select('first_name')->where('id',$tasks->candidate_id)->first();
                            $service = DB::table('services')->select('name')->where('id',$tasks->service_id)->first();
                            $jaf_data_forms = DB::table('jaf_form_data')->where(['candidate_id'=>$tasks->candidate_id,'service_id'=>$tasks->service_id,'check_item_number'=>$tasks->no_of_verification])->first();

                            $zipname = '';
                        
                            $jaf_item_attachments = DB::table('jaf_item_attachments')->where(['jaf_id'=>$jaf_data_forms->id])->get();
                            // dd($jaf_item_attachments);
                            if(count($jaf_item_attachments)>0){
                                $zipname =  $user->first_name.'-'.$service->name.'-'.date('Ymdhis').'-'.$tasks->no_of_verification.'.zip';
                                $zip = new \ZipArchive();
                                $zip->open(public_path().'/uploads/candidate_details/'.$login.'/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                                foreach($jaf_item_attachments as $file)
                                {
                                    $file_data = $file->file_name;
                                    $tmp_data  = $user->first_name.'-'.$service->name.'-'.date('mdYHis').'-'.$file_data; 
                                    File::copy(public_path('/uploads/jaf-files/'.$file_data),public_path("/uploads/candidate_details/".$login.'/'.$tmp_data)); 
                                    // $data = $file_data->move($filePath, $tmp_data);
                                    $attach_on_select[]=$tmp_data; 
                                    $path=public_path()."/uploads/candidate_details/".$login.'/'.$tmp_data;
                                    $zip->addFile($path,'/candidate_details/'.basename($path));
                                }
                                $zip->close();
                            }
                        }
                    }
                    $megazipname='';
                    $files = File::files( $filePath );
                    if (count($files)>0) {
                        $megafilePath = public_path('/uploads/candidate_details/export/'.$login.'/' );
                        $megazipname ='all_export.zip';
                        $megazip = new \ZipArchive();
                        $megazip->open(public_path().'/uploads/candidate_details/export/'.$login.'/'.$megazipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                        if(!File::exists($megafilePath))
                        {
                            File::makeDirectory($megafilePath, $mode = 0777, true, true);
                        }
                       
                        foreach ($files as $key => $file) {
                            // $filename=$file->getClientOriginalName()
                            $filename=$file->getFilename();
                            // dd();
                            $temp= explode('.',$file);
                            $extension = end($temp);
                            if ($extension=='zip') {
                                // dd($extension);
                                $megapath=public_path()."/uploads/candidate_details/".$login.'/'.$filename;
                                $megazip->addFile($megapath,'/export/'.basename($megapath));
                            }

                        }
                        $megazip->close();
                    } 
                    
                    // $file = public_path()."/guest/reports/zip/".$guest_master->zip_name;
                    $headers = array('Content-Type: application/zip');
                    return response()->download($megafilePath.$megazipname,$megazipname,$headers);
                }
            } 
        // dd($vendor_tasks);
        // if (count($vendor_tasks)>0) {
        //     foreach ($vendor_tasks as $vendor_task) {
        //         $user = DB::table('candidate_reinitiates')->select('first_name')->where('id',$vendor_task->candidate_id)->first();
        //         $service = DB::table('services')->select('name')->where('id',$vendor_task->service_id)->first();
        //         $jaf_data_forms = DB::table('jaf_form_data')->where(['candidate_id'=>$vendor_task->candidate_id,'service_id'=>$vendor_task->service_id,'check_item_number'=>$vendor_task->no_of_verification])->first();
                
        //         $zipname =  $user->first_name.'-'.$service->name.'-'.date('Ymdhis').'-'.$vendor_task->no_of_verification.'.zip';
        //         $zip = new \ZipArchive();      
        //         $zip->open(public_path().'/uploads/candidate_details/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        //         $jaf_item_attachments = DB::table('jaf_item_attachments')->where(['jaf_id'=>$jaf_data_forms->id])->get();
        //         if(count($jaf_item_attachments)>0){
        //             foreach($jaf_item_attachments as $file)
        //             {
        //                 $file_data = $file->getClientOriginalName();
        //                 $tmp_data  = $user->first_name.'-'.$service->name.'-'.date('mdYHis').'-'.$file_data; 
        //                 $data = $file->move($filePath, $tmp_data);
        //                 $attach_on_select[]=$tmp_data;
        //                 $path=public_path()."/uploads/candidate_details/".$tmp_data;
        //                 $zip->addFile($path,'/candidate_details/'.basename($path));  
        //             }
        //         }
                
        //     }
        // }
       
    }

    public function vendorraiseInsuffshow(Request $request)
    {
        $candidate_id = base64_decode($request->cand_id); 
        $service_id   = base64_decode($request->service_id); 
        $number_ver   = base64_decode($request->number_id); 
        
        $vendor_insuffs = DB::table('vendor_insufficiencies')->where(['candidate_id'=>$candidate_id,'service_id'=>$service_id,'no_of_verification'=>$number_ver])->get();
      
        $vendor_attachments = DB::table('vendor_insufficiency_attachments')->where(['vendor_insuf_id'=>$candidate_id,'vendor_insuf_id'=>$service_id,'vendor_insuf_id'=>$number_ver])->get();
     
        $viewRender = view('vendor.task.raise-insuff-show',compact('vendor_insuffs','vendor_attachments'))->render();

        return response()->json(
            array(
              'success' => true, 
              'result' => '',
              'html'=>$viewRender
            )
          );
    }

    public function vendorraiseInsuff(Request $request)
    {   
      //dd($request->get('candidate_id'));

       $rules= [
        'comments'  => 'required',
        ];
        
        $vendor_id = base64_decode($request->ver_id); 
        $candidate_id = base64_decode($request->can_id); 
        $service_id   = base64_decode($request->ser_id); 
        $number_ver   = base64_decode($request->number_ver); 
        $vendorname = Auth::user()->name;
        //$item_id = base64_decode($request->jaf_id);
        // $item_id      = base64_decode($request->jaf_id); 
        
        $vein_id = Auth::user()->id;
        $business_id = Auth::user()->business_id;
    
        // $is_updated= FALSE;

        $parent_id=Auth::user()->parent_id;
        if(Auth::user()->user_type=='vendor' || Auth::user()->user_type=='vendor')
        {
          $users=DB::table('users')->select('parent_id')->where('id',$business_id)->first();
          $parent_id=$users->parent_id;
        }
      
        $validator = Validator::make($request->all(), $rules);
      
        if ($validator->fails()){
            return response()->json([
                'fail' => true,
                'errors' => $validator->errors(),
                'error_type'=>'validation'
            ]);
        }
   
        DB::beginTransaction();
        try
        {
            $vendor_insufficiencies=[
                'vendor_id' => $vein_id,
                'business_id' => $business_id,
                'candidate_id' => $candidate_id,
                'service_id' => $service_id,
                'no_of_verification' => $number_ver,
                'status' => 'raise',
                'comments' => $request->comments,
                'created_by' => $vein_id,
                'created_at'    =>date('Y-m-d H:i:s')
            ];
          
            $vendor_insuff_id = DB::table('vendor_insufficiencies')->insertGetId($vendor_insufficiencies);
    
            $s3_config=NULL;
            $attach_on_select=[];
            $zipname="";
            $file_platform = 'web';
            $allowedextension=['jpg','jpeg','png','svg','pdf','JPG','PDF','JPEG','PNG'];
          
              if($request->hasFile('attachments') && $request->file('attachments') !=""){
                
                  $filePath = public_path('/uploads/vendor-raise-insuff/');
                  
                  $files= $request->file('attachments');
                    //dd($files);
                  foreach($files as $file)
                  {
                      $extension = $file->getClientOriginalExtension();
  
                      $check = in_array($extension,$allowedextension);
  
                      if(!$check)
                      {
                          return response()->json([
                            'fail' => true,
                            'errors' => ['attachments' => 'Only jpg,jpeg,png,pdf are allowed !'],
                            'error_type'=>'validation'
                          ]);                        
                      }
                  }

                  $zipname = 'raise-insuff-'.date('Ymdhis').'.zip';
                  $zip = new \ZipArchive();      
                  $zip->open(public_path().'/uploads/vendor-raise-insuff/'.$zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                  foreach($files as $file)
                  {
                      $file_data = $file->getClientOriginalName();
                      $tmp_data  = $candidate_id.'-'.date('mdYHis').'-'.$file_data; 
                      $data = $file->move($filePath, $tmp_data);       
                      $attach_on_select[]=$tmp_data;

                      $path=public_path()."/uploads/vendor-raise-insuff/".$tmp_data;
                      $zip->addFile($path, '/vendor-raise-insuff/'.basename($path));  
                  }

                  $zip->close();


                  $s3_config = S3ConfigTrait::s3Config();

                  $path=public_path().'/uploads/vendor-raise-insuff/';
    
                  if($s3_config!=NULL && $zipname!='')
                  {
                    if(File::exists($path.$zipname))
                    {
                        $file_platform = 's3';
    
                        $s3filePath = 'uploads/vendor-raise-insuff/';
        
                        if(!Storage::disk('s3')->exists($s3filePath))
                        {
                            Storage::disk('s3')->makeDirectory($s3filePath,0777, true, true);
                        }
        
                        $file = Helper::createFileObject($path.$zipname);
        
                        Storage::disk('s3')->put($s3filePath.$zipname,file_get_contents($file));
    
                        File::delete($path.$zipname);
                    }
    
                  }

                  //dd($request->attachments);
            
                //   foreach($files as $file)
                //   {
                //       $file_data = $file->getClientOriginalName();
                //       $tmp_data  = $candidate_id.'-'.date('mdYHis').'-'.$file_data; 
                //       $data = $file->move($filePath, $tmp_data);       
                //       $attach_on_select[]=$tmp_data;
                //       $path=public_path()."/uploads/vendor-raise-insuff/".$tmp_data; 
                //   }

                  $vendor_insuff_attachment = ([
                    'vendor_insuf_id' => $vendor_insuff_id,
                    'attachments' => $zipname!=NULL?$zipname:NULL,
                    'created_at'    =>date('Y-m-d H:i:s')
                  ]);

                  DB::table('vendor_insufficiency_attachments')->insert($vendor_insuff_attachment);
              }
              $comments = DB::table('vendor_insufficiencies')->where(['comments'=>$request->comments])->first();
             
              $tasksId = DB::table('vendor_tasks')->where(['service_id'=>$service_id,'candidate_id'=>$candidate_id,'no_of_verification'=>$number_ver])->first();
              //dd($tasksId);
              $tasksId = $tasksId->assigned_by;
              $vendoruserId = DB::table('users')->where('id',$tasksId)->first();
              $commentsdata = $comments->comments;
              $servicedata = $comments->service_id;
              $name = $vendoruserId->name;
              $email = $vendoruserId->email;
              $msg = "Kindly clear the insuff through your login credentials";
              $sender = DB::table('users')->where(['id'=>$business_id])->first();
              $data  = array('name'=>$name,'email'=>$email,'commentsdata'=>$commentsdata,'vendorname'=>$vendorname,'servicedata'=>$servicedata,'msg'=>$msg,'sender'=>$sender);
              Mail::send(['html'=>'mails.vendor-insuff-raise'], $data, function($message) use($email,$name) {
                    $message->to($email, $name)->subject
                      ('myBCD System - Insuff Raise');
                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
              });

            DB::commit();
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
}
