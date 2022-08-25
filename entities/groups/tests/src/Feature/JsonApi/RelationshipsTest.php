<?php

namespace InetStudio\ClassifiersPackage\Groups\Tests\Feature\JsonApi;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModel;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModel;
use InetStudio\ClassifiersPackage\Groups\Tests\TestCase;
use LaravelJsonApi\Testing\MakesJsonApiRequests;

class RelationshipsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use MakesJsonApiRequests;

    /** @test */
    function show_group_entries(): void
    {
        $group = GroupModel::factory()->create();
        $entries = EntryModel::factory(5)->create();
        $group->entries()->attach($entries);

        $url = route('api.inetstudio.classifiers-package.v1.groups.entries', ['group' => (string) $group->getRouteKey()]);

        $expected = $entries->map(fn(EntryModel $entry) => [
            'type' => 'entries',
            'id' => (string) $entry->getRouteKey(),
            'attributes' => [
                'alias' => $entry->alias,
                'value' => $entry->value,
            ],
        ])->all();

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->get($url);

        $response->assertFetchedMany($expected);
    }

    /** @test */
    function show_group_entries_relationship(): void
    {
        $group = GroupModel::factory()->create();
        $entries = EntryModel::factory(5)->create();
        $group->entries()->attach($entries);

        $url = route('api.inetstudio.classifiers-package.v1.groups.entries.show', ['group' => (string) $group->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->get($url);

        $response->assertFetchedToMany($entries);
    }

    /** @test */
    function show_empty_group_entries_relationship(): void
    {
        $group = GroupModel::factory()->create();

        $url = route('api.inetstudio.classifiers-package.v1.groups.entries.show', ['group' => (string) $group->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->get($url);

        $response->assertFetchedNone();
    }

    /** @test */
    function update_group_entries_relationship(): void
    {
        $group = GroupModel::factory()->create();
        $group->entries()->attach($existing = EntryModel::factory()->create());
        $entries = EntryModel::factory(5)->create();

        $url = route('api.inetstudio.classifiers-package.v1.groups.entries.update', ['group' => (string) $group->getRouteKey()]);

        $data = $entries->map(fn(EntryModel $entry) => [
            'type' => 'entries',
            'id' => (string) $entry->getRouteKey(),
        ])->all();

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->patch($url);

        $response->assertFetchedToMany($entries);

        $this->assertDatabaseMissing('classifiers_package_groups_entries', [
            'entry_id' => $existing->getKey(),
            'group_id' => $group->getKey(),
        ]);

        foreach ($entries as $entry) {
            $this->assertDatabaseHas('classifiers_package_groups_entries', [
                'entry_id' => $entry->getKey(),
                'group_id' => $group->getKey(),
            ]);
        }
    }

    /** @test */
    public function attach_group_entries_relationship(): void
    {
        $group = GroupModel::factory()->create();
        $group->entries()->attach($existing = EntryModel::factory()->create());
        $entries = EntryModel::factory(5)->create();

        $url = route('api.inetstudio.classifiers-package.v1.groups.entries.attach', ['group' => (string) $group->getRouteKey()]);

        $data = $entries->map(fn(EntryModel $entry) => [
            'type' => 'entries',
            'id' => (string) $entry->getRouteKey(),
        ])->all();

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->post($url);

        $response->assertNoContent();

        $this->assertDatabaseHas('classifiers_package_groups_entries', [
            'entry_id' => $existing->getKey(),
            'group_id' => $group->getKey(),
        ]);

        foreach ($entries as $entry) {
            $this->assertDatabaseHas('classifiers_package_groups_entries', [
                'entry_id' => $entry->getKey(),
                'group_id' => $group->getKey(),
            ]);
        }
    }

    /** @test */
    public function detach_group_entries_relationship(): void
    {
        $group = GroupModel::factory()->create();
        $group->entries()->attach($keep = EntryModel::factory()->create());
        $group->entries()->attach($detach = EntryModel::factory(5)->create());

        $url = route('api.inetstudio.classifiers-package.v1.groups.entries.detach', ['group' => (string) $group->getRouteKey()]);

        $data = $detach->map(fn(EntryModel $entry) => [
            'type' => 'entries',
            'id' => (string) $entry->getRouteKey(),
        ])->all();

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->delete($url);

        $response->assertNoContent();

        $this->assertDatabaseHas('classifiers_package_groups_entries', [
            'entry_id' => $keep->getKey(),
            'group_id' => $group->getKey(),
        ]);

        foreach ($detach as $entry) {
            $this->assertDatabaseMissing('classifiers_package_groups_entries', [
                'entry_id' => $entry->getKey(),
                'group_id' => $group->getKey(),
            ]);
        }
    }
}
