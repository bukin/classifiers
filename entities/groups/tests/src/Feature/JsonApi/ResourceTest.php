<?php

namespace InetStudio\ClassifiersPackage\Groups\Tests\Feature\JsonApi;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModel;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModel;
use InetStudio\ClassifiersPackage\Groups\Tests\TestCase;
use LaravelJsonApi\Testing\MakesJsonApiRequests;

class ResourceTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use MakesJsonApiRequests;

    /** @test */
    function index_endpoint(): void
    {
        $groups = GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->get($url);

        $response->assertFetchedMany($groups);
    }

    /** @test */
    function index_endpoint_with_empty_response(): void
    {
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->get($url);

        $response->assertFetchedNone();
    }

    /** @test */
    function index_endpoint_with_id_sort_acs(): void
    {
        $groups = GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->sort('id')
            ->get($url);

        $response->assertFetchedManyInOrder($groups->sortBy('id'));
    }

    /** @test */
    function index_endpoint_with_id_sort_desc(): void
    {
        $groups = GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->sort('-id')
            ->get($url);

        $response->assertFetchedManyInOrder($groups->sortByDesc('id'));
    }

    /** @test */
    function index_endpoint_with_created_at_sort_acs(): void
    {
        $groups = GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->sort('createdAt')
            ->get($url);

        $response->assertFetchedManyInOrder($groups->sortBy('created_at'));
    }

    /** @test */
    function index_endpoint_with_created_at_sort_desc(): void
    {
        $groups = GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->sort('-createdAt')
            ->get($url);

        $response->assertFetchedManyInOrder($groups->sortByDesc('created_at'));
    }

    /** @test */
    function index_endpoint_with_updated_at_sort_acs(): void
    {
        $groups = GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->sort('updatedAt')
            ->get($url);

        $response->assertFetchedManyInOrder($groups->sortBy('updated_at'));
    }

    /** @test */
    function index_endpoint_with_updated_at_sort_desc(): void
    {
        $groups = GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->sort('-updatedAt')
            ->get($url);

        $response->assertFetchedManyInOrder($groups->sortByDesc('updated_at'));
    }

    /** @test */
    function get_error_when_index_endpoint_with_name_sort_acs(): void
    {
        GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->sort('name')
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'sort'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    function get_error_when_index_endpoint_with_name_sort_desc(): void
    {
        GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->sort('-name')
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'sort'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    function get_error_when_index_endpoint_with_alias_sort_acs(): void
    {
        GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->sort('alias')
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'sort'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    function get_error_when_index_endpoint_with_alias_sort_desc(): void
    {
        GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->sort('-alias')
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'sort'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    function get_error_when_index_endpoint_with_description_sort_acs(): void
    {
        GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->sort('description')
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'sort'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    function get_error_when_index_endpoint_with_description_sort_desc(): void
    {
        GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->sort('-description')
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'sort'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    function index_endpoint_with_id_filter(): void
    {
        $groups = GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->filter(['id' => [$groups->first()->id]])
            ->get($url);

        $response->assertFetchedMany([$groups->first()]);
    }

    /** @test */
    function index_endpoint_with_id_filter_empty_response(): void
    {
        GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->filter(['id' => ['test']])
            ->get($url);

        $response->assertFetchedNone();
    }

    /** @test */
    function index_endpoint_with_alias_filter(): void
    {
        $groups = GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->filter(['alias' => $groups->first()->alias])
            ->get($url);

        $response->assertFetchedOne($groups->first());
    }

    /** @test */
    function index_endpoint_with_alias_filter_empty_response(): void
    {
        GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->filter(['alias' => 'test'])
            ->get($url);

        $response->assertFetchedNull();
    }

    /** @test */
    function index_endpoint_with_suggestion_filter(): void
    {
        GroupModel::factory(5)->create();

        $firstExpectedGroup = GroupModel::factory()->create([
            'name' => 'suggestion_test'
        ]);
        $secondExpectedGroup = GroupModel::factory()->create([
            'alias' => 'suggestion_test'
        ]);

        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->filter(['suggestion' => 'suggestion_test'])
            ->get($url);

        $response->assertFetchedMany([$firstExpectedGroup, $secondExpectedGroup]);
    }

    /** @test */
    function index_endpoint_with_suggestion_filter_empty_response(): void
    {
        GroupModel::factory(5)->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->filter(['suggestion' => 'suggestion_test'])
            ->get($url);

        $response->assertFetchedNone();
    }

    /** @test */
    function get_error_when_index_endpoint_with_invalid_filter(): void
    {
        $url = route('api.inetstudio.classifiers-package.v1.groups.index');

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->filter(['test' => 'test'])
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'filter'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    public function show_group_without_entries(): void
    {
        $group = GroupModel::factory()->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.show', ['group' => $group->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->get($url);

        $response
            ->assertFetchedOne($group)
            ->assertDoesntHaveIncluded();
    }

    /** @test */
    public function show_group_with_include_entries(): void
    {
        $group = GroupModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $group->entries()->attach($entries);

        $url = route('api.inetstudio.classifiers-package.v1.groups.show', ['group' => $group->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->includePaths('entries')
            ->get($url);

        $response
            ->assertFetchedOne($group)
            ->assertIncluded($entries->map(fn(EntryModel $entry) => [
                'type' => 'entries',
                'id' => $entry->id,
            ])->all());
    }

    /** @test */
    public function get_error_when_show_group_with_invalid_include(): void
    {
        $group = GroupModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $group->entries()->attach($entries);

        $url = route('api.inetstudio.classifiers-package.v1.groups.show', ['group' => $group->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->includePaths('invalid_relation')
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'include'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    public function show_group_with_sparse_fields(): void
    {
        $group = GroupModel::factory()->create();

        $url = route('api.inetstudio.classifiers-package.v1.groups.show', ['group' => $group->getRouteKey()]);

        $expected = [
            'type' => 'groups',
            'id' => (string) $group->getRouteKey(),
            'attributes' => [
                'alias' => $group->alias,
                'name' => $group->name,
                'description' => $group->description,
            ],
        ];

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->sparseFields('groups', ['alias', 'name', 'description'])
            ->get($url);

        $response->assertFetchedOneExact($expected);
    }

    /** @test */
    public function show_group_with_sparse_entries_relation(): void
    {
        $group = GroupModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $group->entries()->attach($entries);

        $url = route('api.inetstudio.classifiers-package.v1.groups.show', ['group' => $group->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->includePaths('entries')
            ->sparseFields('groups', ['entries'])
            ->get($url);

        $response
            ->assertFetchedOne($group)
            ->assertIncluded($entries->map(fn(EntryModel $entry) => [
                'type' => 'entries',
                'id' => $entry->id,
            ])->all());
    }

    /** @test */
    function get_error_when_show_group_with_invalid_field(): void
    {
        $group = GroupModel::factory()->create();

        $url = route('api.inetstudio.classifiers-package.v1.groups.show', ['group' => $group->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->sparseFields('groups', ['test'])
            ->get($url);

        $response->assertErrorStatus([
            'source' => ['parameter' => 'fields'],
            'status' => '400',
            'title' => 'Invalid Query Parameter',
        ]);
    }

    /** @test */
    public function get_error_when_show_non_existent_group(): void
    {
        $url = route('api.inetstudio.classifiers-package.v1.groups.show', ['group' => 'test']);

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->get($url);

        $response->assertNotFound();
    }

    /** @test */
    public function store_group(): void
    {
        $group = GroupModel::factory()->make();
        $url = route('api.inetstudio.classifiers-package.v1.groups.store');

        $id = Str::uuid();

        $data = [
            'type' => 'groups',
            'id' => $id,
            'attributes' => [
                'alias' => $group->alias,
                'description' => $group->description,
                'name' => $group->name,
            ],
        ];

        $this->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->post($url);

        $this->assertDatabaseHas('classifiers_package_groups', [
            'id' => $id,
            'name' => $group->name,
            'alias' => $group->alias,
            'description' => $group->description,
        ]);
    }

    /** @test */
    public function get_error_when_store_group_without_id(): void
    {
        $group = GroupModel::factory()->make();
        $url = route('api.inetstudio.classifiers-package.v1.groups.store');

        $id = Str::uuid();

        $data = [
            'type' => 'groups',
            'attributes' => [
                'alias' => $group->alias,
                'description' => $group->description,
                'name' => $group->name,
            ],
        ];

        $response = $this->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->post($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/id'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseMissing('classifiers_package_groups', [
            'id' => $id,
            'name' => $group->name,
            'alias' => $group->alias,
            'description' => $group->description,
        ]);
    }

    /** @test */
    public function get_error_when_store_group_with_incorrect_id(): void
    {
        $group = GroupModel::factory()->make();
        $url = route('api.inetstudio.classifiers-package.v1.groups.store');

        $id = Str::uuid();

        $data = [
            'type' => 'groups',
            'id' => 'test',
            'attributes' => [
                'alias' => $group->alias,
                'description' => $group->description,
                'name' => $group->name,
            ],
        ];

        $response = $this->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->post($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/id'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseMissing('classifiers_package_groups', [
            'id' => $id,
            'name' => $group->name,
            'alias' => $group->alias,
            'description' => $group->description,
        ]);
    }

    /** @test */
    public function get_error_when_store_group_without_name(): void
    {
        $group = GroupModel::factory()->make();
        $url = route('api.inetstudio.classifiers-package.v1.groups.store');

        $id = Str::uuid();

        $data = [
            'type' => 'groups',
            'id' => $id,
            'attributes' => [
                'alias' => $group->alias,
                'description' => $group->description,
            ],
        ];

        $response = $this->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->post($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/attributes/name'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseMissing('classifiers_package_groups', [
            'id' => $id,
            'name' => $group->name,
            'alias' => $group->alias,
            'description' => $group->description,
        ]);
    }

    /** @test */
    public function get_error_when_store_group_with_long_name(): void
    {
        $group = GroupModel::factory()->make([
            'name' => $this->faker->text(500),
        ]);
        $url = route('api.inetstudio.classifiers-package.v1.groups.store');

        $id = Str::uuid();

        $data = [
            'type' => 'groups',
            'id' => $id,
            'attributes' => [
                'alias' => $group->alias,
                'description' => $group->description,
                'name' => $group->name,
            ],
        ];

        $response = $this->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->post($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/attributes/name'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseMissing('classifiers_package_groups', [
            'id' => $id,
            'name' => $group->name,
            'alias' => $group->alias,
            'description' => $group->description,
        ]);
    }

    /** @test */
    public function get_error_when_store_group_without_alias(): void
    {
        $group = GroupModel::factory()->make();
        $url = route('api.inetstudio.classifiers-package.v1.groups.store');

        $id = Str::uuid();

        $data = [
            'type' => 'groups',
            'id' => $id,
            'attributes' => [
                'name' => $group->name,
                'description' => $group->description,
            ],
        ];

        $response = $this->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->post($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/attributes/alias'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseMissing('classifiers_package_groups', [
            'id' => $id,
            'name' => $group->name,
            'alias' => $group->alias,
            'description' => $group->description,
        ]);
    }

    /** @test */
    public function get_error_when_store_group_with_long_alias(): void
    {
        $group = GroupModel::factory()->make([
            'alias' => $this->faker->text(500),
        ]);
        $url = route('api.inetstudio.classifiers-package.v1.groups.store');

        $id = Str::uuid();

        $data = [
            'type' => 'groups',
            'id' => $id,
            'attributes' => [
                'alias' => $group->alias,
                'description' => $group->description,
                'name' => $group->name,
            ],
        ];

        $response = $this->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->post($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/attributes/alias'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseMissing('classifiers_package_groups', [
            'id' => $id,
            'name' => $group->name,
            'alias' => $group->alias,
            'description' => $group->description,
        ]);
    }

    /** @test */
    public function get_error_when_store_group_with_non_unique_alias(): void
    {
        $existsGroup = GroupModel::factory()->create();
        $group = GroupModel::factory()->make([
            'alias' => $existsGroup->alias,
        ]);
        $url = route('api.inetstudio.classifiers-package.v1.groups.store');

        $id = Str::uuid();

        $data = [
            'type' => 'groups',
            'id' => $id,
            'attributes' => [
                'alias' => $group->alias,
                'description' => $group->description,
                'name' => $group->name,
            ],
        ];

        $response = $this->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->post($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/attributes/alias'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseMissing('classifiers_package_groups', [
            'id' => $id,
            'name' => $group->name,
            'alias' => $group->alias,
            'description' => $group->description,
        ]);
    }

    /** @test */
    public function update_group(): void
    {
        $group = GroupModel::factory()->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.update', ['group' => $group->getRouteKey()]);

        $data = [
            'type' => 'groups',
            'id' => (string) $group->getRouteKey(),
            'attributes' => [
                'alias' => 'updated-alias',
                'name' => 'updated-name',
                'description' => 'updated-description',
            ],
        ];

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->patch($url);

        $response->assertFetchedOne($group);

        $this->assertDatabaseHas('classifiers_package_groups', [
            'id' => $group->getKey(),
            'alias' => $data['attributes']['alias'],
            'name' => $data['attributes']['name'],
            'description' => $data['attributes']['description'],
        ]);
    }

    /** @test */
    public function get_error_when_update_group_with_long_name(): void
    {
        $group = GroupModel::factory()->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.update', ['group' => $group->getRouteKey()]);

        $data = [
            'type' => 'groups',
            'id' => (string) $group->getRouteKey(),
            'attributes' => [
                'alias' => 'updated-alias',
                'name' => $this->faker->text(500),
                'description' => 'updated-description',
            ],
        ];

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->patch($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/attributes/name'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseHas('classifiers_package_groups', [
            'id' => $group->getKey(),
            'alias' => $group->alias,
            'name' => $group->name,
            'description' => $group->description,
        ]);
    }

    /** @test */
    public function get_error_when_update_group_with_long_alias(): void
    {
        $group = GroupModel::factory()->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.update', ['group' => $group->getRouteKey()]);

        $data = [
            'type' => 'groups',
            'id' => (string) $group->getRouteKey(),
            'attributes' => [
                'alias' => $this->faker->text(500),
                'name' => 'updated-name',
                'description' => 'updated-description',
            ],
        ];

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->patch($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/attributes/alias'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseHas('classifiers_package_groups', [
            'id' => $group->getKey(),
            'alias' => $group->alias,
            'name' => $group->name,
            'description' => $group->description,
        ]);
    }

    /** @test */
    public function get_error_when_update_group_with_non_unique_alias(): void
    {
        $existsGroup = GroupModel::factory()->create();
        $group = GroupModel::factory()->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.update', ['group' => $group->getRouteKey()]);

        $data = [
            'type' => 'groups',
            'id' => (string) $group->getRouteKey(),
            'attributes' => [
                'alias' => $existsGroup->alias,
                'name' => 'updated-name',
                'description' => 'updated-description',
            ],
        ];

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->patch($url);

        $response->assertErrorStatus([
            'source' => ['pointer' => '/data/attributes/alias'],
            'status' => '422',
            'title' => 'Unprocessable Entity',
        ]);

        $this->assertDatabaseHas('classifiers_package_groups', [
            'id' => $group->getKey(),
            'alias' => $group->alias,
            'name' => $group->name,
            'description' => $group->description,
        ]);
    }

    /** @test */
    public function get_error_when_update_non_existent_group(): void
    {
        $url = route('api.inetstudio.classifiers-package.v1.groups.update', ['group' => 'test']);

        $data = [
            'type' => 'groups',
            'id' => 'test',
            'attributes' => [
                'alias' => 'updated-alias',
                'name' => 'updated-name',
                'description' => 'updated-description',
            ],
        ];

        $response = $this
            ->jsonApi()
            ->expects('groups')
            ->withData($data)
            ->patch($url);

        $response->assertNotFound();
    }

    /** @test */
    public function destroy_group(): void
    {
        $group = GroupModel::factory()->create();
        $url = route('api.inetstudio.classifiers-package.v1.groups.destroy', ['group' => $group->getRouteKey()]);

        $response = $this
            ->jsonApi()
            ->delete($url);

        $response->assertNoContent();

        $this->assertSoftDeleted('classifiers_package_groups', [
            'id' => $group->getKey(),
        ]);
    }

    /** @test */
    public function get_error_when_destroy_non_existent_group(): void
    {
        $url = route('api.inetstudio.classifiers-package.v1.groups.destroy', ['group' => 'test']);

        $response = $this
            ->jsonApi()
            ->delete($url);

        $response->assertNotFound();
    }
}
