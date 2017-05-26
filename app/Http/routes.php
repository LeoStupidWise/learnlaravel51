<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
//    echo "Ys";
    return view('welcome');
});

//Route::get('/', "TestController@test");

Route::get("/old", function(){
\Illuminate\Support\Facades\Log::emergency("This is a warning");
}) -> middleware("old");

Route::get("test/do", function(){
    echo "Hello, I am do";
});


Route::get('auth/login',   'Auth\AuthController@getLogin');
Route::post('auth/login',  'Auth\AuthController@postLogin');
Route::get('auth/logout',  'Auth\AuthController@getLogout');

Route::get("auth/register", 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');