<?php

namespace App\Events;

use App\Events\Event;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LitterWasWeighed extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $litter;
    /**
     * @var
     */
    public $date;

    /**
     * Create a new event instance.
     *
     * @param $litter
     * @param $date
     */
    public function __construct($litter, $date)
    {
        $this->litter = $litter;
        $this->date   = $date;
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
