<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\BreedPlan;
use App\Repositories\EventRepository;
use Carbon\Carbon;
use Collective\Bus\Dispatcher;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateBreedPlanJob extends Job implements SelfHandling
{
    use InteractsWithQueue, SerializesModels;
    private $doe;
    private $buck;
    private $date;
    private $type;

    /**
     * Create a new job instance.
     *
     * @param $doe
     * @param $buck
     * @param $date
     * @param $type
     */
    public function __construct($doe, $buck, $date, $type)
    {
        $this->doe  = $doe;
        $this->buck = $buck;
        $this->date = $date;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @param BreedPlan $plan
     * @param Dispatcher $dispatcher
     */
    public function handle(BreedPlan $plan, Dispatcher $dispatcher)
    {

        $animals   = \App::make($this->type)->whereIn('id', [$this->doe, $this->buck])->select(['id', 'name'])->get();
        $breedPlan = $plan->create([
            'name' => implode(' & ', $animals->lists('name')->toArray()),
            'date' => Carbon::createFromFormat('m/d/Y', $this->date)->toDateString(),
        ]);
        $breedPlan->breeders()->attach($animals->lists('id')->toArray());
        foreach ($breedPlan->generateEvents($this->date) as $event) {
            $data['type'] = $event['type'];
            if ($event['type'] == 'breeder')
                $data['type_id'] = $this->doe;
            else
                $data['type_id'] = null;
            $data['name']      = $event['name'];
            $data['date']      = $event['date'];
            $data['recurring'] = 1;
            $data['icon']      = $event['icon'];
            $newEvent          = $dispatcher->dispatchFromArray(CreateEventJob::class, $data);
            if (array_key_exists('all', $event) && $event['all']){
                $newEvent->breeders()->attach($this->buck);
                $newEvent->holderName .= ' & ' . $animals->where('id', (int)$this->buck)->first()->name;
            }

            $planEvents [] = $newEvent;
        }

        $breedPlan->events()->saveMany($planEvents);
        auth()->user()->plans()->save($breedPlan);
    }
}
