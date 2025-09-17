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
        'user_id',
        'user_type',
        'amount_paid',
        'payment_status',
        'subscription_plan_id',
        'subscribable_id',
        'subscribable_type',
        'start_date',
        'end_date',
        'company_id'
    ];

   
}


