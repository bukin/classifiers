<?php

namespace InetStudio\ClassifiersPackage\Entries\Application\Actions\Resource\Destroy;

use InetStudio\AdminPanel\Base\Casters\UuidCast;
use Ramsey\Uuid\UuidInterface;
use Spatie\DataTransferObject\Attributes\CastWith;
use Spatie\DataTransferObject\DataTransferObject;

class DestroyItemData extends DataTransferObject
{
    #[CastWith(UuidCast::class)]
    public UuidInterface $id;
}
