<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSession extends Model
{
   use HasFactory;

    protected $table = 'jobseeker_saved_booking_session';

    protected $fillable = [
        'jobseeker_id',
        'user_type',
        'user_id',
        'booking_slot_id',
        'slot_date',
        'slot_mode',
        'slot_time',
        'status',
        'admin_status',
        'is_postpone',
        'slot_date_after_postpone',
        'slot_time_after_postpone',
        'cancellation_reason',
    ];

    // Relationships
    public function jobseeker()
    {
        return $this->belongsTo(Jobseekers::class);
    }

    public function bookingSlot()
    {
        return $this->belongsTo(BookingSlot::class);
    }

    public function user()
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'user_id');
    }
}
