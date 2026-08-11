<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_id',
        'note_product',
        'bundle_id',
        'qty',
        'selling_price',
        'discount',
        'subtotal',
        'is_pre_order',
        'is_multi_store',
        'source_stores'
    ];

    protected $casts = [
        'qty'            => 'integer',
        'selling_price'  => 'decimal:2',
        'discount'       => 'decimal:2',
        'subtotal'       => 'decimal:2',
        'is_pre_order'   => 'boolean',
        'is_multi_store' => 'boolean',
        'source_stores'  => 'array'
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

    public function bundle()
    {
        return $this->belongsTo(Bundle::class);
    }
}
