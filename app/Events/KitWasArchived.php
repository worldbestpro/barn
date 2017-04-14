<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class KitWasArchived extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $kit;
    /**
     * @var
     */
    public $type;
    public $litter;

    /**
     * Create a new event instance.
     *
     * @param $kit
     * @param $type
     */
    public function __construct($kit, $type)
    {
        $this->kit    = $kit;
        $this->type   = $type;
        $this->litter = $kit->litter;
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
