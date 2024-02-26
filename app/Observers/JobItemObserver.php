<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\JobItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobItemObserver
{
    /**
     * Handle the job item "created" event.
     *
     * @param  \App\Models\Admin\JobItem  $jobItem
     * @return void
     */
    public function created(JobItem $jobItem)
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
        $user_type= DB::table('job_items')->where('id',$jobItem->id)->first();

        $input_data['new'] = [
           'business_id'=>$jobItem->business_id, 'job_id'=>$jobItem->job_id,'candidate_id' =>$jobItem->candidate_id,'sla_id'=>$jobItem->sla_id,'sla_type' => $jobItem->sla_type,'days_type' => $jobItem->days_type,'tat_type'=> $jobItem->tat_type,'incentive'=> $jobItem->incentive,'penalty'=> $jobItem->penalty,'jaf_status' =>'pending','created_by' =>$user_type->created_by?$user_type->created_by:'','created_at'=>date('Y-m-d H:i:s')
          ];
          $user=NULL;
          if ($user_type) {
             $user= DB::table('users')->where('id',$user_type->business_id)->first();
          }
        $user_data = json_encode($input_data);
        $new_activity = new ActivityLog();
        $new_activity->parent_id =  $user!=NULL?$user->parent_id:'';
        $new_activity->business_id = $user_type?$user_type->business_id:'';
        $new_activity->activity_id = $jobItem->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title ='Job Item';
        $new_activity->data = $user_data;
        $new_activity->created_by = $user_type->created_by?$user_type->created_by:'';
        $new_activity->save();
    }

    /**
     * Handle the job item "updated" event.
     *
     * @param  \App\Models\Admin\JobItem  $jobItem
     * @return void
    */
    public function updated(JobItem $jobItem)
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

        if($jobItem)
        {
            $user_type= DB::table('job_items')->where('id',$jobItem->id)->first();

            $input_data['new'] = [
            'business_id'=>$jobItem->business_id, 'job_id'=>$jobItem->job_id,'candidate_id' =>$jobItem->candidate_id,'sla_id'=>$jobItem->sla_id,'sla_type' => $jobItem->sla_type,'days_type' => $jobItem->days_type,'tat_type'=> $jobItem->tat_type,'incentive'=> $jobItem->incentive,'penalty'=> $jobItem->penalty,'jaf_status' =>'pending','created_by' =>$user_type->created_by?$user_type->created_by:'','created_at'=>date('Y-m-d H:i:s')
            ];

            $user=NULL;
            if ($user_type) {
                $user= DB::table('users')->where('id',$user_type->business_id)->first();
            }
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
            $new_activity->parent_id = $user!=NULL?$user->parent_id:'';
            $new_activity->business_id = $user_type?$user_type->business_id:'';
            $new_activity->activity_id = $jobItem->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='updated';
            $new_activity->activity_title ='Job Item';
            $new_activity->data = $user_data;
            $new_activity->created_by = $user_type->created_by?$user_type->created_by:'';
            $new_activity->save();
        }
        
    }

    /**
     * Handle the job item "deleting" event.
     *
     * @param  \App\Models\Admin\JobItem  $jobItem
     * @return void
     */
    public function deleting(JobItem $jobItem)
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

        if($jobItem)
        {
            $user_type= DB::table('job_items')->where('id',$jobItem->id)->first();

            $input_data['new'] = [
            'business_id'=>$jobItem->business_id, 'job_id'=>$jobItem->job_id,'candidate_id' =>$jobItem->candidate_id,'sla_id'=>$jobItem->sla_id,'sla_type' => $jobItem->sla_type,'days_type' => $jobItem->days_type,'tat_type'=> $jobItem->tat_type,'incentive'=> $jobItem->incentive,'penalty'=> $jobItem->penalty,'jaf_status' =>'pending','created_by' =>$user_type->created_by?$user_type->created_by:'','created_at'=>date('Y-m-d H:i:s')
            ];

            $user=NULL;
            if ($user_type) {
                $user= DB::table('users')->where('id',$user_type->business_id)->first();
            }
            $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->where('activity_title','Job Item')->latest()->first();
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
            $new_activity->parent_id = $user!=NULL?$user->parent_id:'';
            $new_activity->business_id = $user_type?$user_type->business_id:'';
            $new_activity->activity_id = $jobItem->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='deleted';
            $new_activity->activity_title ='Job Item';
            $new_activity->data = $user_data;
            $new_activity->created_by = Auth::user()->id;
            $new_activity->save();
        }
    }

    /**
     * Handle the job item "deleted" event.
     *
     * @param  \App\Models\Admin\JobItem  $jobItem
     * @return void
     */
    public function deleted(JobItem $jobItem)
    {
        //
    }

    /**
     * Handle the job item "restored" event.
     *
     * @param  \App\Models\Admin\JobItem  $jobItem
     * @return void
     */
    public function restored(JobItem $jobItem)
    {
        //
    }

    /**
     * Handle the job item "force deleted" event.
     *
     * @param  \App\Models\Admin\JobItem  $jobItem
     * @return void
     */
    public function forceDeleted(JobItem $jobItem)
    {
        //
    }
}
