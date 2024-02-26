<?php

namespace App\Jobs\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\NotificationControl;
use App\Models\NotificationControlConfig;
use App\User;
use App\Models\Admin\UserBusiness;
use Illuminate\Support\Facades\Auth;

class SendMailCandidateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $data;
    protected $email;
    protected $name;
    protected $candidate;
    // protected $company;
    // protected $business_id;

    public function __construct($data,$email,$name,$candidate,$company)
    {
        //
        $this->data = $data;
        $this->email = $email;
        $this->name = $name;
        $this->candidate = $candidate;
        // $this->company = $company;
        // $this->business_id = $business_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $data = $this->data;
        $email = $this->email;
        $name = $this->name;

        $candidate=$this->candidate;

        // $company=$this->company;

        $company = UserBusiness::select('company_name','business_id')->where(['business_id'=>$candidate->business_id])->first();

        // $business_id=$this->business_id;

        Mail::send(['html'=>'mails.jaf_info_credential-candidate'], $data, function($message) use($email,$name) {
            $message->to($email, $name)->subject
                ('myBCD System - Your account credential');
            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
        });

      // Send Email to Customer for JAF Link to Candidate
      // $client = User::where('id',$id)->first();

      // $email = $client->email;
      // $name = $client->first_name;

      $notification_controls = NotificationControlConfig::from('notification_control_configs as nc')
                                  ->select('nc.*')
                                  ->join('notification_controls as n','n.business_id','=','nc.business_id')
                                  ->where(['n.status'=>1,'nc.status'=>1,'n.business_id'=>$candidate->business_id,'n.type'=>'jaf-sent-to-candidate','nc.type'=>'jaf-sent-to-candidate'])
                                  ->get();

      if(count($notification_controls)>0)
      {
        foreach($notification_controls as $item)
        {
          
          $email = $item->email;
          $name = $item->name;
          $company_name=$company->company_name;
          $sender = User::where('id',$item->parent_id)->first();
          
          $msg = 'Notification for Job Application Form Verifications to Candidate ('.$candidate->name.' - '.$candidate->display_id.') Has Been Resent at '.date('d-M-y h:i A').'';
          $data  = array('name'=>$name,'email'=>$email,'company_name'=>$company_name,'sender'=>$sender,'msg'=>$msg);

          Mail::send(['html'=>'mails.jaf-link'], $data, function($message) use($email,$name) {
              $message->to($email, $name)->subject
                  ('myBCD System - Your account credential');
              $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
          });

        }
      }

    }
}
