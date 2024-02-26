<?php

namespace App\Jobs\Admin\Candidate\Jaf;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;

class InsuffClearNotifyFrn implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $email;
    protected $name;

    protected $candidate_name;
    protected $reference_number;
    protected $frnid;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$email,$name,$candidate_name,$reference_number,$frnid)
    {
        //
        $this->data = $data;
        $this->email = $email;
        $this->name = $name;

        $this->candidate_name = $candidate_name;
        $this->reference_number = $reference_number;
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
        $reference_number = $this->reference_number;
        $frnid = $this->frnid;

        Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name,$candidate_name,$reference_number,$frnid) {
            $message->to($email, $name)->subject
                ($frnid.''.$candidate_name . '-' . $reference_number,'-Insuff Cleared Notification');
            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
        });
    }
}
