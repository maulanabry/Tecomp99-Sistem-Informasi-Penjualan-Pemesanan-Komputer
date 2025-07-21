<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $service_id
 * @property int $categories_id
 * @property string $name
 * @property string $description
 * @property int $price
 * @property string $thumbnail
 * @property string $slug
 * @property bool $is_active
 * @property int $sold_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Category $category
 * @property-read mixed $thumbnail_url
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCategoriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereSoldCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereThumbnail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Service withoutTrashed()
 * @mixin \Eloquent
 */
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
        'sold_count',
        'is_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'sold_count' => 'integer',
        'is_active' => 'boolean',
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
