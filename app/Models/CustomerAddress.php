<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerAddress extends Model
{
    use HasFactory;

    protected $table = 'customer_addresses';

    protected $fillable = [
        'customer_id',
        'province_id',
        'province_name',
        'city_id',
        'city_name',
        'district_id',
        'district_name',
        'subdistrict_id',
        'subdistrict_name',
        'postal_code',
        'detail_address',
        'is_default',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($address) {
            if ($address->is_default) {
                // Remove default status from other addresses of the same customer
                static::where('customer_id', $address->customer_id)
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }
        });

        static::saved(function ($address) {
            // If this is the only address for the customer, make it default
            $addressCount = static::where('customer_id', $address->customer_id)->count();
            if ($addressCount === 1) {
                $address->update(['is_default' => true]);
            }
        });
    }

    public function setAsDefault()
    {
        DB::transaction(function () {
            // Remove default status from other addresses
            static::where('customer_id', $this->customer_id)
                ->where('id', '!=', $this->id)
                ->update(['is_default' => false]);

            // Set this address as default
            $this->update(['is_default' => true]);
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }
}
