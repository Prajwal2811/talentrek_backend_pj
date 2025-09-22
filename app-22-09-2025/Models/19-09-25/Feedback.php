<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'sender_type',
        'to_id',
        'to_type',
        'feedback_message',
    ];

    /**
     * Get the sender (Jobseeker, Recruiter, Admin, etc.)
     */
    public function sender()
    {
        return $this->morphTo();
    }

    /**
     * Get the receiver (who received the feedback)
     */
    public function to()
    {
        return $this->morphTo(__FUNCTION__, 'to_type', 'to_id');
    }
}
