<?php

namespace InetStudio\ClassifiersPackage\Groups\Presentation\Console\Commands;

use InetStudio\AdminPanel\Base\Console\Commands\BaseSetupCommand;
use InetStudio\ClassifiersPackage\Groups\Infrastructure\Providers\ServiceProvider;

class SetupCommand extends BaseSetupCommand
{
    protected $name = 'inetstudio:classifiers-package:groups:setup';

    protected $description = 'Setup classifiers groups package';

    protected function initCommands(): void
    {
        $this->calls = [
            [
                'type' => 'artisan',
                'description' => 'Publish migrations',
                'command' => 'vendor:publish',
                'params' => [
                    '--provider' => ServiceProvider::class,
                    '--tag' => 'migrations',
                ],
            ],
            [
                'type' => 'artisan',
                'description' => 'Migration',
                'command' => 'migrate',
            ],
        ];
    }
}
