<?php

namespace App\Models;

use App\Enums\TransactionPaymentMethodEnum;
use App\Enums\TransactionPaymentStatusEnum;
use Illuminate\Database\Eloquent\Model;

class TransactionPayment extends Model
{
    protected $fillable = [
        'transaction_id',
        'method',
        'amount',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount'   => 'decimal:2',
        'paid_at'  => 'datetime',
        'method'   => TransactionPaymentMethodEnum::class,
        'status'   => TransactionPaymentStatusEnum::class,
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
