<?php

namespace InetStudio\ClassifiersPackage\Entries\Presentation\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModelContract;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class EntryType extends GraphQLType
{
    public function __construct(EntryModelContract $model)
    {
        $this->attributes = [
            'name' => $model::ENTITY_TYPE,
            'model' => $model::class,
            'description' => $model::ENTITY_DESCRIPTION,
        ];
    }

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'value' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'alias' => [
                'type' => Type::string(),
            ],
            'groups' => [
                'type' => Type::listOf(GraphQL::type('classifiers_package_group')),
            ],
        ];
    }
}
