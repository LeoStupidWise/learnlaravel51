<?php
/**
 * Created by PhpStorm.
 * User: YaZhou
 * Date: 2017/1/20
 * Time: 17:49
 */

namespace Yz\Test\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider{
    public function register()
    {
        // TODO: Implement register() method.
    }

    public function boot()
    {
        Route::group([
            "prefix" => "test",
            "namespace" => "Yz\Test\Controllers",
        ], function(){
//            $this->mergeConfigFrom(__DIR__ . "/../Config/recovery.conf.php", 'recovery_admin');
            $this->loadViewsFrom(base_path("YaZhou/Test/Views"), "test");
            Route::get("index", function(){
                echo "Hello test index";
            });
            require __DIR__ . "/../Routes/routeDatabase.php";
        });
//        $this->loadViewsFrom(__DIR__."/../Views", "recovery_admin");
    }
}