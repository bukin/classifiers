<?php

namespace InetStudio\ClassifiersPackage\Entries\Tests\Feature\JsonApi;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModel;
use InetStudio\ClassifiersPackage\Entries\Tests\TestCase;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModel;
use LaravelJsonApi\Testing\MakesJsonApiRequests;

class ResourceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use MakesJsonApiRequests;

    /** @test */
    function index_endpoint()
    {
        $entries = EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->get($url);

        $response->assertFetchedMany($entries);
    }

    /** @test */
    function index_endpoint_with_empty_response()
    {
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->get($url);

        $response->assertFetchedNone();
    }

    /** @test */
    function index_endpoint_with_id_sort_acs()
    {
        $entries = EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->sort('id')
            ->get($url);

        $response->assertFetchedManyInOrder($entries->sortBy('id'));
    }

    /** @test */
    function index_endpoint_with_id_sort_desc()
    {
        $entries = EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->sort('-id')
            ->get($url);

        $response->assertFetchedManyInOrder($entries->sortByDesc('id'));
    }

    /** @test */
    function index_endpoint_with_created_at_sort_acs()
    {
        $entries = EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->sort('createdAt')
            ->get($url);

        $response->assertFetchedManyInOrder($entries->sortBy('created_at'));
    }

    /** @test */
    function index_endpoint_with_created_at_sort_desc()
    {
        $entries = EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->sort('-createdAt')
            ->get($url);

        $response->assertFetchedManyInOrder($entries->sortByDesc('created_at'));
    }

    /** @test */
    function index_endpoint_with_updated_at_sort_acs()
    {
        $entries = EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->sort('updatedAt')
            ->get($url);

        $response->assertFetchedManyInOrder($entries->sortBy('updated_at'));
    }

    /** @test */
    function index_endpoint_with_updated_at_sort_desc()
    {
        $entries = EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->sort('-updatedAt')
            ->get($url);

        $response->assertFetchedManyInOrder($entries->sortByDesc('updated_at'));
    }

    /** @test */
    function get_error_when_index_endpoint_with_value_sort_acs()
    {
        EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->sort('value')
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'sort'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    function get_error_when_index_endpoint_with_value_sort_desc()
    {
        EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->sort('-value')
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'sort'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    function get_error_when_index_endpoint_with_alias_sort_acs()
    {
        EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->sort('alias')
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'sort'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    function get_error_when_index_endpoint_with_alias_sort_desc()
    {
        EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->sort('-alias')
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'sort'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    function index_endpoint_with_id_filter()
    {
        $entries = EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->filter(['id' => [$entries->first()->id]])
            ->get($url);

        $response->assertFetchedMany([$entries->first()]);
    }

    /** @test */
    function index_endpoint_with_id_filter_empty_response()
    {
        EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->filter(['id' => ['test']])
            ->get($url);

        $response->assertFetchedNone();
    }

    /** @test */
    function index_endpoint_with_alias_filter()
    {
        $entries = EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->filter(['alias' => $entries->first()->alias])
            ->get($url);

        $response->assertFetchedOne($entries->first());
    }

    /** @test */
    function index_endpoint_with_alias_filter_empty_response()
    {
        EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->filter(['alias' => 'test'])
            ->get($url);

        $response->assertFetchedNull();
    }

    /** @test */
    function index_endpoint_with_group_filter()
    {
        $entries = EntryModel::factory(5)->create();
        $group = GroupModel::factory()->create();

        $entries->first()->groups()->attach($group);
        $entries->last()->groups()->attach($group);

        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->filter(['group' => $group->alias])
            ->get($url);

        $response->assertFetchedMany([$entries->first(), $entries->last()]);
    }

    /** @test */
    function index_endpoint_with_group_filter_empty_response()
    {
        EntryModel::factory(5)->create();
        $group = GroupModel::factory()->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->filter(['group' => $group->alias])
            ->get($url);

        $response->assertFetchedNone();
    }

    /** @test */
    function index_endpoint_with_suggestion_filter()
    {
        EntryModel::factory(5)->create();

        $firstExpectedEntry = EntryModel::factory()->create([
            'value' => 'suggestion_test'
        ]);
        $secondExpectedEntry = EntryModel::factory()->create([
            'alias' => 'suggestion_test'
        ]);

        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->filter(['suggestion' => 'suggestion_test'])
            ->get($url);

        $response->assertFetchedMany([$firstExpectedEntry, $secondExpectedEntry]);
    }

    /** @test */
    function index_endpoint_with_suggestion_filter_empty_response()
    {
        EntryModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->filter(['suggestion' => 'suggestion_test'])
            ->get($url);

        $response->assertFetchedNone();
    }

    /** @test */
    function get_error_when_index_endpoint_with_invalid_filter()
    {
        $url = route('api.inetstudio.classifiers-package.v1.entries.index');

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->filter(['test' => 'test'])
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'filter'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    public function show_entry_without_groups(): void
    {
        $entry = EntryModel::factory()->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.show', ['entry' => $entry->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->get($url);

        $response
            ->assertFetchedOne($entry)
            ->assertDoesntHaveIncluded();
    }

    /** @test */
    public function show_entry_with_include_groups(): void
    {
        $entry = EntryModel::factory()->create();
        $groups = GroupModel::factory(5)->create();

        $entry->groups()->attach($groups);

        $url = route('api.inetstudio.classifiers-package.v1.entries.show', ['entry' => $entry->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->includePaths('groups')
            ->get($url);

        $response
            ->assertFetchedOne($entry)
            ->assertIncluded($groups->map(fn(GroupModel $group) => [
                'type' => 'groups',
                'id' => $group->id,
            ])->all());
    }

    /** @test */
    public function show_endpoint(): void
    {
        $entry = EntryModel::factory()->create();
        $groups = GroupModel::factory()->count(2)->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.show', ['entry' => $entry->getRouteKey()]);

        $expected = [
            'type' => 'entries',
            'id' => (string) $entry->getRouteKey(),
            'attributes' => [
                'alias' => $entry->alias,
                'createdAt' => $entry->created_at,
                'updatedAt' => $entry->updated_at,
                'value' => $entry->value,
            ]
        ];

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->get($url);

        $response->assertFetchedOneExact($expected);
    }

    /** @test */
    public function get_error_when_show_entry_with_invalid_include(): void
    {
        $entry = EntryModel::factory()->create();
        $groups = GroupModel::factory(5)->create();

        $entry->groups()->attach($groups);

        $url = route('api.inetstudio.classifiers-package.v1.entries.show', ['entry' => $entry->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->includePaths('invalid_relation')
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'include'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    public function show_entry_with_sparse_fields(): void
    {
        $entry = EntryModel::factory()->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.show', ['entry' => $entry->getRouteKey()]);

        $expected = [
            'type' => 'entries',
            'id' => (string) $entry->getRouteKey(),
            'attributes' => [
                'alias' => $entry->alias,
                'value' => $entry->value,
            ],
        ];

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->sparseFields('entries', ['alias', 'value'])
            ->get($url);

        $response->assertFetchedOneExact($expected);
    }

    /** @test */
    public function show_entry_with_sparse_groups_relation(): void
    {
        $entry = EntryModel::factory()->create();
        $groups = GroupModel::factory(5)->create();

        $entry->groups()->attach($groups);

        $url = route('api.inetstudio.classifiers-package.v1.entries.show', ['entry' => $entry->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->includePaths('groups')
            ->sparseFields('entries', ['groups'])
            ->get($url);

        $response
            ->assertFetchedOne($entry)
            ->assertIncluded($groups->map(fn(GroupModel $group) => [
                'type' => 'groups',
                'id' => $group->id,
            ])->all());
    }

    /** @test */
    function get_error_when_show_entry_with_invalid_field()
    {
        $entry = EntryModel::factory()->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.show', ['entry' => $entry->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->sparseFields('entries', ['test'])
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'fields'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    public function get_error_when_show_non_existent_entry(): void
    {
        $url = route('api.inetstudio.classifiers-package.v1.entries.show', ['entry' => 'test']);

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->get($url);

        $response->assertNotFound();
    }

    /** @test */
    public function store_entry(): void
    {
        $entry = EntryModel::factory()->make();
        $groups = GroupModel::factory()->count(2)->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.store');

        $id = Str::uuid();

        $data = [
            'type' => 'entries',
            'id' => $id,
            'attributes' => [
                'alias' => $entry->alias,
                'value' => $entry->value,
            ],
            'relationships' => [
                'groups' => [
                    'data' => $groups->map(fn(GroupModel $group) => [
                        'type' => 'groups',
                        'id' => (string) $group->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $this->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->includePaths('groups')
            ->post($url);

        $this->assertDatabaseHas('classifiers_package_entries', [
            'id' => $id,
            'value' => $entry->value,
            'alias' => $entry->alias,
        ]);

        foreach ($groups as $group) {
            $this->assertDatabaseHas('classifiers_package_groups_entries', [
                'group_id' => $group->getKey(),
                'entry_id' => $id,
            ]);
        }
    }

    /** @test */
    public function get_error_when_store_entry_without_id(): void
    {
        $entry = EntryModel::factory()->make();
        $groups = GroupModel::factory(5)->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.store');

        $id = Str::uuid();

        $data = [
            'type' => 'entries',
            'attributes' => [
                'alias' => $entry->alias,
                'value' => $entry->value,
            ],
            'relationships' => [
                'groups' => [
                    'data' => $groups->map(fn(GroupModel $group) => [
                        'type' => 'groups',
                        'id' => (string) $group->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $response = $this->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->post($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/id'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseMissing('classifiers_package_entries', [
            'id' => $id,
            'alias' => $entry->alias,
            'value' => $entry->value,
        ]);
    }

    /** @test */
    public function get_error_when_store_entry_with_incorrect_id(): void
    {
        $entry = EntryModel::factory()->make();
        $groups = GroupModel::factory(5)->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.store');

        $id = Str::uuid();

        $data = [
            'type' => 'entries',
            'id' => 'test',
            'attributes' => [
                'alias' => $entry->alias,
                'value' => $entry->value,
            ],
            'relationships' => [
                'groups' => [
                    'data' => $groups->map(fn(GroupModel $group) => [
                        'type' => 'groups',
                        'id' => (string) $group->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $response = $this->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->post($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/id'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseMissing('classifiers_package_entries', [
            'id' => $id,
            'alias' => $entry->alias,
            'value' => $entry->value,
        ]);
    }

    /** @test */
    public function get_error_when_store_entry_without_value(): void
    {
        $entry = EntryModel::factory()->make();
        $groups = GroupModel::factory(5)->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.store');

        $id = Str::uuid();

        $data = [
            'type' => 'entries',
            'id' => $id,
            'attributes' => [
                'alias' => $entry->alias,
            ],
            'relationships' => [
                'groups' => [
                    'data' => $groups->map(fn(GroupModel $group) => [
                        'type' => 'groups',
                        'id' => (string) $group->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $response = $this->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->post($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/attributes/value'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseMissing('classifiers_package_entries', [
            'id' => $id,
            'alias' => $entry->alias,
            'value' => $entry->value,
        ]);
    }

    /** @test */
    public function get_error_when_store_entry_with_long_value(): void
    {
        $entry = EntryModel::factory()->make([
            'value' => $this->faker->text(500),
        ]);
        $groups = GroupModel::factory(5)->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.store');

        $id = Str::uuid();

        $data = [
            'type' => 'entries',
            'id' => $id,
            'attributes' => [
                'alias' => $entry->alias,
                'value' => $entry->value,
            ],
            'relationships' => [
                'groups' => [
                    'data' => $groups->map(fn(GroupModel $group) => [
                        'type' => 'groups',
                        'id' => (string) $group->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $response = $this->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->post($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/attributes/value'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseMissing('classifiers_package_entries', [
            'id' => $id,
            'alias' => $entry->alias,
            'value' => $entry->value,
        ]);
    }

    /** @test */
    public function get_error_when_store_entry_with_long_alias(): void
    {
        $entry = EntryModel::factory()->make([
            'alias' => $this->faker->text(500),
        ]);
        $groups = GroupModel::factory(5)->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.store');

        $id = Str::uuid();

        $data = [
            'type' => 'entries',
            'id' => $id,
            'attributes' => [
                'alias' => $entry->alias,
                'value' => $entry->value,
            ],
            'relationships' => [
                'groups' => [
                    'data' => $groups->map(fn(GroupModel $group) => [
                        'type' => 'groups',
                        'id' => (string) $group->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $response = $this->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->post($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/attributes/alias'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseMissing('classifiers_package_entries', [
            'id' => $id,
            'alias' => $entry->alias,
            'value' => $entry->value,
        ]);
    }

    /** @test */
    public function get_error_when_store_entry_with_non_unique_alias(): void
    {
        $existsEntry = EntryModel::factory()->create();
        $entry = EntryModel::factory()->make([
            'alias' => $existsEntry->alias,
        ]);
        $groups = GroupModel::factory(5)->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.store');

        $id = Str::uuid();

        $data = [
            'type' => 'entries',
            'id' => $id,
            'attributes' => [
                'alias' => $entry->alias,
                'value' => $entry->value,
            ],
            'relationships' => [
                'groups' => [
                    'data' => $groups->map(fn(GroupModel $group) => [
                        'type' => 'groups',
                        'id' => (string) $group->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $response = $this->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->post($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/attributes/alias'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseMissing('classifiers_package_entries', [
            'id' => $id,
            'alias' => $entry->alias,
            'value' => $entry->value,
        ]);
    }

    /** @test */
    public function get_error_when_store_entry_without_groups(): void
    {
        $entry = EntryModel::factory()->make();

        $url = route('api.inetstudio.classifiers-package.v1.entries.store');

        $id = Str::uuid();

        $data = [
            'type' => 'entries',
            'id' => $id,
            'attributes' => [
                'alias' => $entry->alias,
                'value' => $entry->value,
            ],
        ];

        $response = $this->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->post($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/relationships/groups'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseMissing('classifiers_package_entries', [
            'id' => $id,
            'alias' => $entry->alias,
            'value' => $entry->value,
        ]);
    }

    /** @test */
    public function update_entry(): void
    {
        $entry = EntryModel::factory()->create();
        $entry->groups()->attach($existing = GroupModel::factory()->create());
        $groups = GroupModel::factory()->count(2)->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.update', ['entry' => $entry->getRouteKey()]);

        $data = [
            'type' => 'entries',
            'id' => (string) $entry->getRouteKey(),
            'attributes' => [
                'alias' => 'updated-alias',
                'value' => 'updated-value',
            ],
            'relationships' => [
                'groups' => [
                    'data' => $groups->map(fn(GroupModel $group) => [
                        'type' => 'groups',
                        'id' => (string) $group->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->includePaths('groups')
            ->patch($url);

        $response->assertFetchedOne($entry);

        $this->assertDatabaseHas('classifiers_package_entries', [
            'id' => $entry->getKey(),
            'value' => $data['attributes']['value'],
            'alias' => $data['attributes']['alias'],
        ]);

        $this->assertDatabaseMissing('classifiers_package_groups_entries', [
            'group_id' => $existing->getKey(),
            'entry_id' => $entry->getKey(),
        ]);

        foreach ($groups as $group) {
            $this->assertDatabaseHas('classifiers_package_groups_entries', [
                'group_id' => $group->getKey(),
                'entry_id' => $entry->getKey(),
            ]);
        }
    }

    /** @test */
    public function get_error_when_update_entry_with_long_value(): void
    {
        $entry = EntryModel::factory()->create();
        $groups = GroupModel::factory(5)->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.update', ['entry' => $entry->getRouteKey()]);

        $data = [
            'type' => 'entries',
            'id' => (string) $entry->getRouteKey(),
            'attributes' => [
                'alias' => 'updated-alias',
                'value' => $this->faker->text(500),
            ],
            'relationships' => [
                'groups' => [
                    'data' => $groups->map(fn(GroupModel $group) => [
                        'type' => 'groups',
                        'id' => (string) $group->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->patch($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/attributes/value'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseHas('classifiers_package_entries', [
            'id' => $entry->getKey(),
            'alias' => $entry->alias,
            'value' => $entry->value,
        ]);
    }

    /** @test */
    public function get_error_when_update_entry_with_long_alias(): void
    {
        $entry = EntryModel::factory()->create();
        $groups = GroupModel::factory(5)->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.update', ['entry' => $entry->getRouteKey()]);

        $data = [
            'type' => 'entries',
            'id' => (string) $entry->getRouteKey(),
            'attributes' => [
                'alias' => $this->faker->text(500),
                'value' => 'updated-name',
            ],
            'relationships' => [
                'groups' => [
                    'data' => $groups->map(fn(GroupModel $group) => [
                        'type' => 'groups',
                        'id' => (string) $group->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->patch($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/attributes/alias'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseHas('classifiers_package_entries', [
            'id' => $entry->getKey(),
            'alias' => $entry->alias,
            'value' => $entry->value,
        ]);
    }

    /** @test */
    public function get_error_when_update_entry_with_non_unique_alias(): void
    {
        $existsEntry = EntryModel::factory()->create();
        $entry = EntryModel::factory()->create();
        $groups = GroupModel::factory(5)->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.update', ['entry' => $entry->getRouteKey()]);

        $data = [
            'type' => 'entries',
            'id' => (string) $entry->getRouteKey(),
            'attributes' => [
                'alias' => $existsEntry->alias,
                'value' => 'updated-value',
            ],
            'relationships' => [
                'groups' => [
                    'data' => $groups->map(fn(GroupModel $group) => [
                        'type' => 'groups',
                        'id' => (string) $group->getRouteKey(),
                    ])->all(),
                ],
            ],
        ];

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->patch($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/attributes/alias'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseHas('classifiers_package_entries', [
            'id' => $entry->getKey(),
            'alias' => $entry->alias,
            'value' => $entry->value,
        ]);
    }

    /** @test */
    public function get_error_when_update_entry_without_groups(): void
    {
        $entry = EntryModel::factory()->create();

        $url = route('api.inetstudio.classifiers-package.v1.entries.update', ['entry' => $entry->getRouteKey()]);

        $data = [
            'type' => 'entries',
            'id' => (string) $entry->getRouteKey(),
            'attributes' => [
                'alias' => 'updated-name',
                'value' => 'updated-value',
            ],
        ];

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->patch($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/relationships/groups'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseHas('classifiers_package_entries', [
            'id' => $entry->getKey(),
            'alias' => $entry->alias,
            'value' => $entry->value,
        ]);
    }

    /** @test */
    public function get_error_when_update_non_existent_entry(): void
    {
        $url = route('api.inetstudio.classifiers-package.v1.entries.update', ['entry' => 'test']);

        $data = [
            'type' => 'entries',
            'id' => 'test',
            'attributes' => [
                'alias' => 'updated-alias',
                'value' => 'updated-value',
            ],
        ];

        $response = $this
            ->jsonApi()
            ->expects('entries')
            ->withData($data)
            ->patch($url);

        $response->assertNotFound();
    }

    /** @test */
    public function destroy_entry(): void
    {
        $entry = EntryModel::factory()->create();
        $url = route('api.inetstudio.classifiers-package.v1.entries.destroy', ['entry' => $entry->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->delete($url);

        $response->assertNoContent();

        $this->assertSoftDeleted('classifiers_package_entries', [
            'id' => $entry->getKey(),
        ]);
    }

    /** @test */
    public function get_error_when_destroy_non_existent_entry(): void
    {
        $url = route('api.inetstudio.classifiers-package.v1.entries.destroy', ['entry' => 'test']);

        $response = $this
            ->jsonApi()
            ->delete($url);

        $response->assertNotFound();
    }
}
