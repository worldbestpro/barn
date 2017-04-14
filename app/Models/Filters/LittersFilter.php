<?php

namespace App\Models\Filters;

use App\Http\Requests\GetLittersRequest;
use App\Models\RabbitBreeder;
use Illuminate\Contracts\Cache\Repository as Cache;

class LittersFilter extends Filter
{
    protected $allowedFilters = [
        'archived',
        'order',
        'orderDirection'
    ];
    protected $archived;
    protected $order;
    protected $orderDirection;

    protected $forNatSort = [
        'given_id',
    ];

    public function __construct(GetLittersRequest $request, Cache $cache)
    {
        parent::__construct($request, $cache);
    }
    public function filter($items, $cacheName, $perPage)
    {
        //        $this->cacheName = $cacheName . '.' . $this->cacheName;

//        return $this->cache->remember($this->cacheName, 10, function () use ($bands, $perPage) {   // *_*
//        if( $this->order == 'doe'){
//
//            $table = with(new RabbitBreeder())->getTable();
//            $items->join('litterables', 'litterables.litter_id','=','id')
//                ->select('doe.name')
//                ->join($table . ' as doe', function($join) use ($table) {
//                    $join->on('litterables.litter_id','=','id')
//                         ->on('litterables.litterable_type', '=', RabbitBreeder::class)
//                         ->on($table.'.sex', '=', 'doe');
//                })->orderBy('doe.name', 'asc');
//        }
//        else
        if (in_array($this->order, $this->forNatSort)) {
            $items->orderBy(\DB::raw('LENGTH(' . $this->order . ')'), 'asc')->orderBy($this->order, 'asc');
        } else {
            $items->orderBy($this->order, $this->orderDirection);
        }

        if($perPage >= 0){
            return $items
                ->archived($this->archived)
                ->paginate($perPage);
        } else {
            return $items
                ->archived($this->archived)
                ->get();
        }
//        });
    }
}