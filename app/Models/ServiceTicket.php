<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $service_ticket_id
 * @property string $order_service_id
 * @property int|null $admin_id
 * @property string $status
 * @property \Illuminate\Support\Carbon $schedule_date
 * @property int|null $estimation_days
 * @property \Illuminate\Support\Carbon|null $estimate_date
 * @property \Illuminate\Support\Carbon|null $visit_schedule
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceAction> $actions
 * @property-read int|null $actions_count
 * @property-read \App\Models\Admin|null $admin
 * @property-read \App\Models\OrderService $orderService
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket whereEstimateDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket whereEstimationDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket whereOrderServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket whereScheduleDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket whereServiceTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket whereVisitSchedule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceTicket withoutTrashed()
 * @mixin \Eloquent
 */
class ServiceTicket extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'service_ticket_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'service_ticket_id',
        'order_service_id',
        'admin_id',
        'status',
        'schedule_date',
        'visit_schedule',  // New field for visit schedule with date and time
        'estimation_days', // New field
        'estimate_date',   // New field
    ];

    protected $dates = [
        'schedule_date',
        'visit_schedule',
        'estimate_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'schedule_date' => 'datetime',
        'visit_schedule' => 'datetime',
        'estimate_date' => 'datetime',
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

        // Validasi status ServiceTicket harus sesuai dengan OrderService
        // Kecuali jika sedang dalam proses sinkronisasi otomatis
        static::saving(function ($ticket) {
            // Skip validasi jika sedang dalam proses sinkronisasi
            if (session('syncing_ticket_status', false) || session('bypass_ticket_validation', false)) {
                return;
            }

            if ($ticket->orderService) {
                // Map lowercase ticket status to uppercase order status for comparison
                $statusMapping = [
                    'menunggu' => 'Menunggu',
                    'dijadwalkan' => 'Dijadwalkan',
                    'menuju_lokasi' => 'Menuju_lokasi',
                    'diproses' => 'Diproses',
                    'menunggu_sparepart' => 'Menunggu_sparepart',
                    'siap_diambil' => 'Siap_diambil',
                    'diantar' => 'Diantar',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                    'melewati_jatuh_tempo' => 'Melewati_jatuh_tempo',
                ];

                $mappedOrderStatus = $statusMapping[$ticket->status] ?? $ticket->status;
                if ($mappedOrderStatus !== $ticket->orderService->status_order) {
                    throw new \Exception('Status tiket servis harus sesuai dengan status order servis terkait.');
                }
            }
        });

        static::updating(function ($ticket) {
            // Skip validasi jika sedang dalam proses sinkronisasi
            if (session('syncing_ticket_status', false) || session('bypass_ticket_validation', false)) {
                return;
            }

            if ($ticket->orderService) {
                // Map lowercase ticket status to uppercase order status for comparison
                $statusMapping = [
                    'menunggu' => 'Menunggu',
                    'dijadwalkan' => 'Dijadwalkan',
                    'menuju_lokasi' => 'Menuju_lokasi',
                    'diproses' => 'Diproses',
                    'menunggu_sparepart' => 'Menunggu_sparepart',
                    'siap_diambil' => 'Siap_diambil',
                    'diantar' => 'Diantar',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                    'melewati_jatuh_tempo' => 'Melewati_jatuh_tempo',
                ];

                $mappedOrderStatus = $statusMapping[$ticket->status] ?? $ticket->status;
                if ($mappedOrderStatus !== $ticket->orderService->status_order) {
                    throw new \Exception('Status tiket servis harus sesuai dengan status order servis terkait.');
                }
            }
        });
    }

    public function orderService()
    {
        return $this->belongsTo(OrderService::class, 'order_service_id', 'order_service_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    public function actions()
    {
        return $this->hasMany(ServiceAction::class, 'service_ticket_id', 'service_ticket_id');
    }
}
