<?php


namespace App\Listeners;


use App\Events\LitterKitsAmountWasChanged;
use App\Events\LitterWasArchived;
use App\Events\LitterWasCreated;
use App\Events\LitterWasUnarchived;

class ManageBreedersKits
{
    public function __construct()
    {
        //
    }

    public function increaseCounts(LitterWasCreated $event)
    {
        foreach ($event->parents as $parent) {
            $parent->kits += $event->litter->kits_amount;
            $parent->live_kits += $event->litter->kits_amount;
            $parent->update();
        }

    }

    public function increaseCountsUnarchived(LitterWasUnarchived $event)
    {
        foreach ($event->parents as $parent) {
            $parent->kits += $event->litter->kits_amount;
            $parent->live_kits += $event->litter->kits_amount;
            $parent->update();
        }

    }


    public function decreaseCounts(LitterWasArchived $event)
    {
        foreach ($event->parents as $parent) {
            $parent->kits -= $event->litter->kits_amount;
            $parent->live_kits -= $event->litter->rabbitKits()->count();
            $parent->update();
        }
    }

    public function updateCounts(LitterKitsAmountWasChanged $event)
    {
        foreach ($event->parents as $parent) {
            $parent->kits -= $event->oldAmount - $event->newAmount;
            $parent->live_kits -= $event->oldAmount - $event->newAmount - $event->kitsDied;
            $parent->update();
        }
    }
}