<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Admin\KeyAccountManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KeyAccountManagerObserver
{
    /**
     * Handle the key account manager "created" event.
     *
     * @param  \App\Models\Admin\KeyAccountManager  $keyAccountManager
     * @return void
     */
    public function created(KeyAccountManager $keyAccountManager)
    {
      // dd($customerSla);
      if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
      $url_host = "https://";   
      else  
          $url_host = "http://";   
      // Append the host(domain name, ip) to the URL.   
      $url_host.= $_SERVER['HTTP_HOST'];   

      // dd($url_host);
      // Append the requested resource location to the URL   
      $url= $_SERVER['REQUEST_URI']; 
      $user_type= DB::table('key_account_managers')->where('id',$keyAccountManager->id)->first();

      $input_data['new'] = [
          'business_id'=> $user_type->business_id,'customer_id' => $user_type->customer_id, 'user_id'=> $user_type->user_id,'is_primary'=>$user_type->is_primary,'status' => $user_type->status,'created_by' =>  $user_type->created_by?$user_type->created_by:'','created_at' => date('Y-m-d H:i:s')
        ];
         $user=NULL;
            if ($user_type) {
                $user= DB::table('users')->where('id',$user_type->business_id)->first();
            }
      $user_data = json_encode($input_data);
      $new_activity = new ActivityLog();
      $new_activity->parent_id =  $user!=NULL?$user->parent_id:'';
      $new_activity->business_id = $user_type?$user_type->business_id:'';
      $new_activity->activity_id = $keyAccountManager->id;
      $new_activity->url_host = $url_host;
      $new_activity->url_request = $url;
      $new_activity->activity ='created';
      $new_activity->activity_title =$user_type->is_primary=='1'?'Primary CAM':'Secondary CAM';
      $new_activity->data = $user_data;
      $new_activity->created_by = $user_type->created_by?$user_type->created_by:'';
      $new_activity->save();
    }

    /**
     * Handle the key account manager "updated" event.
     *
     * @param  \App\Models\Admin\KeyAccountManager  $keyAccountManager
     * @return void
     */
    public function updated(KeyAccountManager $keyAccountManager)
    {
        //
    }

    /**
     * Handle the key account manager "deleted" event.
     *
     * @param  \App\Models\Admin\KeyAccountManager  $keyAccountManager
     * @return void
     */
    public function deleted(KeyAccountManager $keyAccountManager)
    {
        //
    }

    /**
     * Handle the key account manager "restored" event.
     *
     * @param  \App\Models\Admin\KeyAccountManager  $keyAccountManager
     * @return void
     */
    public function restored(KeyAccountManager $keyAccountManager)
    {
        //
    }

    /**
     * Handle the key account manager "force deleted" event.
     *
     * @param  \App\Models\Admin\KeyAccountManager  $keyAccountManager
     * @return void
     */
    public function forceDeleted(KeyAccountManager $keyAccountManager)
    {
        //
    }
}
