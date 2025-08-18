<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 

class Assessors extends Authenticatable
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
        'national_id',
        'phone_code',
        'phone_number',
        'date_of_birth',
        'city',
        'password',
        'pass',
        'otp',
        'status',
        'admin_status',
        'inactive_reason',
        'rejection_reason',
        'shortlist',
        'admin_recruiter_status',
        'google_id',
        'avatar',
        'about_assessor',
        'isSubscribtionBuy',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];


    public function educations()
    {
        return $this->hasMany(EducationDetails::class, 'user_id')
                    ->where('user_type', 'assessor');
    }
    public function experiences()
    {

        return $this->hasMany(WorkExperience::class, 'user_id')
                    ->where('user_type', 'assessor');
    }

    public function trainingexperience()
    {
        return $this->hasMany(TrainingExperience::class, 'user_id');
       
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id')->where('user_type', 'assessor');
    }


    public function additionalInfo()
    {
        return $this->hasOne(AdditionalInfo::class, 'user_id')->where('user_type', 'assessor');
    }

    public function profilePicture()
    {
        return $this->hasOne(AdditionalInfo::class, 'user_id')
                    ->where('user_type', 'assessor')
                    ->where('doc_type', 'assessor_profile_picture');
    }


    public function bookingSlots()
    {
        return $this->hasMany(BookingSlot::class, 'user_id')->where('user_type', 'assessor');
    }
}
