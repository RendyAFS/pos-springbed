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
        'supplier_name',
        'invoice_number',
        'purchase_date',
        'total_amount'
    ];

    protected $casts = [
        'purchase_date' => 'datetime'
    ];

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }

    public function storeSetting()
    {
        return $this->belongsTo(StoreSetting::class, 'store_setting_id');
    }
}
