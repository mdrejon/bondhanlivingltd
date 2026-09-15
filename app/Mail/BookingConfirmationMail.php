<?php

namespace App\Mail;

use App\Models\RoomBooking;
use App\Support\EmailNotificationSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public RoomBooking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Request Received – ' . $this->booking->booking_reference . ' | Hotel Beach Way',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-confirmation',
            with: [
                'tplHeaderTitle'    => EmailNotificationSettings::template('email_tpl_bconf_header_title', 'Booking Request Received'),
                'tplHeaderSubtitle' => EmailNotificationSettings::template('email_tpl_bconf_header_subtitle', "Cox's Bazar, Bangladesh"),
                'tplIntroText'      => EmailNotificationSettings::template('email_tpl_bconf_intro_text', 'Thank you for choosing Hotel Beach Way! We have received your booking request and our team will review and confirm it within 24 hours.'),
                'tplFooterText'     => EmailNotificationSettings::template('email_tpl_bconf_footer_text', "Near Kolatoli Beach, Cox's Bazar, Bangladesh"),
            ],
        );
    }
}
