<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobseekerAssessmentStatus extends Model
{
    use HasFactory;

    protected $table = 'jobseeker_assessment_status';

     protected $fillable = ['jobseeker_id', 'assessment_id', 'submitted','score','total','percentage','result_status'];
    /**
     * Relationships
     */
    public function jobseeker()
    {
        return $this->belongsTo(Jobseekers::class);
    }

    public function assessment()
    {
        return $this->belongsTo(TrainerAssessment::class, 'assessment_id');
    }
}
