<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingStatusMail;
use App\Models\BookingLog;
use App\Models\BookingRoom;
use App\Models\Customer;
use App\Models\GlobalSetting;
use App\Models\Room;
use App\Models\RoomBooking;
use App\Models\RoomType;
use App\Support\BookingInvoice;
use App\Support\DocumentUploader;
use App\Support\EmailNotificationSettings;
use App\Support\Geography;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class RoomBookingController extends Controller
{
    private const BLOCKING_STATUSES = ['confirmed', 'checked_in', 'payment_pending'];

    private const SINGLE_DOC_FIELDS = [
        'nid_front_doc'     => 'nid_front',
        'nid_back_doc'      => 'nid_back',
        'passport_doc'      => 'passport_scan',
        'visa_doc'          => 'visa',
        'marriage_cert_doc' => 'marriage_certificate',
        'guest_photo_doc'   => 'guest_photo',
    ];

    public function index(): Response
    {
        $bookings = RoomBooking::with(['customer.documents', 'rooms.room', 'rooms.roomType'])
            ->orderByDesc('id')
            ->get();

        $stats = [
            'pending'         => $bookings->where('booking_status', 'pending')->count(),
            'confirmed'       => $bookings->where('booking_status', 'confirmed')->count(),
            'payment_pending' => $bookings->where('booking_status', 'payment_pending')->count(),
            'checked_in'      => $bookings->where('booking_status', 'checked_in')->count(),
            'checked_out'     => $bookings->where('booking_status', 'checked_out')->count(),
            'cancelled'       => $bookings->where('booking_status', 'cancelled')->count(),
        ];

        $today = now()->toDateString();
        $todayCheckIns  = $bookings->where('check_in_date', $today)
            ->whereIn('booking_status', ['confirmed', 'checked_in'])->count();
        $todayCheckOuts = $bookings->where('check_out_date', $today)
            ->where('booking_status', 'checked_in')->count();

        return Inertia::render('Admin/RoomBookings/Index', [
            'bookings'       => $bookings,
            'stats'          => $stats,
            'todayCheckIns'  => $todayCheckIns,
            'todayCheckOuts' => $todayCheckOuts,
        ]);
    }

    public function create(Request $request): Response
    {
        $roomTypes = RoomType::active()->get([
            'id', 'name', 'price', 'price_usd', 'price_unit', 'max_adults', 'max_children',
            'discount_type_bdt', 'discount_value_bdt', 'discount_type_usd', 'discount_value_usd', 'offer_expires_at',
        ]);
        $rooms     = Room::active()->with('roomType:id,name')->get(['id', 'room_type_id', 'room_number', 'room_name', 'status']);
        $customers = Customer::orderBy('name')->get(['id', 'name', 'phone', 'email', 'nationality', 'document_type', 'nid_number', 'passport_number']);
        $canManageFlag = auth()->user()->canManagePoliceFlag();

        return Inertia::render('Admin/RoomBookings/Create', array_merge(
            Geography::selectOptions(),
            [
                'roomTypes'     => $roomTypes,
                'rooms'         => $rooms,
                'customers'     => $customers,
                'canManageFlag' => $canManageFlag,
                'prefill'   => [
                    'room_id'        => $request->get('room_id') ? (int) $request->get('room_id') : null,
                    'room_type_id'   => $request->get('room_type_id') ? (int) $request->get('room_type_id') : null,
                    'check_in_date'  => $request->get('check_in_date'),
                    'check_out_date' => $request->get('check_out_date'),
                ],
            ]
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateBookingData($request, false);

        if ($conflictError = $this->checkLineConflicts($data['rooms'], $data['check_in_date'], $data['check_out_date'])) {
            return back()->withErrors($conflictError)->withInput();
        }

        $customer = $this->resolveCustomer($data);

        DocumentUploader::syncSingle($request, $customer, self::SINGLE_DOC_FIELDS, 'customers/documents');
        DocumentUploader::addMultiple($request, $customer, 'other_documents', 'other', 'customers/documents');

        $booking = DB::transaction(function () use ($data, $customer) {
            $checkIn     = Carbon::parse($data['check_in_date']);
            $checkOut    = Carbon::parse($data['check_out_date']);
            $totalNights = max(1, $checkIn->diffInDays($checkOut));

            [$totalAmount, $totalAdults, $totalChildren] = $this->sumLines($data['rooms'], $totalNights);

            $booking = RoomBooking::create([
                'booking_reference' => $this->generateReference(),
                'customer_id'       => $customer->id,
                'check_in_date'     => $data['check_in_date'],
                'check_out_date'    => $data['check_out_date'],
                'adults'            => $totalAdults,
                'children'          => $totalChildren,
                'total_nights'      => $totalNights,
                'total_amount'      => $totalAmount,
                'currency'          => $data['currency'] ?? 'BDT',
                'advance_payment'   => $data['advance_payment'] ?? 0,
                'payment_method'    => $data['payment_method'],
                'special_requests'  => $data['special_requests'] ?? null,
                'notes'             => $data['notes'] ?? null,
                'booking_status'    => $data['booking_status'],
                'booked_by'         => auth()->id(),
            ]);

            foreach ($data['rooms'] as $line) {
                $lineTotal = $line['price_per_night'] * $totalNights;
                $booking->rooms()->create([
                    'room_type_id'    => $line['room_type_id'],
                    'room_id'         => $line['room_id'] ?? null,
                    'adults'          => $line['adults'],
                    'children'        => $line['children'],
                    'nights'          => $totalNights,
                    'price_per_night' => $line['price_per_night'],
                    'line_total'      => $lineTotal,
                    'status'          => $data['booking_status'],
                ]);
            }

            return $booking;
        });

        BookingLog::create([
            'booking_id'   => $booking->id,
            'action'       => 'created',
            'description'  => 'Booking created with status: ' . $data['booking_status'] . ' (' . count($data['rooms']) . ' room line(s))',
            'new_value'    => $data['booking_status'],
            'performed_by' => auth()->user()->name ?? 'Admin',
        ]);

        return redirect()->route('admin.room-bookings.show', $booking->id)
            ->with('success', "Booking {$booking->booking_reference} created successfully.");
    }

    public function show(RoomBooking $roomBooking): Response
    {
        $roomBooking->load(['customer.documents', 'customer.district', 'customer.upazila', 'customer.policeStation', 'rooms.room.roomType', 'rooms.roomType', 'bookedBy']);

        return Inertia::render('Admin/RoomBookings/Show', [
            'booking' => $roomBooking,
        ]);
    }

    public function invoicePdf(RoomBooking $roomBooking)
    {
        return BookingInvoice::build($roomBooking)->download(BookingInvoice::filename($roomBooking));
    }

    /**
     * Two identical copies (customer + accountant) on one portrait page, separated
     * by a dotted cut-line — the paper "Money Receipt" convention front desks use.
     */
    public function moneyReceiptPdf(RoomBooking $roomBooking)
    {
        $roomBooking->load(['customer', 'rooms.room', 'rooms.roomType', 'bookedBy']);

        $amount       = (float) $roomBooking->advance_payment;
        $currency     = strtoupper($roomBooking->currency ?? 'BDT');
        $currencyUnit = $currency === 'USD' ? 'US Dollar' : 'Taka';

        $roomNo = $roomBooking->rooms->pluck('room.room_number')->filter()->unique()->values();
        $roomNo = $roomNo->isNotEmpty()
            ? $roomNo->implode(', ')
            : ($roomBooking->rooms->first()?->roomType?->name ?? '—');

        $methodLabels = [
            'cash'          => 'Cash',
            'card'          => 'Card',
            'bkash'         => 'Mobile Banking',
            'nagad'         => 'Mobile Banking',
            'bank_transfer' => 'Bank Transfer',
        ];
        $methodDetail = [
            'bkash'  => 'bKash',
            'nagad'  => 'Nagad',
        ];

        $logoSetting = GlobalSetting::get('header_logo');
        $logoPath    = $logoSetting ? public_path('storage/' . $logoSetting) : public_path('assets/images/logo.png');
        $logoUsable  = extension_loaded('gd') && file_exists($logoPath);

        $pdf = Pdf::loadView('admin.bookings.money-receipt', [
            'booking'       => $roomBooking,
            'amount'        => $amount,
            'amountWords'   => Str::ucfirst($this->amountToWords((int) round($amount))) . ' ' . $currencyUnit . ' Only.',
            'currencyLabel' => $currency === 'USD' ? 'USD' : 'TK.',
            'roomNo'        => $roomNo,
            'receiptNo'     => 'MR-' . $roomBooking->booking_reference,
            'receiptDate'   => now()->format('F j, Y'),
            'methodChecked' => $methodLabels[$roomBooking->payment_method] ?? null,
            'methodDetail'  => $methodDetail[$roomBooking->payment_method] ?? null,
            'scriptFont'    => resource_path('fonts/Kalam-Bold.ttf'),
            'logo'          => $logoUsable ? $logoPath : null,
            'siteName'      => GlobalSetting::get('site_name', 'Hotel Beach Way'),
            'phone'         => GlobalSetting::get('contact_phone', GlobalSetting::get('header_phone', '')),
            'email'         => GlobalSetting::get('contact_email', GlobalSetting::get('header_email', '')),
            'address'       => GlobalSetting::get('contact_address', ''),
            'website'       => url('/'),
        ])->setPaper('a4', 'portrait');

        return $pdf->download('MoneyReceipt_' . $roomBooking->booking_reference . '.pdf');
    }

    /**
     * Standard English short-scale number-to-words (thousand/million/billion),
     * used to spell out the "Taka ... Only" line on the money receipt.
     */
    private function amountToWords(int $num): string
    {
        $ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
            'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
        $tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

        if ($num === 0) {
            return 'zero';
        }

        $words = '';

        foreach ([1000000000 => 'billion', 1000000 => 'million', 1000 => 'thousand', 100 => 'hundred'] as $unit => $label) {
            if ($num >= $unit) {
                $words .= $this->amountToWords((int) ($num / $unit)) . ' ' . $label . ' ';
                $num %= $unit;
            }
        }

        if ($num > 0) {
            if ($words !== '') {
                $words .= 'and ';
            }
            $words .= $num < 20 ? $ones[$num] : $tens[(int) ($num / 10)] . ($num % 10 ? '-' . $ones[$num % 10] : '');
        }

        return trim($words);
    }

    public function edit(RoomBooking $roomBooking): Response
    {
        $roomBooking->load(['customer.documents', 'customer.district', 'customer.upazila', 'customer.policeStation', 'rooms.room', 'rooms.roomType']);
        $roomTypes = RoomType::active()->get([
            'id', 'name', 'price', 'price_usd', 'price_unit', 'max_adults', 'max_children',
            'discount_type_bdt', 'discount_value_bdt', 'discount_type_usd', 'discount_value_usd', 'offer_expires_at',
        ]);
        $rooms     = Room::active()->with('roomType:id,name')->get(['id', 'room_type_id', 'room_number', 'room_name', 'status']);
        $canManageFlag = auth()->user()->canManagePoliceFlag();

        return Inertia::render('Admin/RoomBookings/Edit', array_merge(
            Geography::selectOptions(),
            [
                'booking'       => $roomBooking,
                'roomTypes'     => $roomTypes,
                'rooms'         => $rooms,
                'canManageFlag' => $canManageFlag,
            ]
        ));
    }

    public function update(Request $request, RoomBooking $roomBooking): RedirectResponse
    {
        $data = $this->validateBookingData($request, true);

        if ($conflictError = $this->checkLineConflicts($data['rooms'], $data['check_in_date'], $data['check_out_date'], $roomBooking->id)) {
            return back()->withErrors($conflictError)->withInput();
        }

        $customer = $roomBooking->customer;
        $customerPayload = $this->extractCustomerPayload($data, $request);
        $customer->update($customerPayload);

        if (auth()->user()->canManagePoliceFlag() && isset($data['is_flagged'])) {
            $customer->update([
                'is_flagged'   => $request->boolean('is_flagged'),
                'flagged_note' => $data['flagged_note'] ?? null,
            ]);
        }

        DocumentUploader::syncSingle($request, $customer, self::SINGLE_DOC_FIELDS, 'customers/documents');
        DocumentUploader::addMultiple($request, $customer, 'other_documents', 'other', 'customers/documents');

        $oldStatus = $roomBooking->booking_status;
        $newStatus = $data['booking_status'];

        if ($newStatus === 'checked_in' && $oldStatus !== 'checked_in') {
            $unassignedTypes = collect($data['rooms'])
                ->filter(fn ($line) => empty($line['room_id']))
                ->pluck('room_type_id')
                ->unique();

            if ($unassignedTypes->isNotEmpty()) {
                $names = RoomType::whereIn('id', $unassignedTypes)->pluck('name')->implode(', ');
                return back()->withErrors([
                    'booking_status' => "Please assign a physical room number to every room line before checking in (missing for: {$names}).",
                ])->withInput();
            }
        }

        DB::transaction(function () use ($data, $roomBooking, $oldStatus, $newStatus) {
            $checkIn     = Carbon::parse($data['check_in_date']);
            $checkOut    = Carbon::parse($data['check_out_date']);
            $totalNights = max(1, $checkIn->diffInDays($checkOut));

            // Sync room lines: remove lines the admin dropped (never one already checked in/out —
            // those must be cancelled individually via the per-room action instead), update the rest.
            $incomingIds = collect($data['rooms'])->pluck('id')->filter()->values();
            $roomBooking->rooms()
                ->when($incomingIds->isNotEmpty(), fn ($q) => $q->whereNotIn('id', $incomingIds), fn ($q) => $q)
                ->whereNotIn('status', ['checked_in', 'checked_out'])
                ->delete();

            [$totalAmount, $totalAdults, $totalChildren] = $this->sumLines($data['rooms'], $totalNights);

            foreach ($data['rooms'] as $line) {
                $lineTotal = $line['price_per_night'] * $totalNights;
                $attrs = [
                    'room_type_id'    => $line['room_type_id'],
                    'room_id'         => $line['room_id'] ?? null,
                    'adults'          => $line['adults'],
                    'children'        => $line['children'],
                    'nights'          => $totalNights,
                    'price_per_night' => $line['price_per_night'],
                    'line_total'      => $lineTotal,
                ];

                if (!empty($line['id'])) {
                    $existingLine = $roomBooking->rooms()->find($line['id']);
                    if ($existingLine && !in_array($existingLine->status, ['checked_in', 'checked_out'])) {
                        $existingLine->update($attrs);
                    }
                } else {
                    $attrs['status'] = $newStatus;
                    $roomBooking->rooms()->create($attrs);
                }
            }

            $roomBooking->update([
                'check_in_date'    => $data['check_in_date'],
                'check_out_date'   => $data['check_out_date'],
                'adults'           => $totalAdults,
                'children'         => $totalChildren,
                'total_nights'     => $totalNights,
                'total_amount'     => $totalAmount,
                'currency'         => $data['currency'] ?? $roomBooking->currency ?? 'BDT',
                'advance_payment'  => $data['advance_payment'] ?? 0,
                'payment_method'   => $data['payment_method'],
                'booking_status'   => $newStatus,
                'special_requests' => $data['special_requests'] ?? null,
                'notes'            => $data['notes'] ?? null,
            ]);

            $this->applyStatusToLines($roomBooking, $oldStatus, $newStatus);
        });

        $roomBooking->load(['customer', 'rooms.roomType', 'rooms.room']);

        if ($oldStatus !== $newStatus) {
            $this->sendStatusEmail($roomBooking, $data['notes'] ?? '');
        }

        return redirect()->route('admin.room-bookings.show', $roomBooking->id)
            ->with('success', 'Booking updated successfully.');
    }

    public function updateStatus(Request $request, RoomBooking $roomBooking): RedirectResponse
    {
        $data = $request->validate([
            'booking_status'                => 'required|in:pending,confirmed,payment_pending,checked_in,checked_out,cancelled',
            'notes'                         => 'nullable|string',
            'room_assignments'              => 'nullable|array',
            'room_assignments.*.line_id'    => 'required_with:room_assignments|integer|exists:booking_rooms,id',
            'room_assignments.*.room_id'    => 'required_with:room_assignments|integer|exists:rooms,id',
        ]);

        $oldStatus = $roomBooking->booking_status;
        $newStatus = $data['booking_status'];

        // "Assign & Check In": the admin can assign missing room numbers in the same
        // request that checks the booking in, instead of a separate Edit Booking trip.
        if (!empty($data['room_assignments'])) {
            foreach ($data['room_assignments'] as $assignment) {
                $line = $roomBooking->rooms()->activeLine()->find($assignment['line_id']);
                if (!$line) {
                    continue;
                }

                $conflict = BookingRoom::where('room_id', $assignment['room_id'])
                    ->where('id', '!=', $line->id)
                    ->activeLine()
                    ->withBookingStatus(self::BLOCKING_STATUSES)
                    ->overlapping($roomBooking->check_in_date, $roomBooking->check_out_date)
                    ->exists();

                if ($conflict) {
                    return back()->with('error', 'One of the selected rooms is already booked for these dates. Please pick a different room.');
                }

                $line->update(['room_id' => $assignment['room_id']]);
            }

            $roomBooking->load('rooms');
        }

        if ($newStatus === 'checked_in' && $oldStatus !== 'checked_in') {
            $unassigned = $roomBooking->rooms()->activeLine()->whereNull('room_id')->with('roomType:id,name')->get();
            if ($unassigned->isNotEmpty()) {
                $names = $unassigned->pluck('roomType.name')->filter()->unique()->implode(', ');
                return back()->with('error', 'Please assign a physical room number to every room line before checking in'
                    . ($names ? " (missing for: {$names})" : '') . '. Use Edit Booking to assign rooms.');
            }
        }

        DB::transaction(function () use ($roomBooking, $oldStatus, $newStatus, $data) {
            $this->applyStatusToLines($roomBooking, $oldStatus, $newStatus);
            $this->recalculateBookingTotals($roomBooking);

            $updateData = ['booking_status' => $newStatus];
            if (!empty($data['notes'])) {
                $updateData['notes'] = $data['notes'];
            }
            $roomBooking->update($updateData);
        });

        $roomBooking->load(['customer', 'rooms.roomType', 'rooms.room']);

        BookingLog::create([
            'booking_id'   => $roomBooking->id,
            'action'       => 'status_changed',
            'description'  => 'Status changed to: ' . $newStatus,
            'old_value'    => $oldStatus,
            'new_value'    => $newStatus,
            'performed_by' => auth()->user()->name ?? 'Admin',
        ]);

        $this->sendStatusEmail($roomBooking, $data['notes'] ?? '');

        return back()->with('success', 'Booking status updated to ' . ucfirst(str_replace('_', ' ', $newStatus)) . '.');
    }

    /**
     * Sends BookingStatusMail to the customer and/or admin recipients, each gated by its
     * own per-status GlobalSetting toggle (email_toggle_status_{status}_customer/_admin).
     * Only fires for statuses in EMAIL_STATUSES — pending and no_show are intentionally
     * excluded (no auto customer email until admin explicitly reviews the booking).
     */
    private function sendStatusEmail(RoomBooking $roomBooking, string $notes = ''): void
    {
        $status = $roomBooking->booking_status;

        if (!in_array($status, EmailNotificationSettings::EMAIL_STATUSES, true)) {
            return;
        }

        // Send via this booking's own hotel's SMTP config — matters when a Super
        // Admin is acting-as a different hotel while managing this booking.
        EmailNotificationSettings::applyMailConfigFor($roomBooking->hotel_id);

        $mail = new BookingStatusMail($roomBooking, $notes);

        if ($roomBooking->customer?->email && EmailNotificationSettings::enabled("email_toggle_status_{$status}_customer", true)) {
            try {
                Mail::to($roomBooking->customer->email)->send($mail);
            } catch (\Throwable $e) {
                Log::error('Booking status email (customer) failed for ' . $roomBooking->booking_reference . ': ' . $e->getMessage());
            }
        }

        if (EmailNotificationSettings::enabled("email_toggle_status_{$status}_admin", false)) {
            EmailNotificationSettings::sendToAdmins(fn () => new BookingStatusMail($roomBooking, $notes), 'Booking status (admin)');
        }
    }

    public function destroy(RoomBooking $roomBooking): RedirectResponse
    {
        if (in_array($roomBooking->booking_status, ['confirmed', 'checked_in', 'payment_pending'])) {
            foreach ($roomBooking->rooms as $line) {
                $line->room?->update(['status' => 'available']);
            }
        }

        $roomBooking->delete();

        return redirect()->route('admin.room-bookings.index')
            ->with('success', 'Booking deleted.');
    }

    public function roomAvailability(Request $request): JsonResponse
    {
        $data = $request->validate([
            'room_type_id'        => 'required|exists:room_types,id',
            'check_in_date'       => 'required|date',
            'check_out_date'      => 'required|date|after:check_in_date',
            'exclude_booking_id'  => 'nullable|exists:room_bookings,id',
            'exclude_room_ids'    => 'nullable|array',
            'exclude_room_ids.*'  => 'integer',
        ]);

        $bookedRoomIds = BookingRoom::whereNotNull('room_id')
            ->activeLine()
            ->withBookingStatus(self::BLOCKING_STATUSES)
            ->overlapping($data['check_in_date'], $data['check_out_date'])
            ->when(!empty($data['exclude_booking_id']), fn ($q) => $q->where('room_booking_id', '!=', $data['exclude_booking_id']))
            ->pluck('room_id');

        $excludeInForm = collect($data['exclude_room_ids'] ?? []);

        $rooms = Room::where('room_type_id', $data['room_type_id'])
            ->where('is_active', true)
            ->orderBy('room_number')
            ->get(['id', 'room_number', 'room_name', 'floor'])
            ->map(fn ($r) => [
                'id'          => $r->id,
                'room_number' => $r->room_number,
                'room_name'   => $r->room_name,
                'floor'       => $r->floor,
                'available'   => !$bookedRoomIds->contains($r->id) && !$excludeInForm->contains($r->id),
            ]);

        return response()->json(['rooms' => $rooms]);
    }

    public function allBookings(): Response
    {
        $bookings = RoomBooking::with(['customer', 'rooms.room', 'rooms.roomType'])
            ->orderByDesc('id')->get();
        $roomTypes = RoomType::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Admin/RoomBookings/AllBookings', [
            'bookings'  => $bookings,
            'roomTypes' => $roomTypes,
        ]);
    }

    public function onlineBookings(): Response
    {
        $bookings = RoomBooking::with(['customer', 'rooms.room', 'rooms.roomType'])
            ->where('source', 'web')
            ->orderByDesc('id')->get();

        return Inertia::render('Admin/RoomBookings/OnlineBookings', [
            'bookings' => $bookings,
        ]);
    }

    public function manualBookings(): Response
    {
        $bookings = RoomBooking::with(['customer', 'rooms.room', 'rooms.roomType'])
            ->where('source', 'admin')
            ->orderByDesc('id')->get();
        $roomTypes = RoomType::orderBy('name')->get(['id', 'name']);

        return Inertia::render('Admin/RoomBookings/ManualBookings', [
            'bookings'  => $bookings,
            'roomTypes' => $roomTypes,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $export = $this->buildBookingExport($request);

        $pdf = Pdf::loadView('admin.bookings.pdf', $export)->setPaper('a4', 'landscape');

        $filename = $export['type'] . '_bookings_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * HTML print view: renders the same list and auto-opens the browser's native
     * print dialog on load, so "Print" prints directly instead of just producing
     * a PDF the admin then has to print manually.
     */
    public function printView(Request $request): View
    {
        $export = $this->buildBookingExport($request);

        return view('admin.bookings.print', $export);
    }

    private function buildBookingExport(Request $request): array
    {
        $data = $request->validate([
            'type'  => 'required|in:all,online,manual',
            'range' => 'required|in:today,7days,month,lastmonth,custom',
            'start' => 'required_if:range,custom|nullable|date',
            'end'   => 'required_if:range,custom|nullable|date|after_or_equal:start',
        ]);

        [$from, $to] = $this->resolveExportDateRange($data['range'], $data['start'] ?? null, $data['end'] ?? null);

        $query = RoomBooking::with(['customer', 'rooms.room', 'rooms.roomType'])->orderByDesc('id');

        if ($data['type'] === 'online') {
            $query->where('source', 'web');
        } elseif ($data['type'] === 'manual') {
            $query->where('source', 'admin');
        }

        if ($from && $to) {
            $query->whereBetween('check_in_date', [$from, $to]);
        }

        $bookings = $query->get()->map(function (RoomBooking $b) {
            $rooms = $b->rooms;
            $roomType = $rooms->isNotEmpty() ? ($rooms->first()->roomType?->name ?? 'Unassigned') : '—';
            if ($rooms->count() > 1) {
                $roomType .= ' +' . ($rooms->count() - 1) . ' more';
            }

            return [
                'reference'  => $b->booking_reference,
                'guest_name' => $b->customer?->name ?? '—',
                'guest_phone'=> $b->customer?->phone ?? '—',
                'room_type'  => $roomType,
                'check_in'   => optional($b->check_in_date)->format('d M Y'),
                'check_out'  => optional($b->check_out_date)->format('d M Y'),
                'nights'     => $b->total_nights,
                'amount'     => number_format((float) $b->total_amount, 2),
                'source'     => $b->source === 'web' ? 'Online' : 'Manual',
                'status'     => ucwords(str_replace('_', ' ', $b->booking_status)),
            ];
        });

        $titles = ['all' => 'All Bookings', 'online' => 'Online Bookings', 'manual' => 'Manual Bookings'];

        $subtitle = $from && $to
            ? Carbon::parse($from)->format('d M Y') . ' – ' . Carbon::parse($to)->format('d M Y')
            : 'All time';

        return [
            'type'     => $data['type'],
            'title'    => $titles[$data['type']],
            'subtitle' => $subtitle . ' · ' . $bookings->count() . ' booking' . ($bookings->count() === 1 ? '' : 's'),
            'bookings' => $bookings,
        ];
    }

    /**
     * @return array{0: ?string, 1: ?string}
     */
    private function resolveExportDateRange(string $range, ?string $start, ?string $end): array
    {
        $now = Carbon::now();

        return match ($range) {
            'today'     => [$now->copy()->toDateString(), $now->copy()->toDateString()],
            '7days'     => [$now->copy()->subDays(6)->toDateString(), $now->copy()->toDateString()],
            'month'     => [$now->copy()->startOfMonth()->toDateString(), $now->copy()->endOfMonth()->toDateString()],
            'lastmonth' => [$now->copy()->subMonthNoOverflow()->startOfMonth()->toDateString(), $now->copy()->subMonthNoOverflow()->endOfMonth()->toDateString()],
            'custom'    => [$start, $end],
        };
    }

    public function cancelledBookings(): Response
    {
        $bookings = RoomBooking::with(['customer', 'rooms.room', 'rooms.roomType'])
            ->where('booking_status', 'cancelled')
            ->orderByDesc('id')->get();

        return Inertia::render('Admin/RoomBookings/Cancelled', [
            'bookings' => $bookings,
        ]);
    }

    public function history(Request $request): Response
    {
        $search  = $request->get('search');
        $booking = null;
        $logs    = [];

        if ($search) {
            $booking = RoomBooking::with(['customer', 'rooms.room', 'rooms.roomType', 'logs', 'followUps'])
                ->where('booking_reference', $search)
                ->orWhere('id', is_numeric($search) ? $search : 0)
                ->first();

            if ($booking) {
                $timeline = collect();

                foreach ($booking->logs as $log) {
                    $timeline->push([
                        'time'        => $log->created_at,
                        'action'      => $log->action,
                        'description' => $log->description,
                        'old_value'   => $log->old_value,
                        'new_value'   => $log->new_value,
                        'by'          => $log->performed_by,
                        'title'       => null,
                    ]);
                }

                $timeline->push([
                    'time'        => $booking->created_at,
                    'action'      => 'created',
                    'description' => 'Booking was created',
                    'old_value'   => null,
                    'new_value'   => $booking->booking_status,
                    'by'          => $booking->source === 'web' ? 'Online (Guest)' : 'Admin',
                    'title'       => null,
                ]);

                // Each room line checks in/out independently, so the timeline lists them per room.
                foreach ($booking->rooms as $line) {
                    $label = $line->room?->room_number ?? $line->roomType?->name ?? ('Room line #' . $line->id);

                    if ($line->actual_check_in_at) {
                        $timeline->push([
                            'time'        => $line->actual_check_in_at,
                            'action'      => 'checked_in',
                            'description' => "Guest checked in — {$label}",
                            'old_value'   => null,
                            'new_value'   => null,
                            'by'          => null,
                            'title'       => null,
                        ]);
                    }

                    if ($line->actual_check_out_at) {
                        $timeline->push([
                            'time'        => $line->actual_check_out_at,
                            'action'      => 'checked_out',
                            'description' => "Guest checked out — {$label}",
                            'old_value'   => null,
                            'new_value'   => null,
                            'by'          => null,
                            'title'       => null,
                        ]);
                    }
                }

                foreach ($booking->followUps as $fu) {
                    $timeline->push([
                        'time'        => $fu->created_at,
                        'action'      => 'follow_up',
                        'title'       => $fu->title,
                        'description' => $fu->description,
                        'old_value'   => null,
                        'new_value'   => null,
                        'by'          => null,
                    ]);
                }

                $logs = $timeline->sortBy('time')->values()->toArray();
            }
        }

        return Inertia::render('Admin/RoomBookings/History', [
            'booking' => $booking,
            'logs'    => $logs,
            'search'  => $search,
        ]);
    }

    public function checkoutWithPayment(Request $request, RoomBooking $roomBooking): RedirectResponse
    {
        if ($roomBooking->booking_status !== 'checked_in') {
            return redirect()->route('admin.room-bookings.show', $roomBooking->id)
                ->with('error', 'Booking must be checked in before checkout.');
        }

        $data = $request->validate([
            'collected_amount' => 'nullable|numeric|min:0',
            'discount_type'    => 'nullable|in:fixed,percent',
            'discount_value'   => 'nullable|numeric|min:0',
            'notes'            => 'nullable|string',
        ]);

        $discountAmount = 0;
        if (!empty($data['discount_value'])) {
            if (($data['discount_type'] ?? 'fixed') === 'percent') {
                $discountAmount = round((float) $roomBooking->total_amount * (float) $data['discount_value'] / 100, 2);
            } else {
                $discountAmount = round((float) $data['discount_value'], 2);
            }
        }

        $collectedNow   = (float) ($data['collected_amount'] ?? 0);
        $newAdvancePaid = (float) $roomBooking->advance_payment + $collectedNow;

        try {
            DB::transaction(function () use ($roomBooking, $newAdvancePaid, $discountAmount, $data) {
                $this->applyStatusToLines($roomBooking, 'checked_in', 'checked_out');

                $updateData = [
                    'booking_status'  => 'checked_out',
                    'advance_payment' => $newAdvancePaid,
                    'discount_amount' => $discountAmount,
                ];
                if (!empty($data['notes'])) {
                    $updateData['notes'] = $data['notes'];
                }
                $roomBooking->update($updateData);
            });

            $curSym = $roomBooking->currency === 'USD' ? '$' : '৳';

            $desc = 'Guest checked out.';
            if ($collectedNow > 0) {
                $desc .= ' Collected: ' . $curSym . number_format($collectedNow, 2) . '.';
            }
            if ($discountAmount > 0) {
                $discountLabel = (($data['discount_type'] ?? 'fixed') === 'percent')
                    ? $data['discount_value'] . '% (' . $curSym . number_format($discountAmount, 2) . ')'
                    : $curSym . number_format($discountAmount, 2);
                $desc .= ' Discount applied: ' . $discountLabel . '.';
            }

            $remaining = (float) $roomBooking->total_amount - $newAdvancePaid - $discountAmount;
            if ($remaining > 0) {
                $desc .= ' Outstanding balance: ' . $curSym . number_format($remaining, 2) . '.';
            }

            BookingLog::create([
                'booking_id'   => $roomBooking->id,
                'action'       => 'checked_out',
                'description'  => $desc,
                'old_value'    => 'checked_in',
                'new_value'    => 'checked_out',
                'performed_by' => auth()->user()->name ?? 'Admin',
            ]);
        } catch (\Throwable $e) {
            Log::error('Checkout failed for ' . $roomBooking->booking_reference . ': ' . $e->getMessage());
            return redirect()->route('admin.room-bookings.show', $roomBooking->id)
                ->with('error', 'Checkout failed: ' . $e->getMessage());
        }

        $this->sendStatusEmail($roomBooking->fresh(['customer', 'rooms.roomType', 'rooms.room']), $data['notes'] ?? '');

        return redirect()->route('admin.room-bookings.show', $roomBooking->id)
            ->with('success', 'Guest checked out successfully.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    private function validateBookingData(Request $request, bool $isUpdate): array
    {
        $request->merge([
            'is_foreign_guest' => $request->boolean('is_foreign_guest'),
            'is_couple'        => $request->boolean('is_couple'),
        ]);

        foreach (['nid_front_doc', 'nid_back_doc', 'passport_doc', 'visa_doc', 'marriage_cert_doc', 'guest_photo_doc', 'other_documents'] as $fileKey) {
            if (!$request->hasFile($fileKey)) {
                $request->request->remove($fileKey);
            }
        }

        $rules = [
            'check_in_date'     => 'required|date' . ($isUpdate ? '' : '|after_or_equal:today'),
            'check_out_date'    => 'required|date|after:check_in_date',
            'currency'          => 'nullable|in:BDT,USD',
            'advance_payment'   => 'nullable|numeric|min:0',
            'payment_method'    => 'required|in:cash,card,bkash,nagad,bank_transfer',
            'special_requests'  => 'nullable|string',
            'notes'             => 'nullable|string',
            'rooms'                    => 'required|array|min:1',
            'rooms.*.id'               => 'nullable|integer|exists:booking_rooms,id',
            'rooms.*.room_type_id'     => 'required|exists:room_types,id',
            'rooms.*.room_id'          => 'nullable|exists:rooms,id',
            'rooms.*.adults'           => 'required|integer|min:1',
            'rooms.*.children'         => 'required|integer|min:0',
            'rooms.*.price_per_night'  => 'required|numeric|min:0',
        ];

        if ($isUpdate) {
            $rules['booking_status']     = 'required|in:pending,confirmed,payment_pending,checked_in,checked_out,cancelled';
            $rules['customer_name']      = 'required|string';
            $rules['customer_phone']     = 'nullable|string';
        } else {
            $rules['booking_status']         = 'required|in:pending,confirmed';
            $rules['customer_id']            = 'nullable|exists:customers,id';
            $rules['customer_name']          = 'required_without:customer_id|nullable|string';
            $rules['customer_phone']         = 'required_without:customer_id|nullable|string';
        }

        // Extended Guest Profile & KYC Fields
        $rules['father_name']       = 'nullable|string|max:150';
        $rules['mother_name']       = 'nullable|string|max:150';
        $rules['gender']            = 'nullable|in:male,female,other';
        $rules['date_of_birth']     = 'nullable|date|before:today';
        $rules['customer_email']    = 'nullable|email|max:150';
        $rules['customer_nationality'] = 'nullable|string|max:100';
        $rules['occupation']        = 'nullable|string|max:150';
        $rules['emergency_contact'] = 'nullable|string|max:30';
        $rules['customer_address']  = 'nullable|string';
        $rules['present_address']   = 'nullable|string';
        $rules['permanent_address'] = 'nullable|string';
        $rules['district_id']       = 'nullable|exists:districts,id';
        $rules['upazila_id']        = 'nullable|exists:upazilas,id';
        $rules['police_station_id'] = 'nullable|exists:police_stations,id';
        $rules['post_code']         = 'nullable|string|max:20';

        // Identity
        $rules['document_type']            = 'nullable|in:nid,passport,other';
        $rules['nid_number']               = 'nullable|string|max:30';
        $rules['birth_certificate_number'] = 'nullable|string|max:30';
        $rules['passport_number']          = 'nullable|string|max:30';
        $rules['driving_license_number']   = 'nullable|string|max:30';

        // Foreign guest
        $rules['is_foreign_guest'] = 'boolean';
        $rules['visa_number']      = 'required_if:is_foreign_guest,true,1|nullable|string|max:50';
        $rules['arrival_date_bd']  = 'required_if:is_foreign_guest,true,1|nullable|date';

        // Couple
        $rules['is_couple']     = 'boolean';
        $rules['spouse_name']   = 'required_if:is_couple,true,1|nullable|string|max:150';
        $rules['marriage_date'] = 'nullable|date';

        // Police Flag
        $rules['is_flagged']   = 'boolean';
        $rules['flagged_note'] = 'nullable|string';

        // Documents
        $rules['nid_front_doc']     = 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120';
        $rules['nid_back_doc']      = 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120';
        $rules['passport_doc']      = 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120';
        $rules['visa_doc']          = 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120';
        $rules['marriage_cert_doc'] = 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120';
        $rules['guest_photo_doc']   = 'nullable|image|mimes:jpeg,jpg,png,webp|max:5120';
        $rules['other_documents']   = 'nullable|array';
        $rules['other_documents.*'] = 'nullable|file|mimes:jpeg,jpg,png,pdf|max:5120';

        return $request->validate($rules);
    }

    private function extractCustomerPayload(array $data, Request $request): array
    {
        $payload = [
            'name'                      => $data['customer_name'],
            'father_name'               => $data['father_name'] ?? null,
            'mother_name'               => $data['mother_name'] ?? null,
            'gender'                    => $data['gender'] ?? null,
            'date_of_birth'             => $data['date_of_birth'] ?? null,
            'email'                     => $data['customer_email'] ?? null,
            'nationality'               => $data['customer_nationality'] ?? 'Bangladeshi',
            'occupation'                => $data['occupation'] ?? null,
            'emergency_contact'         => $data['emergency_contact'] ?? null,
            'address'                   => $data['customer_address'] ?? null,
            'present_address'           => $data['present_address'] ?? $data['customer_address'] ?? null,
            'permanent_address'         => $data['permanent_address'] ?? null,
            'district_id'               => $data['district_id'] ?? null,
            'upazila_id'                => $data['upazila_id'] ?? null,
            'police_station_id'         => $data['police_station_id'] ?? null,
            'post_code'                 => $data['post_code'] ?? null,
            'document_type'             => $data['document_type'] ?? 'nid',
            'nid_number'                => $data['nid_number'] ?? null,
            'birth_certificate_number'  => $data['birth_certificate_number'] ?? null,
            'passport_number'           => $data['passport_number'] ?? null,
            'driving_license_number'    => $data['driving_license_number'] ?? null,
            'is_foreign_guest'          => $request->boolean('is_foreign_guest'),
            'visa_number'               => $data['visa_number'] ?? null,
            'arrival_date_bd'           => $data['arrival_date_bd'] ?? null,
            'is_couple'                 => $request->boolean('is_couple'),
            'spouse_name'               => $data['spouse_name'] ?? null,
            'marriage_date'             => $data['marriage_date'] ?? null,
        ];

        if (!empty($data['customer_phone'])) {
            $payload['phone'] = $data['customer_phone'];
        }

        return $payload;
    }

    /**
     * Guards against assigning the same physical room twice within one submitted booking, and
     * against assigning a room that's already booked elsewhere for an overlapping stay.
     */
    private function checkLineConflicts(array $rooms, string $checkIn, string $checkOut, ?int $excludeBookingId = null): ?array
    {
        $roomIds = collect($rooms)->pluck('room_id')->filter()->values();
        if ($roomIds->count() !== $roomIds->unique()->count()) {
            return ['rooms' => 'The same room cannot be assigned twice in one booking.'];
        }

        foreach ($rooms as $i => $line) {
            if (empty($line['room_id'])) {
                continue;
            }

            $conflict = BookingRoom::where('room_id', $line['room_id'])
                ->activeLine()
                ->withBookingStatus(self::BLOCKING_STATUSES)
                ->overlapping($checkIn, $checkOut)
                ->when($excludeBookingId, fn ($q) => $q->where('room_booking_id', '!=', $excludeBookingId))
                ->exists();

            if ($conflict) {
                return ["rooms.$i.room_id" => 'This room is not available for the selected dates.'];
            }
        }

        return null;
    }

    private function resolveCustomer(array $data): Customer
    {
        $payload = $this->extractCustomerPayload($data, request());

        if (!empty($data['customer_id'])) {
            $customer = Customer::findOrFail($data['customer_id']);
            $customer->update(array_filter($payload, fn ($v) => $v !== null));
            return $customer;
        }

        return Customer::updateOrCreate(
            ['phone' => $data['customer_phone']],
            $payload
        );
    }

    /**
     * @return array{0: float, 1: int, 2: int} totalAmount, totalAdults, totalChildren
     */
    private function sumLines(array $rooms, int $totalNights): array
    {
        $totalAmount = 0;
        $totalAdults = 0;
        $totalChildren = 0;
        foreach ($rooms as $line) {
            $totalAmount   += $line['price_per_night'] * $totalNights;
            $totalAdults   += $line['adults'];
            $totalChildren += $line['children'];
        }

        return [$totalAmount, $totalAdults, $totalChildren];
    }

    /**
     * Bulk-applies a booking-level status change to every still-active room line — the default
     * "apply to all rooms" path. Lines already cancelled individually are left untouched.
     */
    private function applyStatusToLines(RoomBooking $booking, string $oldStatus, string $newStatus): void
    {
        $lines = $booking->rooms()->activeLine()->get();

        foreach ($lines as $line) {
            $lineUpdate = ['status' => $newStatus];

            if ($newStatus === 'checked_in' && $line->status !== 'checked_in') {
                $lineUpdate['actual_check_in_at'] = now();
                $line->room?->update(['status' => 'occupied']);
            }

            if ($newStatus === 'checked_out' && $line->status === 'checked_in') {
                $lineUpdate['actual_check_out_at'] = now();
                $line->room?->update(['status' => 'available']);
            }

            if ($newStatus === 'cancelled' && in_array($line->status, ['confirmed', 'checked_in', 'payment_pending'])) {
                $line->room?->update(['status' => 'available']);
            }

            $line->update($lineUpdate);
        }
    }

    /**
     * Recomputes the booking's combined total/occupancy from its currently-active lines, so a
     * cancelled room line stops counting toward the amount due.
     */
    private function recalculateBookingTotals(RoomBooking $booking): void
    {
        $activeLines = $booking->rooms()->activeLine()->get();

        $booking->update([
            'total_amount' => $activeLines->sum('line_total'),
            'adults'       => $activeLines->sum('adults') ?: $booking->adults,
            'children'     => $activeLines->sum('children'),
        ]);
    }

    private function generateReference(): string
    {
        do {
            $ref = 'HBW-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
        } while (RoomBooking::where('booking_reference', $ref)->exists());

        return $ref;
    }
}
