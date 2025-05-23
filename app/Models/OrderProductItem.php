<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
