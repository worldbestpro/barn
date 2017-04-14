<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class KitsWereButched extends Event
{
    use SerializesModels;
    public $type;
    public $litter;
    public $amount;
    public $date;

    /**
     * Create a new event instance.
     *
     * @param $litter
     * @param $type
     * @param $amount
     * @param $date
     */
    public function __construct($litter,$type,$amount,$date)
    {
        $this->type = $type;
        $this->litter = $litter;
        $this->amount = $amount;
        $this->date = $date;
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
