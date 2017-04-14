<?php

namespace App\Jobs;

use App\Events\KitWasWeighed;
use App\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class WeightKitJob extends Job implements SelfHandling
{
    use InteractsWithQueue, SerializesModels;
    /**
     * @var
     */
    public $kit;
    /**
     * @var
     */
    public $current_weight;

    /**
     * Create a new job instance.
     *
     * @param $kit
     * @param $current_weight
     */
    public function __construct($kit, $current_weight)
    {
        $this->kit            = $kit;
        $this->current_weight = $current_weight;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $weight = $this->kit->weight;
        if ( !$weight) {
            $weight = [$this->current_weight];
        } else {
            if (count($weight) >= 3)
                $weight[2] = $this->current_weight;
            else
                array_push($weight, $this->current_weight);
        }
        $this->kit->weight         = $weight;
        $this->kit->current_weight = $this->current_weight;
        $this->kit->update();

    }
}
