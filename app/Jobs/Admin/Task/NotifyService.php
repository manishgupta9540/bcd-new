<?php

namespace App\Jobs\Admin\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;

class NotifyService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $email;
    protected $name;
    protected $reference_number;
    protected $candidate_name;
    protected $frn_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$email,$name,$candidate_name,$reference_number,$frn_id)
    {
        //
        $this->data = $data;
        $this->email = $email;
        $this->name = $name;

        $this->reference_number = $reference_number;

        $this->candidate_name = $candidate_name;

        $this->frn_id = $frn_id;

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

        $reference_number = $this->reference_number;

        $candidate_name = $this->candidate_name;

        $frn_id = $this->frn_id;

        Mail::send(['html'=>'mails.task-notify-service'], $data, function($message) use($email,$name, $candidate_name,$reference_number,$frnid) {
                $message->to($email, $name)->subject
                ($frnid.''.$candidate_name. '-' .$reference_number.'-Notification for JAF Filling Task');
                $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
        });
    }
}
