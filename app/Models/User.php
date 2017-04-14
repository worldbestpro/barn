<?php

namespace App\Models;

use App\Traits\CloudinaryImageAbleTrait;
use App\Traits\EventsTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Models\BreedChain;

class User extends Authenticatable
{
    use EntrustUserTrait,CloudinaryImageAbleTrait,EventsTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable     = [
        'name', 'email', 'password',
    ];
    protected $imagesFolder = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $appends     = [
        'weight_slug'
    ];


    public function breeders()
    {
        return $this->hasMany(RabbitBreeder::class);
    }

    public function litters()
    {
        return $this->hasMany(Litter::class);
    }
    public function plans()
    {
        return $this->hasMany(BreedPlan::class);
    }
    public function maleBreeders()
    {
        return $this->hasMany(RabbitBreeder::class)->where('sex', '=', 'buck');
    }

    public function femaleBreeders()
    {
        return $this->hasMany(RabbitBreeder::class)->where('sex', '=', 'doe');
    }

    public function breedChains()
    {
        return $this->hasMany(BreedChain::class);
    }

    public function updateBreadChains($request){

        foreach($this->breedChains as $bc){
            if(!isset($request['days'][$bc->id])) $bc->delete();
        }

        if(isset($request['name'])):
            foreach($request['name'] as $k=>$v){
                if(!$chain = $this->breedChains()->where('id',$k)->first()){
                    $chain = new BreedChain();
                    $chain->user_id = $this->id;
                }

                $chain->name = $v;
                $chain->days = $request['days'][$k];
                $chain->icon = $request['icon'][$k];
                $chain->save();
            }
        endif;
    }


    public function getWeightSlugAttribute()
    {
        /*
         * short label: lbs for pounds
oz for ounces
g for grams
kg for kilograms
         */
        switch($this->general_weight_units){
            case 'Grams':
                return 'g';
                break;
            case 'Ounces':
                return 'oz';
                break;
            case 'Pounds':
                return 'lbs';
                break;
            case 'Pound/Ounces':
                return '';
                break;
            case 'Kilograms':
                return 'kg';
                break;
        }
    }


    public function breedChainsOrdered()
    {
        if(!$this->breedChains->count()){

            $b = new BreedChain();
            $b->user_id = $this->id;
            $b->name="breed";
            $b->days=0;
            $b->icon='fa-venus-mars bg-blue';
            $b->save();

            $b = new BreedChain();
            $b->user_id = $this->id;
            $b->name="pregnancy check";
            $b->days=15;
            $b->icon='fa-check bg-maroon';
            $b->save();

            $b = new BreedChain();
            $b->user_id = $this->id;
            $b->name="kindle/birth";
            $b->days=30;
            $b->icon='fa-birthday-cake bg-green';
            $b->save();

            $b = new BreedChain();
            $b->user_id = $this->id;
            $b->name="weigh1";
            $b->days=65;
            $b->icon='fa-balance-scale bg-yellow first-weight';
            $b->save();

            $b = new BreedChain();
            $b->user_id = $this->id;
            $b->name="weigh2";
            $b->days=80;
            $b->icon='fa-balance-scale bg-yellow';
            $b->save();

            $b = new BreedChain();
            $b->user_id = $this->id;
            $b->name="weigh3";
            $b->days=95;
            $b->icon='fa-balance-scale bg-yellow';
            $b->save();

            $b = new BreedChain();
            $b->user_id = $this->id;
            $b->name="butcher";
            $b->days=100;
            $b->icon='fa-cutlery bg-red';
            $b->save();
        }
        return $this->breedChains()->orderBy('days','asc');
    }

}
