<?php

namespace App\Models;


use App\Traits\ArchivableTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use ArchivableTrait;
    protected $table = 'events';
//    protected $with   = ['litters'];
    protected $hidden = ['pivot'];
    protected $with=['recurringEvents'];

    public    $fillable    = ['type', 'name', 'date','holderName'];
    protected $frequencies = [
        1 => 'Once',
        2 => 'Every week',
        3 => 'Every two weeks',
        4 => 'Every month',

    ];

    protected $subtypes      = [
        'fa-venus-mars bg-blue'      => 'breed',
        'fa-check bg-maroon'         => 'check',
        'fa-birthday-cake bg-green'  => 'birth',
        'fa-balance-scale bg-yellow' => 'weigh',
        'fa-cutlery bg-red'          => 'butch',
    ];
    protected $once          = 1;
    protected $everyWeek     = 2;
    protected $everyTwoWeeks = 3;
    protected $everyMonth    = 4;


    public static function boot()
    {
        parent::boot();

        static::saving(function ($entity) {

            if ($entity->icon) {
                if (array_key_exists($entity->icon, $entity->subtypes))
                    $entity->subtype = $entity->subtypes[$entity->icon];
                else
                    $entity->subtype = 'general';
            } else {
                //need from decoupling icon names when we creating events from ManageLitterWeights::class and ManageKitsButch::class
                $entity->icon = array_search($entity->subtype, $entity->subtypes);
            }
        });
    }

    public function getAll()
    {
        $data = $this->select()->get();

        return $data;
    }

    public function litters()
    {
        return $this->morphedByMany(Litter::class, 'eventable')->select(['id', 'given_id'])->with('parentsShort');
    }

    public function breeders()
    {
        return $this->morphedByMany(RabbitBreeder::class, 'eventable');
    }

    public function recurringEvents()
    {
        return $this->hasMany(RecurringEvent::class);
    }

    public function recurringOldest()
    {
        return $this->hasOne(RecurringEvent::class)->oldest('date');
    }

    public function generals()
    {
        return $this->morphedByMany(User::class, 'eventable');
    }

//    public function getTypeAttribute($type)
//    {
//        if ($type == 'general')
//            return 'user';
//
//        return $type;
//    }

    public function setDateAttribute($date)
    {
        if ($date) {
            $this->attributes['date'] = Carbon::createFromFormat('m/d/Y', $date)->toDateString();
        } else
            $this->attributes['date'] = null;
    }

    public function getDateAttribute($date)
    {
        if ($date) {
            return $date = Carbon::createFromFormat('Y-m-d', $date)->format('m/d/Y');
        } else
            $this->attributes['date'] = null;
    }

    public function isRecurring()
    {
        return $this->recurring > 1;
    }

    public function getRecurringAttribute($recurring)
    {
        //if ($recurring)
        //    return $this->frequencies[$recurring];
        return $recurring;
    }

    public function scopeRecurring($query)
    {
        return $query->where('recurring', '>', 1);
    }

    public function scopeLastDay($query)
    {
        return $query->where('date', '=', Carbon::now()->subDay()->toDateString());
    }

    public function closeRecurring()
    {
        $date              = Carbon::createFromFormat('m/d/Y', $this->date);
        $recurring         = $this->recurringEvents()->where('date', '=', $date->toDateString())->first();
        $recurring->closed = 1;
        $recurring->update();
    }

    public function archive()
    {
        $this->archived = 1;
        $this->update();
    }

    public function dateLessThan(Carbon $date)
    {
        return $date->diffInDays(Carbon::createFromFormat('m/d/Y', $this->date), false) < 0;
    }

    public function scopeSinceToday($query, $today)
    {
        if ($today) {
            return $query->where('date', '>=', Carbon::now()->toDateString());
        }
    }

    public function scopeExpired($query, $today)
    {
        if ($today) {
            return $query->where('date', '<', Carbon::now()->toDateString());
        }
    }

    public function formRecurring()
    {
        $evDate        = Carbon::createFromFormat('m/d/Y', $this->date);
        $recurrings [] = $this->makeReq($evDate);
        for ($i = 0; $i <= 5; $i++) {
            $date          = $this->recurringDate($evDate);
            $recurrings [] = $this->makeReq($date);
        }

        return $recurrings;
    }

    public function makeReq(Carbon $date)
    {
        return ['date' => $date->toDateString(), 'event_id' => $this->id];
    }

    /**
     * @param Carbon $evDate is the  date of the event
     * @return Carbon
     */
    public function recurringDate(Carbon $evDate)
    {
        if ($this->recurring != 4)
            $date = $evDate->addWeeks(($this->recurring - 1));
        else
            $date = $evDate->addMonth();

        return $date;
    }
}