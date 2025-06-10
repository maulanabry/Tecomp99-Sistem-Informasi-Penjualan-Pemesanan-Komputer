<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'order_product_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'order_product_id',
        'customer_id',
        'status_order',
        'status_payment',
        'sub_total',
        'discount_amount',
        'shipping_cost',
        'grand_total',
        'type',
        'note',
    ];

    public function promo()
    {
        return $this->belongsTo(Promo::class, 'promo_id', 'promo_id');
    }

    public function shipping()
    {
        return $this->hasOne(Shipping::class, 'order_product_id', 'order_product_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderProductItem::class, 'order_product_id', 'order_product_id');
    }
}
