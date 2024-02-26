<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Helpers\Helper;
use PDF;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class ReportShareNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reportShare:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to the COCs whose allowed to get the notification of report mark as completed';

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
        // Notification for Report Mark as Completed

        $clients = User::from('users as u')
                        ->select('u.*','n.days as n_days','n.status as n_status')
                        ->join('notification_controls as n','u.id','=','n.business_id')
                        ->where('u.user_type','client')
                        ->where(['u.status'=>1,'n.type'=>'report-share-complete'])
                        ->get();

        if(count($clients)>0)
        {
            foreach($clients as $client)
            {
                $parent_id = $client->parent_id;

                $business_id = $client->business_id;

                $user_id = $client->id;

                if($client->n_status==1)
                {
                    $notify_control=DB::table('notification_controls')->where(['business_id'=>$client->id])->first();

                    $today_date = date('Y-m-d');

                    $start_date = date('Y-m-d',strtotime($today_date.'-'.$client->n_days.'days'));

                    // Send Email to Customer for Report Mark as Completed

                    $notification_controls = DB::table('notification_control_configs as nc')
                                            ->select('nc.*')
                                            ->join('notification_controls as n','n.business_id','=','nc.business_id')
                                            ->where(['n.status'=>1,'nc.status'=>1,'n.business_id'=>$client->business_id,'n.type'=>'report-share-complete','nc.type'=>'report-share-complete'])
                                            ->get();

                    $report_notify_log=DB::table('notification_logs')->where('business_id',$client->id)->where('module_type','report_share_logs')->latest()->first();

                    if($report_notify_log!=NULL)
                    {
                        $next_end_date = date('Y-m-d',strtotime($report_notify_log->end_date.'+'.$client->n_days.'days'));

                        if(strtotime($today_date)>=strtotime($next_end_date))
                        {
                            $reports=DB::table('reports')
                                ->where('business_id',$client->id)
                                ->where('status','<>','incomplete')
                                ->where('is_report_complete',1)
                                ->whereDate('report_complete_created_at','>=',$start_date)
                                ->whereDate('report_complete_created_at','<=',date('Y-m-d',strtotime($today_date.'-1 days')))
                                ->get();

                            if(count($reports)>0)
                            {
                                $data=[
                                    'business_id' => $user->id,
                                    'start_date' => date('Y-m-d H:i:s',strtotime($start_date)),
                                    'end_date' => date('Y-m-d H:i:s',strtotime($today_date)),
                                    'days' => $client->n_days,
                                    'module_type' => 'report_share_logs'
                                ];
            
                                $notify_id=DB::table('notification_logs')->insertGetId($data);

                                if(count($notification_controls)>0)
                                {
                                    $report_path = public_path().'/uploads/report-share-data/'.$user_id.'/'.'pdf/';

                                    if (!File::exists($report_path)) {
                                        File::makeDirectory($report_path, $mode = 0777, true, true);
                                    }

                                    if (File::exists($report_path)) 
                                    {
                                        File::cleanDirectory($report_path); 
                                    }

                                    $report_zipname = 'reports-'.date('Ymdhis').'-'.$client->display_id.'.zip';

                                    $report_zip_path = public_path().'/uploads/report-share-data/'.$user_id.'/';

                                    if (!File::exists($report_zip_path)) {

                                        File::makeDirectory($report_zip_path, $mode = 0777, true, true);
                                    }

                                    // if (File::exists($report_zip_path)) 
                                    // {
                                    //     File::cleanDirectory($report_zip_path); 
                                    // }

                                    $zip = new \ZipArchive();      
                                    $zip->open($report_zip_path.$report_zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                                    $report_array=$candidate_array=[];

                                    foreach($reports as $report)
                                    {
                                        $data = [];
                        
                                        $pdf =new PDF;
                                        $report_items = DB::table('report_items as ri')
                                        ->select('ri.*','s.name as service_name','s.id as service_id','s.type_name')  
                                        ->join('services as s','s.id','=','ri.service_id')
                                        ->where(['ri.report_id'=>$report->id,'is_report_output'=>'1']) 
                                        ->orderBy('s.sort_number','asc')
                                        ->orderBy('ri.service_item_order','asc')
                                        ->get(); 

                                        // get candidate_id
                                        $report_data = DB::table('reports as r')->select('id','candidate_id','status','verifier_name','verifier_email','verifier_designation','generated_at','created_at','is_manual_mark','insuff_raised_date','insuff_cleared_date','initiated_date')->where(['id'=>$report->id])->first(); 
                                        // dd($report_data);
                                        $candidate = DB::table('candidate_reinitiates as u')
                                                    ->select('u.id','u.created_at as initiated_date','u.display_id','u.business_id','u.client_emp_code','u.entity_code','u.first_name','u.last_name','u.name','u.email','u.phone','r.id as report_id','r.created_at','r.approval_status_id','r.sla_id','cs.title as sla_name','u.gender','u.dob','r.created_by','u.parent_id','r.revised_date','u.name','r.is_manual_mark','r.status as report_status','r.is_report_complete','r.report_complete_created_at')  
                                                    ->leftjoin('reports as r','r.candidate_id','=','u.id')
                                                    ->join('customer_sla as cs','cs.id','=','r.sla_id')
                                                    ->where(['r.id'=>$report->id]) 
                                                    ->first();

                                        $jaf = DB::table('jaf_form_data')->where(['candidate_id'=>$report_data->candidate_id])->first(); 

                                        $file_name = 'myBCD-Report-'.$candidate->id.'-'.date('Y-m-d').".pdf";

                                        // Check for Report File Renaming

                                        $customer = DB::table('user_businesses')
                                        ->where(['business_id'=>$candidate->business_id,'is_report_file_config'=>1])
                                        ->whereNotNull('report_file_config_details')
                                        ->first();

                                        $template_type = DB::table('report_add_page_statuses')->select('status')->where(['coc_id' => $candidate->business_id,'template_type'=>'3'])->first();

                                        if($customer!=NULL)
                                        {
                                            $file_detail = $customer->report_file_config_details;

                                            $file_detail_arr = json_decode($file_detail,true);

                                            if($file_detail_arr!=NULL && count($file_detail_arr)>0)
                                            {
                                                $file_name = '';

                                                asort($file_detail_arr);

                                                $i=0;

                                                $count = count($file_detail_arr);

                                                foreach($file_detail_arr as $key => $item)
                                                {
                                                    if(stripos($key,'reference_no')!==false)
                                                    {
                                                        $file_name.=$candidate->display_id;
                                                    }
                                                    else if(stripos($key,'emp_code')!==false)
                                                    {
                                                        if($candidate->client_emp_code!='' && $candidate->client_emp_code!=null)
                                                            $file_name.=$candidate->client_emp_code;
                                                    }
                                                    else if(stripos($key,'candidate_name')!==false)
                                                    {
                                                        $file_name.=$candidate->name;
                                                    }
                                                    else if(stripos($key,'status')!==false)
                                                    {
                                                        $status = '';

                                                        if(stripos($candidate->report_status,'interim')!==false)
                                                        {
                                                            $status = 'Interim Report';
                                                        }
                                                        else if(stripos($candidate->report_status,'completed')!==false)
                                                        {
                                                            $status = 'Final Report';
                                                        }
                                                        
                                                        $file_name.=$status;
                                                    }
                                                    else if(stripos($key,'date')!==false)
                                                    {
                                                        $file_name.=date('d-F-Y');
                                                    }

                                                    if(++$i!=$count)
                                                    {
                                                        $file_name.=' - ';
                                                    }
                                                }

                                                $file_name.='.pdf';
                                            }
                                        }

                                        if($template_type!=NULL && $template_type->status=='enable')
                                        {
                                            $pdf = PDF::loadView('admin.candidates.pdf.report-template3', compact('data','report_items','jaf','candidate','report_data','parent_id','business_id','user_id'),[],[
                                                'title' => 'Report',
                                                'margin_top' => 20,
                                                'margin-header'=>20,
                                                'margin_bottom' =>25,
                                                'margin_footer'=>5,
                                                
                                            ])->save($report_path.$file_name);
                                        }
                                        else
                                        {
                                            $pdf = PDF::loadView('admin.candidates.pdf.report', compact('report_data','report_items','data','jaf','candidate','parent_id','business_id','user_id'),[],[
                                            'title' => 'Report',
                                            'margin_top' => 20,
                                            'margin-header'=>20,
                                            'margin_bottom' =>25,
                                            'margin_footer'=>5,
                                            
                                            ])->save($report_path.$file_name); 
                                        }

                                        $path = $report_path.$file_name;
                                        
                                        $zip->addFile($path, '/reports/'.basename($path));  
                                    }

                                    $zip->close();

                                    if (File::exists($report_path)) 
                                    {
                                        File::cleanDirectory($report_path);
                                    }

                                    $zip_id=DB::table('zip_logs')->insertGetId([
                                        'parent_id' => $parent_id,
                                        'business_id'  => $business_id,
                                        'user_id'     =>  $user_id,
                                        'report_id'   =>  count($report_array)>0?json_encode($report_array):NULL,
                                        'candidate_id'  => count($candidate_array)>0?json_encode($candidate_array):NULL,
                                        'zip_name' => $report_zipname!=""?$report_zipname:NULL,
                                        'created_at'    =>  date('Y-m-d H:i:s'),
                                        'updated_at'    =>  date('Y-m-d H:i:s')
                                    ]);

                                    DB::table('notification_logs')->where(['id'=>$notify_id])->update([
                                        'record_status' => 'found',
                                        'file_name'     => $report_zipname!=""?$report_zipname:NULL,
                                        'updated_at' => date('Y-m-d H:i:s')
                                    ]);

                                    DB::table('notifications')->insert(
                                        [
                                            'parent_id' => $parent_id,
                                            'business_id' => $business_id,
                                            'user_id' => $user_id,
                                            'title' => 'Report Share Notification',
                                            'message' => 'You have Receive the report Record, Please checkout the details for further Updates.',
                                            'module_id' => $notify_id,
                                            'created_by'   => $parent_id,
                                            'module_type' => 'notification_logs',
                                            'created_at' => date('Y-m-d H:i:s')
                                        ]
                                    );
        
                                    $report_log_data = DB::table('notification_logs')->where(['id'=>$notify_id])->first();
        
                                    $sender_d = DB::table('users')->where(['id'=>$parent_id])->first();
        
                                    foreach($notification_controls as $control)
                                    {
                                        $name = $control->name;
        
                                        $email = $control->email;
        
                                        $data=['name' =>$name,'email' => $email,'report_log'=>$report_log_data,'sender'=>$sender_d];
                    
                                        Mail::send(['html'=>'mails.report-share-notification'], $data, function($message) use($email,$name) {
                                            $message->to($email, $name)->subject
                                                ('myBCD System - Report Share Notification');
                                            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                        });
                                    }

                                }
                                else
                                {
                                    DB::table('notification_logs')->where(['id'=>$notify_id])->update([
                                        'record_status' => 'disable',
                                        'updated_at' => date('Y-m-d H:i:s')
                                    ]);
                                }

                            }
                            else
                            {
                                $data=[
                                    'business_id' => $client->id,
                                    'start_date' => date('Y-m-d H:i:s',strtotime($start_date)),
                                    'end_date' => date('Y-m-d H:i:s',strtotime($today_date)),
                                    'record_status' => 'not_found',
                                    'days' => $client->n_days,
                                    'module_type' => 'report_share_logs'
                                ];
            
                                $notify_id=DB::table('notification_logs')->insertGetId($data);
                            }
                        }
                    }
                    else
                    {
                        $reports=DB::table('reports')
                                ->where('business_id',$client->id)
                                ->where('status','<>','incomplete')
                                ->where('is_report_complete',1)
                                ->whereDate('report_complete_created_at','>=',$start_date)
                                ->whereDate('report_complete_created_at','<=',date('Y-m-d',strtotime($today_date.'-1 days')))
                                ->get();

                        if(count($reports)>0)
                        {
                            $data=[
                                'business_id' => $user->id,
                                'start_date' => date('Y-m-d H:i:s',strtotime($start_date)),
                                'end_date' => date('Y-m-d H:i:s',strtotime($today_date)),
                                'days' => $client->n_days,
                                'module_type' => 'report_share_logs'
                            ];
        
                            $notify_id=DB::table('notification_logs')->insertGetId($data);

                            if(count($notification_controls)>0)
                            {
                                $report_path = public_path().'/uploads/report-share-data/'.$user_id.'/'.'pdf/';

                                if (!File::exists($report_path)) {
                                    File::makeDirectory($report_path, $mode = 0777, true, true);
                                }

                                if (File::exists($report_path)) 
                                {
                                    File::cleanDirectory($report_path); 
                                }

                                $report_zipname = 'reports-'.date('Ymdhis').'-'.$client->display_id.'.zip';

                                $report_zip_path = public_path().'/uploads/report-share-data/'.$user_id.'/';

                                if (!File::exists($report_zip_path)) {

                                    File::makeDirectory($report_zip_path, $mode = 0777, true, true);
                                }

                                // if (File::exists($report_zip_path)) 
                                // {
                                //     File::cleanDirectory($report_zip_path); 
                                // }

                                $zip = new \ZipArchive();      
                                $zip->open($report_zip_path.$report_zipname, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                                $report_array=$candidate_array=[];

                                foreach($reports as $report)
                                {
                                    $data = [];
                    
                                    $pdf =new PDF;
                                    $report_items = DB::table('report_items as ri')
                                    ->select('ri.*','s.name as service_name','s.id as service_id','s.type_name')  
                                    ->join('services as s','s.id','=','ri.service_id')
                                    ->where(['ri.report_id'=>$report->id,'is_report_output'=>'1']) 
                                    ->orderBy('s.sort_number','asc')
                                    ->orderBy('ri.service_item_order','asc')
                                    ->get(); 

                                    // get candidate_id
                                    $report_data = DB::table('reports as r')->select('id','candidate_id','status','verifier_name','verifier_email','verifier_designation','generated_at','created_at','is_manual_mark','insuff_raised_date','insuff_cleared_date','initiated_date')->where(['id'=>$report->id])->first(); 
                                    // dd($report_data);
                                    $candidate = DB::table('candidate_reinitiates as u')
                                                ->select('u.id','u.created_at as initiated_date','u.display_id','u.business_id','u.client_emp_code','u.entity_code','u.first_name','u.last_name','u.name','u.email','u.phone','r.id as report_id','r.created_at','r.approval_status_id','r.sla_id','cs.title as sla_name','u.gender','u.dob','r.created_by','u.parent_id','r.revised_date','u.name','r.is_manual_mark','r.status as report_status','r.is_report_complete','r.report_complete_created_at')  
                                                ->leftjoin('reports as r','r.candidate_id','=','u.id')
                                                ->join('customer_sla as cs','cs.id','=','r.sla_id')
                                                ->where(['r.id'=>$report->id]) 
                                                ->first();

                                    $jaf = DB::table('jaf_form_data')->where(['candidate_id'=>$report_data->candidate_id])->first(); 

                                    $file_name = 'myBCD-Report-'.$candidate->id.'-'.date('Y-m-d').".pdf";

                                    // Check for Report File Renaming

                                    $customer = DB::table('user_businesses')
                                    ->where(['business_id'=>$candidate->business_id,'is_report_file_config'=>1])
                                    ->whereNotNull('report_file_config_details')
                                    ->first();

                                    $template_type = DB::table('report_add_page_statuses')->select('status')->where(['coc_id' => $candidate->business_id,'template_type'=>'3'])->first();

                                    if($customer!=NULL)
                                    {
                                        $file_detail = $customer->report_file_config_details;

                                        $file_detail_arr = json_decode($file_detail,true);

                                        if($file_detail_arr!=NULL && count($file_detail_arr)>0)
                                        {
                                            $file_name = '';

                                            asort($file_detail_arr);

                                            $i=0;

                                            $count = count($file_detail_arr);

                                            foreach($file_detail_arr as $key => $item)
                                            {
                                                if(stripos($key,'reference_no')!==false)
                                                {
                                                    $file_name.=$candidate->display_id;
                                                }
                                                else if(stripos($key,'emp_code')!==false)
                                                {
                                                    if($candidate->client_emp_code!='' && $candidate->client_emp_code!=null)
                                                        $file_name.=$candidate->client_emp_code;
                                                }
                                                else if(stripos($key,'candidate_name')!==false)
                                                {
                                                    $file_name.=$candidate->name;
                                                }
                                                else if(stripos($key,'status')!==false)
                                                {
                                                    $status = '';

                                                    if(stripos($candidate->report_status,'interim')!==false)
                                                    {
                                                        $status = 'Interim Report';
                                                    }
                                                    else if(stripos($candidate->report_status,'completed')!==false)
                                                    {
                                                        $status = 'Final Report';
                                                    }
                                                    
                                                    $file_name.=$status;
                                                }
                                                else if(stripos($key,'date')!==false)
                                                {
                                                    $file_name.=date('d-F-Y');
                                                }

                                                if(++$i!=$count)
                                                {
                                                    $file_name.=' - ';
                                                }
                                            }

                                            $file_name.='.pdf';
                                        }
                                    }

                                    if($template_type!=NULL && $template_type->status=='enable')
                                    {
                                        $pdf = PDF::loadView('admin.candidates.pdf.report-template3', compact('data','report_items','jaf','candidate','report_data','parent_id','business_id','user_id'),[],[
                                            'title' => 'Report',
                                            'margin_top' => 20,
                                            'margin-header'=>20,
                                            'margin_bottom' =>25,
                                            'margin_footer'=>5,
                                            
                                        ])->save($report_path.$file_name);
                                    }
                                    else
                                    {
                                        $pdf = PDF::loadView('admin.candidates.pdf.report', compact('report_data','report_items','data','jaf','candidate','parent_id','business_id','user_id'),[],[
                                        'title' => 'Report',
                                        'margin_top' => 20,
                                        'margin-header'=>20,
                                        'margin_bottom' =>25,
                                        'margin_footer'=>5,
                                        
                                        ])->save($report_path.$file_name); 
                                    }

                                    $path = $report_path.$file_name;
                                    
                                    $zip->addFile($path, '/reports/'.basename($path));  
                                }

                                $zip->close();

                                if (File::exists($report_path)) 
                                {
                                    File::cleanDirectory($report_path);
                                }

                                $zip_id=DB::table('zip_logs')->insertGetId([
                                    'parent_id' => $parent_id,
                                    'business_id'  => $business_id,
                                    'user_id'     =>  $user_id,
                                    'report_id'   =>  count($report_array)>0?json_encode($report_array):NULL,
                                    'candidate_id'  => count($candidate_array)>0?json_encode($candidate_array):NULL,
                                    'zip_name' => $report_zipname!=""?$report_zipname:NULL,
                                    'created_at'    =>  date('Y-m-d H:i:s'),
                                    'updated_at'    =>  date('Y-m-d H:i:s')
                                ]);

                                DB::table('notification_logs')->where(['id'=>$notify_id])->update([
                                    'record_status' => 'found',
                                    'file_name'     => $report_zipname!=""?$report_zipname:NULL,
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]);

                                DB::table('notifications')->insert(
                                    [
                                        'parent_id' => $parent_id,
                                        'business_id' => $business_id,
                                        'user_id' => $user_id,
                                        'title' => 'Report Share Notification',
                                        'message' => 'You have Receive the report Record, Please checkout the details for further Updates.',
                                        'module_id' => $notify_id,
                                        'created_by'   => $parent_id,
                                        'module_type' => 'notification_logs',
                                        'created_at' => date('Y-m-d H:i:s')
                                    ]
                                );

                                $report_log_data = DB::table('notification_logs')->where(['id'=>$notify_id])->first();

                                $sender_d = DB::table('users')->where(['id'=>$parent_id])->first();

                                foreach($notification_controls as $control)
                                {
                                    $name = $control->name;

                                    $email = $control->email;

                                    $data=['name' =>$name,'email' => $email,'report_log'=>$report_log_data,'sender'=>$sender_d];
                
                                    Mail::send(['html'=>'mails.report-share-notification'], $data, function($message) use($email,$name) {
                                        $message->to($email, $name)->subject
                                            ('myBCD System - Report Share Notification');
                                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                    });
                                }

                            }
                            else
                            {
                                DB::table('notification_logs')->where(['id'=>$notify_id])->update([
                                    'record_status' => 'disable',
                                    'updated_at' => date('Y-m-d H:i:s')
                                ]);
                            }

                        }
                        else
                        {
                            $data=[
                                'business_id' => $client->id,
                                'start_date' => date('Y-m-d H:i:s',strtotime($start_date)),
                                'end_date' => date('Y-m-d H:i:s',strtotime($today_date)),
                                'record_status' => 'not_found',
                                'days' => $client->n_days,
                                'module_type' => 'report_share_logs'
                            ];
        
                            $notify_id=DB::table('notification_logs')->insertGetId($data);
                        }
                    }

                }
                else
                {
                    $data=[
                        'business_id' => $client->id,
                        'start_date' => date('Y-m-d H:i:s',strtotime($start_date)),
                        'end_date' => date('Y-m-d H:i:s',strtotime($today_date)),
                        'record_status' => 'disable',
                        'days' => $client->n_days,
                        'module_type' => 'report_share_logs'
                    ];

                    $notify_id=DB::table('notification_logs')->insertGetId($data);
                }
                
            }

            $this->info('Notification for Report Mark as Completed Run Successfully at '.date('Y-m-d h:i A'));
        }
        return 0;
    }
}
