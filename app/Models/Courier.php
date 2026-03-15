<?php

namespace App\Models;

use App\Enums\TypeCourierEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Courier extends Model
{
    use SoftDeletes, Userstamps;

    protected $fillable = [
        'name',
        'type',
        'shipping_cost',
        'is_active',
    ];

    protected $casts = [
        'type'          => TypeCourierEnum::class,
        'shipping_cost' => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    public function transactionShipments()
    {
        return $this->hasMany(TransactionShipment::class, 'courier_id');
    }
}
