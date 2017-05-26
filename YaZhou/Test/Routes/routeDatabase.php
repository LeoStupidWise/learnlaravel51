<?php
/**
 * Created by PhpStorm.
 * User: YaZhou
 * Date: 2017/1/20
 * Time: 17:53
 */

use Illuminate\Support\Facades\Route;

Route::group([
    "prefix" => "database"
], function(){
    Route::get("connect",                        "DatabaseController@connect");
    /*编码*/
});

Route::get("test",                                "TestController@test");
Route::get("login",                               "TestController@authLogin");

Route::get("crawler",                             "TestController@crawler");

Route::group([
    "prefix"  =>  "curl"
], function(){
    Route::get("",                                 "CurlController@basic");
    Route::get("weather",                         "CurlController@getWeather");
    Route::get("crawler",                         "CurlController@crawler");
});

Route::group([
    "prefix"  =>  "redis"
], function(){
    Route::get("concurrent",                      "RedisController@concurrent");
});


Route::group([
    "prefix"  =>  "amz"
], function(){
    Route::get("incorrect/{res_code}",           "TestController@amzIncorrect");
    Route::get("healthPage",                      "TestController@amzHealthPage");
    Route::get("authLogin",                        "TestController@authLogin");

});