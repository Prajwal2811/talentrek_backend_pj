<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'jobseeker_id',
        'user_type',
        'user_id',
        'reviews',
        'ratings',
        'trainer_material',
    ];

    // Relationships
    public function jobseeker()
    {
        return $this->belongsTo(Jobseekers::class);
    }

    // Polymorphic relationship (optional idea if you use morphable targets)
    public function target()
    {
        return match ($this->user_type) {
            'trainer'   => $this->belongsTo(Trainers::class, 'user_id'),
            'mentor'    => $this->belongsTo(Mentors::class, 'user_id'),
            'coach'     => $this->belongsTo(Coach::class, 'user_id'),
            'assessor'  => $this->belongsTo(Assessors::class, 'user_id'),
            default     => null,
        };
    }
}
