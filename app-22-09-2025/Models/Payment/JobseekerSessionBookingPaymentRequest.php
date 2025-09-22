<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobseekerSessionBookingPaymentRequest extends Model
{
    use HasFactory;

    protected $table = 'jobseeker_sessions_booking_payment_request';

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
        'amount_paid',
        'currency',
        'payment_gateway',
        'transaction_id',
        'payment_status',
        'response_payload',
        'request_payload',
        'tax_percentage',
        'taxed_amount',    
        'coupon_type',
        'coupon_code',
        'coupon_amount',
        'order_id',     
        'total_amount'
        
    ];

    protected $casts = [
        'slot_date' => 'date',
        'reserved_until' => 'datetime',
        'response_payload' => 'array',
    ];
}
