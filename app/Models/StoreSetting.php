<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreSetting extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_name',
        'phone',
        'email',
        'homepage_banner'
    ];
}
