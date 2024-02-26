<?php

namespace App\Jobs\Admin\Candidate\Jaf;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;

class InsuffClearNotifyConfig implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $email;
    protected $name;

    protected $mail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$email,$name,$mail)
    {
        //
        $this->data = $data;
        $this->email = $email;
        $this->name = $name;

        $this->mail = $mail;
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

        $mail = $this->mail;

        Mail::send(['html'=>'mails.insuff-clear-notify'], $data, function($message) use($email,$name,$mail) {
            $message->to($email, $name)->subject
            ('myBCD System - Insufficiency Notification ');
            $message->from($mail['from']['address'],$mail['from']['name']);
        });
    }
}
