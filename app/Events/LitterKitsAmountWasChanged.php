<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LitterKitsAmountWasChanged extends Event
{
    use SerializesModels;
    /**
     * @var
     */
    public $litter;
    public $type;
    public $parents;
    public $kitsDied;
    public $oldAmount;
    public $newAmount;

    /**
     * Create a new event instance.
     *
     * @param $litter
     * @param $oldAmount
     * @param $newAmount
     * @param $kitsDied
     * @param $type
     */
    public function __construct($litter, $kitsDied, $oldAmount, $newAmount, $type)
    {
        $this->litter = $litter;
        $this->oldAmount = $oldAmount;
        $this->newAmount = $newAmount;
        $this->kitsDied = $kitsDied;
        $this->type = $type;
        $this->parents = $this->litter->parents;
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
