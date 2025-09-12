<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingMaterialsDocument extends Model
{
    use HasFactory;

    protected $table = 'training_materials_documents';

    protected $fillable = [
        'trainer_id',
        'training_material_id',
        'training_title',
        'description',
        'file_path',
        'file_name',
    ];

    // Optional: Define relationships

    public function trainer()
    {
        return $this->belongsTo(Trainers::class);
    }

    public function trainingMaterial()
    {
        return $this->belongsTo(TrainingMaterial::class);
    }
}
