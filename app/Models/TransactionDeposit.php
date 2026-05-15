<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDeposit extends Model
{
    protected $fillable = [
        'transaction_id',
        'amount',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
