<?php

namespace App\Console;

use App\Console\Commands\RemoveTempFiles;
use App\Console\Commands\UpdateRecurringEvents;
use App\Console\Commands\UploadAssetsToCloudinary;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        RemoveTempFiles::class,
        UpdateRecurringEvents::class,
        UploadAssetsToCloudinary::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('removeTempFiles')
            ->dailyAt('00:01');
        $schedule->command('updateRecurringEvents')
            ->weeklyOn(1,'00:01');
    }
}
