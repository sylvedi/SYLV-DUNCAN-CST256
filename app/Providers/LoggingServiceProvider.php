<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Utility\FileLogger;

class LoggingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Services\Utility\ILoggerService', function($app){
            return new FileLogger();
        });
    }
}
