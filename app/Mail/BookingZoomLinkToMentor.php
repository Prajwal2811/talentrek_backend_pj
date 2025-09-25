<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\BookingSession;

class BookingZoomLinkToMentor extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(BookingSession $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('New Consultation Session Scheduled')
                    ->view('emails.booking.mentor_zoom')
                    ->with(['booking' => $this->booking]);
    }
}
