<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentors extends Model
{
    use HasFactory;

    protected $table = 'mentors';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone_code',
        'phone_number',
        'date_of_birth',
        'city',
        'pin_code',
        'country',
        'state',
        'national_id',
        'pin_code',
        'about_mentor'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    
    // Relationship with reviews (assuming `mentor_id` is foreign key in reviews)
    public function mentorReviews()
    {
        return $this->hasMany(Review::class, 'user_id')->where('user_type', 'mentor');
    }

    public function mentorEducations()
    {
        return $this->hasMany(EducationDetails::class, 'user_id')->where('user_type', 'mentor');
    }

    public function latestWorkExperience()
    {
        return $this->hasOne(WorkExperience::class, 'user_id', 'id') // user_id in work_experience = trainer's id
            ->where('user_type', 'mentor') // filter only trainer-type
            ->orderByRaw('ABS(DATEDIFF(end_to, CURDATE()))') // closest to today
            ->select('user_id', 'job_role', 'end_to');
    }

    public function WorkExperience()
    {
        return $this->hasMany(WorkExperience::class, 'user_id', 'id')
        ->where('user_type', 'mentor');
    }

    public function additionalInfo()
    {
        return $this->hasOne(AdditionalInfo::class, 'user_id', 'id')
                    ->where('user_type', 'mentor')->where('doc_type', 'mentor_profile_picture');
    }

}
