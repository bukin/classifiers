<?php

namespace InetStudio\ClassifiersPackage\Groups\Domain\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\AdminPanel\Base\Models\Traits\HasUuidPrimaryKey;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryGroupModel;
use InetStudio\ClassifiersPackage\Entries\Domain\Entity\EntryModelContract;
use OwenIt\Auditing\Auditable;

class GroupModel extends Model implements GroupModelContract
{
    use Auditable;
    use HasFactory;
    use HasUuidPrimaryKey;
    use SoftDeletes;

    protected bool $auditTimestamps = true;

    protected $table = 'classifiers_package_groups';

    protected $fillable = [
        'name',
        'alias',
        'description',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getTypeAttribute(): string
    {
        return self::ENTITY_TYPE;
    }

    public function entries(): BelongsToMany
    {
        $entryModel = resolve(EntryModelContract::class);

        return $this->belongsToMany(
                get_class($entryModel),
                'classifiers_package_groups_entries',
                'group_id',
                'entry_id'
            )
            ->withPivot(['id'])
            ->using(EntryGroupModel::class)
            ->withTimestamps();
    }

    protected static function newFactory(): GroupFactory
    {
        return GroupFactory::new();
    }
}
