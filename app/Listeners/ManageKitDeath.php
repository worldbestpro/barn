<?php

namespace App\Listeners;

use App\Events\KitWasDied;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManageKitDeath
{

    public function __construct()
    {
        //
    }


    public function updateLitterStats(KitWasDied $event)
    {
        $litter = $event->litter;
        $litter->kits_died += 1;
        $litter->survival_rate = ($litter->kits_amount - $litter->kits_died) / $litter->kits_amount * 100;
        $litter->updateWeights();

        $litter->update();


    }

    public function updateBreederStats(KitWasDied $event)
    {
        foreach ($event->litter->parents as $parent) {
            $parent->live_kits -= 1;
            $parent->update();
        }
    }
}
