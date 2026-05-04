<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Product extends Model
{
    use SoftDeletes, Userstamps;

    protected $fillable = [
        'store_setting_id',
        'brand_id',
        'category_id',
        'name',
        'type_id',
        'selling_price',
        'sku',
        'size_id',
        'weight',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'selling_price' => 'decimal:2',
        'weight'        => 'decimal:2',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function size()
    {
        return $this->belongsTo(ProductSize::class, 'size_id');
    }

    public function type()
    {
        return $this->belongsTo(ProductType::class, 'type_id');
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

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'product_id');
    }

    public function inventoryStocks()
    {
        return $this->hasMany(InventoryStock::class);
    }

    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }

    public function storeSetting()
    {
        return $this->belongsTo(StoreSetting::class, 'store_setting_id');
    }
}
