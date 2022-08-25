<?php

namespace InetStudio\ClassifiersPackage\Entries\Application\Actions\Resource\Destroy;

use InetStudio\AdminPanel\Base\Application\Exceptions\ResourceDoesNotExistException;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModelContract;

class DestroyAction
{
    public function __construct(
        protected EntryModelContract $model
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
