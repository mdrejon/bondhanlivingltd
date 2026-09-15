<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingRoom;
use App\Models\RoomBooking;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // INCOME REPORT
    // ─────────────────────────────────────────────────────────────────────────

    public function income(Request $request): Response
    {
        $period = $request->get('period', 'monthly');
        $year   = (int) $request->get('year',  now()->year);
        $month  = (int) $request->get('month', now()->month);

        $bookings = $this->buildBaseQuery($request)->get();

        // Summary
        $summary = [
            'total_bookings' => $bookings->count(),
            'total_revenue'  => (float) $bookings->sum('total_amount'),
            'total_advance'  => (float) $bookings->sum('advance_payment'),
            'total_due'      => (float) ($bookings->sum('total_amount') - $bookings->sum('advance_payment')),
        ];

        // Rows
        $rows = $this->groupRows($bookings, $period)->map(function ($group, $key) {
            return [
                'date_label'      => $group['label'],
                'bookings_count'  => $group['items']->count(),
                'total_amount'    => (float) $group['items']->sum('total_amount'),
                'advance_payment' => (float) $group['items']->sum('advance_payment'),
                'due_amount'      => (float) ($group['items']->sum('total_amount') - $group['items']->sum('advance_payment')),
            ];
        })->values();

        return Inertia::render('Admin/Reports/Income', [
            'filters'     => $this->currentFilters($request),
            'summary'     => $summary,
            'rows'        => $rows,
            'years'       => $this->yearsList(),
            'report_type' => 'income',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // DISCOUNT REPORT
    // ─────────────────────────────────────────────────────────────────────────

    public function discount(Request $request): Response
    {
        $period = $request->get('period', 'monthly');

        // All non-cancelled for summary counts
        $allBookings      = $this->buildBaseQuery($request)->get();
        // Only those with discount for detail rows
        $discountBookings = $this->buildBaseQuery($request)->where('discount_amount', '>', 0)->get();

        $summary = [
            'total_bookings'    => $allBookings->count(),
            'discount_bookings' => $discountBookings->count(),
            'total_discount'    => (float) $discountBookings->sum('discount_amount'),
            'total_original'    => (float) $discountBookings->sum(fn ($b) => $b->total_amount + $b->discount_amount),
            'total_final'       => (float) $discountBookings->sum('total_amount'),
        ];

        $rows = $this->groupRows($discountBookings, $period)->map(function ($group) {
            $items = $group['items'];
            return [
                'date_label'      => $group['label'],
                'bookings_count'  => $items->count(),
                'discount_count'  => $items->where('discount_amount', '>', 0)->count(),
                'total_discount'  => (float) $items->sum('discount_amount'),
                'original_amount' => (float) $items->sum(fn ($b) => $b->total_amount + $b->discount_amount),
                'final_amount'    => (float) $items->sum('total_amount'),
            ];
        })->values();

        return Inertia::render('Admin/Reports/Discount', [
            'filters'     => $this->currentFilters($request),
            'summary'     => $summary,
            'rows'        => $rows,
            'years'       => $this->yearsList(),
            'report_type' => 'discount',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // BOOKING REPORT
    // ─────────────────────────────────────────────────────────────────────────

    public function booking(Request $request): Response
    {
        $groupBy = $request->get('group_by', 'room_type');

        // For summary we need all bookings (including cancelled for cancelled count)
        $allBookings = RoomBooking::query();
        $this->applyDateFilter($allBookings, $request);
        $allBookings = $allBookings->get();

        $nonCancelled = $allBookings->whereNotIn('booking_status', ['cancelled']);

        $summary = [
            'total_bookings'  => $nonCancelled->count(),
            'total_revenue'   => (float) $nonCancelled->sum('total_amount'),
            'total_cancelled' => $allBookings->where('booking_status', 'cancelled')->count(),
            'total_checked_out' => $allBookings->where('booking_status', 'checked_out')->count(),
        ];

        $rows = collect();

        if ($groupBy === 'room_type') {
            $rows = $this->groupLinesBy($request, 'room_type_id', fn ($line) => $line->roomType?->name ?? 'Unassigned');
        } elseif ($groupBy === 'room') {
            $rows = $this->groupLinesBy($request, 'room_id', fn ($line) => $line->room?->room_number ?? $line->room?->room_name ?? 'Unassigned');
        } else { // status
            $total = $allBookings->count();
            $rows = $allBookings->groupBy('booking_status')->map(function ($group, $status) use ($total) {
                return [
                    'label'          => ucfirst(str_replace('_', ' ', $status)),
                    'bookings_count' => $group->count(),
                    'total_amount'   => (float) $group->sum('total_amount'),
                    'percentage'     => $total > 0 ? round(($group->count() / $total) * 100, 1) : 0,
                ];
            })->values();
        }

        return Inertia::render('Admin/Reports/Booking', [
            'filters'     => array_merge($this->currentFilters($request), ['group_by' => $groupBy]),
            'summary'     => $summary,
            'rows'        => $rows,
            'years'       => $this->yearsList(),
            'report_type' => 'booking',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // EXPORT CSV
    // ─────────────────────────────────────────────────────────────────────────

    public function exportCsv(Request $request): HttpResponse
    {
        $type = $request->get('type', 'income');
        [$title, $headers, $csvRows] = $this->buildExportData($request, $type);

        $filename = $type . '_report_' . now()->format('Ymd_His') . '.csv';

        $output = implode(',', $headers) . "\n";
        foreach ($csvRows as $row) {
            $output .= implode(',', array_map(fn ($v) => '"' . str_replace('"', '""', $v) . '"', $row)) . "\n";
        }

        return response($output, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // EXPORT PDF
    // ─────────────────────────────────────────────────────────────────────────

    public function exportPdf(Request $request)
    {
        $type = $request->get('type', 'income');
        [$title, $headers, $pdfRows, $summaryItems, $columns] = $this->buildExportData($request, $type, true);

        $period   = $request->get('period', 'monthly');
        $subtitle = $this->buildSubtitle($request, $period);

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'title'        => $title,
            'subtitle'     => $subtitle,
            'summaryItems' => $summaryItems,
            'columns'      => $columns,
            'rows'         => $pdfRows,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($type . '_report_' . now()->format('Ymd_His') . '.pdf');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    private function buildBaseQuery(Request $request, bool $excludeCancelled = true)
    {
        $q = RoomBooking::query();

        if ($excludeCancelled) {
            $q->whereNotIn('booking_status', ['cancelled']);
        }

        $this->applyDateFilter($q, $request);

        return $q;
    }

    private function applyDateFilter($q, Request $request): void
    {
        $period = $request->get('period', 'monthly');
        $year   = (int) $request->get('year',  now()->year);
        $month  = (int) $request->get('month', now()->month);

        if ($period === 'yearly') {
            $q->whereYear('check_in_date', $year);
        } elseif ($period === 'custom') {
            $from = $request->get('date_from');
            $to   = $request->get('date_to');
            if ($from && $to) {
                $q->whereBetween('check_in_date', [$from, $to]);
            }
        } else {
            // monthly
            $q->whereYear('check_in_date', $year)
              ->whereMonth('check_in_date', $month);
        }
    }

    /**
     * Room-type/room breakdowns operate on booking_rooms (one row per physical room) rather
     * than room_bookings, since a single booking can now span several room types at once.
     */
    private function lineRoomsQuery(Request $request)
    {
        return BookingRoom::query()
            ->whereHas('booking', function ($q) use ($request) {
                $this->applyDateFilter($q, $request);
            })
            ->with(['roomType:id,name', 'room:id,room_number,room_name', 'booking:id,total_amount,advance_payment']);
    }

    private function groupLinesBy(Request $request, string $column, \Closure $labelResolver): Collection
    {
        $lines = $this->lineRoomsQuery($request)->get();

        return $lines->groupBy($column)->map(function ($group) use ($labelResolver) {
            $first   = $group->first();
            $active  = $group->where('status', '!=', 'cancelled');
            $revenue = (float) $active->sum('line_total');
            $advance = round($active->sum(fn ($line) => $this->attributedAdvance($line)), 2);

            return [
                'label'           => $labelResolver($first),
                'bookings_count'  => $active->count(),
                'total_amount'    => $revenue,
                'advance_payment' => $advance,
                'due_amount'      => round($revenue - $advance, 2),
                'pending'         => $group->where('status', 'pending')->count(),
                'confirmed'       => $group->where('status', 'confirmed')->count(),
                'checked_in'      => $group->where('status', 'checked_in')->count(),
                'checked_out'     => $group->where('status', 'checked_out')->count(),
                'cancelled'       => $group->where('status', 'cancelled')->count(),
            ];
        })->sortBy('label')->values();
    }

    /**
     * A booking's advance payment covers the whole stay, not one room type — attribute each
     * line its proportional share of the total so per-room-type/room figures don't double
     * count the same payment across every room type in a multi-type booking.
     */
    private function attributedAdvance(BookingRoom $line): float
    {
        $booking = $line->booking;
        if (!$booking || (float) $booking->total_amount <= 0) {
            return 0.0;
        }

        $share = (float) $line->line_total / (float) $booking->total_amount;
        return (float) $booking->advance_payment * $share;
    }

    private function groupRows(Collection $bookings, string $period): Collection
    {
        if ($period === 'yearly') {
            $grouped = $bookings->groupBy(fn ($b) => $b->check_in_date->format('Y-m'));
            return $grouped->map(function ($items, $key) {
                return [
                    'label' => Carbon::createFromFormat('Y-m', $key)->format('M Y'),
                    'items' => $items,
                ];
            })->sortKeys();
        }

        $grouped = $bookings->groupBy(fn ($b) => $b->check_in_date->format('Y-m-d'));
        return $grouped->map(function ($items, $key) {
            return [
                'label' => Carbon::createFromFormat('Y-m-d', $key)->format('d M Y'),
                'items' => $items,
            ];
        })->sortKeys();
    }

    private function currentFilters(Request $request): array
    {
        return [
            'period'    => $request->get('period', 'monthly'),
            'year'      => (int) $request->get('year',  now()->year),
            'month'     => (int) $request->get('month', now()->month),
            'date_from' => $request->get('date_from', ''),
            'date_to'   => $request->get('date_to', ''),
        ];
    }

    private function yearsList(): array
    {
        $years = [];
        for ($y = 2020; $y <= now()->year; $y++) {
            $years[] = $y;
        }
        return $years;
    }

    private function buildSubtitle(Request $request, string $period): string
    {
        $year  = $request->get('year',  now()->year);
        $month = $request->get('month', now()->month);

        if ($period === 'monthly') {
            return Carbon::createFromDate($year, $month, 1)->format('F Y');
        }
        if ($period === 'yearly') {
            return 'Year ' . $year;
        }
        $from = $request->get('date_from', '');
        $to   = $request->get('date_to', '');
        return $from && $to ? $from . ' to ' . $to : 'Custom Range';
    }

    private function buildExportData(Request $request, string $type, bool $forPdf = false): array
    {
        $period   = $request->get('period', 'monthly');
        $bookings = $this->buildBaseQuery($request)->get();

        $summaryItems = [];
        $columns      = [];
        $csvHeaders   = [];
        $rows         = [];

        if ($type === 'income') {
            $title = 'Income Report';
            $summaryItems = [
                ['label' => 'Total Bookings', 'value' => $bookings->count()],
                ['label' => 'Total Revenue',  'value' => '৳ ' . number_format($bookings->sum('total_amount'), 2)],
                ['label' => 'Total Advance',  'value' => '৳ ' . number_format($bookings->sum('advance_payment'), 2)],
                ['label' => 'Total Due',      'value' => '৳ ' . number_format($bookings->sum('total_amount') - $bookings->sum('advance_payment'), 2)],
            ];
            $columns    = [
                ['key' => 'date_label',      'label' => 'Date',         'align' => ''],
                ['key' => 'bookings_count',  'label' => 'Bookings',     'align' => 'right'],
                ['key' => 'total_amount',    'label' => 'Revenue (BDT)', 'align' => 'right'],
                ['key' => 'advance_payment', 'label' => 'Advance Paid', 'align' => 'right'],
                ['key' => 'due_amount',      'label' => 'Due Amount',   'align' => 'right'],
            ];
            $csvHeaders = ['Date', 'Bookings', 'Revenue (BDT)', 'Advance Paid', 'Due Amount'];

            $grouped = $this->groupRows($bookings, $period);
            foreach ($grouped as $group) {
                $items = $group['items'];
                $row   = [
                    'date_label'      => $group['label'],
                    'bookings_count'  => $items->count(),
                    'total_amount'    => number_format($items->sum('total_amount'), 2),
                    'advance_payment' => number_format($items->sum('advance_payment'), 2),
                    'due_amount'      => number_format($items->sum('total_amount') - $items->sum('advance_payment'), 2),
                ];
                $rows[] = $forPdf ? $row : array_values($row);
            }
        } elseif ($type === 'discount') {
            $title            = 'Discount Report';
            $discountBookings = $this->buildBaseQuery($request)->where('discount_amount', '>', 0)->get();
            $summaryItems = [
                ['label' => 'Total Bookings',    'value' => $bookings->count()],
                ['label' => 'Discount Bookings', 'value' => $discountBookings->count()],
                ['label' => 'Total Discount',    'value' => '৳ ' . number_format($discountBookings->sum('discount_amount'), 2)],
                ['label' => 'Total Final',       'value' => '৳ ' . number_format($discountBookings->sum('total_amount'), 2)],
            ];
            $columns = [
                ['key' => 'date_label',      'label' => 'Date',            'align' => ''],
                ['key' => 'bookings_count',  'label' => 'Bookings',        'align' => 'right'],
                ['key' => 'discount_count',  'label' => 'Discounted',      'align' => 'right'],
                ['key' => 'total_discount',  'label' => 'Discount Amount', 'align' => 'right'],
                ['key' => 'original_amount', 'label' => 'Original Amount', 'align' => 'right'],
                ['key' => 'final_amount',    'label' => 'Final Amount',    'align' => 'right'],
            ];
            $csvHeaders = ['Date', 'Bookings', 'Discounted', 'Discount Amount', 'Original Amount', 'Final Amount'];

            $grouped = $this->groupRows($discountBookings, $period);
            foreach ($grouped as $group) {
                $items = $group['items'];
                $row   = [
                    'date_label'      => $group['label'],
                    'bookings_count'  => $items->count(),
                    'discount_count'  => $items->where('discount_amount', '>', 0)->count(),
                    'total_discount'  => number_format($items->sum('discount_amount'), 2),
                    'original_amount' => number_format($items->sum(fn ($b) => $b->total_amount + $b->discount_amount), 2),
                    'final_amount'    => number_format($items->sum('total_amount'), 2),
                ];
                $rows[] = $forPdf ? $row : array_values($row);
            }
        } else {
            // booking
            $title   = 'Booking Report';
            $groupBy = $request->get('group_by', 'room_type');

            $allBookings  = RoomBooking::query();
            $this->applyDateFilter($allBookings, $request);
            $allBookings  = $allBookings->get();
            $nonCancelled = $allBookings->whereNotIn('booking_status', ['cancelled']);

            $summaryItems = [
                ['label' => 'Total Bookings',  'value' => $nonCancelled->count()],
                ['label' => 'Total Revenue',   'value' => '৳ ' . number_format($nonCancelled->sum('total_amount'), 2)],
                ['label' => 'Cancelled',       'value' => $allBookings->where('booking_status', 'cancelled')->count()],
                ['label' => 'Checked Out',     'value' => $allBookings->where('booking_status', 'checked_out')->count()],
            ];

            if ($groupBy === 'room_type' || $groupBy === 'room') {
                $isRoomType = $groupBy === 'room_type';
                $columns    = [
                    ['key' => 'label',           'label' => $isRoomType ? 'Room Type' : 'Room', 'align' => ''],
                    ['key' => 'bookings_count',  'label' => 'Rooms',       'align' => 'right'],
                    ['key' => 'total_amount',    'label' => 'Revenue',     'align' => 'right'],
                    ['key' => 'advance_payment', 'label' => 'Advance',     'align' => 'right'],
                    ['key' => 'due_amount',      'label' => 'Due',         'align' => 'right'],
                    ['key' => 'pending',         'label' => 'Pending',     'align' => 'right'],
                    ['key' => 'confirmed',       'label' => 'Confirmed',   'align' => 'right'],
                    ['key' => 'checked_in',      'label' => 'Checked In',  'align' => 'right'],
                    ['key' => 'checked_out',     'label' => 'Checked Out', 'align' => 'right'],
                    ['key' => 'cancelled',       'label' => 'Cancelled',   'align' => 'right'],
                ];
                $csvHeaders = [$isRoomType ? 'Room Type' : 'Room', 'Rooms', 'Revenue', 'Advance', 'Due', 'Pending', 'Confirmed', 'Checked In', 'Checked Out', 'Cancelled'];

                $groupedRows = $isRoomType
                    ? $this->groupLinesBy($request, 'room_type_id', fn ($line) => $line->roomType?->name ?? 'Unassigned')
                    : $this->groupLinesBy($request, 'room_id', fn ($line) => $line->room?->room_number ?? $line->room?->room_name ?? 'Unassigned');

                foreach ($groupedRows as $line) {
                    $row = [
                        'label'           => $line['label'],
                        'bookings_count'  => $line['bookings_count'],
                        'total_amount'    => number_format($line['total_amount'], 2),
                        'advance_payment' => number_format($line['advance_payment'], 2),
                        'due_amount'      => number_format($line['due_amount'], 2),
                        'pending'         => $line['pending'],
                        'confirmed'       => $line['confirmed'],
                        'checked_in'      => $line['checked_in'],
                        'checked_out'     => $line['checked_out'],
                        'cancelled'       => $line['cancelled'],
                    ];
                    $rows[] = $forPdf ? $row : array_values($row);
                }
            } else {
                $total      = $allBookings->count();
                $columns    = [
                    ['key' => 'label',          'label' => 'Status',     'align' => ''],
                    ['key' => 'bookings_count', 'label' => 'Bookings',   'align' => 'right'],
                    ['key' => 'total_amount',   'label' => 'Revenue',    'align' => 'right'],
                    ['key' => 'percentage',     'label' => '% of Total', 'align' => 'right'],
                ];
                $csvHeaders = ['Status', 'Bookings', 'Revenue', '% of Total'];
                $grouped    = $allBookings->groupBy('booking_status');
                foreach ($grouped as $status => $group) {
                    $pct = $total > 0 ? round(($group->count() / $total) * 100, 1) : 0;
                    $row = [
                        'label'          => ucfirst(str_replace('_', ' ', $status)),
                        'bookings_count' => $group->count(),
                        'total_amount'   => number_format($group->sum('total_amount'), 2),
                        'percentage'     => $pct . '%',
                    ];
                    $rows[] = $forPdf ? $row : array_values($row);
                }
            }
        }

        return [$title, $csvHeaders, $rows, $summaryItems, $columns];
    }
}
