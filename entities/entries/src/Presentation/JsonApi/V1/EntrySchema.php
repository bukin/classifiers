<?php

namespace InetStudio\ClassifiersPackage\Entries\Presentation\JsonApi\V1;

use Illuminate\Support\Str as StrHelper;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModel;
use InetStudio\ClassifiersPackage\Entries\Presentation\JsonApi\V1\Filters\GroupFilter;
use InetStudio\ClassifiersPackage\Entries\Presentation\JsonApi\V1\Filters\SuggestionFilter;
use LaravelJsonApi\Eloquent\Contracts\Paginator;
use LaravelJsonApi\Eloquent\Fields\DateTime;
use LaravelJsonApi\Eloquent\Fields\ID;
use LaravelJsonApi\Eloquent\Fields\Relations\BelongsToMany;
use LaravelJsonApi\Eloquent\Fields\Str;
use LaravelJsonApi\Eloquent\Filters\Where;
use LaravelJsonApi\Eloquent\Filters\WhereIdIn;
use LaravelJsonApi\Eloquent\Pagination\PagePagination;
use LaravelJsonApi\Eloquent\Schema;

class EntrySchema extends Schema
{
    public static string $model = EntryModel::class;

    protected bool $selfLink = false;

    protected ?string $uriType = 'entries';

    public static function type(): string
    {
        return 'entries';
    }

    public function fields(): array
    {
        return [
            ID::make()->uuid()->clientIds(),
            Str::make('value'),
            Str::make('alias'),
            DateTime::make('createdAt')->sortable()->readOnly(),
            DateTime::make('updatedAt')->sortable()->readOnly(),
            BelongsToMany::make('groups'),
        ];
    }

    public function filters(): array
    {
        return [
            WhereIdIn::make($this),
            Where::make('alias')->singular(),
            SuggestionFilter::make('suggestion'),
            GroupFilter::make('group'),
        ];
    }

    public function pagination(): ?Paginator
    {
        return PagePagination::make();
    }
}
