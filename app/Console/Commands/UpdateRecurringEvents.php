<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\RecurringEvent;
use App\Repositories\EventRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateRecurringEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateRecurringEvents';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param EventRepository $events
     * @return mixed
     */
    public function handle(EventRepository $events)
    {
        $recurrings = $events->getRecurringForLastWeek();

        if ( !$recurrings->isEmpty()) {
            foreach ($recurrings as $recurring) {
                if ($recurring->closed != 1) {
                    $recurring->event->missed += 1;
                }
                $recurring->delete();
                $recurring->event->date = $recurring->event->recurringEvents()->orderBy('date','ASC')->select('date')->first()->date;
                $recurring->event->update();
                //fetch last recurring date
                if ($recurring->event->recurringEvents()->count() <= 7) {
                    $events->addNewRecurringFor($recurring->event);
                }

            }
        }
    }
}
