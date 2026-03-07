<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class PromoProduct extends Model
{
    protected $fillable = [
        'promo_id',
        'product_id',
    ];

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
