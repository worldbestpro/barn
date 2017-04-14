<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RabbitBreederWasArchived extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $breeder;

    /**
     * Create a new event instance.
     *
     * @param $breeder
     */
    public function __construct($breeder)
    {
        $this->breeder = $breeder;
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
