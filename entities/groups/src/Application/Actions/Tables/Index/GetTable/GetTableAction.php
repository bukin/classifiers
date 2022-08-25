<?php

namespace InetStudio\ClassifiersPackage\Groups\Application\Actions\Tables\Index\GetTable;

use InetStudio\AdminPanel\Base\Infrastructure\Contracts\Services\Back\TableServiceContract;

class GetTableAction
{
    public function __construct(
        protected TableServiceContract $tableService
    ) {}

    public function execute(): array
    {
        return $this->tableService->getOptions();
    }
}
