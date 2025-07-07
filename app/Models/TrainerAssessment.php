<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainerAssessment extends Model
{
    use HasFactory;
    protected $table = 'trainer_assessments';

    protected $fillable = [
        'trainer_id',
        'assessment_title',
        'assessment_description',
        'assessment_level',
        'total_questions',
        'passing_questions',
        'passing_percentage',
        'course_id',
    ];

    // Relationships
    public function questions()
    {
        return $this->hasMany(AssessmentQuestion::class, 'assessment_id');
    }

    public function trainer()
    {
        return $this->belongsTo(Trainers::class, 'trainer_id'); // Assuming trainers are users
    }

    public function course()
    {
        return $this->belongsTo(TrainingMaterial::class);
    }
}
