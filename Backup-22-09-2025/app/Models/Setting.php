<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'site_settings'; // explicitly define table name

    protected $fillable = [
        'header_logo',
        'footer_logo',
        'favicon',
        'trainingMaterialTax',
        'trainingMaterialBatchTax',
        'coachTax',
        'mentorTax',
        'assessorTax'
    ];
}
