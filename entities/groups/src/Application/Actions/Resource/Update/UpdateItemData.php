<?php

namespace InetStudio\ClassifiersPackage\Groups\Application\Actions\Resource\Update;

use InetStudio\AdminPanel\Base\Casters\UuidCast;
use Ramsey\Uuid\UuidInterface;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\DataTransferObject;

class UpdateItemData extends DataTransferObject
{
    #[CastWith(UuidCast::class)]
    public UuidInterface $id;

    public ?string $name;

    public ?string $alias;

    public ?string $description;

    public ?array $entries = [];
}
