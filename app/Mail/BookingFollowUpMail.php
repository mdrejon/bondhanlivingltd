<?php

namespace App\Mail;

use App\Models\BookingFollowUp;
use App\Models\RoomBooking;
use App\Support\EmailNotificationSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingFollowUpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public RoomBooking $booking,
        public BookingFollowUp $followUp,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->followUp->title . ' – ' . $this->booking->booking_reference . ' | Hotel Beach Way'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-followup',
            with: [
                'tplHeaderSubtitle' => EmailNotificationSettings::template('email_tpl_bfollowup_header_subtitle', "Cox's Bazar, Bangladesh"),
                'tplFooterText'     => EmailNotificationSettings::template('email_tpl_bfollowup_footer_text', "Near Kolatoli Beach, Cox's Bazar, Bangladesh"),
            ],
        );
    }
}
