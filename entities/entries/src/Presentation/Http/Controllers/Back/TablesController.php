<?php

namespace InetStudio\ClassifiersPackage\Entries\Presentation\Http\Controllers\Back;

use InetStudio\AdminPanel\Base\Http\Controllers\Controller;
use InetStudio\ClassifiersPackage\Entries\Application\Actions\Tables\Index\GetData\GetDataAction as GetIndexDataAction;
use InetStudio\ClassifiersPackage\Entries\Application\Actions\Tables\Index\GetTable\GetTableAction as GetIndexTableAction;
use InetStudio\ClassifiersPackage\Entries\Presentation\Http\Requests\Back\Tables\Index\GetDataRequest as GetIndexDataRequest;
use InetStudio\ClassifiersPackage\Entries\Presentation\Http\Requests\Back\Tables\Index\GetTableRequest as GetIndexTableRequest;
use InetStudio\ClassifiersPackage\Entries\Presentation\Http\Responses\Back\Tables\Index\GetDataResponse as GetIndexDataResponse;
use InetStudio\ClassifiersPackage\Entries\Presentation\Http\Responses\Back\Tables\Index\GetTableResponse as GetIndexTableResponse;

class TablesController extends Controller
{
    public function getIndexTable(GetIndexTableRequest $request, GetIndexTableAction $action, GetIndexTableResponse $response): GetIndexTableResponse
    {
        return $this->process($request, $action, $response);
    }

    public function getIndexData(GetIndexDataRequest $request, GetIndexDataAction $action, GetIndexDataResponse $response): GetIndexDataResponse
    {
        return $this->process($request, $action, $response);
    }
}
