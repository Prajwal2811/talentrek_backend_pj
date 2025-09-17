<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedSubscription extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'purchased_subscriptions';
    protected $fillable = [
        'subscription_plan_id',
        'user_id',
        'user_type',
        'company_id',

        // Dates
        'start_date',
        'end_date',

        // Payment info
        'amount_paid',
        'payment_status',

        // Extra gateway details
        'transaction_id',
        'payment_id',
        'track_id',
        'order_id',
        'currency',
        'result',
        'coupon_type',
        'coupon_code',
        'coupon_amount',
        'order_id',
        'response_payload',
        'raw_response',
    ];


   
}


