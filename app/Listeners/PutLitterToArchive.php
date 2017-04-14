<?php

namespace App\Listeners;

use App\Events\LitterWasArchived;
use App\Events\RabbitBreederWasArchived;
use App\Repositories\LitterRepository;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PutLitterToArchive
{
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  RabbitBreederWasArchived $event
     * @return void
     */
    public function handle(RabbitBreederWasArchived $event)
    {
        $litters = $event->breeder->litters()->get();
        foreach ($litters as $litter) {
            if ($litter->archivedParents->count() == 2) {
                $litter->archived =1;
                $litter->save();
                event(new LitterWasArchived($litter,'rabbit'));
            }
        }
    }
}
