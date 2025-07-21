<?php

namespace App\Models;

use App\Enums\NotificationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use DateTime;

/**
 * @property int $id
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property NotificationType $type
 * @property string $subject_id
 * @property string $subject_type
 * @property string $message
 * @property array<array-key, mixed>|null $data
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $color
 * @property-read string $icon
 * @property-read string $time_ago
 * @property-read Model|\Eloquent $notifiable
 * @property-read Model|\Eloquent $subject
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemNotification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemNotification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemNotification query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemNotification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemNotification whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemNotification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemNotification whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemNotification whereNotifiableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemNotification whereNotifiableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemNotification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemNotification whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemNotification whereSubjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemNotification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SystemNotification whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SystemNotification extends Model
{
    protected $fillable = [
        'notifiable_id',
        'notifiable_type',
        'type',
        'subject_type',
        'subject_id',
        'message',
        'data',
        'read_at'
    ];

    protected $casts = [
        'type' => NotificationType::class,
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * Get the notifiable entity that the notification belongs to.
     */
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the subject entity that the notification is about.
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Check if the notification is unread
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Mark the notification as read
     */
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => new DateTime()]);
        }
    }

    /**
     * Get the icon class for this notification type
     */
    public function getIconAttribute(): string
    {
        return match ($this->type) {
            NotificationType::PRODUCT_ORDER_CREATED => 'fas fa-shopping-cart',
            NotificationType::SERVICE_ORDER_CREATED => 'fas fa-tools',
            NotificationType::PAYMENT_RECEIVED => 'fas fa-money-bill',
            NotificationType::PAYMENT_FAILED => 'fas fa-exclamation-circle',
            default => 'fas fa-bell'
        };
    }

    /**
     * Get the color class for this notification type
     */
    public function getColorAttribute(): string
    {
        return match ($this->type) {
            NotificationType::PRODUCT_ORDER_CREATED => 'text-blue-500',
            NotificationType::SERVICE_ORDER_CREATED => 'text-green-500',
            NotificationType::PAYMENT_RECEIVED => 'text-emerald-500',
            NotificationType::PAYMENT_FAILED => 'text-red-500',
            default => 'text-neutral-500'
        };
    }

    /**
     * Get human readable time difference
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
