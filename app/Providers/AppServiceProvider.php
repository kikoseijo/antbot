<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Config;
use App\Services\LaravelPython;
use Illuminate\Support\Facades\Schema;

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
        $settings = Schema::hasTable('configs') ? Config::find(1) : new Config;
        \View::share('settings', $settings);
    }
}
