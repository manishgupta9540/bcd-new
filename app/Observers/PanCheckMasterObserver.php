<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\PanCheckMaster;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PanCheckMasterObserver
{
    /**
     * Handle the pan check master "created" event.
     *
     * @param  \App\Models\Admin\PanCheckMaster  $panCheckMaster
     * @return void
     */
    public function created(PanCheckMaster $panCheckMaster)
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

        $user_type= DB::table('pan_check_masters')->where('id',$panCheckMaster->id)->first();
        $input_data['new'] = [
           'parent_id'=>$user_type->parent_id??null,'business_id'=>$user_type->business_id??null,'pan_number' =>$panCheckMaster->pan_number,'category'=>$panCheckMaster->category,'full_name'=>$panCheckMaster->full_name,'is_api_verified'=>'1','is_pan_exist'=>'1','created_by'=>$user_type->created_by?$user_type->created_by:'','created_at'=>$user_type->created_at
        ];

        $user_data = json_encode($input_data);

        $new_activity = new ActivityLog();
        $new_activity->parent_id =$user_type->parent_id??NULL;
        $new_activity->business_id =$user_type->business_id??NULL;
        $new_activity->activity_id = $panCheckMaster->id;
        $new_activity->url_host = $url_host;
        $new_activity->url_request = $url;
        $new_activity->activity ='created';
        $new_activity->activity_title ='PAN Check Master';
        $new_activity->data = $user_data;
        $new_activity->created_by =$user_type->created_by?$user_type->created_by:'';
        $new_activity->save();
    }

    /**
     * Handle the pan check master "updated" event.
     *
     * @param  \App\Models\Admin\PanCheckMaster  $panCheckMaster
     * @return void
     */
    public function updated(PanCheckMaster $panCheckMaster)
    {
        //
    }

    /**
     * Handle the pan check master "deleted" event.
     *
     * @param  \App\Models\Admin\PanCheckMaster  $panCheckMaster
     * @return void
     */
    public function deleted(PanCheckMaster $panCheckMaster)
    {
        //
    }

    /**
     * Handle the pan check master "restored" event.
     *
     * @param  \App\Models\Admin\PanCheckMaster  $panCheckMaster
     * @return void
     */
    public function restored(PanCheckMaster $panCheckMaster)
    {
        //
    }

    /**
     * Handle the pan check master "force deleted" event.
     *
     * @param  \App\Models\Admin\PanCheckMaster  $panCheckMaster
     * @return void
     */
    public function forceDeleted(PanCheckMaster $panCheckMaster)
    {
        //
    }
}
