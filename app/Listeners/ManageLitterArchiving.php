<?php

namespace App\Listeners;

use App\Events\LitterWasArchived;
use App\Events\LitterWasDeleted;
use App\Events\LitterWasUnarchived;
use App\Models\BreedPlan;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManageLitterArchiving
{

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LitterWasArchived  $event
     * @return void
     */
    public function archivePlan(LitterWasArchived $event)
    {
        /* @var BreedPlan $plan */
        $plan = $event->litter->plans()->first();

        if($plan){
            foreach($plan->events as $event){
                $event->archived = 1;
                $event->update();
            }
        }
    }

    public function unarchivePlan(LitterWasUnarchived $event)
    {
        /* @var BreedPlan $plan */
        $plan = $event->litter->plans()->first();

        if($plan){
            foreach($plan->events as $event){
                $event->archived = 0;
                $event->update();
            }
        }
    }
}
