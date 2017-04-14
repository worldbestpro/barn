<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Litter;
use App\Models\RabbitBreeder;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GetEventsJob extends Job implements SelfHandling
{

    public  $for;
    public  $perPage;
    public  $type;
    public  $sinceToday;
    public  $expired;
    public  $skipBreeders;
    public  $skipLitters;
    public  $takeBreeders;
    public  $takeLitters;
    private $breedersFetched = false;
    private $littersFetched  = false;
    private $breeders;
    private $litters;


    /**
     * Create a new job instance.
     *
     * @param $for
     * @param $perPage
     * @param $type
     * @param $sinceToday
     * @param $expired
     * @param $skipBreeders
     * @param $skipLitters
     */
    public function __construct($for, $perPage, $type, $sinceToday, $expired, $skipBreeders, $skipLitters)
    {
        $this->for          = $for;
        $this->perPage      = $perPage;
        $this->type         = $type;
        $this->sinceToday   = $sinceToday;
        $this->expired      = $expired;
        $this->skipBreeders = $skipBreeders;
        $this->skipLitters  = $skipLitters;
        if ($this->skipBreeders === 'all') {
            $this->breedersFetched = true;
        }
        if ($this->skipLitters === 'all') {

            $this->littersFetched = true;
        }
        $this->breeders = new Collection();
        $this->litters  = new Collection();
    }

    /**
     * Execute the job.
     *
     */
    public function handle()
    {
        if ($this->for) {
            if ($this->for == 'all') {

                if ($this->breedersFetched && $this->littersFetched)
                    return [];


                if ( !$this->skipBreeders && !$this->skipLitters) {
                    auth()->user()->forSevenWeeks('general');
                    $objects['user'] = auth()->user();
                    $this->perPage -= 1;
                }
                $this->setFetchingCounters();

                if ( !$this->breedersFetched) {
                    $breeders = $this->getObjects('breeder', $this->skipBreeders, $this->takeBreeders);
                    $this->breeders->add($breeders);
                    $firstBreedersGet = $breeders->count();

                    //if breeders are less then we requested it means that all of them were fetched and we need to grab more litters
                    if ($firstBreedersGet < $this->takeBreeders) {
                        $this->takeBreeders    = $firstBreedersGet;
                        $this->takeLitters     = $this->perPage - $this->takeBreeders;
                        $this->breedersFetched = true;
                        $this->breeders->add(['fetchedAll' => 'fetchedAll']);
                    }
                }
                if ( !$this->littersFetched) {
                    $litters = $this->getObjects('litter', $this->skipLitters, $this->takeLitters);
                    $this->litters->add($litters);
                    $firstLittersGet = $litters->count();
                    if ($firstLittersGet < $this->takeLitters) {
                        $this->takeLitters    = $firstLittersGet;
                        $this->takeBreeders   = $this->perPage - $this->takeLitters;
                        $this->littersFetched = true;
                        $this->litters->add(['fetchedAll' => 'fetchedAll']);
                    }
                }

                if ( !$this->breedersFetched && $this->littersFetched) {
                    $breedersSecond = $this->getObjects('breeder', $this->skipBreeders + $firstBreedersGet, $this->takeBreeders - $firstBreedersGet);
                    $this->breeders->add($breedersSecond);
                }

                if ( !$this->littersFetched && $this->breedersFetched) {
                    $littersSecond = $this->getObjects('litter', $this->skipLitters + $firstLittersGet, $this->takeLitters - $firstLittersGet);
                    $this->litters->add($littersSecond);
                }
                $objects['breeders'] = $this->breeders->flatten();
                $objects['litters']  = $this->litters->flatten();

                return $this->setObjectEvents($objects);
            }

            if ($this->for != 'user') {
                $objectOfType = \App::make($this->for);
                $objects      = $objectOfType->where('user_id', auth()->user()->id)->whereHas('sevenWeekEvents', function () {
                })->paginate($this->perPage);
                foreach ($objects as $object) {
                    $object->forSevenWeeks();
                }
            } else {
                auth()->user()->forSevenWeeks('general');
                $objects = auth()->user()->events;
            }


            return $objects;
        }

        if ($this->type) {
            return auth()->user()->entireEvents()->whereIn('type', explode(',', $this->type))->sinceToday($this->sinceToday)->expired($this->expired)->paginate($this->perPage);
        } else {
            return auth()->user()->entireEvents()->sinceToday($this->sinceToday)->expired($this->expired)->select(['id', 'name', 'closed', 'date', 'icon'])->paginate($this->perPage);
        }

    }

    public function getObjects($type, $skip, $take)
    {
        $objectOfType = \App::make($type);

        return $objectOfType->where('user_id', auth()->user()->id)->whereHas('sevenWeekEvents', function () {
        })->skip($skip)->take($take)->get();
    }

    public function setObjectEvents($objectsParts)
    {
        foreach ($objectsParts as $part => $objects) {
            if ($part != 'user') {
                if ( !$objects->isEmpty()) {
                    foreach ($objects as $object) {
                        if (is_object($object))
                            $object->forSevenWeeks();
                    }
                }
            }
        }

        return $objectsParts;
    }

    private function setFetchingCounters()
    {
        if ($this->breedersFetched) {
            $this->takeLitters = $this->perPage;
            return;
        }
        if ($this->littersFetched) {
            $this->takeBreeders = $this->perPage;
            return;
        }
        $this->takeLitters  = floor(($this->perPage) / 2);
        $this->takeBreeders = $this->perPage - $this->takeLitters;

    }
}
