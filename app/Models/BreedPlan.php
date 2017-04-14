<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BreedPlan extends Model
{
    protected $table    = 'breed_plans';
    protected $fillable = ['name', 'date'];

    public function events()
    {
        return $this->hasMany(Event::class, 'breed_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function litters()
    {
        return $this->morphedByMany(Litter::class, 'plannable', 'plannables', 'plan_id', 'plannable_id');
    }

    public function generateEvents($date)
    {


        $plan = [];
        $type = 'breeder';
        foreach(Auth::user()->breedChainsOrdered as $c){

            $plan[] = [
                'name'  =>  $c->name,
                'date'  =>  $this->makeDate($date)->addDays($c->days)->format('m/d/Y'),
                'icon'  =>  $c->icon,
                'type'  =>  $type,
                'all'   =>  $c->icon == 'fa-venus-mars bg-blue' ? true : false
            ];

            if($c->icon =='fa-birthday-cake bg-green') $type='litter';
        }

        if(count($plan)>0){
            return $plan;
        }

        //Original hardcoded breed plan

        $breed  = $this->makeDate($date)->format('m/d/Y');
        $check  = $this->makeDate($date)->addWeeks(2)->format('m/d/Y');
        $birth  = $this->makeDate($date)->addDays(30)->format('m/d/Y');
        $weigh1 = $this->makeDate($birth)->addWeeks(5)->format('m/d/Y');
        $weigh2 = $this->makeDate($weigh1)->addWeeks(2)->format('m/d/Y');
        $weigh3 = $this->makeDate($weigh2)->addWeeks(2)->format('m/d/Y');
        $butch  = $this->makeDate($weigh3)->addWeeks(2)->format('m/d/Y');

        return [
            ['name' => 'breed', 'date' => $breed, 'icon' => 'fa-venus-mars bg-blue','type'=>'breeder','all'=>true],
            ['name' => 'pregnancy check', 'date' => $check, 'icon' => 'fa-check bg-maroon','type'=>'breeder'],
            ['name' => 'kindle/birth', 'date' => $birth, 'icon' => 'fa-birthday-cake bg-green','type'=>'breeder'],
            ['name' => 'weigh1', 'date' => $weigh1, 'icon' => 'fa-balance-scale bg-yellow first-weight','type'=>'litter'],
            ['name' => 'weigh2', 'date' => $weigh2, 'icon' => 'fa-balance-scale bg-yellow','type'=>'litter'],
            ['name' => 'weigh3', 'date' => $weigh3, 'icon' => 'fa-balance-scale bg-yellow','type'=>'litter'],
            ['name' => 'butcher', 'date' => $butch, 'icon' => 'fa-cutlery bg-red','type'=>'litter'],

        ];

    }

    public function makeDate($date)
    {
        return Carbon::createFromFormat('m/d/Y', $date);
    }

    public function breeders()
    {
        return $this->morphedByMany(RabbitBreeder::class, 'plannable', 'plannables', 'plan_id', 'plannable_id');
    }
}