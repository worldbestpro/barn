<?php

namespace App\Models;


use App\Traits\ArchivableTrait;
use App\Traits\CloudinaryImageAbleTrait;
use App\Traits\ImageAbleTrait;

class RabbitKit extends Animal
{
    use CloudinaryImageAbleTrait, ArchivableTrait;
    protected $table    = 'rabbit_kits';
    protected $fillable = ['given_id','archived', 'tattoo', 'litter_id', 'user_id'];
    protected $appends  = ['weight_unit', 'weight_unit_short'];


    protected $imagesFolder = 'kits';

    public function litter()
    {
        return $this->belongsTo(Litter::class)->select(['id', 'given_id', 'total_weight', 'average_weight', 'survival_rate','kits_amount','kits_died']);
    }

    public function getWeightAttribute($weight)
    {
        if ($weight)
            return json_decode($weight);

        return null;
    }

    public function setWeightAttribute($weight)
    {
        if ($weight) {
            $this->attributes['weight'] = json_encode($weight);
        }
    }

    public function newWeight($newWeight)
    {
        $weight = $this->weight;
        if ( !$weight) {
            $weight = [$newWeight];
        } else {
            array_push($weight, $newWeight);
        }
        $this->weight         = $weight;
        $this->current_weight = $newWeight;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function events()
    {
        return $this->morphToMany(Event::class, 'eventable');
    }

    public function getWeightUnitAttribute()
    {
        return $this->user->general_weight_units;
    }

    public function getWeightUnitShortAttribute()
    {
        return $this->user->weight_slug;
    }
}