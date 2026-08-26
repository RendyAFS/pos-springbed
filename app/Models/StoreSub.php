<?php

namespace App\Models;

use App\Enums\StoreSubTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class StoreSub extends Model
{
    use SoftDeletes, Userstamps;

    protected $table = 'store_subs';

    protected $fillable = [
        'store_id',
        'name',
        'type',
        'code',
        'parent_id',
        'created_by',
    ];

    protected $casts = [
        'type' => StoreSubTypeEnum::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (StoreSub $model) {
            if (empty($model->code)) {
                $model->code = static::generateCode($model->store_id, $model->type);
            }
        });
    }

    public static function generateCode(?int $storeId = null, string|StoreSubTypeEnum|null $type = null): string
    {
        $typeStr = $type instanceof StoreSubTypeEnum
            ? $type->value
            : ($type ?? 'Floor');

        $prefix = strtoupper(substr($typeStr, 0, 3)); // FLO or RAC
        $storeId = $storeId ?? 1;

        $count = static::withTrashed()
            ->where('store_id', $storeId)
            ->where('type', $typeStr)
            ->count() + 1;

        do {
            $code = sprintf('%s-%02d-%03d', $prefix, $storeId, $count);
            $count++;
        } while (static::withTrashed()->where('code', $code)->exists());

        return $code;
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(StoreSetting::class, 'store_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(StoreSub::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(StoreSub::class, 'parent_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
