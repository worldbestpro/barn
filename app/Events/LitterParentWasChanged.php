<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LitterParentWasChanged extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $litter;
    /**
     * @var
     */
    public $oldParent;
    /**
     * @var
     */
    public $newParent;
    /**
     * @var
     */
    public $type;

    /**
     * Create a new event instance.
     *
     * @param $litter
     * @param $oldParent
     * @param $newParent
     * @param $type
     */
    public function __construct($litter, $oldParent, $newParent, $type)
    {
        $this->litter    = $litter;
        $this->oldParent = $oldParent;
        $this->newParent = $newParent;
        $this->type      = $type;
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
