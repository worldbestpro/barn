<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class KitWasWeighed extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $kit;

    /**
     * Create a new event instance.
     *
     * @param $kit
     */
    public function __construct($kit)
    {
        $this->kit = $kit;
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
