<?php
namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class JobseekerTrainingMaterialPurchasePaymentRequest extends Model
{
    protected $table = 'jobseeker_training_material_purchases_payment_request';

    protected $fillable = [
        'jobseeker_id',
        'trainer_id',
        'material_id',
        'batch_id',
        'request_payload',
        'track_id',
        'type',
        'training_type',
        'transaction_id',
        'payment_status',
        'tax',
        'amount',
        'amount_paid',
        'currency',
        'payment_gateway',
    ];

    protected $casts = [
        'request_payload' => 'array',
        'tax'             => 'decimal:2',
        'amount'          => 'decimal:2',
        'amount_paid'     => 'decimal:2',
    ];
}
