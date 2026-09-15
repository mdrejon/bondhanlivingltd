<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingRoom;
use App\Models\Customer;
use App\Support\AuditLogger;
use App\Support\GovernmentStats;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Read-only government monitoring reports (PRD § 5.4) — DC Office / UNO Office / Police
 * Admin. Deliberately a separate controller (and `gov-reports` module permission) from
 * Admin\ReportController, which covers hotel financials these roles must never see.
 *
 * Every query here is scoped automatically: `Customer` carries the tenant global scope
 * directly, and `BookingRoom` inherits it via `whereHas('booking', ...)` /
 * eager-loading `booking` (RoomBooking is scoped; BookingRoom itself intentionally isn't
 * — see docs/hgrm-saas/DATABASE-SCHEMA.md). No manual hotel_id filtering needed anywhere
 * below — a DC/UNO/Police account simply never sees rows outside their jurisdiction
 * because the underlying Eloquent query can't return them.
 */
class GovernmentReportController extends Controller
{
    public function hotels(): Response
    {
        return Inertia::render('Admin/Government/HotelReport', [
            'rows' => GovernmentStats::perHotelSummary(),
        ]);
    }

    public function guests(Request $request): Response
    {
        $hotels = GovernmentStats::visibleHotels()->map(fn ($h) => ['id' => $h->id, 'name' => $h->name]);

        return Inertia::render('Admin/Government/GuestReport', [
            'rows'          => $this->guestRows($request),
            'hotels'        => $hotels,
            'filters'       => $this->currentFilters($request),
            'canManageFlag' => auth()->user()->canManagePoliceFlag(),
        ]);
    }

    public function nationality(Request $request): Response
    {
        return Inertia::render('Admin/Government/Nationality', [
            'rows'    => $this->nationalityRows($request),
            'filters' => [
                'country'      => trim((string) $request->get('country', '')),
                'foreign_only' => $request->boolean('foreign_only'),
            ],
        ]);
    }

    public function nidSearch(Request $request): Response
    {
        $term    = trim((string) $request->get('query', $request->get('nid', '')));
        $hotelId = $request->get('hotel_id');
        $hotels  = GovernmentStats::visibleHotels()->map(fn ($h) => ['id' => $h->id, 'name' => $h->name]);
        $results = collect();

        if ($term !== '') {
            $results = Customer::where(function ($q) use ($term) {
                    $q->where('nid_number', 'like', "%{$term}%")
                        ->orWhere('passport_number', 'like', "%{$term}%");
                })
                ->when($hotelId, fn ($q) => $q->where('hotel_id', $hotelId))
                ->with('hotel:id,name')
                ->orderBy('name')
                ->limit(50)
                ->get(['id', 'hotel_id', 'name', 'phone', 'nid_number', 'passport_number', 'nationality', 'is_foreign_guest']);

            AuditLogger::log('searched_nid', null, ['query' => $term, 'hotel_id' => $hotelId, 'results_count' => $results->count()]);
        }

        return Inertia::render('Admin/Government/NidSearch', [
            'query'   => $term,
            'hotelId' => $hotelId,
            'hotels'  => $hotels,
            'results' => $results,
        ]);
    }

