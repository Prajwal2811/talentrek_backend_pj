<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 

class Coach extends Authenticatable
{
    use HasFactory;

    // Optional: specify table if not plural of class
    protected $table = 'coaches';

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
        'about_coach',
        'isSubscribtionBuy', // Added to match migration
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];
}
