<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobseekerTrainingMaterialPurchasePaymentRequest extends Model
{
    use HasFactory;

    // Table name (since it’s long and not standard pluralization)
    protected $table = 'jobseeker_training_material_purchases_payment_request';

    // Mass assignable attributes
    protected $fillable = [
        'jobseeker_id',
        'trainer_id',
        'material_id',
        'batch_id',
        'request_payload',
        'track_id',
        'type',
        'training_type',
        'transaction_id',
        'payment_status',
        'tax',
        'amount',
        'amount_paid',
        'currency',
        'payment_gateway',
        'coupon_type',
        'coupon_code',
        'coupon_amount',
    ];

    // Casts for automatic conversion
    protected $casts = [
        'request_payload' => 'array',
        'tax' => 'decimal:2',
        'amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
    ];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */

    public function jobseeker()
    {
        return $this->belongsTo(Jobseekers::class, 'jobseeker_id');
    }

    public function trainer()
    {
        return $this->belongsTo(Trainers::class, 'trainer_id');
    }

    public function material()
    {
        return $this->belongsTo(TrainingMaterial::class, 'material_id');
    }

    public function batch()
    {
        return $this->belongsTo(TrainingBatch::class, 'batch_id');
    }
}
