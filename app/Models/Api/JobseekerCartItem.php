<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobseekerCartItem extends Model
{
    use HasFactory;

    protected $table = 'jobseeker_cart_items';
    protected $fillable = [
        'jobseeker_id',
        'trainer_id',
        'material_id',
        'status',
    ];

    // Relationships (optional)
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

    public function trainerReviews()
    {
        return $this->hasMany(Review::class, 'trainer_material', 'material_id')
                    ->where('user_type', 'trainer');
    } 

    public function latestWorkExperience()
    {
        return $this->hasOne(WorkExperience::class, 'user_id', 'id') // user_id in work_experience = trainer's id
            ->where('user_type', 'trainer') // filter only trainer-type
            ->orderByRaw('ABS(DATEDIFF(end_to, CURDATE()))') // closest to today
            ->select('user_id', 'job_role', 'end_to');
    }
}
