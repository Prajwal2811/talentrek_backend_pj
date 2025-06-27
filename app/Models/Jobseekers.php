<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobseekers extends Model
{
    use HasFactory;

    protected $table = 'jobseekers';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'assigned_admin',
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
        'status',
        'inactive_reason',
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

    public function educations()
    {
        return $this->hasMany(EducationDetails::class, 'user_id')
                    ->where('user_type', 'jobseeker');
    }

    public function experiences()
    {
        return $this->hasMany(WorkExperience::class, 'user_id')
                    ->where('user_type', 'jobseeker');
    }

    public function skills()
    {
        return $this->hasMany(Skills::class, 'jobseeker_id');
    }

}
