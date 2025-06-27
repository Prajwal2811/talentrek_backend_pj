<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessors extends Model
{
    use HasFactory;

    // Explicitly set table name (optional if naming follows convention)
    protected $table = 'assessors';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_name',
        'company_email',
        'phone_code',
        'company_phone_number',
        'company_instablishment_date',
        'industry_type',
        'company_website',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'company_instablishment_date' => 'date',
    ];
}
