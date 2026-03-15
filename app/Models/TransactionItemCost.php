<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItemCost extends Model
{
    protected $fillable = [
        'transaction_item_id',
        'purchase_order_item_id',
        'qty_taken',
        'cost_price',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2'
    ];

    public function transactionItem()
    {
        return $this->belongsTo(TransactionItem::class, 'transaction_item_id');
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class, 'purchase_order_item_id');
    }
}
