<?php

namespace App\Support;

use App\Models\BookingRoom;
use App\Models\Hotel;
use Illuminate\Support\Collection;

/**
 * Shared occupancy aggregation for the government dashboard (Admin\DashboardController's
 * government branch) and the Hotel-wise report (Admin\GovernmentReportController) — both
 * need "current guests / today's check-ins / today's check-outs per hotel," so it's
 * computed once here rather than twice.
 *
 * Everything here relies on App\Models\Concerns\BelongsToHotel's global scope for
 * correctness: `BookingRoom` itself isn't tenant-scoped (see docs/hgrm-saas), but every
 * query below reaches it only via `whereHas('booking', ...)` / eager-loading `booking`,
 * and `RoomBooking` *is* scoped — so the parent's scope (single hotel, or a whole
 * jurisdiction's hotel set for government roles) is what actually restricts these
 * results. No manual `whereIn('hotel_id', ...)` needed anywhere in this class.
 */
class GovernmentStats
{
    /** Every hotel the current user can see (all of them for super admin). */
    public static function visibleHotels(): Collection
    {
        $ids = CurrentHotel::visibleHotelIds();
        $query = Hotel::with(['district', 'upazila', 'policeStation'])->orderBy('name');

        return is_null($ids) ? $query->get() : $query->whereIn('id', $ids)->get();
    }

    /** Booking lines currently checked in (i.e. the guest is on the premises right now). */
    public static function checkedInLines(): Collection
    {
        return BookingRoom::where('status', 'checked_in')
            ->whereHas('booking')
            ->with(['booking:id,hotel_id,customer_id', 'booking.customer:id,name,gender,nationality,is_foreign_guest'])
            ->get();
    }

    /** Booking lines whose actual check-in/out timestamp falls on the given date. */
    public static function linesOn(string $column, string $date): Collection
    {
        return BookingRoom::whereDate($column, $date)
            ->whereHas('booking')
            ->with('booking:id,hotel_id')
            ->get();
    }

    /** One row per visible hotel: current guest headcount + today's check-in/out counts. */
    public static function perHotelSummary(): Collection
    {
        $today = now()->toDateString();

        $hotels    = self::visibleHotels();
        $checkedIn = self::checkedInLines()->groupBy(fn ($l) => $l->booking->hotel_id);
        $todayIn   = self::linesOn('actual_check_in_at', $today)->groupBy(fn ($l) => $l->booking->hotel_id);
        $todayOut  = self::linesOn('actual_check_out_at', $today)->groupBy(fn ($l) => $l->booking->hotel_id);

        return $hotels->map(function (Hotel $hotel) use ($checkedIn, $todayIn, $todayOut) {
            $inHouse = $checkedIn->get($hotel->id, collect());

            return [
                'hotel_id'       => $hotel->id,
                'hotel_name'     => $hotel->name,
                'district'       => $hotel->district?->name,
                'upazila'        => $hotel->upazila?->name,
                'current_guests' => (int) $inHouse->sum(fn ($l) => $l->adults + $l->children),
                'today_checkin'  => $todayIn->get($hotel->id, collect())->count(),
                'today_checkout' => $todayOut->get($hotel->id, collect())->count(),
            ];
        })->values();
    }

    /** Last N days of check-in/check-out activity, oldest first. */
    public static function dailyTrend(int $days = 30): Collection
    {
        $start = now()->copy()->subDays($days - 1)->startOfDay();

        $checkIns  = BookingRoom::where('actual_check_in_at', '>=', $start)
            ->whereHas('booking')
            ->get(['actual_check_in_at'])
            ->groupBy(fn ($l) => $l->actual_check_in_at->toDateString());

        $checkOuts = BookingRoom::where('actual_check_out_at', '>=', $start)
            ->whereHas('booking')
            ->get(['actual_check_out_at'])
            ->groupBy(fn ($l) => $l->actual_check_out_at->toDateString());

        $trend = collect();
        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i)->toDateString();
            $trend->push([
                'date'       => $date,
                'check_ins'  => $checkIns->get($date, collect())->count(),
                'check_outs' => $checkOuts->get($date, collect())->count(),
            ]);
        }

        return $trend;
    }
}
