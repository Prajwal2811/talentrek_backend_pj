<?php

namespace App\Models\Api;

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
        'start_date',
        'end_date',
        'amount_paid',
        'payment_status',
        'transaction_id',
        'payment_id',
        'track_id',
        'order_id',
        'currency',
        'result',
        'response_payload',
        'raw_response',
    ];

}


