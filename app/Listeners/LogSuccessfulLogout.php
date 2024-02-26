<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class LogSuccessfulLogout
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
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        //
        $login_log = DB::table('login_logout_activity_logs')->where(['user_id'=>$event->user->id])->latest()->first();

        if($login_log!=NULL)
        {
            DB::table('login_logout_activity_logs')->where(['id'=>$login_log->id])->update([
                'last_login_activity_at' => date('Y-m-d H:i:s'),
                'logout_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

    }
}
