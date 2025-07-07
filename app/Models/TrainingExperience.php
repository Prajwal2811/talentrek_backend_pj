<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingExperience extends Model
{
    use HasFactory;

    protected $table = 'training_experience';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'user_type',
        'training_experience',
        'training_skills',
        'website_link',
        'portfolio_link',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function experience()
    {
        return $this->belongsTo(Trainers::class);
    }
  
}
