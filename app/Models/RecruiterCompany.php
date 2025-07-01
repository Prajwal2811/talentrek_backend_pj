<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class RecruiterCompany extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Specify the table name if it's not the plural of the model name
    protected $table = 'recruiters_company';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'recruiter_id',
        'company_name',
        'company_website',
        'company_city',
        'company_address',
        'business_email',
        'phone_code',
        'company_phone_number',
        'no_of_employee',
        'industry_type',
        'registration_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function recruiter()
    {
        return $this->belongsTo(Recruiters::class, 'recruiter_id');
    }
}
