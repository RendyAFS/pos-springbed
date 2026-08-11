<?php

namespace App\Models;

use App\Enums\PaymentMethodDpEnum;
use Illuminate\Database\Eloquent\Model;

class TransactionDownPayment extends Model
{
    protected $fillable = [
        'transaction_id',
        'amount',
        'method_payment',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount'         => 'decimal:2',
        'method_payment' => PaymentMethodDpEnum::class,
        'paid_at'        => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
