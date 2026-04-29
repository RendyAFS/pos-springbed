<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referal extends Model
{
    protected $fillable = [
        'customer_id',
        'name_customer',
        'referal_code',
        'discount_amount',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
