<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobseekerCartItem extends Model
{
    use HasFactory;

    protected $table = 'jobseeker_cart_items';
    protected $fillable = [
        'jobseeker_id',
        'trainer_id',
        'material_type',
        'material_id',
        'batch_id',
        'price',
        'available_seats',
        'batch_status',
        'status',
    ];

    // Relationships
    public function batch() {
        return $this->belongsTo(TrainingBatch::class, 'batch_id');
    }

    // Relationships (optional)
    public function jobseeker()
    {
        return $this->belongsTo(Jobseekers::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainers::class);
    }

    public function material()
    {
        return $this->belongsTo(TrainingMaterial::class, 'material_id');
    }    

}
