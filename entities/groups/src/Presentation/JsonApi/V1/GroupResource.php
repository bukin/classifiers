<?php

namespace InetStudio\ClassifiersPackage\Groups\Presentation\JsonApi\V1;

use LaravelJsonApi\Core\Resources\JsonApiResource;

class GroupResource extends JsonApiResource
{
    public function attributes($request): iterable
    {
        return [
            'name' => $this->name,
            'alias' => $this->alias,
            'description' => $this->description,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
