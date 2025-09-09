<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSession extends Model
{
   use HasFactory;

    protected $table = 'jobseeker_saved_booking_session';

    protected $fillable = [
        'jobseeker_id',
        'user_type',
        'user_id',
        'booking_slot_id',
        'slot_date',
        'slot_mode',
        'slot_time',
        'status',
        'admin_status',
        'is_postpone',
        'slot_date_after_postpone',
        'slot_time_after_postpone',
        'cancellation_reason',
        'zoom_start_url',
        'zoom_join_url',
        'zoom_access_token',
        'zoom_refresh_token',
        'zoom_token_expires_at',
        'amount',
        'tax',
        'total_amount',
        'response_payload',
        'payment_status',
        'track_id',
        'transaction_id'


    ];

    // Relationships
    

    // public function bookingSlot()
    // {
    //     return $this->belongsTo(BookingSlot::class);
    // }

    public function user()
    {
        return $this->morphTo(__FUNCTION__, 'user_type', 'user_id');
    }

    public function bookingSlot()
    {
        return $this->belongsTo(BookingSlot::class, 'booking_slot_id');
    }
    
    // ──────── JOBSEEKER RELATIONS ────────
    public function jobseeker()
    {
        return $this->hasOne(Jobseekers::class, 'id','jobseeker_id');
         
    }

    public function jobseekerLatestWorkExperience()
    {
        return $this->hasOne(WorkExperience::class, 'user_id', 'jobseeker_id') // user_id in work_experience = trainer's id
            ->where('user_type', 'jobseeker') // filter only trainer-type
            ->orderByRaw('ABS(DATEDIFF(end_to, CURDATE()))') // closest to today
            ->select('user_id', 'job_role', 'end_to');
    }

    public function jobseekerWorkExperience()
    {
        return $this->hasMany(WorkExperience::class, 'user_id', 'jobseeker_id')
        ->where('user_type', 'jobseeker');
    }

    public function jobseekerAdditionalInfo()
    {
        return $this->hasMany(AdditionalInfo::class, 'user_id', 'jobseeker_id')
                    ->where('user_type', 'jobseeker')->where('doc_type', 'profile_picture');
    }

    
    // ──────── MENTOR RELATIONS ────────
    public function mentors()
    {
        return $this->hasOne(Mentors::class,'id', 'user_id') ;
    }
    
    public function mentorLatestWorkExperience()
    {
        return $this->hasOne(WorkExperience::class, 'user_id', 'user_id') // user_id in work_experience = trainer's id
            ->where('user_type', 'mentor') // filter only trainer-type
            ->orderByRaw('ABS(DATEDIFF(end_to, CURDATE()))') // closest to today
            ->select('user_id', 'job_role', 'end_to');
    }

    public function WorkExperience()
    {
        return $this->hasMany(WorkExperience::class, 'user_id', 'user_id')
        ->where('user_type', 'mentor');
    }

    public function mentorAdditionalInfo()
    {
        return $this->hasMany(AdditionalInfo::class, 'user_id', 'id')
                    ->where('user_type', 'mentor');
    }

    // ──────── COACHES RELATIONS ────────
    public function coaches()
    {
        return $this->hasOne(Coach::class,'id', 'user_id') ;
    }

    public function coachLatestWorkExperience()
    {
        return $this->hasOne(WorkExperience::class, 'user_id', 'id') // user_id in work_experience = trainer's id
            ->where('user_type', 'coach') // filter only trainer-type
            ->orderByRaw('ABS(DATEDIFF(end_to, CURDATE()))') // closest to today
            ->select('user_id', 'job_role', 'end_to');
    }

    public function coachWorkExperience()
    {
        return $this->hasMany(WorkExperience::class, 'user_id', 'user_id')
        ->where('user_type', 'coach');
    }

    public function coachAdditionalInfo()
    {
        return $this->hasMany(AdditionalInfo::class, 'user_id', 'id')
                    ->where('user_type', 'coach');
    }

    // ──────── ASSESSOR RELATIONS ────────
    public function assessors()
    {
        return $this->hasOne(Assessors::class,'id', 'user_id') ;
    }

    public function assessorLatestWorkExperience()
    {
        return $this->hasOne(WorkExperience::class, 'user_id', 'id') // user_id in work_experience = trainer's id
            ->where('user_type', 'assessor') // filter only trainer-type
            ->orderByRaw('ABS(DATEDIFF(end_to, CURDATE()))') // closest to today
            ->select('user_id', 'job_role', 'end_to');
    }

    public function assessorAdditionalInfo()
    {
        return $this->hasMany(AdditionalInfo::class, 'user_id', 'id')
        
        ->where('user_type', 'assessor');
    }

    public function AssessorWorkExperience()
    {
        return $this->hasMany(WorkExperience::class, 'user_id', 'user_id')
        ->where('user_type', 'assessor');
    }
}
