<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\Candidate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CandidateObserver
{
    /**
     * Handle the candidate "created" event.
     *
     * @param  \App\Models\Admin\Candidate  $candidate
     * @return void
     */
    public function created(Candidate $candidate)
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
        $user_type= DB::table('candidates')->where('id',$candidate->id)->first();

        $input_data['new'] = [
                    'candidate_id'=> $user_type->candidate_id,'business_id'=> $user_type->business_id,'job_id' =>$candidate->job_id,'name' =>$user_type->name,'first_name' => $candidate->first_name,'middle_name' => $candidate->middle_name,'last_name' => $candidate->last_name,'email' => $candidate->email,'phone' => $candidate->phone,'created_by' =>$user_type->created_by?$user_type->created_by:'','created_at'=>date('Y-m-d H:i:s')
          ];

           $user=NULL;
            if ($user_type) {
                $user= DB::table('users')->where('id',$user_type->business_id)->first();
            }
        $user_data = json_encode($input_data);
        $new_activity = new ActivityLog();
        $new_activity->parent_id =$user!=NULL?$user->parent_id:'';
        $new_activity->business_id = $user_type?$user_type->business_id:'';
        $new_activity->activity_id = $candidate->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title ='Candidate';
        $new_activity->data = $user_data;
        $new_activity->created_by = $user_type->created_by?$user_type->created_by:'';
        $new_activity->save();
    }

    /**
     * Handle the candidate "updated" event.
     *
     * @param  \App\Models\Admin\Candidate  $candidate
     * @return void
     */
    public function updated(Candidate $candidate)
    {
        //
    }

    /**
     * Handle the candidate "deleting" event.
     *
     * @param  \App\Models\Admin\Candidate  $candidate
     * @return void
     */
    public function deleting(Candidate $candidate)
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

        if($candidate)
        {
            $user_type= DB::table('candidates')->where('id',$candidate->id)->first();
            $input_data['new'] = [
                'candidate_id'=> $user_type->candidate_id,'business_id'=> $user_type->business_id,'job_id' =>$candidate->job_id,'name' =>$user_type->name,'first_name' => $candidate->first_name,'middle_name' => $candidate->middle_name,'last_name' => $candidate->last_name,'email' => $candidate->email,'phone' => $candidate->phone,'created_by' =>$user_type->created_by?$user_type->created_by:'','created_at'=>date('Y-m-d H:i:s')
            ];

            $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->where('activity_title','Candidate')->latest()->first();
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

            $user=NULL;
            if ($user_type) {
                $user= DB::table('users')->where('id',$user_type->business_id)->first();
            }

            $user_data = json_encode($input_data);
            $new_activity = new ActivityLog();
            $new_activity->parent_id =$user!=NULL?$user->parent_id:'';
            $new_activity->business_id = $user_type?$user_type->business_id:'';
            $new_activity->activity_id = $candidate->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='deleted';
            $new_activity->activity_title ='Candidate';
            $new_activity->data = $user_data;
            $new_activity->created_by = Auth::user()->id;
            $new_activity->save();
        }
    }

    /**
     * Handle the candidate "deleted" event.
     *
     * @param  \App\Models\Admin\Candidate  $candidate
     * @return void
     */
    public function deleted(Candidate $candidate)
    {
        //
    }

    /**
     * Handle the candidate "restored" event.
     *
     * @param  \App\Models\Admin\Candidate  $candidate
     * @return void
     */
    public function restored(Candidate $candidate)
    {
        //
    }

    /**
     * Handle the candidate "force deleted" event.
     *
     * @param  \App\Models\Admin\Candidate  $candidate
     * @return void
     */
    public function forceDeleted(Candidate $candidate)
    {
        //
    }
}
