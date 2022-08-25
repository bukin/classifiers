<?php

namespace InetStudio\ClassifiersPackage\Groups\Domain\Entity;

use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    protected $model = GroupModel::class;

    public function definition()
    {
        return [
            'name' => $this->faker->text(255),
            'alias' => $this->faker->text(255),
            'description' => $this->faker->text(),
        ];
    }
}
