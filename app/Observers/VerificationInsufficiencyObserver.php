<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\VerificationInsufficiency;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VerificationInsufficiencyObserver
{
    /**
     * Handle the verfication insufficiency "created" event.
     *
     * @param  \App\VerificationInsufficiency  $verficationInsufficiency
     * @return void
     */
    public function created(VerificationInsufficiency $verificationInsufficiency)
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

        $user_type= DB::table('verification_insufficiency')->where('id',$verificationInsufficiency->id)->first();
        $input_data['new'] = [
            'parent_id'   => $verificationInsufficiency->parent_id,'business_id' => $verificationInsufficiency->business,'coc_id' => $verificationInsufficiency->coc_id,'candidate_id' => $verificationInsufficiency->candidate_id,'jaf_form_data_id'  => $verificationInsufficiency->jaf_form_data_id,'service_id'  => $verificationInsufficiency->service_id,'item_number' => $verificationInsufficiency->item_number,'activity_type'=> 'jaf-save','status' =>'raised','notes' => $verificationInsufficiency->notes,'created_by'=>$user_type->created_by,'created_at'=>$user_type->created_at
        ];

        $user_data = json_encode($input_data);
        // dd($user_data);
        $new_activity = new ActivityLog();
        $new_activity->parent_id =$user_type?$user_type->parent_id:'';
        $new_activity->business_id =$user_type->business_id?$user_type->business_id:'';
        $new_activity->activity_id = $verificationInsufficiency->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title ='Verification Insufficiency';
        $new_activity->data = $user_data;
        $new_activity->created_by = Auth::user()->id;
        $new_activity->save();
    }

    /**
     * Handle the verfication insufficiency "updated" event.
     *
     * @param  \App\VerificationInsufficiency  $verficationInsufficiency
     * @return void
     */
    public function updated(VerificationInsufficiency $verificationInsufficiency)
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
        if($verificationInsufficiency)
        {
            $user_type= DB::table('verification_insufficiency')->where('id',$verificationInsufficiency->id)->first();
            $input_data['new'] = [
                'parent_id'   => $verificationInsufficiency->parent_id,'business_id' => $verificationInsufficiency->business,'coc_id' => $verificationInsufficiency->coc_id,'candidate_id' => $verificationInsufficiency->candidate_id,'jaf_form_data_id'  => $verificationInsufficiency->jaf_form_data_id,'service_id'  => $verificationInsufficiency->service_id,'item_number' => $verificationInsufficiency->item_number,'activity_type'=> 'jaf-save','status' =>'raised','notes' => $verificationInsufficiency->notes,'updated_by'=>$verificationInsufficiency->updated_by,'updated_at'=>$verificationInsufficiency->updated_by
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
            // dd($user_data);
            $new_activity = new ActivityLog();
            $new_activity->parent_id =$user_type?$user_type->parent_id:'';
            $new_activity->business_id =$user_type->business_id?$user_type->business_id:'';
            $new_activity->activity_id = $verificationInsufficiency->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='updated';
            $new_activity->activity_title ='Verification Insufficiency';
            $new_activity->data = $user_data;
            $new_activity->created_by = Auth::user()->id;
            $new_activity->save();

        }
    }

    /**
     * Handle the verfication insufficiency "deleting" event.
     *
     * @param  \App\VerificationInsufficiency  $verficationInsufficiency
     * @return void
     */
    public function deleting(VerificationInsufficiency $verificationInsufficiency)
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
        if($verificationInsufficiency)
        {
            $user_type= DB::table('verification_insufficiency')->where('id',$verificationInsufficiency->id)->first();
            $input_data['new'] = [
                'parent_id'   => $verificationInsufficiency->parent_id,'business_id' => $verificationInsufficiency->business,'coc_id' => $verificationInsufficiency->coc_id,'candidate_id' => $verificationInsufficiency->candidate_id,'jaf_form_data_id'  => $verificationInsufficiency->jaf_form_data_id,'service_id'  => $verificationInsufficiency->service_id,'item_number' => $verificationInsufficiency->item_number,'activity_type'=> $verificationInsufficiency->activity_type,'status' =>$verificationInsufficiency->status,'notes' => $verificationInsufficiency->notes,'updated_by'=>$verificationInsufficiency->updated_by,'updated_at'=>$verificationInsufficiency->updated_at
            ];
            $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->where('activity_title','Verification Insufficiency')->latest()->first();
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
            // dd($user_data);
            $new_activity = new ActivityLog();
            $new_activity->parent_id =$user_type?$user_type->parent_id:'';
            $new_activity->business_id =$user_type->business_id?$user_type->business_id:'';
            $new_activity->activity_id = $verificationInsufficiency->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='deleted';
            $new_activity->activity_title ='Verification Insufficiency';
            $new_activity->data = $user_data;
            $new_activity->created_by = Auth::user()->id;
            $new_activity->save();
        }

    }

    /**
     * Handle the verfication insufficiency "deleted" event.
     *
     * @param  \App\VerificationInsufficiency  $verficationInsufficiency
     * @return void
     */
    public function deleted(VerificationInsufficiency $verificationInsufficiency)
    {
        //
    }

    /**
     * Handle the verfication insufficiency "restored" event.
     *
     * @param  \App\VerficationInsufficiency  $verficationInsufficiency
     * @return void
     */
    public function restored(VerificationInsufficiency $verificationInsufficiency)
    {
        //
    }

    /**
     * Handle the verfication insufficiency "force deleted" event.
     *
     * @param  \App\VerficationInsufficiency  $verficationInsufficiency
     * @return void
     */
    public function forceDeleted(VerificationInsufficiency $verificationInsufficiency)
    {
        //
    }
}
