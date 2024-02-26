<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Exports\SalesDataExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OPSExport;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ExcelExportNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'excelExport:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a mail notification for weekly based Sales & OPS Tracker Report';

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
        $this->opsExport();

        $this->salesExport();

        $this->info('Excel Export Notify:Cron Command Run Successfully!');

        return 0;
    }

    public function opsExport()
    {
        $today_date = date('Y-m-d');

        $start_date = date('Y-m-d',strtotime('- 6 days'));

        $path=public_path().'/excel-export/';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (File::exists($path)) 
        {
            File::cleanDirectory($path);
        }
        
        $file_name = 'ops-tracker-'.date('YmdHis').'.xlsx';

        $customers = DB::table('users')->where(['user_type'=>'customer'])->get();

        if(count($customers)>0)
        {
            foreach($customers as $cust)
            {
                $business_id = $cust->business_id;

                $customer_id = NULL;

                $user_id = NULL;
            
                $query = DB::table('candidate_reinitiates as u')
                            ->select('u.*','ub.company_name',DB::raw('@row  := @row  + 1 AS s_no'),'s.id as service_id','s.name as service_name','jf.check_item_number as item_no','s.verification_type','j.id as job_item_id','j.tat_type','j.client_tat as case_tat')
                            ->join('user_businesses as ub','u.business_id','=','ub.business_id')
                            ->join('job_items as j','j.candidate_id','=','u.id')
                            ->join('jaf_form_data as jf','jf.job_item_id','=','j.id')
                            ->join('services as s','s.id','=','jf.service_id')
                            ->whereDate('u.created_at','>=',$start_date)
                            ->whereDate('u.created_at','<=',$today_date)
                            ->where(['u.parent_id'=>$business_id,'u.user_type'=>'candidate','j.jaf_status'=>'filled'])
                            ->get();

                if(count($query)>0)
                {

                    Excel::store(new OPSExport($start_date,$today_date,$business_id,$customer_id,$user_id),'/excel-export/'.$file_name,'real_public');

                    $name=$cust->name;
            
                    $email=$cust->email;

                    $msg = 'You have Receive the OPS Tracker Weekly Notification, Please checkout the details for further Updates.';

                    $title = 'You have Receive the OPS Tracker Weekly Notification, Please checkout the details to your Mail about the notification.';

                    DB::table('notifications')->insert(
                        [
                            'parent_id' => $cust->parent_id,
                            'business_id' => $cust->business_id,
                            'title' => 'OPS Weekly Export Notification',
                            'message' => $title,
                            'created_by'   => $cust->id,
                            'created_at' => date('Y-m-d H:i:s')
                        ]
                    );

                    $url = url('/').'/excel-export/'.$file_name;

                    $sender = DB::table('users')->where(['id'=>$business_id])->first();

                    $data=['name' =>$name,'email' => $email, 'msg'=>$msg, 'start_date'=> $start_date, 'end_date' => $today_date, 'url'=> $url,'sender'=>$sender];

                    Mail::send(['html'=>'mails.excel-export-notify'], $data, function($message) use($email,$name) {
                        $message->to($email, $name)->subject
                            ('myBCD System - OPS Tracker Notification');
                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    });

                    $users = DB::table('users')->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0,'is_export_notify'=>'1','status'=>'1'])->get();

                    if(count($users)>0)
                    {
                        foreach($users as $user)
                        {
                            $name = $user->name;

                            $email = $user->email;

                            DB::table('notifications')->insert(
                                [
                                    'parent_id' => $user->parent_id,
                                    'business_id' => $user->business_id,
                                    'title' => 'OPS Weekly Export Notification',
                                    'message' => $title,
                                    'created_by'   => $user->id,
                                    'created_at' => date('Y-m-d H:i:s')
                                ]
                            );

                            $url = url('/').'/excel-export/'.$file_name;

                            $data=['name' =>$name,'email' => $email, 'msg'=>$msg, 'start_date'=> $start_date, 'end_date' => $today_date, 'url'=> $url,'sender'=>$sender];

                            Mail::send(['html'=>'mails.excel-export-notify'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('myBCD System - OPS Tracker Notification');
                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            });
                        }
                    }
                }
                        
            }

        }

        
    }

    public function salesExport()
    {
        
        $today_date = date('Y-m-d');

        $start_date = date('Y-m-d',strtotime('- 6 days'));

        $path=public_path().'/excel-export/';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        if (File::exists($path)) 
        {
            File::cleanDirectory($path);
        }

        $file_name = 'sales-tracker-'.date('YmdHis').'.xlsx';

        $customers = DB::table('users')->where(['user_type'=>'customer'])->get();

        if(count($customers)>0)
        {
            foreach($customers as $cust)
            {
                $business_id = $cust->business_id;

                $customer_id = NULL;

                $clients = DB::table('users as u')
                            ->join('user_businesses as ub','ub.business_id','=','u.business_id')
                            ->where(['u.parent_id'=>$cust->id,'u.user_type'=>'client'])
                            ->get();

                if(count($clients)>0)
                {
                    Excel::store(new SalesDataExport($start_date,$today_date,$business_id,$customer_id,'weekly'),'/excel-export/'.$file_name,'real_public');

                    $name = $cust->name;
    
                    $email=$cust->email;
    
                    $msg = 'You have Receive the Sales Tracker Weekly Notification, Please checkout the details for further Updates.';
    
                    $title = 'You have Receive the Sales Tracker Weekly Notification, Please checkout the details to your Mail about the notification.';
    
                    DB::table('notifications')->insert(
                        [
                            'parent_id' => $cust->parent_id,
                            'business_id' => $cust->business_id,
                            'title' => 'Sales Weekly Export Notification',
                            'message' => $title,
                            'created_by'   => $cust->id,
                            'created_at' => date('Y-m-d H:i:s')
                        ]
                    );
    
                    $url = url('/').'/excel-export/'.$file_name;

                    $sender = DB::table('users')->where(['id'=>$business_id])->first();
    
                    $data=['name' =>$name,'email' => $email, 'msg'=>$msg, 'start_date'=> $start_date, 'end_date' => $today_date, 'url'=> $url,'sender'=>$sender];
    
                    Mail::send(['html'=>'mails.excel-export-notify'], $data, function($message) use($email,$name) {
                        $message->to($email, $name)->subject
                            ('myBCD System - Sales Tracker Notification');
                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    });

                    $users = DB::table('users')->where(['business_id'=>$business_id,'user_type'=>'user','is_deleted'=>0,'is_export_notify'=>'1','status'=>'1'])->get();

                    if(count($users)>0)
                    {
                        foreach($users as $user)
                        {
                            $name = $user->name;
                            
                            $email = $user->email;

                            DB::table('notifications')->insert(
                                [
                                    'parent_id' => $cust->parent_id,
                                    'business_id' => $cust->business_id,
                                    'title' => 'Sales Weekly Export Notification',
                                    'message' => $title,
                                    'created_by'   => $cust->id,
                                    'created_at' => date('Y-m-d H:i:s')
                                ]
                            );
            
                            $url = url('/').'/excel-export/'.$file_name;

                            $data=['name' =>$name,'email' => $email, 'msg'=>$msg, 'start_date'=> $start_date, 'end_date' => $today_date, 'url'=> $url,'sender'=>$sender];

                            Mail::send(['html'=>'mails.excel-export-notify'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('myBCD System - Sales Tracker Notification');
                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            });
                        }
                    }
                }

            }
        }

    }
}
