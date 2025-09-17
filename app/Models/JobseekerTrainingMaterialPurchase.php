<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobseekerTrainingMaterialPurchase extends Model
{
    use HasFactory;

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
        'transaction_id',
        'status',
        'amount',
        'tax',
        'discount',
        'coupon_type',
        'coupon_code',
        'coupon_amount',
        'member_count',
        'order_id',
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
