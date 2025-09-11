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
            return;
        }

        // 2) Validasi kompatibilitas antara status_order dan status_payment
        if ($this->isDirty('status_order') || $this->isDirty('status_payment')) {
            switch ($this->status_order) {
                case 'inden':
                    if ($this->status_payment !== 'down_payment') {
                        session()->flash('error', 'Status "inden" mengharuskan status pembayaran = down_payment.');
                        return false;
                    }
                    break;

                case 'siap_kirim':
                    if (! in_array($this->status_payment, ['down_payment', 'lunas'])) {
                        session()->flash('error', 'Status "siap_kirim" mengharuskan status pembayaran = down_payment atau lunas.');
                        return false;
                    }
                    break;

                case 'diproses':
                    if ($this->status_payment !== 'lunas') {
                        session()->flash('error', 'Status "diproses" mengharuskan pembayaran sudah lunas.');
                        return false;
                    }
                    break;

                case 'dikirim':
                case 'selesai':
                    if ($this->status_payment !== 'lunas') {
                        session()->flash('error', "Tidak bisa mengubah status menjadi {$this->status_order} karena pembayaran belum lunas.");
                        return false;
                    }
                    break;
            }
        }

        // 3) Aturan expired_date (hanya jika payment bukan lunas)
        if ($this->status_order === 'menunggu' && $this->status_payment === 'belum_dibayar') {
            $this->expired_date = Carbon::parse($orderDate)->addDay();
            return;
        }

        if (in_array($this->status_order, ['menunggu', 'inden']) && $this->status_payment === 'down_payment') {
            $this->expired_date = Carbon::parse($orderDate)->addDays(2);
            return;
        }

        if ($this->status_order === 'siap_kirim' && $this->status_payment === 'down_payment') {
            $this->expired_date = $now->copy()->addDays(3);
            return;
        }

        // default: clear expired_date
        $this->expired_date = null;
    }
}
