<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event yang di-broadcast ketika status online user berubah
 */
class UserOnlineStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userType;
    public $userId;
    public $userName;
    public $isOnline;

    /**
     * Create a new event instance.
     */
    public function __construct(string $userType, string $userId, string $userName, bool $isOnline)
    {
        $this->userType = $userType; // 'customer' atau 'admin'
        $this->userId = $userId;
        $this->userName = $userName;
        $this->isOnline = $isOnline;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('online-status'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'user.online.status.changed';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'user_type' => $this->userType,
            'user_id' => $this->userId,
            'user_name' => $this->userName,
            'is_online' => $this->isOnline,
            'timestamp' => now()->toISOString(),
        ];
    }
}
