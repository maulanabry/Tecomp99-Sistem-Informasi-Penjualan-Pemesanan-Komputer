<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;

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

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id', 'customer_id');
    }

    public function defaultAddress()
    {
        return $this->hasOne(CustomerAddress::class, 'customer_id', 'customer_id')
            ->where('is_default', true);
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
