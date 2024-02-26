<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\TaskAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskAssignmentObserver
{
    /**
     * Handle the task assignment "created" event.
     *
     * @param  \App\Models\Admin\TaskAssignment  $taskAssignment
     * @return void
     */
    public function created(TaskAssignment $taskAssignment)
    {
          // dd($jobItem);
          if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
          $url_host = "https://";   
          else  
              $url_host = "http://";   
          // Append the host(domain name, ip) to the URL.   
          $url_host.= $_SERVER['HTTP_HOST'];   
  
          // dd($url_host);
          // Append the requested resource location to the URL   
          $url= $_SERVER['REQUEST_URI']; 
          $user_type= DB::table('task_assignments')->where('id',$taskAssignment->id)->first();
  
          $input_data['new'] = [
                      'parent_id'=> $user_type->parent_id,'business_id'=> $user_type->business_id,'candidate_id' =>$taskAssignment->candidate_id,'job_sla_item_id' =>$user_type->job_sla_item_id,'task_id' => $user_type->task_id,'service_id'=>$user_type->service_id,'number_of_verifications'=>$user_type->number_of_verifications,'status'=>$user_type->status,'created_by' =>Auth::user()->id,'created_at'=>date('Y-m-d H:i:s')
            ];
  
          $user_data = json_encode($input_data);
          $new_activity = new ActivityLog();
          $new_activity->parent_id = $user_type?$user_type->parent_id:'';
          $new_activity->business_id = $user_type?$user_type->business_id:'';
          $new_activity->activity_id = $taskAssignment->id;
          $new_activity->url_host = $url_host;
          $new_activity->url_request = $url;
          $new_activity->activity ='created';
          $new_activity->activity_title ='Task Assignment';
          $new_activity->data = $user_data;
          $new_activity->created_by = Auth::user()->id;
          $new_activity->save();
    }

    /**
     * Handle the task assignment "updated" event.
     *
     * @param  \App\Models\Admin\TaskAssignment  $taskAssignment
     * @return void
     */
    public function updated(TaskAssignment $taskAssignment)
    {
        // dd($jobItem);
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url_host = "https://";   
        else  
            $url_host = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url_host.= $_SERVER['HTTP_HOST'];   
 
        // dd($url_host);
        // Append the requested resource location to the URL   
        $url= $_SERVER['REQUEST_URI'];
        
        if($taskAssignment)
        {
            $user_type= DB::table('task_assignments')->where('id',$taskAssignment->id)->first();
            // dd($user_type);
            $input_data['new'] = [
                'parent_id'=> $user_type->parent_id,
                'business_id'=> $user_type->business_id,
                'candidate_id' =>$taskAssignment->candidate_id,
                'job_sla_item_id' =>$user_type->job_sla_item_id,
                'task_id' => $user_type->task_id,
                'status'=>$user_type->status,
                'user_id'=>$user_type->user_id,
                'service_id'=>$user_type->service_id,
                'number_of_verifications'=>$user_type->number_of_verifications,
                'reassign_to'=>$user_type->reassign_to,
                'reassign_by'=>$user_type->reassign_by,
                'updated_by' =>Auth::user()->id,
                'updated_at'=>date('Y-m-d H:i:s')
            ];
            $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->latest()->first();
            // dd($activity);
            $data=[];
            $data1=[];
            if ($activity!=null) {
                $data= json_decode($activity->data,true);
                if(array_key_exists('new',$data))
                {
                    $data1= $data;
                    //dd($data1);
                    $input_data['old'] = $data1;
                }
    
            }
    
            $user_data = json_encode($input_data);
            $new_activity = new ActivityLog();
            $new_activity->parent_id =   $user_type?$user_type->parent_id:'';
            $new_activity->business_id = $user_type?$user_type->business_id:'';
            $new_activity->activity_id = $taskAssignment->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='updated';
            $new_activity->activity_title ='Task Assignment';
            $new_activity->data = $user_data;
            $new_activity->created_by = Auth::user()->id;
            $new_activity->save();
        }
    }

    /**
     * Handle the task assignment "deleting" event.
     *
     * @param  \App\Models\Admin\TaskAssignment  $taskAssignment
     * @return void
     */
    public function deleting(TaskAssignment $taskAssignment)
    {
        // dd($jobItem);
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url_host = "https://";   
        else  
            $url_host = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url_host.= $_SERVER['HTTP_HOST'];   
 
        // dd($url_host);
        // Append the requested resource location to the URL   
        $url= $_SERVER['REQUEST_URI'];

        if($taskAssignment)
        {
            $user_type= DB::table('task_assignments')->where('id',$taskAssignment->id)->first();
            // dd($user_type);
            $input_data['new'] = [
                'parent_id'=> $user_type->parent_id,
                'business_id'=> $user_type->business_id,
                'candidate_id' =>$taskAssignment->candidate_id,
                'job_sla_item_id' =>$user_type->job_sla_item_id,
                'task_id' => $user_type->task_id,
                'status'=>$user_type->status,
                'user_id'=>$user_type->user_id,
                'service_id'=>$user_type->service_id,
                'number_of_verifications'=>$user_type->number_of_verifications,
                'reassign_to'=>$user_type->reassign_to,
                'reassign_by'=>$user_type->reassign_by,
                'updated_by' =>Auth::user()->id,
                'updated_at'=>date('Y-m-d H:i:s')
            ];
            $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->where('activity_title','Task Assignment')->latest()->first();
            // dd($activity);
            $data=[];
            $data1=[];
            if ($activity!=null) {
                $data= json_decode($activity->data,true);
                if(array_key_exists('new',$data))
                {
                    $data1= $data;
                    //dd($data1);
                    $input_data['old'] = $data1;
                }
    
            }
    
            $user_data = json_encode($input_data);
            $new_activity = new ActivityLog();
            $new_activity->parent_id =   $user_type?$user_type->parent_id:'';
            $new_activity->business_id = $user_type?$user_type->business_id:'';
            $new_activity->activity_id = $taskAssignment->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='deleted';
            $new_activity->activity_title ='Task Assignment';
            $new_activity->data = $user_data;
            $new_activity->created_by = Auth::user()->id;
            $new_activity->save();
        }

        
    }

    /**
     * Handle the task assignment "deleted" event.
     *
     * @param  \App\Models\Admin\TaskAssignment  $taskAssignment
     * @return void
     */
    public function deleted(TaskAssignment $taskAssignment)
    {
        //
    }

    /**
     * Handle the task assignment "restored" event.
     *
     * @param  \App\Models\Admin\TaskAssignment  $taskAssignment
     * @return void
     */
    public function restored(TaskAssignment $taskAssignment)
    {
        //
    }

    /**
     * Handle the task assignment "force deleted" event.
     *
     * @param  \App\Models\Admin\TaskAssignment  $taskAssignment
     * @return void
     */
    public function forceDeleted(TaskAssignment $taskAssignment)
    {
        //
    }
}
