<?php

namespace InetStudio\ClassifiersPackage\Entries\Tests\Support\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use InetStudio\ClassifiersPackage\Entries\Domain\Traits\HasClassifiers;
use InetStudio\ClassifiersPackage\Entries\Tests\Support\Factories\TestFactory;

class TestModel extends Model
{
    use HasClassifiers;
    use HasFactory;

    protected $table = 'test_models';

    protected $guarded = [];

    public $timestamps = false;

    protected static function newFactory(): TestFactory
    {
        return TestFactory::new();
    }
}
