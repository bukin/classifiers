<?php

namespace InetStudio\ClassifiersPackage\Groups\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModel;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModel;
use InetStudio\ClassifiersPackage\Groups\Tests\TestCase;

class GroupModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_group_has_a_name(): void
    {
        $group = GroupModel::factory()->create(['name' => 'Test name']);

        $this->assertEquals('Test name', $group->name);
    }

    /** @test */
    function a_group_has_an_alias(): void
    {
        $group = GroupModel::factory()->create(['alias' => 'Test alias']);

        $this->assertEquals('Test alias', $group->alias);
    }

    /** @test */
    function a_group_has_a_description(): void
    {
        $group = GroupModel::factory()->create(['description' => 'Test description']);

        $this->assertEquals('Test description', $group->description);
    }

    /** @test */
    function a_group_has_a_type_attribute(): void
    {
        $group = GroupModel::factory()->create();

        $this->assertEquals('classifiers_package_group', $group->getTypeAttribute());
    }

    /** @test */
    function a_group_has_entries(): void
    {
        $group = GroupModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $group->entries()->attach($entries);

        $this->assertTrue($group->entries->diff($entries)->isEmpty());
        $this->assertEquals(5, $group->entries->count());
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $group->entries);
    }
}
