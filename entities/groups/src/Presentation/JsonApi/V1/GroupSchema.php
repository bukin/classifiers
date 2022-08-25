<?php

namespace InetStudio\ClassifiersPackage\Groups\Presentation\JsonApi\V1;

use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModel;
use InetStudio\ClassifiersPackage\Groups\Presentation\JsonApi\V1\Filters\SuggestionFilter;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsToMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

class GroupSchema extends Schema
{
    public static string $model = GroupModel::class;

    protected bool $selfLink = false;

    protected ?string $uriType = 'groups';

    public static function type(): string
    {
        return 'groups';
    }

    public function fields(): array
    {
        return [
            ID::make()->uuid()->clientIds(),
            Str::make('name'),
            Str::make('alias'),
            Str::make('description'),
            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),
            BelongsToMany::make('entries'),
        ];
    }

    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
            Where::make('alias')->singular(),
            SuggestionFilter::make('suggestion'),
        ];
    }

    public function pagination(): ?Paginator
    {
        return PagePagination::make();
    }
}
