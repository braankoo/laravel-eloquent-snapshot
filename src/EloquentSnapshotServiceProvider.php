<?php

namespace Braankoo\EloquentSnapshot;

use Illuminate\Support\ServiceProvider;

class EloquentSnapshotServiceProvider extends ServiceProvider
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

            $this->commands([
                Console\Command\Restore::class,
                Console\Command\Purge::class,
            ]);
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
        $this->app->singleton('eloquent-snapshot', function ($app) {
            return $app->make(EloquentSnapshot::class);
        });
    }
}
