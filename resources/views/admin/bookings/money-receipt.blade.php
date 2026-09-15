<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  @font-face {
    font-family: 'Script';
    src: url('{{ $scriptFont }}') format('truetype');
    font-weight: bold;
    font-style: normal;
  }

  * { box-sizing: border-box; }
  body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #111; margin: 22px; }

  .receipt { border: 1.5px solid #000; padding: 14px 20px 20px; }
  .cut-line { border-top: 1px dashed #999; text-align: center; height: 0; margin: 38px 0; }
  .cut-line span { position: relative; top: -6px; background: #fff; padding: 0 10px; font-size: 9px; color: #888; letter-spacing: 1px; }

  table.head { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
  table.head td { vertical-align: top; padding: 0; }
  .logo-cell { width: 130px; }
  .logo-cell img { max-height: 46px; }
  .logo-cell .fallback { font-size: 19px; font-weight: bold; color: #1a2340; }
  .addr-cell { font-size: 9px; color: #333; line-height: 1.4; padding-left: 10px; }
  .title-cell { text-align: right; white-space: nowrap; width: 1%; }
  .title-cell .mr-title { font-size: 19px; font-weight: bold; color: #111; white-space: nowrap; }
  .title-cell .mr-meta { font-size: 10px; margin-top: 5px; white-space: nowrap; }
  .title-cell .mr-meta b { font-weight: bold; }

  table.fields { width: 100%; border-collapse: collapse; margin-top: 6px; }
  table.fields td { padding: 4px 0; font-size: 11px; }
  .lbl { white-space: nowrap; padding-right: 4px !important; }
  .val { border-bottom: 1px dotted #000; padding: 0 4px !important; }
  .dyn { font-family: 'Script', cursive; font-weight: bold; color: #1a2340; font-size: 15px; line-height: 1.1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; }

  table.sign { width: 100%; border-collapse: collapse; margin-top: 30px; }
  table.sign td { font-size: 11px; padding-top: 4px; }
  .sign-line { border-top: 1px dashed #000; width: 190px; padding-top: 3px !important; }
  .sign-right { text-align: right; }
</style>
</head>
<body>

@for ($i = 0; $i < 2; $i++)
  <div class="receipt">

    <table class="head">
      <tr>
        <td class="logo-cell">
          @if($logo)
            <img src="{{ $logo }}" alt="{{ $siteName }}">
          @else
            <div class="fallback">{{ $siteName }}</div>
          @endif
        </td>
        <td class="addr-cell">
          {{ $address }}<br>
          @if($phone)Hotline: {{ $phone }}<br>@endif
          @if($email)Email: {{ $email }}<br>@endif
          @if($website)Website: {{ $website }}@endif
        </td>
        <td class="title-cell">
          <div class="mr-title">Money Receipt</div>
          <div class="mr-meta"><b>Receipt No:</b> {{ $receiptNo }}</div>
          <div class="mr-meta"><b>Date:</b> {{ $receiptDate }}</div>
        </td>
      </tr>
    </table>

    <table class="fields">
      <tr>
        <td class="lbl">Received With Thanks From</td>
        <td class="val" colspan="5"><span class="dyn">{{ $booking->customer?->name ?? '—' }}</span></td>
      </tr>
      <tr>
        <td class="lbl">Company Name</td>
        <td class="val" colspan="5">&nbsp;</td>
      </tr>
      <tr>
        <td class="lbl">Room No.</td>
        <td class="val" style="width:35%;"><span class="dyn">{{ $roomNo }}</span></td>
        <td class="lbl">{{ $currencyLabel }}</td>
        <td class="val" colspan="3"><span class="dyn">{{ number_format($amount, 2) }}</span></td>
      </tr>
      <tr>
        <td class="lbl">Reservation No.</td>
        <td class="val" colspan="5"><span class="dyn">{{ $booking->booking_reference }}</span></td>
      </tr>
      <tr>
        <td class="lbl">{{ $currencyLabel === 'USD' ? 'In Words' : 'Taka' }}</td>
        <td class="val" colspan="5"><span class="dyn">{{ $amountWords }}</span></td>
      </tr>
      <tr>
        <td class="lbl">On Account of</td>
        <td class="val" colspan="5">&nbsp;</td>
      </tr>
      @php
        $box = fn (string $label) => ($methodChecked === $label ? '☑' : '☐') . ' ' . $label;
      @endphp
      <tr>
        <td class="lbl" colspan="6" style="white-space:normal;">
          By&nbsp;&nbsp;
          {{ $box('Cash') }}&nbsp;&nbsp;&nbsp;
          {{ $box('Bank Transfer') }}&nbsp;&nbsp;&nbsp;
          {{ $box('Card') }}&nbsp;&nbsp;&nbsp;
          {{ $box('Mobile Banking') }}
          <span class="dyn" style="display:inline-block; border-bottom:1px dotted #000; padding:0 4px; min-width:110px;">
            {{ $methodDetail }}
          </span>
        </td>
      </tr>
      <tr>
        <td class="lbl">Bank</td>
        <td class="val" style="width:35%;">&nbsp;</td>
        <td class="lbl">Date</td>
        <td class="val" colspan="3">&nbsp;</td>
      </tr>
    </table>

    <table class="sign">
      <tr>
        <td>
          <div class="sign-line">Guest's Signature</div>
        </td>
        <td class="sign-right">
          <div class="sign-line" style="margin-left:auto;">{{ $booking->bookedBy?->name ?? 'Front Desk' }}</div>
        </td>
      </tr>
    </table>

  </div>
  @if($i === 0)
    <div class="cut-line"><span>&#9986; CUSTOMER COPY &nbsp;&mdash;&nbsp; CUT HERE &nbsp;&mdash;&nbsp; ACCOUNTANT COPY</span></div>
  @endif
@endfor

</body>
</html>
