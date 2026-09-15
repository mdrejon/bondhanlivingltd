<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Confirmation</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; color: #1a2a3a; }
  .wrapper { max-width: 620px; margin: 32px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #0f2a42 0%, #1a3a5c 100%); padding: 40px 44px 32px; text-align: center; }
  .logo-text { font-size: 11px; font-weight: 700; letter-spacing: 3px; color: rgba(255,255,255,0.5); text-transform: uppercase; margin-bottom: 10px; }
  .header h1 { font-size: 26px; font-weight: 700; color: #ffffff; line-height: 1.3; }
  .header p { font-size: 14px; color: rgba(255,255,255,0.65); margin-top: 8px; }
  .gold-bar { height: 3px; background: linear-gradient(90deg, #c9a47a, #e8c99a, #c9a47a); }
  .body { padding: 40px 44px; }
  .greeting { font-size: 16px; color: #1a2a3a; margin-bottom: 20px; }
  .ref-box { background: #f0f6ff; border: 1.5px solid #c3d9f5; border-radius: 10px; padding: 20px 24px; margin-bottom: 28px; text-align: center; }
  .ref-label { font-size: 11px; font-weight: 700; letter-spacing: 1.5px; color: #6b7f96; text-transform: uppercase; }
  .ref-number { font-size: 24px; font-weight: 800; color: #0f2a42; letter-spacing: 1px; margin-top: 6px; font-family: monospace; }
  .section-title { font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #8a9ab0; border-bottom: 1px solid #e8edf3; padding-bottom: 8px; margin-bottom: 16px; margin-top: 28px; }
  .detail-table { width: 100%; border-collapse: collapse; }
  .detail-table tr td { padding: 9px 0; font-size: 14px; border-bottom: 1px solid #f0f3f7; vertical-align: top; }
  .detail-table tr:last-child td { border-bottom: none; }
  .detail-table .label { color: #7a8a9a; width: 40%; }
  .detail-table .value { color: #1a2a3a; font-weight: 600; }
  .amount-box { background: #f8fbf8; border: 1.5px solid #b8e8c8; border-radius: 10px; padding: 18px 24px; margin: 24px 0; display: flex; justify-content: space-between; align-items: center; }
  .amount-label { font-size: 13px; color: #4a6a5a; }
  .amount-value { font-size: 22px; font-weight: 800; color: #1a6a3a; }
  .notice { background: #fffbf0; border: 1.5px solid #f5e0a0; border-radius: 10px; padding: 16px 20px; margin: 24px 0; font-size: 13.5px; color: #7a5a10; line-height: 1.6; }
  .notice strong { color: #5a4010; }
  .cta-btn { display: block; width: fit-content; margin: 28px auto; padding: 14px 36px; background: #c9a47a; color: #ffffff; font-size: 15px; font-weight: 700; text-decoration: none; border-radius: 50px; text-align: center; }
  .footer { background: #f8fafc; padding: 28px 44px; text-align: center; border-top: 1px solid #e8edf3; }
  .footer p { font-size: 12px; color: #9aa8b8; line-height: 1.7; }
  .footer a { color: #c9a47a; text-decoration: none; }
  @media (max-width: 600px) {
    .body { padding: 28px 20px; }
    .header { padding: 28px 20px; }
    .footer { padding: 20px; }
  }
</style>
</head>
<body>
@php $curSym = $booking->currency === 'USD' ? '$' : '৳'; @endphp
<div class="wrapper">

  <div class="header">
    <p class="logo-text">Hotel Beach Way</p>
    <h1>{{ $tplHeaderTitle }}</h1>
    <p>{{ $tplHeaderSubtitle }}</p>
  </div>
  <div class="gold-bar"></div>

  <div class="body">
    <p class="greeting">Dear <strong>{{ $booking->customer->name }}</strong>,</p>
    <p style="font-size:14px;color:#4a5a6a;line-height:1.7;margin-bottom:20px;">
      {!! $tplIntroText !!}
    </p>

    <div class="ref-box">
      <div class="ref-label">Your Booking Reference</div>
      <div class="ref-number">{{ $booking->booking_reference }}</div>
      <div style="font-size:12px;color:#8a9ab0;margin-top:6px;">Please keep this reference for your records</div>
    </div>

    <div class="section-title">Rooms Requested</div>
    <table class="detail-table">
      @foreach($booking->rooms as $line)
      <tr>
        <td class="label">{{ $line->roomType?->name ?? 'To be assigned' }}</td>
        <td class="value">
          {{ $line->adults }} Adult{{ $line->adults > 1 ? 's' : '' }}@if($line->children), {{ $line->children }} Child{{ $line->children > 1 ? 'ren' : '' }}@endif
          — {{ $curSym }}{{ number_format($line->line_total) }}
        </td>
      </tr>
      @endforeach
    </table>

    <div class="section-title">Stay Details</div>
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

    @if($booking->total_amount > 0)
    <div class="amount-box">
      <span class="amount-label">Estimated Total</span>
      <span class="amount-value">{{ $curSym }}{{ number_format($booking->total_amount) }}</span>
    </div>
    @endif

    @if($booking->special_requests)
    <div class="section-title">Your Message</div>
    <p style="font-size:14px;color:#4a5a6a;line-height:1.6;background:#f8f9fc;padding:14px 18px;border-radius:8px;border-left:3px solid #c9a47a;">
      {{ $booking->special_requests }}
    </p>
    @endif

    <div class="notice">
      <strong>What's next?</strong><br>
      Our team will review your request and send you a confirmation email with full details. If you need to reach us urgently, please contact our front desk.
    </div>

    <a href="{{ route('contact') }}" class="cta-btn">Contact Us</a>
  </div>

  <div class="footer">
    <p>
      <strong>Hotel Beach Way</strong><br>
      {{ $tplFooterText }}<br>
      <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
    </p>
    <p style="margin-top:12px;">
      This email was sent because you submitted a booking request on our website.<br>
      &copy; {{ date('Y') }} Hotel Beach Way. All rights reserved.
    </p>
  </div>

</div>
</body>
</html>
