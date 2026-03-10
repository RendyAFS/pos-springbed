<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $fillable = [
        'product_id',
        'qty_before',
        'qty_after',
        'qty_difference',
        'reason',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
