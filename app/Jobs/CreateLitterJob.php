<?php

namespace App\Jobs;

use App\Events\LitterWasCreated;
use App\Jobs\Job;
use App\Models\BreedPlan;
use App\Repositories\LitterRepository;
use Carbon\Carbon;
use Collective\Bus\Dispatcher;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateLitterJob extends Job implements SelfHandling
{
    use InteractsWithQueue, SerializesModels;
    public  $given_id;
    public  $bred;
    public  $born;
    public  $father_id;
    public  $mother_id;
    public  $kits_amount;
    public  $notes;
    public  $survival_rate = 100;
    private $animal_type;
    private $breedplan;


    /**
     * Create a new job instance.
     *
     * @param $given_id
     * @param $bred
     * @param $born
     * @param $father_id
     * @param $mother_id
     * @param $kits_amount
     * @param $notes
     * @param $animal_type
     */
    public function __construct($given_id, $bred, $born, $father_id, $mother_id, $kits_amount, $notes, $animal_type, $breedplan)
    {
        $this->given_id    = $given_id;
        $this->bred        = $bred;
        $this->born        = $born;
        $this->father_id   = $father_id;
        $this->mother_id   = $mother_id;
        $this->kits_amount = $kits_amount;
        $this->notes       = $notes;
        $this->animal_type = $animal_type;
        $this->breedplan   = $breedplan;
    }

    /**
     * Execute the job.
     *
     * @param LitterRepository $litters
     * @param Dispatcher $dispatcher
     * @param BreedPlan $breedPlan
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function handle(LitterRepository $litters, Dispatcher $dispatcher, BreedPlan $breedPlan)
    {
        $litter = $litters->createFromJob($this);

        //attaching parents
        if ($this->mother_id && $this->father_id)
            $litter->parents()->attach([$this->mother_id, $this->father_id]);//todo change in case of many type of animals

        //creating empty kits
        $dispatcher->dispatchFromArray(CreateKitsJob::class, ['litter' => $litter, 'animal_type' => $this->animal_type . 'kit']);

        //attach to existing breed plan
        if ($this->breedplan) {
            $plan = $breedPlan->find($this->breedplan);
            $plan->litters()->attach($litter);
            $events = $plan->events;

            //calculate difference between planned and given
            $birth         = $events->where('subtype', 'birth')->first();
            $birth->closed = 1;
            $diffDays      = Carbon::createFromFormat('m/d/Y', $birth->date)->diffInDays(Carbon::createFromFormat('m/d/Y', $this->born), false);
            $birth->date = $this->born;
            $birth->update();

            //update the chain of events according to date difference
            foreach ($events->where('type','litter') as $event) {
                $date = Carbon::createFromFormat('m/d/Y', $event->date);
                if ($diffDays < 0) {
                    $event->date = $date->subDays(abs($diffDays))->format('m/d/Y');
                } else
                    $event->date = $date->addDays(abs($diffDays))->format('m/d/Y');
                $event->update();
            }

            $litter->bred = $events->where('subtype', 'breed')->first()->date;
            $litter->parents()->attach($plan->breeders()->lists('id')->toArray());
            $litter->update();
            $litter->events()->attach($events->where('type','litter')->lists('id')->toArray());
            $litter->rawEvents()->update(['holderName'=>$litter->given_id]);
        }

        auth()->user()->litters()->save($litter);
        event(new LitterWasCreated($litter, $this->animal_type));

        return $litter;
    }
}
