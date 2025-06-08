<?php

namespace Braankoo\LaravelEloquentSnapshot;

use Illuminate\Support\ServiceProvider;

class LaravelEloquentSnapshotServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-eloquent-snapshot.php'),
            ], 'config');

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-eloquent-snapshot');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-eloquent-snapshot', function () {
            return new LaravelEloquentSnapshot;
        });
    }
}
