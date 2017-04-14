<?php

namespace App\Listeners;

use App\Events\KitWasDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManageKitDeletion
{

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  KitWasDeleted  $event
     * @return void
     */
    public function updateLitterStats(KitWasDeleted $event)
    {
        $litter = $event->litter;
        $litter->kits_amount -= 1;
        $litter->survival_rate = ($litter->kits_amount - $litter->kits_died) / $litter->kits_amount * 100;
        $litter->updateWeights();

        $litter->update();
    }
    public function updateBreederStats(KitWasDeleted $event)
    {
        foreach ($event->litter->parents as $parent) {//TODO change in case of multiple animal type
            $parent->live_kits -= 1;
            $parent->kits -= 1;
            $parent->update();
        }
    }
}
