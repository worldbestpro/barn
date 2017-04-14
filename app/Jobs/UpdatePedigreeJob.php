<?php

namespace App\Jobs;

use App\Handlers\ImageHandler;
use App\Jobs\Job;
use App\Repositories\PedigreeRepository;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdatePedigreeJob extends Job implements SelfHandling
{
    use InteractsWithQueue, SerializesModels;
    /**
     * @var
     */
    public $pedigree;
    /**
     * @var
     */
    public $name;
    /**
     * @var
     */
    public $custom_id;
    /**
     * @var
     */
    public $day_of_birth;
    /**
     * @var
     */
    public $breed;
    /**
     * @var
     */
    public $sex;
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
     * @param $pedigree
     * @param $name
     * @param $custom_id
     * @param $day_of_birt
     * @param $breed
     * @param $sex
     * @param $image
     * @param $notes
     */
    public function __construct($pedigree, $name, $custom_id, $day_of_birth, $breed, $sex, $image, $notes)
    {
        $this->pedigree   = $pedigree;
        $this->name      = $name;
        $this->custom_id      = $custom_id;
        $this->day_of_birth    = $day_of_birth;
        $this->breed    = $breed;
        $this->sex       = $sex;
        $this->image     = $image;
        $this->notes     = $notes;
    }


    public function handle(PedigreeRepository $pedigrees, ImageHandler $handler)
    {
        $image       = $handler->prepareImageUsingTemp($this->image, 'pedigree');
        $this->image = $image['name'];

        return $pedigrees->updateFromJob($this->pedigree, $this);
    }
}
