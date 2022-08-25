<?php

namespace InetStudio\ClassifiersPackage\Entries\Domain\Traits;

use ArrayAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use InetStudio\ClassifiersPackage\Entries\Application\DTO\AttachData;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\ClassifierableModel;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModelContract;

trait HasClassifiers
{
    use HasClassifiersCollection;

    protected array $queuedClassifiers = [];

    public function getClassifierClassName(): string
    {
        $model = resolve(EntryModelContract::class);

        return $model::class;
    }

    public function classifiers(?string $collection = null): MorphToMany
    {
        $className = $this->getClassifierClassName();

        $relation = $this->morphToMany($className, 'classifierable', 'classifiers_package_classifierables')
            ->withPivot(['id', 'collection'])
            ->using(ClassifierableModel::class)
            ->withTimestamps();

        if ($collection !== null) {
            $relation = $relation->wherePivot('collection', $collection);
        }

        return $relation;
    }

    public function setClassifiersAttribute(AttachData $attachClassifiersData): void
    {
        if (! $this->exists) {
            $this->queuedClassifiers[] = $attachClassifiersData;

            return;
        }

        $this->attachClassifiers($attachClassifiersData->entries, $attachClassifiersData->attributes, $attachClassifiersData->collection);
    }

    public static function bootHasClassifiers(): void
    {
        static::created(
            function (Model $classifierableModel) {
                if (! empty($classifierableModel->queuedClassifiers)) {
                    foreach ($classifierableModel->queuedClassifiers as $classifiersData) {
                        $classifierableModel->attachClassifiers(
                            classifiers: $classifiersData->entries,
                            attributes: $classifiersData->attributes,
                            collection: $classifiersData->collection,
                        );
                    }

                    $classifierableModel->queuedClassifiers = [];
                }
            }
        );

        static::deleted(
            function (Model $classifierableModel) {
                $classifierableModel->syncClassifiers(null);
            }
        );
    }

    public function getClassifiersList(
        ?string $collection = null,
    ): array {
        $list = $this->classifiers($collection)
            ->get()
            ->groupBy('pivot.collection')
            ->map(function ($item, $key) {
                return collect($item)->mapWithKeys(function($classifier) {
                    return [$classifier->id => $classifier->alias];
                })->toArray();
            })
            ->toArray();

        return ($collection !== null) ? ($list[$collection] ?? []) : $list;
    }

    public function scopeWithAllClassifiers(
        Builder $query,
        string|array|ArrayAccess|EntryModelContract $classifiers = []
    ): Builder {
        $classifiers = $this->hydrateClassifiers($classifiers)->pluck('id')->toArray();

        collect($classifiers)->each(
            function ($classifier) use ($query) {
                $query->whereHas(
                    'classifiers',
                    function (Builder $query) use ($classifier) {
                        return $query->where('classifiers_package_entries.id', $classifier);
                    }
                );
            }
        );

        return $query;
    }

    public function scopeWithAnyClassifiers(
        Builder $query,
        string|array|ArrayAccess|EntryModelContract $classifiers = []
    ): Builder {
        $classifiers = $this->hydrateClassifiers($classifiers)->pluck('id')->toArray();

        return $query
            ->whereHas(
            'classifiers',
                function (Builder $query) use ($classifiers) {
                    $query->whereIn('classifiers_package_entries.id', $classifiers);
                }
            );
    }

    public function scopeWithClassifiers(
        Builder $query,
        string|array|ArrayAccess|EntryModelContract $classifiers = []
    ): Builder {
        return $this->scopeWithAnyClassifiers($query, $classifiers);
    }

    public function scopeWithoutClassifiers(
        Builder $query,
        string|array|ArrayAccess|EntryModelContract $classifiers = []
    ): Builder {
        $classifiers = $this->hydrateClassifiers($classifiers)->pluck('id')->toArray();

        return $query
            ->whereDoesntHave(
                'classifiers',
                function (Builder $query) use ($classifiers) {
                    $query->whereIn('classifiers_package_entries.id', $classifiers);
                }
            );
    }

    public function scopeWithoutAnyClassifiers(Builder $query): Builder
    {
        return $query->doesntHave('classifiers');
    }

    public function attachClassifiers(
        string|array|ArrayAccess|EntryModelContract $classifiers = [],
        array $attributes = [],
        string $collection = 'default'
    ): self {
        $this->setClassifiers(
            action: 'syncWithoutDetaching',
            classifiers: $classifiers,
            attributes: $attributes,
            collection: $collection
        );

        return $this;
    }

    public function syncClassifiers(
        string|array|ArrayAccess|EntryModelContract|null $classifiers = [],
        array $attributes = [],
        ?string $collection = 'default'
    ): self {
        $this->setClassifiers(
            action: 'sync',
            classifiers: $classifiers,
            attributes: $attributes,
            collection: $collection
        );

        return $this;
    }

    public function detachClassifiers(
        string|array|ArrayAccess|EntryModelContract|null $classifiers = [],
        ?string $collection = 'default'
    ): self {
        $this->setClassifiers(
            action: 'detach',
            classifiers: $classifiers,
            collection: $collection
        );

        return $this;
    }

    protected function setClassifiers(
        string $action,
        string|array|ArrayAccess|EntryModelContract|null $classifiers = [],
        array $attributes = [],
        ?string $collection = 'default'
    ): void {
        $event = ($action === 'syncWithoutDetaching') ? 'attach' : $action;

        $classifiers = $this->hydrateClassifiers($classifiers)?->pluck('id')->toArray();

        static::$dispatcher->dispatch('inetstudio.classifiers.entries.'.$event.'ing', [$this, $classifiers]);

        match ($action) {
            'syncWithoutDetaching' => $this->classifiers()->attach($this->prepareClassifiersWithAttributes($classifiers, $attributes, $collection)),
            'sync' => $this->classifiers($collection)->sync($this->prepareClassifiersWithAttributes($classifiers, $attributes, $collection)),
            'detach' => $this->classifiers($collection)->detach($classifiers),
        };

        static::$dispatcher->dispatch('inetstudio.classifiers.entries.'.$event.'ed', [$this, $classifiers]);
    }

    protected function hydrateClassifiers(
        string|array|ArrayAccess|EntryModelContract|null $classifiers
    ): ?Collection {
        if ($classifiers === null) {
            return null;
        }

        $isClassifiersUuidBased = $this->isClassifiersUuidBased($classifiers);
        $isClassifiersStringBased = $this->isClassifiersStringBased($classifiers);

        $className = $this->getClassifierClassName();

        return match (true) {
            $isClassifiersUuidBased => $className::query()->whereIn('id', (array) $classifiers)->get(),
            $isClassifiersStringBased => $className::query()->whereIn('alias', (array) $classifiers)->get(),
            ($classifiers instanceof EntryModelContract) => collect([$classifiers]),
            ($classifiers instanceof ArrayAccess || is_array($classifiers)) => collect($classifiers),
        };
    }

    protected function prepareClassifiersWithAttributes(
        ?array $classifiers,
        array $attributes,
        ?string $collection
    ): array {
        $classifiersWithAttributes = [];

        foreach ($classifiers ?? [] as $id) {
            $classifiersWithAttributes[$id] = array_merge($attributes[$id] ?? [], ['collection' => $collection]);
        }

        return $classifiersWithAttributes;
    }
}
