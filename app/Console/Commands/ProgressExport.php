<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use App\Exports\ProgressDataExport;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Config;

class ProgressExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'progress-export:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a mail notification for Progress Tracker Excel Export which was requested by an admin & user..';

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
        ini_set('max_execution_time', '0');

        $progress_logs = DB::table('progress_export_logs')->where('status',0)->get();

        if(count($progress_logs)>0)
        {
            foreach($progress_logs as $item)
            {
                $business_id = $item->business_id;

                $type = $item->duration_type;

                $month = [];

                $year = $item->year!=NULL ? $item->year : date('Y');

                $customer_id = $item->customer_id!=NULL ? explode(',',$item->customer_id) : [];

                $report_type=$item->report_type!=NULL ? explode(',',$item->report_type) : [];

                $from_date = $item->from_date;

                $to_date = $item->to_date;

                $month = ($type=='monthly' || $type=='yearly') && $item->month!=NULL ? explode(',',$item->month) : [date('m')];

                $file_name = 'progress-tracker-('.$from_date.' - '.$to_date.')-'.date('YmdHis').'.xlsx';

                $user = DB::table('users')->where('id',$item->created_by)->first();

                if($user!=NULL)
                {
                    Excel::store(new ProgressDataExport($type,$month,$year,$report_type,$business_id,$customer_id),'/uploads/progress-export/'.$file_name,'real_public');

                    DB::table('progress_export_logs')->where('id',$item->id)->update([
                        'status' => 1,
                        'file_name' => $file_name
                    ]);

                    $name = $user->first_name;

                    $email = $user->email;

                    $link = Config::get('app.user_url').'/user/downloadProgressExcel/'.base64_encode($item->id);
    
                    $sender = DB::table('users')->where(['id'=>$business_id])->first();

                    $progress_log = DB::table('progress_export_logs')->where('id',$item->id)->first();
    
                    $data  = array('name'=>$name,'email'=>$email,'link'=>$link,'sender'=>$sender,'data'=>$progress_log);

                    Mail::send(['html'=>'mails.progress-export-notify'], $data, function($message) use($email,$name) {
                            $message->to($email, $name)->subject
                            ('myBCD System - Notification for Progress Export');
                            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                    });
    
                    
                }

            }

            $this->info('Progress Export Notify:Cron Command Run Successfully!');
        }

        return 0;
    }
}
