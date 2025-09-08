<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSeen implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sender_id;  // Jobseeker ID
    public $sender_type;
    public $to_id;      // Admin ID
    public $to_type;

    public function __construct($sender_id, $sender_type, $to_id, $to_type)
    {
        $this->sender_id = $sender_id;
        $this->sender_type = $sender_type;
        $this->to_id = $to_id;
        $this->to_type = $to_type;
    }

    public function broadcastOn()
    {
        return ['chat.admin'];
    }

    public function broadcastAs()
    {
        return 'message.seen';
    }
}

