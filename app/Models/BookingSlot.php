<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_type',
        'user_id',
        'slot_mode',
        'start_time',
        'end_time',
        'unavailable_dates',
    ];

    protected $casts = [
        'unavailable_dates' => 'array',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
    ];

    public function unavailableDates()
    {
        return $this->hasMany(BookingSlotUnavailableDate::class, 'booking_slot_id');
    }

}
