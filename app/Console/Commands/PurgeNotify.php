<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PurgeNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purge:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends the purge notification to Client, Candidate, & Guest';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // Guest Purge Notification
        $this->guestPurgeNotify();

        $this->info('Purge:Cron Command Run successfully!');

        return 0;
    }

    public function guestPurgeNotify(){
        $users = DB::table('users')->where(['user_type'=>'guest','is_purged'=>'1'])->get();
        
        $today_date = date('Y-m-d');
        if(count($users)>0)
        {
            foreach($users as $user)
            {
                $parent_id = $user->parent_id;

                $business_id = $user->business_id;

                $user_id = $user->id;

                $modules = [];

                $tables = [];

                $module_all = [];

                $pending_order = DB::table('guest_instant_masters')->where(['business_id'=>$user->business_id,'is_payment_done'=>0])->get();

                $guest_masters = DB::table('guest_instant_masters')->where(['business_id'=>$business_id])->get();

                if(count($pending_order) <= 0 && count($guest_masters) > 0)
                {
                    $purge_date = date('Y-m-d',strtotime($user->created_at.'+'.($user->purge_days - 1).'days'));

                    $purge_logs = DB::table('purge_data_logs')->where(['business_id'=>$user->business_id,'module_type'=>'purge-notify'])->latest()->first();

                    $modules[]='Instant Verification & Orders';

                    array_push($tables,'guest_instant_masters','guest_instant_carts','guest_instant_cart_services');

                    $module_all['Instant Verification & Orders'] = ['guest_instant_masters','guest_instant_carts','guest_instant_cart_services'];


                    if($purge_logs!=NULL)
                    {
                        $purge_date = date('Y-m-d',strtotime($purge_logs->created_at.'+'.($user->purge_days - 1).'days')); 
                    }
                    else if($user->purge_date!=NULL)
                    {
                        $purge_date = date('Y-m-d',strtotime($user->purge_date.'+'.($user->purge_days - 1).'days')); 
                    }

                    if(strtotime($today_date) >= strtotime($purge_date))
                    {

                        //Delete the report pdf & zip
                        // foreach($guest_masters as $item)
                        // {
                        //     // Cart Services

                        //     $guest_cart_services =DB::table('guest_instant_cart_services')->where(['giv_m_id'=>$item->id])->get();

                        //     if(count($guest_cart_services)>0)
                        //     {
                        //         $path=public_path().'/guest/reports/pdf/';

                        //         foreach($guest_cart_services as $gcs)
                        //         {
                        //             if($gcs->file_name!=NULL && File::exists($path.$gcs->file_name))
                        //             {
                        //                 File::delete($path.$gcs->file_name);
                        //             }
                        //         }
                        //     }

                        //     // Carts 

                        //     $guest_carts =DB::table('guest_instant_carts')->where(['giv_m_id'=>$item->id])->get();

                        //     if(count($guest_carts)>0)
                        //     {
                        //         $path = public_path();

                        //         foreach($guest_carts as $gc)
                        //         {
                        //             $services=DB::table('services')->where('id',$gc->service_id)->first();

                        //             if($services->name=='Aadhar')
                        //             {
                        //                 $path = public_path().'/guest/reports/zip/aadhar/';
                        //             }
                        //             else if($gc->service_id==3)
                        //             {
                        //                 $path = public_path().'/guest/reports/zip/pan/';
                        //             }
                        //             else if($services->name=='Voter ID')
                        //             {
                        //                 $path = public_path().'/guest/reports/zip/voterid/';
                        //             }
                        //             else if($services->name=='RC')
                        //             {
                        //                 $path = public_path().'/guest/reports/zip/rc/';
                        //             }
                        //             else if($services->name=='Passport')
                        //             {
                        //                 $path = public_path().'/guest/reports/zip/passport/';
                        //             }
                        //             else if($services->name=='Driving')
                        //             {
                        //                 $path = public_path().'/guest/reports/zip/driving/';
                        //             }
                        //             else if($services->name=='Bank Verification')
                        //             {
                        //                 $path = public_path().'/guest/reports/zip/bank/';
                        //             }
                        //             else if(stripos($services->name,'E-Court')!==false)
                        //             {
                        //                 $path = public_path().'/guest/reports/zip/e-court/';
                        //             }

                        //             if($gc->zip_name!=NULL && File::exists($path.$gc->zip_name))
                        //             {
                        //                 File::delete($path.$gc->zip_name);
                        //             }
                        //         }
                        //     }

                        //     // masters
                        //     $path = public_path().'/guest/reports/zip/';

                        //     if($item->zip_name!=NULL && File::exists($path.$item->zip_name))
                        //     {
                        //         File::delete($path.$item->zip_name);
                        //     }

                        // }
                        

                        // DB::table('guest_instant_cart_services')->where(['business_id'=>$business_id])->delete();

                        // DB::table('guest_instant_carts')->where(['business_id'=>$business_id])->delete();

                        // DB::table('guest_instant_masters')->where(['business_id'=>$business_id])->delete();

                        $user_d=DB::table('users')->where(['id'=>$user->id])->first();

                        $name=$user_d->name;

                        $email=$user_d->email;

                        $msg = 'You Have Receive The Purge Notification, Your Order records has been removed !!';
                
                        $data=['name' =>$name,'email' => $email,'user'=>$user_d,'msg'=>$msg,'date' => $purge_date];

                        Mail::send(['html'=>'mails.purge-notify'], $data, function($message) use($email,$name) {
                            $message->to($email, $name)->subject
                                ('myBCD System - Purge Notification');
                            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                        });

                        DB::table('notifications')->insert([
                            'parent_id' => $user_d->parent_id,
                            'business_id' => $user_d->business_id,
                            'user_id' => $user_d->id,
                            'title'    => 'Purge Notification',
                            'message'   => $msg,
                            'created_by'   => $user_d->parent_id,
                            'created_at'    => date('Y-m-d H:i:s')
                        ]);

                        DB::table('purge_data_logs')->insert([
                            'parent_id' => $parent_id,
                            'business_id' => $business_id,
                            'user_id' => $user_id,
                            'name' => $name,
                            'modules' => json_encode($modules),
                            'tables' => json_encode($tables),
                            'module_all' => json_encode($module_all),
                            'module_type' => 'purge-notify',
                            'user_type' => 'guest',
                            'days' =>  $user->purge_days,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }
        }
    }
}
