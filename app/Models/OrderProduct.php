<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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
        'warranty_period_months',
        'warranty_expired_at',
        'paid_amount',
        'remaining_balance',
        'last_payment_at',
    ];

    protected $casts = [
        'warranty_expired_at' => 'datetime',
        'last_payment_at' => 'datetime',
        'paid_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($order) {
            // Auto-calculate warranty expiration when warranty_period_months is set
            if ($order->warranty_period_months && $order->isDirty('warranty_period_months')) {
                $order->warranty_expired_at = Carbon::now()->addMonths($order->warranty_period_months);
            }

            // Initialize payment tracking fields for new orders
            if (!$order->exists) {
                $order->paid_amount = $order->paid_amount ?? 0;
                $order->remaining_balance = $order->grand_total - ($order->paid_amount ?? 0);
            }
        });
    }

    /**
     * Calculate and update warranty_expired_at based on completion date and warranty_period_months
     */
    public function updateWarrantyExpiration(\DateTimeInterface $completionDate)
    {
        if ($this->warranty_period_months) {
            $this->warranty_expired_at = Carbon::parse($completionDate)->addMonths($this->warranty_period_months);
            $this->save();
        }
    }

    /**
     * Update payment status and amounts based on related payments
     */
    public function updatePaymentStatus()
    {
        $paidAmount = $this->payments()->where('status', 'dibayar')->sum('amount');
        $this->paid_amount = $paidAmount;
        $this->remaining_balance = max(0, $this->grand_total - $paidAmount);
        $this->last_payment_at = $this->payments()->where('status', 'dibayar')->latest('created_at')->value('created_at');

        if ($paidAmount >= $this->grand_total) {
            $this->status_payment = 'lunas';
        } elseif ($paidAmount > 0) {
            $this->status_payment = 'down_payment';
        } else {
            $this->status_payment = 'belum_dibayar';
        }
        $this->save();
    }

    /**
     * Check if payment can be made for this order
     */
    public function canAcceptPayment()
    {
        return !in_array($this->status_payment, ['lunas', 'dibatalkan']);
    }

    /**
     * Validate payment amount and type
     */
    public function validatePayment($amount, $paymentType)
    {
        $errors = [];

        if (!$this->canAcceptPayment()) {
            $errors[] = 'Pembayaran tidak dapat dilakukan untuk pesanan yang sudah lunas atau dibatalkan.';
            return $errors;
        }

        if ($paymentType === 'full' && $amount < $this->remaining_balance) {
            $errors[] = 'Total pembayaran tidak mencukupi untuk pelunasan penuh. Minimum: Rp ' . number_format($this->remaining_balance, 0, ',', '.');
        }

        if ($amount <= 0) {
            $errors[] = 'Jumlah pembayaran harus lebih dari 0.';
        }

        return $errors;
    }

    /**
     * Process payment and update order status
     */
    public function processPayment($amount, $paymentType)
    {
        $this->paid_amount += $amount;
        $this->remaining_balance = max(0, $this->grand_total - $this->paid_amount);
        $this->last_payment_at = now();

        // Calculate change if overpayment
        $changeReturned = 0;
        if ($this->paid_amount > $this->grand_total) {
            $changeReturned = $this->paid_amount - $this->grand_total;
            $this->paid_amount = $this->grand_total;
            $this->remaining_balance = 0;
        }

        // Update payment status
        if ($this->remaining_balance <= 0) {
            $this->status_payment = 'lunas';
        } else {
            $this->status_payment = 'down_payment';
        }

        $this->save();

        return $changeReturned;
    }

    /**
     * Get warranty status information
     */
    public function getWarrantyStatusAttribute()
    {
        if (!$this->warranty_expired_at) {
            return ['status' => 'no_warranty', 'message' => 'Tidak ada garansi'];
        }

        $now = Carbon::now();
        $expiry = Carbon::parse($this->warranty_expired_at);

        if ($expiry->isPast()) {
            return ['status' => 'expired', 'message' => 'Garansi sudah habis'];
        }

        $daysLeft = $now->diffInDays($expiry);
        if ($daysLeft <= 30) {
            return ['status' => 'expiring_soon', 'message' => "{$daysLeft} hari lagi habis garansi"];
        }

        return ['status' => 'active', 'message' => "Garansi berlaku sampai {$expiry->format('d/m/Y')}"];
    }

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['payments'];

    public function shipping()
    {
        return $this->hasOne(Shipping::class, 'order_product_id', 'order_product_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class, 'order_product_id', 'order_product_id');
    }

    public function items()
    {
        return $this->hasMany(OrderProductItem::class, 'order_product_id', 'order_product_id');
    }

    /**
     * Get the payments for the order product.
     */
    public function payments()
    {
        return $this->hasMany(PaymentDetail::class, 'order_product_id', 'order_product_id');
    }

    /**
     * Get the promo applied to this order (if any).
     * Note: This is a conceptual relationship since promo_id is not stored directly.
     * In practice, promo information is stored as discount_amount.
     */
    public function getAppliedPromoAttribute()
    {
        // This could be enhanced to store promo_id in the future
        // For now, we can only determine if a discount was applied
        return $this->discount_amount > 0;
    }
}
