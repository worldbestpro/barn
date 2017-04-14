<?php

namespace App\Listeners;

use App\Events\KitWasArchived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManageKitArchive
{

    public function __construct()
    {
        //
    }


    public function updateLitterStats(KitWasArchived $event)
    {
        $litter = $event->litter;
        //$litter->kits_amount -= 1;
        $litter->survival_rate = ($litter->kits_amount - $litter->kits_died) / $litter->kits_amount * 100;
        $litter->updateWeights();

        $litter->update();


    }

    public function updateBreederStats(KitWasArchived $event)
    {
        foreach ($event->litter->parents as $parent) {
            if($event->kit->archived == 1){
                $parent->live_kits -= 1;
            } else {
                $parent->live_kits += 1;
            }
            $parent->update();
        }
    }
}
