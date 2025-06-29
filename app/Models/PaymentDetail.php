<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PaymentDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'payment_id';
    protected $keyType = 'string';
    public $incrementing = false;

    const PAYMENT_METHODS = [
        'Tunai' => 'Tunai',
        'Bank BCA' => 'Bank BCA'
    ];

    const PAYMENT_TYPES = [
        'full' => 'Full Payment',
        'down_payment' => 'Down Payment'
    ];

    const PAYMENT_STATUSES = [
        'pending' => 'Pending',
        'dibayar' => 'Dibayar',
        'gagal' => 'Gagal'
    ];

    protected $fillable = [
        'payment_id',
        'order_product_id',
        'order_service_id',
        'method',
        'amount',
        'cash_received',
        'change_returned',
        'name',
        'status',
        'payment_type',
        'order_type',
        'proof_photo',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'cash_received' => 'decimal:2',
        'change_returned' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($payment) {
            // For cash payments, calculate change based on cash_received
            if ($payment->method === 'Tunai') {
                if ($payment->cash_received) {
                    // Set amount to the actual payment (what goes to revenue)
                    $order = $payment->order_type === 'produk' ? $payment->orderProduct : $payment->orderService;
                    if ($order) {
                        // Amount is either the remaining balance or the cash received, whichever is smaller
                        $payment->amount = min($payment->cash_received, $order->remaining_balance);
                        // Change is cash_received minus actual amount paid
                        $payment->change_returned = $payment->cash_received - $payment->amount;
                    }
                } else {
                    // If cash_received is not set, use amount as cash_received (backward compatibility)
                    $payment->cash_received = $payment->amount;
                    $order = $payment->order_type === 'produk' ? $payment->orderProduct : $payment->orderService;
                    if ($order && $payment->amount > $order->remaining_balance) {
                        // Adjust amount to actual payment and calculate change
                        $payment->change_returned = $payment->amount - $order->remaining_balance;
                        $payment->amount = $order->remaining_balance;
                    } else {
                        $payment->change_returned = 0;
                    }
                }
            } else {
                // For non-cash payments, amount is the actual payment and no change
                $payment->cash_received = null;
                $payment->change_returned = null;
            }
        });

        static::saved(function ($payment) {
            // Update order payment status after payment is saved
            if ($payment->status === 'dibayar') {
                if ($payment->order_type === 'produk' && $payment->orderProduct) {
                    $payment->orderProduct->processPayment($payment->amount, $payment->payment_type);
                } elseif ($payment->order_type === 'servis' && $payment->orderService) {
                    $payment->orderService->processPayment($payment->amount, $payment->payment_type);
                }
            }
        });
    }

    /**
     * Validate payment before saving
     */
    public function validate()
    {
        $errors = [];

        // Validate payment method
        if (!array_key_exists($this->method, self::PAYMENT_METHODS)) {
            $errors[] = 'Metode pembayaran tidak valid.';
        }

        // Validate payment type
        if (!array_key_exists($this->payment_type, self::PAYMENT_TYPES)) {
            $errors[] = 'Tipe pembayaran tidak valid.';
        }

        // Validate amount
        if ($this->amount <= 0) {
            $errors[] = 'Jumlah pembayaran harus lebih dari 0.';
        }

        // Get associated order
        $order = $this->order_type === 'produk' ? $this->orderProduct : $this->orderService;

        if ($order) {
            // Validate order status
            if (!$order->canAcceptPayment()) {
                $errors[] = 'Order tidak dapat menerima pembayaran (sudah lunas atau dibatalkan).';
            }

            // Validate full payment amount
            if ($this->payment_type === 'full' && $this->amount < $order->remaining_balance) {
                $errors[] = 'Jumlah pembayaran tidak mencukupi untuk pelunasan penuh.';
            }
        } else {
            $errors[] = 'Order tidak ditemukan.';
        }

        return $errors;
    }

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
     * Get the associated order (product or service)
     */
    public function getOrderAttribute()
    {
        return $this->order_type === 'produk' ? $this->orderProduct : $this->orderService;
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get formatted cash received
     */
    public function getFormattedCashReceivedAttribute()
    {
        return $this->cash_received ? 'Rp ' . number_format($this->cash_received, 0, ',', '.') : '-';
    }

    /**
     * Get formatted change returned
     */
    public function getFormattedChangeReturnedAttribute()
    {
        return $this->change_returned ? 'Rp ' . number_format($this->change_returned, 0, ',', '.') : '-';
    }

    /**
     * Get the full URL for the proof photo
     */
    public function getProofPhotoUrlAttribute()
    {
        if ($this->proof_photo) {
            // Check if it's the new format (just filename)
            if (!str_contains($this->proof_photo, '/')) {
                return asset('images/payment/' . $this->proof_photo);
            }
            // Legacy format (full path)
            return asset('storage/' . $this->proof_photo);
        }
        return null;
    }

    /**
     * Get payment type label
     */
    public function getPaymentTypeLabelAttribute()
    {
        return self::PAYMENT_TYPES[$this->payment_type] ?? $this->payment_type;
    }

    /**
     * Get payment status label
     */
    public function getStatusLabelAttribute()
    {
        return self::PAYMENT_STATUSES[$this->status] ?? $this->status;
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
