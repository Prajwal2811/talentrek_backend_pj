<?php

namespace App\Models\Api;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AdditionalInfo extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Specify the table name if it's not the plural of the model name
    protected $table = 'additional_info';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'user_type',
        'doc_type',
        'document_name',
        'document_path',
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
    ];

    public function jobseeker()
    {
        return $this->belongsTo(Jobseekers::class);
    }

}
