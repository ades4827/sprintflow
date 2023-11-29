<?php

namespace Ades4827\Sprintflow;

use Ades4827\Sprintflow\Commands\CacheTest;
use Ades4827\Sprintflow\Commands\PermissionRefresh;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class SprintflowServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Route::macro('crud', function (string $model, string $controller, array $options = []) {
            $model = new $model();
            // define names
            $model_slug = $model->getClassSlug();
            $section_slug = $model->getClassSlug(true);

            $name_prefix = '';
            if (isset($options['name_prefix'])) {
                $name_prefix = $options['name_prefix'];
            }
            static::get("{$section_slug}", "$controller@index")->name("{$name_prefix}{$section_slug}.index");
            static::get("{$section_slug}/datatable", "$controller@datatable")->name("{$name_prefix}{$section_slug}.datatable");
            static::get("{$section_slug}/create", "$controller@create")->name("{$name_prefix}{$section_slug}.create");
            static::get("{$section_slug}/{".$model_slug."}/edit", "$controller@edit")->withTrashed()->name("{$name_prefix}{$section_slug}.edit");
            static::get("{$section_slug}/{deleted_".$model_slug."}/restore", "$controller@restore")->withTrashed()->name("{$name_prefix}{$section_slug}.restore");
            static::get("{$section_slug}/{".$model_slug."}/delete", "$controller@delete")->name("{$name_prefix}{$section_slug}.delete");
            static::post("{$section_slug}/{".$model_slug."}/status", "$controller@changeStatus")->name("{$name_prefix}{$section_slug}.changeStatus");
        });

        /*
         * Optional methods to load your package assets
         */
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'sprintflow');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'sprintflow');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/sprintflow.php' => config_path('sprintflow.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/sprintflow'),
            ], 'views');

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/sprintflow'),
            ], 'assets');*/

            // Publishing the translation files.
            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/sprintflow'),
            ], 'lang');

            // Registering package commands.
            $this->commands([
                PermissionRefresh::class,
                CacheTest::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/sprintflow.php', 'sprintflow');

        // Register the main class to use with the facade
        $this->app->singleton('sprintflow', function () {
            return new Sprintflow;
        });
    }
}
