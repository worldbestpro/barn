<?php

namespace App\Models\Filters;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Http\Request;

abstract class Filter
{
    protected $allowedFilters;
    protected $cache;
    protected $cacheName;
    protected $request;

    public function __construct(Request $request, Cache $cache)
    {
        foreach ($this->allowedFilters as $filter) {
            $filterVal = $request->get($filter);
            if ($filterVal !== null) {
                $this->{$filter} = str_contains($filterVal, ',') ? explode(',', $filterVal) : $filterVal;
            }

            if ($request->has($filter))
                $this->cacheName .= $filter . $filterVal . '.';
        }
        $this->cache = $cache;

        $this->request = $request;
    }

    public abstract function filter($items, $cacheName, $perPage);

}