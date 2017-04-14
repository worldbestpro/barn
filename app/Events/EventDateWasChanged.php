<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EventDateWasChanged extends Event
{
    use SerializesModels;
    public $oldDate;
    public $newDate;
    public $user;

    /**
     * EventDateWasChanged constructor.
     * @param $oldDate
     * @param $newDate
     * @param $user
     */
    public function __construct($oldDate, $newDate, $user)
    {
        $this->oldDate = $oldDate;
        $this->newDate = $newDate;
        $this->user    = $user;
    }
}