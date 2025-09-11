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
        'user_id',
        'user_type',
        'company_id',

        // Subscription info
        'subscription_plan_id',
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
        'raw_response',
    ];

   
}


