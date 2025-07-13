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
                    ->where('user_type', 'mentors');
    }
    public function experiences()
    {

        return $this->hasMany(WorkExperience::class, 'user_id')
                    ->where('user_type', 'mentors');
    }

    public function trainingexperience()
    {
        return $this->hasMany(TrainingExperience::class, 'user_id');
       
    }

}
