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
        'store_setting_id',
        'customer_id',
        'transaction_code',
        'subtotal',
        'discount_total',
        'shiping_cost',
        'grand_total',
        'status',
        'promo_id',
        'transaction_date',
        'is_referal',
        'referal_customer_id',
        'nominal_referal',
    ];

    protected $casts = [
        'status'           => TransactionStatusEnum::class,
        'subtotal'         => 'decimal:2',
        'discount_total'   => 'decimal:2',
        'shiping_cost'     => 'decimal:2',
        'grand_total'      => 'decimal:2',
        'transaction_date' => 'date',
        'is_referal'       => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function transactionPayment()
    {
        return $this->hasOne(TransactionPayment::class);
    }

    public function promoUsages()
    {
        return $this->hasMany(PromoUsage::class);
    }

    public function storeSetting()
    {
        return $this->belongsTo(StoreSetting::class, 'store_setting_id');
    }

    public function transactionShipment()
    {
        return $this->hasOne(TransactionShipment::class, 'transaction_id');
    }

    public function referalCustomer()
    {
        return $this->belongsTo(Customer::class, 'referal_customer_id');
    }
}
