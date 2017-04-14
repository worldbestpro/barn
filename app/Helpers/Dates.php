<?php
use Carbon\Carbon;

function dateLessThan($date,Carbon $dateCompared,$format){
    return $dateCompared->diffInDays(Carbon::createFromFormat($format, $date), false) < 0;
}