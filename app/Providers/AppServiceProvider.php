<?php

namespace App\Providers;

use App\Events\KitWasWeighed;
use App\Models\Event;
use App\Models\RabbitBreeder;
use App\Models\RabbitKit;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        RabbitKit::updated(function ($kit) {
//            if (array_key_exists('weight', $kit->getDirty())) {
//                event(new KitWasWeighed($kit, 'rabbit'));
//            }
//        });

        Event::deleting(function ($event) {
            $event->{$event->type . 's'}()->detach();
            if ($event->type != 'general')
                $event->generals()->detach();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
