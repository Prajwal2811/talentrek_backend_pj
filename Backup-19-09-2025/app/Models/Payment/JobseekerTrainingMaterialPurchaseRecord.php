<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobseekerTrainingMaterialPurchaseRecord extends Model
{
    use HasFactory;

    protected $table = 'jobseeker_training_material_purchases_payment_record';

    protected $fillable = [
        'jobseeker_id',
        'trainer_id',
        'material_id',
        'training_type',
        'session_type',
        'batch_id',
        'purchase_for',
        'payment_id',
        'batchStatus',
        'status',

        // Amount fields
        'tax_percentage',
        'taxed_amount',
        'amount_paid',

        // Coupon fields
        'coupon_type',
        'coupon_code',
        'coupon_amount',

        // Order & Tracking
        'order_id',
        'track_id',
        'transaction_id',

        // Payment fields
        'payment_status',
        'response_payload',

        // Others
        'member_count',
        'created_at',
        'updated_at',
    ];

}
