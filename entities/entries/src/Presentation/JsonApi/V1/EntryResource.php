<?php

namespace InetStudio\ClassifiersPackage\Entries\Presentation\JsonApi\V1;

use LaravelJsonApi\Core\Resources\JsonApiResource;

class EntryResource extends JsonApiResource
{
    public function attributes($request): iterable
    {
        return [
            'value' => $this->value,
            'alias' => $this->alias,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
