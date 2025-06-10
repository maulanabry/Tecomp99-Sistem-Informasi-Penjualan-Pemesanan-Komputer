<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

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
    ];

    protected $casts = [
        'last_active' => 'datetime',
    ];

    public function addresses()
    {
        return $this->hasOne(CustomerAddress::class, 'customer_id', 'customer_id');
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
}
