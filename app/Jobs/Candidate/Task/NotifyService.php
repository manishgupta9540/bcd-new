<?php

namespace App\Jobs\Candidate\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $email;
    protected $name;
    protected $reference_number;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$email,$name,$reference_number)
    {
        //

        $this->data = $data;
        $this->email = $email;
        $this->name = $name;

        $this->reference_number = $reference_number;
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

        Mail::send(['html'=>'mails.task_notify_service'], $data, function($message) use($email,$name,$reference_number) {
            $message->to($email, $name)->subject
              ('myBCD System - Notification for JAF Filling Task ('.$reference_number.')');
            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
        });
    }
}
