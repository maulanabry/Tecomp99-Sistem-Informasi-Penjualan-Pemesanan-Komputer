<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $voucher_id
 * @property string $code
 * @property string $name
 * @property string $type
 * @property string|null $discount_percentage
 * @property int|null $discount_amount
 * @property int|null $minimum_order_amount
 * @property bool $is_active
 * @property int $used_count
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher invalid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher valid()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereDiscountPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereMinimumOrderAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereUsedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher whereVoucherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Voucher withoutTrashed()
 * @mixin \Eloquent
 */
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
