<?php

namespace InetStudio\ClassifiersPackage\Groups\Tests;

use InetStudio\ClassifiersPackage\Entries\Infrastructure\Providers\ServiceProvider as EntriesServiceProvider;
use InetStudio\ClassifiersPackage\Groups\Infrastructure\Providers\ServiceProvider as GroupsServiceProvider;
use InetStudio\ClassifiersPackage\Infrastructure\Providers\ServiceProvider as PackageServiceProvider;
use LaravelJsonApi\Core\Facades\JsonApi;
use LaravelJsonApi\Encoder\Neomerx\ServiceProvider as LaravelJsonApiEncoderServiceProvider;
use LaravelJsonApi\Laravel\Facades\JsonApiRoute;
use LaravelJsonApi\Laravel\ServiceProvider as LaravelJsonApiServiceProvider;
use LaravelJsonApi\Spec\ServiceProvider as LaravelJsonApiSpecServiceProvider;
use LaravelJsonApi\Validation\ServiceProvider as LaravelJsonApiValidationServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelJsonApiServiceProvider::class,
            LaravelJsonApiEncoderServiceProvider::class,
            LaravelJsonApiSpecServiceProvider::class,
            LaravelJsonApiValidationServiceProvider::class,
            PackageServiceProvider::class,
            GroupsServiceProvider::class,
            EntriesServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'JsonApi' => JsonApi::class,
            'JsonApiRoute' => JsonApiRoute::class,
        ];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../../../entries/database/migrations');
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('app.key', 'base64:jXEV5lsYsDz/7LtvuEB/4psnit6zHTbFQ57eKSjoKkQ=');
        $app['config']->set('database.default', 'testing');
    }
}
