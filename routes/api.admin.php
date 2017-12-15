<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//Countries Routes
Route::resource('countries','CountriesController', [
    'except' => ['create','edit']
]);




//states Routes
Route::resource('states','StatesController', [
    'except' => ['create','edit']
]);
Route::get('countries/{id}/states' ,'StatesController@StateCountriesWise');

Route::resource('cities', 'CitiesController',
    ['names' => [
        'index'     => 'cities.index',
        'store'     => 'cities.store',
        'update'    => 'cities.update',
        'destroy'   => 'cities.destroy'
    ],
        'except' => ['create','edit']
    ]);
Route::get('states/{id}/cities' ,'CitiesController@CityStateWise');

//Areas Routes
Route::resource('areas','AreasController',[
    'except' => ['create','edit']
]);
Route::get('cities/{id}/areas' ,'AreasController@areaCityWise');

//Stores Routes
Route::resource('stores','StoresController',[
    'except' => ['create','edit']
]);


//Categories Routes
Route::apiResource('categories','CategoriesController');
//Route::any('category' ,'CategoriesController@getAllCategory');
Route::get('categories/{id}/sub-categories' ,'CategoriesController@getSubCategories');

$router->group(["prefix" => ""], function($router) {
   /* $router->group(["prefix" => "/countries", "as" => "countries."], function($router) {
        $router->get('','CountriesController@index')->name('all');
        $router->post('','CountriesController@save')->name('save');
        $router->get('/{id}','CountriesController@show')->name('show');
        $router->patch('/{id}','CountriesController@update')->name('update');
        $router->delete('/{id}','CountriesController@destroy')->name('destroy');
    });*/

    /*$router->group(["prefix" => "/states", "as" => "states."], function($router) {
        $router->get('','StatesController@index')->name('all');
        $router->post('/save','StatesController@save')->name('save');
        $router->get('/{id}','StatesController@show')->name('show');
        $router->patch('/{id}','StatesController@update')->name('update');
        $router->delete('/{id}','StatesController@destroy')->name('destroy');
    });*/

    /*$router->group(["prefix" => "/cities", "as" => "cities."], function($router) {
        $router->get('','CitiesController@index')->name('all');
        $router->post('/save','CitiesController@save')->name('save');
        $router->get('/{id}','CitiesController@show')->name('show');
        $router->patch('/{id}','CitiesController@update')->name('update');
        $router->delete('/{id}','CitiesController@destroy')->name('destroy');
    });*/

    $router->group(["prefix" => "/horoscope-categories", "as" => "horoscope-categories."], function($router) {
        $router->get('','HoroscopeCategoriesController@index')->name('all');
        $router->post('/save','HoroscopeCategoriesController@save')->name('save');
        $router->get('/{id}','HoroscopeCategoriesController@show')->name('show');
        $router->patch('/{id}','HoroscopeCategoriesController@update')->name('update');
        $router->delete('/{id}','HoroscopeCategoriesController@destroy')->name('destroy');

        $router->get('/{categoryId}/horoscopes','HoroscopesController@byCategoryId')->name('byCategoryId');
    });

    $router->group(["prefix" => "/horoscopes", "as" => "horoscopes."], function($router) {
        $router->get('','HoroscopesController@index')->name('all');
        $router->post('/save','HoroscopesController@save')->name('save');
        $router->get('/{id}','HoroscopesController@show')->name('show');
        $router->patch('/{id}','HoroscopesController@update')->name('update');
        $router->delete('/{id}','HoroscopesController@destroy')->name('destroy');
    });

    $router->group(["prefix" => "/booklet-memberships", "as" => "booklet-memberships."], function($router) {
        $router->get('','BookletMembershipsController@index')->name('all');
        $router->post('','BookletMembershipsController@save')->name('save');
        $router->get('/{id}','BookletMembershipsController@show')->name('show');
        $router->patch('/{id}','BookletMembershipsController@update')->name('update');
        $router->delete('/{id}','BookletMembershipsController@destroy')->name('destroy');

        $router->group(["prefix" => "/{bookletMembershipId}/deals", "as" => "deals."], function($router) {
            $router->get('','BookletMembershipDealsController@index')->name('all');
            $router->post('','BookletMembershipDealsController@save')->name('save');
            $router->get('/{id}','BookletMembershipDealsController@show')->name('show');
            $router->patch('/{id}','BookletMembershipDealsController@update')->name('update');
            $router->delete('/{id}','BookletMembershipDealsController@destroy')->name('destroy');
        });
    });

    $router->group(["prefix" => "/stores", "as" => "stores."], function($router) {
        $router->get('','StoresController@index')->name('all');
        $router->post('','StoresController@save')->name('save');
        $router->get('/{id}','StoresController@show')->name('show');
        $router->patch('/{id}','StoresController@update')->name('update');
        $router->delete('/{id}','StoresController@destroy')->name('destroy');
    });

    $router->group(["prefix" => "/users", "as" => "users."], function($router) {
        $router->get('','UsersController@index')->name('all');
    });

    //cities listing
    $router->get('/cities/index','CitiesController@index');
    
    //city adding in database
    $router->post('/cities/add','CitiesController@store');
    
    //city updating in database
    $router->post('/cities/update/{id}','CitiesController@update');

    //Advertisement
    $router->group(["prefix" => "/advertisement", "as" => "advertisement."], function($router) {
        //$router->get('/{page}','AdvertisementController@index')->name('all');
        $router->get('/random','AdvertisementController@randomAdvertisement')->name('random');

    });

});

