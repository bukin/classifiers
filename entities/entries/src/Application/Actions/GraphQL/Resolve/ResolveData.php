<?php

namespace InetStudio\ClassifiersPackage\Entries\Application\Actions\GraphQL\Resolve;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Spatie\DataTransferObject\DataTransferObject;

class ResolveData extends DataTransferObject
{
    public mixed $root = null;

    public array $arguments = [];

    public mixed $context = null;

    public ?ResolveInfo $resolveInfo = null;

    public ?Closure $getSelectFields = null;
}

