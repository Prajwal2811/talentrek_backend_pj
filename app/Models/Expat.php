<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class Expat extends Authenticatable
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
        'state',
        'address',
        'password',
        'pin_code',
        'country',
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
        'isSubscribtionBuy',
        'is_registered',
        'avatar',
        'active_subscription_plan_id',
        'zoom_access_token',
        'zoom_refresh_token',
        'zoom_token_expires_at'
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
                    ->where('user_type', 'expat');
    }

    public function experiences()
    {

        return $this->hasMany(WorkExperience::class, 'user_id')
                    ->where('user_type', 'expat');
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

            // Handle ongoing jobs
            $end = $exp->end_to && strtolower($exp->end_to) !== 'work here' && strtolower($exp->end_to) !== 'present'
                ? Carbon::parse($exp->end_to)
                : now();

            $totalDays += $start->diffInDays($end);
        }

        $years = floor($totalDays / 365);
        $months = floor(($totalDays % 365) / 30);
        // $days = $totalDays % 30; // optional

        return "$years years, $months months";
    }

    public function payments()
    {
        return $this->hasMany(PaymentHistory::class);
    }

    public function profilePicture()
    {
        return $this->hasOne(AdditionalInfo::class, 'user_id')
            ->where('user_type', 'expat')
            ->where('doc_type', 'profile_picture');
    }
    

}
