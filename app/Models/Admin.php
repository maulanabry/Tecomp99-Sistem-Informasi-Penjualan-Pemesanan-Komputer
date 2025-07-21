<?php

namespace App\Models;

use App\Models\SystemNotification;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $theme
 * @property \Carbon\Carbon|null $email_verified_at
 * @property \Carbon\Carbon|null $last_seen_at
 * @property-read \Illuminate\Database\Eloquent\Collection<Chat> $chats
 * @property-read \Illuminate\Database\Eloquent\Collection<ChatMessage> $chatMessages
 * @property-read \Illuminate\Database\Eloquent\Collection<SystemNotification> $notifications
 * @property-read int $unread_notifications_count
 * @property-read int $unread_messages_count
 * @method HasMany chats()
 * @method HasMany chatMessages()
 * @method MorphMany notifications()
 */
class Admin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'theme',
        'email_verified_at',
        'last_seen_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_seen_at' => 'datetime',
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

    /**
     * Check if admin is currently online
     * An admin is considered online if they were active within the last 5 minutes
     */
    public function isOnline(): bool
    {
        return $this->last_seen_at && $this->last_seen_at->gt(now()->subMinutes(5));
    }

    /**
     * Relasi ke model Chat
     */
    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'admin_id', 'id');
    }

    /**
     * Relasi ke ChatMessage sebagai pengirim
     */
    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id', 'id')
            ->where('sender_type', 'admin');
    }

    /**
     * Mendapatkan chat aktif dengan customer tertentu
     */
    public function getChatWithCustomer(string $customerId)
    {
        return $this->chats()
            ->where('customer_id', $customerId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Mendapatkan semua chat aktif admin
     */
    public function getActiveChats()
    {
        return $this->chats()
            ->where('is_active', true)
            ->with(['customer', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();
    }

    /**
     * Mendapatkan jumlah pesan yang belum dibaca dari semua customer
     */
    public function getUnreadMessagesCountAttribute(): int
    {
        return ChatMessage::whereHas('chat', function ($query) {
            $query->where('admin_id', $this->id);
        })
            ->where('sender_type', 'customer')
            ->where('is_read_by_admin', false)
            ->count();
    }

    /**
     * Mendapatkan chat dengan pesan yang belum dibaca
     */
    public function getChatsWithUnreadMessages()
    {
        return $this->chats()
            ->whereHas('messages', function ($query) {
                $query->where('sender_type', 'customer')
                    ->where('is_read_by_admin', false);
            })
            ->with(['customer', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();
    }
}
