<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1a2340; margin: 20px; }
  h1 { font-size: 18px; margin: 0 0 4px; }
  .meta { color: #666; font-size: 11px; margin-bottom: 16px; }
  table { width: 100%; border-collapse: collapse; border: 1px solid #1a2340; }
  th { background: #1a2340; color: #fff; padding: 7px 10px; text-align: left; font-size: 11px; border: 1px solid #1a2340; }
  td { padding: 6px 10px; border: 1px solid #ddd; font-size: 11px; }
  tr:nth-child(even) td { background: #f7f7f7; }
  .right { text-align: right; }
  .center { text-align: center; }
  .footer { margin-top: 20px; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 8px; }
</style>
</head>
<body>
  <h1>Hotel Beach Way &mdash; {{ $title }}</h1>
  <div class="meta">{{ $subtitle }}</div>

  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Reference</th>
        <th>Guest</th>
        <th>Room Type</th>
        <th>Check-In</th>
        <th>Check-Out</th>
        <th class="center">Nights</th>
        <th class="right">Amount</th>
        <th class="center">Source</th>
        <th class="center">Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($bookings as $i => $b)
      <tr>
        <td>{{ $i + 1 }}</td>
        <td>{{ $b['reference'] }}</td>
        <td>{{ $b['guest_name'] }}<br><span style="color:#888;">{{ $b['guest_phone'] }}</span></td>
        <td>{{ $b['room_type'] }}</td>
        <td>{{ $b['check_in'] }}</td>
        <td>{{ $b['check_out'] }}</td>
        <td class="center">{{ $b['nights'] }}</td>
        <td class="right">&#2547;{{ $b['amount'] }}</td>
        <td class="center">{{ $b['source'] }}</td>
        <td class="center">{{ $b['status'] }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="10" style="text-align:center; color:#999; padding: 20px;">No bookings found for the selected period.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  <div class="footer">Generated on {{ now()->format('d M Y, h:i A') }} &mdash; Hotel Beach Way Admin Panel</div>
</body>
</html>
