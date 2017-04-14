<?php

namespace App\Listeners;

use App\Events\LitterWasArchived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PutLitterKitsToArchive
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
    public function handle(LitterWasArchived $event)
    {
        //TODO Change in case of many types of animals
        $event->litter->rabbitKits()->update(['archived'=>1]);
    }
}
