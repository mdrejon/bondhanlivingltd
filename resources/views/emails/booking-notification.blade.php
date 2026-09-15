<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Booking Alert</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; color: #1a2a3a; }
  .wrapper { max-width: 620px; margin: 32px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #1a3a5c 0%, #0f2a42 100%); padding: 28px 40px; }
  .badge { display: inline-block; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); border-radius: 50px; padding: 4px 14px; font-size: 10px; font-weight: 700; letter-spacing: 2px; color: rgba(255,255,255,0.7); text-transform: uppercase; margin-bottom: 12px; }
  .header h1 { font-size: 22px; font-weight: 700; color: #ffffff; }
  .header p { font-size: 13px; color: rgba(255,255,255,0.6); margin-top: 4px; }
  .gold-bar { height: 3px; background: linear-gradient(90deg, #c9a47a, #e8c99a, #c9a47a); }
  .alert-strip { background: #fff8ee; border-bottom: 2px solid #f0c060; padding: 14px 40px; font-size: 13.5px; color: #7a5a10; font-weight: 600; }
  .body { padding: 32px 40px; }
  .ref-box { background: #0f2a42; border-radius: 10px; padding: 16px 24px; margin-bottom: 28px; display: flex; justify-content: space-between; align-items: center; }
  .ref-label { font-size: 11px; font-weight: 700; letter-spacing: 1.5px; color: rgba(255,255,255,0.5); text-transform: uppercase; }
  .ref-number { font-size: 20px; font-weight: 800; color: #c9a47a; letter-spacing: 1px; font-family: monospace; }
  .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
  .info-card { background: #f8fafc; border: 1px solid #e0e8f0; border-radius: 10px; padding: 16px 18px; }
  .info-card h3 { font-size: 10px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #8a9ab0; margin-bottom: 10px; }
  .info-card p { font-size: 13.5px; color: #1a2a3a; line-height: 1.8; }
  .info-card .key { color: #7a8a9a; font-size: 12px; }
  .info-card .val { font-weight: 600; color: #1a2a3a; }
  .amount-row { background: #f0fbf4; border: 1.5px solid #a0d8b8; border-radius: 10px; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; margin: 20px 0; }
  .amount-row .label { font-size: 13px; color: #3a6a4a; }
  .amount-row .value { font-size: 22px; font-weight: 800; color: #1a5a2a; }
  .request-box { background: #fffbf4; border-left: 3px solid #c9a47a; padding: 14px 18px; border-radius: 0 8px 8px 0; font-size: 13.5px; color: #5a4a2a; line-height: 1.6; margin: 16px 0; }
  .action-btn { display: block; width: fit-content; margin: 24px auto; padding: 13px 32px; background: #1a3a5c; color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; border-radius: 50px; text-align: center; }
  .footer { background: #f8fafc; padding: 20px 40px; text-align: center; border-top: 1px solid #e0e8f0; }
  .footer p { font-size: 11.5px; color: #9aa8b8; line-height: 1.6; }
</style>
</head>
<body>
@php $curSym = $booking->currency === 'USD' ? '$' : '৳'; @endphp
<div class="wrapper">

  <div class="header">
    <div class="badge">Admin Notification</div>
    <h1>{{ $tplHeaderTitle }}</h1>
    <p>{{ $tplHeaderSubtitle }}</p>
  </div>
  <div class="gold-bar"></div>

  <div class="alert-strip">
    ⚠ Action Required: Review and confirm this booking request in the admin panel.
  </div>

  <div class="body">

    <div class="ref-box">
      <div>
        <div class="ref-label">Booking Reference</div>
        <div class="ref-number">{{ $booking->booking_reference }}</div>
      </div>
      <div style="text-align:right;">
        <div class="ref-label">Submitted</div>
        <div style="color:#e8c99a;font-size:13px;font-weight:600;margin-top:4px;">
          {{ $booking->created_at->format('d M Y, h:i A') }}
        </div>
      </div>
    </div>

    <div class="two-col">
      <div class="info-card">
        <h3>Guest Information</h3>
        <table style="width:100%;border-collapse:collapse;">
          <tr><td class="key">Name</td><td class="val">{{ $booking->customer->name }}</td></tr>
          <tr><td class="key">Email</td><td class="val" style="word-break:break-all;">{{ $booking->customer->email }}</td></tr>
          <tr><td class="key">Phone</td><td class="val">{{ $booking->customer->phone ?? '—' }}</td></tr>
        </table>
      </div>
      <div class="info-card">
        <h3>Stay Details</h3>
        <table style="width:100%;border-collapse:collapse;">
          <tr><td class="key">Check-In</td><td class="val">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</td></tr>
          <tr><td class="key">Check-Out</td><td class="val">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</td></tr>
          <tr><td class="key">Nights</td><td class="val">{{ $booking->total_nights }}</td></tr>
          <tr><td class="key">Guests</td><td class="val">{{ $booking->adults }}A @if($booking->children), {{ $booking->children }}C @endif</td></tr>
        </table>
      </div>
    </div>

    <div class="info-card" style="margin-bottom:20px;">
      <h3>Rooms Requested</h3>
      <table style="width:100%;border-collapse:collapse;">
        @foreach($booking->rooms as $line)
        <tr>
          <td class="key">{{ $line->roomType?->name ?? 'Any / TBD' }}</td>
          <td class="val">{{ $line->adults }}A @if($line->children), {{ $line->children }}C @endif — {{ $curSym }}{{ number_format($line->line_total) }}</td>
        </tr>
        @endforeach
      </table>
    </div>

    @if($booking->total_amount > 0)
    <div class="amount-row">
      <span class="label">Estimated Total ({{ $booking->total_nights }} night{{ $booking->total_nights > 1 ? 's' : '' }})</span>
      <span class="value">{{ $curSym }}{{ number_format($booking->total_amount) }}</span>
    </div>
    @endif

    @if($booking->special_requests)
    <p style="font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#8a9ab0;margin-bottom:8px;">Guest Message</p>
    <div class="request-box">{{ $booking->special_requests }}</div>
    @endif

    @php $assignedLines = $booking->rooms->filter(fn ($line) => $line->room); @endphp
    @if($assignedLines->isNotEmpty())
    <p style="font-size:13px;color:#3a6a4a;background:#f0fbf4;padding:10px 16px;border-radius:8px;margin-top:16px;">
      ✓ Room{{ $assignedLines->count() > 1 ? 's' : '' }}
      <strong>{{ $assignedLines->map(fn ($line) => $line->room->room_number)->implode(', ') }}</strong>
      {{ $assignedLines->count() > 1 ? 'have' : 'has' }} been tentatively reserved for these dates.
    </p>
    @endif

    <a href="{{ route('admin.room-bookings.show', $booking->id) }}" class="action-btn">
      View &amp; Manage Booking in Admin
    </a>

  </div>

  <div class="footer">
    <p>
      {{ $tplFooterText }}<br>
      &copy; {{ date('Y') }} Hotel Beach Way · Cox's Bazar, Bangladesh
    </p>
  </div>

</div>
</body>
</html>
