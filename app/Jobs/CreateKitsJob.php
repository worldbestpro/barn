<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateKitsJob extends Job implements SelfHandling
{
    use InteractsWithQueue, SerializesModels;


    private $animal_type;
    private $litter;

    public function __construct($litter, $animal_type)
    {
        $this->animal_type = $animal_type;
        $this->litter      = $litter;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $kit = \App::make($this->animal_type);

        for ($i = 1; $i <= $this->litter->kits_amount; $i++) {
            $formattedKitId = $i< 100? sprintf('%02d', $i): $i;
            $kit->create(['given_id' => $this->litter->id . $formattedKitId, 'litter_id' => $this->litter->id, 'user_id' => auth()->user()->id]);
        }
    }
}
