<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

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

    // Status constants
    const STATUS_ORDER_MENUNGGU = 'menunggu';
    const STATUS_ORDER_DIJADWALKAN = 'dijadwalkan';
    const STATUS_ORDER_MENUJU_LOKASI = 'menuju_lokasi';
    const STATUS_ORDER_DIPROSES = 'diproses';
    const STATUS_ORDER_MENUNGGU_SPAREPART = 'menunggu_sparepart';
    const STATUS_ORDER_SIAP_DIAMBIL = 'siap_diambil';
    const STATUS_ORDER_DIANTAR = 'diantar';
    const STATUS_ORDER_SELESAI = 'selesai';
    const STATUS_ORDER_DIBATALKAN = 'dibatalkan';
    const STATUS_ORDER_MELEWATI_JATUH_TEMPO = 'melewati_jatuh_tempo';

    const STATUS_PAYMENT_BELUM_DIBAYAR = 'belum_dibayar';
    const STATUS_PAYMENT_CICILAN = 'cicilan';
    const STATUS_PAYMENT_LUNAS = 'lunas';
    const STATUS_PAYMENT_DIBATALKAN = 'dibatalkan';

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
        'assigned_admin_id',
        'visit_slot',
        'visit_date',
        'expired_date',
    ];

    protected $casts = [
        'warranty_expired_at' => 'datetime',
        'last_payment_at' => 'datetime',
        'estimated_completion' => 'datetime',
        'visit_date' => 'date',
        'expired_date' => 'datetime',
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
                $order->status_payment = $order->status_payment ?? self::STATUS_PAYMENT_BELUM_DIBAYAR;
                $order->expired_date = null; // As per rules
            }

            // Update expired_date based on rules
            if (!$order->exists || $order->isDirty('status_order') || $order->isDirty('status_payment')) {
                $order->updateExpiredDate();
            }
        });

        static::updated(function ($order) {
            // Sinkronisasi status dengan ServiceTicket ketika status_order berubah
            if ($order->isDirty('status_order') && !session('updating_ticket_status', false)) {
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
            $this->status_payment = self::STATUS_PAYMENT_LUNAS;
        } elseif ($paidAmount > 0) {
            $this->status_payment = self::STATUS_PAYMENT_CICILAN;
        } else {
            $this->status_payment = self::STATUS_PAYMENT_BELUM_DIBAYAR;
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
            $this->status_payment = self::STATUS_PAYMENT_LUNAS;
        } elseif ($this->paid_amount > 0) {
            $this->status_payment = self::STATUS_PAYMENT_CICILAN;
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

    public function assignedAdmin()
    {
        return $this->belongsTo(Admin::class, 'assigned_admin_id', 'id');
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

        // Jika status berubah ke dijadwalkan dan belum ada ticket, buat ticket baru
        if ($this->status_order === 'Dijadwalkan' && !$this->hasTicket) {
            $this->createServiceTicketOnSchedule();
        }

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
     * Create service ticket when order status changes to dijadwalkan
     */
    private function createServiceTicketOnSchedule()
    {
        // Generate service ticket ID
        $ticketId = $this->generateServiceTicketId();

        // Parse visit schedule from visit_date and visit_slot
        $visitSchedule = null;
        if ($this->visit_date && $this->visit_slot) {
            // Extract start time from slot (e.g., "08:00 - 09:30" -> "08:00")
            $startTime = explode(' - ', $this->visit_slot)[0];
            $visitSchedule = Carbon::parse($this->visit_date . ' ' . $startTime);
        }

        // Create service ticket
        ServiceTicket::create([
            'service_ticket_id' => $ticketId,
            'order_service_id' => $this->order_service_id,
            'admin_id' => $this->assigned_admin_id,
            'status' => 'dijadwalkan',
            'schedule_date' => $visitSchedule ? $visitSchedule->toDateString() : Carbon::tomorrow()->toDateString(),
            'visit_schedule' => $visitSchedule,
            'estimation_days' => null, // Will be set by technician
            'estimate_date' => null, // Will be calculated later
        ]);

        // Update order service to indicate it has a ticket
        $this->update(['hasTicket' => true]);
    }

    /**
     * Generate service ticket ID
     */
    private function generateServiceTicketId()
    {
        $date = now()->format('dmy');
        $lastTicket = ServiceTicket::withTrashed()
            ->where('service_ticket_id', 'like', "TKT{$date}%")
            ->orderBy('service_ticket_id', 'desc')
            ->first();

        if (!$lastTicket) {
            return "TKT{$date}001";
        }

        $lastNumber = (int) substr($lastTicket->service_ticket_id, -3);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return "TKT{$date}{$newNumber}";
    }

    /**
     * Update expired_date based on current status and rules
     */
    public function updateExpiredDate()
    {
        $now = Carbon::now();
        $orderDate = $this->created_at ?? $now;

        // 1) Jika status_payment = 'lunas' → clear expired_date
        if ($this->status_payment === 'lunas') {
            $this->expired_date = null;
            return;
        }

        // 2) Validasi kompatibilitas status_order & status_payment
        if ($this->isDirty('status_order') || $this->isDirty('status_payment')) {
            switch ($this->status_order) {

                case 'diproses':
                    if (!in_array($this->status_payment, ['belum_dibayar', 'cicilan', 'lunas'])) {
                        throw ValidationException::withMessages([
                            'status_order' => ["Status diproses mengharuskan pembayaran = belum_dibayar, cicilan, atau lunas."],
                        ]);
                    }
                    break;

                case 'menunggu_sparepart':
                    if (!in_array($this->status_payment, ['cicilan', 'lunas'])) {
                        throw ValidationException::withMessages([
                            'status_order' => ["Status {$this->status_order} hanya mengizinkan pembayaran cicilan atau lunas."],
                        ]);
                    }
                    break;
                case 'siap_diambil':
                    if (!in_array($this->status_payment, ['cicilan', 'lunas', 'belum_dibayar'])) {
                        throw ValidationException::withMessages([
                            'status_order' => ["Status {$this->status_order} hanya mengizinkan pembayaran = belum_dibayar, cicilan, atau lunas"],
                        ]);
                    }
                    break;
                case 'diantar':
                    if (!in_array($this->status_payment, ['lunas'])) {
                        throw ValidationException::withMessages([
                            'status_order' => ["Status {$this->status_order} hanya mengizinkan pembayaran  lunas."],
                        ]);
                    }
                    break;

                case 'selesai':
                    if ($this->status_payment !== 'lunas') {
                        throw ValidationException::withMessages([
                            'status_order' => ["Status selesai hanya bisa jika pembayaran lunas 100%."],
                        ]);
                    }
                    break;

                case 'melewati_jatuh_tempo':
                    if ($this->status_payment !== ['cicilan', 'belum_dibayar']) {
                        throw ValidationException::withMessages([
                            'status_order' => ["Status melewati jatuh_tempo hanya berlaku jika sedang cicilan atau belum dibayar."],
                        ]);
                    }
                    break;

                    // dibatalkan → no strict requirement
            }
        }

        // 3) Aturan expired_date (hanya jika payment bukan lunas)
        switch ($this->status_payment) {
            case 'belum_dibayar':
                if (in_array($this->status_order, ['menunggu_sparepart'])) {
                    $this->expired_date = Carbon::parse($now)->addDay(2);
                } else if (in_array($this->status_order, ['siap_diambil'])) {
                    $this->expired_date = Carbon::parse($now)->addDay(14);
                } else {
                    $this->expired_date = null;
                }
                break;

            case 'cicilan':
                // hanya berlaku jika status_order = siap_diambil
                if ($this->status_order === 'siap_diambil') {
                    $this->expired_date = Carbon::parse($now)->addDays(14);
                } else {
                    $this->expired_date = null;
                }
                break;

            default:
                // clear expired_date untuk lunas atau dibatalkan
                $this->expired_date = null;
                break;
        }
    }

    // Mengecek Apakah order sudah melewati expired_date
    public function checkExpiredStatus()
    {
        if ($this->expired_date && $this->expired_date->isPast()) {
            if (in_array($this->status_payment, [
                self::STATUS_PAYMENT_BELUM_DIBAYAR,
                self::STATUS_PAYMENT_CICILAN
            ])) {
                if ($this->status_order !== self::STATUS_ORDER_MELEWATI_JATUH_TEMPO) {
                    $this->update([
                        'status_order' => self::STATUS_ORDER_MELEWATI_JATUH_TEMPO

                    ]);
                    // Sinkronisasi status tiket servis jika order melewati jatuh tempo
                    $this->syncServiceTicketStatus();
                }
            }
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
