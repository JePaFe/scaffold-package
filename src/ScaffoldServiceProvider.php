<?php

namespace JePaFe\Scaffold;

use Illuminate\Support\ServiceProvider;
use JePaFe\Scaffold\Console\Commands\ControllerCommand;
use JePaFe\Scaffold\Console\Commands\FactoryCommand;
use JePaFe\Scaffold\Console\Commands\MigrationCommand;
use JePaFe\Scaffold\Console\Commands\ModelCommand;
use JePaFe\Scaffold\Console\Commands\RequestCommand;
use JePaFe\Scaffold\Console\Commands\ResourceCommand;
use JePaFe\Scaffold\Console\Commands\SeederCommand;
use JePaFe\Scaffold\Console\Commands\ViewCommand;

class ScaffoldServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ModelCommand::class,
                FactoryCommand::class,
                MigrationCommand::class,
                RequestCommand::class,
                SeederCommand::class,
                ResourceCommand::class,
                ViewCommand::class,
                ControllerCommand::class,
            ]);
        }
    }
}
