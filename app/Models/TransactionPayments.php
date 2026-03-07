<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionPayments extends Model
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
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
