<?php

namespace App\Models;

use App\Enums\StatusTransactionShipmentEnum;
use Illuminate\Database\Eloquent\Model;

class TransactionShipment extends Model
{
    protected $fillable = [
        'transaction_id',
        'courier_id',
        'tracking_number',
        'status',
    ];

    protected $casts = [
        'status' => StatusTransactionShipmentEnum::class,
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
