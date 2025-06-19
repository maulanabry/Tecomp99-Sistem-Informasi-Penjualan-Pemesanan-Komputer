<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'payment_id';
    protected $keyType = 'string';
    public $incrementing = false;



    protected $fillable = [
        'payment_id',
        'order_product_id',
        'order_service_id',
        'method',
        'amount',
        'name',
        'status',
        'payment_type',
        'order_type',
        'proof_photo',
    ];

    protected $casts = [
        'amount' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the order product associated with the payment.
     */
    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class, 'order_product_id', 'order_product_id');
    }

    /**
     * Get the order service associated with the payment.
     */
    public function orderService()
    {
        return $this->belongsTo(OrderService::class, 'order_service_id', 'order_service_id');
    }

    /**
     * Get the customer associated with the payment through order product or service.
     */
    public function customer()
    {
        return $this->order_type === 'produk'
            ? $this->orderProduct->customer()
            : $this->orderService->customer();
    }

    /**
     * Resolve a route binding.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('payment_id', $value)->firstOrFail();
    }
}
