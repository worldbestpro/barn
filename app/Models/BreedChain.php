<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BreedChain extends Model
{
    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
