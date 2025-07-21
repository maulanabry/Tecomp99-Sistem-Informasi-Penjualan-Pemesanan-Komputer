<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $service_action_id
 * @property string $service_ticket_id
 * @property string $action
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int $number
 * @property-read \App\Models\ServiceTicket $serviceTicket
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereServiceActionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ServiceAction whereServiceTicketId($value)
 * @mixin \Eloquent
 */
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

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function serviceTicket()
    {
        return $this->belongsTo(ServiceTicket::class, 'service_ticket_id', 'service_ticket_id');
    }
}
