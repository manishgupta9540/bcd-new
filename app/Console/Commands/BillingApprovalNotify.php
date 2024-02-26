<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Helpers\Helper;

class BillingApprovalNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billingApproval:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends the billing approval notification, Where a COC does not perform any action about the billing review';

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
        $users= DB::table('users')->where('user_type','customer')->get();

        foreach ($users as $user)
        {
            $billings = DB::table('billings')->where(['parent_id'=>$user->id])->get();

            if(count($billings)>0)
            {
                foreach($billings as $billing)
                {
                    if(stripos($billing->status,'under_review')!==false)
                    {
                        $today_date = date('Y-m-d');

                        $billing_approval=DB::table('billing_approvals')->where(['billing_id'=>$billing->id])->latest()->first();

                        if($billing_approval!=NULL)
                        {
                            if($billing_approval->request_cancel_by==NULL && ($billing_approval->request_approve_by_cust_id==NULL || $billing_approval->request_approve_by_coc_id==NULL))
                            {
                                $request_date = date('Y-m-d',strtotime($billing_approval->request_sent_at));

                                $client_business = DB::table('user_businesses')
                                                        ->select('bill_action_notify_days as no_of_days')
                                                        ->where('business_id',$billing->business_id)
                                                        ->first();

                                $request_end_date = date('Y-m-d',strtotime($billing_approval->request_sent_at.'+'.$client_business->no_of_days.' weekdays'));

                                if(strtotime($today_date) > strtotime($request_end_date))
                                {
                                    $user_d=DB::table('users')->where(['id'=>$billing->business_id])->first();

                                    $billing_data = DB::table('billings')->where(['id'=>$billing->id])->first();

                                    $name=$user_d->name;

                                    $email=$user_d->email;

                                    $business_id = $user_d->parent_id;

                                    $sender = DB::table('users')->where(['id'=>$business_id])->first();
                            
                                    $data=['name' =>$name,'email' => $email,'user'=>$user_d,'billing'=>$billing_data,'sender'=>$sender];
                            
                                    Mail::send(['html'=>'mails.billing-approval'], $data, function($message) use($email,$name) {
                                        $message->to($email, $name)->subject
                                            ('myBCD System - Billing System Notification');
                                        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
                                    });

                                    DB::table('notifications')->insert([
                                        'parent_id' => $user_d->parent_id,
                                        'business_id' => $user_d->business_id,
                                        'user_id' => $user_d->id,
                                        'title'    => 'Billing Summary',
                                        'message'   => 'You have a pending approval of Billing, that sent by '.Helper::company_name($user_d->parent_id).'. It seems like you have to review once. then do the required action. If you have done already ignore it.',
                                        'created_by'   => $user_d->parent_id,
                                        'module_id'     => $billing->id,
                                        'module_type'   => 'billing',
                                        'created_at'    => date('Y-m-d H:i:s')
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        return 0;
    }
}
