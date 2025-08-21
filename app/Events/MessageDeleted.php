<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageId;
    public $receiverId;

    /**
     * Create a new event instance.
     */
    public function __construct($messageId, $receiverId)
    {
        $this->messageId = $messageId;
        $this->receiverId = $receiverId;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        // Private channel jahan dono users sun sakte hain
        return new PrivateChannel('private-chat.' . $this->receiverId);
    }

    /**
     * Event ka naam client-side par
     */
    public function broadcastAs()
    {
        return 'message.deleted';
    }
}
