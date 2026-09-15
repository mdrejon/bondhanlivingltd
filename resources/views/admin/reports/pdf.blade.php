<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #1a2340; margin: 20px; }
  h1 { font-size: 18px; margin: 0 0 4px; }
  .meta { color: #666; font-size: 11px; margin-bottom: 16px; }
  table { width: 100%; border-collapse: collapse; }
  th { background: #1a2340; color: #fff; padding: 7px 10px; text-align: left; font-size: 11px; }
  td { padding: 6px 10px; border-bottom: 1px solid #e8e8e8; font-size: 11px; }
  tr:nth-child(even) td { background: #f7f7f7; }
  .summary { margin-bottom: 16px; overflow: hidden; }
  .sum-box { border: 1px solid #ddd; border-radius: 4px; padding: 8px 14px; display: inline-block; margin-right: 10px; margin-bottom: 8px; min-width: 120px; }
  .sum-label { font-size: 10px; color: #666; }
  .sum-value { font-size: 14px; font-weight: bold; color: #1a2340; }
  .right { text-align: right; }
  .footer { margin-top: 20px; font-size: 10px; color: #999; border-top: 1px solid #eee; padding-top: 8px; }
</style>
</head>
<body>
  <h1>Hotel Beach Way &mdash; {{ $title }}</h1>
  <div class="meta">{{ $subtitle }}</div>

  <!-- Summary boxes -->
  <div class="summary">
    @foreach($summaryItems as $item)
    <div class="sum-box">
      <div class="sum-label">{{ $item['label'] }}</div>
      <div class="sum-value">{{ $item['value'] }}</div>
    </div>
    @endforeach
  </div>

  <!-- Table -->
  <table>
    <thead>
      <tr>
        @foreach($columns as $col)
        <th class="{{ $col['align'] ?? '' }}">{{ $col['label'] }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $row)
      <tr>
        @foreach($columns as $col)
        <td class="{{ $col['align'] ?? '' }}">{{ $row[$col['key']] ?? '—' }}</td>
        @endforeach
      </tr>
      @empty
      <tr>
        <td colspan="{{ count($columns) }}" style="text-align:center; color:#999; padding: 20px;">No data found for the selected period.</td>
      </tr>
      @endforelse
    </tbody>
  </table>

  <div class="footer">Generated on {{ now()->format('d M Y, h:i A') }} &mdash; Hotel Beach Way Admin Panel</div>
</body>
</html>
