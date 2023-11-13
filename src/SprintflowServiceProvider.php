<?php

namespace Ades4827\Sprintflow;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SprintflowServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Route::macro('crud', function (string $name, string $controller, array $options = []) {
            $name_prefix = '';
            if (isset($options['name_prefix'])) {
                $name_prefix = $options['name_prefix'];
            }
            static::get("{$name}", "$controller@index")->name("{$name_prefix}{$name}.index");
            static::get("{$name}/datatable", "$controller@datatable")->name("{$name_prefix}{$name}.datatable");
            static::get("{$name}/create", "$controller@create")->name("{$name_prefix}{$name}.create");
            static::get("{$name}/edit", "$controller@edit")->withTrashed()->name("{$name_prefix}{$name}.edit");
            static::get("{$name}/restore", "$controller@restore")->withTrashed()->name("{$name_prefix}{$name}.restore");
            static::get("{$name}/delete", "$controller@delete")->name("{$name_prefix}{$name}.delete");
            static::post("{$name}/status", "$controller@changeStatus")->name("{$name_prefix}{$name}.changeStatus");
        });

        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'sprintflow');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'sprintflow');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            /*$this->publishes([
                __DIR__.'/../config/config.php' => config_path('sprintflow.php'),
            ], 'config');*/

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/sprintflow'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/sprintflow'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/sprintflow'),
            ], 'lang');*/

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
        //$this->mergeConfigFrom(__DIR__.'/../config/config.php', 'sprintflow');

        // Register the main class to use with the facade
        $this->app->singleton('sprintflow', function () {
            return new Sprintflow;
        });
    }
}
