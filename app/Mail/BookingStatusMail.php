<?php

namespace App\Mail;

use App\Models\RoomBooking;
use App\Support\BookingInvoice;
use App\Support\EmailNotificationSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $statusLabel;
    public string $statusNote;

    public function __construct(public RoomBooking $booking, string $note = '')
    {
        $this->statusLabel = [
            'confirmed'       => 'Confirmed',
            'payment_pending' => 'Payment Pending',
            'checked_in'      => 'Checked In',
            'checked_out'     => 'Checked Out',
            'cancelled'       => 'Cancelled',
        ][$booking->booking_status] ?? ucfirst($booking->booking_status);

        $this->statusNote = $note;
    }

    public function envelope(): Envelope
    {
        $subjects = [
            'confirmed'       => 'Your Booking is Confirmed',
            'payment_pending' => 'Payment Required for Your Booking',
            'checked_in'      => 'Welcome! You Are Checked In',
            'checked_out'     => 'Thank You for Staying with Us',
            'cancelled'       => 'Your Booking Has Been Cancelled',
            'no_show'         => 'Booking Marked as No Show',
        ];

        $subject = ($subjects[$this->booking->booking_status] ?? 'Booking Update')
            . ' – ' . $this->booking->booking_reference . ' | Hotel Beach Way';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        $status = $this->booking->booking_status;

        return new Content(
            view: 'emails.booking-status',
            with: [
                'tplTitle'      => EmailNotificationSettings::template(
                    "email_tpl_bstatus_{$status}_title",
                    EmailNotificationSettings::STATUS_TITLE_DEFAULTS[$status] ?? 'Booking Status Updated'
                ),
                'tplBody'       => EmailNotificationSettings::template(
                    "email_tpl_bstatus_{$status}_body",
                    EmailNotificationSettings::STATUS_BODY_DEFAULTS[$status] ?? ('Your booking status has been updated to <strong>' . $this->statusLabel . '</strong>.')
                ),
                'tplFooterText' => EmailNotificationSettings::template('email_tpl_bstatus_footer_text', "Near Kolatoli Beach, Cox's Bazar, Bangladesh"),
            ],
        );
    }

    /**
     * The invoice PDF rides along only when admin explicitly confirms the booking —
     * not on any other status transition (payment_pending, checked_in, checked_out, cancelled).
     */
    public function attachments(): array
    {
        if ($this->booking->booking_status !== 'confirmed') {
            return [];
        }

        $pdf = BookingInvoice::build($this->booking);

        return [
            Attachment::fromData(fn () => $pdf->output(), BookingInvoice::filename($this->booking))
                ->withMime('application/pdf'),
        ];
    }
}
