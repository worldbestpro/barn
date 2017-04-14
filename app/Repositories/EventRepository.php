<?php

namespace App\Repositories;


use App\Models\Event;
use App\Models\RecurringEvent;
use Carbon\Carbon;

class EventRepository extends Repository
{
    protected $createFromFields = ['type', 'name', 'date', 'recurring', 'icon'];
    protected $updateFromFields = ['type', 'name', 'date', 'recurring', 'icon'];
    /**
     * @var RecurringEvent
     */
    private $recurringEvent;

    /**
     * EventRepository constructor.
     */
    public function __construct(Event $event, RecurringEvent $recurringEvent)
    {
        $this->object         = $event;
        $this->recurringEvent = $recurringEvent;
    }

    public function getRecurring()
    {
        return $this->object->recurring()->get();
    }

    public function getRecurringForLastDay()
    {
        return $this->object->recurring()->lastDay()->get();
    }
    public function getRecurringForLastWeek()
    {
        return $this->recurringEvent->with(['event'=>function($q){
            $q->select(['id','recurring','missed']);
        }])->lastWeek()->get();
    }

    public function addRecurring($recurrings)
    {
        $recurringTasks = $this->recurringEvent->insert($recurrings);
    }

    public function addNewRecurringFor(Event $event)
    {
        $lastRecurringDate = $event->recurringEvents()->max('date');
        //add interval to last recurring date according to period

        $newRecDate = $event->recurringDate(Carbon::createFromFormat('Y-m-d', $lastRecurringDate));
        $this->recurringEvent->create(['date' => $newRecDate->toDateString(), 'event_id' => $event->id]);
    }

}