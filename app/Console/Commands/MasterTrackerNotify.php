<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use App\Exports\MISDataExport;

class MasterTrackerNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'masterTracker:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a Mail Notification Daily for Master Tracker';

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
        $business_id = 94;

        $path=public_path().'/uploads/master-tracker-notify/';

        if(!File::exists($path))
        {
            File::makeDirectory($path, $mode = 0777, true, true);
        }

        $today_date = date('Y-m-d',strtotime('-1 day'));

        $start_date = date('Y-m-01',strtotime($today_date.' -1 year'));

        $file_name = 'master-tracker-'.date('YmdHis').'.xlsx';

        Excel::store(new MISDataExport($start_date,$today_date,$business_id),'/uploads/master-tracker-notify/'.$file_name,'real_public');

        $mail_arr = [];

        $mail_arr = [
            ['name'=>'John Chenetra','email'=>'john.chenetra@premier-consultancy.com'],
            ['name'=>'Akshay Kumar','email'=>'akshay.kumar@premier-consultancy.com'],
            //['name'=>'Mithilesh Sah','email'=>'mithilesh.techsaga@gmail.com'],
            //['name'=>'Abhijit Ahluwalia','email'=>'abhijit.tagworld@yopmail.com'],
        ];

        $url = url('/').'/uploads/master-tracker-notify/'.$file_name;

        foreach($mail_arr as $item)
        {
            $name = $item['name'];
            $email = $item['email'];

            $sender = DB::table('users')->where(['id'=>$business_id])->first();

            $data=[
                'name' => $name,
                'email' => $email,
                'url' => $url,
                'sender'=>$sender
            ];

            Mail::send(['html'=>'mails.master-tracker-notify'], $data, function($message) use($email,$name) {
                $message->to($email, $name)->subject
                    ('myBCD System - Master Tracker Notification');
                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
            });
        }

        $this->info('Master Tracker Report Created Successfully for '.date('Y-m-d h:i A'));

        return 0;
    }
}
