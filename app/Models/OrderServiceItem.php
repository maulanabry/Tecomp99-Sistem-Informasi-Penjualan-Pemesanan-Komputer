<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
