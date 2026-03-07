<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_id',
        'qty',
        'selling_price',
        'discount',
        'subtotal',
    ];

    protected $casts = [
        'qty'           => 'integer',
        'selling_price' => 'decimal:2',
        'discount'      => 'decimal:2',
        'subtotal'      => 'decimal:2',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function transactionItemsCosts()
    {
        return $this->hasMany(TransactionItemCost::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
