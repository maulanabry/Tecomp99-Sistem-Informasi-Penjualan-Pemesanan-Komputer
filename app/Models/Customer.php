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
        'hasAccount',
        'contact',
        'address',
        'gender',
        'photo',
        'last_active'
    ];

    protected $casts = [
        'hasAccount' => 'boolean',
        'last_active' => 'datetime',
    ];

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
        if (!$this->address) {
            return '-';
        }

        $words = explode(' ', $this->address);
        if (count($words) <= 3) {
            return $this->address;
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
