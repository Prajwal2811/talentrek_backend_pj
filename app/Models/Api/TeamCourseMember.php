<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamCourseMember extends Model
{
    use HasFactory;

    // Explicitly define the table name
    protected $table = 'team_course_members';

    // Mass assignable fields
    protected $fillable = [
        'main_jobseeker_id',
        'jobseeker_id',
        'trainer_id',
        'training_material_purchases_id',
        'material_id',
        'training_type',
        'session_type',
        'batch_id',
        'transaction_id',
        'payment_status',
        'track_id',
        'email',
    ];

    // If you need date casting
    protected $dates = ['created_at', 'updated_at'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Example: Member belongs to a main jobseeker
    public function mainJobseeker()
    {
        return $this->belongsTo(Jobseeker::class, 'main_jobseeker_id');
    }

    // Example: Member itself as jobseeker
    public function jobseeker()
    {
        return $this->belongsTo(Jobseeker::class, 'jobseeker_id');
    }

    // Example: Trainer relation
    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id');
    }

    // Example: Training Material Purchase
    public function purchase()
    {
        return $this->belongsTo(JobseekerTrainingMaterialPurchase::class, 'training_material_purchases_id');
    }

    // Example: Material relation
    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}