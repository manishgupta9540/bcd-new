<?php

namespace App\Observers;

use App\Models\Admin\VoterIdCheck;

class VoterIdCheckObserver
{
    /**
     * Handle the voter id check "created" event.
     *
     * @param  \App\Models\Admin\VoterIdCheck  $voterIdCheck
     * @return void
     */
    public function created(VoterIdCheck $voterIdCheck)
    {
        //
    }

    /**
     * Handle the voter id check "updated" event.
     *
     * @param  \App\Models\Admin\VoterIdCheck  $voterIdCheck
     * @return void
     */
    public function updated(VoterIdCheck $voterIdCheck)
    {
        //
    }

    /**
     * Handle the voter id check "deleting" event.
     *
     * @param  \App\Models\Admin\VoterIdCheck  $voterIdCheck
     * @return void
     */
    public function deleting(VoterIdCheck $voterIdCheck)
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

        if($voterIdCheck)
        {
            $user_type= DB::table('voter_id_checks')->where('id',$voterIdCheck->id)->first();
            $input_data['new'] = [
                'parent_id' => $voterIdCheck->parent_id,'business_id' => $voterIdCheck->business,'candidate_id' => $voterIdCheck->candidate_id,'service_id'=> $voterIdCheck->service_id, 'source_type'=>$voterIdCheck->source_reference, 'api_client_id' =>$voterIdCheck->api_client_id,'voter_id_number'=>$voterIdCheck->voter_id_number,'relation_type' =>$voterIdCheck->relation_type,'relation_name'=>$voterIdCheck->relation_name,'full_name'  =>$voterIdCheck->full_name,'dob'=>$voterIdCheck->dob,'age'  =>$voterIdCheck->age,'gender' =>$voterIdCheck->gender,'house_no' =>$voterIdCheck->house_no,'area' =>$voterIdCheck->area,'state'     =>$voterIdCheck->state,'is_verified' =>$voterIdCheck->is_verified,'is_voter_id_exist' =>$voterIdCheck->is_voter_id_exist,'price'=>$voterIdCheck->price,'used_by' =>$voterIdCheck->used_by,'user_id' => $voterIdCheck->user_id,'created_by'=> $voterIdCheck->user_id?$voterIdCheck->user_id:'','created_at'=>$user_type->created_at
            ];

            $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->where('activity_title','VoterID Check')->latest()->first();

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
            $new_activity->parent_id =$user_type?$user_type->parent_id:'';
            $new_activity->business_id =$user_type->business_id?$user_type->business_id:'';
            $new_activity->activity_id = $voterIdCheck->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='deleted';
            $new_activity->activity_title ='VoterID Check';
            $new_activity->data = $user_data;
            $new_activity->created_by =  Auth::user()->id;
            $new_activity->save();
        }


    }

    /**
     * Handle the voter id check "deleted" event.
     *
     * @param  \App\Models\Admin\VoterIdCheck  $voterIdCheck
     * @return void
     */
    public function deleted(VoterIdCheck $voterIdCheck)
    {
        //
    }

    /**
     * Handle the voter id check "restored" event.
     *
     * @param  \App\Models\Admin\VoterIdCheck  $voterIdCheck
     * @return void
     */
    public function restored(VoterIdCheck $voterIdCheck)
    {
        //
    }

    /**
     * Handle the voter id check "force deleted" event.
     *
     * @param  \App\Models\Admin\VoterIdCheck  $voterIdCheck
     * @return void
     */
    public function forceDeleted(VoterIdCheck $voterIdCheck)
    {
        //
    }
}
