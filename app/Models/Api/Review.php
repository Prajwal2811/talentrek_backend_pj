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
        'jobseeker_id',
        'user_id',
        'reviews'
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

    public function jobSeekerInfo()
    {
        return $this->hasOne(AdditionalInfo::class, 'user_id', 'jobseeker_id')->where('doc_type', 'profile_picture')->where('user_type', 'jobseeker');
    }
	
	public function jobSeekerInfoName()
    {
        return $this->hasOne(Jobseekers::class, 'id', 'jobseeker_id');
    }

    public function trainerReviews()
    {
        return $this->hasMany(Review::class, 'material_id', 'trainer_material')
                    ->where('user_type', 'trainer');
    } 
}
