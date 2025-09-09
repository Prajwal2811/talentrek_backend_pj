<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;
use Carbon\Carbon;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    // public function broadcastOn()
    // {
    //     $channels = [];

    //     // ✅ Always send to sender & receiver channel
    //     if ($this->message->sender_type === 'admin') {
    //         $channels[] = 'chat.admin';
    //         $channels[] = 'chat.jobseeker'; // admin → jobseeker 
    //     }

    //     if ($this->message->sender_type === 'jobseeker') {
    //         $channels[] = 'chat.jobseeker';
    //         $channels[] = 'chat.admin'; // jobseeker → admin 
    //     }

    //     if ($this->message->sender_type === 'mentor') {
    //         $channels[] = 'chat.mentor';
    //         $channels[] = 'chat.admin'; // mentor → admin 
    //     }

    //     if ($this->message->sender_type === 'coach') {
    //         $channels[] = 'chat.coach';
    //         $channels[] = 'chat.admin'; // coach → admin 
    //     }

    //     if ($this->message->sender_type === 'assessor') {
    //         $channels[] = 'chat.assessor';
    //         $channels[] = 'chat.admin'; // assessor → admin 
    //     }

    //     if ($this->message->receiver_type === 'jobseeker') {
    //         $channels[] = 'chat.jobseeker';
    //     }
    //     if ($this->message->receiver_type === 'trainer') {
    //         $channels[] = 'chat.trainer';
    //     }
    //     if ($this->message->receiver_type === 'mentor') {
    //         $channels[] = 'chat.mentor';
    //     }
    //     if ($this->message->receiver_type === 'assessor') {
    //         $channels[] = 'chat.assessor';
    //     }
    //     if ($this->message->receiver_type === 'coach') {
    //         $channels[] = 'chat.coach';
    //     }
    //     if ($this->message->receiver_type === 'group') {
    //         $channels[] = 'chat.group';
    //     }

    //     return array_map(fn($ch) => new Channel($ch), $channels);
    // }
    public function broadcastOn()
    {
        $channels = [];

        // ✅ Always send to sender & receiver channel
        if ($this->message->sender_type === 'admin') {
            $channels[] = 'chat.admin';
            $channels[] = 'chat.jobseeker';
        }

        if ($this->message->sender_type === 'jobseeker') {
            $channels[] = 'chat.jobseeker';
            $channels[] = 'chat.admin';
        }

        if ($this->message->sender_type === 'mentor') {
            $channels[] = 'chat.mentor';
            $channels[] = 'chat.admin';
        }

        if ($this->message->sender_type === 'coach') {
            $channels[] = 'chat.coach';
            $channels[] = 'chat.admin';
        }

        if ($this->message->sender_type === 'assessor') {
            $channels[] = 'chat.assessor';
            $channels[] = 'chat.admin';
        }

        if ($this->message->sender_type === 'recruiter') {
            $channels[] = 'chat.recruiter';
            $channels[] = 'chat.admin';
        }

        if ($this->message->receiver_type) {
            $channels[] = 'chat.' . $this->message->receiver_type;
        }

        // ✅ Duplicate hata do
        $channels = array_unique($channels);

        return array_map(fn($ch) => new Channel($ch), $channels);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith()
    {
        $createdAt = $this->message->created_at;

        if (!($createdAt instanceof Carbon)) {
            $createdAt = Carbon::parse($createdAt);
        }

        return [
            'id'           => $this->message->id,
            'sender_id'    => $this->message->sender_id,
            'sender_type'  => $this->message->sender_type,
            'receiver_id'  => $this->message->receiver_id,
            'receiver_type'=> $this->message->receiver_type,
            'message'      => $this->message->message,
            'type'         => $this->message->type,
            'created_at'   => $createdAt->toDateTimeString(),
        ];
    }
}
