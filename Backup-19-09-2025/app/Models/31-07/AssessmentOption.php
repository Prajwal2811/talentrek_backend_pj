<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentOption extends Model
{
    use HasFactory;
    
    protected $table = 'assessment_options';
    protected $fillable = [
        'trainer_id',
        'assessment_id',
        'question_id',
        'options',
        'correct_option',
    ];

    protected $casts = [
        'correct_option' => 'boolean',
    ];

    public function options()
    {
        return $this->hasOne(AssessmentOption::class, 'question_id');
    }

    public function assessment()
    {
        return $this->belongsTo(TrainerAssessment::class, 'assessment_id');
    }

     // Optional
    public function trainer()
    {
        return $this->belongsTo(Trainers::class, 'trainer_id');
    }
}

