<?php


namespace App\Providers;


use App\Models\Litter;
use App\Models\Pedigree;
use App\Models\RabbitBreeder;
use App\Models\RabbitKit;
use App\Models\User;
use Illuminate\Support\ServiceProvider;

class BindingsServiceProvider extends ServiceProvider
{
    public function boot()
    {

    }

    public function register()
    {
        \App::bind('rabbitkit', function ($app) {
            return new RabbitKit();
        });
        \App::bind('rabbit', function ($app) {
            return new RabbitBreeder();
        });
        \App::bind('breeder', function ($app) {
            return new RabbitBreeder();
        });
        \App::bind('pedigree', function ($app) {
            return new Pedigree();
        });
        \App::bind('litter', function ($app) {
            return new Litter();
        });
        \App::bind('user', function ($app) {
            return new User();
        });

    }
}