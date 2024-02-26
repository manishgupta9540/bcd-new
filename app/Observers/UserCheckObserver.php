<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\UserCheck;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserCheckObserver
{
    /**
     * Handle the user check "created" event.
     *
     * @param  \App\Models\Admin\UserCheck  $userCheck
     * @return void
     */
    public function created(UserCheck $userCheck)
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
        $user_type= DB::table('user_checks')->where('id',$userCheck->id)->first();
        $input_data['new'] = [
            'business_id' => $userCheck->business_id,'user_id' => $user_type->user_id,'checks' => $user_type->checks, 'created_by' =>Auth::user()->id, 'created_at' => date('Y-m-d h:i:s')
          ];

        $user_data = json_encode($input_data);

        $new_activity = new ActivityLog();
        $new_activity->parent_id =  Auth::user()->parent_id;
        $new_activity->business_id = $user_type?$user_type->business_id:'';
        $new_activity->activity_id = $userCheck->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title ='User Check';
        $new_activity->data = $user_data;
        $new_activity->created_by = Auth::user()->id;
        $new_activity->save();
    
    }

    /**
     * Handle the user check "updated" event.
     *
     * @param  \App\Models\Admin\UserCheck  $userCheck
     * @return void
     */
    public function updated(UserCheck $userCheck)
    {
        //
    }

    /**
     * Handle the user check "deleted" event.
     *
     * @param  \App\Models\Admin\UserCheck  $userCheck
     * @return void
     */
    public function deleted(UserCheck $userCheck)
    {
        // dd($userCheck);
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
        $url_host = "https://";   
        else  
            $url_host = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url_host.= $_SERVER['HTTP_HOST'];   

        // dd($url_host);
        // Append the requested resource location to the URL   
        $url= $_SERVER['REQUEST_URI'];    
        // $user_type= DB::table('user_checks')->where('id',$userCheck->id)->first();
        // dd($user_type);
        $input_data['old'] = [
            'business_id' => $userCheck->business_id,'user_id' => $userCheck->user_id,'checks' => $userCheck->checks, 'created_by' =>Auth::user()->id, 'created_at' => date('Y-m-d h:i:s')
          ];

        $user_data = json_encode($input_data);

        $new_activity = new ActivityLog();
        $new_activity->parent_id =  Auth::user()->parent_id;
        $new_activity->business_id = $userCheck?$userCheck->business_id:'';
        $new_activity->activity_id = $userCheck->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='deleted';
        $new_activity->activity_title ='User Check';
        $new_activity->data = $user_data;
        $new_activity->created_by = Auth::user()->id;
        $new_activity->save();
    }

    /**
     * Handle the user check "restored" event.
     *
     * @param  \App\Models\Admin\UserCheck  $userCheck
     * @return void
     */
    public function restored(UserCheck $userCheck)
    {
        //
    }

    /**
     * Handle the user check "force deleted" event.
     *
     * @param  \App\Models\Admin\UserCheck  $userCheck
     * @return void
     */
    public function forceDeleted(UserCheck $userCheck)
    {
        //
    }
}
