<?php

namespace App\Models;

use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\SystemNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string $customer_id
 * @property string $name
 * @property string|null $email
 * @property string|null $password
 * @property \Carbon\Carbon|null $last_active
 * @property bool $hasAccount
 * @property string|null $photo
 * @property string|null $gender
 * @property string $contact
 * @property int $service_orders_count
 * @property int $product_orders_count
 * @property int $total_points
 * @property \Carbon\Carbon|null $email_verified_at
 * @property-read \Illuminate\Database\Eloquent\Collection<CustomerAddress> $addresses
 * @property-read CustomerAddress|null $defaultAddress
 * @property-read \Illuminate\Database\Eloquent\Collection<OrderProduct> $orderProducts
 * @property-read \Illuminate\Database\Eloquent\Collection<OrderService> $orderServices
 * @property-read \Illuminate\Database\Eloquent\Collection<Chat> $chats
 * @property-read \Illuminate\Database\Eloquent\Collection<ChatMessage> $chatMessages
 * @property-read \Illuminate\Database\Eloquent\Collection<SystemNotification> $notifications
 * @property-read int $unread_messages_count
 * @property-read int $unread_notifications_count
 */
class Customer extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, SoftDeletes, Notifiable;

    protected $primaryKey = 'customer_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'customer_id',
        'name',
        'email',
        'password',
        'last_active',
        'hasAccount',
        'photo',
        'gender',
        'contact',
        'service_orders_count',
        'product_orders_count',
        'total_points',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_active' => 'datetime',
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id', 'customer_id');
    }

    public function defaultAddress(): HasOne
    {
        return $this->hasOne(CustomerAddress::class, 'customer_id', 'customer_id')
            ->where('is_default', true);
    }

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class, 'customer_id', 'customer_id');
    }

    public function orderServices(): HasMany
    {
        return $this->hasMany(OrderService::class, 'customer_id', 'customer_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(Cart::class, 'customer_id', 'customer_id');
    }

    /**
     * Get all notifications for the customer
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
        return $this->notifications()->whereNull('read_at')->count();
    }

    /**
     * Relasi ke Chat
     */
    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'customer_id', 'customer_id');
    }

    /**
     * Relasi ke ChatMessage sebagai pengirim
     */
    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id', 'customer_id')
            ->where('sender_type', 'customer');
    }

    /**
     * Mendapatkan chat aktif dengan admin tertentu
     */
    public function getChatWithAdmin($adminId)
    {
        return $this->chats()
            ->where('admin_id', $adminId)
            ->where('is_active', true)
            ->first();
    }

    /**
     * Mendapatkan semua chat aktif customer
     */
    public function getActiveChats()
    {
        return $this->chats()
            ->where('is_active', true)
            ->with(['admin', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();
    }

    /**
     * Mendapatkan jumlah pesan yang belum dibaca
     */
    public function getUnreadMessagesCountAttribute(): int
    {
        return ChatMessage::whereHas('chat', function ($query) {
            $query->where('customer_id', $this->customer_id);
        })
            ->where('sender_type', 'admin')
            ->where('is_read_by_customer', false)
            ->count();
    }

    /**
     * Mendapatkan chat dengan pesan yang belum dibaca
     */
    public function getChatsWithUnreadMessages()
    {
        return $this->chats()
            ->whereHas('messages', function ($query) {
                $query->where('sender_type', 'admin')
                    ->where('is_read_by_customer', false);
            })
            ->with(['admin', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();
    }

    public static function generateCustomerId(): string
    {
        $date = now()->format('dmy');
        $lastCustomer = self::withTrashed()
            ->where('customer_id', 'like', "CST{$date}%")
            ->orderBy('customer_id', 'desc')
            ->first();

        if (!$lastCustomer) {
            return "CST{$date}001";
        }

        $lastNumber = (int) substr($lastCustomer->customer_id, -3);
        $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return "CST{$date}{$newNumber}";
    }

    public function getFormattedAddressAttribute(): string
    {
        $defaultAddress = $this->addresses()->where('is_default', true)->first();
        if (!$defaultAddress) {
            return '-';
        }

        $words = explode(' ', $defaultAddress->detail_address);
        if (count($words) <= 3) {
            return $defaultAddress->detail_address;
        }

        return implode(' ', array_slice($words, 0, 3)) . '...';
    }

    public function getWhatsappLinkAttribute(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->contact);
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }
        return "https://wa.me/{$phone}";
    }

    /**
     * Find customer by email or phone number for authentication
     */
    public static function findForAuth($identifier)
    {
        return static::where('email', $identifier)
            ->orWhere('contact', $identifier)
            ->first();
    }

    /**
     * Get the name of the unique identifier for the user.
     */
    public function getAuthIdentifierName()
    {
        return 'customer_id';
    }

    /**
     * Get the unique identifier for the user.
     */
    public function getAuthIdentifier()
    {
        return $this->getAttribute($this->getAuthIdentifierName());
    }

    /**
     * Get the password for the user.
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the email address that should be used for verification.
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }

    /**
     * Determine if the user has verified their email address.
     */
    public function hasVerifiedEmail()
    {
        return ! is_null($this->email_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        // This method can be implemented if you want to use Laravel's built-in notifications
        // For now, we're using the custom mail class in the controller
    }
}
