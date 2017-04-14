<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EventHolderWasRenamed extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $holder;
    public $name;

    /**
     * Create a new event instance.
     *
     * @param $holder
     * @param $name
     */
    public function __construct($holder, $name)
    {
        $this->holder = $holder;
        $this->name = $name;
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
