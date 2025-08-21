<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSlotUnavailableDate extends Model
{
    use HasFactory;

    protected $table = 'booking_slots_unavailable_dates';

    protected $fillable = [
        'booking_slot_id',
        'unavailable_date',
    ];

    /**
     * Get the booking slot that owns this unavailable date.
     */
    public function bookingSlot()
    {
        return $this->belongsTo(BookingSlot::class);
    }
}
