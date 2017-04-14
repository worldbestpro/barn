<?php

namespace App\Jobs;

use App\Handlers\CloudinaryImageHandler;
use App\Handlers\ImageHandler;
use App\Jobs\Job;
use App\Repositories\LitterRepository;
use App\Repositories\RabbitBreederRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MakeRabbitBreederFromKitJob extends Job implements SelfHandling
{
    use InteractsWithQueue, SerializesModels;

    public $given_id;
    public $sex;
    public $weight;
    public $current_weight;
    public $color;
    public $litter_id;
    public $image;
    public $notes;
    public $name;
    public $cage;
    public $tattoo;
    public $aquired;
    public $father_id;
    public $mother_id;
    public $breed;


    /**
     * Create a new job instance.
     *
     * @param $given_id
     * @param $sex
     * @param $weight
     * @param $current_weight
     * @param $color
     * @param $litter_id
     * @param $image
     * @param $notes
     * @param $created_at
     */
    public function __construct($given_id, $sex, $weight, $current_weight, $color, $litter_id, $image, $notes, $created_at)
    {
        $this->name      = $given_id;
        $this->sex       = $sex;
        $this->weight    = $current_weight;
        $this->color     = $color;
        $this->litter_id = $litter_id;
        $this->image     = $image['name'];
        $this->notes     = $notes;
        $this->name      = $given_id;
        $this->cage      = '';
        $this->tattoo    = $given_id;
        $this->aquired   = null;
        $this->breed     = null;
    }

    /**
     * Execute the job.
     *
     * @param RabbitBreederRepository $breeders
     * @param ImageHandler $handler
     * @param LitterRepository $litters
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function handle(RabbitBreederRepository $breeders, CloudinaryImageHandler $handler, LitterRepository $litters)
    {
        $litter  = $litters->find($this->litter_id);
        $parents = $litter->parents;

        $this->mother_id = $parents->where('sex', 'doe')->first()->id;
        $this->father_id = $parents->where('sex', 'buck')->first()->id;

        $this->aquired = $litter->born;
        $breeder       = $breeders->createFromJob($this);
        if ($this->image)
            $handler->moveImageToFolder('kits', 'breeders', $this->image);


        auth()->user()->breeders()->save($breeder);

        return $breeder;
        //TODO clearify what to to with kit after become breeder
    }
}
