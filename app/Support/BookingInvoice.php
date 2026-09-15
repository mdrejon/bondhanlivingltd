<?php

namespace App\Support;

use App\Models\BookingRoom;
use App\Models\GlobalSetting;
use App\Models\RoomBooking;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdfInstance;

/**
 * Builds the "Download Invoice" PDF — shared between the admin download route
 * and the confirmed-status email attachment so both stay in sync.
 */
class BookingInvoice
{
    public static function build(RoomBooking $roomBooking): DomPdfInstance
    {
        $roomBooking->loadMissing(['customer', 'rooms.roomType', 'bookedBy']);

        // Group identical room-type/rate lines into one row with a quantity,
        // mirroring how a guest booking 3x the same room type reads on a real invoice.
        $lines = $roomBooking->rooms
            ->groupBy(fn (BookingRoom $r) => $r->room_type_id . '|' . $r->price_per_night)
            ->values()
            ->map(function ($group, $index) use ($roomBooking) {
                $first = $group->first();

                return [
                    'reservation_no' => $roomBooking->booking_reference . '-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                    'room_type'      => $first->roomType?->name ?? 'Unassigned',
                    'pax'            => $group->sum('adults') + $group->sum('children'),
                    'rooms'          => $group->count(),
                    'nights'         => $first->nights,
                    'room_rate'      => (float) $first->price_per_night,
                    'total'          => (float) $group->sum('line_total'),
                ];
            });

        $totalRoomRent = (float) $roomBooking->rooms->sum('line_total');
        $discount      = (float) $roomBooking->discount_amount;
        $advance       = (float) $roomBooking->advance_payment;
        $balance       = max(0, (float) $roomBooking->total_amount - $advance - $discount);

        $logoSetting = GlobalSetting::get('header_logo');
        $logoPath    = $logoSetting ? public_path('storage/' . $logoSetting) : public_path('assets/images/logo.png');
        $logoUsable  = extension_loaded('gd') && file_exists($logoPath);

        return Pdf::loadView('admin.bookings.invoice', [
            'booking'       => $roomBooking,
            'lines'         => $lines,
            'totalRoomRent' => $totalRoomRent,
            'discount'      => $discount,
            'advance'       => $advance,
            'balance'       => $balance,
            'logo'          => $logoUsable ? $logoPath : null,
            'siteName'      => GlobalSetting::get('site_name', 'Hotel Beach Way'),
            'phone'         => GlobalSetting::get('contact_phone', GlobalSetting::get('header_phone', '')),
            'email'         => GlobalSetting::get('contact_email', GlobalSetting::get('header_email', '')),
            'address'       => GlobalSetting::get('contact_address', ''),
            'website'       => url('/'),
        ])->setPaper('a4', 'landscape');
    }

    public static function filename(RoomBooking $roomBooking): string
    {
        return 'Invoice_' . $roomBooking->booking_reference . '.pdf';
    }
}