    public function exportCsv(Request $request): HttpResponse
    {
        $type = $request->get('type', 'hotels');
        [$headers, $rows] = $this->exportData($request, $type);

        AuditLogger::log('exported_report', null, ['type' => $type, 'format' => 'csv', 'rows' => count($rows)]);

        $output = implode(',', $headers) . "\n";
        foreach ($rows as $row) {
            $output .= implode(',', array_map(fn ($v) => '"' . str_replace('"', '""', (string) $v) . '"', $row)) . "\n";
        }

        return response($output, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="government_' . $type . '_' . now()->format('Ymd_His') . '.csv"',
        ]);
    }

    public function exportPdf(Request $request)
    {
        $type = $request->get('type', 'hotels');
        [$headers, $rows] = $this->exportData($request, $type);

        AuditLogger::log('exported_report', null, ['type' => $type, 'format' => 'pdf', 'rows' => count($rows)]);

        $columns = array_map(fn ($h) => ['key' => $h, 'label' => $h], $headers);
        $pdfRows = array_map(fn ($row) => array_combine($headers, $row), $rows);

        $pdf = Pdf::loadView('admin.government.pdf', [
            'title'   => ucfirst($type) . ' Report',
            'columns' => $columns,
            'rows'    => $pdfRows,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('government_' . $type . '_' . now()->format('Ymd_His') . '.pdf');
    }

    private function exportData(Request $request, string $type): array
    {
        if ($type === 'guests') {
            $headers = ['Guest Name', 'NID', 'Mobile', 'Hotel', 'Room', 'Check-in', 'Check-out', 'Status'];
            $rows = $this->guestRows($request)->map(fn ($r) => [
                $r['guest_name'], $r['nid'], $r['mobile'], $r['hotel_name'], $r['room'],
                $r['check_in'], $r['check_out'] ?? '—', $r['status'],
            ])->all();

            return [$headers, $rows];
        }

        if ($type === 'nationality') {
            $headers = ['Nationality', 'Guest Count', 'Foreign'];
            $rows = $this->nationalityRows($request)->map(fn ($r) => [$r['nationality'], $r['count'], $r['is_foreign'] ? 'Yes' : 'No'])->all();

            return [$headers, $rows];
        }

        // hotels
        $headers = ['Hotel', 'District', 'Upazila', 'Current Guests', 'Today Check-in', 'Today Check-out'];
        $rows = GovernmentStats::perHotelSummary()->map(fn ($r) => [
            $r['hotel_name'], $r['district'] ?? '—', $r['upazila'] ?? '—',
            $r['current_guests'], $r['today_checkin'], $r['today_checkout'],
        ])->all();

        return [$headers, $rows];
    }

    private function guestRows(Request $request): Collection
    {
        $filters = $this->currentFilters($request);

        $query = BookingRoom::whereHas('booking')
            ->with([
                'booking:id,hotel_id,customer_id,check_in_date,check_out_date',
                'booking.hotel:id,name',
                'booking.customer',
                'booking.customer.documents',
                'room:id,room_number',
                'roomType:id,name',
            ]);

        if ($filters['hotel_id']) {
            $query->whereHas('booking', fn ($q) => $q->where('hotel_id', $filters['hotel_id']));
        }

        if ($filters['date_from']) {
            $from = Carbon::parse($filters['date_from'])->startOfDay();
            $to   = Carbon::parse($filters['date_to'])->endOfDay();

            $query->where(function ($q) use ($from, $to) {
                $q->whereBetween('actual_check_in_at', [$from, $to])
                    ->orWhereBetween('actual_check_out_at', [$from, $to]);
            });
        }

        if ($filters['search']) {
            $term = $filters['search'];
            $query->whereHas('booking.customer', function ($q) use ($term) {
                $q->where('phone', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('nid_number', 'like', "%{$term}%")
                    ->orWhere('passport_number', 'like', "%{$term}%");
            });
        }

        if ($filters['nationality']) {
            $query->whereHas('booking.customer', fn ($q) => $q->where('nationality', $filters['nationality']));
        }

        if ($filters['foreign_only']) {
            $query->whereHas('booking.customer', fn ($q) => $q->where('is_foreign_guest', true));
        }

        if ($filters['flagged_only'] && auth()->user()->canManagePoliceFlag()) {
            $query->whereHas('booking.customer', fn ($q) => $q->where('is_flagged', true));
        }

        if ($filters['status']) {
            $query->where('status', $filters['status']);
        }

        return $query->orderByDesc('id')->limit(500)->get()->map(function (BookingRoom $line) {
            $customer = $line->booking->customer;
            $photo    = $customer?->documents->firstWhere('category', 'guest_photo');

            return [
                'id'                      => $line->id,
                'customer_id'             => $customer?->id,
                'guest_name'              => $customer?->name ?? '—',
                'father_name'             => $customer?->father_name,
                'mother_name'             => $customer?->mother_name,
                'gender'                  => $customer?->gender,
                'address'                 => $customer?->present_address ?? $customer?->address,
                'emergency_contact'       => $customer?->emergency_contact,
                'email'                   => $customer?->email,
                'photo_document_id'       => $photo?->id,
                'legacy_photo_path'       => $photo ? null : $customer?->document_image,
                'nid'                     => $customer?->nid_number,
                'passport'                => $customer?->passport_number,
                'driving_license'         => $customer?->driving_license_number,
                'birth_certificate'       => $customer?->birth_certificate_number,
                'mobile'                  => $customer?->phone ?? '—',
                'hotel_name'              => $line->booking->hotel?->name ?? '—',
                'room'                    => $line->room?->room_number ?? $line->roomType?->name ?? '—',
                'check_in'                => optional($line->actual_check_in_at)->format('d M Y, h:i A') ?? $line->booking->check_in_date?->format('d M Y'),
                'check_out'               => optional($line->actual_check_out_at)->format('d M Y, h:i A'),
                'status'                  => $line->status,
                'is_foreign'              => (bool) $customer?->is_foreign_guest,
                'nationality'             => $customer?->nationality,
                'is_couple'               => (bool) $customer?->is_couple,
                'spouse_name'             => $customer?->spouse_name,
                'is_flagged'              => auth()->user()->canManagePoliceFlag() ? (bool) $customer?->is_flagged : null,
            ];
        });
    }

    private function nationalityRows(?Request $request = null): Collection
    {
        $query = Customer::selectRaw('nationality, is_foreign_guest, count(*) as total')
            ->whereNotNull('nationality');

        $country = trim((string) $request?->get('country', ''));
        if ($country !== '') {
            $query->where('nationality', 'like', "%{$country}%");
        }

        if ($request?->boolean('foreign_only')) {
            $query->where('is_foreign_guest', true);
        }

        return $query->groupBy('nationality', 'is_foreign_guest')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => [
                'nationality' => $row->nationality,
                'count'       => (int) $row->total,
                'is_foreign'  => (bool) $row->is_foreign_guest,
            ]);
    }

    /**
     * No default date range — the report shows recent guests across all dates
     * unless the user (or a quick-link like "Date-wise Report") explicitly
     * sets date_from/date_to.
     */
    private function currentFilters(Request $request): array
    {
        $dateFrom = $request->get('date_from', $request->get('date'));
        $dateTo   = $request->get('date_to', $request->get('date'));

        if ($dateFrom && !$dateTo) {
            $dateTo = $dateFrom;
        } elseif ($dateTo && !$dateFrom) {
            $dateFrom = $dateTo;
        }

        return [
            'hotel_id'     => $request->get('hotel_id'),
            'date_from'    => $dateFrom,
            'date_to'      => $dateTo,
            'search'       => trim((string) $request->get('search', '')),
            'nationality'  => $request->get('nationality'),
            'foreign_only' => $request->boolean('foreign_only'),
            'flagged_only' => $request->boolean('flagged_only'),
            'status'       => $request->get('status'),
        ];
    }
}
