<?php

namespace InetStudio\ClassifiersPackage\Groups\Application\Actions\GraphQL\Resolve;

use Illuminate\Database\Eloquent\Collection;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModelContract;

class ResolveAction
{
    public function __construct(
        protected GroupModelContract $model
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
