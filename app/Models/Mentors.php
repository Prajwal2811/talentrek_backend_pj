<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 

class Mentors extends Authenticatable
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
        'password',
        'pass',
        'otp',
        'national_id',
        'status',
        'admin_status',
        'about_mentor',

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
                    ->where('user_type', 'mentor');
    }
    public function experiences()
    {

        return $this->hasMany(WorkExperience::class, 'user_id')
                    ->where('user_type', 'mentor');
    }

    public function trainingexperience()
    {
        return $this->hasMany(TrainingExperience::class, 'user_id');
       
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id')->where('user_type', 'mentor');
    }


    public function additionalInfo()
    {
        return $this->hasOne(AdditionalInfo::class, 'user_id')->where('user_type', 'mentor');
    }

    public function profilePicture()
    {
        return $this->hasOne(AdditionalInfo::class, 'user_id')
                    ->where('user_type', 'mentor')
                    ->where('doc_type', 'mentor_profile_picture');
    }


    public function bookingSlots()
    {
        return $this->hasMany(BookingSlot::class, 'user_id')->where('user_type', 'mentor');
    }


    


}
