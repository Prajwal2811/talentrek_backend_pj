<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'payments_history';

    protected $fillable = [
        'user_type',        // payer type (jobseeker, trainer, etc.)
        'user_id',          // payer id
        'receiver_type',    // always "talentrek" for subscription
        'receiver_id',      // can be null or fixed
        'payment_for',      // training, booking_slot, subscription
        'amount_paid',
        'payment_status',   // pending, completed, failed, refunded
        'transaction_id',
        'track_id',
        'order_id',
        'currency',
        'payment_method',
        'paid_at',
    ];


    protected $casts = [
        'paid_at' => 'datetime',
    ];

    // Relationships

    public function jobseeker()
    {
        return $this->belongsTo(Jobseekers::class);
    }

    public function course()
    {
        return $this->belongsTo(TrainingMaterial::class, 'course_id');
    }
}
