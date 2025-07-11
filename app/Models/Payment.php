<?php

namespace App\Models;
// app/Models/Payment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'jobseeker_id',
        'course_id',
        'payment_reference',
        'amount_paid',
        'payment_status',
        'payment_method',
        'paid_at',
        'transaction_id',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    // Relationships

    public function jobseeker()
    {
        return $this->belongsTo(Jobseekers::class);
    }

    public function course()
    {
        return $this->belongsTo(TrainingMaterial::class, 'course_id');
    }
}
