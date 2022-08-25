<?php

namespace InetStudio\ClassifiersPackage\Entries\Infrastructure\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use InetStudio\AdminPanel\Base\Infrastructure\Contracts\Services\Back\TableServiceContract;
use InetStudio\ClassifiersPackage\Entries\Application\Actions\Tables\Index\GetData\GetDataAction;
use InetStudio\ClassifiersPackage\Entries\Application\Actions\Tables\Index\GetTable\GetTableAction;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModel;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModelContract;
use InetStudio\ClassifiersPackage\Entries\Infrastructure\Services\Back\Tables\Index\DataTablesService;
use InetStudio\ClassifiersPackage\Entries\Presentation\Console\Commands\SetupCommand;
use InetStudio\ClassifiersPackage\Entries\Presentation\JsonApi\V1\Policies\EntryPolicy;

class ServiceProvider extends BaseServiceProvider
{
    public array $bindings = [
        EntryModelContract::class => EntryModel::class
    ];

    public function provides(): array
    {
        return array_keys($this->bindings);
    }

    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerPolicies();
        $this->registerBindings();
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
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../../../database/migrations/create_classifiers_package_entries_tables.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_classifiers_package_entries_tables.php'),
        ], 'migrations');
    }

    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../../routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/../../../routes/web.php');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../../resources/views', 'inetstudio.classifiers-package.entries');
    }

    protected function registerPolicies(): void
    {
        $model = resolve(EntryModelContract::class);

        Gate::policy($model::class, EntryPolicy::class);
    }

    protected function registerBindings(): void
    {
        $this->app->when(GetTableAction::class)
            ->needs(TableServiceContract::class)
            ->give(function () {
                return new DataTablesService();
            });

        $this->app->when(GetDataAction::class)
            ->needs(TableServiceContract::class)
            ->give(function () {
                return new DataTablesService();
            });
    }
}
