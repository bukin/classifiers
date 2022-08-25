<?php

namespace InetStudio\ClassifiersPackage\Groups\Infrastructure\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use InetStudio\AdminPanel\Base\Infrastructure\Contracts\Services\Back\TableServiceContract;
use InetStudio\ClassifiersPackage\Groups\Application\Actions\Tables\Index\GetData\GetDataAction;
use InetStudio\ClassifiersPackage\Groups\Application\Actions\Tables\Index\GetTable\GetTableAction;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModel;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModelContract;
use InetStudio\ClassifiersPackage\Groups\Infrastructure\Services\Back\Tables\Index\DataTablesService;
use InetStudio\ClassifiersPackage\Groups\Presentation\Console\Commands\SetupCommand;
use InetStudio\ClassifiersPackage\Groups\Presentation\JsonApi\V1\Policies\GroupPolicy;

class ServiceProvider extends BaseServiceProvider
{
    public array $bindings = [
        GroupModelContract::class => GroupModel::class
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
            __DIR__ . '/../../../database/migrations/create_classifiers_package_groups_tables.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_classifiers_package_groups_tables.php'),
        ], 'migrations');
    }

    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../../routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/../../../routes/web.php');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../../resources/views', 'inetstudio.classifiers-package.groups');
    }

    protected function registerPolicies(): void
    {
        $model = resolve(GroupModelContract::class);

        Gate::policy($model::class, GroupPolicy::class);
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
