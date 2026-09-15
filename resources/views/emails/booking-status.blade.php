<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Update – {{ $booking->booking_reference }}</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; color: #1a2a3a; }
  .wrapper { max-width: 620px; margin: 32px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
  .header { padding: 40px 44px 32px; text-align: center; }
  .header.confirmed       { background: linear-gradient(135deg, #0f2a42 0%, #1a3a5c 100%); }
  .header.payment_pending { background: linear-gradient(135deg, #5b21b6 0%, #7c3aed 100%); }
  .header.checked_in      { background: linear-gradient(135deg, #065f46 0%, #047857 100%); }
  .header.checked_out     { background: linear-gradient(135deg, #92400e 0%, #b45309 100%); }
  .header.cancelled       { background: linear-gradient(135deg, #7f1d1d 0%, #b91c1c 100%); }
  .header.no_show         { background: linear-gradient(135deg, #374151 0%, #4b5563 100%); }
  .logo-text { font-size: 11px; font-weight: 700; letter-spacing: 3px; color: rgba(255,255,255,0.5); text-transform: uppercase; margin-bottom: 10px; }
  .status-icon { font-size: 40px; margin-bottom: 12px; }
  .header h1 { font-size: 24px; font-weight: 700; color: #ffffff; line-height: 1.3; }
  .header p { font-size: 13px; color: rgba(255,255,255,0.65); margin-top: 8px; }
  .gold-bar { height: 3px; background: linear-gradient(90deg, #c9a47a, #e8c99a, #c9a47a); }
  .status-strip { padding: 14px 44px; font-size: 13px; font-weight: 600; text-align: center; }
  .status-strip.confirmed       { background: #eff6ff; color: #1d4ed8; border-bottom: 2px solid #bfdbfe; }
  .status-strip.payment_pending { background: #f5f3ff; color: #6d28d9; border-bottom: 2px solid #ddd6fe; }
  .status-strip.checked_in      { background: #f0fdf4; color: #15803d; border-bottom: 2px solid #bbf7d0; }
  .status-strip.checked_out     { background: #fff7ed; color: #c2410c; border-bottom: 2px solid #fed7aa; }
  .status-strip.cancelled       { background: #fef2f2; color: #b91c1c; border-bottom: 2px solid #fecaca; }
  .status-strip.no_show         { background: #f9fafb; color: #374151; border-bottom: 2px solid #e5e7eb; }
  .body { padding: 36px 44px; }
  .greeting { font-size: 16px; color: #1a2a3a; margin-bottom: 16px; }
  .ref-box { background: #f0f6ff; border: 1.5px solid #c3d9f5; border-radius: 10px; padding: 16px 24px; margin-bottom: 28px; text-align: center; }
  .ref-label { font-size: 11px; font-weight: 700; letter-spacing: 1.5px; color: #6b7f96; text-transform: uppercase; }
  .ref-number { font-size: 22px; font-weight: 800; color: #0f2a42; letter-spacing: 1px; margin-top: 6px; font-family: monospace; }
  .section-title { font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: #8a9ab0; border-bottom: 1px solid #e8edf3; padding-bottom: 8px; margin-bottom: 14px; margin-top: 24px; }
  .detail-table { width: 100%; border-collapse: collapse; }
  .detail-table tr td { padding: 8px 0; font-size: 14px; border-bottom: 1px solid #f0f3f7; vertical-align: top; }
  .detail-table tr:last-child td { border-bottom: none; }
  .detail-table .label { color: #7a8a9a; width: 42%; }
  .detail-table .value { color: #1a2a3a; font-weight: 600; }
  .note-box { border-radius: 10px; padding: 14px 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.6; border-left: 4px solid; }
  .note-box.confirmed       { background: #eff6ff; border-color: #3b82f6; color: #1e3a5f; }
  .note-box.payment_pending { background: #f5f3ff; border-color: #8b5cf6; color: #3b0764; }
  .note-box.checked_in      { background: #f0fdf4; border-color: #22c55e; color: #14532d; }
  .note-box.checked_out     { background: #fff7ed; border-color: #f97316; color: #7c2d12; }
  .note-box.cancelled       { background: #fef2f2; border-color: #ef4444; color: #7f1d1d; }
  .note-box.no_show         { background: #f9fafb; border-color: #9ca3af; color: #374151; }
  .staff-note { background: #fffbf0; border: 1.5px solid #f5e0a0; border-radius: 8px; padding: 12px 16px; margin-top: 16px; font-size: 13px; color: #7a5a10; line-height: 1.6; }
  .cta-btn { display: block; width: fit-content; margin: 24px auto; padding: 13px 32px; background: #c9a47a; color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; border-radius: 50px; text-align: center; }
  .footer { background: #f8fafc; padding: 24px 44px; text-align: center; border-top: 1px solid #e8edf3; }
  .footer p { font-size: 12px; color: #9aa8b8; line-height: 1.7; }
  .footer a { color: #c9a47a; text-decoration: none; }
  @media (max-width: 600px) {
    .body, .header { padding: 24px 20px; }
    .footer { padding: 20px; }
    .status-strip { padding: 12px 20px; }
  }
</style>
</head>
<body>
@php $curSym = $booking->currency === 'USD' ? '$' : '৳'; @endphp
<div class="wrapper">

  <div class="header {{ $booking->booking_status }}">
    <p class="logo-text">Hotel Beach Way</p>
    <div class="status-icon">
      @if($booking->booking_status === 'confirmed') ✅
      @elseif($booking->booking_status === 'payment_pending') 💳
      @elseif($booking->booking_status === 'checked_in') 🏨
      @elseif($booking->booking_status === 'checked_out') 👋
      @elseif($booking->booking_status === 'cancelled') ❌
      @else 📋
      @endif
    </div>
    <h1>{{ $tplTitle }}</h1>
    <p>Cox's Bazar, Bangladesh</p>
  </div>
  <div class="gold-bar"></div>

  <div class="status-strip {{ $booking->booking_status }}">
    Booking Status: <strong>{{ $statusLabel }}</strong>
  </div>

  <div class="body">

    <p class="greeting">Dear <strong>{{ $booking->customer->name }}</strong>,</p>

    <p style="font-size:14px;color:#4a5a6a;line-height:1.7;margin-bottom:20px;">
      {!! $tplBody !!}
    </p>

    <div class="ref-box">
      <div class="ref-label">Booking Reference</div>
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
      @if($booking->total_amount > 0)
      <tr>
        <td class="label">Total Amount</td>
        <td class="value" style="color:#1a6a3a;">{{ $curSym }}{{ number_format($booking->total_amount) }}</td>
      </tr>
      @endif
    </table>

    <div class="note-box {{ $booking->booking_status }}">
      @if($booking->booking_status === 'confirmed')
        <strong>What's next?</strong> Please arrive at your scheduled check-in time. Our front desk is available 24/7 to assist you.
      @elseif($booking->booking_status === 'payment_pending')
        <strong>Action required:</strong> Please contact our front desk to complete your payment. Unpaid bookings may be released after 24 hours.
      @elseif($booking->booking_status === 'checked_in')
        <strong>Enjoy your stay!</strong> Our staff is available around the clock. Don't hesitate to contact the front desk for any assistance.
      @elseif($booking->booking_status === 'checked_out')
        <strong>We hope to see you again!</strong> If you enjoyed your stay, please consider leaving us a review. Your feedback means the world to us.
      @elseif($booking->booking_status === 'cancelled')
        <strong>Need to rebook?</strong> We'd love to have you stay with us another time. Contact us or visit our website to make a new reservation.
      @else
        If you have any questions about your booking, please don't hesitate to contact us.
      @endif
    </div>

    @if($statusNote)
    <div class="staff-note">
      <strong>Note from our team:</strong><br>{{ $statusNote }}
    </div>
    @endif

    <a href="{{ route('contact') }}" class="cta-btn">Contact Us</a>

  </div>

  <div class="footer">
    <p>
      <strong>Hotel Beach Way</strong><br>
      {{ $tplFooterText }}<br>
      <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
    </p>
    <p style="margin-top:10px;">
      &copy; {{ date('Y') }} Hotel Beach Way. All rights reserved.
    </p>
  </div>

</div>
</body>
</html>
