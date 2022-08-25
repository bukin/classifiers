<?php

namespace InetStudio\ClassifiersPackage\Entries\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModel;
use InetStudio\ClassifiersPackage\Entries\Tests\TestCase;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModel;

class EntryGroupModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_pivot_has_an_id()
    {
        $entry = EntryModel::factory()->create();
        $group = GroupModel::factory()->create();

        $customPivotId = Str::uuid();

        $entry->groups()->attach([
            $group->id => [
              'id' => $customPivotId,
            ],
        ]);

        $entry = $entry->fresh();

        $this->assertDatabaseCount('classifiers_package_groups_entries', 1);
        $this->assertEquals($customPivotId, $entry->groups->first()->pivot->id);
    }

    /** @test */
    function a_pivot_has_an_entry_id()
    {
        $entry = EntryModel::factory()->create();
        $group = GroupModel::factory()->create();

        $entry->groups()->attach($group);

        $entry = $entry->fresh();

        $this->assertDatabaseCount('classifiers_package_groups_entries', 1);
        $this->assertEquals($entry->id, $entry->groups->first()->pivot->entry_id);
    }

    /** @test */
    function a_pivot_has_a_group_id()
    {
        $entry = EntryModel::factory()->create();
        $group = GroupModel::factory()->create();

        $entry->groups()->attach($group);

        $entry = $entry->fresh();

        $this->assertDatabaseCount('classifiers_package_groups_entries', 1);
        $this->assertEquals($group->id, $entry->groups->first()->pivot->group_id);
    }
}
