<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EventHasGone extends Event
{
    use SerializesModels;
    public $event;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param $event
     * @param $user
     */
    public function __construct($event,$user)
    {
        $this->event = $event;
        $this->user = $user;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
