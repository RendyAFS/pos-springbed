<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;

class Customer extends Model
{
    use SoftDeletes, Userstamps;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'city_code',
        'city_name',
        'district_code',
        'district_name',
    ];

    public function referal()
    {
        return $this->hasOne(Referal::class);
    }
}
