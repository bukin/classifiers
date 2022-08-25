<?php

namespace InetStudio\ClassifiersPackage\Entries\Domain\Entity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\AdminPanel\Base\Models\Traits\HasUuidPrimaryKey;
use InetStudio\ClassifiersPackage\Groups\Domain\Entity\GroupModelContract;
use OwenIt\Auditing\Auditable;

class EntryModel extends Model implements EntryModelContract
{
    use Auditable;
    use HasFactory;
    use HasUuidPrimaryKey;
    use SoftDeletes;

    protected bool $auditTimestamps = true;

    protected $table = 'classifiers_package_entries';

    protected $fillable = [
        'value',
        'alias',
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

    public function groups(): BelongsToMany
    {
        $groupModel = resolve(GroupModelContract::class);

        return $this->belongsToMany(
                get_class($groupModel),
                'classifiers_package_groups_entries',
                'entry_id',
                'group_id'
            )
            ->withPivot(['id'])
            ->using(EntryGroupModel::class)
            ->withTimestamps();
    }

    protected static function newFactory(): EntryFactory
    {
        return EntryFactory::new();
    }
}
