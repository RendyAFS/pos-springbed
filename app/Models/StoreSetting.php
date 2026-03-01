<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mattiverse\Userstamps\Traits\Userstamps;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class StoreSetting extends Model implements HasMedia
{
    use SoftDeletes, Userstamps, InteractsWithMedia;

    protected $fillable = [
        'store_name',
        'phone',
        'email',
        'address',
    ];
}
