<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamCourseMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
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
