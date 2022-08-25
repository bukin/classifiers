<?php

namespace InetStudio\ClassifiersPackage\Groups\Infrastructure\Services\Back\Tables\Index;

use Illuminate\Database\Eloquent\Model;
use InetStudio\AdminPanel\Base\Infrastructure\Contracts\Services\Back\TableServiceContract;
use InetStudio\AdminPanel\Base\Infrastructure\Services\Back\Tables\TableData;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Services\DataTable;

class DataTablesService extends DataTable implements TableServiceContract
{
    public function getOptions(): array
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->ajax($this->getAjaxOptions())
            ->parameters($this->getParameters())
            ->getOptions();
    }

    public function getData(Model $model): TableData
    {
        $queryData = DataTables::of($model->query())
            ->rawColumns(['actions'])
            ->toArray();

        $results = new TableData();

        $results->draw = $queryData['draw'];
        $results->recordsTotal = $queryData['recordsTotal'];
        $results->recordsFiltered = $queryData['recordsFiltered'];
        $results->data = collect($queryData['data']);

        return $results;
    }

    protected function getColumns(): array
    {
        return [
            ['data' => 'id', 'name' => 'id', 'title' => 'ID', 'className' => 'group-id'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Название', 'className' => 'group-name'],
            ['data' => 'alias', 'name' => 'alias', 'title' => 'Алиас', 'className' => 'group-alias'],
            ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Дата создания', 'className' => 'group-created_at'],
            ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Дата обновления', 'className' => 'group-updated_at'],
            [
                'data' => 'actions',
                'name' => 'actions',
                'title' => 'Действия',
                'orderable' => false,
                'searchable' => false,
                'className' => 'group-actions vue-component',
            ],
        ];
    }

    protected function getAjaxOptions(): array
    {
        return [
            'url' => route('inetstudio.classifiers-package.groups.back.tables.index.data'),
            'type' => 'POST',
        ];
    }

    protected function getParameters(): array
    {
        $translation = trans('admin::datatables');

        return [
            'paging' => true,
            'pagingType' => 'full_numbers',
            'searching' => true,
            'info' => false,
            'searchDelay' => 350,
            'language' => $translation,
        ];
    }
}
