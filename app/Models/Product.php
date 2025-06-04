<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'product_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'categories_id',
        'brand_id',
        'name',
        'description',
        'price',
        'stock',
        'weight',
        'is_active',
        'sold_count',
        'slug',
    ];

    protected $attributes = [
        'weight' => 0,
    ];

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'weight' => 'integer',
        'sold_count' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relasi ke kategori
    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id', 'categories_id');
    }

    // Relasi ke brand
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'brand_id');
    }

    // Relasi ke gambar produk
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }

    // Akses URL thumbnail utama
    public function getThumbnailUrlAttribute()
    {
        $mainImage = $this->images()->where('is_main', true)->first()
            ?? $this->images()->first();
        return $mainImage ? asset($mainImage->url) : null;
    }
}
