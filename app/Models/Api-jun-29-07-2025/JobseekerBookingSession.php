<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobseekerBookingSession extends Model
{
    use HasFactory;

    // Explicitly set table name (optional if naming follows convention)
    protected $table = 'jobseeker_saved_booking_session';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [        
        'jobseeker_id',
        'user_type',
        'user_id',
        'booking_slot_id',
        'slot_mode',
        'slot_date',
        'status',
        'admin_status',
        'is_postpone',
        'slot_date_after_postpone',
        'slot_time',
        'slot_time_after_postpone',
        'cancellation_reason',
        'cancelled_at',
        'rescheduled_at',
        'zoom_meeting_id',
        'zoom_join_url',
        'zoom_start_url'
    ];

    public function mentorLatestWorkExperience()
    {
        return $this->hasOne(WorkExperience::class, 'user_id', 'id') // user_id in work_experience = trainer's id
            ->where('user_type', 'mentor') // filter only trainer-type
            ->orderByRaw('ABS(DATEDIFF(end_to, CURDATE()))') // closest to today
            ->select('user_id', 'job_role', 'end_to');
    }

    public function mentorAdditionalInfo()
    {
        return $this->hasMany(AdditionalInfo::class, 'user_id', 'id')
                    ->where('user_type', 'mentor');
    }

    public function coachLatestWorkExperience()
    {
        return $this->hasOne(WorkExperience::class, 'user_id', 'id') // user_id in work_experience = trainer's id
            ->where('user_type', 'coach') // filter only trainer-type
            ->orderByRaw('ABS(DATEDIFF(end_to, CURDATE()))') // closest to today
            ->select('user_id', 'job_role', 'end_to');
    }

    public function coachAdditionalInfo()
    {
        return $this->hasMany(AdditionalInfo::class, 'user_id', 'id')
                    ->where('user_type', 'coach');
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
}
