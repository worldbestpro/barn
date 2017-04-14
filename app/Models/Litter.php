<?php

namespace App\Models;


use App\Traits\ArchivableTrait;
use App\Traits\EventsTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Litter extends Model
{
    use ArchivableTrait, EventsTrait;

    protected $table    = 'litters';
    protected $fillable = ['archived'];
    protected $hidden   = ['pivot'];
    protected $appends  = ['weight_unit','weight_unit_short','total_weight_slug'];

//    protected $with = ['rabbitKits'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parents()
    {
        return $this->morphedByMany(RabbitBreeder::class, 'litterable')->select(['id', 'name', 'sex', 'image', 'litters_count', 'kits', 'live_kits']);
    }

    public function parentsShort()
    {
        return $this->morphedByMany(RabbitBreeder::class, 'litterable')->select(['id', 'name', 'sex']);
    }

    public function archivedParents()
    {
        return $this->morphedByMany(RabbitBreeder::class, 'litterable')->where('archived', '=', 1)->select(['id']);
    }

    public function plans()
    {
        return $this->morphToMany(BreedPlan::class, 'plannable', 'plannables', null, 'plan_id');
    }

    public function weighs()
    {
        return $this->morphToMany(Event::class, 'eventable')
            ->where('subtype', 'weigh')
            ->where('closed', '=', 1);
    }

    public function setBornAttribute($born)
    {
        if ($born) {
            $this->attributes['born'] = Carbon::createFromFormat('m/d/Y', $born)->toDateString();
        } else
            $this->attributes['born'] = null;
    }

    public function getBornAttribute($born)
    {
        if ($born) {
            return $date = Carbon::createFromFormat('Y-m-d', $born)->format('m/d/Y');
        } else
            $this->attributes['born'] = null;
    }

    public function getBredAttribute($bred)
    {
        if ($bred) {
            return $date = Carbon::createFromFormat('Y-m-d', $bred)->format('m/d/Y');
        } else
            $this->attributes['born'] = null;
    }


    public function setBredAttribute($bred)
    {
        if ($bred)
            $this->attributes['bred'] = Carbon::createFromFormat('m/d/Y', $bred)->toDateString();
        else
            $this->attributes['bred'] = null;
    }

    public function kitsPaged()
    {
        return collect($this->rabbitKits()->paginate(getenv('KITS_PER_LITTER')));
    }

    public function kits()
    {
        return $this->rabbitKits;
    }

    public function survivedKits()
    {
        return $this->hasMany(RabbitKit::class)->where('survived', '=', 1);
    }

    public function kitsCount()
    {
        return $this->rabbitKits()->count();
    }

    public function rabbitKits()
    {
        return $this->hasMany(RabbitKit::class)->where('alive', '=', 1);
    }

    public function totalKits()
    {
        return $this->hasMany(RabbitKit::class);
    }

    public function setAge()
    {
        $born = Carbon::createFromFormat('m/d/Y', $this->born);
        $now  = Carbon::now();
        $age  = ($now->diff($born)->days < 7) ? '1 week' : $now->diffForHumans($born, true);

        $this->setAttribute('age', $age);
    }

    public function updateWeights()
    {
        $weights = $this->survivedKits()->where('archived', 0)->lists('current_weight');//TODO change in case of multiple animal

        $this->total_weight   = $weights->sum();
        $this->average_weight = $weights->avg();
    }

    public function getWeightUnitAttribute()
    {
        return $this->user->general_weight_units;
    }

    public function getWeightUnitShortAttribute()
    {
        return $this->user->weight_slug;
    }

    public function getTotalWeightSlugAttribute()
    {

        if($this->user->general_weight_units=='Pound/Ounces'):

        /*
         *
kit weights:
1.2 lb/oz (1 lb 2 oz)
1.3 lb/oz
1.5 lb/oz
1.3 lb/oz

first, convert to oz:
1 lb 2 oz = (1x16) + 2 = 18 oz
1.3 lb/oz = (1x16) + 3 = 19 oz
1.5 lb/oz = (1x16) + 5 = 21 oz
1.3 lb/oz = (1x16) + 3 = 19 oz

total = 77 oz
average = 77 oz / 4 kits = 19.25 oz

then, convert to lbs:
total = 77/16 = 4.8 lbs = 4 lbs + .8 lbs
Convert .8 lbs to oz: (.8x 16) = 13 oz
total = 4 lbs 13 oz

average = 77 oz / 4 kits = 19.25 oz
19.25/16 = 1.20 lbs = 1 lbs + .2 lbs
Convert .2 lbs to oz: (.2x 16) = 3.2 (round to whole) = 3 oz
average = 1 lbs 3 oz
        *
         */
        //First step
        $count=0;$total=0;
        foreach($this->rabbitKits()->get() as $k){
            $tmp = explode(".",$k->current_weight);
            $total += ($tmp[0] * 16);
            $total += isset($tmp[1]) ? substr($tmp[1],0,1) : 0;
            $count++;
        }

        $average = $count ? $total/$count : 0;

        //Second step in total
        $tLbs = $total/16;
        $tmp = explode(".", $tLbs);
        $total = $tmp[0] > 0 ? $tmp[0] . " lbs " : '';
        $tmp2 = ((int)(($tLbs - $tmp[0]) * 16));
        $total .= $tmp2>0 ? $tmp2 . ' oz' : '';

        //Second step in average
        $tLbs = $average/16;
        $tmp = explode(".", $tLbs);
        $average = $tmp[0] > 0 ? $tmp[0] . " lbs " : '';
        $tmp2 = ((int)(($tLbs - $tmp[0]) * 16));
        $average .= $tmp2>0 ? $tmp2 . ' oz' : '';
        //$average = $tLbs - $tmp[0];

        else:
            $total = $this->total_weight && $this->total_weight>0 ? $this->total_weight . " " . $this->user->weight_slug : '';
            $average = $this->average_weight && $this->average_weight>0 ? $this->average_weight . " " . $this->user->weight_slug : '';
        endif;

        return [
            'total'     =>  $total,
            'average'   =>  $average
        ];
    }



}