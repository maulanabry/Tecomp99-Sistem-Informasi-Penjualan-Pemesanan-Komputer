<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Model ChatMessage untuk pesan individual dalam chat
 *
 * @property int $id
 * @property int $chat_id
 * @property string $sender_type
 * @property string $sender_id
 * @property string $message
 * @property string $message_type
 * @property string|null $file_path
 * @property string|null $file_name
 * @property bool $is_read
 * @property bool $is_read_by_customer
 * @property bool $is_read_by_admin
 * @property \Illuminate\Support\Carbon|null $read_by_customer_at
 * @property \Illuminate\Support\Carbon|null $read_by_admin_at
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Chat $chat
 * @property-read string|null $file_url
 * @property-read string $formatted_date
 * @property-read string $formatted_time
 * @property-read string $sender_name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage fromAdmin()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage fromCustomer()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage today()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage unread()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereIsReadByAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereIsReadByCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereMessageType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereReadByAdminAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereReadByCustomerAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereSenderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChatMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'sender_type',
        'sender_id',
        'message',
        'message_type',
        'file_path',
        'file_name',
        'is_read',
        'read_at',
        'is_read_by_customer',
        'is_read_by_admin',
        'read_by_customer_at',
        'read_by_admin_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'is_read_by_customer' => 'boolean',
        'is_read_by_admin' => 'boolean',
        'read_by_customer_at' => 'datetime',
        'read_by_admin_at' => 'datetime',
    ];

    /**
     * Relasi ke Chat
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Relasi polymorphic ke pengirim (Customer atau Admin)
     */
    public function sender()
    {
        if ($this->sender_type === 'customer') {
            return $this->belongsTo(Customer::class, 'sender_id', 'customer_id');
        } elseif ($this->sender_type === 'admin') {
            return $this->belongsTo(Admin::class, 'sender_id', 'id');
        }

        return null;
    }

    /**
     * Mendapatkan nama pengirim
     */
    public function getSenderNameAttribute(): string
    {
        if ($this->sender_type === 'customer') {
            $customer = Customer::where('customer_id', $this->sender_id)->first();
            return $customer ? $customer->name : 'Customer';
        } elseif ($this->sender_type === 'admin') {
            $admin = Admin::find($this->sender_id);
            return $admin ? $admin->name : 'Admin';
        }

        return 'Unknown';
    }

    /**
     * Cek apakah pesan dikirim oleh customer
     */
    public function isFromCustomer(): bool
    {
        return $this->sender_type === 'customer';
    }

    /**
     * Cek apakah pesan dikirim oleh admin
     */
    public function isFromAdmin(): bool
    {
        return $this->sender_type === 'admin';
    }

    /**
     * Mendapatkan waktu dalam format yang mudah dibaca
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->format('H:i');
    }

    /**
     * Mendapatkan tanggal dalam format yang mudah dibaca
     */
    public function getFormattedDateAttribute(): string
    {
        $now = Carbon::now();
        $messageDate = $this->created_at;

        if ($messageDate->isToday()) {
            return 'Hari Ini';
        } elseif ($messageDate->isYesterday()) {
            return 'Kemarin';
        } elseif ($messageDate->diffInDays($now) <= 7) {
            return $messageDate->locale('id')->dayName;
        } else {
            return $messageDate->format('d M Y');
        }
    }

    /**
     * Cek apakah pesan adalah file/gambar
     */
    public function hasAttachment(): bool
    {
        return !empty($this->file_path);
    }

    /**
     * Mendapatkan URL file attachment
     */
    public function getFileUrlAttribute(): ?string
    {
        if ($this->hasAttachment()) {
            return asset('storage/' . $this->file_path);
        }

        return null;
    }

    /**
     * Cek apakah file adalah gambar
     */
    public function isImage(): bool
    {
        if (!$this->hasAttachment()) {
            return false;
        }

        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        $extension = strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));

        return in_array($extension, $imageExtensions);
    }

    /**
     * Menandai pesan sebagai sudah dibaca
     */
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }
    }

    /**
     * Scope untuk pesan yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope untuk pesan dari customer
     */
    public function scopeFromCustomer($query)
    {
        return $query->where('sender_type', 'customer');
    }

    /**
     * Scope untuk pesan dari admin
     */
    public function scopeFromAdmin($query)
    {
        return $query->where('sender_type', 'admin');
    }

    /**
     * Scope untuk pesan hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    /**
     * Boot method untuk update last_message_at di chat
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($message) {
            // Update last_message_at di chat ketika pesan baru dibuat
            $message->chat->update([
                'last_message_at' => $message->created_at
            ]);
        });
    }
}
