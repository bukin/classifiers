<?php

namespace InetStudio\ClassifiersPackage\Entries\Domain\Traits;

use ArrayAccess;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModelContract;

trait HasClassifiersCollection
{
    public function hasClassifier(string|array|ArrayAccess|EntryModelContract $classifiers): bool
    {
        if ($this->isClassifiersUuidBased($classifiers)) {
            $classifiers = (array) $classifiers;

            return ! $this->classifiers->pluck('id')->intersect($classifiers)->isEmpty();
        }

        if ($this->isClassifiersStringBased($classifiers)) {
            $classifiers = (array) $classifiers;

            return ! $this->classifiers->pluck('alias')->intersect($classifiers)->isEmpty();
        }

        if ($classifiers instanceof EntryModelContract) {
            return $this->classifiers->contains('alias', $classifiers['alias']);
        }

        if ($classifiers instanceof Collection) {
            return ! $classifiers->intersect($this->classifiers->pluck('alias'))->isEmpty();
        }

        return false;
    }

    public function hasAnyClassifier(string|array|ArrayAccess|EntryModelContract $classifiers): bool
    {
        return $this->hasClassifier($classifiers);
    }

    public function hasAllClassifiers(string|array|ArrayAccess|EntryModelContract $classifiers): bool
    {
        if ($this->isClassifiersUuidBased($classifiers)) {
            $classifiers = (array) $classifiers;

            return ! $this->classifiers->pluck('id')->intersect($classifiers)->isEmpty();
        }

        if ($this->isClassifiersStringBased($classifiers)) {
            $classifiers = (array) $classifiers;

            return $this->classifiers->pluck('alias')->intersect($classifiers)->count() == count($classifiers);
        }

        if ($classifiers instanceof EntryModelContract) {
            return $this->classifiers->contains('alias', $classifiers['alias']);
        }

        if ($classifiers instanceof Collection) {
            return $this->classifiers->intersect($classifiers)->count() == $classifiers->count();
        }

        return false;
    }

    protected function isClassifiersUuidBased(string|array|ArrayAccess|EntryModelContract $classifiers): bool
    {
        return Str::isUuid($classifiers) || (is_array($classifiers) && isset($classifiers[0]) && Str::isUuid($classifiers[0]));
    }

    protected function isClassifiersStringBased(string|array|ArrayAccess|EntryModelContract $classifiers): bool
    {
        return (is_string($classifiers) && ! Str::isUuid($classifiers)) || (is_array($classifiers) && isset($classifiers[0]) && is_string($classifiers[0]) && ! Str::isUuid($classifiers[0]));
    }
}
