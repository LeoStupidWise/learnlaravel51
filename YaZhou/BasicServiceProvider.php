<?php
/**
 * Created by PhpStorm.
 * User: YaZhou
 * Date: 2017/1/20
 * Time: 12:00
 */

namespace Yz;

use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class BasicServiceProvider extends ServiceProvider{

    public function register()
    {
        // TODO: Implement register() method.
    }

    public function boot()
    {
        //
        App::register("Yz\Test\Providers\TestServiceProvider");
    }

}