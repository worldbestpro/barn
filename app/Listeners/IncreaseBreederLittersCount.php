<?php

namespace App\Listeners;

use App\Events\LitterWasCreated;
use App\Models\RabbitBreeder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncreaseBreederLittersCount
{

    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param $event
     * @return void
     */
    public function handle($event)
    {
        foreach ($event->parents as $breeder) {
            $breeder->litters_count += 1;
            $breeder->update();
        }
    }
}
