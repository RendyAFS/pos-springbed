<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Bundle extends Model
{
    use SoftDeletes, Userstamps;

    protected $fillable = [
        'name',
        'bundle_price',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function bundleItems()
    {
        return $this->hasMany(BundleItem::class);
    }
}
