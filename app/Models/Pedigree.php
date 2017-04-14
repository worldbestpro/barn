<?php

namespace App\Models;

use App\Traits\ArchivableTrait;
use App\Traits\EventsTrait;
use App\Traits\ImageAbleTrait;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pedigree extends Model
{
    use ImageAbleTrait, ArchivableTrait, EventsTrait;

    protected $imagesFolder = 'pedigree';

    protected $appends = [

        'css',
        'user_id'

    ];

    //
    public function breeder()
    {
        return $this->belongsTo(RabbitBreeder::class,'rabbit_breeder_id');
    }


    public function getCssAttribute()
    {
        if($this->sex =='buck') {
            return [
                'icon'  =>  "fa fa-mars",
                'color' =>  "bg-aqua",
                'img'   =>  'icon-male.png'
            ];
        }

        return [
            'icon'  =>  "fa fa-venus",
            'color' =>  "bg-maroon",
            'img'   =>  'icon-female.png'
        ];
    }

    public function getUserIdAttribute()
    {
        return $this->breeder->user_id;
    }

    public function setDayOfBirthAttribute($aquired)
    {
        if ($aquired)
            $this->attributes['day_of_birth'] = Carbon::createFromFormat('m/d/Y', $aquired)->toDateString();
        else
            $this->attributes['day_of_birth'] = null;
    }

    public function getDayOfBirthAttribute($aquired)
    {
        if ($aquired)
            return Carbon::createFromFormat('Y-m-d', $aquired)->format('m/d/Y');

    }
}
