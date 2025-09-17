<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'trainer_material',
        'user_type',
        'ratings',
        // Add other fields as needed
    ];

    public function trainer()
    {
        return $this->belongsTo(Trainers::class, 'trainer_material', 'id');
    }

    public function mentor()
    {
        return $this->belongsTo(Mentors::class, 'trainer_material', 'id');
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class, 'trainer_material', 'id');
    }

    public function assessor()
    {
        return $this->belongsTo(Assessors::class, 'trainer_material', 'id');
    }
}
