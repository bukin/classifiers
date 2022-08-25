<?php

namespace InetStudio\ClassifiersPackage\Groups\Application\Actions\Resource\Store;

use InetStudio\AdminPanel\Base\Casters\UuidCast;
use Ramsey\Uuid\UuidInterface;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\DataTransferObject;

class StoreItemData extends DataTransferObject
{
    #[CastWith(UuidCast::class)]
    public ?UuidInterface $id = null;

    public string $name;

    public string $alias;

    public ?string $description;

    public array $entries = [];
}
