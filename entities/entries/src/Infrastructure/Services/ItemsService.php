<?php

namespace InetStudio\ClassifiersPackage\Entries\Infrastructure\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModelContract;

class ItemsService
{
    public function __construct(
        protected EntryModelContract $model
    ) {}

    public function getItemEntriesByGroup($item, string $group): Collection
    {
        return $item->classifiers()->whereHas(
            'groups',
            function ($query) use ($group) {
                $query->where('name', '=', $group)->orWhere('alias', '=', $group);
            }
        )->get();
    }

    public function getEntriesByGroup(string $group): Collection
    {
        return $this->model::whereHas(
            'groups',
            function ($query) use ($group) {
                $query->where('name', '=', $group)->orWhere('alias', '=', $group);
            }
        )->get();
    }

    public function getEntriesByAliases(string|array $aliases): Collection
    {
        $aliases = Arr::wrap($aliases);

        return $this->model::whereIn('alias', $aliases)->get();
    }

    public function getEntriesByIdsAndGroup(string|array $ids, string $group): Collection
    {
        $ids = Arr::wrap($ids);

        return $this->model::whereIn('id', $ids)
            ->whereHas(
                'groups',
                function ($query) use ($group) {
                    $query->where('name', '=', $group)->orWhere('alias', '=', $group);
                }
            )
            ->get();
    }
}
