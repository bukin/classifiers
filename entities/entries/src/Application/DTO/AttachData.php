<?php

namespace InetStudio\ClassifiersPackage\Entries\Application\DTO;

use ArrayAccess;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModelContract;
use Spatie\DataTransferObject\DataTransferObject;

class AttachData extends DataTransferObject
{
    public string|array|ArrayAccess|EntryModelContract|null $entries;

    public array $attributes = [];

    public ?string $collection = 'default';
}
