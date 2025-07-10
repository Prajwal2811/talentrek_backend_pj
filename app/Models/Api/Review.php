<?php
namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'trainer_material',
        'user_type',
        'ratings',
        // Add other fields as needed
    ];
}
