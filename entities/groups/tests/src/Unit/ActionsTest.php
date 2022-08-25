<?php

namespace InetStudio\ClassifiersPackage\Groups\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use InetStudio\AdminPanel\Base\Application\Exceptions\ResourceDoesNotExistException;
use InetStudio\AdminPanel\Base\Application\Exceptions\ResourceExistsException;
use InetStudio\AdminPanel\Base\Application\Exceptions\SaveResourceToDBException;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModel;
use InetStudio\ClassifiersPackage\Groups\Application\Actions\Resource\Destroy\DestroyAction;
use InetStudio\ClassifiersPackage\Groups\Application\Actions\Resource\Destroy\DestroyItemData;
use InetStudio\ClassifiersPackage\Groups\Application\Actions\Resource\Store\StoreAction;
use InetStudio\ClassifiersPackage\Groups\Application\Actions\Resource\Store\StoreItemData;
use InetStudio\ClassifiersPackage\Groups\Application\Actions\Resource\Update\UpdateAction;
use InetStudio\ClassifiersPackage\Groups\Application\Actions\Resource\Update\UpdateItemData;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModel;
use InetStudio\ClassifiersPackage\Groups\Tests\TestCase;
use stdClass;

class ActionsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    function a_group_stored_without_entries(): void
    {
        $data = new StoreItemData(
            id: $this->faker->uuid(),
            name: $this->faker->text(255),
            alias: $this->faker->text(255),
            description: $this->faker->realText(),
            entries: []
        );

        $result = (new StoreAction(new GroupModel()))->execute($data);

        $this->assertDatabaseCount('classifiers_package_groups', 1);

        $this->assertEquals($data->id, $result->id);
        $this->assertEquals($data->name, $result->name);
        $this->assertEquals($data->alias, $result->alias);
        $this->assertEquals($data->description, $result->description);

        $this->assertDatabaseCount('classifiers_package_entries', 0);
        $this->assertEquals(0, $result->entries->count());
    }

    /** @test */
    function a_group_stored_with_entries(): void
    {
        $entries = EntryModel::factory(2)->create();

        $data = new StoreItemData(
            id: $this->faker->uuid(),
            name: $this->faker->text(255),
            alias: $this->faker->text(255),
            description: $this->faker->realText(),
            entries: $entries->pluck('id')->toArray()
        );

        $result = (new StoreAction(new GroupModel()))->execute($data);

        $this->assertDatabaseCount('classifiers_package_groups', 1);

        $this->assertEquals($data->id, $result->id);
        $this->assertEquals($data->name, $result->name);
        $this->assertEquals($data->alias, $result->alias);
        $this->assertEquals($data->description, $result->description);

        $this->assertTrue($result->entries->diff($entries)->isEmpty());
        $this->assertEquals(2, $result->entries->count());
    }

    /** @test */
    function get_exception_when_store_group_with_exist_id(): void
    {
        $this->expectException(ResourceExistsException::class);

        $group = GroupModel::factory()->create();

        $data = new StoreItemData(
            id: $group->id,
            name: $this->faker->text(255),
            alias: $this->faker->text(255),
            description: $this->faker->realText(),
            entries: []
        );

        (new StoreAction(new GroupModel()))->execute($data);
    }

    /** @test */
    function get_exception_when_store_group_with_exist_alias(): void
    {
        $this->expectException(ResourceExistsException::class);

        $group = GroupModel::factory()->create();

        $data = new StoreItemData(
            id: $this->faker->uuid(),
            name: $this->faker->text(255),
            alias: $group->alias,
            description: $this->faker->realText(),
            entries: []
        );

        (new StoreAction(new GroupModel()))->execute($data);
    }

    /** @test */
    function get_exception_when_store_group_transaction_failed(): void
    {
        $this->expectException(SaveResourceToDBException::class);

        $data = new StoreItemData(
            id: $this->faker->uuid(),
            name: $this->faker->text(255),
            alias: $this->faker->name(),
            description: $this->faker->realText(),
            entries: [new stdClass()]
        );

        (new StoreAction(new GroupModel()))->execute($data);
    }

    /** @test */
    function a_group_updated_without_update_entries(): void
    {
        $group = GroupModel::factory()->create();
        $entries = EntryModel::factory(2)->create();

        $group->entries()->attach($entries);

        $data = new UpdateItemData(
            id: $group->id,
            name: $this->faker->text(255),
            alias: $group->alias,
            description: $this->faker->realText(),
            entries: null,
        );

        $result = (new UpdateAction(new GroupModel()))->execute($data);

        $this->assertDatabaseCount('classifiers_package_groups', 1);

        $this->assertEquals($data->id, $result->id);
        $this->assertEquals($data->name, $result->name);
        $this->assertEquals($data->alias, $result->alias);
        $this->assertEquals($data->description, $result->description);

        $this->assertTrue($result->entries->diff($entries)->isEmpty());
        $this->assertEquals(2, $result->entries->count());
    }

    /** @test */
    function a_group_updated_with_attach_entries(): void
    {
        $group = GroupModel::factory()->create();
        $entries = EntryModel::factory(2)->create();

        $data = new UpdateItemData(
            id: $group->id,
            name: $this->faker->text(255),
            alias: $group->alias,
            description: $this->faker->realText(),
            entries: $entries->pluck('id')->toArray(),
        );

        $result = (new UpdateAction(new GroupModel()))->execute($data);

        $this->assertDatabaseCount('classifiers_package_groups', 1);

        $this->assertEquals($data->id, $result->id);
        $this->assertEquals($data->name, $result->name);
        $this->assertEquals($data->alias, $result->alias);
        $this->assertEquals($data->description, $result->description);

        $this->assertTrue($result->entries->diff($entries)->isEmpty());
        $this->assertEquals(2, $result->entries->count());
    }

    /** @test */
    function a_group_updated_with_detach_entries(): void
    {
        $group = GroupModel::factory()->create();
        $entries = EntryModel::factory(2)->create();

        $group->entries()->attach($entries);

        $data = new UpdateItemData(
            id: $group->id,
            name: $this->faker->text(255),
            alias: $group->alias,
            description: $this->faker->realText(),
            entries: [],
        );

        $result = (new UpdateAction(new GroupModel()))->execute($data);

        $this->assertDatabaseCount('classifiers_package_groups', 1);

        $this->assertEquals($data->id, $result->id);
        $this->assertEquals($data->name, $result->name);
        $this->assertEquals($data->alias, $result->alias);
        $this->assertEquals($data->description, $result->description);

        $this->assertEquals(0, $result->entries->count());
    }

    /** @test */
    function a_group_updated_with_update_entries(): void
    {
        $group = GroupModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $group->entries()->attach($entries);

        $newEntriesCollection = $entries->take(3);

        $data = new UpdateItemData(
            id: $group->id,
            name: $this->faker->text(255),
            alias: $group->alias,
            description: $this->faker->realText(),
            entries: $newEntriesCollection->pluck('id')->toArray(),
        );

        $result = (new UpdateAction(new GroupModel()))->execute($data);

        $this->assertDatabaseCount('classifiers_package_groups', 1);

        $this->assertEquals($data->id, $result->id);
        $this->assertEquals($data->name, $result->name);
        $this->assertEquals($data->alias, $result->alias);
        $this->assertEquals($data->description, $result->description);

        $this->assertTrue($result->entries->diff($newEntriesCollection)->isEmpty());
        $this->assertEquals(3, $result->entries->count());
    }

    /** @test */
    function get_exception_when_update_non_existent_group(): void
    {
        $this->expectException(ResourceDoesNotExistException::class);

        $data = new UpdateItemData(
            id: $this->faker->uuid(),
            name: $this->faker->text(255),
            alias: $this->faker->text(255),
            description: $this->faker->realText(),
            entries: []
        );

        (new UpdateAction(new GroupModel()))->execute($data);
    }

    /** @test */
    function get_exception_when_update_group_with_exist_alias(): void
    {
        $this->expectException(ResourceExistsException::class);

        $groups = GroupModel::factory(2)->create();

        $data = new UpdateItemData(
            id: $groups->first()->id,
            name: $this->faker->text(255),
            alias: $groups->last()->alias,
            description: $this->faker->realText(),
            entries: []
        );

        (new UpdateAction(new GroupModel))->execute($data);
    }

    /** @test */
    function get_exception_when_update_group_transaction_failed(): void
    {
        $this->expectException(SaveResourceToDBException::class);

        $group = GroupModel::factory()->create();

        $data = new UpdateItemData(
            id: $group->id,
            name: $this->faker->text(255),
            alias: $this->faker->name(),
            description: $this->faker->realText(),
            entries: [new stdClass()]
        );

        (new UpdateAction(new GroupModel()))->execute($data);
    }

    /** @test */
    function a_group_destroyed(): void
    {
        $groups = GroupModel::factory(2)->create();

        $data = new DestroyItemData(
            id: $groups->first()->id,
        );

        $result = (new DestroyAction(new GroupModel()))->execute($data);

        $this->assertCount(1, GroupModel::all());
        $this->assertNotEquals($groups->first()->id, GroupModel::first()->id);
        $this->assertEquals(1, $result);
    }

    /** @test */
    function get_exception_when_destroy_non_existent_group(): void
    {
        $this->expectException(ResourceDoesNotExistException::class);

        GroupModel::factory()->create();

        $data = new DestroyItemData(
            id: $this->faker->uuid()
        );

        (new DestroyAction(new GroupModel()))->execute($data);
    }
}
