<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Debugbar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // show debugbar only on local development
        if (env('DEBUGBAR_ENABLED')) {
            Debugbar::enable();
        } else {
            Debugbar::disable();
        }
    }
}
