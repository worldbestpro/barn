<?php

namespace App\Models;


use App\Traits\ArchivableTrait;
use App\Traits\EventsTrait;
use App\Traits\CloudinaryImageAbleTrait;
use Carbon\Carbon;
use App\Helpers\BaseIntEncoder;
use App\Models\Pedigree;

class RabbitBreeder extends Animal
{
    use CloudinaryImageAbleTrait, ArchivableTrait, EventsTrait;

    protected $table = 'rabbit_breeders';
    protected $imagesFolder = 'breeders';
    protected $casts = [
        'weight' => 'float',
    ];

    protected $appends = [
        'weight_slug',
        'css',
        'token'
    ];


    public function setAquiredAttribute($aquired)
    {
        if ($aquired)
            $this->attributes['aquired'] = Carbon::createFromFormat('m/d/Y', $aquired)->toDateString();
        else
            $this->attributes['aquired'] = null;
    }

    public function getAquiredAttribute($aquired)
    {
        if ($aquired)
            return Carbon::createFromFormat('Y-m-d', $aquired)->format('m/d/Y');

    }

    public function getLittersCountAttribute(){
        return $this->litters()->where('archived', 0)->count();
    }

    public function getKitsAttribute(){
        $litters = $this->litters()->where('archived', 0)->get();
        $count = 0;
        foreach($litters as $litter){
            $count += $litter->totalKits()->count();
        }
        if($count){
            return $count;
        }
        return null;
    }

    public function getLiveKitsAttribute(){
        $litters = $this->litters()->where('archived', 0)->get();
        $count = 0;
        foreach($litters as $litter){
            $count += $litter->rabbitKits()->count();
        }
        if($count){
            return $count;
        }
        return null;
    }

    public function kits()
    {
        return $this->hasManyThrough(RabbitKit::class, Litter::class, 'country_id', 'user_id');
    }

    public function father()
    {
        //return $this->hasOne(RabbitBreeder::class, 'id', 'father_id')->select(['id', 'name', 'tattoo']);
        return $this->hasOne(RabbitBreeder::class, 'id', 'father_id');
    }

