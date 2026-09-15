<?php

namespace App\Mail;

use App\Models\RoomBooking;
use App\Support\EmailNotificationSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public RoomBooking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[New Booking] ' . $this->booking->booking_reference . ' – ' . ($this->booking->customer->name ?? 'Guest'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-notification',
            with: [
                'tplHeaderTitle'    => EmailNotificationSettings::template('email_tpl_bnotify_header_title', 'New Booking Request'),
                'tplHeaderSubtitle' => EmailNotificationSettings::template('email_tpl_bnotify_header_subtitle', 'Submitted via Hotel Beach Way website'),
                'tplFooterText'     => EmailNotificationSettings::template('email_tpl_bnotify_footer_text', 'This notification was sent to the Hotel Beach Way admin team.'),
            ],
        );
    }
}
