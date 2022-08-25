<?php

namespace InetStudio\ClassifiersPackage\Presentation\Console\Commands;

use InetStudio\AdminPanel\Base\Console\Commands\BaseSetupCommand;

class SetupCommand extends BaseSetupCommand
{
    protected $name = 'inetstudio:classifiers-package:setup';

    protected $description = 'Setup classifiers package';

    protected function initCommands(): void
    {
        $this->calls = [
            [
                'type' => 'artisan',
                'description' => '',
                'command' => 'inetstudio:classifiers-package:groups:setup',
            ],
            [
                'type' => 'artisan',
                'description' => '',
                'command' => 'inetstudio:classifiers-package:entries:setup',
            ],
        ];
    }
}
