<?php

namespace InetStudio\ClassifiersPackage\Entries\Tests\Support\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use InetStudio\ClassifiersPackage\Entries\Tests\Support\Models\TestModel;

class TestFactory extends Factory
{
    protected $model = TestModel::class;

    public function definition()
    {
        return [
            'name' => $this->faker->text(255),
        ];
    }
}
