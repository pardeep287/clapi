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

$router->group(["prefix" => "/auth"], function($router) {
    $router->post("", "AuthController@authenticate");
    $router->post("/app", "AuthController@appAuthenticate");
    $router->post("/verification", "AuthController@verification")->name('auth.verification');
    $router->post("/edit", "AuthController@editAppAuth")->name('auth.edit');
    $router->post("/get/jbcoins", "AuthController@getJbCoins");
});

$router->group(["prefix" => "/users"], function($router) {
    $router->post("", "UsersController@save");
});

$router->group(["prefix" => "/games"], function($router) {
    $router->group(["prefix" => "/dond", "middleware" => ["auth.api"]], function($router) {
        $router->get("/u/stats", "Games\DealNoDealController@getUserStats");
        $router->post("/u/enter", "Games\DealNoDealController@enterGame");
        $router->post("/u/play", "Games\DealNoDealController@playGame");
        $router->post("/u/finish", "Games\DealNoDealController@finishGame");
    });

    $router->group(["prefix" => "/uo", "middleware" => ["auth.api"]], function($router) {
        $router->get("/u/stats", "Games\UnderOverController@getUserStats");
        $router->post("/u/enter", "Games\UnderOverController@enterGame");
        $router->post("/u/play", "Games\UnderOverController@playGame");
        $router->post("/u/finish", "Games\UnderOverController@finishGame");
        $router->post("/u/choice/save", "Games\UnderOverController@saveChoice");
    });
});

$router->group(["prefix" => "/booklets"], function($router) {
    $router->get('','BookletMembershipsController@index')->name('booklets.all');
    $router->get('/{id}','BookletMembershipsController@show')->name('booklets.show');
    $router->get('/{bookletId}/deals','BookletMembershipDealsController@index')->name('booklets.deals.all');
    $router->get('/{bookletId}/deals/{dealId}','BookletMembershipDealsController@show')->name('booklets.deals.show');
});


