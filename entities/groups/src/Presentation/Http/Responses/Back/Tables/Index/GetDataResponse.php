<?php

namespace InetStudio\ClassifiersPackage\Groups\Presentation\Http\Responses\Back\Tables\Index;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use InetStudio\AdminPanel\Base\Infrastructure\Services\Back\Tables\TableData;
use InetStudio\ClassifiersPackage\Groups\Presentation\Http\Resources\Back\Tables\Index\DataResource;

class GetDataResponse implements Responsable
{
    protected TableData $result;

    public function setResult(TableData $result): void
    {
        $this->result = $result;
    }

    public function toResponse($request): JsonResponse
    {
        $resource = new DataResource($this->result);

        return $resource->response();
    }
}
