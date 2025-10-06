<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobseekerTrainingAssessmentTime extends Model
{
    use HasFactory;

    protected $table = 'jobseeker_training_assessment_times';

    protected $fillable = [
        'jobseeker_id',
        'trainer_id',
        'material_id',
        'start_time',
        'end_time',
        'total_duration',
        'remaining_time',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];
}
