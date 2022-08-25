<?php

namespace InetStudio\ClassifiersPackage\Groups\Infrastructure\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModelContract;

class ItemsService
{
    public function __construct(
        protected GroupModelContract $model
    ) {}

    public function getByAliases(string|array $aliases): Collection
    {
        $aliases = Arr::wrap($aliases);

        return $this->model::whereIn('alias', $aliases)->get();
    }
}
