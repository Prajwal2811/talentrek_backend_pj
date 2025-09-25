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
        'tax_percentage',
        'tax',
        'taxed_amount',
        'total_amount',
        'amount_paid',
        'order_id',
        'coupon_type',
        'coupon_code',
        'coupon_amount',
        'currency',
        'payment_gateway',
        'request_payload',
        'transaction_id',
        'payment_status',
        'response_payload',
    ];

    protected $casts = [
        'request_payload'    => 'array',
        'response_payload'   => 'array',
        'slot_date'          => 'date',
        'reserved_until'     => 'datetime',
        'amount'             => 'decimal:2',
        'tax_percentage'     => 'decimal:2',
        'tax'                => 'decimal:2',
        'taxed_amount'       => 'decimal:2',
        'total_amount'       => 'decimal:2',
        'amount_paid'        => 'decimal:2',
        'coupon_amount'      => 'decimal:2',
    ];
}
