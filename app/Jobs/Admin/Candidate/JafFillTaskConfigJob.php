<?php

namespace App\Jobs\Admin\Candidate;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Mail;

class JafFillTaskConfigJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $email;
    protected $name;
    protected $reference_number;

    protected $mail;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    
    public function __construct($data,$email,$name,$reference_number,$mail)
    {
        //
        $this->data = $data;
        $this->email = $email;
        $this->name = $name;

        $this->reference_number = $reference_number;

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

        $reference_number = $this->reference_number;

        $mail = $this->mail;

         Mail::send(['html'=>'mails.task-notify'], $data, function($message) use($email,$name,$mail,$reference_number) {
                $message->to($email, $name)->subject
                ('myBCD System - Notification for JAF Filling Task ('.$reference_number.')');
                $message->from($mail['from']['address'],$mail['from']['name']);
        });
    }
}
