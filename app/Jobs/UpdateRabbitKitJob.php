<?php

namespace App\Jobs;

use App\Events\KitWasWeighed;
use App\Handlers\CloudinaryImageHandler;
use App\Handlers\ImageHandler;
use App\Jobs\Job;
use App\Repositories\LitterRepository;
use App\Repositories\RabbitKitRepository;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateRabbitKitJob extends Job implements SelfHandling
{
    use InteractsWithQueue, SerializesModels;
    public  $kit;
    public  $litter_id;
    public  $given_id;
    public  $color;
    public  $sex;
    public  $weight;
    public  $image;
    public  $notes;
    public  $current_weight = null;
    public  $weight_changed;
    private $return_count;
    private $kitWasRewighed = false;

    /**
     * Create a new job instance.
     *
     * @param $kit
     * @param $litter_id
     * @param $given_id
     * @param $color
     * @param $sex
     * @param $weight
     * @param $current_weight
     * @param $image
     * @param $notes
     * @param $weight_changed
     * @param $return_count
     */
    public function __construct($kit, $litter_id, $given_id, $color, $sex, $weight, $image, $notes, $weight_changed, $return_count)
    {
        $this->kit            = $kit;
        $this->litter_id      = $litter_id;
        $this->given_id       = $given_id;
        $this->color          = $color;
        $this->sex            = $sex;
        $this->weight         = $weight;
        $this->image          = $image;
        $this->notes          = $notes;
        $this->weight_changed = $weight_changed;
        $this->return_count   = $return_count;
    }

    /**
     * Execute the job.
     *
     * @param RabbitKitRepository $kits
     * @param ImageHandler $handler
     * @param LitterRepository $litters
     * @return
     */
    public function handle(RabbitKitRepository $kits, CloudinaryImageHandler $handler, LitterRepository $litters)
    {
        $image       = $handler->prepareImageUsingTemp($this->image, 'kits');
        $this->image = $image['name'];

        if (is_array($this->weight)){
            if(count($this->weight)>3){
                array_forget($this->weight,2);
                sort($this->weight);
            }
            $this->current_weight = end($this->weight);
        }


        if ($this->current_weight != $this->kit->current_weight) {
            $this->kitWasRewighed = true;
        }

        $kit = $kits->updateFromJob($this->kit, $this);
        if ($this->kitWasRewighed)
            event(new KitWasWeighed($this->kit));

        //for the first weigh we need to return how many kits were already weighed

        if ($this->return_count) {
            $litter      = $litters->find($this->litter_id);
            $weighedKits = $litter->rabbitKits()
                ->whereNotNull('current_weight')
                ->count();

            $kit->weighedKits = $weighedKits;
        }

        return $kit;
    }
}
