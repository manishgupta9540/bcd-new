<?php

namespace App\Http\Controllers\Admin;

use App\Exports\allChecksExport;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Admin\Task;
use App\Models\Admin\TaskAssignment;
use App\Models\Admin\VendorTaskAttachment;
use App\Models\Vendor\VendorTask;
use App\Models\Vendor\VendorTaskAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\EmailConfigTrait;
use Illuminate\Support\Facades\Config;
use App\Traits\CommonTrait;
use Illuminate\Support\Facades\File;
use App\Traits\S3ConfigTrait;

class TaskController extends Controller
{
    
    /**
    * Create a new controller instance.
    *
    * @return void
    */

    public function __contruct()
    {
        ini_set('memory_limit', '-1');

        ini_set('max_execution_time', '0');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request);,compact()
        $user_id = Auth::user()->id;
        $business_id =Auth::user()->business_id;
        $cam=[];
        $tasks=[];
        $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        // foreach ($kams as $kam) {
        //    $cam[]= $kam->user_id;
        // }
        $cam=$kams->pluck('user_id')->all();
        // dd($cam);
        //   if (Auth::user()->user_type=='customer') {
          # code...
        $rows=10;

        // $clients = DB::table('users as u')
        // ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        // ->join('user_businesses as b','b.business_id','=','u.id')
        // ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        // ->get();

        // $users_list = User::where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])->get();

