<?php

namespace App\Listeners;

use App\Events\EventHolderWasRenamed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManageEventHolderRename
{

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  EventHolderWasRenamed  $event
     * @param  string  $name
     * @return void
     */
    public function renameEvents(EventHolderWasRenamed $event)
    {
        foreach($event->holder->events as $holderEvent){
            $holderEvent->holderName = $event->name;
            $holderEvent->update();
        }
    }

}
