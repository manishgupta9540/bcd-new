<?php

namespace App\Jobs\Client\Candidate;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;

class InsuffNotifyDataFrn implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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

        Mail::send(['html'=>'mails.client-insuff-notify-data'], $data, function($message) use($email,$name,$frnid,$reference_number,$candidate_name) {
            $message->to($email, $name)->subject
                ($frnid.'-'.$candidate_name . '-' . $reference_number.'- Raise Insuff');
            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
        });
    }
}
