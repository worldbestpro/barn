<?php

namespace App\Traits;


use App\Models\Event;
use Carbon\Carbon;

trait EventsTrait
{
    public function events()
    {
        return $this->morphToMany(Event::class, 'eventable')->where('closed', '=', 0)->where('archived', '=', 0)->orderBy('date', 'ASC');
    }
    public function rawEvents()
    {
        return $this->morphToMany(Event::class, 'eventable')->where('closed', '=', 0)->where('archived', '=', 0);
    }

    public function entireEvents()
    {
        return $this->morphToMany(Event::class, 'eventable')->orderBy('date', 'ASC')->where('archived', '=', 0);
    }

    public function futureEvents()
    {
        return $this->morphToMany(Event::class, 'eventable')->orderBy('date', 'ASC')->where('archived', '=', 0)->where('date', '>=', Carbon::now()->startOfDay()->toDateString());
    }

    public function weeklyEvents()
    {
        return $this->morphToMany(Event::class, 'eventable')->where('archived', '=', 0)->where(function ($query) {
            $query->whereBetween('date', [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()])->orderBy('date', 'ASC');
        })->select(['id', 'name', 'date', 'icon', 'breed_id','closed','subtype']);

    }

    public function dateWeeklyEvents(Carbon $date)
    {
        return $this->morphToMany(Event::class, 'eventable')->where('archived', '=', 0)->where(function ($query) use ($date) {
            $query->whereBetween('date', [$date->startOfWeek()->toDateString(), $date->endOfWeek()->toDateString()])->orderBy('date', 'ASC');
            $query->orWhereHas('recurringEvents', function ($q) use ($date) {
                $q->whereBetween('date', [$date->startOfWeek()->toDateString(), $date->endOfWeek()->toDateString()]);
            });
        })->select(['id', 'name', 'date', 'icon', 'breed_id','closed','subtype']);
    }

    public function upcomingEvents()
    {
        return $this->morphToMany(Event::class, 'eventable')->with('recurringEvents')->where(function ($query) {
            $query->whereBetween('date', [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()])->orderBy('date', 'ASC')
                ->where('archived', '=', 0)
                ->orWhere(function ($query) {
                    $query->where('date', '<=', Carbon::now()->toDateString());
                    $query->where('archived', '=', 0);
                })
                ->orWhereHas('recurringEvents', function ($q) {
                    $q->where('date', '<=', Carbon::now()->toDateString());
                });
        });
    }

    public function actualEvents()
    {
        return $this->morphToMany(Event::class, 'eventable')->where(function ($query) {
            $query->whereBetween('date', [Carbon::now()->startOfWeek()->toDateString(), Carbon::now()->endOfWeek()->toDateString()])->orderBy('date', 'ASC')
                ->where('closed', '=', 0)
                ->where('archived', '=', 0)
                ->orWhere(function ($query) {
                    $query->where('closed', '=', 0);
                    $query->where('archived', '=', 0);
                    $query->where('date', '<=', Carbon::now()->toDateString());
                });
        });
    }

    public function forSevenWeeks($type=null)
    {
        for ($i = 0; $i <= 6; $i++) {
            $date                   = Carbon::now()->addWeek($i);
            $week[$i]['count']      = $this->dateWeeklyEvents($date)->where(function($q) use($type){
                if($type){
                    $q->where('type',$type);
                }
            })->count();
            $week[$i]['start_date'] = $date->startOfWeek()->format('m/d/Y');
            $week[$i]['end_date']   = $date->endOfWeek()->format('m/d/Y');
            if ($week[$i]['count'] == 1) {
                $week[$i]['event'] = $this->dateWeeklyEvents($date)->where(function($q) use($type){
                    if($type){
                        $q->where('type',$type);
                    }
                })->with(['recurringEvents' => function ($q) use ($date) {
                    $q->whereBetween('date', [$date->startOfWeek()->toDateString(), $date->endOfWeek()->toDateString()]);

                }])->first();
            }
        }

        $this->setAttribute('events', $week);
    }

    public function sevenWeekEvents()
    {
        return $this->morphToMany(Event::class, 'eventable')->where('archived', '=', 0)->whereBetween('date', [
            Carbon::now()->toDateString(),
            Carbon::now()->addWeeks(7)->toDateString(),
        ])->orderBy('date', 'ASC');
    }
}