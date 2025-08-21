<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class TrainingPrograms extends Model
{
    protected $table = 'training_categories';

    protected $fillable = [
        'category',
        //'user_type',
        //'ratings',
        // Add other fields as needed
    ];
}
