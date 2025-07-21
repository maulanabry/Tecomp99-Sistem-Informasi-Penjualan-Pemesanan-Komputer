<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $order_service_media_id
 * @property string $order_service_id
 * @property string $media_path
 * @property string $media_name
 * @property string $file_type
 * @property int $file_size
 * @property bool $is_main
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $formatted_size
 * @property-read mixed $url
 * @property-read \App\Models\OrderService $orderService
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceMedia whereFileSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceMedia whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceMedia whereIsMain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceMedia whereMediaName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceMedia whereMediaPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceMedia whereOrderServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceMedia whereOrderServiceMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceMedia whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderServiceMedia extends Model
{
    protected $table = 'order_service_media';

    protected $primaryKey = 'order_service_media_id';

    protected $fillable = [
        'order_service_id',
        'media_path',
        'media_name',
        'file_type',
        'file_size',
        'is_main'
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'file_size' => 'integer'
    ];

    /**
     * Get the order service that owns this media
     */
    public function orderService()
    {
        return $this->belongsTo(OrderService::class, 'order_service_id', 'order_service_id');
    }

    /**
     * Get the full URL for the media file from private storage
     */
    public function getUrlAttribute()
    {
        // For private storage, we need to create a route to serve the file
        return route('order-service.media', ['media' => $this->order_service_media_id]);
    }

    /**
     * Get the file content from private storage
     */
    public function getFileContent()
    {
        return Storage::disk('private')->get($this->media_path);
    }

    /**
     * Check if the media is an image
     */
    public function isImage()
    {
        return in_array(strtolower($this->file_type), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    /**
     * Check if the media is a video
     */
    public function isVideo()
    {
        return in_array(strtolower($this->file_type), ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm']);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
