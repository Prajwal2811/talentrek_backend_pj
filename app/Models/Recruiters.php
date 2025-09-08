<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Recruiters extends Authenticatable
{
    use HasFactory;

    protected $table = 'recruiters';


    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone_number',
        'national_id',
        'password',
        'pass',
        'otp',
        'role',
        'status',
        'inactive_reason',
        'admin_status',
        'rejection_reason',
        'recruiter_of',
        'is_registered'
    ];

    /**
     * The attributes that should be hidden when serialized.
     */
    protected $hidden = [
        'password',
        'pass',
    ];

    // One-to-One relationship
    public function company()
    {
        return $this->belongsTo(RecruiterCompany::class, 'company_id');
    }

    
}
