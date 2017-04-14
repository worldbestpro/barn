<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/


Route::group(['prefix' => 'admin', 'middleware' => ['web', 'auth']], function () {
    Route::post('images/uploadImage', 'ImagesController@uploadImage');

    Route::get('roles/getList', 'Admin\AdminRolesController@getList');
    Route::get('users/{users}/settings', 'Admin\AdminUsersController@settings');
    Route::get('users/upcoming', 'Admin\AdminUsersController@upcomingEvents');
    Route::get('users/plans', 'Admin\AdminUsersController@plans');
    Route::post('users/{users}/settings', 'Admin\AdminUsersController@updateSettings');
    Route::post('users/{users}/logo', 'Admin\AdminUsersController@updateSettingsLogo');
    Route::get('users/events', 'Admin\AdminUsersController@events');
    Route::post('users/dashboard', 'Admin\AdminUsersController@dashboard');
    Route::resource('users', 'Admin\AdminUsersController');

    Route::get('breeders/{id}/pdf', 'Admin\AdminRabbitBreedersController@getPdf');
    Route::get('breeders/getList', 'Admin\AdminRabbitBreedersController@getList');
    Route::get('breeders/{breeders}/getLitters', 'Admin\AdminRabbitBreedersController@getLitters');
    Route::get('breeders/{breeders}/getPedigree', 'Admin\AdminRabbitBreedersController@getPedigree');
    Route::get('breeders/{breeders}/events', 'Admin\AdminRabbitBreedersController@events');
    Route::get('breeders/checkId', 'Admin\AdminRabbitBreedersController@checkId');
    Route::post('breeders/{breeders}/archive', 'Admin\AdminRabbitBreedersController@archive');
    Route::resource('breeders', 'Admin\AdminRabbitBreedersController');

    Route::resource('pedigrees', 'Admin\AdminPedigreesController');

    Route::get('litters/{litters}/weigh', 'Admin\AdminLittersController@weigh');
    Route::post('litters/{litters}/weigh', 'Admin\AdminLittersController@postWeigh');
    Route::get('litters/{litters}/events', 'Admin\AdminLittersController@events');
    Route::post('litters/{litters}/archive', 'Admin\AdminLittersController@archive');
    Route::get('litters/{litters}/getKits', 'Admin\AdminLittersController@getKits');
    Route::get('litters/getList', 'Admin\AdminLittersController@getList');
    Route::resource('litters', 'Admin\AdminLittersController');

    Route::post('kits/{kits}/weigh', 'Admin\AdminRabbitKitsController@weigh');
    Route::get('kits/{kits}/died', 'Admin\AdminRabbitKitsController@died');
    Route::post('kits/{kits}/archive', 'Admin\AdminRabbitKitsController@archive');
    Route::post('kits/butch', 'Admin\AdminRabbitKitsController@butch');
    Route::get('kits/{kits}/makeBreeder', 'Admin\AdminRabbitKitsController@makeBreeder');
    Route::resource('kits', 'Admin\AdminRabbitKitsController');

    Route::post('events/makeBreedPlan','Admin\AdminEventsController@makeBreedPlan');
    Route::get('events/test','Admin\AdminEventsController@test');
    Route::get('events/{events}/close','Admin\AdminEventsController@close');
    Route::post('events/deleteEvents', 'Admin\AdminEventsController@deleteEvents');
    Route::get('events/{events}/reopen','Admin\AdminEventsController@reOpen');
    Route::get('events/breedPlanDummyEvents','Admin\AdminEventsController@breedPlanDummyEvents');
    Route::resource('events','Admin\AdminEventsController');
});

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    Route::get('/', 'HomeController@index');
    Route::get('/colors/List', 'HomeController@index');

    Route::get('pedigree/{id}', 'HomeController@external');

});
/*Route::get('test', function () {
    return response()->json(['error'=>['name']]);
});*/
