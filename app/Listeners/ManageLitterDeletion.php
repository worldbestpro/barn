<?php

namespace App\Listeners;

use App\Events\LitterWasDeleted;
use App\Models\BreedPlan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManageLitterDeletion
{

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LitterWasDeleted  $event
     * @return void
     */
    public function deleteKits(LitterWasDeleted $event)
    {
        $event->litter->rabbitKits()->delete();
    }

    /**
     * Handle the event.
     *
     * @param  LitterWasDeleted  $event
     * @return void
     */
    public function deletePlan(LitterWasDeleted $event)
    {
        /* @var BreedPlan $plan */
        $event->litter->rabbitKits()->delete();
        $plan = $event->litter->plans()->first();
        if($plan){
            $plan->events()->delete();
            $plan->breeders()->detach();
            $plan->litters()->detach();
            $plan->delete();
        }

    }


    public function updateBreederStats(LitterWasDeleted $event)
    {
        foreach ($event->litter->parents as $parent) {//TODO change in case of multiple animal types
            $parent->litters_count = $parent->litters_count - 1;
            $parent->kits -= $event->litter->kits_amount;
            $parent->live_kits -= $event->litter->rabbitKits()->count();
            $parent->update();
        }
    }
}
