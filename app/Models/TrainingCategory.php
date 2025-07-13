<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCategory extends Model
{
    use HasFactory;

    protected $table = 'training_categories';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'category',
        'image_name',
        'image_path',
        
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

   public function trainings()
    {
        return $this->hasMany(TrainingMaterial::class, 'training_category', 'category');
    }

}