<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentJobseekerData extends Model
{
    use HasFactory;

    protected $table = 'jobseeker_assessment_data';
    protected $fillable = [
        'trainer_id',
        'jobseeker_id',
        'training_id',
        'selected_answer',
        'assessment_id',
        'question_id',
        'correct_answer'    
];    
}
