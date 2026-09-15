@extends('layouts.frontend')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
  /* =========== Date-picker wrapper =========== */
  .caf-date-wrap { position: relative; }

  .caf-date-input {
    height: 46px;
    padding: 0 38px 0 12px;
    border-radius: 4px;
    font-size: 13.5px;
    font-family: Plus Jakarta Sans, Inter, sans-serif;
    color: #1c1c2e;
    background: #fff;
    border: 1.5px solid transparent;
    outline: none;
    width: 100%;
    cursor: pointer;
    transition: border-color .2s ease, background .2s ease;
    box-sizing: border-box;
  }
  .caf-date-input::placeholder { color: #1c1c2e80; }
  .caf-date-input:focus { border-color: #0f288d; background: #fff; }

  /* calendar icon */
  .caf-date-wrap::after {
    content: '';
    position: absolute;
    right: 12px;
    bottom: 13px;
    width: 20px;
    height: 20px;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%230f288d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E") center/contain no-repeat;
    pointer-events: none;
  }

  /* =========== Flatpickr calendar theme =========== */
  .flatpickr-calendar {
    font-family: Plus Jakarta Sans, Inter, sans-serif;
    border-radius: 10px;
    box-shadow: 0 12px 40px rgba(15,40,141,.18);
    border: 1px solid #e2e8f0;
  }
  .flatpickr-months .flatpickr-month {
    background: #0f288d;
    border-radius: 10px 10px 0 0;
    height: 46px;
  }
  .flatpickr-current-month {
    font-size: 15px;
    font-weight: 600;
    color: #fff;
    padding-top: 12px;
  }
  .flatpickr-current-month .flatpickr-monthDropdown-months,
  .flatpickr-current-month input.cur-year {
    color: #fff;
    font-weight: 600;
  }
  /* Month dropdown list — options rendered by OS must use dark text */
  .flatpickr-monthDropdown-months {
    background: transparent;
    border: none;
    outline: none;
  }
  .flatpickr-monthDropdown-months option {
    background: #ffffff;
    color: #1c1c2e;
    font-weight: 500;
  }
  .flatpickr-months .flatpickr-prev-month,
  .flatpickr-months .flatpickr-next-month {
    color: #fff;
    fill: #fff;
    padding: 12px 14px;
  }
  .flatpickr-months .flatpickr-prev-month:hover,
  .flatpickr-months .flatpickr-next-month:hover { opacity: .75; }
  .flatpickr-months .flatpickr-prev-month svg,
  .flatpickr-months .flatpickr-next-month svg { fill: #fff; }

  .flatpickr-weekdays { background: #f0f4ff; }
  span.flatpickr-weekday {
    background: #f0f4ff;
    color: #0f288d;
    font-weight: 700;
    font-size: 12px;
  }

  .flatpickr-day {
    border-radius: 6px;
    font-size: 13px;
    color: #1c1c2e;
    font-weight: 500;
  }
  .flatpickr-day:hover {
    background: #e8edff;
    border-color: #e8edff;
    color: #0f288d;
  }
  .flatpickr-day.selected,
  .flatpickr-day.startRange,
  .flatpickr-day.endRange {
    background: #0f288d;
    border-color: #0f288d;
    color: #fff;
    font-weight: 700;
  }
  .flatpickr-day.selected:hover,
  .flatpickr-day.startRange:hover,
  .flatpickr-day.endRange:hover {
    background: #0f288d;
    border-color: #0f288d;
  }
  .flatpickr-day.inRange {
    background: #d6e0ff;
    border-color: #d6e0ff;
    color: #0f288d;
  }
  .flatpickr-day.flatpickr-disabled,
  .flatpickr-day.flatpickr-disabled:hover {
    color: #c0c8d8;
    background: transparent;
  }
  .flatpickr-day.today {
    background: transparent;
    border-color: transparent;
    /* color: #0f288d; */
    font-weight: 700;
    position: relative;
  }
  .flatpickr-day.today::after {
    content: '';
    position: absolute;
    bottom: 4px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #0f288d;
  }
  .flatpickr-day.today:hover {
    background: #e8edff;
    border-color: #e8edff;
    color: #0f288d;
  }
  .flatpickr-day.today.selected,
  .flatpickr-day.today.selected:hover {
    background: #0f288d;
    border-color: #0f288d;
    color: #fff;
  }
  .flatpickr-day.today.selected::after { display: none; }

  /* =========== Popup modal date inputs =========== */
  .bk-date-wrap { position: relative; }
  .bk-date-input {
    cursor: pointer !important;
    padding-right: 36px !important;
  }
  .bk-date-input::placeholder { opacity: .6; }
  .bk-date-wrap::after {
    content: '';
    position: absolute;
    right: 10px;
    bottom: 11px;
    width: 16px;
    height: 16px;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23a0aec0' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E") center/contain no-repeat;
    pointer-events: none;
  }

  /* =========== Booking form date inputs (FAQ / dark-green card) =========== */
  .bf-field .flatpickr-wrapper { display: block; position: relative; }

  input.bf-date-alt {
    width: 100% !important;
    background: transparent !important;
    border: none !important;
    border-bottom: 1.5px solid rgba(255,255,255,.22) !important;
    border-radius: 0 !important;
    box-shadow: none !important;
    padding: 10px 24px 10px 0 !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    color: #fff !important;
    font-family: Plus Jakarta Sans, Inter, sans-serif !important;
    outline: none !important;
    cursor: pointer !important;
    transition: border-color .25s ease !important;
    box-sizing: border-box !important;
  }
  input.bf-date-alt::placeholder { color: rgba(255,255,255,.4) !important; }
  input.bf-date-alt:focus {
    border-bottom-color: rgba(255,255,255,.7) !important;
    outline: none !important;
  }

  /* calendar icon on dark card */
  .bf-field .flatpickr-wrapper::after {
    content: '';
    position: absolute;
    right: 0;
    bottom: 11px;
    width: 15px;
    height: 15px;
    background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='%23ffffff55' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E") center/contain no-repeat;
    pointer-events: none;
  }

  /* =========== Booking popup — multi room-type cart =========== */
  .bk-room-cart { display: flex; flex-direction: column; gap: 12px; margin-bottom: 4px; }

  /* -- Currency toggle -- */
  .bw-currency-toggle { display: flex; align-items: center; gap: 10px; margin: -8px 0 14px; }
  .bw-currency-label { font-size: 11px; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.4px; }
  .bw-currency-btn {
    padding: 6px 16px; border: 1.5px solid #E2E8F0; border-radius: 50px; background: #fff;
    color: #6b7280; font-size: 12.5px; font-weight: 600; cursor: pointer;
    transition: border-color 0.2s ease, background 0.2s ease, color 0.2s ease;
  }
  .bw-currency-btn:hover { border-color: #0f288d; color: #0f288d; }
  .bw-currency-btn.is-active { border-color: #0f288d; background: #0f288d; color: #fff; }
  .bk-room-price s { opacity: 0.55; margin-right: 4px; font-weight: 400; }

  .bk-room-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    padding: 16px 18px;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    background: #FAFBFC;
    transition: border-color 0.2s ease, background 0.2s ease;
  }
  .bk-room-row.bk-sold-out { opacity: 0.55; }

  .bk-room-info { display: flex; flex-direction: column; gap: 7px; min-width: 200px; }
  .bk-control-type { min-width: 200px; }
  .bk-control-type .bk-room-select-wrap select { min-width: 200px; }

  .bk-room-meta { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
  .bk-room-price { font-size: 12px; color: #6b7280; }
  .bk-room-remaining { font-size: 11px; font-weight: 700; letter-spacing: 0.3px; color: #0F288D; text-transform: uppercase; min-height: 14px; }
  .bk-room-remaining.bk-remaining-low { color: #c0392b; }
  .bk-room-remaining.bk-remaining-none { color: #c0392b; }

  .bk-room-controls { display: flex; align-items: flex-end; gap: 14px; flex-wrap: wrap; }

  .bk-mini-control { display: flex; flex-direction: column; gap: 6px; }
  .bk-mini-label { font-size: 10.5px; font-weight: 600; color: #9CA3AF; text-transform: uppercase; letter-spacing: 0.4px; }

  .bk-room-select-wrap select,
  .bk-mini-control select {
    padding: 9px 30px 9px 12px;
    border: 1.5px solid #E2E8F0;
    border-radius: 9px;
    font-size: 13px;
    font-family: inherit;
    color: #1C1C2E;
    background: #fff;
    outline: none;
    appearance: none;
    -webkit-appearance: none;
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239CA3AF' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 8px center;
    background-size: 13px;
    transition: border-color 0.2s ease;
  }
  .bk-room-select-wrap select:focus,
  .bk-mini-control select:focus { border-color: #0F288D; }

  .bk-qty-stepper { display: flex; align-items: center; border: 1.5px solid #E2E8F0; border-radius: 9px; background: #fff; overflow: hidden; }
  .bk-qty-btn { width: 30px; height: 32px; border: none; background: transparent; color: #0F288D; font-size: 16px; font-weight: 700; cursor: pointer; line-height: 1; transition: background 0.15s ease; }
  .bk-qty-btn:hover { background: rgba(15,40,141,0.08); }
  .bk-qty-input { width: 36px; height: 32px; border: none; border-left: 1.5px solid #E2E8F0; border-right: 1.5px solid #E2E8F0; text-align: center; font-size: 13px; font-weight: 700; color: #1C1C2E; -moz-appearance: textfield; }
  .bk-qty-input::-webkit-outer-spin-button,
  .bk-qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

  .bk-remove-btn {
    display: inline-flex; align-items: center; justify-content: center;
    width: 30px; height: 30px; border: 1.5px solid #E2E8F0; border-radius: 50%;
    background: #fff; color: #9CA3AF; cursor: pointer; flex-shrink: 0;
    transition: border-color 0.2s ease, color 0.2s ease, background 0.2s ease;
  }
  .bk-remove-btn svg { width: 13px; height: 13px; }
  .bk-remove-btn:hover { border-color: #e3a0a0; color: #c0392b; background: #fff5f5; }

  .bk-add-room-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 12px 18px; margin-top: 4px;
    border: 1.5px dashed #c7cfdd; border-radius: 12px; background: transparent;
    color: #0F288D; font-size: 13px; font-weight: 600; cursor: pointer;
    transition: border-color 0.2s ease, background 0.2s ease;
  }
  .bk-add-room-btn svg { width: 15px; height: 15px; }
  .bk-add-room-btn:hover { border-color: #0F288D; background: rgba(15,40,141,0.03); }
  .bk-add-room-btn:disabled { opacity: 0.45; cursor: not-allowed; }
  .bk-add-room-btn[hidden] { display: none; }

  .bk-cart-summary { margin-top: 14px; margin-bottom: 24px; padding: 14px 18px; background: rgba(15,40,141,0.04); border: 1.5px dashed rgba(15,40,141,0.25); border-radius: 12px; }
  .bk-cart-lines { display: flex; flex-direction: column; gap: 4px; margin-bottom: 10px; font-size: 12.5px; color: #4a5a6a; }
  .bk-cart-lines .bk-cart-line { display: flex; justify-content: space-between; gap: 12px; }
  .bk-cart-total { display: flex; justify-content: space-between; align-items: baseline; font-size: 13.5px; color: #1C1C2E; font-weight: 600; padding-top: 10px; border-top: 1px solid rgba(15,40,141,0.15); }
  .bk-cart-total strong { font-size: 17px; color: #0F288D; }

  .bk-room-occupancy {
    font-size: 10.5px;
    font-weight: 600;
    color: #0F288D;
    background: rgba(15,40,141,0.08);
    padding: 2px 8px;
    border-radius: 20px;
  }

  .bk-policy-note {
    margin-top: 14px;
    padding: 14px 16px;
    background: #fff8ec;
    border: 1.5px solid #f2dfb0;
    border-radius: 10px;
  }
  .bk-policy-note strong {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #8a6410;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
  }
  .bk-policy-note ul { margin: 0; padding-left: 16px; display: flex; flex-direction: column; gap: 3px; }
  .bk-policy-note li { font-size: 12px; color: #6b5a2e; line-height: 1.45; }

  .bk-assist-note {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 14px;
    padding: 12px 16px;
    background: rgba(15,40,141,0.04);
    border: 1.5px dashed rgba(15,40,141,0.2);
    border-radius: 10px;
    margin-bottom: 16px;
  }
  .bk-assist-item { display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; color: #4a5a6a; }
  .bk-assist-item svg { width: 15px; height: 15px; color: #0F288D; flex-shrink: 0; }
  .bk-assist-item a { color: #4a5a6a; text-decoration: none; }
  .bk-assist-item a:hover { color: #0F288D; }
  .bk-assist-hint { font-size: 11.5px; color: #9CA3AF; }

  @media (max-width: 560px) {
    .bk-room-row { flex-direction: column; align-items: flex-start; }
    .bk-room-controls { width: 100%; justify-content: space-between; }
  }
</style>
@endpush

@section('title', 'Hotel Beach Way - Luxury Hotel Near Kolatoli Beach, Cox\'s Bazar')

@section('content')
@php
    // Pre-decode JSON about fields from flat settings array
    $missionFeatures = !empty($settings['about_mission_features'])
        ? json_decode($settings['about_mission_features'], true) : [];
    $visionFeatures  = !empty($settings['about_vision_features'])
        ? json_decode($settings['about_vision_features'],  true) : [];
    $historyFeatures = !empty($settings['about_history_features'])
        ? json_decode($settings['about_history_features'], true) : [];

    // Flatten all FAQ items from all faq groups
    $allFaqItems = collect($faqs)->flatMap(fn($f) => $f->items ?? [])->values();

    // Default feature lists for about tabs (used when DB has no data yet)
    $defaultMissionFeatures = [
        'Complimentary Breakfast & Welcome Drinks',
        '24 Hours Front Desk & Room Service',
        'Free Wi-Fi in Lobby & All Rooms',
        'Airport Pick Up & Drop Service',
        'Basement Car Parking',
        '24 Hours CCTV Security',
    ];
    $defaultVisionFeatures = [
        'World-Class Amenities',
        'Eco-Friendly Practices',
        'Guest-First Philosophy',
        'Sustainable Tourism',
        'Community Engagement',
        'Award-Winning Service',
    ];
    $defaultHistoryFeatures = [
        'Established in 2013',
        '13+ Years of Service',
        '500+ Happy Guests Monthly',
        '5 Room Categories',
        'Restaurant & Conference Hall',
        'Trusted by Thousands',
    ];

    // Resolve final display lists (DB data wins, fall back to static defaults)
    $displayMissionFeatures = !empty($missionFeatures) ? $missionFeatures : $defaultMissionFeatures;
    $displayVisionFeatures  = !empty($visionFeatures)  ? $visionFeatures  : $defaultVisionFeatures;
    $displayHistoryFeatures = !empty($historyFeatures) ? $historyFeatures : $defaultHistoryFeatures;
@endphp

  <!-- ===========
       HERO SLIDER
  =========== -->
  <section class="hero-section">
    <div class="swiper hero-swiper">
      <div class="swiper-wrapper">

        @forelse($sliders as $slider)
        <div class="swiper-slide slide-{{ $loop->iteration }}">
          <div class="slide-bg" style="background-image:
            @if($slider->background_image)url('{{ asset('storage/' . $slider->background_image) }}'),@endif
            linear-gradient(160deg, #1a3a5c 0%, #2d6490 35%, #1e4d72 65%, #0f2a42 100%);"></div>
          <div class="slide-overlay"></div>
          <div class="hero-container">
            <div class="slide-content">
              @if($slider->label)
              <p class="slide-label">{{ $slider->label }}</p>
              @endif
              <h1 class="slide-title">
                {!! $slider->title !!}

               
              </h1>
              @if($slider->description)
              <p class="slide-desc">{!! $slider->description !!}</p>
              @endif
              <div class="slide-actions">
                <a href="{{ $slider->button_url }}" class="btn-discover">{{ $slider->button_text }}</a>
                @if($slider->star_label)
                <div class="slide-reviews">
                  <div class="stars">
                    @for($i = 0; $i < ($slider->star_rating ?? 5); $i++)
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                  </div>
                  <span>{{ $slider->star_label }}</span>
                </div>
                @endif
              </div>
            </div>
          </div>
          <div class="slide-deco-arrow">
            <svg viewBox="0 0 320 120" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M10 100 C60 80 140 20 300 55" stroke="rgba(201,164,122,0.75)" stroke-width="1.5" fill="none" stroke-linecap="round"/>
              <path d="M287 46 L302 55 L290 64" stroke="rgba(201,164,122,0.75)" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <div class="watch-video">
            <button class="watch-play-btn" aria-label="Watch Video">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
            </button>
            <span class="watch-label">Watch Video</span>
          </div>
        </div><!-- /slide-{{ $loop->iteration }} -->
        @empty
        <div class="swiper-slide slide-1">
          <div class="slide-bg" style="background-image: linear-gradient(160deg, #1a3a5c 0%, #2d6490 35%, #1e4d72 65%, #0f2a42 100%);"></div>
          <div class="slide-overlay"></div>
          <div class="hero-container">
            <div class="slide-content">
              <h1 class="slide-title">Welcome to <span class="accent">Hotel Beach Way</span></h1>
            </div>
          </div>
        </div>
        @endforelse

      </div><!-- /swiper-wrapper -->
    </div><!-- /hero-swiper -->

    <!-- Custom right-side pagination -->
    <div class="hero-pagination"></div>

  </section><!-- /hero-section -->




  <!-- ===========
       CHECK AVAILABILITY BAR
  =========== -->
  <div class="check-avail-bar" data-reveal="up">
    <div class="check-avail-inner">

      <div class="check-avail-title">
        <h3>Check<br>Availability</h3>
      </div>

      <form class="check-avail-form" id="checkAvailForm">

        <div class="caf-field caf-date-wrap">
          <label for="caf-checkin">Check In</label>
          <input type="text" id="caf-checkin" name="checkin" placeholder="Select date" readonly class="caf-date-input">
        </div>

        <div class="caf-field caf-date-wrap">
          <label for="caf-checkout">Check Out</label>
          <input type="text" id="caf-checkout" name="checkout" placeholder="Select date" readonly class="caf-date-input">
        </div>

        <div class="caf-field caf-select-wrap">
          <label for="caf-room">Room Type</label>
          <select id="caf-room" name="room-type">
            <option value="">Any Room</option>
            @foreach($allRooms as $rt)
            <option value="{{ $rt->slug }}">{{ $rt->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="caf-field caf-select-wrap">
          <label for="caf-adults">Adult</label>
          <select id="caf-adults" name="adults">
            <option value="1">1 Adult</option>
            <option value="2">2 Adults</option>
            <option value="3">3 Adults</option>
            <option value="4">4 Adults</option>
          </select>
        </div>

        <div class="caf-field caf-select-wrap">
          <label for="caf-children">Childrens</label>
          <select id="caf-children" name="children">
            <option value="0">0 Children</option>
            <option value="1">1 Child</option>
            <option value="2">2 Children</option>
            <option value="3">3 Children</option>
          </select>
        </div>

        <button type="submit" class="caf-btn" id="cafBtn">Book Now</button>

      </form>
    </div>
  </div><!-- /check-avail-bar -->
  <div id="cafAvailError" style="display:none;max-width:900px;margin:-8px auto 0;padding:12px 20px;background:#fff3f3;border:1.5px solid #f5b8b8;border-radius:0 0 10px 10px;color:#c0392b;font-size:14px;text-align:center;"></div>


  <!-- ===========
       ABOUT SECTION
  =========== -->
  <section class="about-section" id="about">

    <div class="about-deco about-deco-tl" data-reveal="fade" data-reveal-delay="2">
      <div class="deco-badge">
        <div class="deco-badge-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
          </svg>
        </div>
        <div class="deco-badge-text">
          <strong>{{ $settings['about_deco_badge_value'] ?? '24/7' }}</strong>
          <span>{{ $settings['about_deco_badge_label'] ?? 'Front Desk' }}</span>
        </div>
      </div>
    </div>

    <div class="about-deco about-deco-br" data-reveal="fade" data-reveal-delay="3">
      <div class="deco-years">
        <strong>{{ $settings['about_deco_years_value'] ?? '13+' }}</strong>
        <span>{{ $settings['about_deco_years_label'] ?? 'Years of Trusted Service' }}</span>
      </div>
    </div>

    <div class="about-inner">

      <!-- =========== Left Column =========== -->
      <div class="about-left" data-reveal="left">
        <div>
          <span class="section-badge">{{ $settings['about_badge'] ?? 'OUR LUXURY RESORTS' }}</span>
        </div>
        <h2 class="about-title">{!! $settings['about_title'] ?? "3-Star Boutique Hotel<br>Near Kolatoli Beach" !!}</h2>
        <div class="about-desc">
          {!! $settings['about_desc'] ?? "<p>Hotel Beach Way is the most modern and well furnished with branded fixtures offering everything you need for a comfortable stay in Cox's Bazar — the world's longest unbroken sandy sea beach. 5 minutes walk from Kolatoli Circle &amp; 10 minutes from the Airport.</p>" !!}
        </div>

        <div class="about-img-wrap about-img-main">
          @if(!empty($settings['about_main_image']))
            <img src="{{ asset('storage/' . $settings['about_main_image']) }}" alt="{{ $settings['about_main_image_alt'] ?? 'Hotel Beach Way' }}" loading="lazy">
          @else
            <img src="{{ asset('assets/images/about-2-1.jpg') }}" alt="Hotel guests relaxing outdoors" loading="lazy">
          @endif
        </div>
      </div>

      <!-- =========== Right Column =========== -->
      <div class="about-right" data-reveal="right">

        <div class="about-img-wrap about-img-top">
          @if(!empty($settings['about_top_image']))
            <img src="{{ asset('storage/' . $settings['about_top_image']) }}" alt="{{ $settings['about_top_image_alt'] ?? 'Hotel Beach Way' }}" loading="lazy">
          @else
            <img src="{{ asset('assets/images/about-2-2.jpg') }}" alt="Scenic lake view at sunset" loading="lazy">
          @endif
        </div>

        <div class="about-tabs-wrap">
          <div class="about-tab-nav">
            <button class="about-tab-btn active" data-tab="mission">{{ $settings['about_mission_label'] ?? 'Our Mission' }}</button>
            <button class="about-tab-btn" data-tab="vision">{{ $settings['about_vision_label'] ?? 'Our Vision' }}</button>
            <button class="about-tab-btn" data-tab="history">{{ $settings['about_history_label'] ?? 'Our History' }}</button>
          </div>

          <div class="about-tab-pane active" data-tab="mission">
            <div>{!! $settings['about_mission_text'] ?? "<p>We are committed to providing our guests with world-class hospitality within an affordable price. Eco-friendly infrastructure, skilled staff &amp; world-class service are our key features — making us a unique service provider in Cox's Bazar for families, honeymoon couples, corporate groups &amp; students.</p>" !!}</div>
            <ul class="about-features">
              @foreach($displayMissionFeatures as $feature)
                <li>{{ $feature }}</li>
              @endforeach
            </ul>
          </div>

          <div class="about-tab-pane" data-tab="vision">
            <div>{!! $settings['about_vision_text'] ?? "<p>Our vision is to be the most sought-after luxury hotel in Cox's Bazar, setting the standard for hospitality excellence, sustainability, and guest satisfaction across Bangladesh.</p>" !!}</div>
            <ul class="about-features">
              @foreach($displayVisionFeatures as $feature)
                <li>{{ $feature }}</li>
              @endforeach
            </ul>
          </div>

          <div class="about-tab-pane" data-tab="history">
            <div>{!! $settings['about_history_text'] ?? "<p>Founded in 2013, Hotel Beach Way has grown into one of Cox's Bazar's premier destinations, earning recognition for authentic hospitality and an unwavering commitment to excellence.</p>" !!}</div>
            <ul class="about-features">
              @foreach($displayHistoryFeatures as $feature)
                <li>{{ $feature }}</li>
              @endforeach
            </ul>
          </div>

          <div class="about-tab-footer">
            <a href="{{ $settings['about_more_btn_url'] ?? '#contact' }}" class="btn-about-more">{{ $settings['about_more_btn_text'] ?? 'More About' }}</a>
            <div class="about-scroll-dot">
              <span></span>
            </div>
          </div>
        </div>

      </div><!-- /about-right -->
    </div><!-- /about-inner -->
  </section><!-- /about-section -->


  <!-- ===========
       ROOMS & SUITES SECTION (dynamic)
  =========== -->
  <section class="rooms-section" id="rooms">

    <div class="section-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,45 C360,80 720,5 1080,45 C1260,65 1380,20 1440,45 L1440,0 L0,0 Z" fill="#ffffff"/>
      </svg>
    </div>

    <div class="rooms-header" data-reveal="up">
      <span class="section-badge">OUR ROOM &amp; SUITS</span>
      <h2 class="rooms-title">Book Your Stay And Relax In<br>Luxury Resort</h2>
    </div>

    @php $displayRooms = $featuredRooms->isNotEmpty() ? $featuredRooms : $allRooms; @endphp

    @if($displayRooms->isNotEmpty())
    <div class="rooms-grid">
      @foreach($displayRooms as $i => $roomItem)
      <div class="room-card room-card--link" id="room-{{ $roomItem->slug }}" data-reveal="up" data-reveal-delay="{{ ($i % 3) + 1 }}" onclick="location.href='{{ route('rooms.detail', $roomItem->slug) }}'" style="cursor:pointer;">
        <div class="room-card-img">
          @if($roomItem->feature_image)
            <img src="{{ asset('storage/' . $roomItem->feature_image) }}" alt="{{ $roomItem->name }}" loading="lazy">
          @else
            <img src="{{ asset('assets/images/room-4.jpg') }}" alt="{{ $roomItem->name }}" loading="lazy">
          @endif
          @if($roomItem->rating)
          <div class="room-rating">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            {{ number_format($roomItem->rating, 1) }}
          </div>
          @endif
          @if($roomItem->amenities && count($roomItem->amenities))
          <div class="room-amenities">
            @foreach(array_slice($roomItem->amenities, 0, 5) as $amenity)
            <span class="ram-icon" title="{{ $amenity['label'] ?? '' }}">{!! $amenity['icon_svg'] ?? '' !!}</span>
            @endforeach
          </div>
          @endif
        </div>
        <div class="room-card-body">
          <h3 class="room-card-title">{{ $roomItem->name }}</h3>
          @if($roomItem->short_desc)
          <p class="room-card-desc">{{ $roomItem->short_desc }}</p>
          @endif
          <div class="room-card-footer">
            @if($roomItem->price)
            <div class="room-price-wrap">
              @if($roomItem->discounted_price_bdt || $roomItem->discounted_price_usd)
              <div class="room-offer-badge">Special Offer</div>
              @endif
              @if($roomItem->discounted_price_bdt)
              <s class="room-price-original">BDT {{ number_format($roomItem->price) }}</s>
              <div class="room-price">BDT {{ number_format($roomItem->discounted_price_bdt) }}<span>/{{ ltrim($roomItem->price_unit ?? 'Night', '/') }}</span></div>
              @else
              <div class="room-price">BDT {{ number_format($roomItem->price) }}<span>/{{ ltrim($roomItem->price_unit ?? 'Night', '/') }}</span></div>
              @endif
              @if($roomItem->price_usd)
              <div class="room-price-usd">
                @if($roomItem->discounted_price_usd)<s>${{ number_format($roomItem->price_usd, 2) }}</s> ${{ number_format($roomItem->discounted_price_usd, 2) }}@else${{ number_format($roomItem->price_usd, 2) }}@endif
                <span>/{{ ltrim($roomItem->price_unit ?? 'Night', '/') }}</span>
              </div>
              @endif
            </div>
            @endif
            <a href="{{ route('rooms.detail', $roomItem->slug) }}" class="btn-room-details" onclick="event.stopPropagation()">View Details</a>
          </div>
        </div>
      </div>
      @endforeach
    </div><!-- /rooms-grid -->
    @else
    <div class="rooms-grid">
      <p style="text-align:center;padding:2rem;color:#888;grid-column:1/-1;">No rooms available at the moment. Please check back soon.</p>
    </div>
    @endif

  </section><!-- /rooms-section -->


  <!-- ===========
       SERVICES SECTION
  =========== -->
  <section class="services-section" id="facilities">

    <div class="services-wave-top" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,48 C180,80 360,10 540,48 C720,86 900,8 1080,48 C1260,88 1380,20 1440,48 L1440,0 L0,0 Z" fill="#F8F3EB"/>
      </svg>
    </div>

    
    <div class="services-bg-deco" aria-hidden="true">
      <svg viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="200" cy="200" r="185" stroke="#0f288d" stroke-width="0.8"/>
        <circle cx="200" cy="200" r="145" stroke="#0f288d" stroke-width="0.8"/>
        <circle cx="200" cy="200" r="105" stroke="#0f288d" stroke-width="0.8"/>
        <circle cx="200" cy="200" r="22" fill="#0f288d"/>
        <ellipse cx="200" cy="128" rx="19" ry="58" fill="#0f288d"/>
        <ellipse cx="200" cy="128" rx="19" ry="58" fill="#0f288d" transform="rotate(45 200 200)"/>
        <ellipse cx="200" cy="128" rx="19" ry="58" fill="#0f288d" transform="rotate(90 200 200)"/>
        <ellipse cx="200" cy="128" rx="19" ry="58" fill="#0f288d" transform="rotate(135 200 200)"/>
        <ellipse cx="200" cy="128" rx="19" ry="58" fill="#0f288d" transform="rotate(180 200 200)"/>
        <ellipse cx="200" cy="128" rx="19" ry="58" fill="#0f288d" transform="rotate(225 200 200)"/>
        <ellipse cx="200" cy="128" rx="19" ry="58" fill="#0f288d" transform="rotate(270 200 200)"/>
        <ellipse cx="200" cy="128" rx="19" ry="58" fill="#0f288d" transform="rotate(315 200 200)"/>
      </svg>
    </div>

    <div class="services-inner">

      <div class="services-left" data-reveal="left">
        <span class="section-badge">{{ $settings['svc_badge'] ?? 'OUR SERVICES' }}</span>
        <h2 class="services-title">{!! $settings['svc_title'] ?? 'We Provide Services<br>For You' !!}</h2>
        <p class="services-desc">{{ $settings['svc_desc'] ?? 'Located in the heart of Cox\'s Bazar, minutes from the beach — offering 208 guestrooms with modern amenities, multi-cuisine Dew Drop Restaurant, meeting rooms, fitness center, outdoor heated pool, sauna & snooker table.' }}</p>
        <a href="{{ $settings['svc_btn_url'] ?? route('services.front') }}" class="btn-services-all">{{ $settings['svc_btn_text'] ?? 'View All Services' }}</a>
        <div class="about-scroll-dot svc-dot">
          <span></span>
        </div>
      </div>

      <div class="services-right">
        @forelse($services as $service)
        <div class="service-card" data-reveal="right" data-reveal-delay="{{ $loop->iteration }}">
          <div class="svc-content">
            <h3 class="svc-title">{{ $service->title }}</h3>
            <p class="svc-desc">{{ $service->short_desc ?? $service->description }}</p>
            <a href="{{ route('service.detail', $service->slug) }}" class="btn-view-service">View Details</a>
          </div>
          <div class="svc-img-wrap">
            @if($service->image)
              <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" loading="lazy">
            @else
              <img src="{{ asset('assets/images/gym-center-1.jpg') }}" alt="{{ $service->title }}" loading="lazy">
            @endif
          </div>
        </div>
        @empty
        <div class="service-card" data-reveal="right" data-reveal-delay="1">
          <div class="svc-content">
            <h3 class="svc-title">Dew Drop Restaurant</h3>
            <p class="svc-desc">Dew Drop Restaurant at ground level beside reception serves variety seafood specials &amp; delicious multi-cuisine. Our BBQ &amp; restaurant will make your Cox's Bazar tour memorable. Hotline: 01849-900000.</p>
            <a href="{{ route('services.front') }}#restaurant" class="btn-view-service">View Services</a>
          </div>
          <div class="svc-img-wrap">
            <img src="{{ asset('assets/images/gym-center-1.jpg') }}" alt="Dew Drop Restaurant" loading="lazy">
          </div>
        </div>
        <div class="service-card" data-reveal="right" data-reveal-delay="2">
          <div class="svc-content">
            <h3 class="svc-title">Conference &amp; Banquet</h3>
            <p class="svc-desc">Luxurious &amp; spacious top-floor hall for 200+ guests. Enjoy panoramic sea beach views from the rooftop. Perfect for seminars, workshops, AGMs, weddings &amp; occasions. Hotline: 01777-909595.</p>
            <a href="{{ route('services.front') }}#conference" class="btn-view-service">View Services</a>
          </div>
          <div class="svc-img-wrap">
            <img src="{{ asset('assets/images/gym-center-1.jpg') }}" alt="Conference and Banquet Hall" loading="lazy">
          </div>
        </div>
        <div class="service-card" data-reveal="right" data-reveal-delay="3">
          <div class="svc-content">
            <h3 class="svc-title">Airport Pickup Service</h3>
            <p class="svc-desc">We offer pick-up &amp; drop service primarily to make it convenient for guests to reach their destination hassle-free. Cox's Bazar Airport is just 10 minutes away &amp; local Bus Terminal is nearby.</p>
            <a href="{{ route('services.front') }}#pickup" class="btn-view-service">View Services</a>
          </div>
          <div class="svc-img-wrap">
            <img src="{{ asset('assets/images/gym-center-1.jpg') }}" alt="Airport Transfer Service" loading="lazy">
          </div>
        </div>
        @endforelse
      </div><!-- /services-right -->
    </div><!-- /services-inner -->
  </section><!-- /services-section -->


  <!-- ===========
       FAQ + BOOKING SECTION
  =========== -->
  <section class="faq-booking-section" id="tariff">

    <div class="section-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,55 C240,80 480,20 720,50 C960,80 1200,15 1440,55 L1440,0 L0,0 Z" fill="#e1ede8"/>
      </svg>
    </div>

    <div class="faq-deco-crosses" aria-hidden="true">
      <span></span><span></span><span></span><span></span>
      <span></span><span></span><span></span><span></span>
      <span></span><span></span><span></span><span></span>
    </div>

    <div class="faq-bg-photo">
      <img src="{{ asset('assets/images/faq-bg.jpg') }}" alt="" role="presentation" loading="lazy">
    </div>

    <div class="faq-booking-inner">

      <!-- =========== LEFT: FAQ (dynamic) =========== -->
      <div class="faq-left" data-reveal="left">
        <span class="section-badge">FAQ'S</span>
        <h2 class="faq-section-title">Clear Answers To Your<br>Questions</h2>
        <div class="faq-title-bar"></div>

        <div class="faq-list">
          @forelse($allFaqItems as $idx => $item)
          <div class="faq-item {{ $idx === 0 ? 'active' : '' }}">
            <button class="faq-question" aria-expanded="{{ $idx === 0 ? 'true' : 'false' }}">
              {{ $item['question'] }}
              <span class="faq-icon">
                <svg class="icon-minus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <svg class="icon-plus"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </span>
            </button>
            <div class="faq-answer">
              <p>{{ $item['answer'] }}</p>
            </div>
          </div>
          @empty
          <div class="faq-item active">
            <button class="faq-question" aria-expanded="true">
              Can I Check In Early Or Late At The Hotel?
              <span class="faq-icon">
                <svg class="icon-minus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <svg class="icon-plus"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </span>
            </button>
            <div class="faq-answer">
              <p>Standard Check-in time is 12:30 PM and Check-out time is 11:30 AM. Early check-in is subject to room availability. For any special arrangement please contact our 24/7 front desk in advance.</p>
            </div>
          </div>
          <div class="faq-item">
            <button class="faq-question" aria-expanded="false">
              Is Breakfast Included In The Room Rate?
              <span class="faq-icon">
                <svg class="icon-minus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <svg class="icon-plus"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </span>
            </button>
            <div class="faq-answer">
              <p>Complimentary breakfast is included in our Executive Suite packages. Other room categories offer optional breakfast add-ons. Our Dew Drop Restaurant serves a full buffet breakfast daily from 7:00 AM to 10:30 AM.</p>
            </div>
          </div>
          <div class="faq-item">
            <button class="faq-question" aria-expanded="false">
              Do You Provide Airport Transfer Service?
              <span class="faq-icon">
                <svg class="icon-minus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <svg class="icon-plus"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </span>
            </button>
            <div class="faq-answer">
              <p>Absolutely! We offer a dedicated airport pickup and drop-off service from Cox's Bazar Airport, which is just 10 minutes away. Simply share your flight details while booking and our driver will be there to meet you.</p>
            </div>
          </div>
          <div class="faq-item">
            <button class="faq-question" aria-expanded="false">
              Is Free Wi-Fi Available Throughout The Hotel?
              <span class="faq-icon">
                <svg class="icon-minus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <svg class="icon-plus"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              </span>
            </button>
            <div class="faq-answer">
              <p>Yes, complimentary high-speed Wi-Fi is available in all rooms and public areas including the lobby, restaurant, and conference hall. No password is needed — simply connect to the Hotel Beach Way network.</p>
            </div>
          </div>
          @endforelse
        </div><!-- /faq-list -->
      </div><!-- /faq-left -->

      <!-- =========== RIGHT: Booking form (static) =========== -->
      <div class="faq-right" data-reveal="right">
        <div class="booking-card">
          <span class="booking-badge">BOOKING</span>
          <h3 class="booking-title">Book Your Stay</h3>

          <form class="booking-form" id="bookingForm">
            <div class="bf-row">
              <div class="bf-field">
                <label for="bf-checkin">Check In</label>
                <input type="text" id="bf-checkin" name="checkin" placeholder="Select date" readonly>
              </div>
              <div class="bf-field">
                <label for="bf-checkout">Check Out</label>
                <input type="text" id="bf-checkout" name="checkout" placeholder="Select date" readonly>
              </div>
            </div>

            <div class="bf-row">
              <div class="bf-field">
                <label for="bf-adults">Adults</label>
                <div class="bf-select-wrap">
                  <select id="bf-adults" name="adults">
                    <option value="">Select Adults</option>
                    <option value="1">1 Adult</option>
                    <option value="2">2 Adults</option>
                    <option value="3">3 Adults</option>
                    <option value="4">4 Adults</option>
                  </select>
                  <svg class="bf-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
              </div>
              <div class="bf-field">
                <label for="bf-children">Children</label>
                <div class="bf-select-wrap">
                  <select id="bf-children" name="children">
                    <option value="">Select Children</option>
                    <option value="0">No Children</option>
                    <option value="1">1 Child</option>
                    <option value="2">2 Children</option>
                    <option value="3">3 Children</option>
                  </select>
                  <svg class="bf-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
              </div>
            </div>

            <div class="bf-row">
              <div class="bf-field">
                <label for="bf-room">Room</label>
                <div class="bf-select-wrap">
                  <select id="bf-room" name="room">
                    <option value="">Select Room</option>
                    @foreach($allRooms as $rt)
                    <option value="{{ $rt->slug }}">{{ $rt->name }}</option>
                    @endforeach
                  </select>
                  <svg class="bf-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
              </div>
              <div class="bf-field">
                <label for="bf-extrabed">Extra Bed</label>
                <div class="bf-select-wrap">
                  <select id="bf-extrabed" name="extra-bed">
                    <option value="">Select Extra Bed</option>
                    <option value="none">No Extra Bed</option>
                    <option value="1">1 Extra Bed</option>
                    <option value="2">2 Extra Beds</option>
                  </select>
                  <svg class="bf-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </div>
              </div>
            </div>

            <div id="bfAvailError" style="display:none;background:#fff3f3;border:1.5px solid #f5b8b8;border-radius:8px;color:#c0392b;padding:10px 14px;font-size:13.5px;margin-bottom:10px;"></div>
            <button type="submit" class="btn-check-avail" id="bfBtn">Check Availability</button>
          </form>
        </div>
      </div><!-- /faq-right -->

    </div><!-- /faq-booking-inner -->
  </section><!-- /faq-booking-section -->


  <!-- ===========
       BLOG & ARTICLE SECTION
  =========== -->
  @if($latestBlogs->isNotEmpty())
  <section class="blog-section" id="blog">

    {{-- <div class="section-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,45 C360,80 720,5 1080,45 C1260,65 1380,20 1440,45 L1440,0 L0,0 Z" fill="#fef5ee"/>
      </svg>
    </div> --}}

    <div class="blog-inner">

      <div class="blog-header" data-reveal="up">
        <div class="blog-header-left">
          <span class="section-badge">BLOG &amp; ARTICLE</span>
          <h2 class="blog-title">Update With Our Latest<br>Trends &amp; Insights</h2>
        </div>
        <div class="blog-header-right">
          <p>At our hotel, luxury is more than just a word it's a tradition. From exquisite design to personalized service, every detail is thoughtfully curated to create unforgettable experiences.</p>
        </div>
      </div>

      <div class="swiper blog-swiper" data-reveal="up" data-reveal-delay="2">
        <div class="swiper-wrapper">
          @foreach($latestBlogs as $post)
          <div class="swiper-slide">
            <article class="blog-card">
              <div class="blog-card-img">
                @if($post->feature_image)
                  <img src="{{ asset('storage/' . $post->feature_image) }}" alt="{{ $post->title }}" loading="lazy">
                @else
                  <img src="{{ asset('assets/images/blog-4.jpg') }}" alt="{{ $post->title }}" loading="lazy">
                @endif
              </div>
              <div class="blog-card-info">
                @if($post->published_at)
                  <div class="blog-date">
                    <span class="blog-day">{{ $post->published_at->format('d') }}</span>
                    <span class="blog-month">{{ $post->published_at->format('M Y') }}</span>
                  </div>
                @endif
                <h3 class="blog-card-title">
                  <a href="{{ route('blog.detail', $post->slug) }}">{{ $post->title }}</a>
                </h3>
              </div>
            </article>
          </div>
          @endforeach
        </div><!-- /swiper-wrapper -->
      </div><!-- /blog-swiper -->

      <div class="blog-pagination"></div>

    </div><!-- /blog-inner -->
  </section><!-- /blog-section -->
  @endif


  <!-- ===========
       HOTEL GALLERY SECTION
  =========== -->
  <section class="hotel-gallery-section" id="gallery">

    <div class="section-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,48 C180,80 360,10 540,48 C720,86 900,8 1080,48 C1260,88 1380,20 1440,48 L1440,0 L0,0 Z" fill="#ffffff"/>
      </svg>
    </div>

    <div class="hg-inner">

      <div class="hg-header" data-reveal="up">
        <span class="section-badge">OUR GALLERY</span>
        <h2 class="hg-title">Hotel Gallery</h2>
      </div>

      @php
        $galleryItems  = $gallery->values();
        $topItems      = $galleryItems->take(5);
        $bottomItems   = $galleryItems->slice(5, 3);
      @endphp

      <!-- Top grid: 1 tall left + 2×2 right -->
      <div class="hg-grid-top" data-reveal="scale" data-reveal-delay="2">
        @foreach($topItems as $idx => $img)
        <div class="hg-item {{ $idx === 0 ? 'hg-featured' : '' }}" data-index="{{ $idx }}">
          <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $img->alt ?? 'Hotel Beach Way Gallery' }}" loading="lazy">
          <div class="hg-overlay">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"/><path d="M9 21H3v-6"/><path d="M21 3l-7 7"/><path d="M3 21l7-7"/></svg>
          </div>
        </div>
        @endforeach
      </div>

      <!-- Bottom grid: 3 equal columns -->
      @if($bottomItems->count())
      <div class="hg-grid-bottom" data-reveal="up" data-reveal-delay="3">
        @foreach($bottomItems as $idx => $img)
        <div class="hg-item" data-index="{{ $idx + 5 }}">
          <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $img->alt ?? 'Hotel Beach Way Gallery' }}" loading="lazy">
          <div class="hg-overlay">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"/><path d="M9 21H3v-6"/><path d="M21 3l-7 7"/><path d="M3 21l7-7"/></svg>
          </div>
        </div>
        @endforeach
      </div>
      @endif

    </div><!-- /hg-inner -->
  </section><!-- /hotel-gallery-section -->


  <!-- ===========
       TESTIMONIALS SECTION
  =========== -->
  <section class="testimonials-section">

    <div class="section-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,55 C240,80 480,20 720,50 C960,80 1200,15 1440,55 L1440,0 L0,0 Z" fill="#F5EDE0"/>
      </svg>
    </div>

    <div class="testi-inner">

      <div class="testi-left" data-reveal="left">
        <div class="testi-img-wrap">
          @if(!empty($settings['testi_image']))
            <img src="{{ asset('storage/' . $settings['testi_image']) }}" alt="{{ $settings['testi_image_alt'] ?? 'Happy guests at Hotel Beach Way' }}" loading="lazy">
          @else
            <img src="{{ asset('assets/images/testimonials-3-1.jpg') }}" alt="Happy guests at Hotel Beach Way" loading="lazy">
          @endif
        </div>
      </div>

      <div class="testi-right" data-reveal="right">
        <span class="section-badge">TESTIMONIALS</span>
        <h2 class="testi-title">Hear From Our Satisfied<br>Customers</h2>

        <div class="swiper testi-swiper">
          <div class="swiper-wrapper">

            @forelse($testimonials as $testimonial)
            <div class="swiper-slide">
              <div class="testi-card">
                <div class="testi-quote-icon" aria-hidden="true">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor">
                    <path d="M10 8C6.134 8 3 11.134 3 15v1c0 3.866 3.134 7 7 7h1v-6H8c-.552 0-1-.448-1-1v-1c0-2.757 2.243-5 5-5V8h-2zm14 0c-3.866 0-7 3.134-7 15v1c0 3.866 3.134 7 7 7h1v-6h-3c-.552 0-1-.448-1-1v-1c0-2.757 2.243-5 5-5V8h-2z"/>
                  </svg>
                </div>
                <blockquote class="testi-text">{{ $testimonial->review }}</blockquote>
                <div class="testi-author">
                  <div class="testi-avatar-wrap">
                    @if($testimonial->avatar)
                      <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="testi-avatar"
                           onerror="this.src='{{ asset('assets/images/testi-1.jpg') }}'">
                    @else
                      <img src="{{ asset('assets/images/testi-1.jpg') }}" alt="{{ $testimonial->name }}" class="testi-avatar">
                    @endif
                    <div class="testi-rating-badge">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                      {{ number_format($testimonial->rating, 1) }}
                    </div>
                  </div>
                  <div class="testi-author-info">
                    <h4 class="testi-name">{{ $testimonial->name }}</h4>
                    <span class="testi-role">{{ $testimonial->role }}</span>
                  </div>
                </div>
              </div>
            </div>
            @empty
            <div class="swiper-slide">
              <div class="testi-card">
                <div class="testi-quote-icon" aria-hidden="true">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor"><path d="M10 8C6.134 8 3 11.134 3 15v1c0 3.866 3.134 7 7 7h1v-6H8c-.552 0-1-.448-1-1v-1c0-2.757 2.243-5 5-5V8h-2zm14 0c-3.866 0-7 3.134-7 15v1c0 3.866 3.134 7 7 7h1v-6h-3c-.552 0-1-.448-1-1v-1c0-2.757 2.243-5 5-5V8h-2z"/></svg>
                </div>
                <blockquote class="testi-text">"Very nice hotel in Cox's Bazar. Hotel Beach Way is the most modern and well furnished with luxurious fixture and fittings which offer everything you need to ensure a comfortable and pleasant stay."</blockquote>
                <div class="testi-author">
                  <div class="testi-avatar-wrap">
                    <img src="{{ asset('assets/images/testi-1.jpg') }}" alt="Md. Minhajul Islam" class="testi-avatar">
                    <div class="testi-rating-badge">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                      5.0
                    </div>
                  </div>
                  <div class="testi-author-info">
                    <h4 class="testi-name">Md. Minhajul Islam</h4>
                    <span class="testi-role">Software Engineer, O.R Nizam Road, Chittagong</span>
                  </div>
                </div>
              </div>
            </div>
            @endforelse

          </div><!-- /swiper-wrapper -->
        </div><!-- /testi-swiper -->

        <div class="testi-footer">
          <div class="testi-pagination"></div>
          <div class="about-scroll-dot"><span></span></div>
        </div>

      </div><!-- /testi-right -->
    </div><!-- /testi-inner -->
  </section><!-- /testimonials-section -->


  <!-- Lightbox overlay -->
  <div class="gallery-lightbox" id="galleryLightbox" role="dialog" aria-modal="true" aria-label="Image viewer">
    <button class="lb-close" id="lbClose" aria-label="Close lightbox">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <button class="lb-nav lb-prev" id="lbPrev" aria-label="Previous image">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <button class="lb-nav lb-next" id="lbNext" aria-label="Next image">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
    <div class="lb-stage">
      <img id="lbImg" src="" alt="">
    </div>
    <span class="lb-counter" id="lbCounter">1 / {{ $gallery->count() ?: 8 }}</span>
  </div>
 <!-- ===========
       BOOKING POPUP MODAL
  =========== -->
  @php
    $bkRoomTypesForJs = $allRooms->map(function ($room) {
        return [
            'id'                  => $room->id,
            'slug'                => $room->slug,
            'name'                => $room->name,
            'price_bdt'           => (float) $room->price,
            'price_bdt_effective' => $room->effectivePrice('BDT'),
            'price_usd'           => $room->price_usd ? (float) $room->price_usd : null,
            'price_usd_effective' => $room->effectivePrice('USD'),
            'has_usd'             => (bool) $room->price_usd,
            'price_unit'          => ltrim($room->price_unit ?? 'Night', '/'),
            'max_adults'          => (int) ($room->max_adults ?: 1),
            'max_children'        => (int) ($room->max_children ?? 0),
            'max_guests'          => (int) ($room->max_adults ?: 1) + (int) ($room->max_children ?? 0),
        ];
    })->values();
    $bkAnyUsdPricing         = $allRooms->contains(fn ($room) => (bool) $room->price_usd);
    $modalChildPolicyNote    = $settings['booking_child_policy_note'] ?? "Couple Room: 02 Adults + 01 Child (Below 5 Years)\nTriple Room: 03 Adults + 01 Child (Below 5 Years)\nFour Bed Room: 04 Adults + 02 Children (Below 5 Years)\nConnect Room: 05 Adults + 02 Children (Below 5 Years)\nHoneymoon Room: 02 Adults + 01 Child (Below 5 Years)";
    $modalCancellationPolicy = $settings['booking_cancellation_policy'] ?? "Free cancellation up to 72 hours prior to check-in. Cancellations or no-shows within 72 hours of the check-in date are non-refundable.";
    $modalAssistPhone        = ($settings['booking_assist_phone'] ?? null) ?: ($settings['footer_phone_1'] ?? '+88 01777-909595');
    $modalAssistEmail        = ($settings['booking_assist_email'] ?? null) ?: ($settings['footer_email_1'] ?? 'info@hotelbeachway.com');
  @endphp
  <script type="application/json" id="bkRoomTypesData">{!! $bkRoomTypesForJs->toJson() !!}</script>

  <div class="bk-overlay" id="bkOverlay" role="dialog" aria-modal="true" aria-label="Complete Your Booking" aria-hidden="true">
    <div class="bk-modal" id="bkModal">

      <!-- =========== Dark Header =========== -->
      <div class="bk-modal-header">
        <button class="bk-close" id="bkClose" aria-label="Close booking popup">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>

        <span class="bk-modal-badge">HOTEL BEACH WAY</span>
        <h2 class="bk-modal-title">
          Complete Your Booking
          <span></span>
        </h2>

        <!-- Editable booking fields — pre-filled from trigger form -->
        <div class="bk-summary" id="bkSummary">
          <div class="bk-summary-pill bk-date-wrap">
            <label class="bk-sum-label" for="bkSumCheckin">Check In</label>
            <input type="text" id="bkSumCheckin" name="bk_checkin" class="bk-sum-input bk-date-input" placeholder="Select date" readonly>
          </div>
          <div class="bk-summary-pill bk-date-wrap">
            <label class="bk-sum-label" for="bkSumCheckout">Check Out</label>
            <input type="text" id="bkSumCheckout" name="bk_checkout" class="bk-sum-input bk-date-input" placeholder="Select date" readonly>
          </div>
        </div>
      </div>

      <!-- =========== Form Body =========== -->
      <div class="bk-form-body" id="bkFormBody">
        <p class="bk-form-section-title">Room Selection</p>

        @if($bkAnyUsdPricing)
        <div class="bw-currency-toggle" id="bkCurrencyToggle">
          <span class="bw-currency-label">Pay In</span>
          <button type="button" class="bw-currency-btn is-active" data-currency="BDT">BDT ৳</button>
          <button type="button" class="bw-currency-btn" data-currency="USD">USD $</button>
        </div>
        @endif

        <div class="bk-room-cart" id="bkRoomCart"></div>
        <button type="button" class="bk-add-room-btn" id="bkAddRoomBtn">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Add Another Room Type
        </button>
        <div class="bk-cart-summary" id="bkCartSummary" style="display:none;">
          <div class="bk-cart-lines" id="bkCartLines"></div>
          <div class="bk-cart-total">
            <span>Estimated total</span>
            <strong id="bkCartTotal">BDT 0</strong>
          </div>
        </div>

        @if($modalChildPolicyNote)
        <div class="bk-policy-note">
          <strong>Child Policy</strong>
          <ul>
            @foreach(preg_split('/\r\n|\r|\n/', trim($modalChildPolicyNote)) as $line)
              @continue(trim($line) === '')
              <li>{{ trim($line) }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <p class="bk-form-section-title" style="margin-top:28px;">Customer Details</p>

        <form id="bkForm" novalidate>
          <x-honeypot />
          <div class="bk-grid">
            <div class="bk-field">
              <label for="bk-name">Name *</label>
              <input type="text" id="bk-name" name="name" placeholder="Ex. Name" required>
            </div>
            <div class="bk-field">
              <label for="bk-email">Email *</label>
              <input type="email" id="bk-email" name="email" placeholder="Ex. info@example.com" required>
            </div>
            <div class="bk-field">
              <label for="bk-phone">Phone Number *</label>
              <input type="tel" id="bk-phone" name="phone" placeholder="Ex. 9876543210" required>
            </div>
            <div class="bk-field bk-full">
              <label for="bk-message">Message</label>
              <textarea id="bk-message" name="message" placeholder="Ex. special requests, arrival time…"></textarea>
            </div>
          </div>

          <div id="bkFormError" style="display:none;background:#fff0f0;border:1px solid #f5b8b8;color:#c0392b;padding:10px 14px;border-radius:6px;font-size:13.5px;margin-bottom:12px;"></div>

          <div class="bk-assist-note">
            <span class="bk-assist-item">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
              <a href="tel:{{ preg_replace('/[^0-9+]/', '', $modalAssistPhone) }}">{{ $modalAssistPhone }}</a>
            </span>
            <span class="bk-assist-item">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <a href="mailto:{{ $modalAssistEmail }}">{{ $modalAssistEmail }}</a>
            </span>
            <span class="bk-assist-hint">Need assistance with your booking? We're available 24/7.</span>
          </div>

          <div class="bk-form-footer">
            <p>By submitting you agree to our Privacy Policy. We'll confirm within 24 hours.@if($modalCancellationPolicy) {{ $modalCancellationPolicy }}@endif</p>
            <button type="submit" class="bk-submit" id="bkSubmitBtn">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/></svg>
              Submit Booking
            </button>
          </div>
        </form>
      </div>

      <!-- =========== Success State =========== -->
      <div class="bk-success-state" id="bkSuccess">
        <div class="bk-success-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <h3 class="bk-success-title" id="bkSuccessTitle">Booking Request Sent!</h3>
        <p class="bk-success-desc" id="bkSuccessDesc">Thank you for choosing Hotel Beach Way. Our team will review your request and contact you within 24 hours to confirm your reservation.</p>
        <button class="bk-success-close" id="bkSuccessClose">Close</button>
      </div>

    </div><!-- /bk-modal -->
  </div><!-- /bk-overlay -->

@endsection

@push('scripts')
  <!-- ===========
       BOOKING POPUP SCRIPT
  =========== -->
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    var overlay      = document.getElementById('bkOverlay');
    var closeBtn     = document.getElementById('bkClose');
    var formBody     = document.getElementById('bkFormBody');
    var bkForm       = document.getElementById('bkForm');
    var bkSubmit     = document.getElementById('bkSubmitBtn');
    var bkSuccess    = document.getElementById('bkSuccess');
    var successClose = document.getElementById('bkSuccessClose');

    // Interactive header fields
    var sumCheckin  = document.getElementById('bkSumCheckin');
    var sumCheckout = document.getElementById('bkSumCheckout');
    var roomCart    = document.getElementById('bkRoomCart');
    var addRoomBtn  = document.getElementById('bkAddRoomBtn');
    var cartSummary = document.getElementById('bkCartSummary');
    var cartLines   = document.getElementById('bkCartLines');
    var cartTotal   = document.getElementById('bkCartTotal');

    var csrfToken = document.querySelector('meta[name="csrf-token"]')
                    ? document.querySelector('meta[name="csrf-token"]').content : '';
    var checkAvailUrl = '{{ route("booking.check-availability") }}';

    var ROOM_TYPES = JSON.parse(document.getElementById('bkRoomTypesData').textContent || '[]');
    var rows = [];
    var availabilityById = {};
    var currency = 'BDT';

    // ── Currency helpers: the server already applied any active discount into
    // *_effective, so the client just picks the right pair for the chosen currency. ──
    function isTypeUsableForCurrency(t) {
      return currency !== 'USD' || !!t.has_usd;
    }
    function currentPrice(type) {
      if (!type) return null;
      var v = currency === 'USD' ? type.price_usd_effective : type.price_bdt_effective;
      return (v === null || v === undefined) ? null : v;
    }
    function originalPrice(type) {
      if (!type) return null;
      var v = currency === 'USD' ? type.price_usd : type.price_bdt;
      return (v === null || v === undefined) ? null : v;
    }
    function currencySymbol() {
      return currency === 'USD' ? '$' : '৳';
    }
    function formatAmount(n) {
      var decimals = currency === 'USD' ? 2 : 0;
      return n.toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
    }
    function formatOptionPrice(t) {
      var eff = currentPrice(t);
      if (eff === null) return currency === 'USD' ? 'USD not available' : '';
      return currencySymbol() + formatAmount(eff) + '/' + t.price_unit;
    }
    function formatPriceLabelHtml(type) {
      if (!type) return '';
      var eff = currentPrice(type);
      if (eff === null) return currency === 'USD' ? 'USD not available for this room' : '';
      var orig = originalPrice(type);
      var sym = currencySymbol();
      var html = sym + formatAmount(eff);
      if (orig !== null && orig !== eff) {
        html = '<s>' + sym + formatAmount(orig) + '</s>' + html;
      }
      return html + '/' + type.price_unit;
    }

    // ── Room-type repeater (mirrors the cart pattern on the full /booking page) ──
    function typeById(id) {
      return ROOM_TYPES.find(function (t) { return String(t.id) === String(id); });
    }

    function usedTypeIds(excludeRow) {
      return rows.filter(function (r) { return r !== excludeRow; })
        .map(function (r) { return r.typeSelect.value; })
        .filter(Boolean);
    }

    function nextUnusedTypeId(excludeRow) {
      var used = usedTypeIds(excludeRow || null);
      var found = ROOM_TYPES.find(function (t) {
        return used.indexOf(String(t.id)) === -1 && isTypeUsableForCurrency(t);
      });
      return found ? String(found.id) : '';
    }

    function buildTypeOptions(row) {
      var current = row.typeSelect.value;
      var used = usedTypeIds(row);
      row.typeSelect.innerHTML = '';
      ROOM_TYPES.forEach(function (t) {
        var opt = document.createElement('option');
        opt.value = t.id;
        var priceStr = formatOptionPrice(t);
        opt.textContent = t.name + (priceStr ? ' — ' + priceStr : '');
        var soldOut  = availabilityById[t.id] && availabilityById[t.id].remaining === 0;
        var unusable = !isTypeUsableForCurrency(t);
        if ((used.indexOf(String(t.id)) !== -1 || soldOut || unusable) && String(t.id) !== String(current)) {
          opt.disabled = true;
        }
        row.typeSelect.appendChild(opt);
      });
      row.typeSelect.value = current || '';
    }

    function syncAllTypeOptions() {
      rows.forEach(buildTypeOptions);
      addRoomBtn.disabled = rows.length >= ROOM_TYPES.length;
      addRoomBtn.hidden = ROOM_TYPES.length <= 1;
    }

    function updateRemoveButtons() {
      rows.forEach(function (row) {
        row.removeBtn.style.visibility = rows.length > 1 ? 'visible' : 'hidden';
      });
    }

    function updateMeta(row) {
      var type = typeById(row.typeSelect.value);
      row.el.querySelector('[data-price-label]').innerHTML = formatPriceLabelHtml(type);

      var occupancyLabel = row.el.querySelector('[data-occupancy-label]');
      if (type) {
        occupancyLabel.textContent = 'Max ' + type.max_guests + ' Guest' + (type.max_guests > 1 ? 's' : '') +
          ' (' + type.max_adults + ' Adult' + (type.max_adults > 1 ? 's' : '') +
          (type.max_children > 0 ? ' + ' + type.max_children + ' Child' + (type.max_children > 1 ? 'ren' : '') : '') + ')';
      } else {
        occupancyLabel.textContent = '';
      }
    }

    function populateOccupancySelects(row) {
      var type = typeById(row.typeSelect.value);
      var maxAdults   = type ? type.max_adults : 4;
      var maxChildren = type ? type.max_children : 0;
      var prevAdults   = row.adultsSelect.value;
      var prevChildren = row.childrenSelect.value;

      row.adultsSelect.innerHTML = '';
      for (var a = 1; a <= maxAdults; a++) {
        var opt = document.createElement('option');
        opt.value = a; opt.textContent = a;
        row.adultsSelect.appendChild(opt);
      }
      row.childrenSelect.innerHTML = '';
      for (var c = 0; c <= maxChildren; c++) {
        var copt = document.createElement('option');
        copt.value = c; copt.textContent = c;
        row.childrenSelect.appendChild(copt);
      }
      row.adultsSelect.value   = (prevAdults && +prevAdults <= maxAdults) ? prevAdults : Math.min(2, maxAdults);
      row.childrenSelect.value = (prevChildren && +prevChildren <= maxChildren) ? prevChildren : 0;
    }

    function nights() {
      if (!sumCheckin.value || !sumCheckout.value) return 0;
      var diff = Math.round((new Date(sumCheckout.value).getTime() - new Date(sumCheckin.value).getTime()) / 86400000);
      return diff > 0 ? diff : 0;
    }

    function renderCart() {
      var n = nights();
      var lines = [];
      var total = 0;
      var sym = currencySymbol();

      rows.forEach(function (row) {
        var type = typeById(row.typeSelect.value);
        var qty  = parseInt(row.qtyInput.value, 10) || 0;
        var unitPrice = currentPrice(type);
        if (!type || qty < 1 || unitPrice === null) return;
        var lineTotal = unitPrice * qty * n;
        total += lineTotal;
        lines.push({ name: type.name, qty: qty, total: lineTotal });
      });

      if (!lines.length || n < 1) { cartSummary.style.display = 'none'; return; }

      cartLines.innerHTML = lines.map(function (l) {
        return '<div class="bk-cart-line"><span>' + l.qty + '&times; ' + l.name + '</span><span>' + sym +
          formatAmount(l.total) + '</span></div>';
      }).join('');
      cartTotal.textContent = sym + formatAmount(total) + (n ? ' for ' + n + ' night' + (n > 1 ? 's' : '') : '');
      cartSummary.style.display = 'block';
    }

    function setQty(row, value) {
      var max = parseInt(row.qtyInput.getAttribute('max'), 10);
      if (isNaN(max)) max = 20;
      row.qtyInput.value = Math.max(1, Math.min(max, value));
      renderCart();
    }

    function applyAvailabilityToRow(row) {
      var type = typeById(row.typeSelect.value);
      var info = type ? availabilityById[type.id] : null;

      if (!info || info.remaining === null || info.remaining === undefined) {
        row.remainingLabel.textContent = '';
        row.remainingLabel.className = 'bk-room-remaining';
        row.qtyInput.setAttribute('max', '20');
        row.el.classList.remove('bk-sold-out');
        return;
      }

      row.qtyInput.setAttribute('max', String(Math.max(info.remaining, 1)));
      if (info.remaining < 1) {
        row.remainingLabel.textContent = 'Sold out for these dates';
        row.remainingLabel.className = 'bk-room-remaining bk-remaining-none';
        row.el.classList.add('bk-sold-out');
      } else {
        row.remainingLabel.textContent = info.remaining + ' room' + (info.remaining > 1 ? 's' : '') + ' left';
        row.remainingLabel.className = 'bk-room-remaining' + (info.remaining <= 2 ? ' bk-remaining-low' : '');
        row.el.classList.remove('bk-sold-out');
        if (parseInt(row.qtyInput.value, 10) > info.remaining) setQty(row, info.remaining);
      }
    }

    function applyAvailabilityData(data) {
      availabilityById = {};
      (data.rooms || []).forEach(function (r) {
        var t = ROOM_TYPES.find(function (rt) { return rt.slug === r.slug; });
        if (t) availabilityById[t.id] = r;
      });
      rows.forEach(applyAvailabilityToRow);
      syncAllTypeOptions();
      renderCart();
    }

    function refreshAvailability() {
      if (!sumCheckin.value || !sumCheckout.value || sumCheckout.value <= sumCheckin.value) {
        availabilityById = {};
        rows.forEach(applyAvailabilityToRow);
        syncAllTypeOptions();
        return;
      }
      fetch(checkAvailUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ checkin: sumCheckin.value, checkout: sumCheckout.value }),
      })
        .then(function (res) { return res.json(); })
        .then(applyAvailabilityData)
        .catch(function () { /* soft-fail preview — submit still re-validates server-side */ });
    }

    function rowTemplate() {
      var el = document.createElement('div');
      el.className = 'bk-room-row';
      el.innerHTML =
        '<div class="bk-room-info">' +
          '<div class="bk-mini-control bk-control-type">' +
            '<span class="bk-mini-label">Room Type</span>' +
            '<div class="bk-room-select-wrap"><select class="bk-type-select"></select></div>' +
          '</div>' +
          '<div class="bk-room-meta">' +
            '<span class="bk-room-price" data-price-label></span>' +
            '<span class="bk-room-occupancy" data-occupancy-label></span>' +
            '<span class="bk-room-remaining" data-remaining-label></span>' +
          '</div>' +
        '</div>' +
        '<div class="bk-room-controls">' +
          '<div class="bk-mini-control"><span class="bk-mini-label">Rooms</span>' +
            '<div class="bk-qty-stepper">' +
              '<button type="button" class="bk-qty-btn" data-step="-1" aria-label="Decrease room quantity">&minus;</button>' +
              '<input type="number" class="bk-qty-input" value="1" min="1" max="20" inputmode="numeric" aria-label="Number of rooms of this type">' +
              '<button type="button" class="bk-qty-btn" data-step="1" aria-label="Increase room quantity">+</button>' +
            '</div>' +
          '</div>' +
          '<div class="bk-mini-control"><span class="bk-mini-label">Adults</span><select class="bk-adults-select"></select></div>' +
          '<div class="bk-mini-control"><span class="bk-mini-label">Children</span><select class="bk-children-select"></select></div>' +
          '<button type="button" class="bk-remove-btn" aria-label="Remove this room type">' +
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></svg>' +
          '</button>' +
        '</div>';
      return el;
    }

    function addRow(initialTypeId, initialAdults, initialChildren) {
      var el = rowTemplate();
      roomCart.appendChild(el);

      var row = {
        el: el,
        typeSelect: el.querySelector('.bk-type-select'),
        qtyInput: el.querySelector('.bk-qty-input'),
        adultsSelect: el.querySelector('.bk-adults-select'),
        childrenSelect: el.querySelector('.bk-children-select'),
        removeBtn: el.querySelector('.bk-remove-btn'),
        remainingLabel: el.querySelector('[data-remaining-label]'),
      };
      rows.push(row);

      buildTypeOptions(row);
      row.typeSelect.value = initialTypeId || nextUnusedTypeId();
      populateOccupancySelects(row);
      if (initialAdults)   row.adultsSelect.value   = String(initialAdults);
      if (initialChildren) row.childrenSelect.value = String(initialChildren);
      updateMeta(row);

      row.typeSelect.addEventListener('change', function () {
        if (bkFormError) bkFormError.style.display = 'none';
        populateOccupancySelects(row);
        updateMeta(row);
        syncAllTypeOptions();
        applyAvailabilityToRow(row);
        renderCart();
      });
      row.qtyInput.addEventListener('input', function () {
        setQty(row, parseInt(row.qtyInput.value, 10) || 1);
      });
      row.el.querySelectorAll('.bk-qty-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
          setQty(row, (parseInt(row.qtyInput.value, 10) || 1) + parseInt(btn.getAttribute('data-step'), 10));
        });
      });
      row.adultsSelect.addEventListener('change', renderCart);
      row.childrenSelect.addEventListener('change', renderCart);
      row.removeBtn.addEventListener('click', function () {
        if (rows.length <= 1) return;
        rows = rows.filter(function (r) { return r !== row; });
        el.remove();
        if (bkFormError) bkFormError.style.display = 'none';
        syncAllTypeOptions();
        updateRemoveButtons();
        renderCart();
      });

      syncAllTypeOptions();
      updateRemoveButtons();
      applyAvailabilityToRow(row);
      renderCart();
    }

    function resetRoomCart(prefillSlug, prefillAdults, prefillChildren) {
      rows.forEach(function (r) { r.el.remove(); });
      rows = [];
      var initialId = '';
      if (prefillSlug) {
        var t = ROOM_TYPES.find(function (rt) { return rt.slug === prefillSlug; });
        if (t) initialId = String(t.id);
      }
      addRow(initialId, prefillAdults, prefillChildren);
    }

    if (addRoomBtn) {
      addRoomBtn.addEventListener('click', function () {
        if (addRoomBtn.disabled) return;
        if (bkFormError) bkFormError.style.display = 'none';
        addRow(nextUnusedTypeId());
      });
    }

    document.querySelectorAll('#bkCurrencyToggle .bw-currency-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var next = btn.getAttribute('data-currency');
        if (next === currency) return;
        currency = next;
        document.querySelectorAll('#bkCurrencyToggle .bw-currency-btn').forEach(function (b) {
          b.classList.toggle('is-active', b === btn);
        });
        rows.forEach(function (row) {
          var type = typeById(row.typeSelect.value);
          var needsReassign = !isTypeUsableForCurrency(type || {});
          buildTypeOptions(row);
          if (needsReassign) {
            row.typeSelect.value = nextUnusedTypeId(row);
            populateOccupancySelects(row);
          }
          updateMeta(row);
          applyAvailabilityToRow(row);
        });
        syncAllTypeOptions();
        renderCart();
      });
    });

    // Exposed so the popup's own Flatpickr date pickers (initialized in a later
    // script, after the flatpickr CDN bundle loads) can refresh remaining-room
    // counts when the guest edits dates from inside the open popup.
    window.bkRefreshAvailability = refreshAvailability;

    function openPopup(data, prefetchedAvailability) {
      data = data || {};
      // Set dates via Flatpickr so the pretty UI updates (falls back to .value if fp not ready)
      if (window.bkCheckinFp)  { window.bkCheckinFp.setDate(data.checkin || null, false); }
      else if (sumCheckin)     { sumCheckin.value = data.checkin || ''; }
      if (window.bkCheckoutFp) { window.bkCheckoutFp.setDate(data.checkout || null, false); }
      else if (sumCheckout)    { sumCheckout.value = data.checkout || ''; }

      if (bkForm) bkForm.reset();
      resetRoomCart(data.room || '', data.adults || 2, data.children || 0);
      if (prefetchedAvailability) { applyAvailabilityData(prefetchedAvailability); }
      else { refreshAvailability(); }

      if (formBody) formBody.style.display = '';
      if (bkSuccess) bkSuccess.classList.remove('is-visible');

      if (!overlay) return;
      overlay.classList.add('is-open');
      overlay.style.display = 'flex';
      overlay.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    }

    function closePopup() {
      if (!overlay) return;
      overlay.classList.remove('is-open');
      overlay.style.display = '';
      overlay.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    }

    var cafAvailError  = document.getElementById('cafAvailError');
    var bfAvailError   = document.getElementById('bfAvailError');
    var bkFormError    = document.getElementById('bkFormError');

    // Core availability check — calls API, shows error or opens popup
    function runAvailabilityCheck(payload, errorEl, btnEl, originalBtnText) {
      if (errorEl) { errorEl.style.display = 'none'; errorEl.textContent = ''; }
      btnEl.disabled = true;
      btnEl.textContent = 'Checking…';

      fetch(checkAvailUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ checkin: payload.checkin, checkout: payload.checkout }),
      })
      .then(function(res) { return res.json().then(function(d) { return { ok: res.ok, data: d }; }); })
      .then(function(r) {
        btnEl.disabled = false;
        btnEl.textContent = originalBtnText;
        if (r.data.available) {
          openPopup({ checkin: payload.checkin, checkout: payload.checkout, room: payload.room_slug, adults: payload.adults, children: payload.children }, r.data);
        } else {
          var msg = r.data.message || 'No rooms available for the selected dates. Please try different dates.';
          if (errorEl) { errorEl.textContent = msg; errorEl.style.display = 'block'; }
        }
      })
      .catch(function() {
        btnEl.disabled = false;
        btnEl.textContent = originalBtnText;
        if (errorEl) { errorEl.textContent = 'Network error. Please check your connection and try again.'; errorEl.style.display = 'block'; }
      });
    }

    // =========== Check Availability bar ===========
    var cafForm = document.getElementById('checkAvailForm');
    var cafBtn  = document.getElementById('cafBtn');
    if (cafForm && cafBtn) {
      cafForm.addEventListener('submit', function (e) {
        e.preventDefault();
        var checkin  = document.getElementById('caf-checkin').value;
        var checkout = document.getElementById('caf-checkout').value;
        if (!checkin || !checkout) {
          if (cafAvailError) { cafAvailError.textContent = 'Please select both check-in and check-out dates.'; cafAvailError.style.display = 'block'; }
          return;
        }
        runAvailabilityCheck({
          checkin:   checkin,
          checkout:  checkout,
          room_slug: document.getElementById('caf-room').value,
          adults:    document.getElementById('caf-adults').value,
          children:  document.getElementById('caf-children').value,
        }, cafAvailError, cafBtn, 'Book Now');
      });
    }

    // =========== FAQ Booking card ===========
    var bfForm = document.getElementById('bookingForm');
    var bfBtn  = document.getElementById('bfBtn');
    if (bfForm && bfBtn) {
      bfForm.addEventListener('submit', function (e) {
        e.preventDefault();
        var checkin  = document.getElementById('bf-checkin').value;
        var checkout = document.getElementById('bf-checkout').value;
        if (!checkin || !checkout) {
          if (bfAvailError) { bfAvailError.textContent = 'Please select both check-in and check-out dates.'; bfAvailError.style.display = 'block'; }
          return;
        }
        var roomEl   = document.getElementById('bf-room');
        var adultsEl = document.getElementById('bf-adults');
        var childEl  = document.getElementById('bf-children');
        runAvailabilityCheck({
          checkin:   checkin,
          checkout:  checkout,
          room_slug: roomEl   ? roomEl.value   : '',
          adults:    adultsEl ? (adultsEl.value || '1') : '1',
          children:  childEl  ? (childEl.value  || '0') : '0',
        }, bfAvailError, bfBtn, 'Check Availability');
      });
    }

    // =========== "Book Your Stay" hero button ===========
    document.querySelectorAll('.btn-discover').forEach(function (btn) {
      if (btn.textContent.trim() === 'Book Your Stay') {
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          openPopup({ checkin: '', checkout: '', room: '', adults: '1', children: '0' });
        });
      }
    });

    // =========== Close handlers ===========
    if (closeBtn)     closeBtn.addEventListener('click', closePopup);
    if (successClose) successClose.addEventListener('click', closePopup);
    overlay.addEventListener('click', function (e) { if (e.target === overlay) closePopup(); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && overlay.classList.contains('is-open')) closePopup();
    });

    // =========== Form submission ===========
    var bkSuccessTitle = document.getElementById('bkSuccessTitle');
    var bkSuccessDesc  = document.getElementById('bkSuccessDesc');

    var submitIcon  = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/></svg> ';
    var loadingIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> ';

    if (bkForm) {
      bkForm.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!bkForm.checkValidity()) { bkForm.reportValidity(); return; }

        var roomsPayload = [];
        var seenTypeIds = {};
        var duplicateFound = false;
        var currencyUnavailable = false;
        rows.forEach(function (row) {
          var typeId = row.typeSelect.value;
          var type   = typeById(typeId);
          if (!type) return;
          if (seenTypeIds[typeId]) { duplicateFound = true; return; }
          seenTypeIds[typeId] = true;
          if (!isTypeUsableForCurrency(type)) { currencyUnavailable = true; return; }
          var qty = parseInt(row.qtyInput.value, 10) || 0;
          if (qty < 1) return;
          roomsPayload.push({
            room_slug: type.slug,
            quantity:  qty,
            adults:    parseInt(row.adultsSelect.value, 10) || 1,
            children:  parseInt(row.childrenSelect.value, 10) || 0,
          });
        });

        if (duplicateFound) {
          if (bkFormError) { bkFormError.textContent = 'You have selected the same room type more than once. Please remove the duplicate or choose a different type for each row.'; bkFormError.style.display = 'block'; }
          return;
        }
        if (currencyUnavailable) {
          if (bkFormError) { bkFormError.textContent = 'One of the selected rooms isn\'t available in USD. Please switch to BDT or choose a different room.'; bkFormError.style.display = 'block'; }
          return;
        }
        if (!roomsPayload.length) {
          if (bkFormError) { bkFormError.textContent = 'Please select at least one room.'; bkFormError.style.display = 'block'; }
          return;
        }

        if (bkFormError) { bkFormError.style.display = 'none'; bkFormError.textContent = ''; }
        bkSubmit.disabled = true;
        bkSubmit.innerHTML = loadingIcon + 'Sending…';

        var payload = {
          name:             document.getElementById('bk-name').value,
          email:            document.getElementById('bk-email').value,
          phone:            document.getElementById('bk-phone').value,
          checkin:          sumCheckin  ? sumCheckin.value  : '',
          checkout:         sumCheckout ? sumCheckout.value : '',
          message:          document.getElementById('bk-message').value,
          currency:         currency,
          rooms:            roomsPayload,
          company_website:  bkForm.querySelector('[name="company_website"]')?.value || '',
          form_rendered_at: bkForm.querySelector('[name="form_rendered_at"]')?.value || '',
        };

        fetch('{{ route("booking.store") }}', {
          method:  'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept':        'application/json',
            'X-CSRF-TOKEN':  csrfToken,
          },
          body: JSON.stringify(payload),
        })
        .then(function (res) { return res.json().then(function (d) { return { ok: res.ok, data: d }; }); })
        .then(function (r) {
          bkSubmit.disabled = false;
          bkSubmit.innerHTML = submitIcon + 'Submit Booking';
          if (r.ok && r.data.success) {
            if (bkSuccessTitle) bkSuccessTitle.textContent = 'Booking Request Sent!';
            if (bkSuccessDesc)  bkSuccessDesc.innerHTML   = r.data.message || 'We will confirm within 24 hours.';
            if (formBody) formBody.style.display = 'none';
            if (bkSuccess) bkSuccess.classList.add('is-visible');
          } else {
            var msg = (r.data.errors ? Object.values(r.data.errors).flat().join(' ') : null)
                      || r.data.message || 'Something went wrong. Please try again.';
            if (bkFormError) { bkFormError.textContent = msg; bkFormError.style.display = 'block'; }
          }
        })
        .catch(function () {
          bkSubmit.disabled = false;
          bkSubmit.innerHTML = submitIcon + 'Submit Booking';
          if (bkFormError) { bkFormError.textContent = 'Network error. Please check your connection and try again.'; bkFormError.style.display = 'block'; }
        });
      });
    }
  }); // DOMContentLoaded
  </script>

  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script>
  (function () {
    var today = new Date();
    today.setHours(0, 0, 0, 0);

    function addAltClass(instance, cls) {
      if (instance.altInput) instance.altInput.classList.add(cls);
    }

    // =========== Popup modal date pickers ===========
    window.bkCheckinFp = flatpickr('#bkSumCheckin', {
      dateFormat: 'Y-m-d',
      altInput: true,
      altFormat: 'M j, Y',
      minDate: 'today',
      disableMobile: true,
      onReady: function () {
        if (this.altInput) {
          this.altInput.classList.add('bk-sum-input', 'bk-date-input');
          this.altInput.readOnly = true;
        }
      },
      onChange: function (selectedDates) {
        if (selectedDates[0]) {
          var nextDay = new Date(selectedDates[0]);
          nextDay.setDate(nextDay.getDate() + 1);
          window.bkCheckoutFp.set('minDate', nextDay);
          if (window.bkCheckoutFp.selectedDates[0] && window.bkCheckoutFp.selectedDates[0] <= selectedDates[0]) {
            window.bkCheckoutFp.clear();
          }
        }
        if (window.bkRefreshAvailability) window.bkRefreshAvailability();
      },
    });

    window.bkCheckoutFp = flatpickr('#bkSumCheckout', {
      dateFormat: 'Y-m-d',
      altInput: true,
      altFormat: 'M j, Y',
      minDate: new Date(today.getTime() + 86400000),
      disableMobile: true,
      onReady: function () {
        if (this.altInput) {
          this.altInput.classList.add('bk-sum-input', 'bk-date-input');
          this.altInput.readOnly = true;
        }
      },
      onChange: function () {
        if (window.bkRefreshAvailability) window.bkRefreshAvailability();
      },
    });

    // =========== Check Availability bar ===========
    var cafCheckin = flatpickr('#caf-checkin', {
      dateFormat: 'Y-m-d',
      altInput: true,
      altFormat: 'M j, Y',
      minDate: 'today',
      disableMobile: true,
      onReady: function () { addAltClass(this, 'caf-date-input'); },
      onChange: function (selectedDates) {
        if (selectedDates[0]) {
          var nextDay = new Date(selectedDates[0]);
          nextDay.setDate(nextDay.getDate() + 1);
          cafCheckout.set('minDate', nextDay);
          if (cafCheckout.selectedDates[0] && cafCheckout.selectedDates[0] <= selectedDates[0]) {
            cafCheckout.clear();
          }
        }
      },
    });

    var cafCheckout = flatpickr('#caf-checkout', {
      dateFormat: 'Y-m-d',
      altInput: true,
      altFormat: 'M j, Y',
      minDate: new Date(today.getTime() + 86400000),
      disableMobile: true,
      onReady: function () { addAltClass(this, 'caf-date-input'); },
    });

    // =========== FAQ Booking form ===========
    var bfCheckin = flatpickr('#bf-checkin', {
      dateFormat: 'Y-m-d',
      altInput: true,
      altFormat: 'M j, Y',
      minDate: 'today',
      disableMobile: true,
      onReady: function () { if (this.altInput) this.altInput.classList.add('bf-date-alt'); },
      onChange: function (selectedDates) {
        if (selectedDates[0]) {
          var nextDay = new Date(selectedDates[0]);
          nextDay.setDate(nextDay.getDate() + 1);
          bfCheckout.set('minDate', nextDay);
          if (bfCheckout.selectedDates[0] && bfCheckout.selectedDates[0] <= selectedDates[0]) {
            bfCheckout.clear();
          }
        }
      },
    });

    var bfCheckout = flatpickr('#bf-checkout', {
      dateFormat: 'Y-m-d',
      altInput: true,
      altFormat: 'M j, Y',
      minDate: new Date(today.getTime() + 86400000),
      disableMobile: true,
      onReady: function () { if (this.altInput) this.altInput.classList.add('bf-date-alt'); },
    });
  })();
  </script>

@endpush
