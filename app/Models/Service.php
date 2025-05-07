<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'service';
    protected $primaryKey = 'service_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'service_id',
        'categories_id',
        'name',
        'description',
        'price',
        'thumbnail',
        'slug',
    ];

    protected $casts = [
        'price' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke kategori layanan.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id', 'categories_id');
    }

    /**
     * Accessor untuk URL lengkap thumbnail.
     */
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset($this->thumbnail) : null;
    }
}
