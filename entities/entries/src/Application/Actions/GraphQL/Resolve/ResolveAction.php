<?php

namespace InetStudio\ClassifiersPackage\Entries\Application\Actions\GraphQL\Resolve;

use Illuminate\Database\Eloquent\Collection;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModelContract;

class ResolveAction
{
    public function __construct(
        protected EntryModelContract $model
    ) {}

    public function execute(ResolveData $data): Collection
    {
        $query = $this->model::query();

        if (! empty($data->arguments['id'])) {
            return $query->find($data->arguments['id']);
        }

        return $query->get();
    }
}
