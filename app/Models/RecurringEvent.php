<?php
/**
 * Date: 19.02.2016
 * Time: 10:47
 */

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class RecurringEvent extends Model
{
    protected $table      = 'recurring_events';
    public    $timestamps = false;
    protected $fillable   = ['name', 'date', 'event_id'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getDateAttribute($date)
    {
        if ($date) {
            return $date = Carbon::createFromFormat('Y-m-d', $date)->format('m/d/Y');
        } else
            $this->attributes['date'] = null;
    }

    public function scopeLastWeek($query)
    {
        $now = Carbon::now();
        return $query->whereBetween('date',[$now->copy()->subDays(7)->toDateString(),$now->copy()->subDay()->toDateString()]);
    }
}