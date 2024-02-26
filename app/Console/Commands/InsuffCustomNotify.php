<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Models\Admin\CandidateReinitiate;
use App\Models\Admin\JafFormData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Auth;


class InsuffCustomNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insuffcustom:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send an email to the Candidate & its COC's whose allowed to get the insuff notification & link";

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
        $insuff_data = DB::table('candidate_insuff_data as ci')
                        ->where('status','0')
                       ->get()->pluck('candidate_id')->all();

        $candidates = DB::table('candidate_reinitiates as u')
                        ->select('u.*','j.business_id as coc_id','j.id as jaf_id','v.created_at as insuff_date','v.created_by as insuff_by','v.updated_by','v.updated_at','v.item_number','v.notes','s.verification_type','s.name as service_name','v.business_id as cust_id','v.attachment')
                        ->join('jaf_form_data as j','u.id','=','j.candidate_id')
                        ->join('verification_insufficiency as v','v.jaf_form_data_id','=','j.id')
                        ->join('services as s','s.id','=','v.service_id')
                        ->where(['u.id'=>$insuff_data,'u.status'=>1])->get(); 
        // dd($candidates);
        
        if(count($candidates)>0)
        {
            foreach($candidates as $candidate)
            {
            
                $jaf_insuff = JafFormData::from('jaf_form_data as jf')
                            ->distinct('l.jaf_form_data_id')
                            ->select('jf.id','jf.service_id','l.id as log_id','jf.candidate_id')
                            ->join('insufficiency_logs as l','l.jaf_form_data_id','=','jf.id')
                            ->where('jf.candidate_id',$candidate->id)
                            ->where('l.status','raised')
                            ->get();
               // dd($jaf_insuff);

                $candidate_insuff = DB::table('candidate_insuff_data as ci')
                                    ->select('ci.*')
                                    ->where(['candidate_id'=>$candidate->id,'status'=>'0'])
                                    ->latest()
                                    ->first();
                //dd($candidate_insuff);   
                $candidate_logs = DB::table('candidate_insuff_data_logs')
                                    ->where(['candidate_id'=>$candidate->id,'status'=>'1'])
                                    ->count();

                $daysfollow = $candidate_insuff->days_follow_up;                     
                
                if($candidate_logs <= $daysfollow){
                    if($candidate_logs == 0 ){

                            $email = $candidate->email;
                            $name = $candidate->first_name;
                            
                            $msg = 'Insufficiency Clear For Candidate';                  

                            $url=url('/candidateInsuffForm',['id'=>base64_encode($candidate->id)]);
                        
                            $sender = User::from('users')->where(['id'=>$candidate->business_id])->first();
                            
                            $candidate_data = CandidateReinitiate::from('candidate_reinitiates')->where(['id'=>$candidate->id])->first();

                            $data = array('name'=>$name,'email'=>$email,'sender'=>$sender,'link'=>$url,'msg'=>$msg,'candidate'=>$candidate,'jaf_insuff' => $jaf_insuff,);

                            Mail::send(['html'=>'mails.insuff-notify-data'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('myBCD System - Insufficiency Notification');
                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            });

                            $dayscount = $candidate_insuff->days;
                            $daysfollow = $candidate_insuff->days_follow_up; 
                        

                            DB::table('candidate_insuff_data_logs')->insertGetId([
                                'business_id' => $candidate->business_id,
                                'candidate_id' => $candidate->id,
                                'start_date' => Carbon::now()->format('Y-m-d H:i:s'),
                                'end_date' => Carbon::now()->addDays($dayscount),
                                'days'     =>  $dayscount,
                                'days_follow_up' => $daysfollow,
                                'jaf_id'   =>   $candidate_insuff->jaf_id,
                                'status'   => '1',
                                'created_at' => date('Y-m-d H:i:s')
                            ]);
                        
                    }
                    else
                    {
                    
                        $startendDate = DB::table('candidate_insuff_data_logs')->select('start_date','end_date')->where(['candidate_id'=>$candidate->id,'status'=>'1'])->first();
                        $enddate =  date('Y-m-d', strtotime($startendDate->end_date));

                        $today = Carbon::now()->format('Y-m-d');
                    
                        if($startendDate && $enddate  == $today)
                        {
                            $email = $candidate->email;
                            $name = $candidate->first_name;

                            $url=url('/candidateInsuffForm',['id'=>base64_encode($candidate->id)]);

                            $msg = 'Insufficiency Clear For Candidate';                  

                            $sender = User::from('users')->where(['id'=>$candidate->business_id])->first();

                            $candidate_data = CandidateReinitiate::from('candidate_reinitiates')->where(['id'=>$candidate->id])->first();

                            $data = array('name'=>$name,'email'=>$email,'sender'=>$sender,'link'=>$url,'jaf_insuff' => $jaf_insuff,'msg'=>$msg,'candidate'=>$candidate);

                            Mail::send(['html'=>'mails.insuff-notify-data'], $data, function($message) use($email,$name) {
                                $message->to($email, $name)->subject
                                    ('myBCD System - Insufficiency Notification');
                                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                            });

                            $dayscount = $candidate_insuff->days; 
                            $daysfollow = $candidate_insuff->days_follow_up;
                        
                            DB::table('candidate_insuff_data_logs')->insertGetId([
                                'business_id' => $candidate->business_id,
                                'candidate_id' => $candidate->id,
                                'start_date' => Carbon::now()->format('Y-m-d'),
                                'end_date' => Carbon::now()->addDays($dayscount),
                                'days'     =>  $dayscount,
                                'days_follow_up' => $daysfollow,
                                'jaf_id'   =>   $candidate_insuff->jaf_id,
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
}
