<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'voucher_id';

    protected $fillable = [
        'code',
        'name',
        'type',
        'discount_percentage',
        'discount_amount',
        'minimum_order_amount',
        'is_active',
        'used_count',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function isCurrentlyValid(): bool
    {
        $today = now()->toDateString();
        return $this->start_date <= $today && $today <= $this->end_date;
    }

    public function scopeValid($query)
    {
        $today = now()->toDateString();
        return $query->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today);
    }

    public function scopeInvalid($query)
    {
        $today = now()->toDateString();
        return $query->where(function ($q) use ($today) {
            $q->where('start_date', '>', $today)
                ->orWhere('end_date', '<', $today);
        });
    }
}
