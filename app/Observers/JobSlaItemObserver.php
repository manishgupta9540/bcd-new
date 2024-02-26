<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\JobSlaItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobSlaItemObserver
{
    /**
     * Handle the job sla item "created" event.
     *
     * @param  \App\Models\Admin\JobSlaItem  $jobSlaItem
     * @return void
     */
    public function created(JobSlaItem $jobSlaItem)
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
         $user_type= DB::table('job_sla_items')->where('id',$jobSlaItem->id)->first();
        //  dd($user_type);
         $input_data['new'] = [
            'business_id'=>$jobSlaItem->business_id, 'job_id'=>$jobSlaItem->job_id,'candidate_id' =>$jobSlaItem->candidate_id,'sla_id'=>$jobSlaItem->sla_id,'service_id'  => $jobSlaItem->service_id,'jaf_send_to' => $user_type->jaf_send_to,'number_of_verifications'=>$user_type->number_of_verifications,'tat'=>$jobSlaItem->tat,'incentive_tat'=>$jobSlaItem->incentive_tat,'sla_item_id' => $jobSlaItem->sla_item_id,'created_by' =>$user_type->created_by?$user_type->created_by:'','created_at'=>date('Y-m-d H:i:s')
           ];
           $user=NULL;
            if ($user_type) {
                $user= DB::table('users')->where('id',$user_type->business_id)->first();
            }
         $user_data = json_encode($input_data);
         $new_activity = new ActivityLog();
         $new_activity->parent_id =  $user!=NULL?$user->parent_id:'';
         $new_activity->business_id = $user_type?$user_type->business_id:'';
         $new_activity->activity_id = $jobSlaItem->id;
         $new_activity->url_host = $url_host;
         $new_activity->url_request = $url;
         $new_activity->activity ='created';
         $new_activity->activity_title ='Job Sla Item';
         $new_activity->data = $user_data;
         $new_activity->created_by = $user_type->created_by?$user_type->created_by:'';
         $new_activity->save();
    }

    /**
     * Handle the job sla item "updated" event.
     *
     * @param  \App\Models\Admin\JobSlaItem  $jobSlaItem
     * @return void
     */
    public function updated(JobSlaItem $jobSlaItem)
    {
        //
    }

    /**
     * Handle the job sla item "deleting" event.
     *
     * @param  \App\Models\Admin\JobSlaItem  $jobSlaItem
     * @return void
     */
    public function deleting(JobSlaItem $jobSlaItem)
    {
        // dd($jobSlaItem);
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url_host = "https://";   
        else  
            $url_host = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url_host.= $_SERVER['HTTP_HOST'];   

        // dd($url_host);
        // Append the requested resource location to the URL   
        $url= $_SERVER['REQUEST_URI'];    
        // $user_type= DB::table('job_sla_items')->where('id',$jobSlaItem->id)->first();
        if($jobSlaItem)
        {
            $user_type= DB::table('job_sla_items')->where('id',$jobSlaItem->id)->first();
            $input_data['new'] = [
                'business_id'=>$jobSlaItem->business_id, 'job_id'=>$jobSlaItem->job_id,'candidate_id' =>$jobSlaItem->candidate_id,'sla_id'=>$jobSlaItem->sla_id,'service_id'  => $jobSlaItem->service_id,'jaf_send_to' => $jobSlaItem->jaf_send_to,'number_of_verifications'=>$jobSlaItem->number_of_verifications,
                'tat'=>$jobSlaItem->tat,'incentive_tat'=>$jobSlaItem->incentive_tat,'sla_item_id' => $jobSlaItem->sla_item_id,'created_by' =>$jobSlaItem->created_by?$jobSlaItem->created_by:'','created_at'=>date('Y-m-d H:i:s')
            ];
            $user=NULL;
           
            $user= DB::table('users')->where('id',$jobSlaItem->business_id)->first();
            

            $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->where('activity_title','Job Sla Item')->latest()->first();
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
            $new_activity->parent_id =  $user!=NULL?$user->parent_id:'';
            $new_activity->business_id = $jobSlaItem?$jobSlaItem->business_id:'';
            $new_activity->activity_id = $jobSlaItem->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='deleted';
            $new_activity->activity_title ='Job Sla Item';
            $new_activity->data = $user_data;
            $new_activity->created_by = $jobSlaItem->created_by?$jobSlaItem->created_by:'';
            $new_activity->save();
        }
    }

    /**
     * Handle the job sla item "deleted" event.
     *
     * @param  \App\Models\Admin\JobSlaItem  $jobSlaItem
     * @return void
     */

     public function deleted(JobSlaItem $jobSlaItem)
     {
        //
     }

    /**
     * Handle the job sla item "restored" event.
     *
     * @param  \App\Models\Admin\JobSlaItem  $jobSlaItem
     * @return void
     */
    public function restored(JobSlaItem $jobSlaItem)
    {
        //
    }

    /**
     * Handle the job sla item "force deleted" event.
     *
     * @param  \App\Models\Admin\JobSlaItem  $jobSlaItem
     * @return void
     */
    public function forceDeleted(JobSlaItem $jobSlaItem)
    {
        //
    }
}
