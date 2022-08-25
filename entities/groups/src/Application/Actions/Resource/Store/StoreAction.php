<?php

namespace InetStudio\ClassifiersPackage\Groups\Application\Actions\Resource\Store;

use Illuminate\Support\Facades\DB;
use InetStudio\AdminPanel\Base\Application\Exceptions\ResourceExistsException;
use InetStudio\AdminPanel\Base\Application\Exceptions\SaveResourceToDBException;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModelContract;
use Ramsey\Uuid\UuidInterface;
use Throwable;

class StoreAction
{
    public function __construct(
        protected GroupModelContract $model
    ) {}

    /**
     * @throws ResourceExistsException
     * @throws SaveResourceToDBException
     */
    public function execute(StoreItemData $data): GroupModelContract
    {
        $this->checkWithIdExists($data->id);
        $this->checkWithAliasExists($data->alias);

        try {
            $item = DB::transaction(function() use ($data) {
                $item = new $this->model;

                $item->id = $data->id->toString() ?: $item->id;
                $item->name = $data->name;
                $item->alias = $data->alias;
                $item->description = $data->description;

                $item->save();

                $item->entries()->attach($data->entries);

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
    protected function checkWithIdExists(?UuidInterface $id): void
    {
        if (! $id) {
            return;
        }

        $item = $this->model::find($id->toString());

        if ($item) {
            throw ResourceExistsException::resourceWithFieldExists('id', $id->toString());
        }
    }

    /**
     * @throws ResourceExistsException
     */
    protected function checkWithAliasExists(string $alias): void
    {
        $exists = $this->model::where('alias', '=', $alias)->exists();

        if ($exists) {
            throw ResourceExistsException::resourceWithFieldExists('alias', $alias);
        }
    }
}
