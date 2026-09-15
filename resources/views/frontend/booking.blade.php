@extends('layouts.frontend')

@section('title', !empty($settings['booking_seo_title']) ? $settings['booking_seo_title'] : 'Book Your Stay – Hotel Beach Way')
@section('meta_description', !empty($settings['booking_seo_description']) ? $settings['booking_seo_description'] : 'Reserve your room at Hotel Beach Way, Cox\'s Bazar. Easy online booking with instant confirmation.')
@if(!empty($settings['booking_seo_keywords']))
@section('meta_keywords', $settings['booking_seo_keywords'])
@endif
@section('og_title', !empty($settings['booking_seo_title']) ? $settings['booking_seo_title'] : 'Book Your Stay – Hotel Beach Way')
@section('og_description', !empty($settings['booking_seo_description']) ? $settings['booking_seo_description'] : 'Reserve your room at Hotel Beach Way, Cox\'s Bazar. Easy online booking with instant confirmation.')
@if(!empty($settings['booking_seo_og_image']))
@section('og_image', asset('storage/' . $settings['booking_seo_og_image']))
@endif

@section('content')

@php
  $heroImage = $settings['booking_hero_image'] ?? ($settings['rooms_hero_image'] ?? null);
  $heroTitle = $settings['booking_hero_title'] ?? 'Book Now';
  $phone     = ($settings['booking_assist_phone'] ?? null) ?: ($settings['footer_phone_1'] ?? '+88 01777-909595');
  $email     = ($settings['booking_assist_email'] ?? null) ?: ($settings['footer_email_1'] ?? 'info@hotelbeachway.com');
  $address   = $settings['footer_address'] ?? "Kolatoli Road, Cox's Bazar";
  $childPolicyNote      = $settings['booking_child_policy_note'] ?? "Couple Room: 02 Adults + 01 Child (Below 5 Years)\nTriple Room: 03 Adults + 01 Child (Below 5 Years)\nFour Bed Room: 04 Adults + 02 Children (Below 5 Years)\nConnect Room: 05 Adults + 02 Children (Below 5 Years)\nHoneymoon Room: 02 Adults + 01 Child (Below 5 Years)";
  $cancellationPolicy   = $settings['booking_cancellation_policy'] ?? "Free cancellation up to 72 hours prior to check-in. Cancellations or no-shows within 72 hours of the check-in date are non-refundable.";
