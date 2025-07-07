<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 

class Trainers extends Authenticatable
{
    use HasFactory;

    protected $table = 'trainers';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'phone_code',
        'phone_number',
        'password',
        'pass',
        'date_of_birth',
        'city',
        'otp',
        'status',
        'admin_status',
        'inactive_reason',
        'rejection_reason',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];


    public function educations()
    {
        return $this->hasMany(EducationDetails::class, 'user_id')
                    ->where('user_type', 'trainer');
    }

    public function experiences()
    {

        return $this->hasMany(WorkExperience::class, 'user_id')
                    ->where('user_type', 'trainer');
    }

    public function experience()
    {
        return $this->hasMany(TrainingExperience::class, 'user_id');
       
    }

    public function materials()
    {
        return $this->hasMany(TrainingMaterial::class, 'trainer_id');
       
    }

    public function materialsDocuments()
    {
        return $this->hasMany(TrainingMaterialsDocument::class, 'trainer_id');
       
    }

    public function courseBatch()
    {
        return $this->hasMany(TrainingBatch::class, 'trainer_id');
       
    }
}
