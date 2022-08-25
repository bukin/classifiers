<?php

namespace InetStudio\ClassifiersPackage\Entries\Domain\Entity;

use Illuminate\Database\Eloquent\Relations\MorphPivot;
use InetStudio\AdminPanel\Base\Models\Traits\HasUuidPrimaryKey;

class ClassifierableModel extends MorphPivot
{
    use HasUuidPrimaryKey;

    protected $table = 'classifiers_package_classifierables';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
