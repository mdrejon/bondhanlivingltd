<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingFollowUpMail;
use App\Models\BookingFollowUp;
use App\Models\RoomBooking;
use App\Support\EmailNotificationSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class BookingFollowUpController extends Controller
{
    public function index(): Response
    {
        $bookings = RoomBooking::with(['customer', 'rooms.room', 'rooms.roomType', 'followUps' => function ($q) {
                $q->orderByDesc('id');
            }])
            ->where('booking_status', '!=', 'cancelled')
            ->orderByDesc('id')
            ->get();

        return Inertia::render('Admin/RoomBookings/FollowUp', [
            'bookings' => $bookings,
        ]);
    }

    public function store(Request $request, RoomBooking $booking): RedirectResponse
    {
        $data = $request->validate([
            'title'       => 'required|string',
            'description' => 'required|string',
        ]);

        $data['booking_id'] = $booking->id;

        $followUp = BookingFollowUp::create($data);

        $booking->load(['customer', 'rooms.roomType']);

        // Send to customer email if available and the follow-up toggle is enabled
        $toEmail = $booking->customer?->email;
        $emailEnabled = EmailNotificationSettings::enabled('email_toggle_followup_customer', true);

        if ($toEmail && $emailEnabled) {
            try {
                Mail::to($toEmail)->send(new BookingFollowUpMail($booking, $followUp));
            } catch (\Throwable $e) {
                Log::error('Follow-up email to customer failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', match (true) {
            !$emailEnabled => 'Follow-up recorded. Customer email notifications are currently disabled in Email Settings.',
            (bool) $toEmail => 'Follow-up sent and email delivered to ' . $toEmail . '.',
            default => 'Follow-up recorded. No customer email found — email not sent.',
        });
    }

    public function destroy(BookingFollowUp $followUp): RedirectResponse
    {
        $followUp->delete();

        return back()->with('success', 'Follow-up deleted.');
    }
}
