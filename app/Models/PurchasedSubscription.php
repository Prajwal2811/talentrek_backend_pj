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
        'price',
        'status',
        'payment_status',
        'subscription_plan_id',
        'subscribable_id',
        'subscribable_type',
        'start_date',
        'end_date',
    ];

    public function subscribable()
    {
        return $this->morphTo();
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
}


