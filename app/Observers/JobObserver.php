<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\Job;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobObserver
{
    /**
     * Handle the job "created" event.
     *
     * @param  \App\Models\Admin\Job  $job
     * @return void
     */
    public function created(Job $job)
    {
        // dd($customerSla);
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url_host = "https://";   
        else  
            $url_host = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url_host.= $_SERVER['HTTP_HOST'];   

        // dd($url_host);
        // Append the requested resource location to the URL   
        $url= $_SERVER['REQUEST_URI']; 
        $user_type= DB::table('jobs')->where('id',$job->id)->first();

        $input_data['new'] = [
            'business_id'=> $user_type->business_id,'parent_id' => $user_type->parent_id, 'title'=> $user_type->title,'total_candidates'=>$user_type->total_candidates,'send_jaf_link_required' => $user_type->send_jaf_link_required,'status'  => $user_type->status,'created_by' => $user_type->created_by?$user_type->created_by:'','created_at' => date('Y-m-d H:i:s')
        ];

        $user_data = json_encode($input_data);
        $new_activity = new ActivityLog();
        $new_activity->parent_id =  $user_type?$user_type->parent_id:'';
        $new_activity->business_id = $user_type?$user_type->business_id:'';
        $new_activity->activity_id = $job->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title ='Jobs';
        $new_activity->data = $user_data;
        $new_activity->created_by = $user_type->created_by?$user_type->created_by:'';
        $new_activity->save();
    }

    /**
     * Handle the job "updated" event.
     *
     * @param  \App\Models\Admin\Job  $job
     * @return void
     */
    public function updated(Job $job)
    {
          // dd($customerSla);
          if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
          $url_host = "https://";   
          else  
              $url_host = "http://";   
          // Append the host(domain name, ip) to the URL.   
          $url_host.= $_SERVER['HTTP_HOST'];   
  
          // dd($url_host);
          // Append the requested resource location to the URL   
          $url= $_SERVER['REQUEST_URI']; 

          if($job)
          {
            $user_type= DB::table('jobs')->where('id',$job->id)->first();
    
            $input_data['new'] = [
                'business_id'=> $user_type->business_id,'parent_id' => $user_type->parent_id, 'title'=> $user_type->title,'total_candidates'=>$user_type->total_candidates,'send_jaf_link_required' => $user_type->send_jaf_link_required,'status'  => $user_type->status,'created_by' => $user_type->created_by?$user_type->created_by:'','created_at' => date('Y-m-d H:i:s')
                ];
    
                $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->latest()->first();
                // dd($activity);
                $data=[];
                $data1=[];
                if ($activity!=null) {
                    $data= json_decode($activity->data,true);
                    if(array_key_exists('new',$data))
                    {
                        $data1= $data['new'];
                        //dd($data1);
                        $input_data['old'] = $data1;
                    }
                }
                
            $user_data = json_encode($input_data);
            $new_activity = new ActivityLog();
            $new_activity->parent_id =  $user_type?$user_type->parent_id:'';
            $new_activity->business_id = $user_type?$user_type->business_id:'';
            $new_activity->activity_id = $job->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='updated';
            $new_activity->activity_title ='Jobs';
            $new_activity->data = $user_data;
            $new_activity->created_by =  $user_type->created_by?$user_type->created_by:'';
            $new_activity->save();
          }
    }

    /**
     * Handle the job "deleting" event.
     *
     * @param  \App\Models\Admin\Job  $job
     * @return void
     */
    public function deleting(Job $job)
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
    
        // echo $url;  

        if($job)
        {
            $user_type= DB::table('jobs')->where('id',$job->id)->first();
    
            $input_data['new'] = [
                'business_id'=> $user_type->business_id,'parent_id' => $user_type->parent_id, 'title'=> $user_type->title,'total_candidates'=>$user_type->total_candidates,'send_jaf_link_required' => $user_type->send_jaf_link_required,'status'  => $user_type->status,'created_by' => $user_type->created_by?$user_type->created_by:'','created_at' => date('Y-m-d H:i:s')
                ];
    
                $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->where('activity_title','Jobs')->latest()->first();
                // dd($activity);
                $data=[];
                $data1=[];
                if ($activity!=null) {
                    $data= json_decode($activity->data,true);
                    if(array_key_exists('new',$data))
                    {
                        $data1= $data['new'];
                        //dd($data1);
                        $input_data['old'] = $data1;
                    }
                }

                $user_data = json_encode($input_data);
                $new_activity = new ActivityLog();
                $new_activity->parent_id =  $user_type?$user_type->parent_id:'';
                $new_activity->business_id = $user_type?$user_type->business_id:'';
                $new_activity->activity_id = $job->id;
                $new_activity->url_host = $url_host;
                $new_activity->url_request = $url;
                $new_activity->activity ='deleted';
                $new_activity->activity_title ='Jobs';
                $new_activity->data = $user_data;
                $new_activity->created_by =  Auth::user()->id;
                $new_activity->save();
        }
    }

    /**
     * Handle the job "deleted" event.
     *
     * @param  \App\Models\Admin\Job  $job
     * @return void
     */
    public function deleted(Job $job)
    {
        //
    }

    /**
     * Handle the job "restored" event.
     *
     * @param  \App\Models\Admin\Job  $job
     * @return void
     */
    public function restored(Job $job)
    {
        //
    }

    /**
     * Handle the job "force deleted" event.
     *
     * @param  \App\Models\Admin\Job  $job
     * @return void
     */
    public function forceDeleted(Job $job)
    {
        //
    }
}
