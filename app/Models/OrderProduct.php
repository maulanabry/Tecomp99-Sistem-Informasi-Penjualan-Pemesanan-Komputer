<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

/**
 * @property string $order_product_id
 * @property string $customer_id
 * @property string $status_order
 * @property string $status_payment
 * @property numeric $sub_total
 * @property numeric|null $discount_amount
 * @property numeric $grand_total
 * @property numeric|null $shipping_cost
 * @property string $type
 * @property string|null $note
 * @property int|null $warranty_period_months
 * @property \Illuminate\Support\Carbon|null $warranty_expired_at
 * @property numeric $paid_amount
 * @property numeric $remaining_balance
 * @property \Illuminate\Support\Carbon|null $last_payment_at
 * @property \Illuminate\Support\Carbon|null $expired_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Customer $customer
 * @property-read mixed $applied_promo
 * @property-read mixed $warranty_status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderProductItem> $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentDetail> $paymentDetails
 * @property-read int|null $payment_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentDetail> $payments
 * @property-read int|null $payments_count
 * @property-read \App\Models\Shipping|null $shipping
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereExpiredDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereLastPaymentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereOrderProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereRemainingBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereShippingCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereStatusOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereStatusPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereWarrantyExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderProduct whereWarrantyPeriodMonths($value)
 */
