<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainers extends Model
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
        'date_of_birth',
        'city',
        'otp',
        'status',
        'admin_status',
        'inactive_reason',
        'rejection_reason',
        'national_id',
        'country',
        'pin_code',
        'state',
        'is_registered'
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

    // In Trainer.php model
    public function latestWorkExperience()
    {
        return $this->hasOne(WorkExperience::class, 'user_id', 'id') // user_id in work_experience = trainer's id
            ->where('user_type', 'trainer') // filter only trainer-type
            ->orderByRaw('ABS(DATEDIFF(end_to, CURDATE()))') // closest to today
            ->select('user_id', 'job_role', 'end_to');
    }

    
    // public function trainer()
    // {
    //     return $this->belongsTo(WorkExperience::class, 'user_id', 'id');
    // }

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
