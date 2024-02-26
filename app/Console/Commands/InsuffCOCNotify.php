<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use PDF;
use Illuminate\Support\Facades\File;
class InsuffCOCNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insuffCoc:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to the COCs whose allowed to get the insuff notification';

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
        $users= DB::table('users')->where('user_type','client')->orderBy('id','desc')->get();

        foreach($users as $user)
        {
            $parent_id = $user->parent_id;

            $insuff_control=DB::table('coc_insuff_controls')->where(['business_id'=>$user->id])->first();

            if($insuff_control!=NULL)
            {
                $today_date = date('Y-m-d');

                $start_date = date('Y-m-d',strtotime($today_date.'-'.$insuff_control->days.'days'));

                $path=public_path().'/uploads/insuff-notify/';

                if (!File::exists($path)) {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }

                $file_name="insuff-notify-".date('Ymdhis').".pdf";

                $insuff_notify_log=DB::table('notification_logs')->where('business_id',$user->id)->where('module_type','insuff_logs')->latest()->first();

                if($insuff_notify_log!=NULL)
                {
                    $next_end_date = date('Y-m-d',strtotime($insuff_notify_log->end_date.'+'.$insuff_control->days.'days'));

                    if(strtotime($today_date)==strtotime($next_end_date))
                    {
                        $insuff_log=DB::table('insufficiency_logs as si')
                                    ->select('si.*','s.verification_type','s.name')
                                    ->join('services as s','s.id','=','si.service_id')
                                    ->where('coc_id',$user->id)
                                    ->whereIn('si.status',['raised','failed'])
                                    ->whereDate('si.created_at','>=',$start_date)
                                    ->whereDate('si.created_at','<=',date('Y-m-d',strtotime($today_date.'-1 days')))
                                    ->orderBy('si.created_at','desc')
                                    ->get();
                    
                            if(count($insuff_log)>0)
                            {
                                $data=[
                                    'business_id' => $user->id,
                                    'start_date' => date('Y-m-d H:i:s',strtotime($start_date)),
                                    'end_date' => date('Y-m-d H:i:s',strtotime($today_date)),
                                    'days' => $insuff_control->days,
                                    'module_type' => 'insuff_logs'
                                ];
            
                                $insuff_id=DB::table('notification_logs')->insertGetId($data);

                                if($insuff_control->status==1)
                                {
                                    $pdf = PDF::loadView('admin.accounts.insufficiency.pdf.insuff-notify', compact('insuff_log'),[],[
                                        'title' => 'Insufficiency Details',
                                        'margin_top' => 20,
                                        'margin-header'=>20,
                                        'margin_bottom' =>25,
                                        'margin_footer'=>5,
                                    ])->save($path.$file_name);
                                    
                                    DB::table('notification_logs')->where(['id'=>$insuff_id])->update([
                                        'file_name' => $file_name,
                                        'record_status' => 'found',
                                        'updated_at' => date('Y-m-d H:i:s')
                                    ]);
            
                                    DB::table('notifications')->insert(
                                        [
                                            'parent_id' => $user->parent_id,
                                            'business_id' => $user->business_id,
                                            'user_id' => $user->id,
                                            'title' => 'Insufficiency Notification',
                                            'message' => 'You have Receive the insufficiency Record, Please checkout the details for further Updates.',
                                            'module_id' => $insuff_id,
                                            'created_by'   => $user->parent_id,
                                            'module_type' => 'notification_logs',
                                            'created_at' => date('Y-m-d H:i:s')
                                        ]
                                    );
                                    $user_d=DB::table('users')->where(['id'=>$user->id])->first();
            
                                    $insuff_log_data = DB::table('notification_logs')->where(['id'=>$insuff_id])->first();
            
                                    $name=$user_d->first_name.' '.$user_d->last_name;
            
                                    $email=$user_d->email;

                                    $sender_d = DB::table('users')->where(['id'=>$parent_id])->first();
                            
                                    $data=['name' =>$name,'email' => $email,'user'=>$user_d,'insuff_log'=>$insuff_log_data,'sender'=>$sender_d];
            
                                    Mail::send(['html'=>'mails.insuff_notification'], $data, function($message) use($email,$name) {
                                        $message->to($email, $name)->subject
                                            ('myBCD System - Insufficiency Notification');
                                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                    });

                                }
                                else
                                {
                                    DB::table('notification_logs')->where(['id'=>$insuff_id])->update([
                                        'record_status' => 'disable',
                                        'updated_at' => date('Y-m-d H:i:s')
                                    ]);
                                }
                            }
                            else
                            {
                                $data=[
                                    'business_id' => $user->id,
                                    'start_date' => date('Y-m-d H:i:s',strtotime($start_date)),
                                    'end_date' => date('Y-m-d H:i:s',strtotime($today_date)),
                                    'record_status' => 'not_found',
                                    'days' => $insuff_control->days,
                                    'module_type' => 'insuff_logs'
                                ];
            
                                $insuff_id=DB::table('notification_logs')->insertGetId($data);

                            }
                    }
                }
                else
                {
                    $insuff_log=DB::table('insufficiency_logs as si')
                                    ->select('si.*','s.verification_type','s.name')
                                    ->join('services as s','s.id','=','si.service_id')
                                    ->where('coc_id',$user->id)
                                    ->whereIn('si.status',['raised','failed'])
                                    ->whereDate('si.created_at','>=',$start_date)
                                    ->whereDate('si.created_at','<=',date('Y-m-d',strtotime($today_date.'-1 days')))
                                    ->orderBy('si.created_at','desc')
                                    ->get();
                    
                    if(count($insuff_log)>0)
                    {
                        $data=[
                            'business_id' => $user->id,
                            'start_date' => date('Y-m-d H:i:s',strtotime($start_date)),
                            'end_date' => date('Y-m-d H:i:s',strtotime($today_date)),
                            'days' => $insuff_control->days,
                            'module_type' => 'insuff_logs'
                        ];
    
                        $insuff_id=DB::table('notification_logs')->insertGetId($data);

                        if($insuff_control->status==1)
                        {
                            $pdf = PDF::loadView('admin.accounts.insufficiency.pdf.insuff-notify', compact('insuff_log'),[],[
                                'title' => 'Insufficiency Details',
                                'margin_top' => 20,
                                'margin-header'=>20,
                                'margin_bottom' =>25,
                                'margin_footer'=>5,
                            ])->save($path.$file_name);
                            
                            DB::table('notification_logs')->where(['id'=>$insuff_id])->update([
                                'file_name' => $file_name,
                                'record_status' => 'found',
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
    
                            DB::table('notifications')->insert(
                                [
                                    'parent_id' => $user->parent_id,
                                    'business_id' => $user->id,
                                    'title' => 'Insufficiency Notification',
                                    'message' => 'You have Receive the insufficiency Record, Please checkout the details for further Updates.',
                                    'module_id' => $insuff_id,
                                    'created_by'   => $user->parent_id,
                                    'module_type' => 'notification_logs',
                                    'created_at' => date('Y-m-d H:i:s')
                                ]
                            );
                            $user_d=DB::table('users')->where(['id'=>$user->id])->first();
    
                            $insuff_log_data = DB::table('notification_logs')->where(['id'=>$insuff_id])->first();
    
                            $name=$user_d->first_name.' '.$user_d->last_name;
    
                            $email=$user_d->email;

                            $sender_d = DB::table('users')->where(['id'=>$parent_id])->first();
                    
                            $data=['name' =>$name,'email' => $email,'user'=>$user_d,'insuff_log'=>$insuff_log_data,'sender'=>$sender_d];
    
                            Mail::send(['html'=>'mails.insuff_notification'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('myBCD System - Insufficiency Notification');
                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            });

                        }
                        else
                        {
                            DB::table('notification_logs')->where(['id'=>$insuff_id])->update([
                                'record_status' => 'disable',
                                'updated_at' => date('Y-m-d H:i:s')
                            ]);
                        }

                    }
                    else
                    {
                        $data=[
                            'business_id' => $user->id,
                            'start_date' => date('Y-m-d H:i:s',strtotime($start_date)),
                            'end_date' => date('Y-m-d H:i:s',strtotime($today_date)),
                            'record_status' => 'not_found',
                            'days' => $insuff_control->days,
                            'module_type' => 'insuff_logs'
                        ];
    
                        $insuff_id=DB::table('notification_logs')->insertGetId($data);

                    }
                }
            }
        }

        return 0;
    }
}
