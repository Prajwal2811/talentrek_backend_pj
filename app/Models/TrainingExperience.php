<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TrainingExperience extends Model
{
    use HasFactory;

    protected $table = 'training_experience';

    protected $fillable = [
        'user_id',
        'user_type',
        'training_experience',
        'training_skills',
        'website_link',
        'portfolio_link',
    ];


    public function experience()
    {
        return $this->belongsTo(Trainers::class);
    }
  
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date_of_birth' => 'date',

    ];
}
