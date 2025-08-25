<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessors extends Model
{
    use HasFactory;

    // Explicitly set table name (optional if naming follows convention)
    protected $table = 'assessors';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone_code',
        'phone_number',
        'instablishment_date',
        'industry_type',
        'website',
        'pin_code',
        'country',
        'state',
        'national_id',
        'about_assessor'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'company_instablishment_date' => 'date',
    ];

    public function assessorReviews()
    {
        return $this->hasMany(Review::class, 'user_id')->where('user_type', 'assessor');
    }

    public function assessorEducations()
    {
        return $this->hasMany(EducationDetails::class, 'user_id')->where('user_type', 'assessor');
    }

    public function latestWorkExperience()
    {
        return $this->hasOne(WorkExperience::class, 'user_id', 'id') // user_id in work_experience = trainer's id
            ->where('user_type', 'assessor') // filter only trainer-type
            ->orderByRaw('ABS(DATEDIFF(end_to, CURDATE()))') // closest to today
            ->select('user_id', 'job_role', 'end_to');
    }

    public function WorkExperience()
    {
        return $this->hasMany(WorkExperience::class, 'user_id', 'id')
        ->where('user_type', 'assessor');
    }

    public function additionalInfo()
    {
        return $this->hasOne(AdditionalInfo::class, 'user_id', 'id')
                    ->where('user_type', 'assessor')->where('doc_type', 'assessor_profile_picture');
    }
}
