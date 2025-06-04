<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class, 'order_product_id', 'order_product_id');
    }
}
