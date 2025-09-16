<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TrainingBatch extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Specify the table name if it's not the plural of the model name
    protected $table = 'training_batches';

    /**
     * The attributes that are mass assignable.
     */
     // app/Models/TrainingBatch.php
    protected $fillable = [
        'trainer_id',
        'training_material_id',
        'batch_no',
        'start_date',
        'end_date',
        'start_timing',
        'end_timing',
        'duration',
        'days',
        'strength',
        'zoom_start_url',
        'zoom_join_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions' => 'array',
    ];


    public function trainer()
    {
        return $this->belongsTo(Trainers::class);
    }
    
    public function trainingMaterial()
    {
        return $this->belongsTo(TrainingMaterial::class, 'training_material_id');
    }
}
