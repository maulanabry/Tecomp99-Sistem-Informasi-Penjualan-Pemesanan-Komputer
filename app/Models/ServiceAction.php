<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceAction extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'service_action_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'service_action_id',
        'service_ticket_id',
        'number',
        'action',
        'created_at',
    ];

    public function serviceTicket()
    {
        return $this->belongsTo(ServiceTicket::class, 'service_ticket_id', 'service_ticket_id');
    }
}
