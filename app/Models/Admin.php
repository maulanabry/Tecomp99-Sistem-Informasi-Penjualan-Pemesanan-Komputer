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
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string $role
 * @property string $theme
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $last_seen_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ChatMessage> $chatMessages
 * @property-read int|null $chat_messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Chat> $chats
 * @property-read int|null $chats_count
 * @property-read mixed $recent_notifications
 * @property-read int $unread_messages_count
 * @property-read int $unread_notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SystemNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereLastSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereTheme($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Admin withoutTrashed()
 * @mixin \Eloquent
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
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Chat>
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
