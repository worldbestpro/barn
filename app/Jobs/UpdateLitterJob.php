<?php

namespace App\Jobs;

use App\Events\EventHolderWasRenamed;
use App\Events\LitterKitsAmountWasChanged;
use App\Events\LitterParentWasChanged;
use App\Jobs\Job;
use App\Repositories\LitterRepository;
use Collective\Bus\Dispatcher;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateLitterJob extends Job implements SelfHandling
{
    use InteractsWithQueue, SerializesModels;
    /**
     * @var
     */
    public $litter;
    /**
     * @var
     */
    public $given_id;
    /**
     * @var
     */
    public $kits_amount;
    /**
     * @var
     */
    public $father_id;
    /**
     * @var
     */
    public $mother_id;
    /**
     * @var
     */
    public $bred;
    /**
     * @var
     */
    public $born;
    /**
     * @var
     */
    public $notes;
    /**
     * @var
     */
    private $parents;

    /**
     * Create a new job instance.
     *
     * @param $litter
     * @param $given_id
     * @param $father_id
     * @param $mother_id
     * @param $parents
     * @param $bred
     * @param $born
     * @param $notes
     */
    public function __construct($litter, $given_id, $father_id, $mother_id, $kits_amount, $parents, $bred, $born, $notes)
    {
        $this->litter    = $litter;
        $this->given_id  = $given_id;
        $this->father_id = $father_id;
        $this->mother_id = $mother_id;
        $this->bred      = $bred;
        $this->born      = $born;
        $this->notes     = $notes;
        $this->parents   = $parents;
        $this->kits_amount   = $kits_amount;
    }

    /**
     * Execute the job.
     *
     * @param LitterRepository $litters
     */
    public function handle(LitterRepository $litters, Dispatcher $dispatcher)
    {

        $oldKitAmount = $this->litter->kits_amount;

        if ($this->given_id != $this->litter->given_id) {
            event(new EventHolderWasRenamed($this->litter, $this->given_id));
        }

        $litter = $litters->updateFromJob($this->litter, $this);
        if($oldKitAmount != $this->litter->kits_amount){
            $this->litter->rabbitKits()->delete();  // TODO: find better place
            $this->litter->updateWeights();
            $oldKitsDied = $this->litter->kits_died;
            $this->litter->kits_died = null;
            $this->litter->survival_rate = 100;
            $this->litter->update();
            $type = 'rabbitkit';
            $dispatcher->dispatchFromArray(CreateKitsJob::class, ['litter' => $litter, 'animal_type' => $type]);
            event(new LitterKitsAmountWasChanged($this->litter, $oldKitsDied, $oldKitAmount, $this->litter->kits_amount, $type));
        }

        $parents     = collect($this->parents);
        $oldMotherId = $parents->where('sex', 'doe')->pluck('id')->toArray()[0];
        $oldFatherId = $parents->where('sex', 'buck')->pluck('id')->toArray()[0];

        if ($this->father_id != $oldFatherId) {
            $this->litter->parents()->detach($oldFatherId);
            $this->litter->parents()->attach($this->father_id);
            event(new LitterParentWasChanged($litter, $oldFatherId, $this->father_id,'rabbit'));
        }
        if ($this->mother_id != $oldMotherId){
            $this->litter->parents()->detach($oldMotherId);
            $this->litter->parents()->attach($this->mother_id);
            event(new LitterParentWasChanged($litter, $oldMotherId, $this->mother_id,'rabbit'));
        }



        $litter->load('parents');
        $litter->weighs    = $litter->weighs()->count();
        $litter->mother_id = $litter->parents()->where('sex', '=', 'doe')->first()->id;
        $litter->father_id = $litter->parents()->where('sex', '=', 'buck')->first()->id;

        return $litter;
    }
}
