<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class PurchaseOrder extends Model
{
    use SoftDeletes, Userstamps;

    protected $fillable = [
        'store_setting_id',
        'store_sub_id',
        'supplier_name',
        'invoice_number',
        'purchase_date',
        'delivery_order_number',
        'taxpayer_name',
        'total_amount'
    ];

    protected $casts = [
        'purchase_date' => 'datetime'
    ];

    protected static function booted(): void
    {
        static::deleting(function (PurchaseOrder $purchaseOrder) {
            if ($purchaseOrder->trashed()) {
                return;
            }

            app(\App\Services\PurchaseOrderService::class)->revertStock($purchaseOrder);
        });

        static::restoring(function (PurchaseOrder $purchaseOrder) {
            app(\App\Services\PurchaseOrderService::class)->receiveStock($purchaseOrder);
        });
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }

    public function storeSetting()
    {
        return $this->belongsTo(StoreSetting::class, 'store_setting_id');
    }

    public function storeSub()
    {
        return $this->belongsTo(StoreSub::class, 'store_sub_id');
    }
}
