<?php

namespace InetStudio\ClassifiersPackage\Entries\Presentation\Http\Resources\Back\Tables\Index;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use InetStudio\AdminPanel\Base\Infrastructure\Services\Back\Tables\TableData;
use InetStudio\AdminPanel\Base\Infrastructure\Services\Back\VueService;

class DataResource extends JsonResource
{
    protected VueService $vueService;

    public function __construct(
        TableData $resource
    ) {
        parent::__construct($resource);

        $this->vueService = resolve(VueService::class);
    }

    public function toArray($request): array
    {
        $data = [];

        foreach ($this->resource->data as $item) {
            $data[] = [
                'id' => $item['id'],
                'groups' => $this->vueService->getComponentJsonData(
                    'classifiers-package_entries_partials_tables_groups',
                    [
                        'itemProp' => $item
                    ]
                ),
                'value' => $item['value'],
                'alias' => $item['alias'],
                'created_at' => (string) Carbon::parse($item['created_at']),
                'updated_at' => (string) Carbon::parse($item['updated_at']),
                'actions' => $this->vueService->getComponentJsonData(
                    'classifiers-package_entries_partials_tables_actions',
                    [
                        'itemProp' => $item
                    ]
                )
            ];
        }

        return $data;
    }

    public function with($request)
    {
        return [
            'draw' => $this->resource->draw,
            'recordsTotal' => $this->resource->recordsTotal,
            'recordsFiltered' => $this->resource->recordsFiltered,
        ];
    }
}
