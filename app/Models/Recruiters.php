<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recruiters extends Model
{
    use HasFactory;

    protected $table = 'recruiters';


    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'pass',
    ];

    /**
     * The attributes that should be hidden when serialized.
     */
    protected $hidden = [
        'password',
        'pass',
    ];
}
