<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderService extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'order_service_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'order_service_id',
        'customer_id',
        'status_order',
        'status_payment',
        'complaints',
        'type',
        'device',
        'note',
        'sub_total',
        'grand_total_amount',
        'discount_amount',
    ];

    public function items()
    {
        return $this->hasMany(OrderServiceItem::class, 'order_service_id', 'order_service_id');
    }

    public function images()
    {
        return $this->hasMany(OrderServiceImage::class, 'order_service_id', 'order_service_id');
    }

    public function tickets()
    {
        return $this->hasMany(ServiceTicket::class, 'order_service_id', 'order_service_id');
    }
}
