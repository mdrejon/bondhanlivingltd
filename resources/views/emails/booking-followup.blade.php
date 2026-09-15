<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $followUp->title }}</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; color: #1a2a3a; }
  .wrapper { max-width: 620px; margin: 32px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
  .header { padding: 36px 44px 28px; background: linear-gradient(135deg, #0f2a42 0%, #1a4a72 100%); text-align: center; }
  .logo-text { font-size: 11px; font-weight: 700; letter-spacing: 3px; color: rgba(255,255,255,0.5); text-transform: uppercase; margin-bottom: 12px; }
  .header h1 { font-size: 22px; font-weight: 700; color: #ffffff; line-height: 1.35; }
  .header p { font-size: 13px; color: rgba(255,255,255,0.55); margin-top: 6px; }
  .gold-bar { height: 3px; background: linear-gradient(90deg, #c9a47a, #e8c99a, #c9a47a); }
  .body { padding: 36px 44px; }
  .greeting { font-size: 15px; color: #1a2a3a; margin-bottom: 18px; font-weight: 500; }
  .message-box { background: #f8fafc; border-left: 4px solid #c9a47a; border-radius: 0 10px 10px 0; padding: 18px 20px; margin-bottom: 28px; font-size: 14px; line-height: 1.8; color: #334155; white-space: pre-line; }
  .ref-box { background: #f0f6ff; border: 1.5px solid #c3d9f5; border-radius: 10px; padding: 14px 24px; margin-bottom: 28px; text-align: center; }
  .ref-label { font-size: 11px; font-weight: 700; letter-spacing: 1.5px; color: #6b7f96; text-transform: uppercase; }
  .ref-number { font-size: 20px; font-weight: 800; color: #0f2a42; letter-spacing: 1px; margin-top: 4px; font-family: monospace; }
  .section-title { font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #8a9ab0; border-bottom: 1px solid #e8edf3; padding-bottom: 8px; margin-bottom: 14px; }
  .detail-table { width: 100%; border-collapse: collapse; }
  .detail-table tr td { padding: 8px 0; font-size: 14px; border-bottom: 1px solid #f0f3f7; }
  .detail-table tr:last-child td { border-bottom: none; }
  .detail-table .label { color: #7a8a9a; width: 42%; }
  .detail-table .value { color: #1a2a3a; font-weight: 600; }
  .footer { background: #f8fafc; padding: 24px 44px; text-align: center; border-top: 1px solid #e8edf3; }
  .footer p { font-size: 12px; color: #9aa8b8; line-height: 1.7; }
  .footer a { color: #c9a47a; text-decoration: none; }
  @media (max-width: 600px) {
    .body, .header { padding: 24px 20px; }
    .footer { padding: 20px; }
  }
</style>
</head>
<body>
<div class="wrapper">

  <div class="header">
    <p class="logo-text">Hotel Beach Way</p>
    <h1>{{ $followUp->title }}</h1>
    <p>{{ $tplHeaderSubtitle }}</p>
  </div>
  <div class="gold-bar"></div>

  <div class="body">

    <p class="greeting">Dear {{ $booking->customer?->name ?? 'Valued Guest' }},</p>

    <div class="message-box">{{ $followUp->description }}</div>

    <div class="ref-box">
      <div class="ref-label">Your Booking Reference</div>
      <div class="ref-number">{{ $booking->booking_reference }}</div>
    </div>

    <div class="section-title">Rooms</div>
    <table class="detail-table">
      @foreach($booking->rooms as $line)
      <tr>
        <td class="label">{{ $line->roomType?->name ?? 'To be assigned' }}</td>
        <td class="value">{{ $line->room?->room_number ?? 'Room not yet assigned' }}</td>
      </tr>
      @endforeach
    </table>

    <div class="section-title">Booking Details</div>
    <table class="detail-table">
      <tr>
        <td class="label">Check-In</td>
        <td class="value">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('D, d M Y') }}</td>
      </tr>
      <tr>
        <td class="label">Check-Out</td>
        <td class="value">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('D, d M Y') }}</td>
      </tr>
      <tr>
        <td class="label">Total Nights</td>
        <td class="value">{{ $booking->total_nights }} night{{ $booking->total_nights > 1 ? 's' : '' }}</td>
      </tr>
      <tr>
        <td class="label">Guests</td>
        <td class="value">
          {{ $booking->adults }} Adult{{ $booking->adults > 1 ? 's' : '' }}
          @if($booking->children), {{ $booking->children }} Child{{ $booking->children > 1 ? 'ren' : '' }}@endif
        </td>
      </tr>
    </table>

  </div>

  <div class="footer">
    <p>
      <strong>Hotel Beach Way</strong><br>
      {{ $tplFooterText }}<br>
      <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
    </p>
    <p style="margin-top:10px;">&copy; {{ date('Y') }} Hotel Beach Way. All rights reserved.</p>
  </div>

</div>
</body>
</html>
