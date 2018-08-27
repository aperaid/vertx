<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EventHappened implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $EventMessage;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($EventMessage)
    {
        $this->EventMessage = $EventMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['EventHappenedChannel'];
    }
}
