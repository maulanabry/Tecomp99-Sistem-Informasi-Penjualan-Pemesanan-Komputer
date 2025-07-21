<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $order_product_item_id
 * @property string $order_product_id
 * @property string $product_id
 * @property int $quantity
 * @property int $price
 * @property int $item_total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\OrderProduct $orderProduct
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductItem whereItemTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductItem whereOrderProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductItem whereOrderProductItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductItem whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProductItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderProductItem extends Model
{
    protected $primaryKey = 'order_product_item_id';

    protected $fillable = [
        'order_product_id',
        'product_id',
        'quantity',
        'price',
        'item_total',
    ];

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class, 'order_product_id', 'order_product_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
