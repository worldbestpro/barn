<?php

namespace App\Models\Filters;


use App\Http\Requests\GetRabbitBreedersRequest;
use Illuminate\Contracts\Cache\Repository as Cache;

class RabbitBreedersFilter extends Filter
{
    protected $allowedFilters = [
        'sex',
        'archived',
        'order'
    ];
    protected $sex;
    protected $archived;
    protected $order;

    protected $forNatSort = [
        'tattoo',
        'cage',
    ];

    public function __construct(GetRabbitBreedersRequest $request, Cache $cache)
    {
        parent::__construct($request, $cache);
    }

    public function filter($breeders, $cacheName, $perPage)
    {
//        $this->cacheName = $cacheName . '.' . $this->cacheName;

//        return $this->cache->remember($this->cacheName, 10, function () use ($bands, $perPage) {

        $breeders
            ->sex($this->sex)
            ->archived($this->archived);
        if (in_array($this->order, $this->forNatSort)) {
            $breeders->orderBy(\DB::raw('LENGTH(' . $this->order . ')'), 'asc')->orderBy($this->order, 'asc');
        } else {
            $breeders->orderBy($this->order, 'asc');
        }
        return $breeders->paginate($perPage);
    }
}