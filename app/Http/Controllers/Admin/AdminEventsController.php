<?php

namespace App\Http\Controllers\Admin;

use App\Events\EventHasGone;
use App\Http\Requests\CreateEventRequest;

use App\Http\Requests\GetEventsRequest;
use App\Http\Requests\MakeBreedPlanRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Jobs\CreateBreedPlanJob;
use App\Jobs\CreateEventJob;
use App\Jobs\GetEventsJob;
use App\Jobs\UpdateEventJob;
use App\Models\BreedPlan;
use App\Models\Event;
use App\Models\Litter;
use App\Models\RabbitBreeder;
use App\Repositories\EventRepository;
use Carbon\Carbon;
use Collective\Bus\Dispatcher;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;
class AdminEventsController extends Controller
{
    /**
     * @var Dispatcher
     */
    private $dispatcher;

    /**
     * AdminEventsController constructor.
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function index(GetEventsRequest $request)
    {
        $objects = $this->dispatcher->dispatchFrom(GetEventsJob::class, $request);

        return response()->json($objects);
    }


    public function store(CreateEventRequest $request)
    {
        return $this->dispatcher->dispatchFrom(CreateEventJob::class, $request);
    }

    public function update(Event $event, UpdateEventRequest $request)
    {
        $request['event'] = $event;

        return $this->dispatcher->dispatchFrom(UpdateEventJob::class, $request);
    }

    public function show(Event $event)
    {
        $event->load($event->type . 's');

        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        if ($event->isRecurring())
            $event->recurringEvents()->delete();
        $event->archive();

        event(new EventHasGone($event, auth()->user()));
    }



    public function deleteEvents(Request $request, EventRepository $events){
        $eventForDelete = $request->get('events');
        $listOfEvents = $events->whereIn('id', $eventForDelete);
        foreach($listOfEvents as $event){
            $this->destroy($event);
        }
    }

    public function close(Event $event, EventRepository $events)
    {
        event(new EventHasGone($event, auth()->user()));
        if ( !$event->isRecurring()) {
            $event->closed = 1;
        } else {
            $event->closeRecurring();
            $events->addNewRecurringFor($event);
            $event->date = $event->recurringDate(Carbon::createFromFormat('m/d/Y', $event->date))->format('m/d/Y');
            $event->update();
        }
        $event->update();
    }

    public function reOpen(Event $event)
    {
        if ( !$event->isRecurring()) {
            $event->closed = 0;
        } else {
            $recurring         = $event->recurringOldest;
            $recurring->closed = 0;
            $recurring->update();
            $event->date = $recurring->date;
        }
        $event->update();
    }

    public function test(EventRepository $events)
    {
        $litter = Litter::find(2);
        $plannedEvent = $litter->weighs()->count();
        dd($plannedEvent);
    }

    public function makeBreedPlan(MakeBreedPlanRequest $request)
    {
        $this->dispatcher->dispatchFrom(CreateBreedPlanJob::class, $request);
    }

    public function breedPlanDummyEvents(Request $request, BreedPlan $breedPlan)
    {
        return $breedPlan->generateEvents($request->get('date'));
    }

}
