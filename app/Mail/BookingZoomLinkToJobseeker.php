<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\BookingSession;

class BookingZoomLinkToJobseeker extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(BookingSession $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Your Consultation Session Zoom Link')
                    ->view('emails.booking.jobseeker_zoom')
                    ->with(['booking' => $this->booking]);
    }
}
