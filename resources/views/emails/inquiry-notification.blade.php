<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New Inquiry</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f4f8; color: #1a2a3a; }
  .wrapper { max-width: 600px; margin: 32px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #1a3a5c 0%, #0f2a42 100%); padding: 24px 36px; }
  .badge { display: inline-block; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); border-radius: 50px; padding: 3px 12px; font-size: 10px; font-weight: 700; letter-spacing: 2px; color: rgba(255,255,255,0.7); text-transform: uppercase; margin-bottom: 10px; }
  .header h1 { font-size: 20px; font-weight: 700; color: #fff; }
  .header p { font-size: 13px; color: rgba(255,255,255,0.6); margin-top: 4px; }
  .gold-bar { height: 3px; background: linear-gradient(90deg, #c9a47a, #e8c99a, #c9a47a); }
  .alert-strip { background: #fff8ee; border-bottom: 2px solid #f0c060; padding: 12px 36px; font-size: 13px; color: #7a5a10; font-weight: 600; }
  .body { padding: 28px 36px; }
  .type-badge { display: inline-block; padding: 4px 14px; border-radius: 50px; font-size: 11px; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 20px; }
  .type-quote   { background: #e8f4ff; color: #1a5a9a; }
  .type-widget  { background: #f0f4ff; color: #4a3a9a; }
  .type-page    { background: #f0fff4; color: #1a6a3a; }
  .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
  .info-table tr td { padding: 8px 0; font-size: 13.5px; border-bottom: 1px solid #f0f3f7; vertical-align: top; }
  .info-table tr:last-child td { border-bottom: none; }
  .info-table .key { color: #7a8a9a; width: 30%; }
  .info-table .val { font-weight: 600; color: #1a2a3a; }
  .msg-box { background: #fffbf4; border-left: 3px solid #c9a47a; padding: 14px 18px; border-radius: 0 8px 8px 0; font-size: 14px; color: #3a2a10; line-height: 1.7; margin: 16px 0; }
  .action-btn { display: block; width: fit-content; margin: 20px auto; padding: 12px 28px; background: #1a3a5c; color: #fff; font-size: 13px; font-weight: 700; text-decoration: none; border-radius: 50px; text-align: center; }
  .footer { background: #f8fafc; padding: 18px 36px; text-align: center; border-top: 1px solid #e0e8f0; }
  .footer p { font-size: 11.5px; color: #9aa8b8; line-height: 1.6; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <div class="badge">Admin Notification</div>
    <h1>New {{ $inquiry->typeLabel() }}</h1>
    <p>Submitted via Hotel Beach Way website</p>
  </div>
  <div class="gold-bar"></div>
  <div class="alert-strip">⚠ A new inquiry has been submitted and requires your attention.</div>

  <div class="body">
    @php
      $badgeClass = match($inquiry->type) {
        'quote'          => 'type-badge type-quote',
        'contact_widget' => 'type-badge type-widget',
        default          => 'type-badge type-page',
      };
    @endphp
    <span class="{{ $badgeClass }}">{{ $inquiry->typeLabel() }}</span>

    <table class="info-table">
      <tr><td class="key">Name</td><td class="val">{{ $inquiry->name }}</td></tr>
      <tr><td class="key">Email</td><td class="val">{{ $inquiry->email }}</td></tr>
      @if($inquiry->phone)
      <tr><td class="key">Phone</td><td class="val">{{ $inquiry->phone }}</td></tr>
      @endif
      @if($inquiry->subject)
      <tr><td class="key">Subject</td><td class="val">{{ $inquiry->subject }}</td></tr>
      @endif
      <tr><td class="key">Submitted</td><td class="val">{{ $inquiry->created_at->format('d M Y, h:i A') }}</td></tr>
    </table>

    <p style="font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#8a9ab0;margin-bottom:8px;">Message</p>
    <div class="msg-box">{{ $inquiry->message }}</div>

    <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="action-btn">
      View Inquiry in Admin Panel
    </a>
  </div>

  <div class="footer">
    <p>
      This notification was sent to the Hotel Beach Way admin team.<br>
      &copy; {{ date('Y') }} Hotel Beach Way · Cox's Bazar, Bangladesh
    </p>
  </div>
</div>
</body>
</html>
