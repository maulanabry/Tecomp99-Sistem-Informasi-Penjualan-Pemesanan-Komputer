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
 * @property \Carbon\Carbon|null $read_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Chat $chat
 * @property-read Customer|Admin|null $sender
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
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
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
