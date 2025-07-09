<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruiterJobseekersShortlist extends Model
{
    use HasFactory;

    protected $table = 'recruiter_jobseeker_shortlist';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'recruiter_id',
        'jobseeker_id',
        'status',
        'interview_request',
        'admin_status',
        'interview_url',
    ];

   

}
