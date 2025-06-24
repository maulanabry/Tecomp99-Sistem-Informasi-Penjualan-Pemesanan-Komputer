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
        'hasTicket',
        'sub_total',
        'hasDevice',
        'grand_total',
        'discount_amount',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

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

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class, 'order_service_id', 'order_service_id');
    }
}
