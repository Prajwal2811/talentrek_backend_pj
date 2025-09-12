<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Taxation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'taxations';

    protected $fillable = [
        'name',
        'type',
        'rate',
        'user_type',
        'is_active',
    ];

    protected $casts = [
        'rate'      => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
