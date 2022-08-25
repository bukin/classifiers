<?php

namespace InetStudio\ClassifiersPackage\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use InetStudio\ClassifiersPackage\Presentation\Console\Commands\SetupCommand;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerViews();
    }

    protected function registerConsoleCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands(
            [
                SetupCommand::class,
            ]
        );
    }

    protected function registerPublishes(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../../config/graphql.schemas.php',
            'graphql.schemas'
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../../config/jsonapi.php', 'jsonapi.servers.api.inetstudio'
        );
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../../resources/views', 'inetstudio.classifiers-package');
    }
}