class OrderProduct extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'order_product_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Status constants
    const STATUS_ORDER_MENUNGGU = 'menunggu';
    const STATUS_ORDER_INDEN = 'inden';
    const STATUS_ORDER_SIAP_KIRIM = 'siap_kirim';
    const STATUS_ORDER_DIPROSES = 'diproses';
    const STATUS_ORDER_DIKIRIM = 'dikirim';
    const STATUS_ORDER_SELESAI = 'selesai';
    const STATUS_ORDER_DIBATALKAN = 'dibatalkan';
    const STATUS_ORDER_MELEWATI_JATUH_TEMPO = 'melewati_jatuh_tempo';

    const STATUS_PAYMENT_BELUM_DIBAYAR = 'belum_dibayar';
    const STATUS_PAYMENT_DOWN_PAYMENT = 'down_payment';
    const STATUS_PAYMENT_LUNAS = 'lunas';
    const STATUS_PAYMENT_DIBATALKAN = 'dibatalkan';

    protected $fillable = [
        'order_product_id',
        'customer_id',
        'status_order',
        'status_payment',
        'sub_total',
        'discount_amount',
        'grand_total',
        'shipping_cost',
        'type',
        'note',
        'warranty_period_months',
        'warranty_expired_at',
        'paid_amount',
        'remaining_balance',
        'last_payment_at',
        'expired_date',
    ];

    protected $casts = [
        'warranty_expired_at' => 'datetime',
        'last_payment_at' => 'datetime',
        'expired_date' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->updateExpiredDate();
        });

        static::updating(function ($model) {
            if ($model->isDirty('status_order') || $model->isDirty('status_payment')) {
                $model->updateExpiredDate();
            }
        });
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

    /**
     * Update expired_date based on current status_order and status_payment
     */
    public function updateExpiredDate()
    {
        $now = Carbon::now();
        $orderDate = $this->created_at ?? $now;

        // 1) Jika status_payment = 'lunas' => selalu clear expired_date
        if ($this->status_payment === 'lunas') {
            $this->expired_date = null;
            \Log::info("OrderProduct {$this->order_product_id}: expired_date cleared because status_payment is lunas");
            return;
        }

        // 2) Validasi kompatibilitas antara status_order dan status_payment
        if ($this->isDirty('status_order') || $this->isDirty('status_payment')) {
            switch ($this->status_order) {
                case 'inden':
                    if ($this->status_payment !== 'down_payment') {
                        session()->flash('error', 'Status "inden" mengharuskan status pembayaran = down_payment.');
                        throw ValidationException::withMessages([
                            'status_order' => ['Status "inden" mengharuskan status pembayaran = down_payment.'],
                        ]);
                    }
                    break;

                case 'siap_kirim':
                    if (! in_array($this->status_payment, ['down_payment', 'lunas'])) {
                        session()->flash('error', 'Status "siap_kirim" mengharuskan status pembayaran = down_payment atau lunas.');
                        throw ValidationException::withMessages([
                            'status_order' => ['Status "siap_kirim" mengharuskan status pembayaran = down_payment atau lunas.'],
                        ]);
                    }
                    break;

                case 'diproses':
                    if ($this->status_payment !== 'lunas') {
                        session()->flash('error', 'Status "diproses" mengharuskan pembayaran sudah lunas.');
                        throw ValidationException::withMessages([
                            'status_order' => ['Status "diproses" mengharuskan pembayaran sudah lunas.'],
                        ]);
                    }
                    break;

                case 'dikirim':
                    if ($this->status_payment !== 'lunas') {
                        session()->flash('error', 'Status "dikirim" mengharuskan pembayaran sudah lunas.');
                        throw ValidationException::withMessages([
                            'status_order' => ['Status "dikirim" mengharuskan pembayaran sudah lunas.'],
                        ]);
                    }
                    break;

                case 'selesai':
                    if ($this->status_payment !== 'lunas') {
                        session()->flash('error', 'Status "selesai" mengharuskan pembayaran sudah lunas.');
                        throw ValidationException::withMessages([
                            'status_order' => ['Status "selesai" mengharuskan pembayaran sudah lunas.'],
                        ]);
                    }
                    break;
            }
        }

        // 3) Aturan expired_date (hanya jika payment bukan lunas)
        if ($this->status_order === 'menunggu' && $this->status_payment === 'belum_dibayar') {
            $this->expired_date = Carbon::parse($orderDate)->addDay();
            \Log::info("OrderProduct {$this->order_product_id}: expired_date set to {$this->expired_date} for status_order menunggu and status_payment belum_dibayar");
            return;
        }

        if (in_array($this->status_order, ['menunggu', 'inden']) && $this->status_payment === 'down_payment') {
            $this->expired_date = Carbon::parse($orderDate)->addDays(2);
            \Log::info("OrderProduct {$this->order_product_id}: expired_date set to {$this->expired_date} for status_order {$this->status_order} and status_payment down_payment");
            return;
        }

        if ($this->status_order === 'siap_kirim' && $this->status_payment === 'down_payment') {
            $this->expired_date = $now->copy()->addDays(3);
            \Log::info("OrderProduct {$this->order_product_id}: expired_date set to {$this->expired_date} for status_order siap_kirim and status_payment down_payment");
            return;
        }

        // default: clear expired_date
        $this->expired_date = null;
        \Log::info("OrderProduct {$this->order_product_id}: expired_date cleared for status_order {$this->status_order} and status_payment {$this->status_payment}");
    }

    /**
     * Check if payment can be made for this order
     */
    public function canAcceptPayment()
    {
        return !in_array($this->status_payment, ['lunas', 'dibatalkan']);
    }

    /**
     * Update payment status and amounts based on related payments
     */
    public function updatePaymentStatus()
    {
        $paidAmount = $this->paymentDetails()->where('status', 'dibayar')->sum('amount');
        $this->paid_amount = $paidAmount;
        $this->remaining_balance = max(0, $this->grand_total - $paidAmount);
        $lastPaymentDate = $this->paymentDetails()->where('status', 'dibayar')->latest('created_at')->value('created_at');
        $this->last_payment_at = $lastPaymentDate ? Carbon::parse($lastPaymentDate) : null;

        if ($paidAmount >= $this->grand_total) {
            $this->status_payment = self::STATUS_PAYMENT_LUNAS;
        } elseif ($paidAmount > 0) {
            // For OrderProduct, we use 'down_payment' instead of 'cicilan'
            $this->status_payment = self::STATUS_PAYMENT_DOWN_PAYMENT;
        } else {
            $this->status_payment = self::STATUS_PAYMENT_BELUM_DIBAYAR;
        }
        $this->save();
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

        // Validate down payment for product orders (must be exactly 50%)
        if ($paymentType === 'down_payment') {
            $expectedDP = round($this->grand_total * 0.5);
            if ($amount != $expectedDP) {
                $errors[] = "Down Payment untuk produk harus tepat 50% dari total (Rp " . number_format($expectedDP, 0, ',', '.') . ").";
            }
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
            $this->status_payment = self::STATUS_PAYMENT_LUNAS;
        } elseif ($this->paid_amount > 0) {
            $this->status_payment = self::STATUS_PAYMENT_DOWN_PAYMENT;
        } else {
            $this->status_payment = self::STATUS_PAYMENT_BELUM_DIBAYAR;
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
            return ['status' => 'melewati_jatuh_tempo', 'message' => 'Garansi sudah habis'];
        }

        $daysLeft = $now->diffInDays($expiry);
        if ($daysLeft <= 30) {
            return ['status' => 'expiring_soon', 'message' => "{$daysLeft} hari lagi habis garansi"];
        }

        return ['status' => 'active', 'message' => "Garansi berlaku sampai {$expiry->format('d/m/Y')}"];
    }
}
