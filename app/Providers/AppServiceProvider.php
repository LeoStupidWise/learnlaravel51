<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Yz\BasicServiceProvider;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->app->register(BasicServiceProvider::class);
//        App::register("\Yz\BasicServiceProvider");
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}