<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\CandidateReinitiate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\User;
use Carbon\Carbon;
use Auth;

class SendLinkSmsSetting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendlink:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a Mail Notification , Regarding Cases & document form not submit';

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
        $send_link = DB::table('send_link_settings')
                    ->where('status','1')
                    ->get();
                    
      
        if(count($send_link)>0)
        {
            foreach($send_link as $send)
            {
                $business_Id = $send->business_id;
                
                $candidates = CandidateReinitiate::from('candidate_reinitiates as u')
                            ->select('u.*')
                            ->orderBy('id','desc')
                            ->where(['u.business_id'=>$business_Id,'u.status'=>1,'u.user_type'=>'candidate','u.is_deleted'=>0])
                            ->get();
        
                    foreach ($candidates as $candidate) {
                      
                        $send_link = DB::table('candidate_documents')
                                        ->where(['candidate_id'=>$candidate->id,'status'=>'0'])
                                        ->exists();
                        // dd($send_link);
                        if($send_link)
                        {
                            $setting_logs = DB::table('send_link_setting_logs')
                                    ->where(['candidate_id'=>$candidate->id,'status'=>'1'])
                                    ->count();
                        
                            $daysfollow = $send->days_follow_up;
                        
                            if($setting_logs <= $daysfollow){
                                if($setting_logs == 0 ){

                                    $email = $candidate->email;
                                    $name = $candidate->first_name;

                                    $name = $candidate->name;
                                    $display_id = $candidate->display_id;
                                    $frnid = $candidate->frnid ? $candidate->frnid.'-' : '';
                                    $date=$candidate->created_at;

                                    $url = url('/candidateDocumentForm',['id'=>base64_encode($candidate->id)]);

                                    $msg = "Address Proof Required";
                                    $sender = User::from('users')->where(['id'=>$business_Id])->first();
                                    $data  = array('name'=>$name,'email'=>$email,'link'=>$url,'date'=>$date,'msg'=>$msg,'sender'=>$sender);
                                    Mail::send(['html'=>'mails.send-link-form'], $data, function($message) use($email,$name,$display_id,$frnid) {
                                        $message->to($email, $name)->subject
                                        ($frnid.''.$name. '-' .$display_id.'-Addres Proof Required');
                                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                    });


                                    $dayscount = $send->days;
                                    $daysfollow = $send->days_follow_up; 
                                
        
                                    DB::table('send_link_setting_logs')->insertGetId([
                                        'business_id' => $business_Id,
                                        'candidate_id' => $candidate->id,
                                        'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
                                        'end_date' => Carbon::now()->addDays($dayscount),
                                        'days'     =>  $dayscount,
                                        'days_follow_up' => $daysfollow,
                                        'status'   => '1',
                                        'created_at' => date('Y-m-d H:i:s')
                                    ]);
                                }
                                else
                                {
                                    
                                    $startendDate = DB::table('send_link_setting_logs')->select('start_date','end_date')->where(['candidate_id'=>$candidate->id,'status'=>'1'])->first();
                                    $enddate =  date('Y-m-d', strtotime($startendDate->end_date));
                                    
                                    $today = Carbon::now()->format('Y-m-d');

                                    if($startendDate && $enddate  == $today)
                                    {
                                        $email = $candidate->email;
                                        $name = $candidate->first_name;

                                        $name = $candidate->name;
                                        $display_id = $candidate->display_id;
                                        $frnid = $candidate->frnid ? $candidate->frnid.'-' : '';
                                        $date=$candidate->created_at;

                                        $url = url('/candidateDocumentForm',['id'=>base64_encode($candidate->id)]);

                                        $msg = "Address Proof Required";
                                        $sender = User::from('users')->where(['id'=>$business_Id])->first();
                                        $data  = array('name'=>$name,'email'=>$email,'link'=>$url,'date'=>$date,'msg'=>$msg,'sender'=>$sender);
                                        Mail::send(['html'=>'mails.send-link-form'], $data, function($message) use($email,$name,$display_id,$frnid) {
                                            $message->to($email, $name)->subject
                                            ($frnid.''.$name. '-' .$display_id.'-Addres Proof Required');
                                            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                        });

                                        $dayscount = $send->days;
                                        $daysfollow = $send->days_follow_up; 
                                    
                                        DB::table('send_link_setting_logs')->insertGetId([
                                            'business_id' => $business_Id,
                                            'candidate_id' => $candidate->id,
                                            'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
                                            'end_date' => Carbon::now()->addDays($dayscount),
                                            'days'     =>  $dayscount,
                                            'days_follow_up' => $daysfollow,
                                            'status'   => '1',
                                            'created_at' => date('Y-m-d H:i:s')
                                        ]);
                                    }
                                }
                            }
                        }   
                          
                    
                    }   

                    return 0;                 
            }
            
            // $this->info('Notification for Send Link Created Successfully at '.date('Y-m-d h:i A'));
            
        } 
       
    }
}
