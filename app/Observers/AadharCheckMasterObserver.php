<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\AadharCheckMaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AadharCheckMasterObserver
{
    /**
     * Handle the aadhar check master "created" event.
     *
     * @param  \App\Models\Admin\AadharCheckMaster  $aadharCheckMaster
     * @return void
     */
    public function created(AadharCheckMaster $aadharCheckMaster)
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

        $user_type= DB::table('aadhar_check_masters')->where('id',$aadharCheckMaster->id)->first();
        $input_data['new'] = [
            'aadhar_number' =>$aadharCheckMaster->aadhaar_number,'age_range'=>$aadharCheckMaster->age_range,'gender'=>$aadharCheckMaster->gender,'state'=>$aadharCheckMaster->state,'last_digit' =>$aadharCheckMaster->last_digits,'is_api_verified'=>'1','is_aadhar_exist'=>'1','created_by'=> $user_type->created_by?$user_type->created_by:'','created_at'=>$user_type->created_at
        ];

        $user_data = json_encode($input_data);

        $new_activity = new ActivityLog();
        $new_activity->parent_id =$user_type->parent_id??null;
        $new_activity->business_id =$user_type->business_id?$user_type->business_id:'';
        $new_activity->activity_id = $aadharCheckMaster->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title ='Aadhar Check Master';
        $new_activity->data = $user_data;
        $new_activity->created_by = $user_type->created_by?$user_type->created_by:'';
        $new_activity->save();
    }

    /**
     * Handle the aadhar check master "updated" event.
     *
     * @param  \App\Models\Admin\AadharCheckMaster  $aadharCheckMaster
     * @return void
     */
    public function updated(AadharCheckMaster $aadharCheckMaster)
    {
        //
    }

    /**
     * Handle the aadhar check master "deleted" event.
     *
     * @param  \App\Models\Admin\AadharCheckMaster  $aadharCheckMaster
     * @return void
     */
    public function deleted(AadharCheckMaster $aadharCheckMaster)
    {
        //
    }

    /**
     * Handle the aadhar check master "restored" event.
     *
     * @param  \App\Models\Admin\AadharCheckMaster  $aadharCheckMaster
     * @return void
     */
    public function restored(AadharCheckMaster $aadharCheckMaster)
    {
        //
    }

    /**
     * Handle the aadhar check master "force deleted" event.
     *
     * @param  \App\Models\Admin\AadharCheckMaster  $aadharCheckMaster
     * @return void
     */
    public function forceDeleted(AadharCheckMaster $aadharCheckMaster)
    {
        //
    }
}
