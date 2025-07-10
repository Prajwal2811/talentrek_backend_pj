<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class Jobseekers extends Authenticatable
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
        'national_id',
        'city',
        'address',
        'password',
        'pass',
        'role',
        'otp',
        'status',
        'inactive_reason',
        'admin_status',
        'rejection_reason', 
        'shortlist',
        'admin_recruiter_status',
        'google_id',
        'isSubscribtionBuy'
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


    public function getTotalExperienceAttribute()
    {
        $totalDays = 0;

        foreach ($this->experiences as $exp) {
            $start = Carbon::parse($exp->starts_from);
            $end = Carbon::parse($exp->end_to);
            $totalDays += $start->diffInDays($end);
        }

        $years = floor($totalDays / 365);
        $months = floor(($totalDays % 365) / 30);
        $days = $totalDays % 30;

        return "$years years, $months months";
    }

}
