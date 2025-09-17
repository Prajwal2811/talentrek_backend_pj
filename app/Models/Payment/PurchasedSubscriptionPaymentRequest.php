<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedSubscriptionPaymentRequest extends Model
{
    use HasFactory;

    // Table name (since it's not plural 'purchase_subscription_payment_requests')
    protected $table = 'purchased_subscription_payment_requests';

    // Primary key
    protected $primaryKey = 'id';

    // Auto-increment
    public $incrementing = true;

    // Primary key type
    protected $keyType = 'int';

    // Laravel timestamps (true if using created_at & updated_at)
    public $timestamps = true;

    // Mass assignable fields
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

    // Cast JSON fields automatically
    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'amount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Example relationships (if you have related models):
     */

    // Subscription Plan relationship
    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }
}
