<?php

namespace App\Jobs\Admin\Candidate\Jaf;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;

class InsuffNotifyFrn implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $email;
    protected $name;

    protected $candidate_name;
    protected $display_id;
    protected $frnid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$email,$name,$candidate_name,$display_id,$frnid)
    {
        //
        $this->data = $data;
        $this->email = $email;
        $this->name = $name;

        $this->candidate_name = $candidate_name;
        $this->display_id = $display_id;
        $this->frnid = $frnid;
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

        $candidate_name = $this->candidate_name;
        $display_id = $this->display_id;
        $frnid = $this->frnid;

        Mail::send(['html'=>'mails.insuff-notify'], $data, function($message) use($email,$name,$candidate_name,$display_id,$frnid) {
            $message->to($email, $name)->subject
                ($frnid.''.$candidate_name. '-' .$display_id.'-Insufficiency Notification');
            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
        });
    }
}
