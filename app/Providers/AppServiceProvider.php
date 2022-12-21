<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\LaravelPython;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Solo cargamos esta clase cuando la inyectemos una única vez.
        $this->app->singleton('python', function () { return new LaravelPython();});
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
