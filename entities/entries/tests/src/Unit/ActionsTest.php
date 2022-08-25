<?php

namespace InetStudio\ClassifiersPackage\Entries\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use InetStudio\AdminPanel\Base\Application\Exceptions\ResourceDoesNotExistException;
use InetStudio\AdminPanel\Base\Application\Exceptions\ResourceExistsException;
use InetStudio\AdminPanel\Base\Application\Exceptions\SaveResourceToDBException;
use InetStudio\ClassifiersPackage\Entries\Application\Actions\Resource\Destroy\DestroyAction;
use InetStudio\ClassifiersPackage\Entries\Application\Actions\Resource\Destroy\DestroyItemData;
use InetStudio\ClassifiersPackage\Entries\Application\Actions\Resource\Store\StoreAction;
use InetStudio\ClassifiersPackage\Entries\Application\Actions\Resource\Store\StoreItemData;
use InetStudio\ClassifiersPackage\Entries\Application\Actions\Resource\Update\UpdateAction;
use InetStudio\ClassifiersPackage\Entries\Application\Actions\Resource\Update\UpdateItemData;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModel;
use InetStudio\ClassifiersPackage\Entries\Tests\TestCase;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModel;
use stdClass;

class ActionsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    function an_entry_stored_without_groups(): void
    {
        $data = new StoreItemData(
            id: $this->faker->uuid(),
            value: $this->faker->text(255),
            alias: $this->faker->text(255),
            groups: []
        );

        $result = (new StoreAction(new EntryModel()))->execute($data);

        $this->assertDatabaseCount('classifiers_package_entries', 1);

        $this->assertEquals($data->id, $result->id);
        $this->assertEquals($data->value, $result->value);
        $this->assertEquals($data->alias, $result->alias);

        $this->assertDatabaseCount('classifiers_package_groups', 0);
        $this->assertEquals(0, $result->groups->count());
    }

    /** @test */
    function an_entry_stored_with_groups(): void
    {
        $groups = GroupModel::factory(2)->create();

        $data = new StoreItemData(
            id: $this->faker->uuid(),
            value: $this->faker->text(255),
            alias: $this->faker->text(255),
            groups: $groups->pluck('id')->toArray()
        );

        $result = (new StoreAction(new EntryModel()))->execute($data);

        $this->assertDatabaseCount('classifiers_package_entries', 1);

        $this->assertEquals($data->id, $result->id);
        $this->assertEquals($data->value, $result->value);
        $this->assertEquals($data->alias, $result->alias);

        $this->assertDatabaseCount('classifiers_package_groups', 2);
        $this->assertEquals(2, $result->groups->count());
    }

    /** @test */
    function get_exception_when_store_entry_with_exist_id(): void
    {
        $this->expectException(ResourceExistsException::class);

        $entry = EntryModel::factory()->create();

        $data = new StoreItemData(
            id: $entry->id,
            value: $this->faker->text(255),
            alias: $this->faker->text(255),
            groups: []
        );

        (new StoreAction(new EntryModel()))->execute($data);
    }

    /** @test */
    function get_exception_when_store_entry_with_exist_alias(): void
    {
        $this->expectException(ResourceExistsException::class);

        $entry = EntryModel::factory()->create();

        $data = new StoreItemData(
            id: $this->faker->uuid(),
            value: $this->faker->text(255),
            alias: $entry->alias,
            groups: []
        );

        (new StoreAction(new EntryModel()))->execute($data);
    }

    /** @test */
    function get_exception_when_store_group_transaction_failed(): void
    {
        $this->expectException(SaveResourceToDBException::class);

        $data = new StoreItemData(
            id: $this->faker->uuid(),
            value: $this->faker->text(255),
            alias: $this->faker->name(),
            groups: [new stdClass()]
        );

        (new StoreAction(new EntryModel()))->execute($data);
    }

    /** @test */
    function an_entry_updated_without_update_groups(): void
    {
        $entry = EntryModel::factory()->create();
        $groups = GroupModel::factory(2)->create();

        $entry->groups()->attach($groups);

        $data = new UpdateItemData(
            id: $entry->id,
            value: $this->faker->text(255),
            alias: $entry->alias,
            groups: null,
        );

        $result = (new UpdateAction(new EntryModel()))->execute($data);

        $this->assertDatabaseCount('classifiers_package_entries', 1);

        $this->assertEquals($data->id, $result->id);
        $this->assertEquals($data->value, $result->value);
        $this->assertEquals($data->alias, $result->alias);

        $this->assertTrue($result->groups->diff($groups)->isEmpty());
        $this->assertEquals(2, $result->groups->count());
    }

    /** @test */
    function an_entry_updated_with_attach_groups(): void
    {
        $entry = EntryModel::factory()->create();
        $groups = GroupModel::factory(2)->create();

        $data = new UpdateItemData(
            id: $entry->id,
            value: $this->faker->text(255),
            alias: $entry->alias,
            groups: $groups->pluck('id')->toArray(),
        );

        $result = (new UpdateAction(new EntryModel()))->execute($data);

        $this->assertDatabaseCount('classifiers_package_entries', 1);

        $this->assertEquals($data->id, $result->id);
        $this->assertEquals($data->value, $result->value);
        $this->assertEquals($data->alias, $result->alias);

        $this->assertTrue($result->groups->diff($groups)->isEmpty());
        $this->assertEquals(2, $result->groups->count());
    }

    /** @test */
    function an_entry_updated_with_detach_groups(): void
    {
        $entry = EntryModel::factory()->create();
        $groups = GroupModel::factory(2)->create();

        $entry->groups()->attach($groups);

        $data = new UpdateItemData(
            id: $entry->id,
            value: $this->faker->text(255),
            alias: $entry->alias,
            groups: [],
        );

        $result = (new UpdateAction(new EntryModel()))->execute($data);

        $this->assertDatabaseCount('classifiers_package_entries', 1);

        $this->assertEquals($data->id, $result->id);
        $this->assertEquals($data->value, $result->value);
        $this->assertEquals($data->alias, $result->alias);

        $this->assertEquals(0, $result->groups->count());
    }

    /** @test */
    function an_entry_updated_with_update_groups(): void
    {
        $entry = EntryModel::factory()->create();
        $groups = GroupModel::factory(5)->create();

        $entry->groups()->attach($groups);

        $newGroupsCollection = $groups->take(3);

        $data = new UpdateItemData(
            id: $entry->id,
            value: $this->faker->text(255),
            alias: $entry->alias,
            groups: $newGroupsCollection->pluck('id')->toArray(),
        );

        $result = (new UpdateAction(new EntryModel()))->execute($data);

        $this->assertDatabaseCount('classifiers_package_entries', 1);

        $this->assertEquals($data->id, $result->id);
        $this->assertEquals($data->value, $result->value);
        $this->assertEquals($data->alias, $result->alias);

        $this->assertTrue($result->groups->diff($newGroupsCollection)->isEmpty());
        $this->assertEquals(3, $result->groups->count());
    }

    /** @test */
    function get_exception_when_update_non_existent_entry(): void
    {
        $this->expectException(ResourceDoesNotExistException::class);

        $data = new UpdateItemData(
            id: $this->faker->uuid(),
            value: $this->faker->text(255),
            alias: $this->faker->text(255),
            groups: []
        );

        (new UpdateAction(new EntryModel()))->execute($data);
    }

    /** @test */
    function get_exception_when_update_entry_with_exist_alias(): void
    {
        $this->expectException(ResourceExistsException::class);

        $entries = EntryModel::factory(2)->create();

        $data = new UpdateItemData(
            id: $entries->first()->id,
            value: $this->faker->text(255),
            alias: $entries->last()->alias,
            groups: []
        );

        (new UpdateAction(new EntryModel()))->execute($data);
    }

    /** @test */
    function get_exception_when_update_entry_transaction_failed(): void
    {
        $this->expectException(SaveResourceToDBException::class);

        $entry = EntryModel::factory()->create();

        $data = new UpdateItemData(
            id: $entry->id,
            value: $this->faker->text(255),
            alias: $this->faker->name(),
            groups: [new stdClass()]
        );

        (new UpdateAction(new EntryModel()))->execute($data);
    }

    /** @test */
    function an_entry_destroyed(): void
    {
        $entries = EntryModel::factory(2)->create();

        $data = new DestroyItemData(
            id: $entries->first()->id,
        );

        $result = (new DestroyAction(new EntryModel))->execute($data);

        $this->assertCount(1, EntryModel::all());
        $this->assertNotEquals($entries->first()->id, EntryModel::first()->id);
        $this->assertEquals(1, $result);
    }

    /** @test */
    function get_exception_when_destroy_non_existent_entry(): void
    {
        $this->expectException(ResourceDoesNotExistException::class);

        EntryModel::factory()->create();

        $data = new DestroyItemData(
            id: $this->faker->uuid()
        );

        $result = (new DestroyAction(new EntryModel))->execute($data);

        $this->assertCount(1, EntryModel::all());
        $this->assertEquals(0, $result);
    }
}
