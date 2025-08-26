<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    use HasFactory;

    protected $fillable = [
        'district_id',
        'name',
        'postal_code',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function customerAddresses()
    {
        return $this->hasMany(CustomerAddress::class, 'subdistrict_id');
    }
}
