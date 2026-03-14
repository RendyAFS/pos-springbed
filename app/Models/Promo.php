<?php

namespace App\Models;

use App\Enums\PromoDiscountEnum;
use App\Enums\PromoTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Promo extends Model
{
    use SoftDeletes, Userstamps;

    protected $fillable = [
        'name',
        'type',
        'discount_type',
        'discount_value',
        'min_purchase',
        'usage_limit',
        'usage_count',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'type'           => PromoTypeEnum::class,
        'discount_type'  => PromoDiscountEnum::class,
        'discount_value' => 'decimal:2',
        'min_purchase'   => 'decimal:2',
        'usage_limit'    => 'integer',
        'usage_count'    => 'integer',
        'start_date'     => 'datetime',
        'end_date'       => 'datetime',
        'is_active'      => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promo_products');
    }

    public function promoProducts()
    {
        return $this->hasMany(PromoProduct::class);
    }
}
