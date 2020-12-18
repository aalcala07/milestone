<?php

namespace Milestone;

use Milestone\Console\CreateGoalSetsCommand;
use Milestone\Console\InstallCommand;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;

class MilestoneServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any package services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerMigrations();
        $this->registerPublishing();
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'milestone');
    }

    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/milestone.php',
            'Milestone'
        );


        $this->commands([
            CreateGoalSetsCommand::class,
            InstallCommand::class,
        ]);
    }

    /**
     * Register the package's routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/milestone'),
            ], 'milestone-assets');

            $this->publishes([
                __DIR__.'/../config/milestone.php' => config_path('milestone.php'),
            ], 'milestone-config');

            $this->publishes([
                __DIR__ . '/../database/seeds/MilestoneSeeder.php' => database_path('seeds/MilestoneSeeder.php'),
            ], 'milestone-seeds');
        }
    }

    private function registerMigrations()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

}