<?php

namespace InetStudio\ClassifiersPackage\Groups\Application\Actions\Resource\Destroy;

use InetStudio\AdminPanel\Base\Application\Exceptions\ResourceDoesNotExistException;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModelContract;

class DestroyAction
{
    public function __construct(
        protected GroupModelContract $model
    ) {}

    /**
     * @throws ResourceDoesNotExistException
     */
    public function execute(DestroyItemData $data): int
    {
         $count = $this->model::destroy($data->id->toString());

         if (! $count) {
             throw ResourceDoesNotExistException::create($data->id->toString());
         }

         return $count;
    }
}
