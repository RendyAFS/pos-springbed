<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryStock extends Model
{
    protected $fillable = [
        'product_id',
        'store_setting_id',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function storeSetting()
    {
        return $this->belongsTo(Product::class, 'store_setting_id');
    }
}
