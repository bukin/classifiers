<?php

namespace InetStudio\ClassifiersPackage\Entries\Domain\Entity;

use Illuminate\Database\Eloquent\Relations\Pivot;
use InetStudio\AdminPanel\Base\Models\Traits\HasUuidPrimaryKey;

class EntryGroupModel extends Pivot
{
    use HasUuidPrimaryKey;

    protected $table = 'classifiers_package_groups_entries';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
