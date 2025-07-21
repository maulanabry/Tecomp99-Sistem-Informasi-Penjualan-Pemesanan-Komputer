<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $shipping_id
 * @property string $order_product_id
 * @property string $courier_name
 * @property string $courier_service
 * @property string|null $tracking_number
 * @property string $status
 * @property int $shipping_cost
 * @property int $total_weight
 * @property \Illuminate\Support\Carbon|null $shipped_at
 * @property \Illuminate\Support\Carbon|null $delivered_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\OrderProduct $orderProduct
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereCourierName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereCourierService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereDeliveredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereOrderProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereShippedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereShippingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereTotalWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereTrackingNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Shipping withoutTrashed()
 * @mixin \Eloquent
 */
class Shipping extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'shipping';

    protected $primaryKey = 'shipping_id';

    protected $fillable = [
        'order_product_id',
        'courier_name',
        'courier_service',
        'tracking_number',
        'status',
        'shipping_cost',
        'total_weight',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class, 'order_product_id', 'order_product_id');
    }
}
