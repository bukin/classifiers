<?php

namespace InetStudio\ClassifiersPackage\Entries\Tests\Unit;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use InetStudio\ClassifiersPackage\Entries\Application\DTO\AttachData;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModel;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModelContract;
use InetStudio\ClassifiersPackage\Entries\Tests\Support\Models\TestModel;
use InetStudio\ClassifiersPackage\Entries\Tests\TestCase;

class HasClassifiersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_model_has_a_classifiers_class_name(): void
    {
        $classifierModel = resolve(EntryModelContract::class);

        $model = TestModel::factory()->create();

        $this->assertEquals($classifierModel::class, $model->getClassifierClassName());
    }

    /** @test */
    function a_model_has_a_morph_classifiers_relation(): void
    {
        $model = TestModel::factory()->create();

        $this->assertInstanceOf(MorphToMany::class, $model->classifiers());
    }

    /** @test */
    function attach_classifiers_by_set_attribute(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->classifiers = new AttachData(
            entries: $entries,
            attributes: [],
            collection: 'default'
        );

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(5, $model->classifiers('default')->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
    }

    /** @test */
    function attach_classifiers_after_model_saving(): void
    {
        $model = TestModel::factory()->make();
        $entries = EntryModel::factory(5)->create();

        $model->classifiers = new AttachData(
            entries: $entries,
            attributes: [],
            collection: 'default'
        );

        $model->save();

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(5, $model->classifiers('default')->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
    }

    /** @test */
    function attach_classifiers_by_string_id_to_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->attachClassifiers($entry->id);

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(1, $model->classifiers('default')->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
    }

    /** @test */
    function attach_classifiers_by_string_alias_to_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->attachClassifiers($entry->alias);

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(1, $model->classifiers('default')->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
    }

    /** @test */
    function attach_classifiers_by_array_ids_to_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries->pluck('id')->toArray());

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals(['default'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function attach_classifiers_by_array_aliases_to_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries->pluck('alias')->toArray());

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals(['default'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function attach_classifiers_by_collection_to_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals(['default'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function attach_classifiers_by_model_to_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->attachClassifiers($entry);

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals('default', $model->classifiers()->first()->pivot->collection);
    }

    /** @test */
    function attach_classifiers_by_string_id_to_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->attachClassifiers($entry->id, [], 'custom_collection');

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals('custom_collection', $model->classifiers()->first()->pivot->collection);
    }

    /** @test */
    function attach_classifiers_by_string_alias_to_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->attachClassifiers($entry->alias, [], 'custom_collection');

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals('custom_collection', $model->classifiers()->first()->pivot->collection);
    }

    /** @test */
    function attach_classifiers_by_array_ids_to_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries->pluck('id')->toArray(), [], 'custom_collection');

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals(['custom_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function attach_classifiers_by_array_aliases_to_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries->pluck('alias')->toArray(), [], 'custom_collection');

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals(['custom_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function attach_classifiers_by_collection_to_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries, [], 'custom_collection');

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals(['custom_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function attach_classifiers_by_model_to_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->attachClassifiers($entry, [], 'custom_collection');

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals('custom_collection', $model->classifiers()->first()->pivot->collection);
    }

    /** @test */
    function attach_classifiers_to_multiple_collections(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries->first()->id);
        $model->attachClassifiers($entries->pluck('id')->toArray(), [], 'custom_collection1');
        $model->attachClassifiers($entries->first(), [], 'custom_collection2');
        $model->attachClassifiers($entries, [], 'custom_collection3');

        $this->assertEquals(12, $model->classifiers()->count());
        $this->assertEquals(1, $model->classifiers('default')->count());
        $this->assertEquals(5, $model->classifiers('custom_collection1')->count());
        $this->assertEquals(1, $model->classifiers('custom_collection2')->count());
        $this->assertEquals(5, $model->classifiers('custom_collection3')->count());
        $this->assertEquals(4, $model->classifiers->pluck('pivot.collection')->unique()->count());
        $this->assertEquals(1, $model->classifiers->where('pivot.collection', 'default')->count());
        $this->assertEquals(5, $model->classifiers->where('pivot.collection', 'custom_collection1')->count());
        $this->assertEquals(1, $model->classifiers->where('pivot.collection', 'custom_collection2')->count());
        $this->assertEquals(5, $model->classifiers->where('pivot.collection', 'custom_collection3')->count());
    }

    /** @test */
    function attach_classifiers_by_string_id_with_attributes(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $customAttribute = Str::uuid()->toString();

        $model->attachClassifiers($entry->id, [
            $entry->id => ['id' => $customAttribute]
        ]);

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals($customAttribute, $model->classifiers()->first()->pivot->id);
    }

    /** @test */
    function attach_classifiers_by_string_alias_with_attributes(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $customAttribute = Str::uuid()->toString();

        $model->attachClassifiers($entry->alias, [
            $entry->id => ['id' => $customAttribute]
        ]);

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals($customAttribute, $model->classifiers()->first()->pivot->id);
    }

    /** @test */
    function attach_classifiers_by_array_ids_with_attributes(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $attributes = $entries->mapWithKeys(function ($item) {
            return [$item->id => ['id' => Str::uuid()->toString()]];
        })->toArray();

        $model->attachClassifiers($entries->pluck('id')->toArray(), $attributes);

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals($attributes, $model->classifiers->mapWithKeys(function ($item) {
            return [$item->id => ['id' => $item->pivot->id]];
        })->toArray());
    }

    /** @test */
    function attach_classifiers_by_array_aliases_with_attributes(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $attributes = $entries->mapWithKeys(function ($item) {
            return [$item->id => ['id' => Str::uuid()->toString()]];
        })->toArray();

        $model->attachClassifiers($entries->pluck('alias')->toArray(), $attributes);

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals($attributes, $model->classifiers->mapWithKeys(function ($item) {
            return [$item->id => ['id' => $item->pivot->id]];
        })->toArray());
    }

    /** @test */
    function attach_classifiers_by_collection_with_attributes(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $attributes = $entries->mapWithKeys(function ($item) {
            return [$item->id => ['id' => Str::uuid()->toString()]];
        })->toArray();

        $model->attachClassifiers($entries, $attributes);

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals($attributes, $model->classifiers->mapWithKeys(function ($item) {
            return [$item->id => ['id' => $item->pivot->id]];
        })->toArray());
    }

    /** @test */
    function attach_classifiers_by_model_with_attributes(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $customAttribute = Str::uuid()->toString();

        $model->attachClassifiers($entry, [
            $entry->id => ['id' => $customAttribute]
        ]);

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals($customAttribute, $model->classifiers()->first()->pivot->id);
    }

    /** @test */
    function attach_classifiers_by_string_id_with_attributes_and_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $customAttribute = Str::uuid()->toString();

        $model->attachClassifiers($entry->id, [
            $entry->id => ['id' => $customAttribute]
        ], 'custom_collection');

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals($customAttribute, $model->classifiers()->first()->pivot->id);
        $this->assertEquals('custom_collection', $model->classifiers()->first()->pivot->collection);
    }

    /** @test */
    function attach_classifiers_by_string_alias_with_attributes_and_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $customAttribute = Str::uuid()->toString();

        $model->attachClassifiers($entry->alias, [
            $entry->id => ['id' => $customAttribute]
        ], 'custom_collection');

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals($customAttribute, $model->classifiers()->first()->pivot->id);
        $this->assertEquals('custom_collection', $model->classifiers()->first()->pivot->collection);
    }

    /** @test */
    function attach_classifiers_by_array_ids_with_attributes_and_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $attributes = $entries->mapWithKeys(function ($item) {
            return [$item->id => ['id' => Str::uuid()->toString()]];
        })->toArray();

        $model->attachClassifiers($entries->pluck('id')->toArray(), $attributes, 'custom_collection');

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals($attributes, $model->classifiers->mapWithKeys(function ($item) {
            return [$item->id => ['id' => $item->pivot->id]];
        })->toArray());
        $this->assertEquals(['custom_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function attach_classifiers_by_array_aliases_with_attributes_and_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $attributes = $entries->mapWithKeys(function ($item) {
            return [$item->id => ['id' => Str::uuid()->toString()]];
        })->toArray();

        $model->attachClassifiers($entries->pluck('alias')->toArray(), $attributes, 'custom_collection');

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals($attributes, $model->classifiers->mapWithKeys(function ($item) {
            return [$item->id => ['id' => $item->pivot->id]];
        })->toArray());
        $this->assertEquals(['custom_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function attach_classifiers_by_collection_with_attributes_and_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $attributes = $entries->mapWithKeys(function ($item) {
            return [$item->id => ['id' => Str::uuid()->toString()]];
        })->toArray();

        $model->attachClassifiers($entries, $attributes, 'custom_collection');

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals($attributes, $model->classifiers->mapWithKeys(function ($item) {
            return [$item->id => ['id' => $item->pivot->id]];
        })->toArray());
        $this->assertEquals(['custom_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function attach_classifiers_by_model_with_attributes_and_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $customAttribute = Str::uuid()->toString();

        $model->attachClassifiers($entry, [
            $entry->id => ['id' => $customAttribute]
        ], 'custom_collection');

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals($customAttribute, $model->classifiers()->first()->pivot->id);
        $this->assertEquals(['custom_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function sync_attach_classifiers_by_string_id_in_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->syncClassifiers($entry->id);

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(1, $model->classifiers('default')->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals(['default'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function sync_attach_classifiers_by_string_alias_in_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->syncClassifiers($entry->alias);

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(1, $model->classifiers('default')->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals(['default'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function sync_attach_classifiers_by_array_ids_in_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->syncClassifiers($entries->pluck('id')->toArray());

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(5, $model->classifiers('default')->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals(['default'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function sync_attach_classifiers_by_array_aliases_in_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->syncClassifiers($entries->pluck('alias')->toArray());

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(5, $model->classifiers('default')->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals(['default'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function sync_attach_classifiers_by_collection_in_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->syncClassifiers($entries);

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(5, $model->classifiers('default')->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals(['default'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function sync_attach_classifiers_by_model_in_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->syncClassifiers($entry);

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(1, $model->classifiers('default')->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals('default', $model->classifiers()->first()->pivot->collection);
    }

    /** @test */
    function sync_attach_classifiers_by_string_id_in_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->syncClassifiers($entry, [], 'custom_collection');

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(1, $model->classifiers('custom_collection')->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals(['custom_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function sync_attach_classifiers_by_string_alias_in_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->syncClassifiers($entry->alias, [], 'custom_collection');

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(1, $model->classifiers('custom_collection')->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals(['custom_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function sync_attach_classifiers_by_array_ids_in_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->syncClassifiers($entries->pluck('id')->toArray(), [], 'custom_collection');

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(5, $model->classifiers('custom_collection')->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals(['custom_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function sync_attach_classifiers_by_array_aliases_in_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->syncClassifiers($entries->pluck('alias')->toArray(), [], 'custom_collection');

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(5, $model->classifiers('custom_collection')->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals(['custom_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function sync_attach_classifiers_by_collection_in_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->syncClassifiers($entries, [], 'custom_collection');

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(5, $model->classifiers('custom_collection')->count());
        $this->assertEquals(true, $model->classifiers->diff($entries)->isEmpty());
        $this->assertEquals(['custom_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function sync_attach_classifiers_by_model_in_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->syncClassifiers($entry, [], 'custom_collection');

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(1, $model->classifiers('custom_collection')->count());
        $this->assertEquals(true, $model->classifiers->contains($entry));
        $this->assertEquals('custom_collection', $model->classifiers()->first()->pivot->collection);
    }

    /** @test */
    function sync_detach_classifiers_in_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->attachClassifiers($entry);
        $model->syncClassifiers();

        $this->assertEquals(0, $model->classifiers()->count());
        $this->assertEquals(0, $model->classifiers('default')->count());
    }

    /** @test */
    function sync_detach_classifiers_in_custom_collection(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->attachClassifiers($entry, [], 'custom_collection');
        $model->syncClassifiers([], [], 'custom_collection');

        $this->assertEquals(0, $model->classifiers()->count());
        $this->assertEquals(0, $model->classifiers('custom_collection')->count());
    }

    /** @test */
    function sync_attach_detach_classifiers_by_array_ids_in_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);

        $newEntry = EntryModel::factory()->create();
        $newClassifiers = $entries->take(2)->pluck('id')->toArray();
        $newClassifiers[] = $newEntry->id;

        $model->syncClassifiers($newClassifiers);

        $this->assertEquals(3, $model->classifiers()->count());
        $this->assertEquals(3, $model->classifiers('default')->count());
        $this->assertCount(0, array_intersect($newClassifiers, $model->classifiers('custom_collection')->pluck('classifiers_package_entries.id')->toArray()));
        $this->assertEquals(['default'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function sync_attach_detach_classifiers_by_array_aliases_in_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);

        $newEntry = EntryModel::factory()->create();
        $newClassifiers = $entries->take(2)->pluck('alias')->toArray();
        $newClassifiers[] = $newEntry->alias;

        $model->syncClassifiers($newClassifiers);

        $this->assertEquals(3, $model->classifiers()->count());
        $this->assertEquals(3, $model->classifiers('default')->count());
        $this->assertCount(0, array_intersect($newClassifiers, $model->classifiers('custom_collection')->pluck('classifiers_package_entries.id')->toArray()));
        $this->assertEquals(['default'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function sync_attach_detach_classifiers_by_collection_in_default_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);

        $newEntry = EntryModel::factory()->create();
        $newClassifiers = $entries->take(2)->push($newEntry);

        $model->syncClassifiers($newClassifiers);

        $this->assertEquals(3, $model->classifiers()->count());
        $this->assertEquals(3, $model->classifiers('default')->count());
        $this->assertCount(0, array_intersect($newClassifiers->pluck('id')->toArray(), $model->classifiers('custom_collection')->pluck('classifiers_package_entries.id')->toArray()));
        $this->assertEquals(['default'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function full_detach_classifiers_by_sync_method(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);

        $this->assertEquals(5, $model->classifiers()->count());

        $model->syncClassifiers(null);

        $this->assertEquals(0, $model->classifiers()->count());
    }

    /** @test */
    function full_detach_classifiers_by_detach_method(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);

        $this->assertEquals(5, $model->classifiers()->count());

        $model->detachClassifiers(null);

        $this->assertEquals(0, $model->classifiers()->count());
    }

    /** @test */
    function full_detach_classifiers_by_string_id(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->attachClassifiers($entry, [], 'first_collection');
        $model->attachClassifiers($entry, [], 'second_collection');

        $this->assertEquals(2, $model->classifiers()->count());

        $model->detachClassifiers($entry->id, null);

        $this->assertEquals(0, $model->classifiers()->count());
    }

    /** @test */
    function full_detach_classifiers_by_string_alias(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->attachClassifiers($entry, [], 'first_collection');
        $model->attachClassifiers($entry, [], 'second_collection');

        $this->assertEquals(2, $model->classifiers()->count());

        $model->detachClassifiers($entry->alias, null);

        $this->assertEquals(0, $model->classifiers()->count());
    }

    /** @test */
    function full_detach_classifiers_by_array_ids(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries, [], 'first_collection');
        $model->attachClassifiers($entries, [], 'second_collection');

        $this->assertEquals(10, $model->classifiers()->count());

        $model->detachClassifiers($entries->pluck('id')->toArray(), null);

        $this->assertEquals(0, $model->classifiers()->count());
    }

    /** @test */
    function full_detach_classifiers_by_array_aliases(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries, [], 'first_collection');
        $model->attachClassifiers($entries, [], 'second_collection');

        $this->assertEquals(10, $model->classifiers()->count());

        $model->detachClassifiers($entries->pluck('alias')->toArray(), null);

        $this->assertEquals(0, $model->classifiers()->count());
    }

    /** @test */
    function full_detach_classifiers_by_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries, [], 'first_collection');
        $model->attachClassifiers($entries, [], 'second_collection');

        $this->assertEquals(10, $model->classifiers()->count());

        $model->detachClassifiers($entries, null);

        $this->assertEquals(0, $model->classifiers()->count());
    }

    /** @test */
    function full_detach_classifiers_by_model(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->attachClassifiers($entry, [], 'first_collection');
        $model->attachClassifiers($entry, [], 'second_collection');

        $this->assertEquals(2, $model->classifiers()->count());

        $model->detachClassifiers($entry, null);

        $this->assertEquals(0, $model->classifiers()->count());
    }

    /** @test */
    function full_detach_classifiers_in_one_collection_by_string_id(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->attachClassifiers($entry, [], 'first_collection');
        $model->attachClassifiers($entry, [], 'second_collection');

        $this->assertEquals(2, $model->classifiers()->count());

        $model->detachClassifiers($entry->id, 'first_collection');

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(['second_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function full_detach_classifiers_in_one_collection_by_string_alias(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->attachClassifiers($entry, [], 'first_collection');
        $model->attachClassifiers($entry, [], 'second_collection');

        $this->assertEquals(2, $model->classifiers()->count());

        $model->detachClassifiers($entry->alias, 'first_collection');

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(['second_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function full_detach_classifiers_in_one_collection_by_array_ids(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries, [], 'first_collection');
        $model->attachClassifiers($entries, [], 'second_collection');

        $this->assertEquals(10, $model->classifiers()->count());

        $model->detachClassifiers($entries->pluck('id')->toArray(), 'first_collection');

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(['second_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function full_detach_classifiers_in_one_collection_by_array_aliases(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries, [], 'first_collection');
        $model->attachClassifiers($entries, [], 'second_collection');

        $this->assertEquals(10, $model->classifiers()->count());

        $model->detachClassifiers($entries->pluck('alias')->toArray(), 'first_collection');

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(['second_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function full_detach_classifiers_in_one_collection_by_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries, [], 'first_collection');
        $model->attachClassifiers($entries, [], 'second_collection');

        $this->assertEquals(10, $model->classifiers()->count());

        $model->detachClassifiers($entries, 'first_collection');

        $this->assertEquals(5, $model->classifiers()->count());
        $this->assertEquals(['second_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function full_detach_classifiers_in_one_collection_by_model(): void
    {
        $model = TestModel::factory()->create();
        $entry = EntryModel::factory()->create();

        $model->attachClassifiers($entry, [], 'first_collection');
        $model->attachClassifiers($entry, [], 'second_collection');

        $this->assertEquals(2, $model->classifiers()->count());

        $model->detachClassifiers($entry, 'first_collection');

        $this->assertEquals(1, $model->classifiers()->count());
        $this->assertEquals(['second_collection'], $model->classifiers->pluck('pivot.collection')->unique()->toArray());
    }

    /** @test */
    function partial_detach_classifiers_in_collection_by_string_id(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);
        $model->attachClassifiers($entries, [], 'custom_collection');

        $this->assertEquals(10, $model->classifiers()->count());

        $detachedClassifier = $entries->first()->id;

        $model->detachClassifiers($detachedClassifier, 'custom_collection');

        $this->assertEquals(9, $model->classifiers()->count());
        $this->assertEquals(5, $model->classifiers('default')->count());
        $this->assertEquals(4, $model->classifiers('custom_collection')->count());
        $this->assertEquals(false, in_array($detachedClassifier, $model->classifiers('custom_collection')->pluck('classifiers_package_entries.id')->toArray()));
    }

    /** @test */
    function partial_detach_classifiers_in_collection_by_string_alias(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);
        $model->attachClassifiers($entries, [], 'custom_collection');

        $this->assertEquals(10, $model->classifiers()->count());

        $detachedClassifier = $entries->first()->alias;

        $model->detachClassifiers($detachedClassifier, 'custom_collection');

        $this->assertEquals(9, $model->classifiers()->count());
        $this->assertEquals(5, $model->classifiers('default')->count());
        $this->assertEquals(4, $model->classifiers('custom_collection')->count());
        $this->assertEquals(false, in_array($detachedClassifier, $model->classifiers('custom_collection')->pluck('classifiers_package_entries.id')->toArray()));
    }

    /** @test */
    function partial_detach_classifiers_in_collection_by_array_ids(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);
        $model->attachClassifiers($entries, [], 'custom_collection');

        $this->assertEquals(10, $model->classifiers()->count());

        $detachedClassifiers = $entries->take(2)->pluck('id')->toArray();

        $model->detachClassifiers($detachedClassifiers, 'custom_collection');

        $this->assertEquals(8, $model->classifiers()->count());
        $this->assertEquals(5, $model->classifiers('default')->count());
        $this->assertEquals(3, $model->classifiers('custom_collection')->count());
        $this->assertCount(0, array_intersect($detachedClassifiers, $model->classifiers('custom_collection')->pluck('classifiers_package_entries.id')->toArray()));
    }

    /** @test */
    function partial_detach_classifiers_in_collection_by_array_aliases(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);
        $model->attachClassifiers($entries, [], 'custom_collection');

        $this->assertEquals(10, $model->classifiers()->count());

        $detachedClassifiers = $entries->take(2)->pluck('alias')->toArray();

        $model->detachClassifiers($detachedClassifiers, 'custom_collection');

        $this->assertEquals(8, $model->classifiers()->count());
        $this->assertEquals(5, $model->classifiers('default')->count());
        $this->assertEquals(3, $model->classifiers('custom_collection')->count());
        $this->assertCount(0, array_intersect($detachedClassifiers, $model->classifiers('custom_collection')->pluck('alias')->toArray()));
    }

    /** @test */
    function partial_detach_classifiers_in_collection_by_collection(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);
        $model->attachClassifiers($entries, [], 'custom_collection');

        $this->assertEquals(10, $model->classifiers()->count());

        $detachedClassifiers = $entries->take(2);

        $model->detachClassifiers($detachedClassifiers, 'custom_collection');

        $this->assertEquals(8, $model->classifiers()->count());
        $this->assertEquals(5, $model->classifiers('default')->count());
        $this->assertEquals(3, $model->classifiers('custom_collection')->count());
        $this->assertCount(0, array_intersect($detachedClassifiers->pluck('id')->toArray(), $model->classifiers('custom_collection')->pluck('classifiers_package_entries.id')->toArray()));
    }

    /** @test */
    function partial_detach_classifiers_in_collection_by_model(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);
        $model->attachClassifiers($entries, [], 'custom_collection');

        $this->assertEquals(10, $model->classifiers()->count());

        $detachedClassifier = $entries->first();

        $model->detachClassifiers($detachedClassifier, 'custom_collection');

        $this->assertEquals(9, $model->classifiers()->count());
        $this->assertEquals(5, $model->classifiers('default')->count());
        $this->assertEquals(4, $model->classifiers('custom_collection')->count());
        $this->assertEquals(false, in_array($detachedClassifier->id, $model->classifiers('custom_collection')->pluck('classifiers_package_entries.id')->toArray()));
    }

    /** @test */
    function detach_classifiers_after_model_deleting(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);

        $model->delete();
        $this->assertDatabaseCount('classifiers_package_classifierables', 0);
    }

    /** @test */
    function scope_models_with_all_classifiers(): void
    {
        $models = TestModel::factory(5)->create();
        $entries = EntryModel::factory(5)->create();

        $models[0]->attachClassifiers($entries->take(2));
        $models[1]->attachClassifiers($entries);
        $models[2]->attachClassifiers($entries[2]);
        $models[3]->attachClassifiers($entries[3]);
        $models[4]->attachClassifiers($entries[4]);

        $modelsWithAllClassifiers = TestModel::withAllClassifiers($entries->take(2))->get();

        $this->assertEquals(2, $modelsWithAllClassifiers->count());
        $this->assertEquals([$models[0]->id, $models[1]->id], $modelsWithAllClassifiers->pluck('id')->toArray());
    }

    /** @test */
    function scope_models_with_any_classifiers(): void
    {
        $models = TestModel::factory(5)->create();
        $entries = EntryModel::factory(5)->create();

        $models->first()->attachClassifiers($entries->take(2));
        $models->last()->attachClassifiers($entries);

        $modelsWithAnyClassifiers = TestModel::withAnyClassifiers($entries->take(2))->get();

        $this->assertEquals(2, $modelsWithAnyClassifiers->count());
        $this->assertEquals([$models->first()->id, $models->last()->id], $modelsWithAnyClassifiers->pluck('id')->toArray());
    }

    /** @test */
    function scope_models_with_classifiers(): void
    {
        $models = TestModel::factory(5)->create();
        $entries = EntryModel::factory(5)->create();

        $models->first()->attachClassifiers($entries->take(2));
        $models->last()->attachClassifiers($entries);

        $modelsWithClassifiers = TestModel::withClassifiers($entries->take(2))->get();

        $this->assertEquals(2, $modelsWithClassifiers->count());
        $this->assertEquals([$models->first()->id, $models->last()->id], $modelsWithClassifiers->pluck('id')->toArray());
    }

    /** @test */
    function scope_models_without_classifiers(): void
    {
        $models = TestModel::factory(5)->create();
        $entries = EntryModel::factory(5)->create();

        $models->first()->attachClassifiers($entries->take(2));
        $models->last()->attachClassifiers($entries);

        $modelsWithoutClassifiers = TestModel::withoutClassifiers($entries->take(2))->get();

        $this->assertEquals(3, $modelsWithoutClassifiers->count());
        $this->assertCount(0, array_intersect([$models->first()->id, $models->last()->id], $modelsWithoutClassifiers->pluck('id')->toArray()));
    }

    /** @test */
    function scope_models_without_any_classifiers(): void
    {
        $models = TestModel::factory(5)->create();
        $entries = EntryModel::factory(5)->create();

        $models->first()->attachClassifiers($entries);
        $models->last()->attachClassifiers($entries);

        $modelsWithoutAnyClassifiers = TestModel::withoutAnyClassifiers()->get();

        $this->assertEquals(3, $modelsWithoutAnyClassifiers->count());
        $this->assertCount(0, array_intersect([$models->first()->id, $models->last()->id], $modelsWithoutAnyClassifiers->pluck('id')->toArray()));
    }

    /** @test */
    function get_all_collections_classifiers_list(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);
        $model->attachClassifiers($entries, [], 'custom_collection');

        $expect = [
            'default' => $entries->pluck('alias', 'id')->toArray(),
            'custom_collection' => $entries->pluck('alias', 'id')->toArray(),
        ];

        $this->assertEquals($expect, $model->getClassifiersList());
    }

    /** @test */
    function get_custom_collection_classifiers_list(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);
        $model->attachClassifiers($entries, [], 'custom_collection');

        $expect = $entries->pluck('alias', 'id')->toArray();

        $this->assertEquals($expect, $model->getClassifiersList('custom_collection'));
    }

    /** @test */
    function get_non_existent_collection_classifiers_list(): void
    {
        $model = TestModel::factory()->create();
        $entries = EntryModel::factory(5)->create();

        $model->attachClassifiers($entries);

        $this->assertEquals([], $model->getClassifiersList('custom_collection2'));
    }
}
