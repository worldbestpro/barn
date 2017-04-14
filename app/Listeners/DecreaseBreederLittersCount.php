<?php

namespace App\Listeners;


use App\Events\LitterWasArchived;

class DecreaseBreederLittersCount
{
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param LitterWasArchived $event
     */
    public function handle(LitterWasArchived $event)
    {
        foreach ($event->parents as $breeder) {
            $breeder->litters_count = $breeder->litters_count - 1;
            $breeder->update();
        }

    }
}