<?php

namespace InetStudio\ClassifiersPackage\Entries\Presentation\JsonApi\V1\Filters;

use LaravelJsonApi\Core\Support\Str;
use LaravelJsonApi\Eloquent\Contracts\Filter;
use LaravelJsonApi\Eloquent\Filters\Concerns\IsSingular;

class GroupFilter implements Filter
{
    use IsSingular;

    private string $name;

    private string $column;

    public static function make(string $name, string $column = null): self
    {
        return new static($name, $column);
    }

    public function __construct(string $name, string $column = null)
    {
        $this->name = $name;
        $this->column = $column ?: Str::underscore($name);
    }

    public function key(): string
    {
        return $this->name;
    }

    public function apply($query, $value)
    {
        $query->whereHas(
                'groups',
                function ($query) use ($value) {
                    $query->where('name', '=', $value)->orWhere('alias', '=', $value);
                }
            );
    }
}
