<?php

namespace App\Services;

use App\Enums\NotificationType;
use App\Models\SystemNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use DateTime;

class NotificationService
{
    /**
     * Create a new notification
     */
    public function create(
        Model $notifiable,
        NotificationType $type,
        Model $subject,
        string $message,
        array $data = []
    ): SystemNotification {
        // Use getKey() for models with custom primary keys, id for default
        $notifiableId = method_exists($notifiable, 'getKey') ? $notifiable->getKey() : $notifiable->id;
        $subjectId = method_exists($subject, 'getKey') ? $subject->getKey() : $subject->id;

        return SystemNotification::create([
            'notifiable_id' => $notifiableId,
            'notifiable_type' => get_class($notifiable),
            'type' => $type,
            'subject_type' => get_class($subject),
            'subject_id' => $subjectId,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * Create notifications for multiple recipients
     * 
     * @param Model[] $notifiables Array of notifiable models
     */
    public function createMultiple(
        array $notifiables,
        NotificationType $type,
        Model $subject,
        string $message,
        array $data = []
    ): void {
        foreach ($notifiables as $notifiable) {
            $this->create($notifiable, $type, $subject, $message, $data);
        }
    }

    /**
     * Mark multiple notifications as read
     */
    public function markAsRead(array $notificationIds): void
    {
        DB::table('system_notifications')
            ->whereIn('id', $notificationIds)
            ->update(['read_at' => new DateTime()]);
    }

    /**
     * Mark all notifications as read for a notifiable
     */
    public function markAllAsRead(Model $notifiable): void
    {
        // Use getKey() for models with custom primary keys, id for default
        $notifiableId = method_exists($notifiable, 'getKey') ? $notifiable->getKey() : $notifiable->id;

        DB::table('system_notifications')
            ->where('notifiable_id', $notifiableId)
            ->where('notifiable_type', get_class($notifiable))
            ->whereNull('read_at')
            ->update(['read_at' => new DateTime()]);
    }

    /**
     * Delete notifications older than given days
     */
    public function deleteOldNotifications(int $days = 30): void
    {
        $cutoff = new DateTime("-{$days} days");
        DB::table('system_notifications')
            ->where('created_at', '<', $cutoff)
            ->whereNotNull('read_at')
            ->delete();
    }

    /**
     * Get unread count for a notifiable
     */
    public function getUnreadCount(Model $notifiable): int
    {
        // Use getKey() for models with custom primary keys, id for default
        $notifiableId = method_exists($notifiable, 'getKey') ? $notifiable->getKey() : $notifiable->id;

        return DB::table('system_notifications')
            ->where('notifiable_id', $notifiableId)
            ->where('notifiable_type', get_class($notifiable))
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Get recent notifications for a notifiable
     */
    public function getRecent(Model $notifiable, int $limit = 10): mixed
    {
        // Use getKey() for models with custom primary keys, id for default
        $notifiableId = method_exists($notifiable, 'getKey') ? $notifiable->getKey() : $notifiable->id;

        return SystemNotification::where('notifiable_id', $notifiableId)
            ->where('notifiable_type', get_class($notifiable))
            ->with('subject')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
