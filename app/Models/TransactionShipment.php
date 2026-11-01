<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionShipment extends Model
{
    protected $fillable = [
        'transaction_id',
        'courier_id',
        'shipping_date',
        'tracking_number',
        'status',
    ];

    protected $casts = [
        'shipping_date' => 'date',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }
}
