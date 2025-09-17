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
        'slot_mode',
        'slot_date',
        'slot_time',
        'status',
        'admin_status',
        'is_postpone',
        'slot_date_after_postpone',
        'slot_time_after_postpone',
        'cancellation_reason',
        'cancelled_at',
        'rescheduled_at',
        'zoom_meeting_id',
        'zoom_join_url',
        'zoom_start_url',
        'coupon_type',
        'coupon_code',
        'coupon_amount',
        'order_id',
    ];


    // Relationships
    public function jobseeker()
    {
        return $this->belongsTo(Jobseekers::class);
    }

    // public function bookingSlot()
    // {
    //     return $this->belongsTo(BookingSlot::class);
    // }

    public function user()
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'user_id');
    }

    public function bookingSlot()
    {
        return $this->belongsTo(BookingSlot::class, 'booking_slot_id');
    }

    public function mentor()
    {
        return $this->belongsTo(Mentors::class, 'user_id', 'id');
    }

    public function assessor()
    {
        return $this->belongsTo(Assessors::class, 'user_id', 'id');
    }
    
    public function coach()
    {
        return $this->belongsTo(Coach::class, 'user_id', 'id');
    }
}
