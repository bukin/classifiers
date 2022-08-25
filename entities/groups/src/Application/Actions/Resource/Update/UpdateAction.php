<?php

namespace InetStudio\ClassifiersPackage\Groups\Application\Actions\Resource\Update;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use InetStudio\AdminPanel\Base\Application\Exceptions\ResourceDoesNotExistException;
use InetStudio\AdminPanel\Base\Application\Exceptions\ResourceExistsException;
use InetStudio\AdminPanel\Base\Application\Exceptions\SaveResourceToDBException;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModelContract;
use Throwable;

class UpdateAction
{
    public function __construct(
        protected GroupModelContract $model
    ) {}

    /**
     * @throws ResourceDoesNotExistException
     * @throws SaveResourceToDBException
     * @throws ResourceExistsException
     */
    public function execute(UpdateItemData $data): ?GroupModelContract
    {
        /** @var  ?GroupModelContract $item */
        $item = $this->model::find($data->id->toString());

        if (! $item) {
            throw ResourceDoesNotExistException::create($data->id);
        }

        $this->checkWithAliasExists($item, $data->alias);

        $preparedData = $this->getDataForUpdate($item, $data);

        try {
            $item = DB::transaction(function() use ($item, $data, $preparedData) {
                foreach ($preparedData as $field => $value) {
                    $item->$field = $value;
                }

                $item->save();

                if ($data->entries !== null) {
                    $item->entries()->sync($data->entries);
                }

                return $item->fresh();
            });
        } catch (Throwable $e) {
            throw SaveResourceToDBException::create();
        }

        return $item;
    }

    /**
     * @throws ResourceExistsException
     */
    protected function checkWithAliasExists(GroupModelContract $item, string $alias): void
    {
        $exists = $this->model::where('id', '!=', $item->id)->where('alias', '=', $alias)->exists();

        if ($exists) {
            throw ResourceExistsException::resourceWithFieldExists('alias', $alias);
        }
    }

    protected function getDataForUpdate(GroupModelContract $item, UpdateItemData $data): array
    {
        $currentItemData = $item->attributesToArray();
        $requestData = collect($data->toArray())->filter()->toArray();

        Arr::forget($requestData, ['entries']);

        return array_merge($currentItemData, $requestData);
    }
}
