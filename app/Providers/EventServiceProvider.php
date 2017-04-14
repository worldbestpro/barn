<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\GetUserEvents',
        ],
        'App\Events\EventWasAdded' => [
            'App\Listeners\UpdateUserEventsCounter@increase',
        ],
        'App\Events\EventDateWasChanged' => [
            'App\Listeners\UpdateUserEventsCounter@manage',
        ],
        'App\Events\EventHasGone' => [
            'App\Listeners\UpdateUserEventsCounter@decrease',
        ],
        'App\Events\RabbitBreederWasArchived' => [
            'App\Listeners\PutLitterToArchive',
        ],
        'App\Events\LitterWasArchived' => [
            'App\Listeners\ManageLitterArchiving@archivePlan',
            'App\Listeners\DecreaseBreederLittersCount',
            'App\Listeners\ManageBreedersKits@decreaseCounts',
        ],
        'App\Events\LitterWasUnarchived' => [
            'App\Listeners\ManageLitterArchiving@unarchivePlan',
            'App\Listeners\ManageBreedersKits@increaseCountsUnarchived',
        ],
        'App\Events\LitterWasDeleted' => [
            'App\Listeners\ManageLitterDeletion@deletePlan',
            'App\Listeners\ManageLitterDeletion@deleteKits',
            'App\Listeners\ManageLitterDeletion@updateBreederStats',
        ],
        'App\Events\LitterWasCreated' => [
            'App\Listeners\IncreaseBreederLittersCount',
            'App\Listeners\ManageBreedersKits@increaseCounts',
        ],
        'App\Events\LitterKitsAmountWasChanged' => [
            'App\Listeners\ManageBreedersKits@updateCounts',
        ],
        'App\Events\LitterParentWasChanged' => [
            'App\Listeners\ChangeParentStats',
        ],
        'App\Events\LitterWasWeighed' => [
            'App\Listeners\ManageLitterWeights@byLitterWeight',
        ],
        'App\Events\EventHolderWasRenamed' => [
            'App\Listeners\ManageEventHolderRename@renameEvents',
        ],
        'App\Events\KitWasWeighed' => [
            'App\Listeners\ManageLitterWeights@byKitWeight',
        ],
        'App\Events\KitWasDied' => [
            'App\Listeners\ManageKitDeath@updateLitterStats',
            'App\Listeners\ManageKitDeath@updateBreederStats',
        ],
        'App\Events\KitWasDeleted' => [
            'App\Listeners\ManageKitDeletion@updateLitterStats',
            'App\Listeners\ManageKitDeletion@updateBreederStats',
        ],
        'App\Events\KitWasArchived' => [
            'App\Listeners\ManageKitArchive@updateLitterStats',
            'App\Listeners\ManageKitArchive@updateBreederStats',
        ],
        'App\Events\KitsWereButched' => [
            'App\Listeners\ManageKitsButch@updateLitterStats',
            'App\Listeners\ManageKitsButch@updateBreederStats',
            'App\Listeners\ManageKitsButch@autoCloseBreedPlanEvent',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
