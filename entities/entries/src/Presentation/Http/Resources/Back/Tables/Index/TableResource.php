<?php

namespace InetStudio\ClassifiersPackage\Entries\Presentation\Http\Resources\Back\Tables\Index;

use Illuminate\Http\Resources\Json\JsonResource;

class TableResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request): array
    {
        return [];
    }

    public function with($request): array
    {
        return [
            'meta' => [
                'options' => $this->resource,
            ],
        ];
    }
}
