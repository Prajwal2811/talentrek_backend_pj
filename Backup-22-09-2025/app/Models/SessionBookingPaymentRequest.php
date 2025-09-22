<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionBookingPaymentRequest extends Model
{
    use HasFactory;

    // Explicit table name
    protected $table = 'jobseeker_sessions_booking_payment_request';

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
        'reserved_until',
        'track_id',
        'amount',
        'tax',
        'total_amount',
        'currency',
        'payment_gateway',
        'request_payload',
        'transaction_id',
        'payment_status',
        'response_payload',
        'tax_percentage',
        'coupon_type',
        'coupon_code',
        'coupon_amount'
    ];

    // Cast JSON fields to array
    protected $casts = [
        'request_payload'  => 'array',
        'response_payload' => 'array',
        'slot_date'        => 'date',
        'reserved_until'   => 'datetime',
    ];
}
