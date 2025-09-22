<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentQuestion extends Model
{
    use HasFactory;

    protected $table = 'assessment_questions';
    protected $fillable = [
        'trainer_id',
        'assessment_id',
        'questions_title',
    ];

    public function assessment()
    {
        return $this->belongsTo(TrainerAssessment::class, 'assessment_id');
    }

    public function options()
    {
        return $this->hasMany(AssessmentOption::class, 'question_id');
    }
}
