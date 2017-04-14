<?php

namespace App\Traits;


trait ArchivableTrait
{
    public function scopeArchived($query, $archived)
    {
        if ($archived !== null)
            return $query->where('archived', '=', $archived);

        return $query;
    }
}