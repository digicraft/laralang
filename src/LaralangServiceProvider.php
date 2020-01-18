<?php

namespace Digicraft\Laralang;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

class LaralangServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/laralang.php' => config_path('laralang.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laralang.php', 'laralang');

        $this->app->bind(Manager::class, function () {
            return new Manager(
                new Filesystem,
                $this->app['config']['laralang.path'],
                array_merge($this->app['config']['view.paths'], [$this->app['path']])
            );
        });

        $this->commands([
            \Digicraft\Laralang\Commands\MissingCommand::class,
            \Digicraft\Laralang\Commands\RemoveCommand::class,
            \Digicraft\Laralang\Commands\TransCommand::class,
            \Digicraft\Laralang\Commands\ShowCommand::class,
            \Digicraft\Laralang\Commands\FindCommand::class,
            \Digicraft\Laralang\Commands\SyncCommand::class,
            \Digicraft\Laralang\Commands\RenameCommand::class,
        ]);
    }
}
