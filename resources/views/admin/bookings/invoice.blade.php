<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  * { box-sizing: border-box; }
  body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #222; margin: 28px; }
  .logo-wrap { text-align: center; margin-bottom: 10px; }
  .logo-wrap img { max-height: 60px; }
  .site-name-fallback { font-size: 22px; font-weight: bold; color: #1a2340; text-align: center; }

  .title-row { border-bottom: 2px solid #1a2340; padding-bottom: 6px; margin-bottom: 10px; width: 100%; }
  .title-row td { vertical-align: bottom; }
  .doc-title { font-size: 15px; font-weight: bold; color: #1a2340; }
  .print-time { text-align: right; font-size: 10px; color: #555; }

  .dear { margin-bottom: 4px; }
  .dear b { text-transform: uppercase; }
  .intro { margin-bottom: 12px; color: #333; }

  .section-bar { background: #d9d9d9; padding: 5px 8px; font-weight: bold; font-size: 11px; color: #1a2340; margin-bottom: 0; }

  .details-table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
  .details-table td { padding: 4px 8px; font-size: 11px; vertical-align: top; }
  .details-table .label { color: #555; width: 32%; }
  .details-table .sep { border-left: 1px dashed #bbb; }

  table.acc-table { width: 100%; border-collapse: collapse; border: 1px solid #1a2340; margin-bottom: 10px; }
  table.acc-table th { background: #1a2340; color: #fff; padding: 6px 8px; font-size: 10px; text-align: left; border: 1px solid #1a2340; }
  table.acc-table td { padding: 6px 8px; font-size: 10px; border: 1px solid #ddd; }
  .right { text-align: right; }
  .center { text-align: center; }

  .totals-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
  .totals-table td { padding: 3px 8px; font-size: 11px; }
  .totals-table .t-label { text-align: right; color: #444; }
  .totals-table .t-value { text-align: right; width: 110px; }
  .totals-table .grand { font-weight: bold; border-top: 1px solid #999; padding-top: 5px; }
  .totals-table .balance { font-weight: bold; font-size: 12px; color: #b02a2a; border-top: 2px solid #1a2340; padding-top: 6px; }

  .policy-page { page-break-before: always; }
  .policy-title { font-weight: bold; text-decoration: underline; margin: 10px 0 6px; }
  .policy-list { margin: 0; padding-left: 14px; }
  .policy-list li { margin-bottom: 5px; }

  .sign-block { margin-top: 26px; }
  .sign-line { border-top: 1px solid #333; width: 200px; margin-top: 30px; margin-bottom: 4px; }

  .footer { margin-top: 24px; border-top: 1px solid #ccc; padding-top: 8px; font-size: 9.5px; color: #666; text-align: center; }
</style>
</head>
<body>

  @php $curSym = $booking->currency === 'USD' ? '$' : '৳'; @endphp

  <div class="logo-wrap">
    @if($logo)
      <img src="{{ $logo }}" alt="{{ $siteName }}">
    @else
      <div class="site-name-fallback">{{ $siteName }}</div>
    @endif
  </div>

  <table class="title-row">
    <tr>
      <td class="doc-title">Reservation Letter</td>
      <td class="print-time">Print Time: {{ now()->format('d-M-Y h:i:sA') }}</td>
    </tr>
  </table>

  <div class="dear">Dear <b>{{ $booking->customer?->name ?? 'Guest' }}</b></div>
  <div class="intro">We truly appreciate your kind patronage in choosing {{ $siteName }}. Please refer to the details of your reservation outlined below:</div>

  <div class="section-bar">Reservation Details :</div>
  <table class="details-table">
    <tr>
      <td class="label">RESERVATION NO.</td>
      <td><b>{{ $booking->booking_reference }}</b></td>
      <td class="sep label">BOOKING STATUS</td>
      <td>{{ ucwords(str_replace('_', ' ', $booking->booking_status)) }}</td>
    </tr>
    <tr>
      <td class="label">GUEST NAME</td>
      <td>{{ $booking->customer?->name ?? '—' }}</td>
      <td class="sep label">SOURCE</td>
      <td>{{ $booking->source === 'web' ? 'Online (Website)' : 'Manual (Front Desk)' }}</td>
    </tr>
    <tr>
      <td class="label">PHONE</td>
      <td>{{ $booking->customer?->phone ?? '—' }}</td>
      <td class="sep label">PAYMENT METHOD</td>
      <td>{{ ucfirst($booking->payment_method ?? '—') }}</td>
    </tr>
    <tr>
      <td class="label">E-MAIL</td>
      <td>{{ $booking->customer?->email ?? 'N/A' }}</td>
      <td class="sep label">BOOKED BY</td>
      <td>{{ $booking->bookedBy?->name ?? 'Online Booking' }}</td>
    </tr>
    <tr>
      <td class="label">ARR. DATE</td>
      <td>{{ optional($booking->check_in_date)->format('d-M-Y') }}</td>
      <td class="sep"></td>
      <td></td>
    </tr>
    <tr>
      <td class="label">DEP. DATE</td>
      <td>{{ optional($booking->check_out_date)->format('d-M-Y') }}</td>
      <td class="sep"></td>
      <td></td>
    </tr>
    <tr>
      <td class="label">PAX</td>
      <td>{{ $booking->adults }} Adult{{ $booking->adults > 1 ? 's' : '' }}{{ $booking->children ? ', ' . $booking->children . ' Child' . ($booking->children > 1 ? 'ren' : '') : '' }}</td>
      <td class="sep"></td>
      <td></td>
    </tr>
    @if($booking->special_requests)
    <tr>
      <td class="label">SPECIAL REQUEST</td>
      <td colspan="3">{{ $booking->special_requests }}</td>
    </tr>
    @endif
  </table>

  <div class="section-bar">Accommodation Details :</div>
  <table class="acc-table">
    <thead>
      <tr>
        <th>Reservation No.</th>
        <th>Room Type</th>
        <th class="center">PAX</th>
        <th class="center">No. of Rooms</th>
        <th class="center">Nights</th>
        <th class="right">Room Rate</th>
        <th class="right">Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach($lines as $line)
      <tr>
        <td>{{ $line['reservation_no'] }}</td>
        <td>{{ $line['room_type'] }}</td>
        <td class="center">{{ $line['pax'] }}</td>
        <td class="center">{{ $line['rooms'] }}</td>
        <td class="center">{{ $line['nights'] }}</td>
        <td class="right">{{ $curSym }}{{ number_format($line['room_rate'], 2) }}</td>
        <td class="right">{{ $curSym }}{{ number_format($line['total'], 2) }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <table class="totals-table">
    <tr>
      <td class="t-label">Total Room Rent</td>
      <td class="t-value">{{ $curSym }}{{ number_format($totalRoomRent, 2) }}</td>
    </tr>
    @if($discount > 0)
    <tr>
      <td class="t-label">Discount</td>
      <td class="t-value">− {{ $curSym }}{{ number_format($discount, 2) }}</td>
    </tr>
    @endif
    <tr>
      <td class="t-label grand">Total Room Rent (Net)</td>
      <td class="t-value grand">{{ $curSym }}{{ number_format($totalRoomRent - $discount, 2) }}</td>
    </tr>
    <tr>
      <td class="t-label">Total Advance Paid</td>
      <td class="t-value">{{ $curSym }}{{ number_format($advance, 2) }}</td>
    </tr>
    <tr>
      <td class="t-label balance">Balance Due</td>
      <td class="t-value balance">{{ $curSym }}{{ number_format($balance, 2) }}</td>
    </tr>
  </table>

  <div class="policy-page policy-title">Reservation Policy:</div>
  <ul class="policy-list">
    @php
      $firstRoom = $booking->rooms->first();
      $checkIn   = $firstRoom?->roomType?->check_in_time;
      $checkOut  = $firstRoom?->roomType?->check_out_time;
    @endphp
    @if($checkIn || $checkOut)
    <li>Our standard <b>CHECK-IN</b> time is <b>{{ $checkIn ?: '2:00 PM' }}</b> &amp; <b>CHECK-OUT</b> time is <b>{{ $checkOut ?: '12:00 PM' }}</b>.</li>
    @endif
    <li>Early check-in / late check-out is subject to availability and additional charges may apply.</li>
    <li>All rooms are strictly non-smoking. A deep cleaning fee will apply if smoking occurs inside.</li>
    <li>Pets are not allowed inside the hotel premises.</li>
    <li>National ID (Bangladeshi) or Passport with valid Visa (Foreign Guests) is mandatory at check-in.</li>
    <li>A written confirmation (letter or email) is required at least 24 hours before arrival for any reservation cancellation or rescheduling to avoid a cancellation fee. Any cancellation within 24 hours of arrival or "No Show" will be charged one night's room rent.</li>
    <li>Outside food and beverage items are not allowed inside the hotel.</li>
  </ul>

  <div class="sign-block">
    <div>We look forward to welcoming you.</div>
    <div>Thanks &amp; Regards,</div>
    <div class="sign-line"></div>
    <div>{{ $siteName }} Reservations Team</div>
  </div>

  <div class="footer">
    {{ $address }}
    @if($address && ($phone || $email)) &mdash; @endif
    @if($phone) Hotline: {{ $phone }} @endif
    @if($email) &middot; Email: {{ $email }} @endif
    @if($website) &middot; Website: {{ $website }} @endif
  </div>

</body>
</html>
