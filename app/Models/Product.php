<?php

namespace App\Models;

use App\Enums\SizeProductEnum;
use App\Enums\TypeProductEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Product extends Model
{
    use SoftDeletes, Userstamps;

    protected $fillable = [
        'name',
        'type',
        'selling_price',
        'sku',
        'size',
        'weight',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'selling_price' => 'decimal:2',
        'weight'        => 'decimal:2',
        'type'          => TypeProductEnum::class,
        'size'          => SizeProductEnum::class,
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function bundleItems()
    {
        return $this->hasMany(BundleItem::class);
    }

    public function promos()
    {
        return $this->belongsToMany(Promo::class, 'promo_products');
    }
}
