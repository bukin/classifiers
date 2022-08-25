<?php

namespace InetStudio\ClassifiersPackage\Entries\Application\Actions\Resource\Store;

use Illuminate\Support\Facades\DB;
use Spatie\QueueableAction\QueueableAction;
use InetStudio\AdminPanel\Base\Application\Exceptions\ResourceExistsException;
use InetStudio\AdminPanel\Base\Application\Exceptions\SaveResourceToDBException;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModelContract;
use Ramsey\Uuid\UuidInterface;
use Throwable;

class StoreAction
{
    use QueueableAction;

    public function __construct(
        protected EntryModelContract $model
    ) {}

    /**
     * @throws ResourceExistsException
     * @throws SaveResourceToDBException
     */
    public function execute(StoreItemData $data): EntryModelContract
    {
        $this->checkWithIdExists($data->id);
        $this->checkWithAliasExists($data->alias);

        try {
            $item = DB::transaction(function () use ($data) {
                $item = new $this->model;

                $item->id = $data->id->toString() ?: $item->id;
                $item->value = $data->value;
                $item->alias = $data->alias;

                $item->save();

                $item->groups()->attach($data->groups);

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
