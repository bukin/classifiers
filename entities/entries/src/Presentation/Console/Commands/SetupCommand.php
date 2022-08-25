<?php

namespace InetStudio\ClassifiersPackage\Entries\Presentation\Console\Commands;

use InetStudio\AdminPanel\Base\Console\Commands\BaseSetupCommand;
use InetStudio\ClassifiersPackage\Entries\Infrastructure\Providers\ServiceProvider;

class SetupCommand extends BaseSetupCommand
{
    protected $name = 'inetstudio:classifiers-package:entries:setup';

    protected $description = 'Setup classifiers entries package';

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
