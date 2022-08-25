<?php

namespace InetStudio\ClassifiersPackage\Entries\Domain\Entity;

use Illuminate\Database\Eloquent\Factories\Factory;

class EntryFactory extends Factory
{
    protected $model = EntryModel::class;

    public function definition()
    {
        return [
            'value' => $this->faker->text(255),
            'alias' => $this->faker->text(255),
        ];
    }
}
