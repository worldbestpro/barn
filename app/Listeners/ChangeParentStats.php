<?php

namespace App\Listeners;

use App\Events\LitterParentWasChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChangeParentStats
{

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LitterParentWasChanged $event
     * @return void
     */
    public function handle(LitterParentWasChanged $event)
    {
        $parent    = \App::make($event->type);
        $oldParent = $parent->find($event->oldParent);
        $newParent = $parent->find($event->newParent);

        $oldParent->litters_count = $oldParent->litters_count - 1;
        $oldParent->kits -= $event->litter->kits_amount;
        $oldParent->live_kits -= $event->litter->rabbitKits()->count();
        $oldParent->update();

        $newParent->litters_count = $newParent->litters_count + 1;
        $newParent->kits += $event->litter->kits_amount;
        $newParent->live_kits += $event->litter->rabbitKits()->count();
        $newParent->update();


    }
}
