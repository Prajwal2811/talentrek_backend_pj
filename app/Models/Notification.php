<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $fillable = [
        'jobseeker_id',
        'course_id',
        'payment_reference',
        'amount_paid',
        'payment_status',
        'payment_method',
        'paid_at',
        'transaction_id',
        'subscription_plan_id',

    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    // Relationships

}
