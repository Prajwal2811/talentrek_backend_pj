<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionBooking extends Model
{
    use HasFactory;

    // Explicit table name
    protected $table = 'jobseeker_saved_booking_session';

    // Fillable fields
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
        'track_id',
        'transaction_id',
        'payment_status',
        'response_payload',
        'slot_amount',
        'tax_percentage',
        'taxed_amount',
        'amount_paid',
    ];

    // Cast JSON and dates
    protected $casts = [
        'response_payload'        => 'array',
        'slot_date'               => 'date',
        'slot_date_after_postpone'=> 'date',
        'cancelled_at'            => 'datetime',
        'rescheduled_at'          => 'datetime',
        'is_postpone'             => 'boolean',
    ];
}
