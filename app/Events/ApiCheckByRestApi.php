<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApiCheckByRestApi
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $jfd_service = [];
    public $userID;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Array $jfd_service, $userID)
    {
        $this->jfd_service = $jfd_service;
        $this->userID = $userID;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
