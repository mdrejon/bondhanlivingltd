<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingRoom;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoomAvailabilityController extends Controller
{
    /**
     * A room's active booking line can be in one of several blocking statuses at once
     * across different overlapping bookings within the searched range — this ranks
     * them so the most operationally significant one (a guest physically checked in)
     * always wins over a merely-reserved future booking.
     */
    private const STATUS_PRIORITY = [
        'checked_in'      => 4,
        'payment_pending' => 3,
        'confirmed'       => 2,
        'pending'         => 1,
    ];

    public function index(Request $request)
    {
        $checkIn    = $request->get('check_in');
        $checkOut   = $request->get('check_out');
        $roomTypeId = $request->get('room_type_id');

        // No dates picked yet (fresh page load) — default to today so the admin
        // immediately sees today's live availability instead of a blank search.
        if (!$checkIn || !$checkOut) {
            $checkIn  = $checkIn ?: now()->toDateString();
            $checkOut = $checkOut ?: now()->addDay()->toDateString();
        }

        $roomTypes = RoomType::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        $query = Room::with('roomType:id,name,price,price_unit,check_in_time,check_out_time')
            ->where('is_active', true)
            ->orderBy('room_number');

        if ($roomTypeId) {
            $query->where('room_type_id', $roomTypeId);
        }

        $rooms = $query->get();

        $roomBookingStatus = [];
        $checkedSearch = false;

        if ($checkIn && $checkOut && $checkIn < $checkOut) {
            $lines = BookingRoom::whereNotNull('room_id')
                ->activeLine()
                ->withBookingStatus(array_keys(self::STATUS_PRIORITY))
                ->overlapping($checkIn, $checkOut)
                ->get(['room_id', 'status']);

            foreach ($lines as $line) {
                $current = $roomBookingStatus[$line->room_id] ?? null;
                if (!$current || self::STATUS_PRIORITY[$line->status] > self::STATUS_PRIORITY[$current]) {
                    $roomBookingStatus[$line->room_id] = $line->status;
                }
            }

            $checkedSearch = true;
        }

        return Inertia::render('Admin/RoomAvailability/Index', [
            'rooms'              => $rooms,
            'roomTypes'          => $roomTypes,
            'unavailableRoomIds' => array_keys($roomBookingStatus),
            'roomBookingStatus'  => $roomBookingStatus,
            'checkedSearch'      => $checkedSearch,
            'filters'            => [
                'check_in'     => $checkIn,
                'check_out'    => $checkOut,
                'room_type_id' => $roomTypeId ? (int) $roomTypeId : null,
            ],
        ]);
    }
}
