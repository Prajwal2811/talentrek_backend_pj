<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobseekerAssessmentData extends Model
{
    use HasFactory;

    protected $table = 'jobseeker_assessment_data';

    protected $fillable = [
        'trainer_id',
        'training_id',
        'assessment_id',
        'question_id',
        'jobseeker_id',
        'selected_answer',
        'correct_answer', // <-- ADD THIS
    ];

}
