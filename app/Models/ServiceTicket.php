<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'estimation_days', // New field
        'estimate_date',   // New field
    ];

    protected $dates = [
        'schedule_date',
        'estimate_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'schedule_date' => 'datetime',
        'estimate_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

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
