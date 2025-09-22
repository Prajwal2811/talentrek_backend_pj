<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorporatesEmailIds extends Model
{
    use HasFactory;

   protected $table = 'corporates_emailids';

    protected $fillable = [
        'corporatesEmailIds',
        'paymentRequestId',
        'successPaymentId',
        'track_id'
    ];

    protected $casts = [
        'corporatesEmailIds' => 'array',
    ];

    /**
     * Example: relationship to Payment Request
     */
    public function paymentRequest()
    {
        return $this->belongsTo(PurchasedSubscriptionPaymentRequest::class, 'paymentRequestId');
    }

    /**
     * Example: relationship to Success Payment (PurchasedSubscription or similar)
     */
    public function successPayment()
    {
        return $this->belongsTo(PurchasedSubscription::class, 'successPaymentId');
    }
}
