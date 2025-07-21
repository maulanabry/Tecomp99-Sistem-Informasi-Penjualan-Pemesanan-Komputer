<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Model Chat untuk percakapan antara Customer dan Admin
 * 
 * @property int $id
 * @property string $customer_id
 * @property int $admin_id
 * @property \Carbon\Carbon|null $last_message_at
 * @property bool $is_active
 * @property \Carbon\Carbon|null $customer_last_read_at
 * @property \Carbon\Carbon|null $admin_last_read_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Customer $customer
 * @property-read Admin $admin
 * @property-read \Illuminate\Database\Eloquent\Collection<ChatMessage> $messages
 * @property-read ChatMessage|null $lastMessage
 */
class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'admin_id',
        'last_message_at',
        'is_active',
        'customer_last_read_at',
        'admin_last_read_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'customer_last_read_at' => 'datetime',
        'admin_last_read_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Customer
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    /**
     * Relasi ke Admin
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    /**
     * Relasi ke ChatMessage
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    /**
     * Mendapatkan pesan terakhir
     */
    public function lastMessage()
    {
        return $this->hasOne(ChatMessage::class)->latest();
    }

    /**
     * Mendapatkan jumlah pesan yang belum dibaca oleh customer
     */
    public function getUnreadMessagesForCustomerAttribute(): int
    {
        return $this->messages()
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->count();
    }

    /**
     * Mendapatkan jumlah pesan yang belum dibaca oleh admin
     */
    public function getUnreadMessagesForAdminAttribute(): int
    {
        return $this->messages()
            ->where('sender_type', 'customer')
            ->where('is_read', false)
            ->count();
    }

    /**
     * Menandai pesan sebagai sudah dibaca oleh customer
     */
    public function markAsReadByCustomer(): void
    {
        $this->messages()
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        $this->update(['customer_last_read_at' => now()]);
    }

    /**
     * Menandai pesan sebagai sudah dibaca oleh admin
     */
    public function markAsReadByAdmin(): void
    {
        $this->messages()
            ->where('sender_type', 'customer')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        $this->update(['admin_last_read_at' => now()]);
    }

    /**
     * Mencari atau membuat chat antara customer dan admin
     */
    public static function findOrCreateChat(string $customerId, int $adminId): self
    {
        return static::firstOrCreate([
            'customer_id' => $customerId,
            'admin_id' => $adminId,
        ], [
            'is_active' => true,
        ]);
    }

    /**
     * Mendapatkan chat aktif untuk customer
     */
    public static function getActiveChatsForCustomer(string $customerId)
    {
        return static::where('customer_id', $customerId)
            ->where('is_active', true)
            ->with(['admin', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();
    }

    /**
     * Mendapatkan chat aktif untuk admin
     */
    public static function getActiveChatsForAdmin(int $adminId)
    {
        return static::where('admin_id', $adminId)
            ->where('is_active', true)
            ->with(['customer', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();
    }

    /**
     * Mendapatkan semua chat dengan pesan yang belum dibaca untuk admin
     */
    public static function getChatsWithUnreadMessagesForAdmin()
    {
        return static::whereHas('messages', function ($query) {
            $query->where('sender_type', 'customer')
                ->where('is_read', false);
        })
            ->with(['customer', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();
    }
}
