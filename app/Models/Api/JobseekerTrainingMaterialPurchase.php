<?php

namespace App\Models\Api;

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
    ];

    // Relationships
    public function jobseeker()
    {
        return $this->belongsTo(Jobseekers::class);
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

    public function WorkExperience()
    {
        return $this->hasMany(WorkExperience::class, 'user_id', 'jobseeker_id')
        ->where('user_type', 'jobseeker');
    }

}
