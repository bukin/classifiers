<?php

namespace InetStudio\ClassifiersPackage\Groups\Presentation\Http\Responses\Back\Tables\Index;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use InetStudio\ClassifiersPackage\Groups\Presentation\Http\Resources\Back\Tables\Index\TableResource;

class GetTableResponse implements Responsable
{
    protected array $result;

    public function setResult(array $result): void
    {
        $this->result = $result;
    }

    public function toResponse($request): JsonResponse
    {
        $resource = new TableResource($this->result);

        return $resource->response();
    }
}
