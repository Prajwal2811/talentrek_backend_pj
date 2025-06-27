<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Jobseekers extends Authenticatable
{
    use HasFactory;

    protected $table = 'jobseekers';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'gender',
        'phone_code',
        'phone_number',
        'date_of_birth',
        'city',
        'address',
        'password',
        'pass',
        'role',
        'otp',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * The attributes that should be hidden for arrays and JSON.
     */
    protected $hidden = [
        'password',
        'pass',
    ];

    // public function educations()
    // {
    //     return $this->hasMany(EducationDetails::class);
    // }

    // public function experience()
    // {
    //     return $this->hasMany(WorkExperience::class);
    // }

    // public function skills()
    // {
    //     return $this->hasMany(Skills::class);
    // }

    public function educations()
    {
        return $this->hasMany(EducationDetails::class, 'user_id');
    }

    public function experience()
    {
        return $this->hasMany(WorkExperience::class, 'user_id');
    }

    public function skills()
    {
        return $this->hasMany(Skills::class, 'jobseeker_id');
    }

}
