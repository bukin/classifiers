<?php

namespace InetStudio\ClassifiersPackage\Entries\Application\Actions\Resource\Store;

use InetStudio\AdminPanel\Base\Casters\UuidCast;
use Ramsey\Uuid\UuidInterface;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\DataTransferObject;

class StoreItemData extends DataTransferObject
{
    #[CastWith(UuidCast::class)]
    public ?UuidInterface $id = null;

    public string $value;

    public string $alias;

    public array $groups = [];
}
