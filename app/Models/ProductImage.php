<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProductImage extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $timestamps = false;

    protected $fillable = [
        'product_id',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
