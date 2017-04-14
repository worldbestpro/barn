<?php

namespace App\Jobs;

use App\Events\EventWasAdded;
use App\Jobs\Job;
use App\Repositories\EventRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateEventJob extends Job implements SelfHandling
{
    use InteractsWithQueue, SerializesModels;

    public $type;
    public $type_id;
    public $name;
    public $date;
    public $recurring;
    public $icon;


    public function __construct($type, $type_id, $name, $date, $recurring, $icon)
    {
        $this->type      = $type;
        $this->type_id   = $type_id;
        $this->name      = $name;
        $this->date      = $date;
        $this->recurring = $recurring;
        $this->icon      = $icon;
    }


    /**
     * Execute the job.
     *
     * @param EventRepository $events
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function handle(EventRepository $events)
    {
        $event = $events->createFromJob($this);

        if ($event->isRecurring()) {

            $recurrings = $event->formRecurring();
            $events->addRecurring($recurrings);
        }
        if ($this->type != 'general' && $this->type_id) {

            $eventable    = \App::make($this->type);
            $eventableObj = $eventable->find($this->type_id);
            $eventableObj->events()->attach($event);
            $event->holderName = $this->type == 'breeder' ? $eventableObj->name : $eventableObj->given_id;
            $event->update();
        }

        auth()->user()->events()->attach($event);
        event(new EventWasAdded($event, auth()->user()));

        return $event;
    }
}
