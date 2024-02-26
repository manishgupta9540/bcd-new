<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Mail;
use App\Models\Admin\CandidateReinitiate;
use Carbon\Carbon;

class DailyNotificationControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dailynotification:control';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a Mail Notication to COC & its Candidate for JAF Not Filled & Insufficiency Raised';

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
        // Notification for JAF Not Filled

        $clients = User::from('users as u')
                        ->select('u.*','n.reminder_count','n.id as nid')
                        ->join('notification_controls as n','u.id','=','n.business_id')
                        ->where('u.user_type','client')
                        ->where(['u.status'=>1,'n.type'=>'jaf-not-filled','n.status'=>1])
                        ->get();
      
        if(count($clients)>0)
        {
            foreach($clients as $client)
            {
                $parent_id = $client->parent_id;
                $business_id = $client->business_id;
                $reminder  = $client->reminder_count;
                $clientId = $client->nid;
    
                 
                $notification_controls = DB::table('notification_control_configs as nc')
                                        ->select('nc.*')
                                        ->join('notification_controls as n','n.business_id','=','nc.business_id')
                                        ->where(['n.status'=>1,'nc.status'=>1,'n.business_id'=>$client->business_id,'n.type'=>'jaf-not-filled','nc.type'=>'jaf-not-filled'])
                                        ->get();
                                      
                // dd($notification_controls);
                $remimder_logs = DB::table('notification_reminder_logs')
                                ->select('reminder_count')
                                ->where('notification_control_id',$client->nid)
                                ->count();
                               
                if($reminder == 0 || $remimder_logs == 0 || $remimder_logs <= $reminder )
                {
                    if(count($notification_controls)>0)
                    {
                        // $candidate_jaf = CandidateReinitiate::from('candidate_reinitiates as u')
                        //                     ->select('u.*')
                        //                     ->join('job_items as j','j.candidate_id','=','u.id')
                        //                     ->where(['u.user_type'=>'candidate','u.is_deleted'=>0,'u.business_id'=>$client->id])
                        //                     ->where('j.jaf_status','<>','filled')
                        //                     ->get();
                
                            // if(count($candidate_jaf)>0)
                            // {      
                            //     foreach($notification_controls as $item)
                            //     {
                            //         //jaf request email send multiple 

                            //         $email = $item->email;
                            //         $name = $item->name;
                                
                            //         $sender = User::from('users')->where(['id'=>$parent_id])->first();

                            //         $data = array('name'=>$name,'email'=>$email,'candidates'=>$candidate_jaf,'sender'=>$sender);
                                
                            //         Mail::send(['html'=>'mails.client-jaf-not-filled'], $data, function($message) use($email,$name) {
                            //             $message->to($email, $name)->subject
                            //                 ('BGV Form Not Filled');
                            //             $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            //         });

                            //         //kam mail send multiple
                            //         $kam = DB::table('key_account_managers as key')->where('business_id',$item->business_id)->get();
                                    
                            //         $cam1 = json_encode($kam);
                            //         $json_toArray = json_decode($cam1 ,true);
                            //         $array_ids = array_column($json_toArray, 'user_id');
                            //         $kamuser = User::whereIn('id',$array_ids)->get();
                                    
                            //         if(count($kamuser)>0)
                            //         {
                            //             foreach($kamuser as $item)
                            //             {
                            //                 $email = $item->email;
                            //                 $name = $item->name;
                                            
                            //                 $sender = User::from('users')->where(['id'=>$parent_id])->first();
        
                            //                 $data = array('name'=>$name,'email'=>$email,'candidates'=>$candidate_jaf,'sender'=>$sender);
                                        
                            //                 Mail::send(['html'=>'mails.client-multiple-attempts'], $data, function($message) use($email,$name) {
                            //                     $message->to($email, $name)->subject
                            //                         ('BGV Form Not Filled');
                            //                     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            //                 });
                            //             }  
                                        
                            //         } 
                            //     }     
                            // }
                       
                           
                        $candidate_jaf_link = CandidateReinitiate::from('candidate_reinitiates as u')
                                                    ->Distinct('ji.candidate_id')
                                                    ->select('u.*')
                                                    ->join('job_items as j','j.candidate_id','=','u.id')
                                                    ->join('job_sla_items as ji','j.id','=','ji.job_item_id')
                                                    ->where(['u.user_type'=>'candidate','u.is_deleted'=>0,'u.business_id'=>$client->id])
                                                    ->whereNotNull('u.email')
                                                    ->where('j.jaf_status','<>','filled')
                                                    ->where('ji.jaf_send_to','candidate')
                                                    ->groupBy('ji.candidate_id')
                                                    ->get();
                
                        if(count($candidate_jaf_link)>0)
                        {
                            foreach($candidate_jaf_link as $candidate)
                            {
                                //candidate email send
                                $email = $candidate->email;
                        
                                $name = $candidate->first_name;
                                $refrence = $candidate->display_id;
                                $frnid = $candidate->frnid ? $candidate->frnid.'-' : '';
                                
                                $msg = 'Kindly fill the Job Application Form that we have already been sent to you.';                  

                                $sender = User::from('users')->where(['id'=>$candidate->business_id])->first();

                                $candidate_data = CandidateReinitiate::from('candidate_reinitiates')->where(['id'=>$candidate->id])->first();

                                $data = array('name'=>$name,'email'=>$email,'sender'=>$sender,'msg'=>$msg,'candidate'=>$candidate_data);

                                Mail::send(['html'=>'mails.candidate-jaf-not-filled'], $data, function($message) use($email,$frnid,$name,$refrence) {
                                    $message->to($email, $name)->subject
                                        ($frnid.''.$name .'-'. $refrence.'-BGV Form Pending');
                                    $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                });

                                foreach ($notification_controls as $notification) {
                                    
                                    //jaf request email send multiple 
                                   $email =  $notification->email;
                                   $name =  $notification->name;
                                   
                                    $candidatename = $candidate->name;
                                    $refrence = $candidate->display_id;
                                    $frnid = $candidate->frnid ? $candidate->frnid.'-' : '';
                                    
                                    $msg = 'Kindly fill the Job Application Form that we have already been sent to you.';                  

                                    $sender = User::from('users')->where(['id'=>$candidate->business_id])->first();

                                    $candidate_data = CandidateReinitiate::from('candidate_reinitiates')->where(['id'=>$candidate->id])->first();

                                    $data = array('name'=>$name,'email'=>$email,'sender'=>$sender,'msg'=>$msg,'candidate'=>$candidate_data);

                                    Mail::send(['html'=>'mails.candidate-jaf-not-filled'], $data, function($message) use($name,$email,$frnid,$candidatename,$refrence) {
                                        $message->to($email, $name)->subject
                                            ($frnid.''.$candidatename .'-'. $refrence.'-BGV Form Pending');
                                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                    });

                                     //kam mail send multiple
                                    $kam = DB::table('key_account_managers as key')->where('business_id',$notification->business_id)->get();
                                    
                                    $candidate_jaf = CandidateReinitiate::from('candidate_reinitiates as u')
                                                        ->select('u.*')
                                                        ->join('job_items as j','j.candidate_id','=','u.id')
                                                        ->where(['u.user_type'=>'candidate','u.is_deleted'=>0,'u.business_id'=>$client->id])
                                                        ->where('j.jaf_status','<>','filled')
                                                        ->get();

                                    $cam1 = json_encode($kam);
                                    $json_toArray = json_decode($cam1 ,true);
                                    $array_ids = array_column($json_toArray, 'user_id');
                                    $kamuser = User::whereIn('id',$array_ids)->get();
                                    
                                    if(count($kamuser)>0)
                                    {
                                        foreach($kamuser as $item)
                                        {
                                            $email = $item->email;
                                            $name = $item->name;
                                            
                                            $sender = User::from('users')->where(['id'=>$parent_id])->first();
        
                                            $data = array('name'=>$name,'email'=>$email,'candidates'=>$candidate_jaf,'sender'=>$sender);
                                        
                                            Mail::send(['html'=>'mails.client-multiple-attempts'], $data, function($message) use($email,$name) {
                                                $message->to($email, $name)->subject
                                                    ('BGV Form Not Filled');
                                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                            });
                                        }  
                                        
                                    }

                                }
                            }
                        }
     

                        DB::table('notification_date_logs')->insertGetId([
                            'parent_id' => $parent_id,
                            'business_id' => $business_id,
                            'reminder_count' => $reminder,
                            'notification_control_id' => $clientId,
                            'start_date' => Carbon::now()->format('Y-m-d'),
                            'end_date' => Carbon::now()->addDays($reminder),
                            'type' => 'jaf-not-filled',
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
                else
                {
                
                    $startendDate = DB::table('notification_date_logs')->select('start_date','end_date')->where('notification_control_id',$client->nid)->first();
                    $today = Carbon::now()->format('Y-m-d');
                    
                    if($startendDate && $startendDate->end_date  == $today)
                    {
                    
                            $candidate_jaf_link = CandidateReinitiate::from('candidate_reinitiates as u')
                                                    ->Distinct('ji.candidate_id')
                                                    ->select('u.*')
                                                    ->join('job_items as j','j.candidate_id','=','u.id')
                                                    ->join('job_sla_items as ji','j.id','=','ji.job_item_id')
                                                    ->where(['u.user_type'=>'candidate','u.is_deleted'=>0,'u.business_id'=>$client->id])
                                                    ->whereNotNull('u.email')
                                                    ->where('j.jaf_status','<>','filled')
                                                    ->where('ji.jaf_send_to','candidate')
                                                    ->groupBy('ji.candidate_id')
                                                    ->get();

                            if(count($candidate_jaf_link)>0)
                            {
                                    foreach($candidate_jaf_link as $candidate)
                                    {
                                        $email = $candidate->email;

                                        $name = $candidate->name;
                                        $refrence = $candidate->display_id;
                                        $frnid = $candidate->frnid ? $candidate->frnid.'-' : '';
                                        
                                        $msg = 'Kindly fill the Job Application Form that we have already been sent to you.';                  

                                        $sender = User::from('users')->where(['id'=>$candidate->business_id])->first();

                                        $candidate_data = CandidateReinitiate::from('candidate_reinitiates')->where(['id'=>$candidate->id])->first();

                                        $data = array('name'=>$name,'email'=>$email,'sender'=>$sender,'msg'=>$msg,'candidate'=>$candidate_data);

                                        Mail::send(['html'=>'mails.candidate-jaf-not-filled'], $data, function($message) use($email,$frnid,$name,$refrence) {
                                            $message->to($email, $name)->subject
                                                ($frnid.''.$name .'-'. $refrence.'-BGV Form Pending');
                                            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                        });


                                        foreach ($notification_controls as $notification) {
                                    
                                            //jaf request email send multiple 
                                           $email =  $notification->email;
                                           $name =  $notification->name;
                                           
                                            $candidatename = $candidate->name;
                                            $refrence = $candidate->display_id;
                                            $frnid = $candidate->frnid ? $candidate->frnid.'-' : '';
                                            
                                            $msg = 'Kindly fill the Job Application Form that we have already been sent to you.';                  
        
                                            $sender = User::from('users')->where(['id'=>$candidate->business_id])->first();
        
                                            $candidate_data = CandidateReinitiate::from('candidate_reinitiates')->where(['id'=>$candidate->id])->first();
        
                                            $data = array('name'=>$name,'email'=>$email,'sender'=>$sender,'msg'=>$msg,'candidate'=>$candidate_data);
        
                                            Mail::send(['html'=>'mails.candidate-jaf-not-filled'], $data, function($message) use($name,$email,$frnid,$candidatename,$refrence) {
                                                $message->to($email, $name)->subject
                                                    ($frnid.''.$candidatename .'-'. $refrence.'-BGV Form Pending');
                                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                            });
        
                                             //kam mail send multiple
                                            $kam = DB::table('key_account_managers as key')->where('business_id',$notification->business_id)->get();
                                            
                                            $candidate_jaf = CandidateReinitiate::from('candidate_reinitiates as u')
                                                                    ->select('u.*')
                                                                    ->join('job_items as j','j.candidate_id','=','u.id')
                                                                    ->where(['u.user_type'=>'candidate','u.is_deleted'=>0,'u.business_id'=>$client->id])
                                                                    ->where('j.jaf_status','<>','filled')
                                                                    ->get();

                                            $cam1 = json_encode($kam);
                                            $json_toArray = json_decode($cam1 ,true);
                                            $array_ids = array_column($json_toArray, 'user_id');
                                            $kamuser = User::whereIn('id',$array_ids)->get();
                                            
                                            if(count($kamuser)>0)
                                            {
                                                foreach($kamuser as $item)
                                                {
                                                    $email = $item->email;
                                                    $name = $item->name;
                                                    
                                                    $sender = User::from('users')->where(['id'=>$parent_id])->first();
                
                                                    $data = array('name'=>$name,'email'=>$email,'candidates'=>$candidate_jaf,'sender'=>$sender);
                                                
                                                    Mail::send(['html'=>'mails.client-multiple-attempts'], $data, function($message) use($email,$name) {
                                                        $message->to($email, $name)->subject
                                                            ('BGV Form Not Filled');
                                                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                                    });
                                                }  
                                                
                                            }
        
                                        }


                                    }
                            }

                            // $candidate_jaf = CandidateReinitiate::from('candidate_reinitiates as u')
                            //                             ->select('u.*')
                            //                             ->join('job_items as j','j.candidate_id','=','u.id')
                            //                             ->where(['u.user_type'=>'candidate','u.is_deleted'=>0,'u.business_id'=>$client->id])
                            //                             ->where('j.jaf_status','<>','filled')
                            //                             ->get();

                            // if(count($candidate_jaf)>0)
                            // {
                            //     foreach($notification_controls as $item)
                            //     {
                            //         //jaf request email send multiple 
                            //         $email = $item->email;
                            //         $name = $item->name;
                                    
                            //         $sender = User::from('users')->where(['id'=>$parent_id])->first();

                            //         $data = array('name'=>$name,'email'=>$email,'candidates'=>$candidate_jaf,'sender'=>$sender);
                                
                            //         Mail::send(['html'=>'mails.client-multiple-attempts'], $data, function($message) use($email,$name) {
                            //             $message->to($email, $name)->subject
                            //                 ('BGV Form Not Filled');
                            //             $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            //         });
                                 
                            //         //kam mail send multiple
                                    
                            //         $kam = DB::table('key_account_managers as key')->where('business_id',$item->business_id)->get();
                                            
                            //         $cam1 = json_encode($kam);
                            //         $json_toArray = json_decode($cam1 ,true);
                            //         $array_ids = array_column($json_toArray, 'user_id');
                            //         $kamuser = User::whereIn('id',$array_ids)->get();
                                    
                            //         if(count($kamuser)>0)
                            //         {
                            //             foreach($kamuser as $item)
                            //             {
                            //                 $email = $item->email;
                            //                 $name = $item->name;
                                            
                            //                 $sender = User::from('users')->where(['id'=>$parent_id])->first();

                            //                 $data = array('name'=>$name,'email'=>$email,'candidates'=>$candidate_jaf,'sender'=>$sender);
                                        
                            //                 Mail::send(['html'=>'mails.client-multiple-attempts'], $data, function($message) use($email,$name) {
                            //                     $message->to($email, $name)->subject
                            //                         ('BGV Form Not Filled');
                            //                     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            //                 });
                            //             }  
                                        
                            //         }
                            //     } 
                            // }

                            DB::table('notification_date_logs')->insertGetId([
                                'parent_id' => $parent_id,
                                'business_id' => $business_id,
                                'reminder_count' => $reminder,
                                'notification_control_id' => $clientId,
                                'start_date' => Carbon::now()->format('Y-m-d'),
                                'end_date' => Carbon::now()->addDays($reminder),
                                'type' => 'jaf-not-filled',
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                    }
                   
                }

                
                
            }
            
            $this->info('Notification for JAF Not Filled Created Successfully at '.date('Y-m-d h:i A'));
        }

        
        // Notification for Insufficiency

        $clients = User::from('users as u')
                        ->select('u.*')
                        ->join('notification_controls as n','u.id','=','n.business_id')
                        ->where('u.user_type','client')
                        ->where(['u.status'=>1,'n.type'=>'case-insuff','n.status'=>1])
                        ->get();
        // dd($clients);
        if(count($clients)>0)
        {
            foreach($clients as $client)
            {

                // Send Email to Customer for Insufficiency
                $notification_controls = DB::table('notification_control_configs as nc')
                                        ->select('nc.*')
                                        ->join('notification_controls as n','n.business_id','=','nc.business_id')
                                        ->where(['n.status'=>1,'nc.status'=>1,'n.business_id'=>$client->business_id,'n.type'=>'case-insuff','nc.type'=>'case-insuff'])
                                        ->get();

                if(count($notification_controls)>0)
                {
                    
                    $parent_id = $client->parent_id;

                    $candidate_jaf = CandidateReinitiate::from('candidate_reinitiates as u')
                                        ->select('u.*',DB::raw('group_concat(DISTINCT jf.id) as jaf_id'))
                                        ->join('job_items as j','j.candidate_id','=','u.id')
                                        ->join('jaf_form_data as jf','jf.job_item_id','=','j.id')
                                        ->where(['u.user_type'=>'candidate','u.is_deleted'=>0,'u.business_id'=>$client->id,'jf.is_insufficiency'=>0])
                                        ->where('j.jaf_status','=','filled')
                                        ->whereDate('u.created_at','>=',date('Y-m-d',strtotime('19-04-2022')))
                                        ->groupBy('jf.candidate_id')
                                        ->get();

                    if(count($candidate_jaf)>0)
                    {
                        // $email = $client->email;
                        // $name = $client->first_name;

                        foreach($notification_controls as $item)
                        {
                            $email = $item->email;
                            $name = $item->name;
                            $sender = User::from('users')->where(['id'=>$parent_id])->first();

                            $data = array('name'=>$name,'email'=>$email,'candidates'=>$candidate_jaf,'sender'=>$sender);
    
                            Mail::send(['html'=>'mails.client-case-insuff'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('myBCD System - Insufficiency Notification');
                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            });
                        }
                        

                        foreach($candidate_jaf as $candidate)
                        {
                            $jaf_id  = [];

                            $jaf_id=explode(',',$candidate->jaf_id);

                            $email = $candidate->email;
                            
                            $name = $candidate->first_name;

                            $msg = 'Insufficiency has been Raised in your JAF Form';                  

                            $sender = User::from('users')->where(['id'=>$candidate->business_id])->first();

                            $candidate_data = CandidateReinitiate::from('candidate_reinitiates')->where(['id'=>$candidate->id])->first();

                            $data = array('name'=>$name,'email'=>$email,'sender'=>$sender,'msg'=>$msg,'candidate'=>$candidate_data,'jaf_id'=>$jaf_id);

                            Mail::send(['html'=>'mails.candidate-case-insuff'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('myBCD System - Insufficiency Notification');
                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            });


                        }


                    }

                }
                
            }

            $this->info('Notification for Insufficiency Created Successfully at '.date('Y-m-d h:i A'));
        }

        return 0;
    }
}
