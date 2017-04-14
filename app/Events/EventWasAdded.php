<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EventWasAdded extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $user;
    /**
     * @var
     */
    public $event;

    /**
     * Create a new event instance.
     *
     * @param $event
     * @param $user
     */
    public function __construct($event,$user)
    {
        $this->user = $user;
        $this->event = $event;
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
