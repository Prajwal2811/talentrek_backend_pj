<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobseekerTrainingMaterialPurchase extends Model
{
    use HasFactory;

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



    // Relationships
    public function jobseeker()
    {
        return $this->belongsTo(Jobseekers::class, 'jobseeker_id');
    }

    public function profilePicture()
    {
        return $this->hasOne(AdditionalInfo::class, 'user_id')
            ->where('user_type', 'jobseeker')
            ->where('doc_type', 'profile_picture');
    }



    public function trainer()
    {
        return $this->belongsTo(Trainers::class);
    }

    public function material()
    {
        return $this->belongsTo(TrainingMaterial::class, 'material_id');
    }

    public function batch()
    {
        return $this->belongsTo(TrainingBatch::class, 'batch_id');
    }

    public function payment()
    {
        return $this->belongsTo(PaymentHistory::class, 'payment_id');
    }

    public function members()
    {
        return $this->hasMany(TeamCourseMember::class, 'purchase_id');
    }
}
