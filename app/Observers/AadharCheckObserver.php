<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\AadharCheck;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AadharCheckObserver
{
    /**
     * Handle the aadhar check "created" event.
     *
     * @param  \App\Models\Admin\AadharCheck  $aadharCheck
     * @return void
     */
    public function created(AadharCheck $aadharCheck)
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

        $user_type= DB::table('aadhar_checks')->where('id',$aadharCheck->id)->first();
        $input_data['new'] = [
            'parent_id' => $aadharCheck->parent_id,'business_id' => $aadharCheck->business,'candidate_id' => $aadharCheck->candidate_id,'service_id'=> $aadharCheck->service_id, 'source_reference'=>$aadharCheck->source_reference,'aadhar_number' =>$aadharCheck->aadhar_number,'age_range'=>$aadharCheck->age_range, 'gender' =>$aadharCheck->gender,'state' =>$aadharCheck->state,'last_digit'=>$aadharCheck->last_digit,'is_verified' =>$aadharCheck->is_verified,'is_aadhar_exist' =>$aadharCheck->price,'price'=>$aadharCheck->price,'used_by' =>$aadharCheck->used_by,'user_id' => $aadharCheck->user_id,'created_by'=>$user_type->created_by?$user_type->created_by:'','created_at'=>$user_type->created_at
        ];

        $user_data = json_encode($input_data);

        $new_activity = new ActivityLog();
        $new_activity->parent_id =$user_type?$user_type->parent_id:'';
        $new_activity->business_id =$user_type->business_id?$user_type->business_id:'';
        $new_activity->activity_id = $aadharCheck->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title ='Aadhar Check';
        $new_activity->data = $user_data;
        $new_activity->created_by = $user_type->created_by?$user_type->created_by:'';
        $new_activity->save();
    }

    /**
     * Handle the aadhar check "updated" event.
     *
     * @param  \App\Models\Admin\AadharCheck  $aadharCheck
     * @return void
     * 
     */
    public function updated(AadharCheck $aadharCheck)
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
        if($aadharCheck)
        {
            $user_type= DB::table('aadhar_checks')->where('id',$aadharCheck->id)->first();
            $input_data['new'] = [
                'parent_id' =>$user_type->parent_id??null,'business_id' => $user_type->business_id?$user_type->business_id:'','candidate_id' => $user_type->candidate_id,'service_id'=> $user_type->service_id, 'source_reference'=>$user_type->source_reference,'aadhar_number' =>$user_type->aadhar_number,'age_range'=>$user_type->age_range, 'gender' =>$user_type->gender,'state' =>$user_type->state,'last_digit'=>$user_type->last_digit,'is_verified' =>$user_type->is_verified,'is_aadhar_exist' =>$user_type->price,'price'=>$user_type->price,'used_by' =>$user_type->used_by,'user_id' => $user_type->user_id,'notes' => 'Auto check aadhar cleared','updated_by' => $user_type->created_by?$user_type->created_by:'','updated_at' => date('Y-m-d H:i:s')
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
            $new_activity->parent_id =$user_type->parent_id??null;
            $new_activity->business_id =$user_type->business_id?$user_type->business_id:'';
            $new_activity->activity_id = $aadharCheck->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='updated';
            $new_activity->activity_title ='Aadhar Check';
            $new_activity->data = $user_data;
            $new_activity->created_by = $user_type->created_by?$user_type->created_by:'';
            $new_activity->save();
        }
    }

    /**
     * Handle the post "deleting" event.
     *
     * @param  \App\Models\Admin\AadharCheck  $aadharCheck
     * @return void
     */
    public function deleting(AadharCheck $aadharCheck)
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
        if($aadharCheck)
        {
            $user_type= DB::table('aadhar_checks')->where('id',$aadharCheck->id)->first();
            $input_data['new'] = [
                'parent_id' =>$user_type->parent_id??null,'business_id' => $user_type->business_id?$user_type->business_id:'','candidate_id' => $user_type->candidate_id,'service_id'=> $user_type->service_id, 'source_reference'=>$user_type->source_reference,'aadhar_number' =>$user_type->aadhar_number,'age_range'=>$user_type->age_range, 'gender' =>$user_type->gender,'state' =>$user_type->state,'last_digit'=>$user_type->last_digit,'is_verified' =>$user_type->is_verified,'is_aadhar_exist' =>$user_type->price,'price'=>$user_type->price,'used_by' =>$user_type->used_by,'user_id' => $user_type->user_id,'notes' => '','updated_by' => $user_type->created_by?$user_type->created_by:'','updated_at' => date('Y-m-d H:i:s')
            ];
            $activity=DB::table('activity_logs')->where('activity_id',$user_type->id)->where('activity_title','Aadhar Check')->latest()->first();
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
            $new_activity->parent_id =$user_type->parent_id??null;
            $new_activity->business_id =$user_type->business_id?$user_type->business_id:'';
            $new_activity->activity_id = $aadharCheck->id;
            $new_activity->url_host = $url_host;
            $new_activity->url_request = $url;
            $new_activity->activity ='deleted';
            $new_activity->activity_title ='Aadhar Check';
            $new_activity->data = $user_data;
            $new_activity->created_by = Auth::user()->id;
            $new_activity->save();
        }
    }

    /**
     * Handle the aadhar check "deleted" event.
     *
     * @param  \App\Models\Admin\AadharCheck  $aadharCheck
     * @return void
     */
    public function deleted(AadharCheck $aadharCheck)
    {
        //
    }

    /**
     * Handle the aadhar check "restored" event.
     *
     * @param  \App\Models\Admin\AadharCheck  $aadharCheck
     * @return void
     */
    public function restored(AadharCheck $aadharCheck)
    {
        //
    }

    /**
     * Handle the aadhar check "force deleted" event.
     *
     * @param  \App\Models\Admin\AadharCheck  $aadharCheck
     * @return void
     */
    public function forceDeleted(AadharCheck $aadharCheck)
    {
        //
    }
}
