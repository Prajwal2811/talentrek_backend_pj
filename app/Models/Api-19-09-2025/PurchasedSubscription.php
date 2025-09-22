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
    protected $table = 'jobseeker_training_material_purchases';
    protected $fillable = [
        'jobseeker_id',
        'trainer_id',
        'material_id',
        'purchased_by',
        'training_type',
        'session_type',
        'batch_id',
        'purchase_for',
        'payment_id',
        'batchStatus',
        'status',
        'tax_percentage',
        'taxed_amount',
        'amount_paid',
        'coupon_type',
        'coupon_code',
        'coupon_amount',
        'order_id',
        'track_id',
        'transaction_id',
        'payment_status',
        'response_payload',
        'member_count',
    ];   
}


