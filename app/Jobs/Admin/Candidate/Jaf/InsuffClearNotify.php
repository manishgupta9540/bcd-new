<?php

namespace App\Jobs\Admin\Candidate\Jaf;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;

class InsuffClearNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $email;
    protected $name;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$email,$name)
    {
        //

        $this->data = $data;
        $this->email = $email;
        $this->name = $name;
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

        Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name) {
            $message->to($email, $name)->subject
                ('myBCD System - Insufficiency Notification');
            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
        });
    }
}
