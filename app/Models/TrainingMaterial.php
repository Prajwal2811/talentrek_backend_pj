<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingMaterial extends Model
{
    use HasFactory;

    protected $table = 'training_materials';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'trainer_id',
        'training_type' ,  
        'training_title',
        'training_sub_title',
        'training_descriptions',
        'training_category' ,       
        'training_price',  
        'thumbnail_file_path',      
        'thumbnail_file_name',     
        'training_objective',        
        'session_type',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'course_id');
    }

}
