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

    public function users()
    {
        return $this->hasMany(User::class, 'store_setting_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'store_setting_id');
    }

    public function inventoryStocks()
    {
        return $this->hasMany(InventoryStock::class, 'store_setting_id');
    }
}
