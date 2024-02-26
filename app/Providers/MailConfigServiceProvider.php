<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // view()->composer('*', function($view)
        // {
        //     $business_id= Auth::user()->business_id;

        //     // dd($view);
        //     $user = DB::table('users')->select('business_id')->where('business_id',$business_id)->first();
        //    if ($user) {
        //     $mail= DB::table('email_config_masters')->where('business_id',$user->business_id)->first();
        //     // dd($mail);
        //    }
            
        //     if ($mail) {
        //         # code...
           
        //          $config = array(
        //              'driver'     => $mail->driver,
        //              'host'       => $mail->host,
        //              'port'       => $mail->port,
        //              'from'       => array('address' => $mail->sender_email, 'name' => $mail->sender_name),
        //              'encryption' => $mail->encryption,
        //              'username'   => $mail->user_name,
        //              'password'   => $mail->password,
        //              'sendmail'   => '/usr/sbin/sendmail -bs',
        //              'pretend'    => false,
        //          );
        //          Config::set('mail', $config);
        //      }
        // });
        // 
    //    $id= Session::getId();
      
    }
}
