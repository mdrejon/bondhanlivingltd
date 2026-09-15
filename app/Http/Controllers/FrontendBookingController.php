<?php

namespace App\Http\Controllers;

use App\Exceptions\BookingUnavailableException;
use App\Mail\BookingConfirmationMail;
use App\Mail\BookingNotificationMail;
use App\Models\BookingRoom;
use App\Models\Customer;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\RoomType;
use App\Support\EmailNotificationSettings;
use App\Support\SpamGuard;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FrontendBookingController extends Controller
{
    private const BLOCKING_STATUSES = ['confirmed', 'checked_in', 'payment_pending'];

    /**
     * Report live capacity per room type for the requested dates, so the booking page can
     * show "3 left" per room type and stop a guest from adding more than what's available.
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $data = $request->validate([
            'checkin'  => 'required|date|after_or_equal:today',
            'checkout' => 'required|date|after:checkin',
        ]);

        $rooms = RoomType::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (RoomType $rt) => $this->roomTypePayload($rt, $data['checkin'], $data['checkout']))
            ->values();

        $anyAvailable = $rooms->contains('available', true);

        if (!$anyAvailable) {
            $from = Carbon::parse($data['checkin'])->format('d M Y');
            $to   = Carbon::parse($data['checkout'])->format('d M Y');

            return response()->json([
                'available' => false,
                'rooms'     => $rooms,
                'message'   => "Sorry, no rooms are available from {$from} to {$to}. Please try different dates.",
            ]);
        }

        return response()->json(['available' => true, 'rooms' => $rooms]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'                => 'required|string|max:150',
            'email'               => 'required|email|max:150',
            'phone'               => 'required|string|max:30',
            'checkin'             => 'required|date|after_or_equal:today',
            'checkout'            => 'required|date|after:checkin',
            'message'             => 'nullable|string|max:2000',
            'currency'            => 'nullable|in:BDT,USD,bdt,usd',
            'rooms'               => 'required|array|min:1',
            'rooms.*.room_slug'   => 'nullable|string|max:200',
            'rooms.*.quantity'    => 'required|integer|min:1|max:20',
            'rooms.*.adults'      => 'required|integer|min:1|max:20',
            'rooms.*.children'    => 'nullable|integer|min:0|max:20',
        ]);

        $currency = strtoupper($data['currency'] ?? 'BDT');

        if ($reason = SpamGuard::reason($request)) {
            Log::info('Blocked spam booking submission', ['reason' => $reason, 'ip' => $request->ip(), 'email' => $data['email']]);

            // silently "succeeds" from the bot's perspective, no booking created
            return response()->json([
                'success'   => true,
                'reference' => null,
                'message'   => 'Your booking request has been received! Our support team will contact you soon to confirm this booking.',
            ]);
        }

        $checkIn     = Carbon::parse($data['checkin']);
        $checkOut    = Carbon::parse($data['checkout']);
        $totalNights = max(1, $checkIn->diffInDays($checkOut));

        try {
            $booking = DB::transaction(function () use ($data, $totalNights, $currency) {
                $lineSpecs = $this->resolveAndValidateLines($data['rooms'], $data['checkin'], $data['checkout'], $currency);
                $customer  = $this->resolveCustomer($data);

                $totalAmount = 0;
                $totalAdults = 0;
                $totalChildren = 0;
                foreach ($lineSpecs as $spec) {
                    $totalAmount   += $spec['price_per_night'] * $totalNights;
                    $totalAdults   += $spec['adults'];
                    $totalChildren += $spec['children'];
                }

                $booking = RoomBooking::create([
                    'booking_reference' => $this->generateReference(),
                    'customer_id'       => $customer->id,
                    'check_in_date'     => $data['checkin'],
                    'check_out_date'    => $data['checkout'],
                    'adults'            => $totalAdults,
                    'children'          => $totalChildren,
                    'total_nights'      => $totalNights,
                    'total_amount'      => $totalAmount,
                    'currency'          => $currency,
                    'advance_payment'   => 0,
                    'payment_method'    => 'cash',
                    'special_requests'  => $data['message'] ?? null,
                    'booking_status'    => 'pending',
                    'source'            => 'web',
                ]);

                foreach ($lineSpecs as $spec) {
                    $booking->rooms()->create([
                        'room_type_id'    => $spec['room_type_id'],
                        'adults'          => $spec['adults'],
                        'children'        => $spec['children'],
                        'nights'          => $totalNights,
                        'price_per_night' => $spec['price_per_night'],
                        'line_total'      => $spec['price_per_night'] * $totalNights,
                        'status'          => 'pending',
                    ]);
                }

                return $booking;
            });
        } catch (BookingUnavailableException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        $booking->load(['rooms.roomType', 'customer']);
        $this->sendEmails($booking, $booking->customer);

        return response()->json([
            'success'   => true,
            'reference' => $booking->booking_reference,
            'message'   => 'Your booking request has been received! Reference: <strong>' . $booking->booking_reference . '</strong>. We received your reservation — our support team will contact you soon to confirm this booking.',
        ]);
    }

    /**
     * Aggregates requested lines by resolved room type (defends against the same type being
     * submitted twice), validates per-room occupancy against the room type's caps, and checks
     * remaining capacity for the whole requested quantity of each type at once.
     *
     * @return array<int, array{room_type_id: ?int, adults: int, children: int, price_per_night: float}>
     */
    private function resolveAndValidateLines(array $requestedRooms, string $checkin, string $checkout, string $currency = 'BDT'): array
    {
        $groups = [];

        foreach ($requestedRooms as $line) {
            $roomType = null;
            if (!empty($line['room_slug'])) {
                $roomType = RoomType::where('slug', $line['room_slug'])->where('is_active', true)->first();
                if (!$roomType) {
                    throw new BookingUnavailableException('One of the selected room types is no longer available.');
                }
            }

            $key = $roomType?->id ?? 'unassigned';
            $groups[$key]['room_type'] ??= $roomType;
            for ($i = 0; $i < (int) $line['quantity']; $i++) {
                $groups[$key]['units'][] = [
                    'adults'   => (int) $line['adults'],
                    'children' => (int) ($line['children'] ?? 0),
                ];
            }
        }

        $lineSpecs = [];

        foreach ($groups as $group) {
            /** @var ?RoomType $roomType */
            $roomType = $group['room_type'];
            $units    = $group['units'];
            $quantity = count($units);
            $pricePerNight = $roomType ? $roomType->effectivePrice($currency) : 0;

            if ($roomType && $pricePerNight === null) {
                throw new BookingUnavailableException(
                    "{$roomType->name} is not available in " . strtoupper($currency) . '. Please switch currency or remove this room.'
                );
            }

            if ($roomType) {
                $maxAdults   = $roomType->max_adults ?: 1;
                $maxChildren = $roomType->max_children ?: 0;
                foreach ($units as $unit) {
                    if ($unit['adults'] > $maxAdults || $unit['children'] > $maxChildren) {
                        throw new BookingUnavailableException(
                            "{$roomType->name} allows up to {$maxAdults} adult(s) and {$maxChildren} child(ren) per room."
                        );
                    }
                }

                $totalRooms = Room::where('room_type_id', $roomType->id)->where('is_active', true)->count();
                if ($totalRooms > 0) {
                    $booked    = $this->bookedCount($roomType->id, $checkin, $checkout);
                    $remaining = $totalRooms - $booked;

                    if ($quantity > $remaining) {
                        throw new BookingUnavailableException($remaining > 0
                            ? "Only {$remaining} {$roomType->name} room(s) left for your selected dates."
                            : "Sorry, no {$roomType->name} rooms are available for your selected dates.");
                    }
                }
            }

            foreach ($units as $unit) {
                $lineSpecs[] = [
                    'room_type_id'    => $roomType?->id,
                    'adults'          => $unit['adults'],
                    'children'        => $unit['children'],
                    'price_per_night' => $pricePerNight,
                ];
            }
        }

        return $lineSpecs;
    }

    private function bookedCount(int $roomTypeId, string $checkin, string $checkout): int
    {
        return BookingRoom::where('room_type_id', $roomTypeId)
            ->activeLine()
            ->withBookingStatus(self::BLOCKING_STATUSES)
            ->overlapping($checkin, $checkout)
            ->count();
    }

    private function roomTypePayload(RoomType $rt, string $checkin, string $checkout): array
    {
        $totalRooms = Room::where('room_type_id', $rt->id)->where('is_active', true)->count();

        $remaining = null; // no physical rooms configured yet — treated as unlimited, admin assigns later
        if ($totalRooms > 0) {
            $remaining = max(0, $totalRooms - $this->bookedCount($rt->id, $checkin, $checkout));
        }

        return [
            'slug'               => $rt->slug,
            'name'               => $rt->name,
            'price_bdt'          => (float) $rt->price,
            'price_bdt_effective'=> $rt->effectivePrice('BDT'),
            'price_usd'          => $rt->price_usd ? (float) $rt->price_usd : null,
            'price_usd_effective'=> $rt->effectivePrice('USD'),
            'has_usd'            => (bool) $rt->price_usd,
            'price_unit'         => $rt->price_unit ?? '/Night',
            'max_adults'         => $rt->max_adults,
            'max_children'       => $rt->max_children,
            'total_rooms'        => $totalRooms,
            'remaining'          => $remaining,
            'available'          => $remaining === null || $remaining > 0,
        ];
    }

    private function resolveCustomer(array $data): Customer
    {
        $phone = !empty($data['phone']) ? trim($data['phone']) : null;

        if ($phone) {
            return Customer::updateOrCreate(
                ['phone' => $phone],
                array_filter(['name' => $data['name'], 'email' => $data['email']])
            );
        }

        $existing = Customer::where('email', $data['email'])->first();
        if ($existing) {
            $existing->update(['name' => $data['name']]);
            return $existing;
        }

        return Customer::create(['name' => $data['name'], 'email' => $data['email'], 'phone' => null]);
    }

    /**
     * Customer confirmation defaults OFF — per policy, guests aren't emailed until
     * admin reviews and confirms the booking (see EmailNotificationSettings /
     * RoomBookingController::sendStatusEmail for the confirmed-status email).
     */
    private function sendEmails(RoomBooking $booking, Customer $customer): void
    {
        try {
            // Always this booking's own hotel's SMTP config, not just whichever
            // hotel is ambient — see EmailNotificationSettings::applyMailConfigFor().
            EmailNotificationSettings::applyMailConfigFor($booking->hotel_id);

            if ($customer->email && EmailNotificationSettings::enabled('email_toggle_new_booking_customer', false)) {
                Mail::to($customer->email)->send(new BookingConfirmationMail($booking));
            }
            if (EmailNotificationSettings::enabled('email_toggle_new_booking_admin', true)) {
                EmailNotificationSettings::sendToAdmins(fn () => new BookingNotificationMail($booking), 'Booking notification');
            }
        } catch (\Throwable $e) {
            Log::error('Booking email failed for ' . $booking->booking_reference . ': ' . $e->getMessage());
        }
    }

    private function generateReference(): string
    {
        do {
            $ref = 'HBW-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        } while (RoomBooking::where('booking_reference', $ref)->exists());

        return $ref;
    }
}
