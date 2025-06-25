<?php

namespace App\Models;

use App\Models\SystemNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all notifications for this admin
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(SystemNotification::class, 'notifiable');
    }

    /**
     * Get unread notifications count
     */
    public function getUnreadNotificationsCountAttribute(): int
    {
        return DB::table('system_notifications')
            ->where('notifiable_id', $this->id)
            ->where('notifiable_type', self::class)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Get recent notifications
     */
    public function getRecentNotificationsAttribute()
    {
        return DB::table('system_notifications')
            ->where('notifiable_id', $this->id)
            ->where('notifiable_type', self::class)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }
}
