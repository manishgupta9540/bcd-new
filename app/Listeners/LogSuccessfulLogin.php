<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Browser;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        //
       // dd($event);
        $browser = get_browser();

        $login_log = DB::table('login_logout_activity_logs')
                        ->where(['user_id'=>$event->user->id])
                        ->whereNull('logout_at')
                        ->latest()
                        ->first();

        if($login_log!=NULL)
        {
            if($login_log->last_login_activity_at!=NULL)
            {
                DB::table('login_logout_activity_logs')->where(['id'=>$login_log->id])->update([
                    'logout_at'  => $login_log->last_login_activity_at,
                    'updated_at' => date('Y-m-d H:i:s') 
                ]);
            }
            else
            {
                $login_date = date('Y-m-d H:i:s',strtotime($login_log->login_at.'+ 30 minutes'));

                DB::table('login_logout_activity_logs')->where(['id'=>$login_log->id])->update([
                    'logout_at' => $login_date,
                    'updated_at' => date('Y-m-d H:i:s') 
                ]);
            }
        }

        DB::table('login_logout_activity_logs')->insert([
            'user_id'   => $event->user->id,
            'user_type' => $event->user->user_type,
            'login_at' => date('Y-m-d H:i:s'),
            // 'last_login_activity_at' => date('Y-m-d H:i:s'),
            'ip_address' => request()->getClientIp(true),
            'platform' => $browser->platform,
            'device_type' => property_exists($browser,'device_type')?$browser->device_type:NULL,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
