<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedSubscriptionPaymentRequest extends Model
{
    use HasFactory;

    protected $table = 'purchased_subscription_payment_requests';
    protected $fillable = [
        'subscription_plan_id',
        'user_id',
        'user_type',
        'status',
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
    ];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }
}