    public function mother()
    {
        //return $this->hasOne(RabbitBreeder::class, 'id', 'mother_id')->select(['id', 'name', 'tattoo']);
        return $this->hasOne(RabbitBreeder::class, 'id', 'mother_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function scopeForCurrentUser($query)
    {
        return $query->where('user_id', '=', auth()->user()->id);
    }

    public function scopeSex($query, $sex)
    {
        if ($sex)
            return $query->where('sex', '=', $sex);

        return $query;
    }

    public function hashTags()
    {
        return $this->morphToMany(BreedPlan::class, 'plannable', 'plannables', null, 'plan_id');
    }

    public function litters()
    {
        return $this->morphToMany(Litter::class, 'litterable');
    }


    public function getWeightSlugAttribute()
    {

        if(!$this->user) return $this->weight;
        if($this->user->general_weight_units == 'Pound/Ounces'){
            $txt="";
            $tmp = explode(".", $this->weight);
            if($tmp[0]>0) $txt .= $tmp[0] . ' lbs ';
            if(isset($tmp[1]) && $tmp[1]>0) $txt .= $tmp[1].' oz';
            return $txt;
        }else{
            return $this->weight . " " . $this->user->weight_slug;
        }
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

    public function pedigree(){

        $g = $this->user->pedigree_number_generations ? $this->user->pedigree_number_generations : 2;

        $response = [];
        $response['g1'] = $this;

        //create tree
        foreach($this->pedigrees as $p){
            $p = $this->newPedigree($p->level,$p->sex,$p);
            $x = explode(".",$p->level);
            $response[$x[0]][$x[1]] = $p;
        }

        //LEVEL II
        if($g>=2){

            $response['g2']['f1'] = !isset($response['g2']['f1']) ? $this->newPedigree('g2.f1','buck') : $this->newPedigree('g2.f1','buck',$response['g2']['f1']);
            $response['g2']['m1'] = !isset($response['g2']['m1']) ? $this->newPedigree('g2.m1','doe') : $this->newPedigree('g2.m1','doe', $response['g2']['m1']);
            //if(!isset($response['g2']['f1'])) $response['g2']['f1'] = $this->newPedigree('g2.f1','buck');
            //if(!isset($response['g2']['m1'])) $response['g2']['m1'] = $this->newPedigree('g2.m1','doe');
        }

        //LEVEL III
        if($g>=3){

            $response['g3']['f1'] = !isset($response['g3']['f1']) ? $this->newPedigree('g3.f1','buck') : $this->newPedigree('g3.f1','buck', $response['g3']['f1']);
            $response['g3']['m1'] = !isset($response['g3']['m1']) ? $this->newPedigree('g3.m1','doe') : $this->newPedigree('g3.m1','doe', $response['g3']['m1']);

            $response['g3']['f2'] = !isset($response['g3']['f2']) ? $this->newPedigree('g3.f2','buck'): $this->newPedigree('g3.f2','buck', $response['g3']['f2']);
            $response['g3']['m2'] = !isset($response['g3']['m2']) ? $this->newPedigree('g3.m2','doe') : $this->newPedigree('g3.m2','doe', $response['g3']['m2']);

            //if(!isset($response['g3']['f1'])) $response['g3']['f1'] = $this->newPedigree('g3.f1','buck');
            //if(!isset($response['g3']['m1'])) $response['g3']['m1'] = $this->newPedigree('g3.m1','doe');

            //if(!isset($response['g3']['f2'])) $response['g3']['f2'] = $this->newPedigree('g3.f2','buck');
            //if(!isset($response['g3']['m2'])) $response['g3']['m2'] = $this->newPedigree('g3.m2','doe');
        }

        //LEVEL III
        if($g>=4){

            $response['g4']['f1'] = !isset($response['g4']['f1']) ? $this->newPedigree('g4.f1','buck') : $this->newPedigree('g4.f1','buck', $response['g4']['f1']);
            $response['g4']['m1'] = !isset($response['g4']['m1']) ? $this->newPedigree('g4.m1','doe') : $this->newPedigree('g4.m1','doe', $response['g4']['m1']);

            $response['g4']['f2'] = !isset($response['g4']['f2']) ? $this->newPedigree('g4.f2','buck') : $this->newPedigree('g4.f2','buck',$response['g4']['f2']);
            $response['g4']['m2'] = !isset($response['g4']['m2']) ? $this->newPedigree('g4.m2','doe') : $this->newPedigree('g4.m2','doe', $response['g4']['m2']);

            $response['g4']['f3'] = !isset($response['g4']['f3']) ? $this->newPedigree('g4.f3','buck') : $this->newPedigree('g4.f3','buck', $response['g4']['f3']);
            $response['g4']['m3'] = !isset($response['g4']['m3']) ? $this->newPedigree('g4.m3','doe') : $this->newPedigree('g4.m3','doe', $response['g4']['m3']);

            $response['g4']['f4'] = !isset($response['g4']['f4']) ? $this->newPedigree('g4.f4','buck') : $this->newPedigree('g4.f4','buck', $response['g4']['f4']);
            $response['g4']['m4'] = !isset($response['g4']['m4']) ? $this->newPedigree('g4.m4','doe') : $this->newPedigree('g4.m4','doe', $response['g4']['m4']);




            //if(!isset($response['g4']['f1'])) $response['g4']['f1'] = $this->newPedigree('g4.f1','buck');
            //if(!isset($response['g4']['m1'])) $response['g4']['m1'] = $this->newPedigree('g4.m1','doe');

            //if(!isset($response['g4']['f2'])) $response['g4']['f2'] = $this->newPedigree('g4.f2','buck');
            //if(!isset($response['g4']['m2'])) $response['g4']['m2'] = $this->newPedigree('g4.m2','doe');

            //if(!isset($response['g4']['f3'])) $response['g4']['f3'] = $this->newPedigree('g4.f3','buck');
            //if(!isset($response['g4']['m3'])) $response['g4']['m3'] = $this->newPedigree('g4.m3','doe');

            //if(!isset($response['g4']['f4'])) $response['g4']['f4'] = $this->newPedigree('g4.f4','buck');
            //if(!isset($response['g4']['m4'])) $response['g4']['m4'] = $this->newPedigree('g4.m4','doe');
        }


        switch($g){
            case 2:
                unset($response['g3']);
                unset($response['g4']);
                break;
            case 3:
                unset($response['g4']);
                break;
        }


        return $response;
    }

    private function newPedigree($level,$sex, $pedigree=false){

        $p=false;
        $y = explode(".",$level);
        switch($y[0]){
            case 'g2':
                if($sex=='doe')$p = $this->mother;
                if($sex=='buck')$p = $this->father;
                break;
            case 'g3':
                if($y[1]=='f1' && $this->father) $p = $this->father->father;
                if($y[1]=='m1' && $this->father) $p = $this->father->mother;
                if($y[1]=='f2' && $this->mother) $p = $this->mother->father;
                if($y[1]=='m2' && $this->mother) $p = $this->mother->mother;
                break;
            case 'g4':
                if($y[1]=='f1' && $this->father && $this->father->father) $p = $this->father->father->father;
                if($y[1]=='m1' && $this->father && $this->father->father) $p = $this->father->father->mother;

                if($y[1]=='f2' && $this->father && $this->father->mother) $p = $this->father->mother->father;
                if($y[1]=='m2' && $this->father && $this->father->mother) $p = $this->father->mother->mother;

                if($y[1]=='f3' && $this->mother && $this->mother->father) $p = $this->mother->father->father;
                if($y[1]=='m3' && $this->mother && $this->mother->father) $p = $this->mother->father->mother;

                if($y[1]=='f4' && $this->mother && $this->mother->mother) $p = $this->mother->mother->father;
                if($y[1]=='m4' && $this->mother && $this->mother->mother) $p = $this->mother->mother->mother;
                break;
        }



        if(!$pedigree) $pedigree = new Pedigree();
        $pedigree->rabbit_breeder_id = $this->id;
        $pedigree->level = $level;
        $pedigree->sex = $sex;

        if($p && $p->id!=$pedigree->rabbit_breeders_id){
            $pedigree->name = $p->name;
            $pedigree->custom_id = $p->tattoo;
            $pedigree->day_of_birth = $p->aquired;
            $pedigree->notes = $p->notes;
            $pedigree->breed = $p->breed;

            //dd($p->image);
            if($p->image['name']){
                $or = public_path() . DIRECTORY_SEPARATOR .'media/breeders/' . $p->image['name'];
                $de = public_path() . DIRECTORY_SEPARATOR .'media/pedigree/' . $p->image['name'];
                if(is_file($or)) copy($or,$de);

            }
            $pedigree->image = $p->image['name'] && is_file($or) ? $p->image['name'] : null;
            $pedigree->rabbit_breeders_id = $p->id;
        }

        if(!$p && $pedigree->rabbit_breeders_id){
            $pedigree->name = '';
            $pedigree->breed = '';
            $pedigree->custom_id = '';
            $pedigree->day_of_birth = '';
            $pedigree->notes = '';
            $pedigree->image='';
            $pedigree->rabbit_breeders_id = null;
        }

        $pedigree->save();
        return $pedigree;
    }


    public function getTokenAttribute()
    {
        return BaseIntEncoder::encode($this->id);
    }


    public function pedigrees()
    {
        return $this->hasMany(Pedigree::class);
    }
}