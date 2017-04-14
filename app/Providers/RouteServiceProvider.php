<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Litter;
use App\Models\RabbitBreeder;
use App\Models\Pedigree;
use App\Models\RabbitKit;
use App\Models\User;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);
        $router->model('users', User::class);
        $router->model('breeders', RabbitBreeder::class);
        $router->model('pedigrees', Pedigree::class);
        $router->model('litters', Litter::class);
        $router->model('kits', RabbitKit::class);
        $router->model('events', Event::class);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
