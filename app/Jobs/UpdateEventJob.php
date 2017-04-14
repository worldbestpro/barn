<?php

namespace App\Jobs;

use App\Events\EventDateWasChanged;
use App\Jobs\Job;
use App\Repositories\EventRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateEventJob extends Job implements SelfHandling
{
    use InteractsWithQueue, SerializesModels;

    public $event;
    public $type;
    public $type_id;
    public $name;
    public $date;
    public $recurring;
    public $icon;
    public $type_changed;
    public $date_changed   = false;
    public $period_changed = false;

    /**
     * Create a new job instance.
     *
     * @param $event
     * @param $type
     * @param $type_id
     * @param $name
     * @param $date
     * @param $recurring
     * @param $icon
     * @param $type_changed
     */
    public function __construct($event, $type, $type_id, $name, $date, $recurring, $icon, $type_changed)
    {
        $this->event        = $event;
        $this->type         = $type;
        $this->type_id      = $type_id;
        $this->name         = $name;
        $this->date         = $date;
        $this->recurring    = $recurring;
        $this->icon         = $icon;
        $this->type_changed = $type_changed;
    }

    /**
     * Execute the job.
     *
     * @param EventRepository $events
     */
    public function handle(EventRepository $events)
    {
        if ($this->date != $this->event->date)
            $this->date_changed = true;

        if ($this->recurring != $this->event->recurring)
            $this->period_changed = true;


        $this->event->generals()->detach();
        if ($this->type != 'general') {
            $eventable = \App::make($this->type);
            $this->event->{$this->type . 's'}()->detach();

            $eventableObj = $eventable->find($this->type_id);
            $this->event->holderName = $this->type == 'breeder' ? $eventableObj->name : $eventableObj->given_id;

            $eventableObj = $eventable->find($this->type_id);
            $eventableObj->events()->attach($this->event);

        } elseif ($this->type == 'general' && $this->type != $this->event->type) {
            $this->event->{$this->event->type . 's'}()->detach();
        }
        if ($this->date != $this->event->date)
            event(new EventDateWasChanged($this->event->date, $this->date, auth()->user()));

        $event = $events->updateFromJob($this->event, $this);
        auth()->user()->events()->attach($event);

        if ($event->isRecurring()) {
            if ($event->recurringEvents->isEmpty()) {
                $recurrings = $event->formRecurring();
                $events->addRecurring($recurrings);
            } else {
                $date = Carbon::createFromFormat('m/d/Y', $event->date);

                if ($this->date_changed && $this->period_changed) {
                    foreach ($event->recurringEvents as $recurring) {
                        if ( !dateLessThan($recurring->date, $date, 'm/d/Y')) {
                            $recurring->date = $event->recurringDate($date)->toDateString();
                            $recurring->delete();
                        }
                    }
                    $recurrings = $event->formRecurring();
                    $events->addRecurring($recurrings);
                }
            }
        } else {
            if ( !$event->recurringEvents->isEmpty()) {
                $event->recurringEvents()->delete();
            }
        }


        return $event;

    }
}
