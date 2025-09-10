<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * @property string $order_service_id
 * @property string $customer_id
 * @property string $status_order
 * @property string $status_payment
 * @property string|null $complaints
 * @property string $type
 * @property string $device
 * @property string|null $note
 * @property bool $hasTicket
 * @property bool $hasDevice
 * @property numeric $sub_total
 * @property numeric $grand_total
 * @property numeric $discount_amount
 * @property int|null $warranty_period_months
 * @property \Illuminate\Support\Carbon|null $warranty_expired_at
 * @property numeric $paid_amount
 * @property numeric $remaining_balance
 * @property \Illuminate\Support\Carbon|null $last_payment_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Customer $customer
 * @property-read mixed $applied_promo
 * @property-read mixed $warranty_status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderServiceImage> $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderServiceItem> $items
 * @property-read int|null $items_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderServiceMedia> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentDetail> $paymentDetails
 * @property-read int|null $payment_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceTicket> $tickets
 * @property-read int|null $tickets_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereComplaints($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereHasDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereHasTicket($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereLastPaymentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereOrderServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService wherePaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereRemainingBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereStatusOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereStatusPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereWarrantyExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService whereWarrantyPeriodMonths($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderService withoutTrashed()
 * @mixin \Eloquent
 */
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
        'warranty_period_months',
        'warranty_expired_at',
        'paid_amount',
        'remaining_balance',
        'last_payment_at',
        'estimated_completion',
    ];

    protected $casts = [
        'warranty_expired_at' => 'datetime',
        'last_payment_at' => 'datetime',
        'estimated_completion' => 'datetime',
        'paid_amount' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'hasTicket' => 'boolean',
        'hasDevice' => 'boolean',
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
                $order->warranty_expired_at = Carbon::now()->addMonths((int) $order->warranty_period_months);
            }

            // Initialize payment tracking fields for new orders
            if (!$order->exists) {
                $order->paid_amount = $order->paid_amount ?? 0;
                $order->remaining_balance = $order->grand_total - ($order->paid_amount ?? 0);
            }
        });

        static::updated(function ($order) {
            // Sinkronisasi status dengan ServiceTicket ketika status_order berubah
            if ($order->isDirty('status_order')) {
                $order->syncServiceTicketStatus();
            }
        });
    }

    /**
     * Calculate and update warranty_expired_at based on completion date and warranty_period_months
     */
    public function updateWarrantyExpiration(\DateTimeInterface $completionDate)
    {
        if ($this->warranty_period_months) {
            $this->warranty_expired_at = Carbon::parse($completionDate)->addMonths((int) $this->warranty_period_months);
            $this->save();
        }
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
            return ['status' => 'melewati_jatuh_tempo', 'message' => 'Garansi sudah habis'];
        }

        $daysLeft = $now->diffInDays($expiry);
        if ($daysLeft <= 30) {
            return ['status' => 'expiring_soon', 'message' => "{$daysLeft} hari lagi habis garansi"];
        }

        return ['status' => 'active', 'message' => "Garansi berlaku sampai {$expiry->format('d/m/Y')}"];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class, 'order_service_id', 'order_service_id');
    }

    public function items()
    {
        return $this->hasMany(OrderServiceItem::class, 'order_service_id', 'order_service_id');
    }

    public function images()
    {
        return $this->hasMany(OrderServiceImage::class, 'order_service_id', 'order_service_id');
    }

    public function media()
    {
        return $this->hasMany(OrderServiceMedia::class, 'order_service_id', 'order_service_id');
    }

    public function tickets()
    {
        return $this->hasMany(ServiceTicket::class, 'order_service_id', 'order_service_id');
    }

    /**
     * Sinkronisasi status ServiceTicket dengan status OrderService
     * Ketika status OrderService berubah, ServiceTicket yang terkait juga akan diperbarui
     */
    public function syncServiceTicketStatus()
    {
        // Mapping status OrderService (uppercase) ke ServiceTicket (lowercase)
        $statusMapping = [
            'Menunggu' => 'menunggu',
            'Dijadwalkan' => 'dijadwalkan',
            'Menuju_lokasi' => 'menuju_lokasi',
            'Diproses' => 'diproses',
            'Menunggu_sparepart' => 'menunggu_sparepart',
            'Siap_diambil' => 'siap_diambil',
            'Diantar' => 'diantar',
            'Selesai' => 'selesai',
            'Dibatalkan' => 'dibatalkan',
            'Melewati_jatuh_tempo' => 'melewati_jatuh_tempo'
        ];

        $newTicketStatus = $statusMapping[$this->status_order] ?? 'menunggu';

        // Set session flag untuk bypass validasi
        session(['syncing_ticket_status' => true]);

        try {
            // Update semua ServiceTicket yang terkait dengan OrderService ini
            $this->tickets()->update(['status' => $newTicketStatus]);

            // Buat ServiceAction untuk setiap tiket yang diperbarui
            foreach ($this->tickets as $ticket) {
                // Buat deskripsi aksi berdasarkan status baru
                $actionDescriptions = [
                    'menunggu' => 'Status order diubah menjadi menunggu',
                    'dijadwalkan' => 'Status order diubah menjadi dijadwalkan',
                    'menuju_lokasi' => 'Status order diubah menjadi menuju lokasi',
                    'diproses' => 'Status order diubah menjadi diproses',
                    'menunggu_sparepart' => 'Status order diubah menjadi menunggu sparepart',
                    'siap_diambil' => 'Status order diubah menjadi siap diambil',
                    'diantar' => 'Status order diubah menjadi diantar',
                    'selesai' => 'Status order diubah menjadi selesai',
                    'dibatalkan' => 'Status order diubah menjadi dibatalkan',
                    'melewati_jatuh_tempo' => 'Status order diubah menjadi melewati_jatuh_tempo'
                ];

                $actionDescription = $actionDescriptions[$newTicketStatus] ?? 'Status order diperbarui';

                // Get the next number for this ticket's actions
                $nextNumber = $ticket->actions()->max('number') + 1 ?? 1;

                // Generate unique service action ID with timestamp and random component
                do {
                    $timestamp = now()->format('ymdHis');
                    $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                    $actionId = 'OSA' . $timestamp . $random;
                    $exists = \App\Models\ServiceAction::where('service_action_id', $actionId)->exists();
                } while ($exists);

                // Buat ServiceAction baru
                \App\Models\ServiceAction::create([
                    'service_action_id' => $actionId,
                    'service_ticket_id' => $ticket->service_ticket_id,
                    'action' => $actionDescription,
                    'number' => $nextNumber,
                    'created_at' => now()
                ]);
            }
        } finally {
            // Hapus session flag setelah selesai
            session()->forget('syncing_ticket_status');
        }
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
