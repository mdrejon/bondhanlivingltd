<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Message Received</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; color: #1a2a3a; }
  .wrapper { max-width: 600px; margin: 32px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
  .header { background: linear-gradient(135deg, #0f2a42 0%, #1a3a5c 100%); padding: 36px 40px; text-align: center; }
  .logo-text { font-size: 11px; font-weight: 700; letter-spacing: 3px; color: rgba(255,255,255,0.5); text-transform: uppercase; margin-bottom: 10px; }
  .header h1 { font-size: 24px; font-weight: 700; color: #fff; }
  .header p { font-size: 13px; color: rgba(255,255,255,0.6); margin-top: 6px; }
  .gold-bar { height: 3px; background: linear-gradient(90deg, #c9a47a, #e8c99a, #c9a47a); }
  .body { padding: 36px 40px; }
  .greeting { font-size: 15px; margin-bottom: 16px; }
  .intro { font-size: 14px; color: #4a5a6a; line-height: 1.7; margin-bottom: 24px; }
  .msg-box { background: #f8fafc; border: 1px solid #e0e8f0; border-left: 3px solid #c9a47a; border-radius: 0 8px 8px 0; padding: 16px 20px; margin-bottom: 24px; }
  .msg-box .label { font-size: 11px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: #8a9ab0; margin-bottom: 8px; }
  .msg-box p { font-size: 14px; color: #1a2a3a; line-height: 1.7; }
  .notice { background: #fffbf0; border: 1.5px solid #f5e0a0; border-radius: 10px; padding: 14px 18px; font-size: 13.5px; color: #7a5a10; line-height: 1.6; margin-bottom: 24px; }
  .footer { background: #f8fafc; padding: 24px 40px; text-align: center; border-top: 1px solid #e8edf3; }
  .footer p { font-size: 12px; color: #9aa8b8; line-height: 1.7; }
  .footer a { color: #c9a47a; text-decoration: none; }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <p class="logo-text">Hotel Beach Way</p>
    <h1>Thank You For Reaching Out</h1>
    <p>Cox's Bazar, Bangladesh</p>
  </div>
  <div class="gold-bar"></div>

  <div class="body">
    <p class="greeting">Dear <strong>{{ $inquiry->name }}</strong>,</p>
    <p class="intro">
      Thank you for contacting Hotel Beach Way. We have received your message and our team will get back to you as soon as possible — usually within <strong>24 hours</strong>.
    </p>

    <div class="msg-box">
      <div class="label">Your Message</div>
      <p>{{ $inquiry->message }}</p>
    </div>

    <div class="notice">
      <strong>What's next?</strong> Our team will review your inquiry and respond to <strong>{{ $inquiry->email }}</strong>. For urgent matters, please call us directly.
    </div>
  </div>

  <div class="footer">
    <p>
      <strong>Hotel Beach Way</strong><br>
      Near Kolatoli Beach, Cox's Bazar, Bangladesh<br>
      <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a>
    </p>
    <p style="margin-top:10px;">
      &copy; {{ date('Y') }} Hotel Beach Way. All rights reserved.
    </p>
  </div>
</div>
</body>
</html>
