<?php

namespace App\Jobs;

use App\Events\EventHolderWasRenamed;
use App\Handlers\CloudinaryImageHandler;
use App\Handlers\ImageHandler;
use App\Jobs\Job;
use App\Repositories\RabbitBreederRepository;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateRabbitBreederJob extends Job implements SelfHandling
{
    use InteractsWithQueue, SerializesModels;
    /**
     * @var
     */
    public $breeder;
    /**
     * @var
     */
    public $name;
    /**
     * @var
     */
    public $breed;
    /**
     * @var
     */
    public $cage;
    /**
     * @var
     */
    public $tattoo;
    /**
     * @var
     */
    public $sex;
    /**
     * @var
     */
    public $weight;
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
    public $color;
    /**
     * @var
     */
    public $aquired;
    /**
     * @var
     */
    public $image;
    /**
     * @var
     */
    public $notes;

    /**
     * Create a new job instance.
     *
     * @param $breeder
     * @param $name
     * @param $breed
     * @param $cage
     * @param $tattoo
     * @param $sex
     * @param $weight
     * @param $father_id
     * @param $mother_id
     * @param $color
     * @param $aquired
     * @param $image
     * @param $notes
     */
    public function __construct($breeder, $name, $breed, $cage, $tattoo, $sex, $weight, $father_id, $mother_id, $color, $aquired, $image, $notes)
    {
        $this->breeder   = $breeder;
        $this->name      = $name;
        $this->breed     = $breed;
        $this->cage      = $cage;
        $this->tattoo    = $tattoo;
        $this->sex       = $sex;
        $this->weight    = $weight;
        $this->father_id = $father_id;
        $this->mother_id = $mother_id;
        $this->color     = $color;
        $this->aquired   = $aquired;
        $this->image     = $image;
        $this->notes     = $notes;
    }


    public function handle(RabbitBreederRepository $breeders, CloudinaryImageHandler $handler)
    {
        $image       = $handler->prepareImageUsingTemp($this->image, 'breeders');
        $this->image = $image['name'];

        if ($this->name != $this->breeder->name) {
            \Event::fire(new EventHolderWasRenamed($this->breeder, $this->name));
        }

        return $breeders->updateFromJob($this->breeder, $this);
    }
}
