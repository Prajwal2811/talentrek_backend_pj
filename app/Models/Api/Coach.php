<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
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
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];
}
