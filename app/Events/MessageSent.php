<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct( $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        $channels = [];

        if ($this->message->receiver_type == 'trainer') {
            $channels[] = "chat.trainer.{$this->message->receiver_id}";
        }
        if ($this->message->receiver_type == 'mentor') {
            $channels[] = "chat.mentor.{$this->message->receiver_id}";
        }
        if ($this->message->receiver_type == 'assessor') {
            $channels[] = "chat.assessor.{$this->message->receiver_id}";
        }
        if ($this->message->receiver_type == 'coach') {
            $channels[] = "chat.coach.{$this->message->receiver_id}";
        }
        if ($this->message->receiver_type == 'admin') {
            $channels[] = "chat.admin.{$this->message->receiver_id}";
        }
        if ($this->message->receiver_type == 'jobseeker') {
            $channels[] = "chat.jobseeker.{$this->message->receiver_id}";
        }

        return array_map(fn($channel) => new PrivateChannel($channel), $channels);
    }



    public function broadcastAs()
    {
        return 'message.sent';
    }

    // public function broadcastWith()
    // {
    //     return [
    //         'id' => $this->message->id,
    //         'sender_id' => $this->message->sender_id,
    //         'sender_type' => $this->message->sender_type,
    //         'receiver_id' => $this->message->receiver_id,
    //         'receiver_type' => $this->message->receiver_type,
    //         'message' => $this->message->message,
    //         'type' => $this->message->type,
    //         'created_at' => $this->message->created_at->toDateTimeString(),
    //     ];
    // }
    public function broadcastWith()
    {
        
        $createdAt = $this->message->created_at;

        if (!($createdAt instanceof \Carbon\Carbon)) {
            $createdAt = \Carbon\Carbon::parse($createdAt);
        }

        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'sender_type' => $this->message->sender_type,
            'receiver_id' => $this->message->receiver_id,
            'receiver_type' => $this->message->receiver_type,
            'message' => $this->message->message,
            'type' => $this->message->type,
            'created_at' => $createdAt->toDateTimeString(),
        ];
    }

}
