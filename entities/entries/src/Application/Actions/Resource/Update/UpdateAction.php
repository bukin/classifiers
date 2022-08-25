<?php

namespace InetStudio\ClassifiersPackage\Entries\Application\Actions\Resource\Update;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use InetStudio\AdminPanel\Base\Application\Exceptions\ResourceDoesNotExistException;
use InetStudio\AdminPanel\Base\Application\Exceptions\ResourceExistsException;
use InetStudio\AdminPanel\Base\Application\Exceptions\SaveResourceToDBException;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModelContract;
use Spatie\QueueableAction\QueueableAction;
use Throwable;

class UpdateAction
{
    use QueueableAction;

    public function __construct(
        protected EntryModelContract $model
    ) {}

    /**
     * @throws ResourceDoesNotExistException
     * @throws SaveResourceToDBException
     * @throws ResourceExistsException
     */
    public function execute(UpdateItemData $data): ?EntryModelContract
    {
        /** @var  ?EntryModelContract $item */
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

                if ($data->groups !== null) {
                    $item->groups()->sync($data->groups);
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
    protected function checkWithAliasExists(EntryModelContract $item, string $alias): void
    {
        $exists = $this->model::where('id', '!=', $item->id)->where('alias', '=', $alias)->exists();

        if ($exists) {
            throw ResourceExistsException::resourceWithFieldExists('alias', $alias);
        }
    }

    protected function getDataForUpdate(EntryModelContract $item, UpdateItemData $data): array
    {
        $currentItemData = $item->attributesToArray();
        $requestData = collect($data->toArray())->filter()->toArray();

        Arr::forget($requestData, ['groups']);

        return array_merge($currentItemData, $requestData);
    }
}
