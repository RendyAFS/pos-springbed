<?php

namespace App\Models;

use App\Enums\ReferenceTypeStockMovementEnum;
use App\Enums\TypeStockMovementEnum;
use Illuminate\Database\Eloquent\Model;
use League\CommonMark\Reference\Reference;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'store_setting_id',
        'type',
        'reference_type',
        'reference_id',
        'qty',
        'cost_price_snapshot',
    ];

    protected $cast = [
        'type'           => TypeStockMovementEnum::class,
    ];

    public function reference()
    {
        return $this->morphTo();
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function storeSetting()
    {
        return $this->belongsTo(StoreSetting::class, 'store_setting_id');
    }
}
