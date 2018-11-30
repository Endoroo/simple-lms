<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $path = base_path() . '/vendor/laravel/framework/src/Illuminate/Foundation/Exceptions/views';
        if (file_exists($path)) {
            $this->app['view']->addNamespace('errors', $path);
        }
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
