<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event yang di-broadcast ketika pesan chat dikirim
 */
class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatMessage;

    /**
     * Create a new event instance.
     */
    public function __construct(ChatMessage $chatMessage)
    {
        $this->chatMessage = $chatMessage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.' . $this->chatMessage->chat_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->chatMessage->id,
            'chat_id' => $this->chatMessage->chat_id,
            'sender_type' => $this->chatMessage->sender_type,
            'sender_id' => $this->chatMessage->sender_id,
            'sender_name' => $this->chatMessage->sender_name,
            'message' => $this->chatMessage->message,
            'message_type' => $this->chatMessage->message_type,
            'file_path' => $this->chatMessage->file_path,
            'file_name' => $this->chatMessage->file_name,
            'file_url' => $this->chatMessage->file_url,
            'is_image' => $this->chatMessage->isImage(),
            'formatted_time' => $this->chatMessage->formatted_time,
            'formatted_date' => $this->chatMessage->formatted_date,
            'created_at' => $this->chatMessage->created_at->toISOString(),
        ];
    }
}
