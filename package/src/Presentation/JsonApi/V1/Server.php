<?php

namespace InetStudio\ClassifiersPackage\Presentation\JsonApi\V1;

use InetStudio\ClassifiersPackage\Entries\Presentation\JsonApi\V1\EntrySchema;
use InetStudio\ClassifiersPackage\Groups\Presentation\JsonApi\V1\GroupSchema;
use LaravelJsonApi\Core\Server\Server as BaseServer;

class Server extends BaseServer
{
    protected string $baseUri = '/api/inetstudio/classifiers-package/v1/';

    public function serving(): void
    {
        // no-op
    }

    protected function allSchemas(): array
    {
        return [
            EntrySchema::class,
            GroupSchema::class,
        ];
    }
}
