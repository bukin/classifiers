<?php

namespace InetStudio\ClassifiersPackage\Entries\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModel;
use InetStudio\ClassifiersPackage\Entries\Tests\TestCase;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModel;

class EntryModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function an_entry_has_a_value()
    {
        $entry = EntryModel::factory()->create(['value' => 'Test value']);

        $this->assertEquals('Test value', $entry->value);
    }

    /** @test */
    function an_entry_has_an_alias()
    {
        $entry = EntryModel::factory()->create(['alias' => 'Test alias']);

        $this->assertEquals('Test alias', $entry->alias);
    }

    /** @test */
    function an_entry_has_a_type_attribute()
    {
        $entry = EntryModel::factory()->create();

        $this->assertEquals('classifiers_package_entry', $entry->getTypeAttribute());
    }

    /** @test */
    function an_entry_has_groups()
    {
        $entry = EntryModel::factory()->create();
        $groups = GroupModel::factory(5)->create();

        $entry->groups()->attach($groups);

        // Method 1: An entries exists in a group's entries collections.
        $this->assertTrue($entry->groups->diff($groups)->isEmpty());

        // Method 2: Count that an employee scopes collection exists.
        $this->assertEquals(5, $entry->groups->count());

        // Method 3: Entries are related to group and is a collection instance.
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $entry->groups);
    }
}
