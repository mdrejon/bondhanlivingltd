<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\RoomType;
use App\Support\GovernmentStats;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;

        if ($role && in_array($role->scope_type, ['district', 'upazila', 'police_station'], true)) {
            return $this->governmentDashboard();
        }

        $stats = [
            'total_rooms'        => Room::count(),
            'total_room_types'   => RoomType::count(),
            'all_room_bookings'  => RoomBooking::count(),
            'paid_room_bookings' => RoomBooking::where('booking_status', 'paid')->count(),
        ];

        $recentRoomBookings = RoomBooking::with(['rooms.room', 'rooms.roomType'])
            ->latest()
            ->take(10)
            ->get()
            ->map(function (RoomBooking $b) {
                $firstLine = $b->rooms->first();
                $label = $firstLine?->room?->room_name ?? $firstLine?->roomType?->name ?? '—';
                if ($b->rooms->count() > 1) {
                    $label .= ' +' . ($b->rooms->count() - 1) . ' more';
                }

                return [
                    'id'     => $b->id,
                    'room'   => $label,
                    'rent'   => number_format($b->total_amount, 2),
                    'status' => $b->booking_status,
                ];
            });

        return Inertia::render('Admin/Dashboard', compact(
            'stats',
            'recentRoomBookings'
        ));
    }

    /**
     * DC Office / UNO Office / Police Admin see occupancy across their jurisdiction's
     * hotels instead of the hotel-operations dashboard above — same route, same
     * `dashboard` module permission (already granted to these roles by
     * GovernmentRoleSeeder), different content based on the user's role scope_type.
     */
    private function governmentDashboard()
    {
        $perHotel  = GovernmentStats::perHotelSummary();
        $checkedIn = GovernmentStats::checkedInLines();

        $stats = [
            'today_checkins'  => $perHotel->sum('today_checkin'),
            'today_checkouts' => $perHotel->sum('today_checkout'),
            'current_guests'  => $perHotel->sum('current_guests'),
            'foreign_guests'  => (int) $checkedIn
                ->filter(fn ($l) => (bool) $l->booking->customer?->is_foreign_guest)
                ->sum(fn ($l) => $l->adults + $l->children),
            'hotel_count'     => $perHotel->count(),
        ];

        // Gender is only reliably known for the primary registrant on a booking line,
        // not every occupant of the room — labelled accordingly in the UI.
        $genderSplit = [
            'male'    => $checkedIn->filter(fn ($l) => $l->booking->customer?->gender === 'male')->count(),
            'female'  => $checkedIn->filter(fn ($l) => $l->booking->customer?->gender === 'female')->count(),
            'other'   => $checkedIn->filter(fn ($l) => in_array($l->booking->customer?->gender, ['other', null], true))->count(),
        ];

        $districtWise = $perHotel->groupBy('district')->map(function ($rows, $district) {
            return [
                'label'          => $district ?: 'Unknown',
                'current_guests' => $rows->sum('current_guests'),
                'hotel_count'    => $rows->count(),
            ];
        })->values();

        $upazilaWise = $perHotel->groupBy('upazila')->map(function ($rows, $upazila) {
            return [
                'label'          => $upazila ?: '— (metro / not set)',
                'current_guests' => $rows->sum('current_guests'),
                'hotel_count'    => $rows->count(),
            ];
        })->values();

        return Inertia::render('Admin/Government/Dashboard', [
            'stats'        => $stats,
            'genderSplit'  => $genderSplit,
            'perHotel'     => $perHotel,
            'districtWise' => $districtWise,
            'upazilaWise'  => $upazilaWise,
            'trend'        => GovernmentStats::dailyTrend(30),
        ]);
    }
}
