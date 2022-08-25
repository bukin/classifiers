<?php

namespace InetStudio\ClassifiersPackage\Entries\Application\Actions\Tables\Index\GetData;

use InetStudio\AdminPanel\Base\Infrastructure\Contracts\Services\Back\TableServiceContract;
use InetStudio\AdminPanel\Base\Infrastructure\Services\Back\Tables\TableData;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModelContract;

class GetDataAction
{
    public function __construct(
        protected TableServiceContract $tableService,
        protected EntryModelContract $model
    ) {}

    public function execute(): TableData
    {
        return $this->tableService->getData($this->model);
    }
}
