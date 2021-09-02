<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/'], function ($app) {

    $app->post('register', 'AuthController@register');
    $app->post('login', 'AuthController@login');
    $app->get('verify/{token}', 'AuthController@verifyAccount');

    $app->group(['middleware' => ['auth','IsVerifyEmail']], function ($app) {
        $app->post('article', 'ArticlesController@store');
        $app->get('article', 'ArticlesController@index');
        $app->get('article/{id}', 'ArticlesController@show');
        $app->put('article/{id}', 'ArticlesController@update');
        $app->delete('article/{id}', 'ArticlesController@destroy');
    });

});
