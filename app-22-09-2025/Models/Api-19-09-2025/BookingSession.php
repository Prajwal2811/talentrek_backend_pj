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
        'slot_mode',
        'slot_date',
        'slot_time',
        'status',
        'admin_status',
        'is_postpone',
        'slot_date_after_postpone',
        'slot_time_after_postpone',
        'cancellation_reason',
        'cancelled_at',
        'rescheduled_at',

        // Zoom
        'zoom_meeting_id',
        'zoom_join_url',
        'zoom_start_url',
        'zoom_access_token',
        'zoom_refresh_token',
        'zoom_token_expires_at',

        // Coupon
        'coupon_type',
        'coupon_code',
        'coupon_amount',

        // Payment info
        'order_id',
        'track_id',        // Unique booking reference number
        'transaction_id',  // From payment provider
        'payment_status',
        'response_payload',

        // Amounts
        'slot_amount',
        'tax_percentage',
        'taxed_amount',
        'amount_paid',

    ];

    // Relationships
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
        return $this->hasOne(Jobseekers::class, 'id', 'jobseeker_id');
    }

    public function jobseekerLatestWorkExperience()
    {
        return $this->hasOne(WorkExperience::class, 'user_id', 'jobseeker_id')
            ->where('user_type', 'jobseeker')
            ->orderByRaw('ABS(DATEDIFF(end_to, CURDATE()))')
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
            ->where('user_type', 'jobseeker')
            ->where('doc_type', 'profile_picture');
    }

    // ──────── MENTOR RELATIONS ────────
    public function mentors()
    {
        return $this->hasOne(Mentors::class, 'id', 'user_id');
    }

    public function mentorLatestWorkExperience()
    {
        return $this->hasOne(WorkExperience::class, 'user_id', 'user_id')
            ->where('user_type', 'mentor')
            ->orderByRaw('ABS(DATEDIFF(end_to, CURDATE()))')
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
        return $this->hasOne(Coach::class, 'id', 'user_id');
    }

    public function coachLatestWorkExperience()
    {
        return $this->hasOne(WorkExperience::class, 'user_id', 'id')
            ->where('user_type', 'coach')
            ->orderByRaw('ABS(DATEDIFF(end_to, CURDATE()))')
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
        return $this->hasOne(Assessors::class, 'id', 'user_id');
    }

    public function assessorLatestWorkExperience()
    {
        return $this->hasOne(WorkExperience::class, 'user_id', 'id')
            ->where('user_type', 'assessor')
            ->orderByRaw('ABS(DATEDIFF(end_to, CURDATE()))')
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
