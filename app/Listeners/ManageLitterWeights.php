<?php

namespace App\Listeners;

use App\Events\KitWasWeighed;
use App\Events\LitterWasWeighed;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManageLitterWeights
{
    /**
     * @var Event
     */
    private $event;

    /**
     * Create the event listener.
     *
     * @param Event $event
     */
    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
     * Handle the event.
     *
     * @param  KitWasWeighed $event
     * @return void
     */
    public function byKitWeight(KitWasWeighed $event)
    {
        $litter = $event->kit->litter;
        $litter->updateWeights();
        $litter->update();
    }

    public function byLitterWeight(LitterWasWeighed $event)
    {
        $event->litter->updateWeights();
        $event->litter->update();

        $weighs = $event->litter->weighs()->count();

        $plannedEvent = $event->litter->events()->whereNotNull('breed_id')->where('subtype', 'weigh')->first();

        if ($plannedEvent) {
            $plannedEvent->date   = $event->date;
            $plannedEvent->closed = 1;
            $plannedEvent->update();
        } else {
            $this->event->type    = 'litter';
            $this->event->name    = 'weigh' . ($weighs + 1);
            $this->event->subtype = 'weigh';
            $this->event->closed  = 1;
            $this->event->date    = $event->date;
            $this->event->save();
            $event->litter->events()->attach($this->event);
        }
    }
}
