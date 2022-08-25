<?php

namespace InetStudio\ClassifiersPackage\Entries\Application\Actions\Resource\Update;

use InetStudio\AdminPanel\Base\Casters\UuidCast;
use Ramsey\Uuid\UuidInterface;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\DataTransferObject;

class UpdateItemData extends DataTransferObject
{
    #[CastWith(UuidCast::class)]
    public UuidInterface $id;

    public ?string $value;

    public ?string $alias;

    public ?array $groups = [];
}
