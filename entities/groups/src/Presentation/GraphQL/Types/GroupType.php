<?php

namespace InetStudio\ClassifiersPackage\Groups\Presentation\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModelContract;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class GroupType extends GraphQLType
{
    public function __construct(GroupModelContract $model)
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
            'name' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'alias' => [
                'type' => Type::nonNull(Type::string()),
            ],
            'description' => [
                'type' => Type::string(),
            ],
            'entries' => [
                'type' => Type::listOf(GraphQL::type('classifiers_package_entry')),
            ],
        ];
    }
}
