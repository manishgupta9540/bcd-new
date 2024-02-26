<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\JafFormData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JafFormdataObserver
{
    /**
     * Handle the jaf form data "created" event.
     *
     * @param  \App\Models\Admin\JafFormData  $jafFormData
     * @return void
     */
    public function created(JafFormData $jafFormData)
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
        $user_type= DB::table('jaf_form_data')->where('id',$jafFormData->id)->first();

        $input_data = [];

        $input_data['new'] = [
           'business_id'=>$jafFormData->business_id, 'job_id'=>$jafFormData->job_id,'job_item_id'=>$jafFormData->job_item_id,'candidate_id' =>$jafFormData->candidate_id,'sla_id'=>$jafFormData->sla_id,'service_id' => $jafFormData->service_id,'check_item_number' => $jafFormData->check_item_number,'is_filled'=>'0','created_by' =>$user_type->created_by?$user_type->created_by:'','created_at'=>date('Y-m-d H:i:s')
          ];

        $user_data = json_encode($input_data);
        $new_activity = new ActivityLog();
        $new_activity->parent_id =  Auth::user()->business_id;
        $new_activity->business_id = $user_type?$user_type->business_id:'';
        $new_activity->activity_id = $jafFormData->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title ='Jaf Form Data';
        $new_activity->data = $user_data;
        $new_activity->created_by =$user_type->created_by?$user_type->created_by:'';
        $new_activity->save();
    }

    /**
     * Handle the jaf form data "updated" event.
     *
     * @param  \App\Models\Admin\JafFormData  $jafFormData
     * @return void
     */
    public function updated(JafFormData $jafFormData)
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

        if($jafFormData)
        {
            $user_type= DB::table('jaf_form_data')->where('id',$jafFormData->id)->first();

            $input_data['new'] = [
            'business_id'=>$user_type->business_id, 'job_id'=>$user_type->job_id,'job_item_id'=>$user_type->job_item_id,'candidate_id' =>$user_type->candidate_id,'sla_id'=>$user_type->sla_id,'service_id' => $user_type->service_id,'check_item_number' => $user_type->check_item_number,'is_filled'=>'1','form_data'=>$jafFormData->form_data,'form_data_all'=>$jafFormData->form_data_all,'is_insufficiency'=>$jafFormData->is_insufficiency,'insufficiency_notes'=>$jafFormData->insufficiency_notes,'address_type'=>$jafFormData->address_type,'updated_by' =>$user_type->updated_by?$user_type->updated_by:'','updated_at'=>date('Y-m-d H:i:s')
            ];
            $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->latest()->first();
            // dd($activity);
            $data=[];
            $data1=[];
            if ($activity!=null) {
                $data= json_decode($activity->data,true);
                if($data!=null && array_key_exists('new',$data))
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
            $new_activity->parent_id =  $user!=NULL?$user->parent_id:'';
            $new_activity->business_id = $user_type?$user_type->business_id:'';
            $new_activity->activity_id = $jafFormData->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='updated';
            $new_activity->activity_title ='Jaf Form Data';
            $new_activity->data = $user_data;
            $new_activity->created_by =$user_type->created_by?$user_type->created_by:'';
            $new_activity->save();
        }
        
    }

    /**
     * Handle the jaf form data "deleting" event.
     *
     * @param  \App\Models\Admin\JafFormData  $jafFormData
     * @return void
     */
    public function deleting(JafFormData $jafFormData)
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

        if($jafFormData)
        {
            $user_type= DB::table('jaf_form_data')->where('id',$jafFormData->id)->first();

            $input_data['new'] = [
            'business_id'=>$user_type->business_id, 'job_id'=>$user_type->job_id,'job_item_id'=>$user_type->job_item_id,'candidate_id' =>$user_type->candidate_id,'sla_id'=>$user_type->sla_id,'service_id' => $user_type->service_id,'check_item_number' => $user_type->check_item_number,'is_filled'=>'1','form_data'=>$jafFormData->form_data,'form_data_all'=>$jafFormData->form_data_all,'is_insufficiency'=>$jafFormData->is_insufficiency,'insufficiency_notes'=>$jafFormData->insufficiency_notes,'address_type'=>$jafFormData->address_type,'updated_by' =>$user_type->updated_by?$user_type->updated_by:'','updated_at'=>date('Y-m-d H:i:s')
            ];
            $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->where('activity_title','Jaf Form Data')->latest()->first();
            // dd($activity);
            $data=[];
            $data1=[];
            if ($activity!=null) {
                $data= json_decode($activity->data,true);
                if($data!=null && array_key_exists('new',$data))
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
            $new_activity->parent_id =  $user!=NULL?$user->parent_id:'';
            $new_activity->business_id = $user_type?$user_type->business_id:'';
            $new_activity->activity_id = $jafFormData->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='deleted';
            $new_activity->activity_title ='Jaf Form Data';
            $new_activity->data = $user_data;
            $new_activity->created_by =Auth::user()->id;
            $new_activity->save();
        }
    }

    /**
     * Handle the jaf form data "deleted" event.
     *
     * @param  \App\Models\Admin\JafFormData  $jafFormData
     * @return void
     */
    public function deleted(JafFormData $jafFormData)
    {
        //
    }

    /**
     * Handle the jaf form data "restored" event.
     *
     * @param  \App\Models\Admin\JafFormData  $jafFormData
     * @return void
     */
    public function restored(JafFormData $jafFormData)
    {
        //
    }

    /**
     * Handle the jaf form data "force deleted" event.
     *
     * @param  \App\Models\Admin\JafFormData  $jafFormData
     * @return void
     */
    public function forceDeleted(JafFormData $jafFormData)
    {
        //
    }
}
