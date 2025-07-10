<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingMaterial extends Model
{
    use HasFactory;

    protected $table = 'training_materials';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'trainer_id',              // FK to trainers table
        'training_type' ,                       // e.g., Online, Offline, Hybrid
        'training_title',
        'training_sub_title',
        'training_descriptions',
        'training_category' ,       // e.g., Technical, Soft Skills
        'training_price',  // e.g., 299.99
        'thumbnail_file_path',      // Path to stored image
        'thumbnail_file_name',      // Original filename
        'training_objective',         // Objectives
        'session_type',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // In TrainingMaterial.php model
    public function trainer()
    {
        return $this->belongsTo(Trainers::class, 'trainer_id', 'id');
    }    

    public function trainerUserId()
    {
        return $this->belongsTo(WorkExperience::class, 'user_id', 'id');
    }

    public function trainerReviews()
    {
        return $this->hasMany(Review::class, 'trainer_material', 'id')
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
