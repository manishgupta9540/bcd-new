<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskObserver
{
    /**
     * Handle the task "created" event.
     *
     * @param  \App\Models\Admin\Task  $task
     * @return void
     */
    public function created(Task $task)
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
        $user_type= DB::table('tasks')->where('id',$task->id)->first();

        $input_data['new'] = [
                    'name'=>$user_type->name,'parent_id'=> $user_type->parent_id,'business_id'=> $user_type->business_id,'description' => $user_type->description,'job_id'=> $task->job_id,'priority' => 'normal','candidate_id' =>$task->candidate_id,'assigned_to' =>$user_type->assigned_to,'assigned_by' => $user_type->assigned_by,'assigned_at' => $user_type->assigned_at,'start_date' => $user_type->start_date,'created_by'=>Auth::user()->id,'created_at'  => date('Y-m-d H:i:s'),'is_completed' => $user_type->is_completed,'status'=>$user_type->status,'started_at' => date('Y-m-d H:i:s')
          ];

        $user_data = json_encode($input_data);
        $new_activity = new ActivityLog();
        $new_activity->parent_id =   $user_type?$user_type->parent_id:'';
        $new_activity->business_id = $user_type?$user_type->business_id:'';
        $new_activity->activity_id = $task->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title ='Task';
        $new_activity->data = $user_data;
        $new_activity->created_by = Auth::user()->id;
        $new_activity->save();
    }

    /**
     * Handle the task "updated" event.
     *
     * @param  \App\Models\Admin\Task  $task
     * @return void
     */
    public function updated(Task $task)
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

       if($task)
       {
            $user_type= DB::table('tasks')->where('id',$task->id)->first();
                //    dd($user_type);
            $input_data['new'] = [
                        'name'=>$user_type->name,'parent_id'=> $user_type->parent_id,'business_id'=> $user_type->business_id,'description' => $user_type->description,'job_id'=> $task->job_id,'priority' => 'normal','candidate_id' =>$task->candidate_id,'assigned_to' =>$user_type->assigned_to,'assigned_by' => $user_type->assigned_by,'assigned_at' => $user_type->assigned_at,'start_date' => $user_type->start_date,'created_by'=>Auth::user()->id,'created_at'  => date('Y-m-d H:i:s'),'is_completed' => $user_type->is_completed,'status'=>$user_type->status,'started_at' => date('Y-m-d H:i:s')
                ];
                $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->latest()->first();
                //  dd($activity);
                $data=[];
                $data1=[];
                if ($activity!=null) {
                    $data= json_decode($activity->data,true);
                    
                    $data1= $data;
                    //    dd($data1);
                    $input_data['old'] = $data1;
        
                }

            $user_data = json_encode($input_data);
            $new_activity = new ActivityLog();
            $new_activity->parent_id =   $user_type?$user_type->parent_id:'';
            $new_activity->business_id = $user_type?$user_type->business_id:'';
            $new_activity->activity_id = $task->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='updated';
            $new_activity->activity_title ='Task';
            $new_activity->data = $user_data;
            $new_activity->created_by = Auth::user()->id;
            $new_activity->save();
        }
    }

    /**
     * Handle the task "deleting" event.
     *
     * @param  \App\Models\Admin\Task  $task
     * @return void
     */
    public function deleting(Task $task)
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url_host = "https://";   
        else  
            $url_host = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url_host.= $_SERVER['HTTP_HOST'];   

        // dd($url_host);
        // Append the requested resource location to the URL   
        $url= $_SERVER['REQUEST_URI'];

        if($task)
        {
            $user_type= DB::table('tasks')->where('id',$task->id)->first();
            //    dd($user_type);
            $input_data['new'] = [
                        'name'=>$user_type->name,'parent_id'=> $user_type->parent_id,'business_id'=> $user_type->business_id,'description' => $user_type->description,'job_id'=> $task->job_id,'priority' => 'normal','candidate_id' =>$task->candidate_id,'assigned_to' =>$user_type->assigned_to,'assigned_by' => $user_type->assigned_by,'assigned_at' => $user_type->assigned_at,'start_date' => $user_type->start_date,'created_by'=>Auth::user()->id,'created_at'  => date('Y-m-d H:i:s'),'is_completed' => $user_type->is_completed,'status'=>$user_type->status,'started_at' => date('Y-m-d H:i:s')
                ];
            $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->where('activity_title','Task')->latest()->first();
            //  dd($activity);
            $data=[];
            $data1=[];
            if ($activity!=null) {
                $data= json_decode($activity->data,true);
                
                $data1= $data;
                //    dd($data1);
                $input_data['old'] = $data1;
    
            }

            $user_data = json_encode($input_data);
            $new_activity = new ActivityLog();
            $new_activity->parent_id =   $user_type?$user_type->parent_id:'';
            $new_activity->business_id = $user_type?$user_type->business_id:'';
            $new_activity->activity_id = $task->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='deleted';
            $new_activity->activity_title ='Task';
            $new_activity->data = $user_data;
            $new_activity->created_by = Auth::user()->id;
            $new_activity->save();
        }
    }

    /**
     * Handle the task "deleted" event.
     *
     * @param  \App\Models\Admin\Task  $task
     * @return void
     */
    public function deleted(Task $task)
    {
        //
    }

    /**
     * Handle the task "restored" event.
     *
     * @param  \App\Models\Admin\Task  $task
     * @return void
     */
    public function restored(Task $task)
    {
        //
    }

    /**
     * Handle the task "force deleted" event.
     *
     * @param  \App\Models\Admin\Task  $task
     * @return void
     */
    public function forceDeleted(Task $task)
    {
        //
    }
}
