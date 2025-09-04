<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentJobseekerDataStatus extends Model
{
    use HasFactory;

    protected $table = 'jobseeker_assessment_status';
    protected $fillable = [
        'jobseeker_id',
        'assessment_id',
        'submitted',    
];    
}
