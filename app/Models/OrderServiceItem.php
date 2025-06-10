<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderServiceItem extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'order_service_item_id';

    protected $fillable = [
        'order_service_item_id',
        'order_service_id',
        'service_id',
        'product_id',
        'description',
        'price',
        'quantity',
        'item_total',
        'created_at',
    ];

    public function orderService()
    {
        return $this->belongsTo(OrderService::class, 'order_service_id', 'order_service_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
