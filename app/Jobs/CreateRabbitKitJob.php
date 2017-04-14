<?php

namespace App\Jobs;

use App\Handlers\CloudinaryImageHandler;
use App\Handlers\ImageHandler;
use App\Jobs\Job;
use App\Repositories\RabbitKitRepository;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateRabbitKitJob extends Job implements SelfHandling
{
    use InteractsWithQueue, SerializesModels;
    public $given_id;
    public $sex;
    public $weight;
    public $color;
    public $litter_id;
    public $image;
    public $notes;
    public $user_id;

    /**
     * CreateRabbitKitJob constructor.
     * @param $given_id
     * @param $sex
     * @param $current_weight
     * @param $color
     * @param $litter_id
     * @param $image
     * @param $notes
     * @param $litter_id
     */
    public function __construct($given_id, $sex, $current_weight, $color, $litter_id, $image, $notes)
    {
        $this->given_id       = $given_id;
        $this->sex            = $sex;
        $this->current_weight = $current_weight;
        $this->color          = $color;
        $this->litter_id      = $litter_id;
        $this->image          = $image;
        $this->notes          = $notes;
    }

    /**
     * Execute the job.
     *
     * @param RabbitKitRepository $kits
     * @param ImageHandler $handler
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function handle(RabbitKitRepository $kits, CloudinaryImageHandler $handler)
    {
        $image         = $handler->prepareImageUsingTemp($this->image, 'kits');
        $this->image   = $image['name'];
        $this->user_id = auth()->user()->id;

        return $kits->createFromJob($this);
    }
}
