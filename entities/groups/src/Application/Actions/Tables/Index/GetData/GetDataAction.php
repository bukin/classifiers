<?php

namespace InetStudio\ClassifiersPackage\Groups\Application\Actions\Tables\Index\GetData;

use InetStudio\AdminPanel\Base\Infrastructure\Contracts\Services\Back\TableServiceContract;
use InetStudio\AdminPanel\Base\Infrastructure\Services\Back\Tables\TableData;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModelContract;

class GetDataAction
{
    public function __construct(
        protected TableServiceContract $tableService,
        protected GroupModelContract $model
    ) {}

    public function execute(): TableData
    {
        return $this->tableService->getData($this->model);
    }
}
