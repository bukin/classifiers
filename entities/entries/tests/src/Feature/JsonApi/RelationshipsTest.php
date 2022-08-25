<?php

namespace InetStudio\ClassifiersPackage\Entries\Tests\Feature\JsonApi;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModel;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModel;
use InetStudio\ClassifiersPackage\Entries\Tests\TestCase;
use LaravelJsonApi\Testing\MakesJsonApiRequests;

class RelationshipsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use MakesJsonApiRequests;

    /** @test */
    function show_entry_groups(): void
    {
        $entry = EntryModel::factory()->create();
        $groups = GroupModel::factory(5)->create();
        $entry->groups()->attach($groups);

        $url = route('api.inetstudio.classifiers-package.v1.entries.groups', ['entry' => (string) $entry->getRouteKey()]);

        $expected = $groups->map(fn(GroupModel $group) => [
            'type' => 'groups',
            'id' => (string) $group->getRouteKey(),
            'attributes' => [
                'alias' => $group->alias,
                'name' => $group->name,
            ],
        ])->all();

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->get($url);

        $response->assertFetchedMany($expected);
    }

    /** @test */
    function show_entry_groups_relationship(): void
    {
        $entry = EntryModel::factory()->create();
        $groups = GroupModel::factory(5)->create();
        $entry->groups()->attach($groups);

        $url = route('api.inetstudio.classifiers-package.v1.entries.groups.show', ['entry' => (string) $entry->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->get($url);

        $response->assertFetchedToMany($groups);
    }

    /** @test */
    function show_empty_entry_groups_relationship(): void
    {
        $entry = EntryModel::factory()->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.groups.show', ['entry' => (string) $entry->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->get($url);

        $response->assertFetchedNone();
    }

    /** @test */
    function update_entry_groups_relationship(): void
    {
        $entry = EntryModel::factory()->create();
        $entry->groups()->attach($existing = GroupModel::factory()->create());
        $groups = GroupModel::factory(5)->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.groups.update', ['entry' => (string) $entry->getRouteKey()]);

        $data = $groups->map(fn(GroupModel $group) => [
            'type' => 'groups',
            'id' => (string) $group->getRouteKey(),
        ])->all();

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->patch($url);

        $response->assertFetchedToMany($groups);

        $this->assertDatabaseMissing('classifiers_package_groups_entries', [
            'group_id' => $existing->getKey(),
            'entry_id' => $entry->getKey(),
        ]);

        foreach ($groups as $group) {
            $this->assertDatabaseHas('classifiers_package_groups_entries', [
                'entry_id' => $entry->getKey(),
                'group_id' => $group->getKey(),
            ]);
        }
    }

    /** @test */
    public function attach_entry_groups_relationship(): void
    {
        $entry = EntryModel::factory()->create();
        $entry->groups()->attach($existing = GroupModel::factory()->create());
        $groups = GroupModel::factory(5)->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.groups.attach', ['entry' => (string) $entry->getRouteKey()]);

        $data = $groups->map(fn(GroupModel $group) => [
            'type' => 'groups',
            'id' => (string) $group->getRouteKey(),
        ])->all();

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->post($url);

        $response->assertNoContent();

        $this->assertDatabaseHas('classifiers_package_groups_entries', [
            'group_id' => $existing->getKey(),
            'entry_id' => $entry->getKey(),
        ]);

        foreach ($groups as $group) {
            $this->assertDatabaseHas('classifiers_package_groups_entries', [
                'entry_id' => $entry->getKey(),
                'group_id' => $group->getKey(),
            ]);
        }
    }

    /** @test */
    public function detach_entry_groups_relationship(): void
    {
        $entry = EntryModel::factory()->create();
        $entry->groups()->attach($keep = GroupModel::factory()->create());
        $entry->groups()->attach($detach = GroupModel::factory(5)->create());

        $url = route('api.inetstudio.classifiers-package.v1.entries.groups.detach', ['entry' => (string) $entry->getRouteKey()]);

        $data = $detach->map(fn(GroupModel $group) => [
            'type' => 'groups',
            'id' => (string) $group->getRouteKey(),
        ])->all();

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->delete($url);

        $response->assertNoContent();

        $this->assertDatabaseHas('classifiers_package_groups_entries', [
            'group_id' => $keep->getKey(),
            'entry_id' => $entry->getKey(),
        ]);

        foreach ($detach as $group) {
            $this->assertDatabaseMissing('classifiers_package_groups_entries', [
                'entry_id' => $entry->getKey(),
                'group_id' => $group->getKey(),
            ]);
        }
    }
}
