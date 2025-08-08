<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
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