@endphp

  <!-- ===========
       PAGE BREADCRUMB HERO
  =========== -->
  <section class="page-hero">
    <div class="page-hero-bg"
      @if($heroImage)
        style="background-image: url('{{ asset('storage/' . $heroImage) }}'); background-size: cover; background-position: center;"
      @endif
    ></div>
    <div class="page-hero-overlay"></div>
    <div class="page-hero-content">
      <h1 class="page-hero-title">{{ $heroTitle }}</h1>
      <nav class="page-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="bc-sep">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
        <span class="bc-current">{{ $heroTitle }}</span>
      </nav>
    </div>
    <div class="page-hero-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,40 C360,75 720,5 1080,40 C1260,58 1380,18 1440,40 L1440,80 L0,80 Z" fill="#EDF3F9"/>
      </svg>
    </div>
  </section>


  <!-- ===========
       BOOKING PAGE SECTION
  =========== -->
  <section class="booking-page-section">
    <div class="bp-single-inner">

      <!-- =========== Main Booking Card =========== -->
      <div class="bp-main-card" data-reveal="up">

        <!-- Card Header -->
        <div class="bp-card-header">
          <div class="bp-header-text">
            <span class="booking-badge">BOOKING</span>
            <h2 class="bp-main-title">Book Your Stay</h2>
            <p class="bp-main-desc">Fill in your stay details and we'll confirm your reservation within 24 hours.</p>
          </div>
          <div class="bp-header-deco" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
          </div>
        </div>

        <form class="bp-unified-form" id="bookingUnifiedForm" novalidate>
          @csrf
          <x-honeypot />

          <!-- =========== Stay Details =========== -->
          <div class="bp-section-heading">
            <span class="bp-section-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </span>
            Stay Details
          </div>

          <div class="bp-stay-grid bp-stay-grid-dates">

            <div class="bp-field">
              <label for="bw-checkin">Check In <span class="bp-req">*</span></label>
              <input type="text" id="bw-checkin" name="checkin" placeholder="Select date" readonly>
            </div>

            <div class="bp-field">
              <label for="bw-checkout">Check Out <span class="bp-req">*</span></label>
              <input type="text" id="bw-checkout" name="checkout" placeholder="Select date" readonly>
            </div>

          </div><!-- /bp-stay-grid -->

          <div class="bp-divider"></div>

          <!-- =========== Room Selection =========== -->
          <div class="bp-section-heading">
            <span class="bp-section-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            </span>
            Room Selection
          </div>
          <p class="bw-room-hint">Pick a room type below, then use <strong>Add Another Room Type</strong> if you'd like to book more than one type in this reservation.</p>

          @php
            $roomTypesForJs = $rooms->map(function ($room) {
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
            $anyUsdPricing = $rooms->contains(fn ($room) => (bool) $room->price_usd);
          @endphp
          <script type="application/json" id="bwRoomTypesData">{!! $roomTypesForJs->toJson() !!}</script>

          @if($anyUsdPricing)
          <div class="bw-currency-toggle" id="bwCurrencyToggle">
            <span class="bw-currency-label">Pay In</span>
            <button type="button" class="bw-currency-btn is-active" data-currency="BDT">BDT ৳</button>
            <button type="button" class="bw-currency-btn" data-currency="USD">USD $</button>
          </div>
          @endif

          <div class="bw-room-cart" id="bwRoomCart"></div>

          <button type="button" class="bw-add-room-btn" id="bwAddRoomBtn">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Another Room Type
          </button>

          <div class="bw-cart-summary" id="bwCartSummary" style="display:none;">
            <div class="bw-cart-lines" id="bwCartLines"></div>
            <div class="bw-cart-total">
              <span>Estimated total</span>
              <strong id="bwCartTotal">BDT 0</strong>
            </div>
          </div>

          @if($childPolicyNote)
          <div class="bp-policy-note bp-child-policy-note">
            <strong>Child Policy</strong>
            <ul>
              @foreach(preg_split('/\r\n|\r|\n/', trim($childPolicyNote)) as $line)
                @continue(trim($line) === '')
                <li>{{ trim($line) }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <div class="bp-divider"></div>

          <!-- =========== Customer Details =========== -->
          <div class="bp-section-heading">
            <span class="bp-section-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </span>
            Customer Details
          </div>

          <div class="bp-customer-grid bp-customer-grid-3">

            <div class="bp-field">
              <label for="bp-name">Full Name <span class="bp-req">*</span></label>
              <input type="text" id="bp-name" name="name" placeholder="Ex. John Doe" required>
            </div>

            <div class="bp-field">
              <label for="bp-email">Email Address <span class="bp-req">*</span></label>
              <input type="email" id="bp-email" name="email" placeholder="Ex. john@example.com" required>
            </div>

            <div class="bp-field">
              <label for="bp-phone">Phone Number <span class="bp-req">*</span></label>
              <input type="tel" id="bp-phone" name="phone" placeholder="Ex. +880 1700 000000" required>
            </div>

          </div><!-- /bp-customer-grid -->

          <div class="bp-field bp-field-full">
            <label for="bp-message">Special Requests / Message</label>
            <textarea id="bp-message" name="message" placeholder="Any special requests or additional information..."></textarea>
          </div>

          <!-- Error -->
          <div class="bp-error" id="bwError" style="display:none;"></div>

          <!-- Submit -->
          <div class="bp-form-footer">
            <p class="bp-form-note">By submitting you agree to our Privacy Policy. We'll confirm within 24 hours.</p>
            <button type="submit" class="btn-book-now" id="bwSubmitBtn">
              <span id="bwBtnText">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/></svg>
                <span>Book Now</span>
              </span>
              <span id="bwBtnSpinner" style="display:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="animation:spin 1s linear infinite;vertical-align:middle;"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg>
                Sending...
              </span>
            </button>
          </div>

          <!-- Success state -->
          <div class="bp-success" id="bwSuccess" style="display:none;">
            <div class="bp-success-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <h4>Booking Request Sent!</h4>
            <p id="bwSuccessMsg">We'll contact you shortly to confirm your booking.</p>
          </div>

        </form>
      </div><!-- /bp-main-card -->


      <!-- =========== Need Assistance =========== -->
      <div class="bp-assist-card" data-reveal="up" data-reveal-delay="2">
        <div class="bp-assist-inner">
          <div class="bp-assist-info">
            <h4 class="bp-assist-title">Need Assistance?</h4>
            <p class="bp-assist-desc">Our front desk is available 24/7 to help with your reservation.</p>
            <ul class="bp-assist-list">
              <li>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">{{ $phone }}</a>
              </li>
              <li>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <a href="mailto:{{ $email }}">{{ $email }}</a>
              </li>
              @if($address)
              <li>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>{{ $address }}</span>
              </li>
              @endif
              <li>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span>24/7 Front Desk Available</span>
              </li>
            </ul>
          </div>
          <div class="bp-assist-features">
            <div class="bp-feature-item">
              <div class="bp-feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              </div>
              <div>
                <strong>Secure Booking</strong>
                <span>Your data is safe with us</span>
              </div>
            </div>
            <div class="bp-feature-item">
              <div class="bp-feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              </div>
              <div>
                <strong>Instant Confirmation</strong>
                <span>Confirmed within 24 hours</span>
              </div>
            </div>
            <div class="bp-feature-item">
              <div class="bp-feature-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              </div>
              <div>
                <strong>24/7 Support</strong>
                <span>Always here to help</span>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /bp-assist-card -->

      @if($cancellationPolicy)
      <!-- =========== Cancellation Policy =========== -->
      <div class="bp-policy-card" data-reveal="up" data-reveal-delay="3">
        <div class="bp-policy-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div>
          <h4 class="bp-policy-title">Cancellation Policy</h4>
          <p class="bp-policy-text">{{ $cancellationPolicy }}</p>
        </div>
      </div><!-- /bp-policy-card -->
      @endif

    </div><!-- /bp-single-inner -->
  </section>


@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
  @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

  /* =========== Page section =========== */
  .booking-page-section {
    background: #EDF3F9;
    padding: 60px 0 100px;
  }

  .bp-single-inner {
    max-width: 960px;
    margin: 0 auto;
    padding: 0 24px;
    display: flex;
    flex-direction: column;
    gap: 32px;
  }

  /* =========== Main card =========== */
  .bp-main-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 8px 48px rgba(0,0,0,0.08);
    overflow: hidden;
  }

  .bp-card-header {
    background: linear-gradient(135deg, #0a1a4e 0%, #0f288d 60%, #1a3aaa 100%);
    padding: 44px 48px 40px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 32px;
  }

  /* Decorative circles */
  .bp-card-header::before {
    content: '';
    position: absolute;
    right: -80px;
    top: -80px;
    width: 280px;
    height: 280px;
    border-radius: 50%;
    border: 40px solid rgba(255,255,255,0.05);
    pointer-events: none;
  }
  .bp-card-header::after {
    content: '';
    position: absolute;
    right: 60px;
    top: -120px;
    width: 200px;
    height: 200px;
    border-radius: 50%;
    border: 30px solid rgba(255,255,255,0.04);
    pointer-events: none;
  }

  .bp-header-text { position: relative; z-index: 1; }

  .bp-card-header .booking-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 16px;
    border: 1.5px solid rgba(255,255,255,0.3);
    border-radius: 50px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.8px;
    color: rgba(255,255,255,0.85);
    text-transform: uppercase;
    margin-bottom: 16px;
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(4px);
  }
  .bp-card-header .booking-badge::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #7eb3ff;
    display: block;
    flex-shrink: 0;
  }

  .bp-main-title {
    position: relative;
    z-index: 1;
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(28px, 3.5vw, 42px);
    font-weight: 700;
    color: #fff;
    margin-bottom: 12px;
    line-height: 1.15;
  }

  .bp-main-desc {
    position: relative;
    z-index: 1;
    font-size: 14.5px;
    color: rgba(255,255,255,0.6);
    line-height: 1.65;
    max-width: 440px;
  }

  /* Decorative right icon */
  .bp-header-deco {
    position: relative;
    z-index: 1;
    flex-shrink: 0;
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background: rgba(255,255,255,0.07);
    border: 1.5px solid rgba(255,255,255,0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.5);
  }
  .bp-header-deco svg { width: 48px; height: 48px; }
  @media (max-width: 640px) { .bp-header-deco { display: none; } }

  /* =========== Unified form =========== */
  .bp-unified-form {
    padding: 40px 48px 44px;
  }
  @media (max-width: 640px) {
    .bp-card-header { padding: 28px 24px 24px; }
    .bp-unified-form { padding: 28px 24px 32px; }
  }

  /* =========== Section headings inside form =========== */
  .bp-section-heading {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    font-weight: 700;
    color: #0f288d;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 20px;
  }
  .bp-section-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(15,40,141,0.1);
    color: #0f288d;
    flex-shrink: 0;
  }
  .bp-section-icon svg { width: 16px; height: 16px; }

  /* =========== Stay grid (2x2) =========== */
  .bp-stay-grid {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr;
    gap: 20px;
    margin-bottom: 36px;
  }
  @media (max-width: 768px) {
    .bp-stay-grid { grid-template-columns: 1fr 1fr; }
  }
  @media (max-width: 480px) {
    .bp-stay-grid { grid-template-columns: 1fr; }
  }

  /* =========== Customer grid (2x2) =========== */
  .bp-customer-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
  }
  .bp-customer-grid-3 { grid-template-columns: 1fr 1fr 1fr; }
  @media (max-width: 640px) {
    .bp-customer-grid { grid-template-columns: 1fr; }
  }

  /* =========== Stay grid: dates only (2 cols) =========== */
  .bp-stay-grid-dates { grid-template-columns: 1fr 1fr; }
  @media (max-width: 480px) {
    .bp-stay-grid-dates { grid-template-columns: 1fr; }
  }

  /* =========== Room selection cart =========== */
  .bw-room-hint {
    font-size: 13.5px;
    color: #9ca3af;
    margin: -8px 0 18px;
    line-height: 1.55;
  }

  .bw-room-cart {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 4px;
  }

  /* -- Currency toggle -- */
  .bw-currency-toggle {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: -8px 0 18px;
  }
  .bw-currency-label {
    font-size: 11px;
    font-weight: 600;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.4px;
  }
  .bw-currency-btn {
    padding: 6px 16px;
    border: 1.5px solid #e8eaf0;
    border-radius: 50px;
    background: #fff;
    color: #6b7280;
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    transition: border-color 0.2s ease, background 0.2s ease, color 0.2s ease;
  }
  .bw-currency-btn:hover { border-color: #0f288d; color: #0f288d; }
  .bw-currency-btn.is-active {
    border-color: #0f288d;
    background: #0f288d;
    color: #fff;
  }
  .bw-room-price s { opacity: 0.55; margin-right: 4px; font-weight: 400; }

  .bw-room-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
    padding: 16px 20px;
    border: 1.5px solid #e8eaf0;
    border-radius: 12px;
    background: #fafbfc;
    transition: border-color 0.2s ease, background 0.2s ease;
  }
  .bw-room-row.bw-has-qty {
    border-color: #0f288d;
    background: rgba(15,40,141,0.03);
  }
  .bw-room-row.bw-sold-out {
    opacity: 0.55;
  }

  .bw-room-info {
    display: flex;
    flex-direction: column;
    gap: 7px;
    min-width: 220px;
  }
  .bw-control-type { min-width: 220px; }
  .bw-control-type .bf-select-wrap select { min-width: 220px; }

  .bw-room-meta {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
  }
  .bw-room-price { font-size: 12.5px; color: #6b7280; }
  .bw-room-occupancy {
    font-size: 11.5px;
    font-weight: 600;
    color: #0f288d;
    background: rgba(15,40,141,0.08);
    padding: 2px 9px;
    border-radius: 20px;
  }
  .bw-room-remaining {
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: 0.3px;
    color: #0f288d;
    text-transform: uppercase;
    min-height: 14px;
  }
  .bw-room-remaining.bw-remaining-low { color: #c0392b; }
  .bw-room-remaining.bw-remaining-none { color: #c0392b; }

  .bw-room-controls {
    display: flex;
    align-items: flex-end;
    gap: 16px;
    flex-wrap: wrap;
  }

  /* -- Remove room-type button -- */
  .bw-remove-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border: 1.5px solid #e8eaf0;
    border-radius: 50%;
    background: #fff;
    color: #9ca3af;
    cursor: pointer;
    flex-shrink: 0;
    transition: border-color 0.2s ease, color 0.2s ease, background 0.2s ease;
  }
  .bw-remove-btn svg { width: 15px; height: 15px; }
  .bw-remove-btn:hover { border-color: #e3a0a0; color: #c0392b; background: #fff5f5; }

  /* -- Add another room type -- */
  .bw-add-room-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 13px 20px;
    margin-top: 4px;
    border: 1.5px dashed #c7cfdd;
    border-radius: 12px;
    background: transparent;
    color: #0f288d;
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    transition: border-color 0.2s ease, background 0.2s ease;
  }
  .bw-add-room-btn svg { width: 16px; height: 16px; }
  .bw-add-room-btn:hover { border-color: #0f288d; background: rgba(15,40,141,0.03); }
  .bw-add-room-btn:disabled { opacity: 0.45; cursor: not-allowed; }
  .bw-add-room-btn[hidden] { display: none; }

  .bw-control {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .bw-control-label {
    font-size: 11px;
    font-weight: 600;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.4px;
  }

  .bw-qty-stepper {
    display: flex;
    align-items: center;
    border: 1.5px solid #e8eaf0;
    border-radius: 10px;
    background: #fff;
    overflow: hidden;
  }
  .bw-qty-btn {
    width: 34px;
    height: 36px;
    border: none;
    background: transparent;
    color: #0f288d;
    font-size: 17px;
    font-weight: 700;
    cursor: pointer;
    line-height: 1;
    transition: background 0.15s ease;
  }
  .bw-qty-btn:hover { background: rgba(15,40,141,0.08); }
  .bw-qty-btn:disabled { color: #c9cdd6; cursor: not-allowed; background: none; }
  .bw-qty-input {
    width: 40px;
    height: 36px;
    border: none;
    border-left: 1.5px solid #e8eaf0;
    border-right: 1.5px solid #e8eaf0;
    text-align: center;
    font-size: 14px;
    font-weight: 700;
    color: #1c1c2e;
    -moz-appearance: textfield;
  }
  .bw-qty-input::-webkit-outer-spin-button,
  .bw-qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

  .bf-select-wrap-sm select {
    padding: 8px 32px 8px 12px !important;
    min-width: 68px;
    font-size: 13px !important;
  }
  .bf-select-wrap-sm .bf-chevron { width: 13px; height: 13px; right: 10px; }

  @media (max-width: 560px) {
    .bw-room-row { flex-direction: column; align-items: flex-start; }
    .bw-room-controls { width: 100%; justify-content: space-between; }
  }

  /* -- Cart summary -- */
  .bw-cart-summary {
    margin-top: 16px;
    padding: 16px 20px;
    background: rgba(15,40,141,0.04);
    border: 1.5px dashed rgba(15,40,141,0.25);
    border-radius: 12px;
  }
  .bw-cart-lines {
    display: flex;
    flex-direction: column;
    gap: 4px;
    margin-bottom: 10px;
    font-size: 13px;
    color: #4a5a6a;
  }
  .bw-cart-lines .bw-cart-line {
    display: flex;
    justify-content: space-between;
    gap: 12px;
  }
  .bw-cart-total {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    font-size: 14px;
    color: #1c1c2e;
    font-weight: 600;
    padding-top: 10px;
    border-top: 1px solid rgba(15,40,141,0.15);
  }
  .bw-cart-total strong { font-size: 18px; color: #0f288d; }

  /* =========== Policy note (child policy) =========== */
  .bp-policy-note {
    margin-top: 16px;
    padding: 16px 20px;
    background: #fff8ec;
    border: 1.5px solid #f2dfb0;
    border-radius: 12px;
  }
  .bp-policy-note strong {
    display: block;
    font-size: 12.5px;
    font-weight: 700;
    color: #8a6410;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
  }
  .bp-policy-note ul {
    margin: 0;
    padding-left: 18px;
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  .bp-policy-note li {
    font-size: 13px;
    color: #6b5a2e;
    line-height: 1.5;
  }

  /* =========== Cancellation policy card =========== */
  .bp-policy-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    padding: 28px 32px;
    display: flex;
    align-items: flex-start;
    gap: 18px;
  }
  .bp-policy-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(15,40,141,0.08);
    color: #0f288d;
    flex-shrink: 0;
  }
  .bp-policy-icon svg { width: 22px; height: 22px; }
  .bp-policy-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 17px;
    font-weight: 700;
    color: #1c1c2e;
    margin-bottom: 6px;
  }
  .bp-policy-text {
    font-size: 13.5px;
    color: #6b7280;
    line-height: 1.65;
  }

  /* =========== Divider =========== */
  .bp-divider {
    border: none;
    border-top: 1.5px solid #f0f0f0;
    margin: 0 0 32px;
  }

  /* =========== Fields =========== */
  .bp-field {
    display: flex;
    flex-direction: column;
    gap: 7px;
  }
  .bp-field-full { margin-top: 4px; }

  .bp-field label {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .bp-req { color: #0f288d; font-size: 12px; margin-left: 2px; }

  .bp-field input[type="text"],
  .bp-field input[type="email"],
  .bp-field input[type="tel"],
  .bp-field input[type="date"],
  .bp-field textarea {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #e8eaf0;
    border-radius: 10px;
    font-size: 14px;
    font-family: inherit;
    color: #1c1c2e;
    background: #fafbfc;
    outline: none;
    transition: border-color 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
  }
  .bp-field input:focus,
  .bp-field textarea:focus {
    border-color: #0f288d;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(15,40,141,0.1);
  }
  .bp-field input::placeholder,
  .bp-field textarea::placeholder { color: #b0b8c4; }

  .bp-field textarea {
    min-height: 120px;
    resize: vertical;
  }

  /* Select wrap */
  .bp-field .bf-select-wrap,
  .bw-control .bf-select-wrap {
    position: relative;
  }
  .bp-field .bf-select-wrap select,
  .bw-control .bf-select-wrap select {
    width: 100%;
    padding: 12px 40px 12px 16px;
    border: 1.5px solid #e8eaf0;
    border-radius: 10px;
    font-size: 14px;
    font-family: inherit;
    color: #1c1c2e;
    background: #fafbfc;
    outline: none;
    appearance: none;
    -webkit-appearance: none;
    cursor: pointer;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  .bp-field .bf-select-wrap select:focus,
  .bw-control .bf-select-wrap select:focus {
    border-color: #0f288d;
    box-shadow: 0 0 0 3px rgba(15,40,141,0.1);
  }
  .bp-field .bf-chevron,
  .bw-control .bf-chevron {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    color: #9ca3af;
    pointer-events: none;
  }
  /* -- Form footer -- */
  .bp-form-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    margin-top: 32px;
    padding-top: 28px;
    border-top: 1.5px solid #f0f0f0;
    flex-wrap: wrap;
  }
  .bp-form-note {
    font-size: 13px;
    color: #9ca3af;
    line-height: 1.55;
    max-width: 340px;
  }

  /* -- Submit button -- */
  .btn-book-now {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 14px 36px;
    background: #0f288d;
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    white-space: nowrap;
    width: auto;
    align-self: center;
    transition: background 0.25s ease, transform 0.2s ease, box-shadow 0.25s ease;
    box-shadow: 0 6px 24px rgba(15,40,141,0.30);
    letter-spacing: 0.3px;
  }
  /* Icon + label inside button — must be flex so SVG (display:block) stays inline */
  #bwBtnText {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    line-height: 1;
  }
  #bwBtnText svg {
    display: block;
    width: 17px;
    height: 17px;
    flex-shrink: 0;
  }
  #bwBtnSpinner {
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }
  .btn-book-now:hover {
    background: #0d2f7d;
    transform: translateY(-2px);
    box-shadow: 0 10px 32px rgba(15,40,141,0.42);
  }
  .btn-book-now:disabled { opacity: 0.7; pointer-events: none; }

  @media (max-width: 560px) {
    .bp-form-footer { flex-direction: column; align-items: flex-start; }
    .btn-book-now { align-self: flex-start; }
  }

  /* =========== Error =========== */
  .bp-error {
    background: #fff2f2;
    border: 1.5px solid #f5a0a0;
    border-radius: 10px;
    padding: 14px 18px;
    font-size: 13.5px;
    color: #c0392b;
    margin-top: 20px;
    line-height: 1.5;
  }

  /* =========== Success =========== */
  .bp-success {
    text-align: center;
    padding: 40px 20px;
  }
  .bp-success-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: rgba(15,40,141,0.1);
    color: #0f288d;
    margin: 0 auto 20px;
  }
  .bp-success-icon svg { width: 34px; height: 34px; }
  .bp-success h4 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 22px;
    font-weight: 700;
    color: #1c1c2e;
    margin-bottom: 10px;
  }
  .bp-success p { font-size: 14px; color: #6b7280; line-height: 1.65; }

  /* =========== Assistance card =========== */
  .bp-assist-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.06);
    overflow: hidden;
  }
  .bp-assist-inner {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
  }
  @media (max-width: 768px) {
    .bp-assist-inner { grid-template-columns: 1fr; }
  }

  .bp-assist-info {
    padding: 36px 40px;
    border-right: 1px solid #f0f0f0;
  }
  @media (max-width: 768px) {
    .bp-assist-info { border-right: none; border-bottom: 1px solid #f0f0f0; padding: 28px 24px; }
  }

  .bp-assist-title {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 20px;
    font-weight: 700;
    color: #1c1c2e;
    margin-bottom: 8px;
  }
  .bp-assist-desc {
    font-size: 13.5px;
    color: #9ca3af;
    margin-bottom: 22px;
    line-height: 1.55;
  }
  .bp-assist-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 14px;
  }
  .bp-assist-list li {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 14px;
    color: #4a5a6a;
  }
  .bp-assist-list li svg {
    flex-shrink: 0;
    width: 18px;
    height: 18px;
    color: #0f288d;
  }
  .bp-assist-list li a { color: #4a5a6a; text-decoration: none; transition: color 0.2s; }
  .bp-assist-list li a:hover { color: #0f288d; }

  .bp-assist-features {
    padding: 36px 40px;
    display: flex;
    flex-direction: column;
    gap: 24px;
    justify-content: center;
    background: #fafbfc;
  }
  @media (max-width: 768px) {
    .bp-assist-features { padding: 28px 24px; }
  }

  .bp-feature-item {
    display: flex;
    align-items: center;
    gap: 16px;
  }
  .bp-feature-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(15,40,141,0.08);
    color: #0f288d;
    flex-shrink: 0;
  }
  .bp-feature-icon svg { width: 22px; height: 22px; }
  .bp-feature-item > div:last-child {
    display: flex;
    flex-direction: column;
    gap: 2px;
  }
  .bp-feature-item strong {
    font-size: 14px;
    font-weight: 600;
    color: #1c1c2e;
    display: block;
  }
  .bp-feature-item span {
    font-size: 12.5px;
    color: #9ca3af;
    display: block;
  }

  /* =========== Flatpickr date inputs (check-in / check-out) =========== */
  .bp-field input.bp-date-alt {
    cursor: pointer;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%239ca3af' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'/%3E%3Cline x1='16' y1='2' x2='16' y2='6'/%3E%3Cline x1='8' y1='2' x2='8' y2='6'/%3E%3Cline x1='3' y1='10' x2='21' y2='10'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 16px;
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
(function () {
  var csrfToken       = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  var storeUrl        = '{{ route("booking.store") }}';
  var availabilityUrl = '{{ route("booking.check-availability") }}';

  var unifiedForm = document.getElementById('bookingUnifiedForm');
  var submitBtn   = document.getElementById('bwSubmitBtn');
  var btnText     = document.getElementById('bwBtnText');
  var btnSpinner  = document.getElementById('bwBtnSpinner');
  var errorBox    = document.getElementById('bwError');
  var successBox  = document.getElementById('bwSuccess');
  var successMsg  = document.getElementById('bwSuccessMsg');

  var checkinInput  = document.getElementById('bw-checkin');
  var checkoutInput = document.getElementById('bw-checkout');
  var cartSummary   = document.getElementById('bwCartSummary');
  var cartLines     = document.getElementById('bwCartLines');
  var cartTotal     = document.getElementById('bwCartTotal');
  var roomCart      = document.getElementById('bwRoomCart');
  var addRoomBtn    = document.getElementById('bwAddRoomBtn');

  var ROOM_TYPES = JSON.parse(document.getElementById('bwRoomTypesData').textContent || '[]');
  var rows = []; // { el, typeSelect, qtyInput, adultsSelect, childrenSelect, removeBtn, remainingLabel, priceLabel }
  var availabilityById = {};
  var currency = 'BDT';

  // ---- Currency helpers: the server already applied any active discount into
  // *_effective, so the client just picks the right pair for the chosen currency. ----
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

  // Flatpickr date pickers — dateFormat keeps the underlying input value as
  // Y-m-d (what the rest of this script and the server expect); altInput shows
  // the guest a friendlier "Jul 10, 2026" style date.
  var checkinFp = flatpickr(checkinInput, {
    dateFormat: 'Y-m-d',
    altInput: true,
    altFormat: 'M j, Y',
    altInputClass: 'bp-date-alt',
    minDate: 'today',
    disableMobile: true,
    onChange: function (selectedDates) {
      if (selectedDates[0]) {
        var nextDay = new Date(selectedDates[0]);
        nextDay.setDate(nextDay.getDate() + 1);
        checkoutFp.set('minDate', nextDay);
        if (checkoutFp.selectedDates[0] && checkoutFp.selectedDates[0] <= selectedDates[0]) {
          checkoutFp.clear();
        }
      }
      refreshAvailability();
      renderCart();
    },
  });

  var checkoutFp = flatpickr(checkoutInput, {
    dateFormat: 'Y-m-d',
    altInput: true,
    altFormat: 'M j, Y',
    altInputClass: 'bp-date-alt',
    minDate: new Date(Date.now() + 86400000),
    disableMobile: true,
    onChange: function () {
      refreshAvailability();
      renderCart();
    },
  });

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
      var soldOut   = availabilityById[t.id] && availabilityById[t.id].remaining === 0;
      var unusable  = !isTypeUsableForCurrency(t);
      if ((used.indexOf(String(t.id)) !== -1 || soldOut || unusable) && String(t.id) !== String(current)) {
        opt.disabled = true;
      }
      row.typeSelect.appendChild(opt);
    });
    row.typeSelect.value = current || '';
  }

  function syncAllTypeOptions() {
    rows.forEach(buildTypeOptions);
    var allTypesUsed = rows.length >= ROOM_TYPES.length;
    addRoomBtn.disabled = allTypesUsed;
    addRoomBtn.hidden = ROOM_TYPES.length <= 1;
  }

  function updateRemoveButtons() {
    rows.forEach(function (row) {
      row.removeBtn.style.visibility = rows.length > 1 ? 'visible' : 'hidden';
    });
  }

  function updateMeta(row) {
    var type = typeById(row.typeSelect.value);
    var priceLabel = row.el.querySelector('[data-price-label]');
    priceLabel.innerHTML = formatPriceLabelHtml(type);

    var occupancyLabel = row.el.querySelector('[data-occupancy-label]');
    if (type) {
      var parts = ['Max ' + type.max_guests + ' Guest' + (type.max_guests > 1 ? 's' : '')];
      parts.push(type.max_adults + ' Adult' + (type.max_adults > 1 ? 's' : ''));
      if (type.max_children > 0) parts.push(type.max_children + ' Child' + (type.max_children > 1 ? 'ren' : ''));
      occupancyLabel.textContent = parts[0] + ' (' + parts.slice(1).join(' + ') + ')';
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
    if (!checkinInput.value || !checkoutInput.value) return 0;
    var inMs  = new Date(checkinInput.value).getTime();
    var outMs = new Date(checkoutInput.value).getTime();
    var diff  = Math.round((outMs - inMs) / 86400000);
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

    if (!lines.length || n < 1) {
      cartSummary.style.display = 'none';
      return;
    }

    cartLines.innerHTML = lines.map(function (l) {
      return '<div class="bw-cart-line"><span>' + l.qty + '&times; ' + l.name + '</span><span>' + sym +
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
      row.remainingLabel.className = 'bw-room-remaining';
      row.qtyInput.setAttribute('max', '20');
      row.el.classList.remove('bw-sold-out');
      return;
    }

    row.qtyInput.setAttribute('max', String(Math.max(info.remaining, 1)));
    if (info.remaining < 1) {
      row.remainingLabel.textContent = 'Sold out for these dates';
      row.remainingLabel.className = 'bw-room-remaining bw-remaining-none';
      row.el.classList.add('bw-sold-out');
    } else {
      row.remainingLabel.textContent = info.remaining + ' room' + (info.remaining > 1 ? 's' : '') + ' left';
      row.remainingLabel.className = 'bw-room-remaining' + (info.remaining <= 2 ? ' bw-remaining-low' : '');
      row.el.classList.remove('bw-sold-out');
      if (parseInt(row.qtyInput.value, 10) > info.remaining) setQty(row, info.remaining);
    }
  }

  // ---- Live availability: fetch remaining count for every room type once both dates are set ----
  function refreshAvailability() {
    if (!checkinInput.value || !checkoutInput.value || checkoutInput.value <= checkinInput.value) {
      availabilityById = {};
      rows.forEach(applyAvailabilityToRow);
      syncAllTypeOptions();
      return;
    }

    fetch(availabilityUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ checkin: checkinInput.value, checkout: checkoutInput.value }),
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        availabilityById = {};
        (data.rooms || []).forEach(function (r) {
          var t = ROOM_TYPES.find(function (rt) { return rt.slug === r.slug; });
          if (t) availabilityById[t.id] = r;
        });
        rows.forEach(applyAvailabilityToRow);
        syncAllTypeOptions();
        renderCart();
      })
      .catch(function () { /* availability is a soft-fail preview — submit still re-validates server-side */ });
  }

  function rowTemplate() {
    var el = document.createElement('div');
    el.className = 'bw-room-row';
    el.innerHTML =
      '<div class="bw-room-info">' +
        '<div class="bw-control bw-control-type">' +
          '<span class="bw-control-label">Room Type</span>' +
          '<div class="bf-select-wrap"><select class="bw-type-select"></select>' +
          '<svg class="bf-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></div>' +
        '</div>' +
        '<div class="bw-room-meta">' +
          '<span class="bw-room-price" data-price-label></span>' +
          '<span class="bw-room-occupancy" data-occupancy-label></span>' +
          '<span class="bw-room-remaining" data-remaining-label></span>' +
        '</div>' +
      '</div>' +
      '<div class="bw-room-controls">' +
        '<div class="bw-control"><span class="bw-control-label">Rooms</span>' +
          '<div class="bw-qty-stepper">' +
            '<button type="button" class="bw-qty-btn" data-step="-1" aria-label="Decrease room quantity">&minus;</button>' +
            '<input type="number" class="bw-qty-input" value="1" min="1" max="20" inputmode="numeric" aria-label="Number of rooms of this type">' +
            '<button type="button" class="bw-qty-btn" data-step="1" aria-label="Increase room quantity">+</button>' +
          '</div>' +
        '</div>' +
        '<div class="bw-control"><span class="bw-control-label">Adults / room</span>' +
          '<div class="bf-select-wrap bf-select-wrap-sm"><select class="bw-adults-select"></select>' +
          '<svg class="bf-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></div>' +
        '</div>' +
        '<div class="bw-control"><span class="bw-control-label">Children / room</span>' +
          '<div class="bf-select-wrap bf-select-wrap-sm"><select class="bw-children-select"></select>' +
          '<svg class="bf-chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></div>' +
        '</div>' +
        '<button type="button" class="bw-remove-btn" aria-label="Remove this room type">' +
          '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></svg>' +
        '</button>' +
      '</div>';
    return el;
  }

  function addRow(initialTypeId) {
    var el = rowTemplate();
    roomCart.appendChild(el);

    var row = {
      el: el,
      typeSelect: el.querySelector('.bw-type-select'),
      qtyInput: el.querySelector('.bw-qty-input'),
      adultsSelect: el.querySelector('.bw-adults-select'),
      childrenSelect: el.querySelector('.bw-children-select'),
      removeBtn: el.querySelector('.bw-remove-btn'),
      remainingLabel: el.querySelector('[data-remaining-label]'),
    };
    rows.push(row);

    buildTypeOptions(row);
    row.typeSelect.value = initialTypeId || nextUnusedTypeId();
    populateOccupancySelects(row);
    updateMeta(row);

    row.typeSelect.addEventListener('change', function () {
      errorBox.style.display = 'none';
      populateOccupancySelects(row);
      updateMeta(row);
      syncAllTypeOptions();
      applyAvailabilityToRow(row);
      renderCart();
    });
    row.qtyInput.addEventListener('input', function () {
      setQty(row, parseInt(row.qtyInput.value, 10) || 1);
    });
    row.el.querySelectorAll('.bw-qty-btn').forEach(function (btn) {
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
      errorBox.style.display = 'none';
      syncAllTypeOptions();
      updateRemoveButtons();
      renderCart();
    });

    syncAllTypeOptions();
    updateRemoveButtons();
    applyAvailabilityToRow(row);
    renderCart();
  }

  addRoomBtn.addEventListener('click', function () {
    if (addRoomBtn.disabled) return;
    errorBox.style.display = 'none';
    addRow(nextUnusedTypeId());
  });

  document.querySelectorAll('.bw-currency-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var next = btn.getAttribute('data-currency');
      if (next === currency) return;
      currency = next;
      document.querySelectorAll('.bw-currency-btn').forEach(function (b) {
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

  // Start with exactly one room-type row, matching the first available type.
  if (ROOM_TYPES.length) {
    addRow(String(ROOM_TYPES[0].id));
  }

  if (!unifiedForm) return;

  unifiedForm.addEventListener('submit', function (e) {
    e.preventDefault();

    var name     = document.getElementById('bp-name')?.value.trim();
    var email    = document.getElementById('bp-email')?.value.trim();
    var phone    = document.getElementById('bp-phone')?.value.trim();
    var message  = document.getElementById('bp-message')?.value.trim();
    var checkin  = checkinInput?.value;
    var checkout = checkoutInput?.value;

    var rooms = [];
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
      rooms.push({
        room_slug: type.slug,
        quantity:  qty,
        adults:    parseInt(row.adultsSelect.value, 10) || 1,
        children:  parseInt(row.childrenSelect.value, 10) || 0,
      });
    });

    var errors = [];
    if (!name)     errors.push('Full name is required.');
    if (!email)    errors.push('Email address is required.');
    if (!phone)    errors.push('Phone number is required.');
    if (!checkin)  errors.push('Check-in date is required.');
    if (!checkout) errors.push('Check-out date is required.');
    if (checkin && checkout && checkout <= checkin) errors.push('Check-out must be after check-in.');
    if (duplicateFound) errors.push('You have selected the same room type more than once. Please remove the duplicate or choose a different type for each row.');
    if (currencyUnavailable) errors.push('One of the selected rooms isn\'t available in USD. Please switch to BDT or choose a different room.');
    if (!rooms.length) errors.push('Please select at least one room.');

    if (errors.length) {
      errorBox.innerHTML = errors.join('<br>');
      errorBox.style.display = 'block';
      errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }

    errorBox.style.display   = 'none';
    submitBtn.disabled        = true;
    btnText.style.display     = 'none';
    btnSpinner.style.display  = 'inline';

    fetch(storeUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept':        'application/json',
        'X-CSRF-TOKEN':  csrfToken,
      },
      body: JSON.stringify({
        name:             name,
        email:            email,
        phone:            phone || null,
        message:          message || null,
        checkin:          checkin,
        checkout:         checkout,
        currency:         currency,
        rooms:            rooms,
        company_website:  unifiedForm.querySelector('[name="company_website"]')?.value || '',
        form_rendered_at: unifiedForm.querySelector('[name="form_rendered_at"]')?.value || '',
      }),
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
      submitBtn.disabled       = false;
      btnText.style.display    = 'inline-flex';
      btnSpinner.style.display = 'none';

      if (data.success) {
        unifiedForm.style.display  = 'none';
        successBox.style.display   = 'block';
        if (data.message) successMsg.innerHTML = data.message;
      } else {
        errorBox.innerHTML     = data.message || 'Something went wrong. Please try again.';
        errorBox.style.display = 'block';
        errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    })
    .catch(function () {
      submitBtn.disabled       = false;
      btnText.style.display    = 'inline-flex';
      btnSpinner.style.display = 'none';
      errorBox.innerHTML       = 'Network error. Please check your connection and try again.';
      errorBox.style.display   = 'block';
    });
  });
}());
</script>
@endpush
