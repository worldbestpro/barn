<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LitterWasDeleted extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $litter;
    /**
     * @var
     */
    public $type;

    /**
     * Create a new event instance.
     *
     * @param $litter
     * @param $type
     */
    public function __construct($litter,$type)
    {
        $this->litter = $litter;
        $this->type = $type;
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
