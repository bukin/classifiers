<?php

namespace InetStudio\ClassifiersPackage\Groups\Presentation\GraphQL\Queries;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use InetStudio\ClassifiersPackage\Groups\Application\Actions\GraphQL\Resolve\ResolveAction;
use InetStudio\ClassifiersPackage\Groups\Application\Actions\GraphQL\Resolve\ResolveData;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModelContract;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class GroupsQuery extends Query
{
    protected $attributes = [
        'name' => 'groups',
    ];

    public function __construct(
        protected ResolveAction $resolveAction
    ) {}

    public function type(): Type
    {
        return Type::nonNull(Type::listOf(Type::nonNull(GraphQL::type(GroupModelContract::ENTITY_TYPE))));
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::string(),
            ],
            'alias' => [
                'name' => 'alias',
                'type' => Type::string(),
            ],
        ];
    }

    public function resolve($root, $arguments, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $data = new ResolveData(compact('root', 'arguments', 'context', 'resolveInfo', 'getSelectFields'));

        return $this->resolveAction->execute($data);
    }
}