        $users = DB::table('users as u')
        ->join('role_masters as rm', 'rm.id', '=', 'u.role')
        ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
        ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
        ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0,'u.status'=>1])
        ->get();


        // $all_users = DB::table('users')->where(['user_type'=>'user','business_id'=>Auth::user()->business_id]);
         
        // $user_service = DB::table('users as u')
        // ->join('role_masters as rm', 'rm.id', '=', 'u.role')
        // ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
        // ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
        // ->select('uc.checks','u.id' )
        // ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0])
        // ->get();

        // echo"<pre>";
        // print_r($user_service);
        // die; 
        // dd($user_service);
        $action_master = DB::table('action_masters')
        ->select('*')
        ->where('route_group','=','')
        ->get();

        // $services = DB::table('services')
        // ->select('name','id')
        // ->where('business_id',NULL)
        // ->whereNotIn('type_name',['e_court'])
        // ->orwhere('business_id',$business_id)
        // ->where(['status'=>'1'])
        // ->get();
            //dd($tasks);

        // $candidates = DB::table('candidate_reinitiates')
        //     ->select('id','display_id','first_name','middle_name','last_name','phone','name')
        //     ->where(['parent_id'=>$business_id,'user_type'=>'candidate'])
        //     ->orderBy('id','DESC')
        //     ->get(); 
               
        // dd($tasks);

        // if($request->ajax())
        //     return view('admin.task.all-task.ajax',compact('tasks','action_master','users','users_list','cam'));
        // else
        //     return view('admin.task.all-task.index',compact('tasks','action_master','users','users_list','cam'));

        if($request->ajax())
        {
            if (Auth::user()->user_type=='customer') {

                $tasks =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created','u.display_id')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
                ->whereIn('ta.status',['1','2'])->orderBy('ta.updated_at','DESC');
    
                if($request->get('from_date') !=""){
                    $tasks->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                // if($request->get('ref_list')){
                //     $value=$request->get('ref_list');
                //         $tasks->whereIn('u.display_id',explode(',',$value));
                // }
                if($request->get('ref')){
                    $tasks->where('u.display_id',$request->get('ref'));
                }
    
                if($request->get('to_date') !=""){
                    $tasks->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                if(is_numeric($request->get('customer_id'))){
                     $tasks->where('t.business_id',$request->get('customer_id'));
                }
                if(is_numeric($request->get('candidate_id'))){
                     $tasks->where('t.candidate_id',$request->get('candidate_id'));
                }
                if(is_numeric($request->get('service_id'))){
                // echo($request->get('service_id'));
                    $tasks->where('t.service_id',$request->get('service_id'));
                }
                if(is_numeric($request->get('user_id'))){
                    $tasks->where('ta.user_id',$request->get('user_id'));
                }
                if($request->get('task_type')){
                    $tasks->where('t.description',$request->get('task_type'));
                }
                if($request->get('assign_status')){
                   
                    if ($request->get('assign_status')=='assigned') {
                        $tasks->whereNotNull('t.assigned_to');
                    }
                    else{
                        $tasks->whereNull('t.assigned_to');
                    }
                   
                }
                if(is_numeric($request->get('complete_status'))){
                    // echo($request->get('complete_status'));
                    $tasks->where('t.is_completed',$request->get('complete_status'));
                }
    
                //dd($tasks->assigned_to);
            }
            else if(in_array($user_id,$cam))
            {
                $task_kam = DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created','u.display_id')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
                    ->whereIn('ta.status',['1','2'])
                    ->whereIn('u.business_id',$kams->pluck('business_id')->all())
                    ->orderBy('ta.updated_at','DESC');
    
                    if($request->get('from_date') !=""){
                        $task_kam->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                        // if($request->get('ref_list')){
                        //     $value=$request->get('ref_list');
                        //         $task_kam->whereIn('u.display_id',explode(',',$value));
                        // }
                    if($request->get('ref')){
                        $task_kam->where('u.display_id',$request->get('ref'));
                    }
        
                    if($request->get('to_date') !=""){
                        $task_kam->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    if(is_numeric($request->get('customer_id'))){
                         $task_kam->where('t.business_id',$request->get('customer_id'));
                    }
                    if(is_numeric($request->get('candidate_id'))){
                         $task_kam->where('t.candidate_id',$request->get('candidate_id'));
                    }
                    if(is_numeric($request->get('service_id'))){
                        // echo($request->get('service_id'));
                        $task_kam->where('t.service_id',$request->get('service_id'));
                    }
                    if(is_numeric($request->get('user_id'))){
                        $task_kam->where('ta.user_id',$request->get('user_id'));
                    }
                    if($request->get('task_type')){
                        $task_kam->where('t.description',$request->get('task_type'));
                    }
                    if($request->get('assign_status')){
                       
                        if ($request->get('assign_status')=='assigned') {
                            $task_kam->whereNotNull('t.assigned_to');
                        }
                        else{
                            $task_kam->whereNull('t.assigned_to');
                        }
                       
                    }
                    if(is_numeric($request->get('complete_status'))){
                        // echo($request->get('complete_status'));
                        $task_kam->where('t.is_completed',$request->get('complete_status'));
                    }
    
                    $task_kam = $task_kam->get();
    
                //dd($task_kam);
                
                $task_user =DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created','u.display_id')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
                    ->whereIn('ta.status',['1','2'])
                    ->whereNotNull('t.assigned_to')
                    ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.Auth::user()->id.', ta.user_id='.Auth::user()->id.')')->orderBy('ta.updated_at','DESC');
                    
                    if($request->get('from_date') !=""){
                        $task_user->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                    // if($request->get('ref_list')){
                    //     $value=$request->get('ref_list');
                    //         $task_user->whereIn('u.display_id',explode(',',$value));
                    // }
                    if($request->get('ref')){
                        $task_user->where('u.display_id',$request->get('ref'));
                    }
        
                    if($request->get('to_date') !=""){
                        $task_user->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    if(is_numeric($request->get('customer_id'))){
                         $task_user->where('t.business_id',$request->get('customer_id'));
                    }
                    if(is_numeric($request->get('candidate_id'))){
                         $task_user->where('t.candidate_id',$request->get('candidate_id'));
                    }
                    if(is_numeric($request->get('service_id'))){
                    // echo($request->get('service_id'));
                        $task_user->where('t.service_id',$request->get('service_id'));
                    }
                    if(is_numeric($request->get('user_id'))){
                        $task_user->where('ta.user_id',$request->get('user_id'));
                    }
                    if($request->get('task_type')){
                        $task_user->where('t.description',$request->get('task_type'));
                    }
                    if($request->get('assign_status')){
                       
                        if ($request->get('assign_status')=='assigned') {
                            $task_user->whereNotNull('t.assigned_to');
                        }
                        else{
                            $task_user->whereNull('t.assigned_to');
                        }
                       
                    }
                    if(is_numeric($request->get('complete_status'))){
                        // echo($request->get('complete_status'));
                        $task_user->where('t.is_completed',$request->get('complete_status'));
                    }
    
                $task_user = $task_user->get();
                
                $tasks = $task_kam->merge($task_user);
                
    
                //dd($tasks);
            }
            else{
                $tasks =DB::table('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created','u.display_id')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
                    ->whereIn('ta.status',['1','2'])
                    ->whereNotNull('t.assigned_to')
                    ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.Auth::user()->id.', ta.user_id='.Auth::user()->id.')')->orderBy('ta.updated_at','DESC');
    
                    if($request->get('from_date') !=""){
                        $tasks->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                    // if($request->get('ref_list')){
                    //     $value=$request->get('ref_list');
                    //         $tasks->whereIn('u.display_id',explode(',',$value));
                    // }
                    if($request->get('ref')){
                        $tasks->where('u.display_id',$request->get('ref'));
                    }
        
                    if($request->get('to_date') !=""){
                        $tasks->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    if(is_numeric($request->get('customer_id'))){
                         $tasks->where('t.business_id',$request->get('customer_id'));
                    }
                    if(is_numeric($request->get('candidate_id'))){
                         $tasks->where('t.candidate_id',$request->get('candidate_id'));
                    }
                    if(is_numeric($request->get('service_id'))){
                    // echo($request->get('service_id'));
                        $tasks->where('t.service_id',$request->get('service_id'));
                    }
                    if(is_numeric($request->get('user_id'))){
                        $tasks->where('ta.user_id',$request->get('user_id'));
                    }
                    if($request->get('task_type')){
                        $tasks->where('t.description',$request->get('task_type'));
                    }
                    if($request->get('assign_status')){
                       
                        if ($request->get('assign_status')=='assigned') {
                            $tasks->whereNotNull('t.assigned_to');
                        }
                        else{
                            $tasks->whereNull('t.assigned_to');
                        }
                       
                    }
                    if(is_numeric($request->get('complete_status'))){
                        // echo($request->get('complete_status'));
                        $tasks->where('t.is_completed',$request->get('complete_status'));
                    }
            }

            // }else{
            //     $kam_task =DB::table('tasks as t') var assign_status = $("#assign_status option:selected").val();+'&complete_status='+complete_status
            //     ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
            //     ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
            //     ->join('key_account_managers as kam','kam.business_id','=','t.business_id')
            //     ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
            //     ->where(['u.is_deleted'=>'0','kam.user_id'=>Auth::user()->id])
            //     ->whereIn('ta.status',['1','2'])->orderBy('id','DESC');
            //     $tasks=$kam_task->paginate(10);
            // }
            // $normal_task =DB::table('tasks as t')
            // ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
            // ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
            // ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
            // ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
            // ->whereIn('ta.status',['1','2'])
            // ->whereNotNull('t.assigned_to')
            // ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.Auth::user()->id.', t.assigned_to='.Auth::user()->id.')')->get();
            
            // $normal_task =DB::table('tasks as t')
            // ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
            // ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
            // ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
            
            // ->where(['u.is_deleted'=>'0','ta.user_id'=>Auth::user()->id])->whereNull('ta.reassign_to')
            // ->orWhere(['u.is_deleted'=>'0','ta.user_id'=>Auth::user()->id,'ta.reassign_to'=>Auth::user()->id])
            // ->whereIn('ta.status',['1','2'])->orderBy('id','DESC')->get();
            
            //  echo"<pre>";
            //     print_r($normal_task); 
            //  die;
            // $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
            // dd($kams);
            // dd($customer_task);
                
            if ($request->get('rows')!='') {
                $rows = $request->get('rows');
            }
            $tasks=$tasks->paginate($rows);

            return view('admin.task.all-task.ajax',compact('tasks','action_master','users','cam'));
        }
        else
            return view('admin.task.all-task.index',compact('tasks','action_master','users','cam'));
    }

    public function indexFilter(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $clients = DB::table('users as u')
                    ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                    ->get();

        $users_list = User::where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])->get();

        $users = DB::table('users as u')
                    ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                    ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                    ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                    ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0,'u.status'=>1])
                    ->get();

        $user_service = DB::table('users as u')
                        ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                        ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                        ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
                        ->select('uc.checks','u.id' )
                        ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0])
                        ->get();

        $action_master = DB::table('action_masters')
                            ->select('*')
                            ->where('route_group','=','')
                            ->get();

        $services = DB::table('services')
                        ->select('name','id')
                        ->where('business_id',NULL)
                        ->whereNotIn('type_name',['e_court'])
                        ->orwhere('business_id',$business_id)
                        ->where(['status'=>'1'])
                        ->get();

        // $candidates = DB::table('candidate_reinitiates')
        //                 ->select('id','display_id','first_name','middle_name','last_name','phone','name')
        //                 ->where(['parent_id'=>$business_id,'user_type'=>'candidate'])
        //                 ->orderBy('id','DESC')
        //                 ->get(); 

        $viewRender = view('admin.task.all-task.filter',compact('clients','users_list','users','user_service','action_master','services'))->render();
        return response()->json(array('success' => true, 'html'=>$viewRender));
    }

     /**
     * Display a listing of the resource.assignModal
     *
     * @return \Illuminate\Http\Response
     */
    public function assignIndex(Request $request)
    {
        // dd($request);,compact()
        $user_id = Auth::user()->id;
        $business_id =Auth::user()->business_id;
        $cam=[];
        $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        // foreach ($kams as $kam) {
        //    $cam[]= $kam->user_id;
        // }
        // dd($cam);

        $cam=$kams->pluck('user_id')->all();

        //   if (Auth::user()->user_type=='customer') {
          # code...
        $rows=10;
        if (Auth::user()->user_type=='customer'  ) {
            $tasks =Task::from('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created','u.display_id')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
                    ->whereNotNull('t.assigned_to')
                    ->whereIn('ta.status',['1','2']);

                    if($request->get('from_date') !=""){
                        $tasks->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                    if($request->get('to_date') !=""){
                        $tasks->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    // if($request->get('ref_list')){
                    //     $value=$request->get('ref_list');
                    //         $tasks->whereIn('u.display_id',explode(',',$value));
                    // }
                    if(is_numeric($request->get('task_service'))){
                           //echo($request->get('task_service'));
                        $tasks->where('t.service_id',$request->get('task_service'));
                    }
                    if(is_numeric($request->get('task_user'))){
                        //($request->get('task_user'));
                        $tasks->where('ta.user_id',$request->get('task_user')); 
                    }
                    if($request->get('ref')){
                        $tasks->where('u.display_id',$request->get('ref'));
                    }
                    if(is_numeric($request->get('customer_id'))){
                        $tasks->where('t.business_id',$request->get('customer_id'));
                    }
                    if(is_numeric($request->get('candidate_id'))){
                        $tasks->where('t.candidate_id',$request->get('candidate_id'));
                    }
                    if(is_numeric($request->get('service_id'))){
                    // echo($request->get('service_id'));
                        $tasks->where('t.service_id',$request->get('service_id'));
                    }
                    if($request->get('task_type')){
                        $tasks->where('t.description',$request->get('task_type'));
                    }
                    if($request->get('verify_status')!='')
                    {
                        if(stripos($request->get('verify_status'),'all')!==false)
                        {
                            $tasks->whereIn('ta.status',['1','2']);
                        }
                        else
                        {
                            $tasks->where('ta.status',$request->get('verify_status'));
                        }
                    }
                    if($request->get('t_type')!='')
                    {
                        if($request->get('t_type')=='verify_task')
                        {
                            $tasks->where('t.description','Task for Verification');
        
                            //Check for Insufficiency exists
                            // if($request->get('insuff')!='')
                            // {
                            //     $tasks->where('insuff',1);
                            // }
                        }
                        else if($request->get('t_type')=='jaf_fill')
                        {
                            $tasks->where('t.description','JAF Filling');
                        }
                        else if($request->get('t_type')=='report_write')
                        {
                            $tasks->where('t.description','Report generation');
                        }
                    }
                    if(is_numeric($request->get('user_id'))){
                        $tasks->where('ta.user_id',$request->get('user_id')); 
                    }
                    if(is_numeric($request->get('task_service'))){
                        // echo($request->get('task_service'));
                        $tasks->where('t.service_id',$request->get('task_service'));
                    }
                    if($request->input('task_start_date') !=""){
                        // $tasks->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('task_start_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('task_start_date'))).' END');
        
                        $tasks->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('task_start_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('task_start_date'))));
                         });
                    }
                    if($request->input('task_end_date') !=""){
                        // $tasks->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('task_end_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('task_end_date'))).' END');
                        $tasks->where(function($q) use ($request){
                             $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('task_end_date'))))
                             ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('task_end_date'))));
                         });
                    }

                    $tasks=$tasks->orderBy('t.start_date','DESC')->get();

                    //dd($tasks);
                    
        }
        else if(in_array($user_id,$cam))
        {
            $task_kam =Task::from('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created','u.display_id')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
                    ->whereNotNull('t.assigned_to')
                    ->whereIn('ta.status',['1','2'])
                    ->whereIn('u.business_id',$kams->pluck('business_id')->all());

                    if($request->get('from_date') !=""){
                        $task_kam->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                    if($request->get('to_date') !=""){
                        $task_kam->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    // if($request->get('ref_list')){
                    //     $value=$request->get('ref_list');
                    //         $task_kam->whereIn('u.display_id',explode(',',$value));
                    // }
                    if($request->get('ref')){
                        $task_kam->where('u.display_id',$request->get('ref'));
                    }
                    if(is_numeric($request->get('customer_id'))){
                        $task_kam->where('t.business_id',$request->get('customer_id'));
                    }
                    if(is_numeric($request->get('candidate_id'))){
                        $task_kam->where('t.candidate_id',$request->get('candidate_id'));
                    }
                    if(is_numeric($request->get('service_id'))){
                    // echo($request->get('service_id'));
                        $task_kam->where('t.service_id',$request->get('service_id'));
                    }
                    if($request->get('task_type')){
                        $task_kam->where('t.description',$request->get('task_type'));
                    }
                    if($request->get('verify_status')!='')
                    {
                        if(stripos($request->get('verify_status'),'all')!==false)
                        {
                            $task_kam->whereIn('ta.status',['1','2']);
                        }
                        else
                        {
                            $task_kam->where('ta.status',$request->get('verify_status'));
                        }
                    }
                    if($request->get('t_type')!='')
                    {
                        if($request->get('t_type')=='verify_task')
                        {
                            $task_kam->where('t.description','Task for Verification');
        
                            //Check for Insufficiency exists
                            // if($request->get('insuff')!='')
                            // {
                            //     $task_kam->where('insuff',1);
                            // }
                        }
                        else if($request->get('t_type')=='jaf_fill')
                        {
                            $task_kam->where('t.description','JAF Filling');
                        }
                        else if($request->get('t_type')=='report_write')
                        {
                            $task_kam->where('t.description','Report generation');
                        }
                    }
                    if(is_numeric($request->get('user_id'))){
                        $task_kam->where('ta.user_id',$request->get('user_id')); 
                    }
                    if(is_numeric($request->get('task_service'))){
                        // echo($request->get('task_service'));
                        $task_kam->where('t.service_id',$request->get('task_service'));
                    }
                    if(is_numeric($request->get('task_user'))){
                        $task_kam->where('ta.user_id',$request->get('task_user')); 
                    }
                    
                    if($request->input('task_start_date') !=""){
                        // $task_kam->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('task_start_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('task_start_date'))).' END');
        
                        $task_kam->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('task_start_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('task_start_date'))));
                         });
                    }
                    if($request->input('task_end_date') !=""){
                        // $task_kam->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('task_end_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('task_end_date'))).' END');
                        $task_kam->where(function($q) use ($request){
                             $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('task_end_date'))))
                             ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('task_end_date'))));
                         });
                    }

            $task_kam = $task_kam->orderBy('t.start_date','DESC')->get();
                
            $task_user =Task::from('tasks as t')
                    ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created','u.display_id')
                    ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
                    ->whereIn('ta.status',['1','2'])
                    ->whereNotNull('t.assigned_to')
                    ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.Auth::user()->id.', ta.user_id='.Auth::user()->id.')');

                    if($request->get('from_date') !=""){
                        $task_user->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                    }
                    if($request->get('to_date') !=""){
                        $task_user->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                    }
                    // if($request->get('ref_list')){
                    //     $value=$request->get('ref_list');
                    //         $task_user->whereIn('u.display_id',explode(',',$value));
                    // }
                    if($request->get('ref')){
                        $task_user->where('u.display_id',$request->get('ref'));
                    }
                    if(is_numeric($request->get('customer_id'))){
                        $task_user->where('t.business_id',$request->get('customer_id'));
                    }
                    if(is_numeric($request->get('candidate_id'))){
                        $task_user->where('t.candidate_id',$request->get('candidate_id'));
                    }
                    if(is_numeric($request->get('service_id'))){
                    // echo($request->get('service_id'));
                        $task_user->where('t.service_id',$request->get('service_id'));
                    }
                    if($request->get('task_type')){
                        $task_user->where('t.description',$request->get('task_type'));
                    }
                    if($request->get('verify_status')!='')
                    {
                        if(stripos($request->get('verify_status'),'all')!==false)
                        {
                            $task_user->whereIn('ta.status',['1','2']);
                        }
                        else
                        {
                            $task_user->where('ta.status',$request->get('verify_status'));
                        }
                    }
                    if($request->get('t_type')!='')
                    {
                        if($request->get('t_type')=='verify_task')
                        {
                            $task_user->where('t.description','Task for Verification');
        
                            //Check for Insufficiency exists
                            // if($request->get('insuff')!='')
                            // {
                            //     $task_user->where('insuff',1);
                            // }
                        }
                        else if($request->get('t_type')=='jaf_fill')
                        {
                            $task_user->where('t.description','JAF Filling');
                        }
                        else if($request->get('t_type')=='report_write')
                        {
                            $task_user->where('t.description','Report generation');
                        }
                    }
                    if(is_numeric($request->get('user_id'))){
                        $task_user->where('ta.user_id',$request->get('user_id')); 
                    }
                    if(is_numeric($request->get('task_service'))){
                        // echo($request->get('task_service'));
                        $task_user->where('t.service_id',$request->get('task_service'));
                    }
                    if(is_numeric($request->get('task_user'))){
                        $task_user->where('ta.user_id',$request->get('task_user')); 
                    }
                    if($request->input('task_start_date') !=""){
                        // $task_user->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('task_start_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('task_start_date'))).' END');
        
                        $task_user->where(function($q) use ($request){
                             $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('task_start_date'))))
                             ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('task_start_date'))));
                         });
                    }
                    if($request->input('task_end_date') !=""){
                        // $task_user->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('task_end_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('task_end_date'))).' END');
                        $task_user->where(function($q) use ($request){
                             $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('task_end_date'))))
                             ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('task_end_date'))));
                         });
                    }

                $task_user = $task_user->orderBy('t.start_date','DESC')->get();

                $tasks = $task_kam->merge($task_user);

                //dd($tasks);
        }
        else{
            
            $tasks =Task::from('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created','u.display_id')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
                ->whereIn('ta.status',['1','2'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.Auth::user()->id.', ta.user_id='.Auth::user()->id.')');

                if($request->get('from_date') !=""){
                    $tasks->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
                }
                if($request->get('to_date') !=""){
                    $tasks->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
                }
                // if($request->get('ref_list')){
                //     $value=$request->get('ref_list');
                //         $tasks->whereIn('u.display_id',explode(',',$value));
                // }
                if(is_numeric($request->get('task_user'))){
                    $tasks->where('ta.user_id',$request->get('task_user')); 
                }
                if($request->get('ref')){
                    $tasks->where('u.display_id',$request->get('ref'));
                }
                if(is_numeric($request->get('customer_id'))){
                    $tasks->where('t.business_id',$request->get('customer_id'));
                }
                if(is_numeric($request->get('candidate_id'))){
                    $tasks->where('t.candidate_id',$request->get('candidate_id'));
                }
                if(is_numeric($request->get('service_id'))){
                // echo($request->get('service_id'));
                    $tasks->where('t.service_id',$request->get('service_id'));
                }
                if($request->get('task_type')){
                    $tasks->where('t.description',$request->get('task_type'));
                }
                if($request->get('verify_status')!='')
                {
                    if(stripos($request->get('verify_status'),'all')!==false)
                    {
                        $tasks->whereIn('ta.status',['1','2']);
                    }
                    else
                    {
                        $tasks->where('ta.status',$request->get('verify_status'));
                    }
                }
                if($request->get('t_type')!='')
                {
                    if($request->get('t_type')=='verify_task')
                    {
                        $tasks->where('t.description','Task for Verification');
    
                        //Check for Insufficiency exists
                        // if($request->get('insuff')!='')
                        // {
                        //     $tasks->where('insuff',1);
                        // }
                    }
                    else if($request->get('t_type')=='jaf_fill')
                    {
                        $tasks->where('t.description','JAF Filling');
                    }
                    else if($request->get('t_type')=='report_write')
                    {
                        $tasks->where('t.description','Report generation');
                    }
                }
                if(is_numeric($request->get('user_id'))){
                    $tasks->where('ta.user_id',$request->get('user_id')); 
                }
                if(is_numeric($request->get('task_service'))){
                    // echo($request->get('task_service'));
                    $tasks->where('t.service_id',$request->get('task_service'));
                }
                if($request->input('task_start_date') !=""){
                    // $tasks->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)>='.date('Y-m-d',strtotime($request->input('task_start_date'))).' ELSE DATE(t.assigned_at)>='.date('Y-m-d',strtotime($request->input('task_start_date'))).' END');
    
                    $tasks->where(function($q) use ($request){
                         $q->whereDate('t.start_date','>=',date('Y-m-d',strtotime($request->input('task_start_date'))))
                         ->orWhereDate('t.assigned_at','>=',date('Y-m-d',strtotime($request->input('task_start_date'))));
                     });
                }
                if($request->input('task_end_date') !=""){
                    // $tasks->whereRaw('CASE WHEN t.start_date IS NOT NULL THEN DATE(t.start_date)<='.date('Y-m-d',strtotime($request->input('task_end_date'))).' ELSE DATE(t.assigned_at)<='.date('Y-m-d',strtotime($request->input('task_end_date'))).' END');
                    $tasks->where(function($q) use ($request){
                         $q->whereDate('t.start_date','<=',date('Y-m-d',strtotime($request->input('task_end_date'))))
                         ->orWhereDate('t.assigned_at','<=',date('Y-m-d',strtotime($request->input('task_end_date'))));
                     });
                }

                $tasks=$tasks->orderBy('t.start_date','DESC')->get();
        }
            
            //$tasks=$tasks->orderBy('t.start_date','DESC')->toSql();
            //dd($tasks);
            if (!(Auth::user()->user_type=='customer' || in_array($user_id,$cam))) {
                if($request->get('insuff')!=''){
                    $tasks=$tasks->each(function ($task) {
                        $task->setAppends(['insuff']);
                    });

                    $tasks = $tasks->where('insuff',1);
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


            } 

            if ($request->get('rows')!='') {
                $rows = $request->get('rows');
            }

            $tasks = $tasks->paginate($rows);
            //$tasks = $tasks->toSql();
            //dd($tasks);

        // $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        // dd($kams);
        // dd($customer_task);

        // $clients = DB::table('users as u')
        // ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        // ->join('user_businesses as b','b.business_id','=','u.id')
        // ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        // ->get();

        // $users_list = User::where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])->get();

        $users = DB::table('users as u')
        ->join('role_masters as rm', 'rm.id', '=', 'u.role')
        ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
        ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
        ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0,'u.status'=>1])
        ->get();
        
        $action_master = DB::table('action_masters')
        ->select('*')
        ->where('route_group','=','')
        ->get();
        // $services = DB::table('services')
        // ->select('name','id')
        // ->where('business_id',NULL)
        // ->whereNotIn('type_name',['e_court'])
        // ->orwhere('business_id',$business_id)
        // ->where(['status'=>'1'])
        // ->get();

        // $candidates = DB::table('users')
        //         ->select('id','display_id','first_name','middle_name','last_name','phone','name')
        //         ->where(['parent_id'=>$business_id,'user_type'=>'candidate'])
        //         ->orderBy('id','DESC')
        //         ->get(); 

        $verify_status = $request->get('verify_status');
        $t_type = $request->get('t_type');
        $task_start_date = $request->get('task_start_date');
        $task_end_date = $request->get('task_end_date');
        $insuff = $request->get('insuff');
        $in_tat = $request->get('in_tat');
        $out_tat = $request->get('out_tat');
        $task_service = $request->get('task_service');
        $task_user = $request->get('task_user');
            // dd($tasks);
        // if($request->ajax())
        //     return view('admin.task.assign.ajax',compact('tasks','action_master','users','clients','users_list','services','cam','verify_status','t_type','task_start_date','task_end_date','insuff','in_tat','out_tat','task_service','task_user'));
        // else
        //     return view('admin.task.assign.index',compact('tasks','action_master','users','clients','users_list','services','cam','verify_status','t_type','task_start_date','task_end_date','insuff','in_tat','out_tat','task_service','task_user'));

        if($request->ajax())
            return view('admin.task.assign.ajax',compact('tasks','action_master','users','cam','verify_status','t_type','task_start_date','task_end_date','insuff','in_tat','out_tat','task_service','task_user'));
        else
            return view('admin.task.assign.index',compact('tasks','action_master','users','cam','verify_status','t_type','task_start_date','task_end_date','insuff','in_tat','out_tat','task_service','task_user'));
    }

    public function assignIndexFilter(Request $request)
    {
        $business_id = Auth::user()->business_id;
        $clients = DB::table('users as u')
                    ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                    ->get();

        $users_list = User::where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])->get();

        $users = DB::table('users as u')
                        ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                        ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                        ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                        ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0,'u.status'=>1])
                        ->get();
        
        $action_master = DB::table('action_masters')
                        ->select('*')
                        ->where('route_group','=','')
                        ->get();

        $services = DB::table('services')
                        ->select('name','id')
                        ->where('business_id',NULL)
                        ->whereNotIn('type_name',['e_court'])
                        ->orwhere('business_id',$business_id)
                        ->where(['status'=>'1'])
                        ->get();

        // $candidates = DB::table('candidate_reinitiates')
        //         ->select('id','display_id','first_name','middle_name','last_name','phone','name')
        //         ->where(['parent_id'=>$business_id,'user_type'=>'candidate'])
        //         ->orderBy('id','DESC')
        //         ->get(); 

        $viewRender = view('admin.task.assign.filter',compact('clients','users_list','users','action_master','services'))->render();
        return response()->json(array('success' => true, 'html'=>$viewRender));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function unassignIndex(Request $request)
    {
        // dd($request);,compact()
        $user_id = Auth::user()->id;
        $business_id =Auth::user()->business_id;
        $tasks=[];
        $cam=[];
        $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        foreach ($kams as $kam) {
           $cam[]= $kam->user_id;
        }
        // dd($cam);
        //   if (Auth::user()->user_type=='customer') {
          # code...
        $rows=10;
        if (Auth::user()->user_type=='customer' || in_array($user_id,$cam)) {
            $tasks =DB::table('tasks as t')
            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created','u.display_id')
            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
            ->where('t.assigned_to',null)
            ->orderBy('id','DESC');
        
            if($request->get('from_date') !=""){
            $tasks->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
            }
            if($request->get('to_date') !=""){
            $tasks->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
            }
            // if($request->get('ref_list')){
            //     $value=$request->get('ref_list');
            //         $tasks->whereIn('u.display_id',explode(',',$value));
            // }
            if($request->get('ref')){
                $tasks->where('u.display_id',$request->get('ref'));
            }
            if(is_numeric($request->get('customer_id'))){
            $tasks->where('t.business_id',$request->get('customer_id'));
            }
            if(is_numeric($request->get('candidate_id'))){
            $tasks->where('t.candidate_id',$request->get('candidate_id'));
            }
            if(is_numeric($request->get('service_id'))){
            // echo($request->get('service_id'));
                $tasks->where('t.service_id',$request->get('service_id'));
            }
            if($request->get('task_type')){
                $tasks->where('t.description',$request->get('task_type'));
            }
            if(is_numeric($request->get('user_id'))){
            $tasks->where('ta.user_id',$request->get('user_id'));
            }
            if($request->get('rows')!='') {
                $rows = $request->get('rows');
            }
            $tasks=$tasks->paginate($rows);
        }

        // $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        // dd($kams);
        // dd($customer_task);

        $clients = DB::table('users as u')
        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->get();

        $users_list = User::where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])->get();

        $users = DB::table('users as u')
        ->join('role_masters as rm', 'rm.id', '=', 'u.role')
        ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
        ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
        ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0,'u.status'=>1])
        ->get();
        
        $action_master = DB::table('action_masters')
        ->select('*')
        ->where('route_group','=','')
        ->get();
            // dd($tasks);
            
        $services = DB::table('services')
            ->select('name','id')
            ->where('business_id',NULL)
            ->whereNotIn('type_name',['e_court'])
            ->orwhere('business_id',$business_id)
            ->where(['status'=>'1'])
            ->get();

        // $candidates = DB::table('candidate_reinitiates')
        //     ->select('id','display_id','first_name','middle_name','last_name','phone','name')
        //     ->where(['parent_id'=>$business_id,'user_type'=>'candidate'])
        //     ->orderBy('id','DESC')
        //     ->get();     
            

        if($request->ajax())
            return view('admin.task.unassign-ajax',compact('tasks','action_master','users','clients','users_list','services','cam'));
        else
            return view('admin.task.unassign-index',compact('tasks','action_master','users','clients','users_list','services','cam'));
    }


     /**
     * Display a listing of the resource.assignModal
     *
     * @return \Illuminate\Http\Response
     */
    public function completeIndex(Request $request)
    {
        // dd($request);,compact()
        $user_id = Auth::user()->id;
        $business_id =Auth::user()->business_id;
        $cam=[];
        $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        foreach ($kams as $kam) {
           $cam[]= $kam->user_id;
        }
        // dd($cam);
        //   if (Auth::user()->user_type=='customer') {
          # code...
        $rows=10;
        if (Auth::user()->user_type=='customer' || in_array($user_id,$cam)) {
            $tasks =DB::table('tasks as t')
            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created','u.display_id')
            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2'])
            ->orderBy('id','DESC');
       }
        else{
            $tasks =DB::table('tasks as t')
                ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created','u.display_id')
                ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id,'ta.status'=>'2'])
                ->whereNotNull('t.assigned_to')
                ->whereRaw('IF (ta.reassign_to IS NOT NULL, ta.reassign_to='.Auth::user()->id.', ta.user_id='.Auth::user()->id.')')->orderBy('ta.updated_at','DESC');
        }
        if($request->get('from_date') !=""){
            $tasks->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
            }
            if($request->get('to_date') !=""){
            $tasks->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
            }
            // if($request->get('ref_list')){
            //     $value=$request->get('ref_list');
            //         $tasks->whereIn('u.display_id',explode(',',$value));
            // }
            if($request->get('ref')){
                $tasks->where('u.display_id',$request->get('ref'));
            }
            if(is_numeric($request->get('customer_id'))){
            $tasks->where('t.business_id',$request->get('customer_id'));
            }
            if(is_numeric($request->get('candidate_id'))){
            $tasks->where('t.candidate_id',$request->get('candidate_id'));
            }
            if(is_numeric($request->get('service_id'))){
            // echo($request->get('service_id'));
                $tasks->where('t.service_id',$request->get('service_id'));
            }
            if($request->get('task_type')){
                $tasks->where('t.description',$request->get('task_type'));
            }
            if(is_numeric($request->get('user_id'))){
            $tasks->where('ta.user_id',$request->get('user_id'));
            }
            if ($request->get('rows')!='') {
                $rows = $request->get('rows');
            }
            $tasks=$tasks->paginate($rows);
            

        // $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        // dd($kams);
        // dd($customer_task);

        $clients = DB::table('users as u')
        ->select('u.id','u.display_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
        ->join('user_businesses as b','b.business_id','=','u.id')
        ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
        ->get();

        $users_list = User::where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])->get();

        $users = DB::table('users as u')
        ->join('role_masters as rm', 'rm.id', '=', 'u.role')
        ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
        ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
        ->where('u.business_id',Auth::user()->business_id)
        ->get();
        
        $action_master = DB::table('action_masters')
        ->select('*')
        ->where('route_group','=','')
        ->get();

        $services = DB::table('services')
        ->select('name','id')
        ->where('business_id',NULL)
        ->whereNotIn('type_name',['e_court'])
        ->orwhere('business_id',$business_id)
        ->where(['status'=>'1'])
        ->get();

        // $candidates = DB::table('users')
        //         ->select('id','display_id','first_name','middle_name','last_name','phone','name')
        //         ->where(['parent_id'=>$business_id,'user_type'=>'candidate'])
        //         ->orderBy('id','DESC')
        //         ->get(); 
            // dd($tasks);
        if($request->ajax())
            return view('admin.task.complete-ajax',compact('tasks','action_master','users','clients','users_list','services','cam'));
        else
            return view('admin.task.complete-index',compact('tasks','action_master','users','clients','users_list','services','cam'));
    }


     /**
     * Display a listing of the resource.assignModal
     *
     * @return \Illuminate\Http\Response
     */
    public function vendorIndex(Request $request)
    {
       
        // dd($request);,compact()
        $user_id = Auth::user()->id;
        $business_id =Auth::user()->business_id;
        $cam=[];
        $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        foreach ($kams as $kam) {
           $cam[]= $kam->user_id;
        }
        // dd($cam);
        //   if (Auth::user()->user_type=='customer') {
          # code...
        $rows=10;
        //if (Auth::user()->user_type=='customer' || in_array($user_id,$cam)) {
            $tasks =DB::table('tasks as t')
            // ->distinct('vvd.vendor_task_id')
            ->join('vendor_tasks as ta', 'ta.task_id', '=', 't.id')
            // ->join('vendor_verification_data as vvd', 'vvd.vendor_task_id', '=', 'ta.id')
            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
            ->join('services as s','s.id','=','ta.service_id')
            ->select('t.*','ta.status as tastatus','ta.reassigned_to','ta.id as vtId','s.name as servicename','ta.reassigned_by','ta.created_at as created','ta.vendor_sla_id','u.display_id')
            ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
            ->whereIn('ta.status',['1','2'])
            ->orderBy('id','DESC');
        // }
        // else{
        //     $tasks =DB::table('tasks as t')
        //         // ->distinct('vvd.vendor_task_id')
        //         ->join('vendor_tasks as ta', 'ta.task_id', '=', 't.id')
        //         // ->join('vendor_verification_data as vvd', 'vvd.vendor_task_id', '=', 'ta.id')
        //         ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
        //         ->select('t.*','ta.status as tastatus','ta.reassigned_to','ta.reassigned_by','ta.created_at as created','ta.vendor_sla_id','u.display_id')
        //         ->where(['u.is_deleted'=>'0','t.parent_id'=>$business_id])
        //         //->whereNotNull('t.assigned_to')
        //         ->whereIn('ta.status',['1','2'])
        //         ->orderBy('ta.updated_at','DESC');
        // }
        if($request->get('from_date') !=""){
            $tasks->whereDate('t.created_at','>=',date('Y-m-d',strtotime($request->get('from_date'))));
        }
        if($request->get('to_date') !=""){
            $tasks->whereDate('t.created_at','<=',date('Y-m-d',strtotime($request->get('to_date'))));
        }
        // if($request->get('ref_list')){
        //     $value=$request->get('ref_list');
        //         $tasks->whereIn('u.display_id',explode(',',$value));
        // }
        if($request->get('ref')){
            $tasks->where('u.display_id',$request->get('ref'));
        }
        if(is_numeric($request->get('customer_id'))){
            $tasks->where('t.business_id',$request->get('customer_id'));
        }
        if(is_numeric($request->get('candidate_id'))){
            $tasks->where('t.candidate_id',$request->get('candidate_id'));
        }
        if(is_numeric($request->get('service_id'))){
            // echo($request->get('service_id'));
            $tasks->where('t.service_id',$request->get('service_id'));
        }
        if($request->get('task_type')){
            $tasks->where('t.description',$request->get('task_type'));
        }
        if(is_numeric($request->get('user_id'))){
            //echo($request->get('user_id'));
            $tasks->where('t.assigned_to',$request->get('user_id'));
        }
        if ($request->get('rows')!='') {
            $rows = $request->get('rows');
        }
       
        $tasks=$tasks->groupBy('ta.task_id')->paginate($rows);
        //dd($tasks);
        // $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
        // dd($kams);
        // dd($customer_task);

        $clients = DB::table('users as u')
                    ->select('u.id','u.display_id','u.vendor_id','u.name','u.first_name','u.last_name','u.email','u.phone','b.company_name')
                    ->join('user_businesses as b','b.business_id','=','u.id')
                    ->where(['u.user_type'=>'client','u.parent_id'=>$business_id])
                    ->get();
    
        $users_list = User::where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0])->get();
        $vendors = DB::table('users')->select('id','vendor_id','name')->where(['parent_id'=>Auth::user()->business_id,'user_type'=>'vendor','is_deleted'=>0,'status'=>1])->get();
        // dd($vendors);
        $users = DB::table('users as u')
                ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                ->where('u.business_id',Auth::user()->business_id)
                ->get();
            
        $action_master = DB::table('action_masters')
                        ->select('*')
                        ->where('route_group','=','')
                        ->get();

        $services = DB::table('services')
                    ->select('name','id')
                    ->where('business_id',NULL)
                    ->whereNotIn('type_name',['e_court'])
                    ->orwhere('business_id',$business_id)
                    ->where(['status'=>'1'])
                    ->get();

        // $candidates = DB::table('candidate_reinitiates')
        //         ->select('id','display_id','first_name','middle_name','last_name','phone','name')
        //         ->where(['parent_id'=>$business_id,'user_type'=>'candidate'])
        //         ->orderBy('id','DESC')
        //         ->get(); 
        
        //dd($tasks);
        if($request->ajax())
            return view('admin.task.vendor-ajax',compact('tasks','action_master','users','clients','users_list','services','cam','vendors'));
        else
            return view('admin.task.vendor-index',compact('tasks','action_master','users','clients','users_list','services','cam','vendors'));
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
        // Session()->forget('check_id');

        // Session()->forget('jaf_id');
        // Session()->forget('service_id');

        // if( is_numeric($request->get('customer_id')) ){             
        //     session()->put('customer_id', $request->get('customer_id'));
        // }
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
    
    public function exportChecks(Request $request) 
    {
        // dd($request->session()->get('candidate_id'));
        $from_date = $to_date= $customer_id=$business_id = $check_id = "";

        // if( $request->session()->has('from_date') && $request->session()->has('to_date'))
        // {  
        //   $from_date     =  $request->session()->get('from_date');
        //   $to_date       =  $request->session()->get('to_date');
        // }
        // else
        // {
        //   if($request->session()->has('from_date'))
        //   {
        //     $from_date     =  $request->session()->get('from_date');
        //   }
        // }
        if($request->session()->has('task_id'))
          {
            $task_id      =  $request->session()->get('task_id');
          }
          $service_id=[];
          $candidate_id=[];
          foreach ($task_id as $key => $task) {
            
            $tasks =  DB::table('tasks')->where(['id'=>$task,'description'=>'Task for Verification '])->first();
            if ($tasks) {
                        $service_id[] = $tasks->service_id;
                        $candidate_id[]=$tasks ->candidate_id;
                        // $no_of_verifications[] =$tasks->number_of_verifications;
            }
          }
             $candidate_id=array_values(array_unique($candidate_id));
             $service_id=array_values(array_unique($service_id));
            sort($service_id);
            rsort($candidate_id);
            // foreach ($candidate_id as $key => $id) {
            //   $job_sla_items=  DB::table('job_sla_items')->select('service_id','number_of_verifications')->where('candidate_id',$id)->get();
            // }

        //dd($candidate_id);

        $file_name = 'task-all-checks-data-'.date('YmdHis').'.xlsx';
       
        return Excel::download(new allChecksExport($from_date, $to_date, $candidate_id, $service_id), $file_name);
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
     * Task Re-Assignment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
            $this->validate($request, [
            'user' => 'required',
            ]);
            // 'roles'     => 'required'
            
            $business_id = Auth::user()->business_id;
            $user_id = Auth::user()->id;
            $task_id =$request->input('task_id');
            // $service_id =$request->input('service_id');
            // $services = explode(',', $service_id);
            DB::beginTransaction();
            try{

                //Change status of Old task
                // foreach ($services as $key => $service) {
                    //  dd($service);
                    $task_assgn = TaskAssignment::where(['business_id'=>$request->input('business_id'),'candidate_id'=>$request->input('candidate_id'),'status'=>"1",'task_id'=>$task_id])->first();
                        $task_assgn->status= '0';
                        $task_assgn->save();
                //  }
                //  foreach ($services as $key => $service) { 
                    $taskdata = [
                    
                        'business_id'=> $request->input('business_id'),
                        'candidate_id' =>$request->input('candidate_id'),   
                        'job_sla_item_id'  => $request->input('job_sla_item_id'),
                        'task_id'=> $request->input('task_id'),
                        'user_id' => $request->input('user'),
                        // 'service_id'  =>$service,
                        'reassign_to' =>$request->input('user'),
                        'reassign_by' => $user_id,
                        'status' =>'1',
                        // 'tat' =>$request->input('tat'),
                        'created_at'  => date('Y-m-d H:i:s'),
                        'updated_at'  => date('Y-m-d H:i:s')
                    ];
                    DB::table('task_assignments')->insertGetId($taskdata);

                    $user= User::where('id',$request->user)->first();
                    $email = $user->email;
                    $name  = $user->name;
                    $candidate_name = Helper::candidate_user_name($request->candidate_id);
                    $sender = DB::table('users')->where(['id'=>$business_id])->first();
                    $msg = "JAF verification Task Re-Assign to you with candidate name";
                    $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
                    EmailConfigTrait::emailConfig();
                        //get Mail config data
                            //   $mail =null;
                            $mail= Config::get('mail');
                            // dd($mail['from']['address']);
                        if (count($mail)>0) {
                                Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                                    $message->to($email, $name)->subject
                                    ('myBCD System - Notification for JAF Filling Task');
                                    $message->from($mail['from']['address'],$mail['from']['name']);
                                });
                        }else {
                            Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('myBCD System - Notification for JAF Filling Task');
                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            });
                        }
                // }
              DB::commit();
              return redirect('/task')
              ->with('success', 'Task Re-assigned successfully');
            }
            catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return $e;
            } 
    }

    /**
     * Store a newly created resource in storage.
     * Task Re-Assignment
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reportReassign(Request $request)
    {
        // dd($request);
        $rules = [
            'report_user' => 'required',
            //   'user_status' => 'required'
        
        ];
        $customMessages=[
            'report_user.required' => 'Please select a user first!',
          ];

        $validator = Validator::make($request->all(), $rules,$customMessages);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
            // 'roles'     => 'required'
            
            $business_id = Auth::user()->business_id;
            $user_id = Auth::user()->id;
            $task_id =$request->input('report_task_id');
            // $service_id =$request->input('service_id');
            // $services = explode(',', $service_id);
            DB::beginTransaction();
            try{

                //Change status of Old task
                // foreach ($services as $key => $service) {
                    //  dd($service);
                    //  $task= Task::find($task_id);
           
                    $task_assgn = TaskAssignment::where(['business_id'=>$request->input('report_business_id'),'candidate_id'=>$request->input('report_candidate_id'),'status'=>"1",'task_id'=>$task_id])->first();
                      
                    if($task_assgn)
                    {
                    $task_assgn->status= '0';
                        $task_assgn->save();
                //  }
                //  foreach ($services as $key => $service) { 
                    $taskdata = [
                    
                        'business_id'=> $request->input('report_business_id'),
                        'candidate_id' =>$request->input('report_candidate_id'),   
                        'job_sla_item_id'  => $request->input('report_job_sla_item_id'),
                        'task_id'=> $request->input('report_task_id'),
                        'user_id' => $request->input('report_user'),
                        // 'service_id'  =>$service,
                        'reassign_to' =>$request->input('report_user'),
                        'reassign_by' => $user_id,
                        'status' =>'1',
                        // 'tat' =>$request->input('tat'),
                        'created_at'  => date('Y-m-d H:i:s'),
                        'updated_at'  => date('Y-m-d H:i:s')
                    ];
                    DB::table('task_assignments')->insertGetId($taskdata);

                    $user= User::where('id',$request->report_user)->first();
                    $email = $user->email;
                    $name  = $user->name;
                    $candidate_name = Helper::candidate_user_name($request->report_candidate_id);
                    $sender = DB::table('users')->where(['id'=>$business_id])->first();
                    $msg = "Report generation Task Re-Assign to you with candidate name";
                    $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
                    EmailConfigTrait::emailConfig();
                        //get Mail config data
                            //   $mail =null;
                            $mail= Config::get('mail');
                            // dd($mail['from']['address']);
                        if (count($mail)>0) {
                                Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                                    $message->to($email, $name)->subject
                                    ('myBCD System - Notification for Report generation Task');
                                    $message->from($mail['from']['address'],$mail['from']['name']);
                                });
                        }else {
                            Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('myBCD System - Notification for Report generation Task');
                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            });
                        }
                // }
              DB::commit();
                return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'errors'  =>[]
                ]);
            }else{
                return response()->json([
                'success' =>false,
                'custom'  =>'yes',
                'errors'  =>[]
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
     * 
     *
     *Task Reassign the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function taskReassign(Request $request)
    {
        // dd($request->reassign_sla_id);
        $rules = [
            'user' => 'required',
                // 'tat' => 'required'
            ];

            $validator = Validator::make($request->all(), $rules);
                
               if ($validator->fails()){
                   return response()->json([
                       'success' => false,
                       'errors' => $validator->errors()
                   ]);
               }
            // 'roles'     => 'required'
            
            $business_id = Auth::user()->business_id;
            $user_id = Auth::user()->id;
            $task_id =$request->input('tasks_id');
            $candidate_id =$request->input('candidat_id');
            $user_type = $request->reassign_user_status;
            // dd($task_id); 
            // $services = explode(',', $service_id);

            DB::beginTransaction();
            try{
                //Change status of Old task
                // foreach ($services as $key => $service) {
                //  dd($service);
                 $task_assgn = TaskAssignment::where(['business_id'=>$request->input('business'),'candidate_id'=>$candidate_id,'status'=>"1",'task_id'=>$task_id,'service_id'=>$request->service, 'number_of_verifications'=>$request->no_of_verification])->first();
                 //dd($task_assgn);
                    //dd($task_assgn);
                    // $task_assgn->save();
                    // $task= [
                    //     $task_assgn->status= '0'
                        
                    // ];
                    if ($task_assgn) {
                        # code...
                    
                        DB::table('task_assignments')->where(['business_id'=>$request->input('business'),'candidate_id'=>$candidate_id,'status'=>"1",'task_id'=>$task_id,'service_id'=>$request->service, 'number_of_verifications'=>$request->no_of_verification])->update(['status'=>'0']);

                    }
            //  foreach ($services as $key => $service) { 
                //  $task_assgn = TaskAssignment::where(['business_id'=>$request->input('business'),'candidate_id'=>$request->input('candidate'),'status'=>"1",'task_id'=>$task_id,'service_id'=>$request->service, 'number_of_verifications'=>$request->no_of_verification])->first();
                //  dd($task_assgn);  
                //  $task_assgn->status= '0';
                //     $task_assgn->save();
                //  }
                //  foreach ($services as $key => $service) { 
                    
                    $taskdata = [
                    
                        'business_id'=> $request->input('business'),
                        'parent_id'=>$business_id,
                        'candidate_id' =>$candidate_id,   
                        'job_sla_item_id'  =>$user_type=='vendor'? $request->reassign_sla_id :$request->input('job_sla_item'),
                        'task_id'=> $task_id,
                        'user_id' => $request->input('user'),
                        'service_id'  =>$request->service,
                        'number_of_verifications'=>$request->no_of_verification,
                        'reassign_to' =>$request->input('user'),
                        'reassign_by' => $user_id,
                        'user_type' =>$user_type,
                        'status' =>'1',
                        // 'tat' =>$request->input('tat'),
                        'created_at' =>date('Y-m-d H:i:s'),
                        'updated_at'  => date('Y-m-d H:i:s')
                        
                    ];
                    DB::table('task_assignments')->insertGetId($taskdata);
                // }

                if ($user_type=='vendor') {

                    $service_id =[];
                    $services = DB::table('services')->where('verification_type','Manual')->get();
                    foreach ($services as $service) {
                
                        $service_id[] = $service->id; 
                    }

                    if (in_array($request->service,$service_id)) {
                        # code...
                    
                       
                        $vendor = DB::table('vendors')->select('id','user_id')->where('user_id',$request->user)->first();
                        
                        $vendor_sla = DB::table('vendor_sla_items')->select('sla_id','tat')->where(['vendor_id'=>$vendor->id,'service_id'=>$request->service])->first();
                        // dd($vendor_sla);
                        $vendor_task_assgn = TaskAssignment::where(['candidate_id'=>$candidate_id,'status'=>"1",'task_id'=>$task_id,'service_id'=>$request->service, 'number_of_verifications'=>$request->no_of_verification])->first();
                      // $vendor_task_assgn = VendorTask::where(['candidate_id'=>$candidate_id,'status'=>"1",'task_id'=>$task_id,'service_id'=>$request->service, 'no_of_verification'=>$request->no_of_verification])->first();
                       //dd($vendor_task_assgn);
                       
                        if ($vendor_task_assgn) {
                            $check_vendor =  TaskAssignment::where(['candidate_id'=>$candidate_id,'status'=>"1",'task_id'=>$task_id,'service_id'=>$request->service, 'number_of_verifications'=>$request->no_of_verification])->first();
                          
                            //Data send to Vendor task for assignment
                            $vendor_task = new VendorTask;
                            $vendor_task->parent_id = Auth::user()->business_id;
                            $vendor_task->business_id =  $request->user;
                            $vendor_task->candidate_id = $candidate_id ;
                            $vendor_task->task_id = $task_id;
                            $vendor_task->service_id = $request->service;
                            $vendor_task->vendor_sla_id = $request->reassign_sla_id;
                            $vendor_task->no_of_verification = $request->no_of_verification;
                            $vendor_task->status = '1';
                            $vendor_task->assigned_to = $vendor->user_id;
                            $vendor_task->assigned_by = Auth::user()->id;
                            $vendor_task->assigned_at = $check_vendor->assigned_at;
                            $vendor_task->reassigned_to = $request->user;
                            $vendor_task->reassigned_by = Auth::user()->id;
                            //$vendor_task->reassigned_at = date('Y-m-d H:i:s');
                            $vendor_task->reassigned_at = date('Y-m-d H:i:s');
                            $vendor_task->tat = $vendor_sla->tat;
                            $vendor_task->updated_by = Auth::user()->id;
                           // dd($vendor_task);
                            $vendor_task->save();
                           

                             //Data send to Vendor task for assignment
                            //  $vendor_task = new VendorTaskAssignment;
                            //  $vendor_task->parent_id = Auth::user()->business_id;
                            //  $vendor_task->business_id =  $request->user;
                            //  $vendor_task->candidate_id = $candidate_id ;
                            //  $vendor_task->task_id = $task_id;
                            //  $vendor_task->service_id = $request->service;
                            //  $vendor_task->vendor_sla_id = $request->reassign_sla_id;
                            //  $vendor_task->no_of_verification = $request->no_of_verification;
                            //  $vendor_task->status = '1';
                            //  $vendor_task->assigned_to = $check_vendor->assigned_to;
                            //  $vendor_task->assigned_by =$check_vendor->assigned_by;
                            //  $vendor_task->assigned_at = $check_vendor->assigned_at;
                            //  $vendor_task->reassigned_to = $request->user;
                            //  $vendor_task->reassigned_by = Auth::user()->id;
                            //  $vendor_task->reassigned_at = date('Y-m-d H:i:s');
                            //  $vendor_task->updated_by = Auth::user()->id;
                            //  $vendor_task->save();
 

                        }
                    }
                    else {
                        return response()->json([
                            'success' =>false,
                            'custom'  =>'yes',
                            'errors'  =>['name'=>'This Service  cannot assign to any vendor!']
                          ]);
                    }
                }
                 // Mail send to user

                 $user= User::where('id',$request->user)->first();
                 if ($user->email) {
                     # code...
                 
                 $email = $user->email;
                 $name  = $user->name;
                 $candidate_name =  Helper::candidate_user_name($request->candidate);
                 $msg = " JAF verification Task Re-Assign to you with candidate name";
                 $sender = DB::table('users')->where(['id'=>$business_id])->first();
                 $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
                 EmailConfigTrait::emailConfig();
                 //get Mail config data
                   //   $mail =null;
                   $mail= Config::get('mail');
                   // dd($mail['from']['address']);
                   if (count($mail)>0) {
                       Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                           $message->to($email, $name)->subject
                           ('myBCD System - Notification for JAF verification Task');
                           $message->from($mail['from']['address'],$mail['from']['name']);
                       });
                   }else {
                        Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                            $message->to($email, $name)->subject
                                ('myBCD System - Notification for JAF verification Task');
                            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        });
                    }
                }

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

    /**
     * 
     *
     *Task Reassign the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
    */
    public function bulkAssignModal(Request $request)
    {
        
        // 'roles'     => 'required'
        
        $business_id = Auth::user()->business_id;
        $user_id = Auth::user()->id;
        
        $service_id =$request->input('service_id');
        $task_time =$request->input('task_time');
        $user_type = $request->user_type;
        // dd($request->bulk_task_type);
        // $services = explode(',', $service_id);
        if ($user_type == 'user') {
            # code...
        
            $users = DB::table('users as u')
            ->join('role_masters as rm', 'rm.id', '=', 'u.role')
            ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
            ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
            ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0,'u.status'=>1])
            ->get();
            // // 
            // $user_service = DB::table('users as u')
            // ->join('role_masters as rm', 'rm.id', '=', 'u.role')
            // ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
            // ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
            // ->select('uc.checks','u.id' )
            // ->where(['u.business_id'=>Auth::user()->business_id,'uc.checks'=>$service_id])
            // ->get();

            // // $task =DB::table('tasks');
            
            // $user_id =[];
            // foreach($user_service as $us)
            // {
            //     $user_id[]= $us->id;


            // }
            // $created_date = Carbon::parse($request->created_time)->format('d-m-Y');
            // $completion_date = Carbon::now()->addDays($task_time)->format('d-m-Y');
            $now = Carbon::now()->format('d-m-Y');
        
            // echo"<pre>";
            // print_r($created_date);
            // die;
            // dd($user_service); 
            if ($request->bulk_task_type=='jaf') {
                $action_master = DB::table('action_masters')
                ->select('*')
                ->where(['route_group'=>'','action_title'=>'JAF Link'])
                ->first();
                $action_title='JAF Link';
                $desc ='JAF Filling';

            }
           elseif ($request->bulk_task_type=='task') {
                $action_master = DB::table('action_masters')
                ->select('*')
                ->where(['route_group'=>'','action_title'=>'JAF Filled'])
                ->first();
                $action_title='JAF Filled';
                $desc ='Task for Verification ';

            } else {
                $action_master = DB::table('action_masters')
                ->select('*')
                ->where(['route_group'=>'','action_title'=>'Generate Candidate Reports'])
                ->first();
                $action_title='Generate Candidate Reports';
                $desc ='Report generation';
            }
            
            $data = "<option value=''>Select User</option>";
            foreach($users as $user){
            
                // if ($request->bulk_task_type=='task') {
                //     $action_title='JAF Filled';
                //                    $desc ='Task for Verification ';
                   
                //                } else {
                //     $action_title='Generate Candidate Reports';
                //                    $desc ='Report generation';
                //                }
                    
                    if ( in_array($action_master->id,json_decode($user->permission_id)) && $action_master->action_title == $action_title ) {
                        
                        $tasks =DB::table('tasks as t')
                        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                        ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                        ->where(['u.is_deleted'=>'0','description'=>$desc,'ta.status'=>'1','ta.user_id'=>$user->id])
                        ->whereDate('t.start_date','<=',$now)
                        ->count();

                        $data .=" <option value=".$user->id. ">".$user->name.' '.' ( Assigned tasks-'.$tasks.')</option>' ;
                    }
                    // elseif (in_array($action_masters->id,json_decode($user->permission_id)) && $action_master->action_title == 'Generate Candidate Reports' ) {
                    //     $tasks =DB::table('tasks as t')
                    //     ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                    //     ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                    //     ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                    //     ->where(['u.is_deleted'=>'0','description'=>'Report generation','ta.status'=>'1','ta.user_id'=>$user->id])
                    //     ->whereDate('t.start_date','<=',$now)
                    //     ->count();

                    //     $data .=" <option value=".$user->id. ">".$user->name.' '.' ( Assigned tasks-'.$tasks.' )</option>' ;
                    // }
                
            } 

            return response()->json([
                'fail'      =>false,
                'data' => $data,
                
                ]);
            
        }
        if ($user_type == 'vendor') {
            
            $vendors = DB::table('users as u')
            ->join('vendors as v', 'v.user_id','=','u.id')
            ->join('vendor_slas as vs','vs.vendor_id','=','v.id')
            ->select('u.*','vs.vendor_id as vendor_sla_id')
            ->where(['u.parent_id'=>Auth::user()->business_id,'u.user_type'=>'vendor'])
            ->groupBy('v.id')
            ->get();
            // dd($vendors);
            $data = "<option value=''>Select User</option>";
            foreach ($vendors as $vendor) {
                $data .=" <option value=".$vendor->id." data-bulk=".$vendor->vendor_sla_id. ">".$vendor->name.' </option>' ;
            }

            return response()->json([
                'fail'      =>false,
                'data' => $data,
                
            ]);
        }
            
    }

     //Get Vendor Sla 
     public function bulkVendorSla(Request $request)
     {
 
         $vendor_id = $request->vendor_sla_id;
            // dd($vendor_id);
          $vendors= DB::table('vendor_slas')->where(['vendor_id'=>$vendor_id])->get();
        //   dd($vendor_sla);
        //   $vendors = DB::table('vendor_slas as vs')
        //   ->join('vendor_sla_items as vsi', 'vsi.sla_id', '=', 'vs.id')
        //   ->select('vs.id','vs.title')
        //   ->where(['vs.business_id'=>$vendor_id,'vsi.service_id'=>$request->service_id])->get();
 
            $data = "<option value=''>Select SLA</option>";
            if (count($vendors)>0) {
                # code...
        
                foreach($vendors as $sla){
                    $data .=" <option value=".$sla->id. ">".$sla->title.'</option>' ;
                }
                return response()->json([
                    'fail'      =>false,
                    'data' => $data,
                    
                ]); 
            }
            else {
                return response()->json([
                    'fail'      =>true,
                    'custom'  =>'yes',
                    'errors'  =>['vendor_sla'=>'Please select other vendor or Create Sla of this vendor !']
                    
                ]); 
            }  
     }
     
    /**
     * 
     *
     *Task Reassign the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function assignModal(Request $request)
    {
        
            // 'roles'     => 'required'
            
            $user_id = Auth::user()->id;
            $business_id = Auth::user()->business_id;
           
            $service_id =$request->input('service_id');
            $task_time =$request->input('task_time');
            $user_type = $request->user_type;
            //dd($task_time);
            // $services = explode(',', $service_id);
            if ($user_type == 'user') {
                # code...
           
                $users = DB::table('users as u')
                ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0])
                ->get();
                // // 
                $user_service = DB::table('users as u')
                ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
                ->select('uc.checks','u.id' )
                ->where(['u.business_id'=>Auth::user()->business_id,'uc.checks'=>$service_id])
                ->get();

                // $task =DB::table('tasks');
                
                $user_id =[];
                foreach($user_service as $us)
                {
                    $user_id[]= $us->id;


                }
                // $created_date = Carbon::parse($request->created_time)->format('d-m-Y');
                // $completion_date = Carbon::now()->addDays($task_time)->format('d-m-Y');
                $now = Carbon::now()->format('d-m-Y');
            
                // echo"<pre>";
                // print_r($created_date);
                // die;
                // dd($user_service); 
                $action_master = DB::table('action_masters')
                ->select('*')
                ->where(['route_group'=>'','action_title'=>'JAF Filled'])
                ->first();
                $data = "<option value=''>Select User</option>";
                foreach($users as $user){
                
                    
                        if ( in_array($action_master->id,json_decode($user->permission_id)) && $action_master->action_title == 'JAF Filled' && in_array($user->id,$user_id) ) {
                            
                            $tasks =DB::table('tasks as t')
                            ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                            ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                            ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                            ->where(['u.is_deleted'=>'0','description'=>'Task for Verification ','ta.status'=>'1','ta.user_id'=>$user->id])
                            ->whereDate('t.start_date','<=',$now)
                            ->count();

                            $data .=" <option value=".$user->id. ">".$user->name.' '.' ( Assigned tasks-'.$tasks.' )</option>' ;
                        }
                    
                } 

                return response()->json([
                    'fail'      =>false,
                    'data' => $data,
                    
                    ]);
             
            }

            $cam=[];
            $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id])->get();
           //dd($kams);
            foreach ($kams as $kam) {
               $cam[]= $kam->user_id;
            }

            if ($user_type == 'vendor') {
                
                if(Auth::user()->user_type=='customer'){
                    $vendors = DB::table('users as u')
                                ->join('vendors as v', 'v.user_id','=','u.id')
                                ->join('vendor_slas as vs','vs.vendor_id','=','v.id')
                                ->select('u.*')
                                ->where(['u.parent_id'=>Auth::user()->business_id,'u.user_type'=>'vendor'])
                                ->groupBy('v.id')
                                ->get();
                }
                else if(in_array($user_id,$cam))
                {
                    $vendors = DB::table('users as u')
                                ->join('vendors as v', 'v.user_id','=','u.id')
                                ->join('vendor_slas as vs','vs.vendor_id','=','v.id')
                                ->select('u.*')
                                ->where(['u.parent_id'=>Auth::user()->business_id,'u.user_type'=>'vendor'])
                                ->groupBy('v.id')
                                ->get();
                }
                else
                {
                    $users = User::from('users')->where('id',$user_id)->first();
                    $vendorId = $users->vendor_id;
                    $usersVendorid =   explode(',',$vendorId);

                    $vendors = DB::table('users as u')
                                ->join('vendors as v', 'v.user_id','=','u.id')
                                ->join('vendor_slas as vs','vs.vendor_id','=','v.id')
                                ->select('u.*')
                                ->where(['u.parent_id'=>Auth::user()->business_id,'u.user_type'=>'vendor'])
                                ->groupBy('v.id')
                                ->whereIn('v.user_id',$usersVendorid)
                                ->get();
                }
                // dd($vendors);
                $data = "<option value=''>Select Vendor</option>";
                foreach ($vendors as $vendor) {
                    $data .=" <option value=".$vendor->id. ">".$vendor->name.' </option>' ;
                }

                return response()->json([
                    'fail'      =>false,
                    'data' => $data,
                    
                ]);
            }
            
    }


    /**
     * 
     *
     *JAF filling Task Reassign the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function fillingReassignModal(Request $request)
    {
        
            // 'roles'     => 'required'
            
            $business_id = Auth::user()->business_id;
            $user_id = Auth::user()->id;
           
            $service_id =$request->input('task_id');
            $task_time =$request->input('task_time');
            // dd($service_id);
            // $services = explode(',', $service_id);
            $task =DB::table('task_assignments')->where(['candidate_id'=>$request->candidate_id,'status'=>'1','task_id'=>$request->task_id])->whereNotNull('user_id')->first();
        // dd($task);
            $users = DB::table('users as u')
            ->join('role_masters as rm', 'rm.id', '=', 'u.role')
            ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
            ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
            ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0])
            ->whereNotIn('u.id',[$task->user_id])
            ->get();
            // dd($users);
            // // 
            // $user_service = DB::table('users as u')
            // ->join('role_masters as rm', 'rm.id', '=', 'u.role')
            // ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
            // ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
            // ->select('uc.checks','u.id' )
            // ->where(['u.business_id'=>Auth::user()->business_id,'uc.checks'=>$service_id])
            // ->get();

            // $task =DB::table('tasks');
            
            // $user_id =[];
            // foreach($user_service as $us)
            // {
            //     $user_id[]= $us->id;

               
               

            // }
            // $created_date = Carbon::parse($request->created_time)->format('d-m-Y');
            // $completion_date = Carbon::now()->addDays($task_time)->format('d-m-Y');
            $now = Carbon::now()->format('d-m-Y');
          
            // echo"<pre>";
            // print_r($created_date);
            // die;
            // dd($user_service); 
            $action_master = DB::table('action_masters')
            ->select('*')
            ->where(['route_group'=>'','action_title'=>'JAF Link'])
            ->first();
            // dd($action_master);
            $data = "<option value=''>Select User</option>";
            foreach($users as $user){
               
                  
                    if ( in_array($action_master->id,json_decode($user->permission_id)) && $action_master->action_title == 'JAF Link')  {
                        
                        // $tasks =DB::table('tasks as t')
                        // ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                        // ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                        // ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                        // ->where(['u.is_deleted'=>'0','description'=>'Task for Verification ','ta.status'=>'1','ta.user_id'=>$user->id])
                        // ->whereDate('t.start_date','<=',$now)
                        // ->count(); '.' ( Assigned tasks-'.$tasks.' )

                        $data .=" <option value=".$user->id. ">".$user->name.'</option>' ;
                    }
                
            }

            return response()->json([
                'fail'      =>false,
                'data' => $data,
                
                  ]);
             
    }
    /**
     * 
     *
     *JAF filling Task Reassign the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function reportReassignModal(Request $request)
    {
        
            // 'roles'     => 'required'
            // dd($request);
            $business_id = Auth::user()->business_id;
            $user_id = Auth::user()->id;
           
            // $service_id =$request->input('task_id');
            // $task_time =$request->input('task_time');
            // dd($request->report_candidate_id);
            // $services = explode(',', $service_id);
            $task =DB::table('task_assignments')->where(['candidate_id'=>$request->report_candidate_id,'status'=>'1','task_id'=>$request->report_task_id])->whereNotNull('user_id')->first();
        // dd($task);
            $users = DB::table('users as u')
            ->join('role_masters as rm', 'rm.id', '=', 'u.role')
            ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
            ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
            ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0])
            ->whereNotIn('u.id',[$task->user_id])
            ->get();
            // dd($users);
            // // 
            // $user_service = DB::table('users as u')
            // ->join('role_masters as rm', 'rm.id', '=', 'u.role')
            // ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
            // ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
            // ->select('uc.checks','u.id' )
            // ->where(['u.business_id'=>Auth::user()->business_id,'uc.checks'=>$service_id])
            // ->get();

            // $task =DB::table('tasks');
            
            // $user_id =[];
            // foreach($user_service as $us)
            // {
            //     $user_id[]= $us->id;

               
               

            // }
            // $created_date = Carbon::parse($request->created_time)->format('d-m-Y');
            // $completion_date = Carbon::now()->addDays($task_time)->format('d-m-Y');
            $now = Carbon::now()->format('d-m-Y');
          
            // echo"<pre>";
            // print_r($created_date);
            // die;
            // dd($user_service); 
            $action_master = DB::table('action_masters')
            ->select('*')
            ->where(['route_group'=>'','action_title'=>'Generate Candidate Reports'])
            ->first();
            // dd($action_master);
            $data = "<option value=''>Select User</option>";
            foreach($users as $user){
               
                  
                    if ( in_array($action_master->id,json_decode($user->permission_id)) && $action_master->action_title == 'Generate Candidate Reports')  {
                        
                        // $tasks =DB::table('tasks as t')
                        // ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                        // ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                        // ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                        // ->where(['u.is_deleted'=>'0','description'=>'Task for Verification ','ta.status'=>'1','ta.user_id'=>$user->id])
                        // ->whereDate('t.start_date','<=',$now)
                        // ->count(); '.' ( Assigned tasks-'.$tasks.' )

                        $data .=" <option value=".$user->id. ">".$user->name.'</option>' ;
                    }
                
            }

            return response()->json([
                'fail'      =>false,
                'data' => $data,
                
                  ]);
             
    }
    /**
     * 
     *
     *Task Reassign the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
    */
    public function reassignModal(Request $request)
    {
        
            // 'roles'     => 'required'
            $user_id = Auth::user()->id;
            $business_id = Auth::user()->business_id;
           
            $service_id =$request->input('service_id');
            $candidate_id=$request->candidate_id;
            $task_time =$request->input('task_time');
            $number_of_verifications=$request->number_of_verifications;
            $user_type=$request->user_type;

        if ($user_type == 'user') {
            # code...
        
            // dd($candidate_id);
            // $services = explode(',', $service_id);
            $task =DB::table('task_assignments')->where(['candidate_id'=>$candidate_id,'service_id'=>$service_id,'status'=>'1','number_of_verifications'=>$number_of_verifications])->whereNotNull('user_id')->first();
            //dd($task);
            $users = DB::table('users as u')
                    ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                    ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                    ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                    ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0])
                    ->whereNotIn('u.id',[$task->user_id])
                    ->get();
            // // 
            $user_service = DB::table('users as u')
                            ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                            ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                            ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
                            ->select('uc.checks','u.id' )
                            ->where(['u.business_id'=>Auth::user()->business_id,'uc.checks'=>$service_id])
                            ->get();

            $user_id =[];
            foreach($user_service as $us)
            {
                $user_id[]= $us->id;
            }
            // $created_date = Carbon::parse($request->created_time)->format('d-m-Y');
            // $completion_date = Carbon::now()->addDays($task_time)->format('d-m-Y');
            $now = Carbon::now()->format('d-m-Y');
          
            // echo"<pre>";
            // print_r($created_date);
            // die;
            // dd($user_service); 
            $action_master = DB::table('action_masters')
            ->select('*')
            ->where(['route_group'=>'','action_title'=>'JAF Filled'])
            ->first();
            $data = "<option value=''>Select User</option>";

            foreach($users as $user){
               
                  
                    if ( in_array($action_master->id,json_decode($user->permission_id)) && $action_master->action_title == 'JAF Filled' && in_array($user->id,$user_id)   ) {
                        
                        $tasks =DB::table('tasks as t')
                        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                        ->join('candidate_reinitiates as u', 't.candidate_id', '=', 'u.id')
                        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                        ->where(['u.is_deleted'=>'0','description'=>'Task for Verification ','ta.status'=>'1','ta.user_id'=>$user->id])
                        ->whereDate('t.start_date','<=',$now)
                        ->count();

                        $data .=" <option value=".$user->id. ">".$user->name.' '.' ( Assigned tasks-'.$tasks.' )</option>' ;
                    }
                
            }

            return response()->json([
                'fail'      =>false,
                'data' => $data,  
            ]);

        }     

        $task =DB::table('task_assignments')->where(['candidate_id'=>$candidate_id,'service_id'=>$service_id,'status'=>'1','number_of_verifications'=>$number_of_verifications])->whereNotNull('user_id')->first();
        $taskId = $task->business_id;
        
        $cam=[];
        $kams = DB::table('key_account_managers')->where(['user_id'=>$user_id,'business_id'=>$taskId])->get();
       
        foreach ($kams as $kam) {
           $cam[]= $kam->user_id;
        }

        if ($user_type == 'vendor') {

            if(Auth::user()->user_type=='customer')
            {
                $vendors = DB::table('users as u')
                    ->join('vendors as v', 'v.user_id','=','u.id')
                    ->join('vendor_slas as vs','vs.vendor_id','=','v.id')
                    ->select('u.*')
                    ->where(['u.parent_id'=>Auth::user()->business_id,'u.user_type'=>'vendor'])
                    ->groupBy('v.id')
                    ->whereNotIn('v.user_id',[$task->user_id])
                    ->get();
            }
            else if(in_array($user_id,$cam))
            {
                //$task =DB::table('task_assignments')->where(['candidate_id'=>$candidate_id,'service_id'=>$service_id,'status'=>'1','number_of_verifications'=>$number_of_verifications])->whereNotNull('user_id')->first();
                $vendors = DB::table('users as u')
                    ->join('vendors as v', 'v.user_id','=','u.id')
                    ->join('vendor_slas as vs','vs.vendor_id','=','v.id')
                    ->select('u.*')
                    ->where(['u.parent_id'=>Auth::user()->business_id,'u.user_type'=>'vendor'])
                    ->groupBy('v.id')
                    ->whereNotIn('v.user_id',[$task->user_id])
                    ->get();
            }
            else
            {
                $users = User::from('users')->where('id',$user_id)->first();
                $vendorId = $users->vendor_id;
                $usersVendorid =   explode(',',$vendorId);
                //$task =DB::table('task_assignments')->where(['candidate_id'=>$candidate_id,'service_id'=>$service_id,'status'=>'1','number_of_verifications'=>$number_of_verifications])->whereNotNull('user_id')->first();
                
                $vendors = User::from('users as u')
                    ->join('vendors as v', 'v.user_id','=','u.id')
                    ->join('vendor_slas as vs','vs.vendor_id','=','v.id')
                    ->select('u.*')
                    ->where(['u.parent_id'=>Auth::user()->business_id,'u.user_type'=>'vendor'])
                    ->groupBy('v.id')
                    ->whereNotIn('v.user_id',[$task->user_id])
                    ->whereIn('v.user_id',$usersVendorid)
                    ->get();
            }
                //dd($vendors);
            // $vendors = DB::table('users')->where(['parent_id'=>Auth::user()->business_id,'user_type'=>'vendor'])->whereNotIn('id',[$task->user_id])->get();
            $data = "<option value=''>Select Vendor</option>";
            foreach ($vendors as $vendor) {
                $data .=" <option value=".$vendor->id. ">".$vendor->name.' </option>' ;
            }

            return response()->json([
                'fail'      =>false,
                'data' => $data,    
            ]);
        }     
             
    }
    
    //Get Vendor Sla 
    public function vendorSla(Request $request)
    {

        $vendor_id = $request->vendor_id;
         //   dd($request->vendor_id);
        $vendors = DB::table('vendor_slas as vs')
        ->join('vendor_sla_items as vsi', 'vsi.sla_id', '=', 'vs.id')
        ->select('vs.id','vs.title')
        ->where(['vs.business_id'=>$vendor_id,'vsi.service_id'=>$request->service_id])->get();

        $data = "<option value=''>Select SLA</option>";
        if (count($vendors)>0) {
            # code...
        
            foreach($vendors as $sla){
                $data .=" <option value=".$sla->id. ">".$sla->title.'</option>' ;
            }
            return response()->json([
                'fail'      =>false,
                'data' => $data,
                
            ]); 
        }
        else {
            return response()->json([
                'fail'      =>true,
                'custom'  =>'yes',
                'errors'  =>['vendor_sla'=>'Please select other vendor or Create Sla of this vendor !']
                
            ]); 
        }  
    }

    //Get Reassign Vendor Sla 
    public function reassignVendorSla(Request $request)
    {




        $vendor_id = $request->vendor_id;
            
        $vendors = DB::table('vendor_slas as vs')
        ->join('vendor_sla_items as vsi', 'vsi.sla_id', '=', 'vs.id')
        ->select('vs.id','vs.title')
        ->where(['vs.business_id'=>$vendor_id,'vsi.service_id'=>$request->service_id])->get();
        // dd($vendors);
        
            $data = "<option value=''>Select SLA</option>";
            if (count($vendors)>0) {
                # code...
            
                foreach($vendors as $sla){
                    $data .=" <option value=".$sla->id. ">".$sla->title.'</option>' ;
                }
                return response()->json([
                    'fail'      =>false,
                    'data' => $data,
                    
                ]); 
            }
            else {
                return response()->json([
                    'fail'      =>true,
                    'custom'  =>'yes',
                    'errors'  =>['reassign_sla_id'=>'Please select other vendor or Create Sla of this vendor ! ']
                    
                ]); 
            }  
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
    */
    public function reportAssignUser(Request $request)
    {
        //   dd($request);
        
        $task_id =$request->get('report_task_id'); 
        $job_sla_item_id =$request->get('report_job_sla_item_id');
         $rules = [
            'report_users' => 'required',
            //   'user_status' => 'required'
        
        ];
        $customMessages=[
            'report_users.required' => 'Please select a user first!',
          ];

        $validator = Validator::make($request->all(), $rules,$customMessages);
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        DB::beginTransaction();
        try{
            $business_id = Auth::user()->business_id;
            $task= Task::find($task_id);
            if($task)
            {
                $task->assigned_to = $request->report_users;
                $task->assigned_by = Auth::user()->id;
                $task->assigned_at = date('Y-m-d H:i:s');
                $task->start_date = date('Y-m-d');
                $task->updated_at = date('Y-m-d H:i:s');
                $task->save();

                $task_assgn = TaskAssignment::where(['task_id'=>$task_id])->update(['user_id'=>$request->report_users,'user_type'=>'user','updated_at'=> date('Y-m-d H:i:s'),'job_sla_item_id'=>$job_sla_item_id]);
                       
                
                $user= User::where('id',$request->report_users)->first();
                if ($user->email) {
                    # code...
                
                $email = $user->email;
                $name  = $user->name;
                $candidate_name =  Helper::candidate_user_name($user->candidate_id);
                $msg = " Report Generation Task Assign to you with candidate name";
                $sender = DB::table('users')->where(['id'=>$business_id])->first();
                $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
                EmailConfigTrait::emailConfig();
                //get Mail config data
                  //   $mail =null;
                  $mail= Config::get('mail');
                  // dd($mail['from']['address']);
                  if (count($mail)>0) {
                      Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                          $message->to($email, $name)->subject
                          ('myBCD System - Notification for Report Generation Task');
                          $message->from($mail['from']['address'],$mail['from']['name']);
                      });
                  }else {
                       Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                           $message->to($email, $name)->subject
                               ('myBCD System - Notification for Report Generation Task');
                           $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                       });
                   }
               }
                //   $user_type =$request->user_status;
           
              DB::commit();
                return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'errors'  =>[]
                ]);
            }else{
                return response()->json([
                'success' =>false,
                'custom'  =>'yes',
                'errors'  =>[]
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
    */
    public function assignUser(Request $request)
    {
         //dd($request->job_sla_items_id);
        $business_id = Auth::user()->business_id;
        // dd($request);
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
        $user_type =$request->user_status;
        DB::beginTransaction();
        try{
            $type = $request->type;
            if($type)
            {
                if($type == 'verify_task')
                {
                    $tasks_id =$request->get('verify_task_id');
                    $tasks= Task::find($tasks_id);
                    if($tasks)
                    {
                        // $new_assign = str_replace($user_id,'',$task->assigned_to);
                        // $new_assign = str_replace(array(',,', '[,',',]'), array(',', '[',']'), $new_assign);
                        
            
                        // $tasks->assigned_to = $request->users;
                        // $tasks->assigned_by = Auth::user()->id;
                        // $tasks->assigned_at = date('Y-m-d H:i:s');
                        // $tasks->start_date = date('Y-m-d');
                        // $tasks->updated_at = date('Y-m-d H:i:s');
                        // $tasks->save();
            
                        // $task_assgn = TaskAssignment::where(['task_id'=>$tasks_id])->update(['user_id'=>$request->users,'updated_at'  => date('Y-m-d H:i:s')]);
                        


                        $tasks->assigned_to = $request->users;
                        $tasks->assigned_by = Auth::user()->id;
                        $tasks->assigned_at = date('Y-m-d H:i:s');
                        $tasks->start_date = date('Y-m-d');
                        $tasks->updated_at = date('Y-m-d H:i:s');
                        $tasks->save();
            
                        $task_assgn = TaskAssignment::where(['task_id'=>$tasks_id])->update(['user_id'=>$request->users,'user_type'=>$user_type,'updated_at'=> date('Y-m-d H:i:s'),'job_sla_item_id'=>$request->job_sla_items_id]);
                       
                        if ($user_type=='vendor') {
                            $task_assgn = TaskAssignment::where(['task_id'=>$tasks_id])->update(['user_id'=>$request->users,'user_type'=>$user_type,'updated_at'=> date('Y-m-d H:i:s'),'job_sla_item_id'=>$request->vendor_sla]);

                            $vendor = DB::table('vendors')->select('id')->where('user_id',$request->users)->first();
                            $vendor_sla = DB::table('vendor_sla_items')->select('sla_id','tat')->where(['vendor_id'=>$vendor->id,'service_id'=>$request->modal_service_id])->first();
                            $vendor_task = new VendorTask;
                            $vendor_task->parent_id = Auth::user()->business_id;
                            $vendor_task->business_id =  $request->users;
                            $vendor_task->candidate_id = $request->verify_candidate;
                            $vendor_task->task_id = $tasks_id;
                            $vendor_task->service_id = $request->modal_service_id;
                            $vendor_task->vendor_sla_id = $request->vendor_sla;
                            $vendor_task->status = '1';
                            $vendor_task->no_of_verification = $tasks->number_of_verifications;
                            $vendor_task->assigned_to = $request->users;
                            $vendor_task->assigned_by = Auth::user()->id;
                            $vendor_task->assigned_at = date('Y-m-d H:i:s');
                            $vendor_task->tat = $vendor_sla->tat;
                            $vendor_task->save();

                            $vendor_task_assign = new VendorTaskAssignment;
                            $vendor_task_assign->parent_id = Auth::user()->business_id;
                            $vendor_task_assign->business_id =  $vendor_task->business_id;
                            $vendor_task_assign->candidate_id = $request->verify_candidate;
                            $vendor_task_assign->vendor_task_id = $vendor_task->id;
                            $vendor_task_assign->service_id = $request->modal_service_id;
                            $vendor_task_assign->vendor_sla_id = $request->vendor_sla;
                            $vendor_task_assign->status = '1';
                            $vendor_task_assign->no_of_verification = $tasks->number_of_verifications;
                            $vendor_task_assign->save();
                        }
                        // $login_user = Auth::user()->business_id;
                        // Mail send to user
                            
                            $user= User::where('id',$request->users)->first();
                            $email = $user->email;
                            $name  = $user->name;
                            $candidate_name =  Helper::candidate_user_name($request->verify_candidate);
                            $sender = DB::table('users')->where(['id'=>$business_id])->first();
                            $msg = "JAF Verification Task Assign to you with candidate name";
                            $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
            
                            Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('myBCD System - Notification for JAF Verification Task');
                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            });

                            
                        
                        DB::commit();
                        return response()->json([
                        'success' =>true,
                        'custom'  =>'yes',
                        'errors'  =>[]
                        ]);
                    }else{
                        return response()->json([
                        'success' =>false,
                        'custom'  =>'yes',
                        'errors'  =>[]
                        ]);
                    }
                }
            }
            else{

            
                $candidate_id = $request->get('candidate_id');  
                $business_id = $request->get('business_id');  
                // $user_id =$request->get('user_id'); 
                $task_id =$request->get('task_id'); 
                $job_sla_item_id =$request->get('job_sla_item_id');
                
                $task= Task::find($task_id);
                
                if($task)
                {
                    // $new_assign = str_replace($user_id,'',$task->assigned_to);
                    // $new_assign = str_replace(array(',,', '[,',',]'), array(',', '[',']'), $new_assign);
                    
                    $task->assigned_to = $request->users;
                    $task->assigned_by = Auth::user()->id;
                    $task->assigned_at = date('Y-m-d H:i:s');
                    $task->start_date = date('Y-m-d');
                    $task->updated_at = date('Y-m-d H:i:s');
                    $task->save();
        
                    $task_assgn = TaskAssignment::where(['task_id'=>$task_id])->update(['user_id'=>$request->users,'user_type'=>$user_type,'updated_at'=> date('Y-m-d H:i:s'),'job_sla_item_id'=>$job_sla_item_id]);
                       
                    if ($user_type=='vendor') {
                        $task_assgn = TaskAssignment::where(['task_id'=>$task_id])->update(['user_id'=>$request->users,'user_type'=>$user_type,'updated_at'=> date('Y-m-d H:i:s'),'job_sla_item_id'=>$request->vendor_sla]);
                        $vendor = DB::table('vendors')->select('id')->where('user_id',$request->users)->first();
                        $vendor_sla = DB::table('vendor_sla_items')->select('sla_id')->where(['vendor_id'=>$vendor->id,'service_id'=>$request->modal_service_id])->first();
                        $vendor_task = new VendorTask;
                        $vendor_task->parent_id = Auth::user()->business_id;
                        $vendor_task->business_id =  $request->users;
                        $vendor_task->candidate_id = $request->verify_candidate;
                        $vendor_task->task_id = $task_id;
                        $vendor_task->service_id = $request->modal_service_id;
                        $vendor_task->vendor_sla_id = $request->vendor_sla;
                        $vendor_task->status = '1';
                        $vendor_task->no_of_verification = $task->number_of_verifications;
                        $vendor_task->assigned_to = $request->users;
                        $vendor_task->assigned_by = Auth::user()->id;
                        $vendor_task->assigned_at = date('Y-m-d H:i:s');
                        $vendor_task->save();

                        $vendor_task_assign = new VendorTaskAssignment;
                        $vendor_task_assign->parent_id = Auth::user()->business_id;
                        $vendor_task_assign->business_id =  $vendor_task->business_id;
                        $vendor_task_assign->candidate_id = $request->verify_candidate;
                        $vendor_task_assign->vendor_task_id = $vendor_task->id;
                        $vendor_task_assign->service_id = $request->modal_service_id;
                        $vendor_task_assign->vendor_sla_id = $request->vendor_sla;
                        $vendor_task_assign->status = '1';
                        $vendor_task_assign->no_of_verification = $task->number_of_verifications;
                        $vendor_task_assign->save();
                    }
                     

                    // $login_user = Auth::user()->business_id;
                    // Mail send to user

                        $user= User::where('id',$request->users)->first();
                        $email = $user->email;
                        $name  = $user->name;
                        $candidate_name =  Helper::candidate_user_name($request->verify_candidate);
                        $msg = " JAF verification Task Assign to you  with candidate name";
                        $sender = DB::table('users')->where(['id'=>$business_id])->first();
                        $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
                        EmailConfigTrait::emailConfig();
                        //get Mail config data
                          //   $mail =null;
                          $mail= Config::get('mail');
                          // dd($mail['from']['address']);
                          if (count($mail)>0) {
                              Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                                  $message->to($email, $name)->subject
                                  ('myBCD System - Notification for JAF verification Task');
                                  $message->from($mail['from']['address'],$mail['from']['name']);
                              });
                          }else {
                                Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                    $message->to($email, $name)->subject
                                        ('myBCD System - Notification for JAF verification Task');
                                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                });
                        }
                        DB::commit();
                    return response()->json([
                    'success' =>true,
                    'custom'  =>'yes',
                    'errors'  =>[]
                    ]);
                }else{
                    return response()->json([
                    'success' =>false,
                    'custom'  =>'yes',
                    'errors'  =>[]
                    ]);
                }
            }
            // dd($user_id);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

     /**Bull task Assign 
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function bulkAssign(Request $request)
    {
        // dd($request);
        $from_date = $to_date=$user_id= "";
        $business_id = Auth::user()->business_id;
        $user_type= $request->bulk_user_status;
        $bulk_verify_task = $request->bulk_verify_task_id;
        $bulk_task_type=$request->bulk_task_type;
        // dd($bulk_verify_task);
        // $bulk_verify_task_id= $request->bulk_verify_task_id;
        // var_dump($business_id);
        DB::beginTransaction();
        try{
            if($user_type=='vendor'){
            
                if($bulk_verify_task)
                {
                    $task_ids =  $bulk_verify_task;
                    $task_id = explode(',',$task_ids);
                    // var_dump($task_id);
                }
                if($request->bulk_users)
                {
                    $user_id=  $request->bulk_users;
                }
                // dd($user_id);
                // $user_id =$request->user_id;
                $vendor_sla_id = $request->bulk_vendor_sla;
                $vendors = DB::table('vendor_slas as vs')
                ->join('vendor_sla_items as vsi', 'vsi.sla_id', '=', 'vs.id')
                ->select('vsi.service_id')
                ->where(['vs.business_id'=>$user_id,'vsi.sla_id'=>$vendor_sla_id])->get();
                foreach ($vendors as $vendor) {
                    
                    $services_id[]= $vendor->service_id;
                }
                // dd($services_id);
                $tasks =  DB::table('tasks')->where(['assigned_to'=>null,'description'=>'Task for Verification'])->whereIn('id',$task_id)->get();
                // dd($tasks);
                if (count($tasks)>0) {
                    foreach ($tasks as $key => $task) {

                        if (in_array($task->service_id,$services_id)) {
                           
                            $tasks_id= Task::find($task->id);
                            $tasks_id->assigned_to = $user_id;
                            $tasks_id->assigned_by = Auth::user()->id;
                            $tasks_id->assigned_at = date('Y-m-d H:i:s');
                            $tasks_id->start_date = date('Y-m-d');
                            $tasks_id->updated_at = date('Y-m-d H:i:s');
                            $tasks_id->save();

                            // $task_data= DB::table('task_assignments')->where(['task_id'=>$task->id,'user_id'=>0])->first();
                            $task_assgn = TaskAssignment::where(['task_id'=>$task->id])->update(['user_id'=>$user_id,'user_type'=>$user_type,'updated_at'=> date('Y-m-d H:i:s'),'job_sla_item_id'=>$vendor_sla_id]);

                            $vendor = DB::table('vendors')->select('id')->where('user_id',$user_id)->first();
                            $vendor_sla = DB::table('vendor_sla_items')->select('sla_id','tat')->where(['vendor_id'=>$vendor->id,'service_id'=>$task->service_id])->first();
                            $vendor_task = new VendorTask;
                            $vendor_task->parent_id = Auth::user()->business_id;
                            $vendor_task->business_id =  $user_id;
                            $vendor_task->candidate_id = $task->candidate_id;
                            $vendor_task->task_id = $task->id;
                            $vendor_task->service_id = $task->service_id;
                            $vendor_task->vendor_sla_id = $vendor_sla_id;
                            $vendor_task->status = '1';
                            $vendor_task->no_of_verification = $task->number_of_verifications;
                            $vendor_task->assigned_to = $user_id;
                            $vendor_task->assigned_by = Auth::user()->id;
                            $vendor_task->assigned_at = date('Y-m-d H:i:s');
                            $vendor_task->tat = $vendor_sla->tat;
                            $vendor_task->save();

                            $vendor_task_assign = new VendorTaskAssignment;
                            $vendor_task_assign->parent_id = Auth::user()->business_id;
                            $vendor_task_assign->business_id =  $vendor_task->business_id;
                            $vendor_task_assign->candidate_id = $task->candidate_id;
                            $vendor_task_assign->vendor_task_id = $vendor_task->id;
                            $vendor_task_assign->service_id = $task->service_id;
                            $vendor_task_assign->vendor_sla_id = $vendor_sla_id;
                            $vendor_task_assign->status = '1';
                            $vendor_task_assign->no_of_verification = $task->number_of_verifications;
                            $vendor_task_assign->save();
                        }

                    }
                    DB::commit();
                    return response()->json([
                        'fail' => false,
                        'status'=>'ok',
                        'message' => 'updated',
                        ], 200);
                    
    
                }
                else {
                    return response()->json([
                        'fail' => true,
                        'status' =>'no',
                        ], 200);
                }

                
            }
            else{
               
                if($bulk_verify_task)
                {
                    // $task_id =  $bulk_verify_task;
                    $task_ids =  $bulk_verify_task;
                    $task_id = explode(',',$task_ids);
                    // dd($task_id);
                }
                if($request->bulk_users)
                {
                    $user_id=  $request->bulk_users;
                }
                $i=0;
                $j=0;
                $k=0;
                $service_id=[];
                $candidate_id=[];
                // foreach ($task_id as $key => $task) {
                $users = DB::table('users as u')
                ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                ->select('u.name','u.id','u.role as role_id','rm.role','rp.permission_id' )
                ->where(['u.business_id'=>Auth::user()->business_id,'u.is_deleted'=>0])
                ->get();
                
                $tasks =  DB::table('tasks')->where('assigned_to',null)->whereIn('id',$task_id)->get();
                //   dd($tasks);
                if (count($tasks)>0) {
                    foreach ($tasks as $key => $task) {
                       if (stripos($bulk_task_type,'jaf')!==false) {
                            if (stripos($task->description,'JAF Filling')!==false && $task->assigned_to==null) {
                                // dd($task->description);
                                $action_master = DB::table('action_masters')
                                ->select('*')
                                ->where(['route_group'=>'','action_title'=>'JAF Link'])
                                ->first();

                                foreach ($users as $key => $user) {
                                
                                    // dd(json_decode($user->permission_id));
                                    if ( in_array($action_master->id,json_decode($user->permission_id)) && $action_master->action_title == 'JAF Link') {
                                        
                                        if ($user_id ==$user->id ) {
                                            // dd($user_id);
                                            $filling_task= Task::find($task->id);
                                            
                                            if($filling_task)
                                            {
                                                // $new_assign = str_replace($user_id,'',$task->assigned_to);
                                                // $new_assign = str_replace(array(',,', '[,',',]'), array(',', '[',']'), $new_assign);
                                                
                                    
                                                $filling_task->assigned_to = $user_id;
                                                $filling_task->assigned_by = Auth::user()->id;
                                                $filling_task->assigned_at = date('Y-m-d H:i:s');
                                                $filling_task->start_date = date('Y-m-d');
                                                $filling_task->updated_at = date('Y-m-d H:i:s');
                                                $filling_task->save();
                                    
                                                $task_assgn = TaskAssignment::where(['task_id'=>$task->id])->update(['user_id'=>$user_id,'updated_at'  => date('Y-m-d H:i:s')]);
                                                
                                                // Mail send to user
                                                $j++;
                                                // $user= User::where('id',$user_id)->first();
                                                // $email = $user->email;
                                                // $name  = $user->name;
                                                // $candidate_name =  Helper::user_name($task->candidate_id);
                                                // $msg = " JAF Filling Task Assign to you with candidate name";
                                                // $sender = DB::table('users')->where(['id'=>$business_id])->first();
                                                // // dd($sender);
                                                // $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg);
                                                // EmailConfigTrait::emailConfig();
                                                // //get Mail config data
                                                //   //   $mail =null;
                                                //   $mail= Config::get('mail');
                                                //   // dd($mail['from']['address']);
                                                // if (count($mail)>0) {
                                                //       Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                                                //           $message->to($email, $name)->subject
                                                //           ('myBCD System - Notification for JAF Filling Task');
                                                //           $message->from($mail['from']['address'],$mail['from']['name']);
                                                //       });
                                                // }else {
                                                //     Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                                //         $message->to($email, $name)->subject
                                                //             ('myBCD System - Notification for JAF Filling Task');
                                                //         $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                                //     });
                                                // }


                                                
                                            }
                                            // else {
                                            //     return response()->json([
                                            //         'fail' => true,
                                            //         'status' =>'no',
                                            //         ], 200);
                                            // }

                                        }

                                    }
                                    
                                }


                            }
                       }
                       elseif (stripos($bulk_task_type,'task')!==false) {
                            if (stripos($task->description,'Task for Verification')!==false && $task->assigned_to==null) {
                                // echo"<pre>";
                                // print_r($task->description);
                                // dd($task->description);
                                // dd($users);
                                
                                $user_service = DB::table('users as u')
                                ->join('role_masters as rm', 'rm.id', '=', 'u.role')
                                ->join('role_permissions as rp', 'rp.role_id', '=', 'rm.id')
                                ->leftJoin('user_checks as uc','uc.user_id','=','u.id')
                                ->select('uc.checks','u.id' )
                                ->where(['u.business_id'=>Auth::user()->business_id,'uc.checks'=>$task->service_id])
                                ->get();
                                
                                // $task =DB::table('tasks');
                                
                                $user_service_id =[];
                                foreach($user_service as $us)
                                {
                                    $user_service_id[]= $us->id;
                    
                                }
                                
                                $action_master = DB::table('action_masters')
                                ->select('*')
                                ->where(['route_group'=>'','action_title'=>'JAF Filled'])
                                ->first();
                                
                                foreach($users as $user){
                                    if ( in_array($action_master->id,json_decode($user->permission_id)) && $action_master->action_title == 'JAF Filled' && in_array($user->id,$user_service_id) ) {
                                        
                                        
                                        if ($user_id ==$user->id ) {

                                            $verify_task= Task::find($task->id);
                                            if($verify_task)
                                            {
                                                // $new_assign = str_replace($user_id,'',$task->assigned_to);
                                                // $new_assign = str_replace(array(',,', '[,',',]'), array(',', '[',']'), $new_assign);
                                                
                                    
                                                $verify_task->assigned_to = $user_id;
                                                $verify_task->assigned_by = Auth::user()->id;
                                                $verify_task->assigned_at = date('Y-m-d H:i:s');
                                                $verify_task->start_date = date('Y-m-d');
                                                $verify_task->updated_at = date('Y-m-d H:i:s');
                                                $verify_task->save();
                                    
                                                $task_assgn = TaskAssignment::where(['task_id'=>$task->id])->update(['user_id'=>$user_id,'updated_at'  => date('Y-m-d H:i:s')]);
                                                $i++;
                                                // $login_user = Auth::user()->business_id;
                                                // Mail send to user
                            
                                                    // $user= User::where('id',$user_id)->first();
                                                    // $email = $user->email;
                                                    // $name  = $user->name;
                                                    // $candidate_name =  Helper::candidate_user_name($task->candidate_id);
                                                    // $msg = " JAF verification Task Assign to you with candidate name";
                                                    // $sender = DB::table('users')->where(['id'=>$business_id])->first();
                                                    // // dd($sender);
                                                    // $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'sender'=>$sender);
                                                    // EmailConfigTrait::emailConfig();
                                                    // //get Mail config data
                                                    //   //   $mail =null;
                                                    // $mail= Config::get('mail');
                                                    //   // dd($mail['from']['address']);
                                                    // if (count($mail)>0) {
                                                    //       Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                                                    //           $message->to($email, $name)->subject
                                                    //           ('myBCD System - Notification for JAF verification Task');
                                                    //           $message->from($mail['from']['address'],$mail['from']['name']);
                                                    //       });
                                                    // }else {
                                                    //     Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                                    //         $message->to($email, $name)->subject
                                                    //             ('myBCD System - Notification for JAF verification Task');
                                                    //         $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                                    //     });
                                                    // }
                                                    // return response()->json([
                                                    //     'fail' => false,
                                                    //     'status'=>'ok',
                                                    //     'message' => 'updated',                
                                                    //     ], 200);
                                        
                                            }
                                            // else {
                                            //     return response()->json([
                                            //         'fail' => true,
                                            //         'status' =>'no',
                                            //         ], 200);
                                            // }
                                        }


                                    }
                                    
                                }
                        
                            }
                       } 
                       elseif (stripos($bulk_task_type,'report')!==false) {
                            if (stripos($task->description,'Report generation')!==false && $task->assigned_to==null) {
                                // dd($task->description);
                                $action_master = DB::table('action_masters')
                                ->select('*')
                                ->where(['route_group'=>'','action_title'=>'Generate Candidate Reports'])
                                ->first();

                                foreach ($users as $key => $user) {
                                
                                    
                                    // dd(json_decode($user->permission_id));
                                    if ( in_array($action_master->id,json_decode($user->permission_id)) && $action_master->action_title == 'Generate Candidate Reports') {
                                        // dd($user_id);
                                        if ($user_id ==$user->id ) {
                                            // dd($user->id);
                                            $filling_task= Task::find($task->id);
                                            
                                            if($filling_task)
                                            {
                                                // $new_assign = str_replace($user_id,'',$task->assigned_to);
                                                // $new_assign = str_replace(array(',,', '[,',',]'), array(',', '[',']'), $new_assign);
                                                
                                                // dd($filling_task);
                                                $filling_task->assigned_to = $user_id;
                                                $filling_task->assigned_by = Auth::user()->id;
                                                $filling_task->assigned_at = date('Y-m-d H:i:s');
                                                $filling_task->start_date = date('Y-m-d');
                                                $filling_task->updated_at = date('Y-m-d H:i:s');
                                                $filling_task->save();
                                    
                                                $task_assgn = TaskAssignment::where(['task_id'=>$task->id])->update(['user_id'=>$user_id,'updated_at'  => date('Y-m-d H:i:s')]);
                                                
                                                // Mail send to user
                                                $k++;
                                                // $user= User::where('id',$user_id)->first();
                                                // $email = $user->email;
                                                // $name  = $user->name;
                                                // $candidate_name =  Helper::candidate_user_name($task->candidate_id);
                                                // $msg = " JAF Filling Task Assign to you with candidate name";
                                                // $sender = DB::table('users')->where(['id'=>$business_id])->first();
                                                // // dd($sender);
                                                // $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg);
                                                // EmailConfigTrait::emailConfig();
                                                // //get Mail config data
                                                //   //   $mail =null;
                                                //   $mail= Config::get('mail');
                                                //   // dd($mail['from']['address']);
                                                // if (count($mail)>0) {
                                                //       Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail) {
                                                //           $message->to($email, $name)->subject
                                                //           ('myBCD System - Notification for JAF Filling Task');
                                                //           $message->from($mail['from']['address'],$mail['from']['name']);
                                                //       });
                                                // }else {
                                                //     Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name) {
                                                //         $message->to($email, $name)->subject
                                                //             ('myBCD System - Notification for JAF Filling Task');
                                                //         $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                                //     });
                                                // }


                                                
                                            }
                                            // else {
                                            //     return response()->json([
                                            //         'fail' => true,
                                            //         'status' =>'no',
                                            //         ], 200);
                                            // }

                                        }

                                    }
                                    
                                }


                            }
                       } 
                    
                    }
                    $l = $i+$j+$k;
                    if($l>0){
                        $assign_by = Auth::user()->name;
                        $user= User::where('id',$user_id)->first();
                        $email = $user->email;
                        $name  = $user->name;
                        $candidate_name =  Helper::candidate_user_name($task->candidate_id);
                        if($k==1){
                            $msg = "task has been assigned to you";
                        }
                        else{
                            $msg = "tasks have been assigned to you";
                        }
                        $sender = DB::table('users')->where(['id'=>$business_id])->first();
                        
                        // dd($sender);
                        $data  = array('name'=>$name,'email'=>$email,'candidate_name'=>$candidate_name,'msg'=>$msg,'no_of_task'=>$k,'sender'=>$sender,'assign_by'=>$assign_by);
                        EmailConfigTrait::emailConfig();
                        //get Mail config data
                        //   $mail =null;
                        $mail= Config::get('mail');
                        // dd($mail['from']['address']);
                        if (count($mail)>0) {
                            Mail::send(['html'=>'mails.bulk_assign'], $data, function($message) use($email,$name,$mail) {
                                $message->to($email, $name)->subject
                                ('myBCD System - Notification for JAF Task');
                                $message->from($mail['from']['address'],$mail['from']['name']);
                            });
                        }else {
                            Mail::send(['html'=>'mails.bulk_assign'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('myBCD System - Notification for JAF Task');
                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            });
                        }
                    
                        DB::commit();
                        return response()->json([
                            'fail' => false,
                            'status'=>'ok',
                            'message' => 'updated',
                            ], 200);
                    }
                    else{
                        return response()->json([
                            'fail' => false,
                            'status' =>'zero',
                            ], 200);
                    }
                            // $no_of_verifications[] =$tasks->number_of_verifications;'description'=>'Task for Verification '
                }
                else {
                    return response()->json([
                        'fail' => true,
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
        
        // dd($business_id);
        // if( $request->session()->has('from_date') && $request->session()->has('to_date'))
        //   {  
        //     $from_date     =  $request->session()->get('from_date');
        //     $to_date       =  $request->session()->get('to_date');
        //   }
        //   else
        //   {
        //     if($request->session()->has('from_date'))
        //     {
        //       $from_date     =  $request->session()->get('from_date');
        //     }
        //   }
        
                    // }
         
        // return response()->json([
        //     'success' =>false,
        //     'custom'  =>'yes',
        //     'errors'  =>[]
        //   ]);

    }

    // Preview of completed task
    public function taskPreview(Request $request)
    {
        $form='';
        $task_id=$request->task_id;
        // dd($task_id);
        $vendor_task = DB::table('vendor_tasks')->where(['task_id'=>$task_id])->whereIn('status',['1','2'])->first();
        if ($vendor_task) {
            $task = DB::table('tasks')->where('id',$task_id)->first();
            $ver_status=DB::table('vendor_verification_statuses')->where(['vendor_task_id'=>$vendor_task->id])->first();

            if($ver_status->remarks==NULL){
                $comments='N/A';
                // $status = $data->status=='done'?'Done' :'Unable to verify';
             } else{
                $comments=$ver_status->remarks;
                // $status = $data->status=='done'?'Done' :'Unable to verify';
                
             }
             if($ver_status->status==NULL){
                $status='N/A';
                // $status = $data->status=='done'?'Done' :'Unable to verify';
             } else{
                // $comments=$data->remarks;
                $status = $ver_status->status=='done'?'Done' :'Unable to verify';
                
             }

             $add_btn = '';

             if($task)
             {
                $add_btn = '<div class="col-6 text-right">
                                <div class="form-group">
                                    <a class="btn btn-sm btn-outline-info addToReport" href="'.url("/task/vendor/add_report",["id"=>base64_encode($vendor_task->id,)]).'" title="Add to Report"><i class="fas fa-plus"></i> Add to Report </a>
                                </div>
                            </div>';
             }
            //  if()
                $form.='
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="label_name"> <strong>Status:</strong> <span id="comments">'.$status.'</span></label>
                        </div>
                    </div>
                    '.$add_btn.'
                    ';
                $form.='
                    <div class="col-12">
                        <div class="form-group">
                            <label for="label_name"> <strong>Remarks:</strong> <span id="comments">'.$comments.'</span></label>
                        </div>
                    </div>';
            $upload_attach=DB::table('vendor_verification_data')->where(['vendor_task_id'=>$vendor_task->id,'is_deleted'=>'0'])->get();
            if(count($upload_attach)>0)
            {
                $path=url('/').'/uploads/verification-file/';
                $form.='<div class="col-12">
                        <div class="form-group">
                        <label><strong>Attachments: </strong></label>
                        <div class="row">';
                foreach($upload_attach as $upload)
                {
                    $img='';
                    $file=$path.$upload->file_name;
                    $temp= explode('.',$upload->file_name);
                    $extension = end($temp);
                    
                    $img='<img src="'.$file.'" alt="preview" style="height:100px;"/>
                    <a class="remove-image" data-id="'.$upload->id.'" href="javascript:;" style="display: inline;">×</a>
                    <input type="hidden" name="fileID[]" value="'.$upload->id.'">';
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
                                <a href="'.$file.'" id="previewImgBtn">
                                    '.$img.'
                                    <p style="font-size:15px;">'.'<i class="fa fa-eye" >'.' '.'<small>'.'Preview'.'</small>'.'</i>'.'</p>
                                </a>
                            </div><input class="checks" type="checkbox" name="checks[]" style="margin-left: 70px;" id="selected" value="'.$upload->id.'">
                            </div>';
                     }
                } 
                $form.='</div>
                        </div>
                        </div>';
            }
            $form.='</div>';
            return $form;
        }
        // dd($task_id);
    } 


    public function vendorRemoveImage(Request $request)
    {
        $id =  $request->input('id');

       DB::beginTransaction();
       try{
        // DB::table('vendor_verification_data')->where('id',$id)->delete();
            $file_data=DB::table('vendor_verification_data')->where(['id'=>$request->id])->first();
        
            $path=public_path().'/uploads/verification-file/';
           
            if(File::exists($path.$file_data->file_name))
            {
                File::delete($path.$file_data->file_name);
            }
               
            DB::table('vendor_verification_data')->where(['id'=>$id])->update(['is_deleted'=>'1','deleted_at'=>date('Y-m-d H:i:s'),'deleted_by'=>Auth::user()->id]); 
            DB::commit();
            // Do something when it fails
            return response()->json([
                'fail' => false,
                'message' => 'File removed!'
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }    
    }

    public function taskVerifyInfo(Request $request)
    {
        // dd($request->verify_candidate_id);
        $candidate_id= base64_decode($request->verify_candidate_id);
        $verify_service_id= base64_decode($request->verify_service_id);
        $verify_number_id= base64_decode($request->verify_number_id);
        // dd($candidate_id);
        $user_id = Auth::user()->id;
        $business_id = Auth::user()->business_id;
        $report_id ="";
        $jaf_item = [];
        
          $candidate = DB::table('candidate_reinitiates as u')
          ->select('u.id','u.business_id','u.client_emp_code','u.entity_code','u.display_id','u.first_name','u.middle_name','u.last_name','u.name','u.email','u.phone','u.phone_code','u.phone_iso','u.dob','u.aadhar_number','u.father_name','u.gender','j.created_at','j.job_id','j.sla_id','j.is_all_insuff_cleared','j.insuff_cleared_by','j.jaf_status','u.digital_signature','j.is_jaf_ready_report','u.digital_signature_file_platform')  
          ->leftjoin('job_items as j','j.candidate_id','=','u.id')
          ->where(['u.id'=>$candidate_id]) 
          ->first(); 

          //get JAF data - 
          $jaf_item = DB::table('jaf_form_data as jf')
                ->select('jf.id','jf.form_data_all','jf.form_data','jf.check_item_number','jf.address_type','jf.insufficiency_notes','jf.is_insufficiency','jf.insuff_attachment','jf.is_api_checked','jf.verification_status','jf.verified_at','jf.is_data_verified','s.name as service_name','s.id as service_id','s.verification_type','jf.candidate_id','jf.is_supplementary','s.type_name')
                ->join('services as s','s.id','=','jf.service_id')
                ->where(['jf.candidate_id'=>$candidate_id,'jf.service_id'=>$verify_service_id,'jf.check_item_number'=>$verify_number_id])
                ->orderBy('s.sort_number','asc')
                ->orderBy('jf.check_item_number','asc')
                ->first();
                // dd($jaf_item);
          if($jaf_item){
            $is_insuff_arr=$jaf_item->is_insufficiency;
          }
           
          // dd($is_insuff_arr);
          // dd($jaf_items);
          // $job_items=DB::table('job_items')->where(['candidate_id'=>$candidate_id])->first();
          // dFd($kams);

          $report = DB::table('reports')->where(['candidate_id'=>$candidate_id,'status'=>'completed'])->first();
            // dd($report);
            if ($report==NULL) {
              $report= '';
              $report_id='';
              $report_status='';
              $report_items=[];
              $status_list=[];
            }

              $job = DB::table('job_items')->where(['candidate_id'=>$candidate_id])->first();

              if($job->jaf_status=='filled')
              {
                  //check report items created or not
                  $report_count = DB::table('reports')->where(['candidate_id'=>$candidate_id])->count(); 
                  if($report_count == 0){ 
                  
                    $data = 
                      [
                        'parent_id'     =>$business_id,
                        'business_id'   =>$job->business_id,
                        'candidate_id'  =>$candidate_id,
                        'sla_id'        =>$job->sla_id,
                        'created_at'    =>date('Y-m-d H:i:s')
                      ];
                      
                      $report_id = DB::table('reports')->insertGetId($data);
                      
                      // add service items
                      $item = DB::table('jaf_form_data')->where(['candidate_id'=>$candidate_id,'service_id'=>$verify_service_id,'check_item_number'=>$verify_number_id])->first(); 
                    if($item){
                        if ($item->verification_status == 'success') {
                          $data = 
                          [
                            'report_id'     =>$report_id,
                            'service_id'    =>$item->service_id,
                            'service_item_number'=>$item->check_item_number,
                            'candidate_id'  =>$candidate_id,      
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
                            'candidate_id'  =>$candidate_id,      
                            'jaf_data'      =>$item->form_data,
                            'jaf_id'        =>$item->id,
                            'is_report_output' => '0',
                            'created_at'    =>date('Y-m-d H:i:s')
                          ]; 
                        }
                        
                        $report_item_id = DB::table('report_items')->insertGetId($data);
                    }
                  }
              }
            
              $reports = DB::table('reports')->where(['candidate_id'=>$candidate_id])->first(); 

              if($reports)
              {
                $report_id = $reports->id;
                $report_status = $reports->status;
            
              

                // $candidate = [];
                $report_items = [];
                // $candidate =    Db::table('candidate_reinitiates as u')
                //                   ->select('u.id','u.business_id','u.first_name','u.last_name','u.name','u.email','u.phone','r.created_at')  
                //                   ->leftjoin('reports as r','r.candidate_id','=','u.id')
                //                   ->where(['u.id'=>$candidate_id]) 
                //                   ->first(); 
              
                $report_items = DB::table('report_items as ri')
                                ->select('ri.*','s.name as service_name','s.id as service_id' )  
                                ->join('services as s','s.id','=','ri.service_id')
                                ->where(['ri.report_id'=>$report_id]) 
                                ->orderBy('s.sort_number','asc')
                                ->get(); 

                    $status_list = DB::table('report_status_masters')->get(); 
                  // dd($jaf_items);
      
              }

              $user_service_check=DB::table('jaf_form_data as jf')
                            ->join('user_checks as u','u.checks','=','jf.service_id')
                            ->where(['jf.candidate_id'=>$candidate_id,'u.user_id'=>$user_id])
                            ->get();
              // dd($user_service_check);
              $services = DB::table('services')
              ->select('name','id')
              ->where(['status'=>'1'])
              ->whereNull('business_id')
              ->whereNotIn('type_name',['gstin'])
              ->orwhere('business_id',$business_id)
              ->get();
              // dd($services);
              // Auto Services
              
              $task_for_verify = DB::table('tasks as t')
                        ->select('t.*','ta.job_sla_item_id','ta.reassign_to','ta.user_id as user','ta.status as tastatus','ta.tat','ta.reassign_by','ta.created_at as created')
                        ->join('task_assignments as ta', 'ta.task_id', '=', 't.id')
                        ->where(['t.candidate_id'=>$candidate_id,'t.service_id'=>$verify_service_id,'t.number_of_verifications'=>$verify_number_id])
                        ->latest('ta.id')
                        ->first();
              
              $viewRender = view('admin.candidates.task-jaf-info',compact('candidate','jaf_item','is_insuff_arr','report','status_list','report_items','report_id','services','user_service_check','task_for_verify'))->render();
              return response()->json(array('success' => true, 'html'=>$viewRender));

    }
    // assigned verification task to  normal user
    public function userTaskAssigned(Request $request)
    {

        $form='';
        $task_id=$request->task_id;
        // dd($task_id);
        $vendor_task = DB::table('vendor_tasks')->where(['task_id'=>$task_id])->whereIn('status',['1','2'])->first();
        if ($vendor_task) {
            $ver_status=DB::table('vendor_verification_statuses')->where(['vendor_task_id'=>$vendor_task->id])->first();

            if($ver_status->remarks==NULL){
                $comments='N/A';
                // $status = $data->status=='done'?'Done' :'Unable to verify';
            } else{
                $comments=$ver_status->remarks;
                // $status = $data->status=='done'?'Done' :'Unable to verify';
                
            }
            if($ver_status->status==NULL){
                $status='N/A';
                // $status = $data->status=='done'?'Done' :'Unable to verify';
            } else{
                // $comments=$data->remarks;
                $status = $ver_status->status=='done'?'Done' :'Unable to verify';
                
            }
            //  if()
                $form.='<div class="form-group">
                <label for="label_name"> <strong>Status:</strong> <span id="comments">'.$status.'</span></label>
                </div>';
                $form.='<div class="form-group">
                    <label for="label_name"> <strong>Remarks:</strong> <span id="comments">'.$comments.'</span></label>
                    </div>';
            $upload_attach=DB::table('vendor_verification_data')->where(['vendor_task_id'=>$vendor_task->id])->get();
            if(count($upload_attach)>0)
            {
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
                    
                    $img='<img src="'.$file.'" alt="preview" style="height:100px;"/>';
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
    public function getUserList(Request $request)
    {
        $business_id = Auth::user()->business_id;
        // dd($business_id);
        $user_type = $request->user_type;
        if ($user_type=='user') {
            $candidates = DB::table('users')
                        ->select('id','first_name','middle_name','last_name','phone')
                        ->where(['business_id'=>$business_id,'user_type'=>$user_type,'is_deleted'=>'0'])
                        ->get();
        }
        if ($user_type=='vendor') {
            $candidates = DB::table('users')
                        ->select('id','first_name','middle_name','last_name','phone')
                        ->where(['parent_id'=>$business_id,'user_type'=>$user_type,'is_deleted'=>'0'])
                        ->get();
        }

        // $customer_sla = DB::table('customer_sla')
        //                 ->select('id','title')
        //                 ->where(['business_id'=>$business_id])
        //                 ->get();
        
        return response()->json([
            'success'   =>true,
            'data'      =>$candidates
        ]);
    }
    /**assignModal
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    
    }

    public function taskinsuffcleare(Request $request)
    {

        $vendor_insuffs = DB::table('vendor_insufficiencies')->where(['candidate_id'=>$request->candidate_id,'service_id'=>$request->service_id,'no_of_verification'=>$request->number_id])->get();
        //dd($vendor_insuffs);
        $viewRender = view('admin.task.raise-insuff',compact('vendor_insuffs'))->render();

        return response()->json(
            array(
              'success' => true, 
              'result' => '',
              'html'=>$viewRender
            )
          );
    }

    public function vendortaskraiseInsuff(Request $request)
    {
           
            //dd($request->get('candidate_id'));
             $rules= [
              'comments'  => 'required',
              ];
              $vendor_id = $request->vendor_id; 
              $candidate_id = $request->candidate_id; 
              $service_id   = $request->service_id; 
              $number_ver   = $request->number_id; 
              $vendorname = Auth::user()->name;
              //$item_id = base64_decode($request->jaf_id);
              // $item_id      = base64_decode($request->jaf_id); 
              $created_by = Auth::user()->id;
              $business_id = Auth::user()->id;
              // $is_updated= FALSE;
      
             
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
                      'vendor_id' => $vendor_id,
                      'business_id' => $vendor_id,
                      'candidate_id' => $candidate_id,
                      'service_id' => $service_id,
                      'no_of_verification' => $number_ver,
                      'status' => 'cleared',
                      'comments' => $request->comments,
                      'created_by' => $created_by,
                      'created_at'    =>date('Y-m-d H:i:s')
                  ];
               
                  $vendor_insuff_id = DB::table('vendor_insufficiencies')->insertGetId($vendor_insufficiencies);
                 
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
                  
                        foreach($files as $file){
                            $file_data = $file->getClientOriginalName();
                            $file_ext = $file->getClientOriginalExtension();
                            $tmp_data  = $candidate_id.'-'.date('mdYHis').'.'.$file_ext; 
                            $data = $file->move($filePath, $tmp_data);       
                            $attach_on_select[]=$tmp_data;
                            $path=public_path()."/uploads/raise-insuff/".$tmp_data; 
                            
                            $vendor_insuff_attachment = ([
                              'vendor_insuf_id' => $vendor_insuff_id,
                              'attachments' => $tmp_data,
                              'created_at'    =>date('Y-m-d H:i:s')
                            ]);
                            DB::table('vendor_insufficiency_attachments')->insert($vendor_insuff_attachment);
                        }
                    }

                    $comments = DB::table('vendor_insufficiencies')->where(['comments'=>$request->comments])->first();
                   
                    $tasksId = DB::table('vendor_tasks')->where(['service_id'=>$service_id,'candidate_id'=>$candidate_id,'no_of_verification'=>$number_ver])->first();
                    
                    $tasksId = $tasksId->assigned_to;
                    
                    $vendoruserId = DB::table('users')->where('id',$tasksId)->first();
                    $commentsdata = $comments->comments;
                    $servicedata = $comments->service_id;
                    $name = $vendoruserId->name;
                    $email = $vendoruserId->email;
                    $msg = "Kindly clear the insuff through your login credentials";
                    $sender = DB::table('users')->where(['id'=>$business_id])->first();
                    $data  = array('name'=>$name,'email'=>$email,'commentsdata'=>$commentsdata,'vendorname'=>$vendorname,'servicedata'=>$servicedata,'msg'=>$msg,'sender'=>$sender);
                    Mail::send(['html'=>'mails.vendor-insuff-clear'], $data, function($message) use($email,$name) {
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
// service base task
// SELECT u.name as username,u.email,ta.business_id,ta.user_id,ta.service_id,s.name,ta.candidate_id,ta.created_at,ta.job_sla_item_id,ta.task_id,ta.status,ta.reassign_to FROM task_assignments as ta JOIN users AS u ON u.id=ta.user_id JOIN services AS s ON ta.service_id = s.id WHERE u.user_type = 'user'  AND ta.user_id= $user_id OR ta.reassign_to=$user_id