<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamCourseMember extends Model
{
    use HasFactory;
    protected $table = "team_course_members";
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


    /**
     * Relation: Team member belongs to a purchase
     */
    public function purchase()
    {
        return $this->belongsTo(JobseekerTrainingMaterialPurchase::class, 'purchase_id');
    }
}
