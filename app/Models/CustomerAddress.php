<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $customer_id
 * @property int|null $province_id
 * @property string|null $province_name
 * @property int|null $city_id
 * @property string|null $city_name
 * @property int|null $district_id
 * @property string|null $district_name
 * @property int|null $subdistrict_id
 * @property string|null $subdistrict_name
 * @property string|null $postal_code
 * @property string|null $detail_address
 * @property int $is_default
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereCityName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereDetailAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereDistrictName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereProvinceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereProvinceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereSubdistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereSubdistrictName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerAddress whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class, 'subdistrict_id');
    }
}
