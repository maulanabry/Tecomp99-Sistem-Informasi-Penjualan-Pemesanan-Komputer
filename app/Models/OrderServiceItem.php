<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $order_service_item_id
 * @property string $order_service_id
 * @property string $item_type
 * @property string $item_id
 * @property int $price
 * @property int $quantity
 * @property int $item_total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $item
 * @property-read \App\Models\OrderService $orderService
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceItem whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceItem whereItemTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceItem whereItemType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceItem whereOrderServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceItem whereOrderServiceItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderServiceItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderServiceItem extends Model
{
    public $timestamps = true;

    protected $primaryKey = 'order_service_item_id';

    protected $fillable = [
        'order_service_id',
        'item_type',
        'item_id',
        'price',
        'quantity',
        'item_total',
        'created_at',
        'updated_at',
    ];

    public function orderService()
    {
        return $this->belongsTo(OrderService::class, 'order_service_id', 'order_service_id');
    }

    /**
     * Polymorphic relationship to Product or Service
     */
    public function item()
    {
        return $this->morphTo();
    }
}
