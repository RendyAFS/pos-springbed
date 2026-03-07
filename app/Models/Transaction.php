<?php

namespace App\Models;

use App\Enums\TransactionStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Transaction extends Model
{
    use SoftDeletes, Userstamps;

    protected $fillable = [
        'customer_id',
        'transaction_code',
        'subtotal',
        'discount_total',
        'shiping_cost',
        'grand_total',
        'status',
        'promo_id',
        'transaction_date',
    ];

    protected $casts = [
        'status'           => TransactionStatusEnum::class,
        'subtotal'         => 'decimal:2',
        'discount_total'   => 'decimal:2',
        'shiping_cost'     => 'decimal:2',
        'grand_total'      => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    // public function transactionItems()
    // {
    //     return $this->hasMany(TransactionItem::class);
    // }

    public function promoUsages()
    {
        return $this->hasMany(PromoUsage::class);
    }
}
