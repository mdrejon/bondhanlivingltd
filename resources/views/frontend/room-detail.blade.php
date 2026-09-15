@extends('layouts.frontend')

@section('title', $room->meta_title ?: $room->name . ' – Hotel Beach Way')
@section('meta_description', $room->meta_description ?: ($room->short_desc ?? 'Book ' . $room->name . ' at Hotel Beach Way, Cox\'s Bazar.'))
@if($room->meta_keywords)
@section('meta_keywords', $room->meta_keywords)
@endif
@section('og_title', $room->meta_title ?: $room->name . ' – Hotel Beach Way')
@section('og_description', $room->meta_description ?: ($room->short_desc ?? 'Book ' . $room->name . ' at Hotel Beach Way, Cox\'s Bazar.'))
@if($room->og_image)
@section('og_image', asset('storage/' . $room->og_image))
@elseif($room->feature_image)
@section('og_image', asset('storage/' . $room->feature_image))
@endif

@section('content')

@php
  $phone    = $settings['footer_phone_1'] ?? '+88 01777-909595';
  $email    = $settings['footer_email_1'] ?? 'info@hotelbeachway.com';
  $whatsapp = $settings['contact_agent_whatsapp'] ?? 'https://wa.me/8801777909595';
  $gallery  = $room->gallery_images ?? [];
  $allImages = array_filter(array_merge(
      $room->feature_image ? [$room->feature_image] : [],
      is_array($gallery) ? $gallery : []
  ));
  $amenities = $room->amenities ?? [];
  $features  = $room->features  ?? [];
@endphp

  <!-- ===========
       PAGE BREADCRUMB HERO
  =========== -->
  <section class="page-hero">
    <div class="page-hero-bg"
      @if($room->feature_image)
        style="background-image: url('{{ asset('storage/' . $room->feature_image) }}'); background-size: cover; background-position: center;"
      @endif
    ></div>
    <div class="page-hero-overlay"></div>
    <div class="page-hero-content">
      <h1 class="page-hero-title">{{ $room->name }}</h1>
      <nav class="page-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="bc-sep">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
        <a href="{{ route('rooms') }}">Rooms &amp; Suites</a>
        <span class="bc-sep">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
        <span class="bc-current">{{ $room->name }}</span>
      </nav>
    </div>
    <div class="page-hero-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,40 C360,75 720,5 1080,40 C1260,58 1380,18 1440,40 L1440,80 L0,80 Z" fill="#ffffff"/>
      </svg>
    </div>
  </section>


  <!-- ===========
       ROOM DETAILS
  =========== -->
  <section class="room-details-section">
    <div class="rd-inner">

      <!-- =========== LEFT: Room Content =========== -->
      <div class="rd-left" data-reveal="left">

        <h1 class="rd-title">{{ $room->name }}</h1>

        <div class="rd-price-row">
          @if($room->price)
          <div class="rd-price-block">
            @if($room->discounted_price_bdt || $room->discounted_price_usd)
            <span class="room-offer-badge" style="margin-bottom:4px;">Special Offer</span>
            @endif
            @if($room->discounted_price_bdt)
            <div><s class="rd-price-original">BDT {{ number_format($room->price) }}</s>
            <span class="rd-price">BDT {{ number_format($room->discounted_price_bdt) }}<span>/{{ ltrim($room->price_unit ?? 'Night', '/') }}</span></span></div>
            @else
            <span class="rd-price">BDT {{ number_format($room->price) }}<span>/{{ ltrim($room->price_unit ?? 'Night', '/') }}</span></span>
            @endif
            @if($room->price_usd)
            <span class="room-price-usd" style="display:block;margin-top:2px;">@if($room->discounted_price_usd)<s>${{ number_format($room->price_usd, 2) }}</s> ${{ number_format($room->discounted_price_usd, 2) }}@else${{ number_format($room->price_usd, 2) }}@endif /{{ ltrim($room->price_unit ?? 'Night', '/') }}</span>
            @endif
          </div>
          @endif

          @if($room->rating)
          <div class="rd-rating">
            @php $fullStars = floor($room->rating); @endphp
            @for($s = 0; $s < 5; $s++)
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
              @if($s < $fullStars) fill="currentColor" @else fill="none" stroke="currentColor" stroke-width="1.5" @endif>
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
            </svg>
            @endfor
            ({{ number_format($room->rating, 1) }})
          </div>
          @endif
        </div>

        <!-- Main Image -->
        <div class="rd-main-img">
          <img id="rdMainImg"
            src="{{ $room->feature_image ? asset('storage/' . $room->feature_image) : asset('assets/images/room-4.jpg') }}"
            alt="{{ $room->name }}">
        </div>

        <!-- Thumbnails -->
        @if(count($allImages) > 1)
        <div class="rd-thumbs">
          @foreach($allImages as $i => $img)
          <div class="rd-thumb {{ $i === 0 ? 'active' : '' }}" data-src="{{ asset('storage/' . $img) }}">
            <img src="{{ asset('storage/' . $img) }}" alt="{{ $room->name }} – view {{ $i + 1 }}">
          </div>
          @endforeach
        </div>
        @endif

        <!-- Short Description -->
        @if($room->short_desc)
        <p class="rd-desc">{{ $room->short_desc }}</p>
        @endif

        <!-- Room Specs -->
        <div class="rd-specs">
          @if($room->bed_type)
          <div class="rd-spec-card">
            <div class="rd-spec-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 4v16"/><path d="M22 4v16"/><path d="M2 8h20"/><path d="M2 12h20"/><rect x="6" y="8" width="4" height="4"/><rect x="14" y="8" width="4" height="4"/></svg>
            </div>
            <div class="rd-spec-value">{{ $room->bed_type }}</div>
            <div class="rd-spec-label">Bed Type</div>
          </div>
          @endif

          @if($room->max_adults)
          <div class="rd-spec-card">
            <div class="rd-spec-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div class="rd-spec-value">
              {{ $room->max_adults }} Adult{{ $room->max_adults > 1 ? 's' : '' }}
              @if($room->max_children) + {{ $room->max_children }} Child{{ $room->max_children > 1 ? 'ren' : '' }}@endif
            </div>
            <div class="rd-spec-label">Max Guests</div>
          </div>
          @endif

          @if($room->check_in_time)
          <div class="rd-spec-card">
            <div class="rd-spec-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <div class="rd-spec-value">{{ $room->check_in_time }}</div>
            <div class="rd-spec-label">Check-In</div>
          </div>
          @endif

          @if($room->check_out_time)
          <div class="rd-spec-card">
            <div class="rd-spec-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 8 14"/></svg>
            </div>
            <div class="rd-spec-value">{{ $room->check_out_time }}</div>
            <div class="rd-spec-label">Check-Out</div>
          </div>
          @endif
        </div>

        <!-- Full Description -->
        @if($room->description)
        <div class="rd-desc">{!! $room->description !!}</div>
        @endif

        <!-- Features / What's Included -->
        @if(!empty($features))
        <h3 class="rd-section-title">What's Included</h3>
        <div class="rd-include-grid">
          <ul class="rd-include-col">
            @foreach($features as $feat)
            <li>{{ $feat }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <!-- Amenities -->
        @if(!empty($amenities))
        <h3 class="rd-section-title">Amenities</h3>
        <div class="rd-amenities-grid">
          @foreach($amenities as $amenity)
          <div class="rd-amenity-item">
            <span class="rd-am-icon">
              @if(!empty($amenity['icon_svg']))
                {!! $amenity['icon_svg'] !!}
              @else
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              @endif
            </span>
            {{ $amenity['label'] ?? '' }}
          </div>
          @endforeach
        </div>
        @endif

        <!-- Room Rules -->
        <h3 class="rd-section-title">Room Rules</h3>
        <ul class="rd-rules-list">
          @if($room->check_in_time)
          <li>Check-in time: {{ $room->check_in_time }}</li>
          @endif
          @if($room->check_out_time)
          <li>Check-out time: {{ $room->check_out_time }}</li>
          @endif
          @if(!empty($room->room_rules) && count($room->room_rules) > 0)
            @foreach($room->room_rules as $rule)
              @if(trim($rule))
              <li>{{ $rule }}</li>
              @endif
            @endforeach
          @else
          <li>Early check-in / late check-out: Subject to availability — additional charges may apply</li>
          <li>All rooms are strictly non-smoking. A deep cleaning fee will apply if smoking occurs inside</li>
          <li>Pets are not allowed inside the hotel premises</li>
          {{-- <li>Quiet hours observed from 11:00 PM – 7:00 AM for the comfort of all guests</li> --}}
          @endif
        </ul>

      </div><!-- /rd-left -->


      <!-- =========== RIGHT: Sticky Sidebar =========== -->
      <div class="rd-right">

        <!-- Book Your Room -->
        <div class="rd-sidebar-card" data-reveal="right">
          <h3 class="rd-card-title">Book Your Room</h3>
          <p class="rd-card-desc">Select your dates and our team will confirm your reservation within 2 hours.</p>
          <form class="rd-book-form" id="rdBookForm" novalidate>

            <div class="rd-field">
              <label for="rdCheckIn">Check In</label>
              <input type="text" id="rdCheckIn" placeholder="Select date" readonly>
            </div>

            <div class="rd-field">
              <label for="rdCheckOut">Check Out</label>
              <input type="text" id="rdCheckOut" placeholder="Select date" readonly>
            </div>

            <div class="rd-field">
              <label for="rdAdults">Adults</label>
              <select id="rdAdults">
                @for($a = 1; $a <= max(4, $room->max_adults ?? 4); $a++)
                <option value="{{ $a }}" {{ $a === 2 ? 'selected' : '' }}>{{ str_pad($a, 2, '0', STR_PAD_LEFT) }}</option>
                @endfor
              </select>
            </div>

            <div class="rd-field">
              <label for="rdChildren">Children</label>
              <select id="rdChildren">
                @for($c = 0; $c <= max(3, $room->max_children ?? 3); $c++)
                <option value="{{ $c }}" {{ $c === 0 ? 'selected' : '' }}>{{ str_pad($c, 2, '0', STR_PAD_LEFT) }}</option>
                @endfor
              </select>
            </div>

            <div id="rdAvailError" style="display:none;background:#fff0f0;border:1px solid #f5b8b8;color:#c0392b;padding:10px 14px;border-radius:6px;font-size:13px;margin-top:8px;"></div>

            <button type="submit" class="btn-book-now" id="rdBookBtn" style="margin-top:1rem;">Check Availability</button>
          </form>
        </div>

        <!-- Reservation Support -->
        <div class="rd-sidebar-card" data-reveal="right" data-reveal-delay="1">
          <h3 class="rd-card-title">Reservation Support</h3>
          <p class="rd-card-desc">Our front desk team is available 24/7 to assist with your reservation and any queries.</p>

          <div class="rd-support-list">
            <div class="rd-support-item">
              <div class="rd-si-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
              </div>
              <div>
                <div class="rd-si-label">Phone Number</div>
                <div class="rd-si-value">{{ $phone }}</div>
              </div>
            </div>
            <div class="rd-support-item">
              <div class="rd-si-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              </div>
              <div>
                <div class="rd-si-label">Email Address</div>
                <div class="rd-si-value">{{ $email }}</div>
              </div>
            </div>
            @if($whatsapp && $whatsapp !== '#')
            <div class="rd-support-item">
              <div class="rd-si-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
              </div>
              <div>
                <div class="rd-si-label">WhatsApp</div>
                <div class="rd-si-value"><a href="{{ $whatsapp }}" target="_blank" rel="noopener">Chat with Us</a></div>
              </div>
            </div>
            @endif
          </div>
        </div>

      </div><!-- /rd-right -->

    </div><!-- /rd-inner -->
  </section><!-- /room-details-section -->


  <!-- ===========
       RELATED ROOMS
  =========== -->
  @if($related->isNotEmpty())
  <section class="rr-section">
    <div class="rr-header" data-reveal="up">
      <h2 class="rr-title">Other Rooms You May Like</h2>
    </div>

    <div class="rr-carousel-wrap">
      <!-- Prev -->
      <button class="rr-arrow rr-prev" aria-label="Previous">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      </button>

      <!-- Track -->
      <div class="rr-viewport">
        <div class="rr-track">
          @foreach($related as $rel)
          <div class="rr-slide">
            <div class="room-card room-card--link" onclick="location.href='{{ route('rooms.detail', $rel->slug) }}'" style="cursor:pointer;">
              <div class="room-card-img">
                @if($rel->feature_image)
                <img src="{{ asset('storage/' . $rel->feature_image) }}" alt="{{ $rel->name }}" loading="lazy">
                @else
                <img src="{{ asset('assets/images/room-4.jpg') }}" alt="{{ $rel->name }}" loading="lazy">
                @endif
                @if($rel->rating)
                <div class="room-rating">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                  {{ number_format($rel->rating, 1) }}
                </div>
                @endif
              </div>
              <div class="room-card-body">
                <h3 class="room-card-title">{{ $rel->name }}</h3>
                @if($rel->short_desc)
                <p class="room-card-desc">{{ Str::limit($rel->short_desc, 90) }}</p>
                @endif
                <div class="room-card-footer">
                  @if($rel->price)
                  <div class="room-price-wrap">
                    @if($rel->discounted_price_bdt || $rel->discounted_price_usd)
                    <div class="room-offer-badge">Special Offer</div>
                    @endif
                    @if($rel->discounted_price_bdt)
                    <s class="room-price-original">BDT {{ number_format($rel->price) }}</s>
                    <div class="room-price">BDT {{ number_format($rel->discounted_price_bdt) }}<span>/{{ $rel->price_unit ?? 'Night' }}</span></div>
                    @else
                    <div class="room-price">BDT {{ number_format($rel->price) }}<span>/{{ $rel->price_unit ?? 'Night' }}</span></div>
                    @endif
                    @if($rel->price_usd)
                    <div class="room-price-usd">
                      @if($rel->discounted_price_usd)<s>${{ number_format($rel->price_usd, 2) }}</s> ${{ number_format($rel->discounted_price_usd, 2) }}@else${{ number_format($rel->price_usd, 2) }}@endif
                      <span>/{{ $rel->price_unit ?? 'Night' }}</span>
                    </div>
                    @endif
                  </div>
                  @endif
                  <a href="{{ route('rooms.detail', $rel->slug) }}" class="btn-room-details" onclick="event.stopPropagation()">View Details</a>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>

      <!-- Next -->
      <button class="rr-arrow rr-next" aria-label="Next">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
      </button>
    </div>

    <!-- Dots -->
    <div class="rr-dots" aria-hidden="true"></div>
  </section>
  @endif


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

      <div class="bk-modal-header">
        <button class="bk-close" id="bkClose" aria-label="Close booking popup">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <span class="bk-modal-badge">HOTEL BEACH WAY</span>
        <h2 class="bk-modal-title">Complete Your Booking <span></span></h2>

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

      <div class="bk-success-state" id="bkSuccess">
        <div class="bk-success-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <h3 class="bk-success-title" id="bkSuccessTitle">Booking Request Sent!</h3>
        <p class="bk-success-desc" id="bkSuccessDesc">Thank you for choosing Hotel Beach Way. Our team will review your request and contact you within 24 hours to confirm your reservation.</p>
        <button class="bk-success-close" id="bkSuccessClose">Close</button>
      </div>

    </div>
  </div>

@endsection

@push('styles')
<style>
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

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
/* ── Thumbnail gallery switcher ── */
document.querySelectorAll('.rd-thumb').forEach(function(thumb) {
  thumb.addEventListener('click', function() {
    var src = thumb.dataset.src;
    if (!src) return;
    document.getElementById('rdMainImg').src = src;
    document.querySelectorAll('.rd-thumb').forEach(function(t) { t.classList.remove('active'); });
    thumb.classList.add('active');
  });
});

/* ── Related Rooms Carousel ── */
(function() {
  var viewport  = document.querySelector('.rr-viewport');
  var track     = document.querySelector('.rr-track');
  var prevBtn   = document.querySelector('.rr-prev');
  var nextBtn   = document.querySelector('.rr-next');
  var dotsWrap  = document.querySelector('.rr-dots');

  if (!track) return;

  var slides     = Array.from(track.querySelectorAll('.rr-slide'));
  var total      = slides.length;
  var current    = 0;
  var autoTimer  = null;

  function getVisible() {
    var w = window.innerWidth;
    if (w >= 1024) return 3;
    if (w >= 640)  return 2;
    return 1;
  }

  function maxIndex() {
    return Math.max(0, total - getVisible());
  }

  function goTo(idx) {
    current = Math.max(0, Math.min(idx, maxIndex()));
    var slideW = slides[0].getBoundingClientRect().width;
    var gap = parseFloat(getComputedStyle(track).gap) || 24;
    track.style.transform = 'translateX(-' + (current * (slideW + gap)) + 'px)';
    updateDots();
    updateArrows();
  }

  function buildDots() {
    dotsWrap.innerHTML = '';
    var pages = maxIndex() + 1;
    for (var i = 0; i < pages; i++) {
      var d = document.createElement('button');
      d.className = 'rr-dot' + (i === 0 ? ' active' : '');
      d.setAttribute('aria-label', 'Go to slide ' + (i + 1));
      d.dataset.idx = i;
      d.addEventListener('click', function() { goTo(+this.dataset.idx); resetAuto(); });
      dotsWrap.appendChild(d);
    }
  }

  function updateDots() {
    Array.from(dotsWrap.querySelectorAll('.rr-dot')).forEach(function(d, i) {
      d.classList.toggle('active', i === current);
    });
  }

  function updateArrows() {
    prevBtn.disabled = current === 0;
    nextBtn.disabled = current >= maxIndex();
  }

  function resetAuto() {
    clearInterval(autoTimer);
    autoTimer = setInterval(function() {
      goTo(current >= maxIndex() ? 0 : current + 1);
    }, 4000);
  }

  prevBtn.addEventListener('click', function() { goTo(current - 1); resetAuto(); });
  nextBtn.addEventListener('click', function() { goTo(current + 1); resetAuto(); });

  /* Touch/swipe */
  var touchStartX = 0;
  viewport.addEventListener('touchstart', function(e) { touchStartX = e.touches[0].clientX; }, { passive: true });
  viewport.addEventListener('touchend', function(e) {
    var diff = touchStartX - e.changedTouches[0].clientX;
    if (Math.abs(diff) > 40) { goTo(diff > 0 ? current + 1 : current - 1); resetAuto(); }
  }, { passive: true });

  window.addEventListener('resize', function() { buildDots(); goTo(current); });

  buildDots();
  goTo(0);
  resetAuto();
})();

/* ── Room Detail Booking Flow ── */
document.addEventListener('DOMContentLoaded', function () {
  var ROOM_SLUG     = '{{ $room->slug }}';
  var checkAvailUrl = '{{ route("booking.check-availability") }}';
  var bookingUrl    = '{{ route("booking.store") }}';
  var csrfToken     = document.querySelector('meta[name="csrf-token"]')
                      ? document.querySelector('meta[name="csrf-token"]').content : '';

  /* Flatpickr on sidebar inputs */
  var rdCheckinFp  = flatpickr('#rdCheckIn',  { minDate: 'today', dateFormat: 'Y-m-d' });
  var rdCheckoutFp = flatpickr('#rdCheckOut', { minDate: 'today', dateFormat: 'Y-m-d' });

  /* Popup elements */
  var overlay      = document.getElementById('bkOverlay');
  var closeBtn     = document.getElementById('bkClose');
  var formBody     = document.getElementById('bkFormBody');
  var bkForm       = document.getElementById('bkForm');
  var bkSubmit     = document.getElementById('bkSubmitBtn');
  var bkSuccess    = document.getElementById('bkSuccess');
  var successClose = document.getElementById('bkSuccessClose');
  var sumCheckin   = document.getElementById('bkSumCheckin');
  var sumCheckout  = document.getElementById('bkSumCheckout');
  var bkFormError  = document.getElementById('bkFormError');
  var roomCart     = document.getElementById('bkRoomCart');
  var addRoomBtn   = document.getElementById('bkAddRoomBtn');
  var cartSummary  = document.getElementById('bkCartSummary');
  var cartLines    = document.getElementById('bkCartLines');
  var cartTotal    = document.getElementById('bkCartTotal');

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

  /* Flatpickr on popup summary inputs — refreshes remaining-room counts on change */
  window.bkCheckinFp = flatpickr('#bkSumCheckin', {
    minDate: 'today',
    dateFormat: 'Y-m-d',
    onChange: function (selectedDates) {
      if (selectedDates[0]) {
        var nextDay = new Date(selectedDates[0]);
        nextDay.setDate(nextDay.getDate() + 1);
        window.bkCheckoutFp.set('minDate', nextDay);
        if (window.bkCheckoutFp.selectedDates[0] && window.bkCheckoutFp.selectedDates[0] <= selectedDates[0]) {
          window.bkCheckoutFp.clear();
        }
      }
      refreshAvailability();
    },
  });
  window.bkCheckoutFp = flatpickr('#bkSumCheckout', {
    minDate: 'today',
    dateFormat: 'Y-m-d',
    onChange: function () { refreshAvailability(); },
  });

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
      opt.textContent = t.name + (priceStr ? ' – ' + priceStr : '');
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

  function openPopup(data, prefetchedAvailability) {
    data = data || {};
    if (window.bkCheckinFp)  window.bkCheckinFp.setDate(data.checkin || null, false);
    else if (sumCheckin)     sumCheckin.value = data.checkin || '';
    if (window.bkCheckoutFp) window.bkCheckoutFp.setDate(data.checkout || null, false);
    else if (sumCheckout)    sumCheckout.value = data.checkout || '';

    if (bkForm) bkForm.reset();
    resetRoomCart(data.room || '', data.adults || 2, data.children || 0);
    if (prefetchedAvailability) { applyAvailabilityData(prefetchedAvailability); }
    else { refreshAvailability(); }

    if (formBody)  formBody.style.display = '';
    if (bkSuccess) bkSuccess.classList.remove('is-visible');
    overlay.classList.add('is-open');
    overlay.style.display = 'flex';
    overlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closePopup() {
    overlay.classList.remove('is-open');
    overlay.style.display = '';
    overlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  /* Sidebar form submit → check availability */
  var rdForm    = document.getElementById('rdBookForm');
  var rdBtn     = document.getElementById('rdBookBtn');
  var rdError   = document.getElementById('rdAvailError');

  if (rdForm && rdBtn) {
    rdForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var checkin  = document.getElementById('rdCheckIn').value;
      var checkout = document.getElementById('rdCheckOut').value;
      if (!checkin || !checkout) {
        rdError.textContent = 'Please select both check-in and check-out dates.';
        rdError.style.display = 'block';
        return;
      }
      rdError.style.display = 'none';
      rdBtn.disabled = true;
      rdBtn.textContent = 'Checking…';

      fetch(checkAvailUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ checkin: checkin, checkout: checkout }),
      })
      .then(function(res) { return res.json().then(function(d) { return { ok: res.ok, data: d }; }); })
      .then(function(r) {
        rdBtn.disabled = false;
        rdBtn.textContent = 'Check Availability';
        if (r.data.available) {
          openPopup({
            checkin:  checkin,
            checkout: checkout,
            room:     ROOM_SLUG,
            adults:   document.getElementById('rdAdults').value,
            children: document.getElementById('rdChildren').value,
          }, r.data);
        } else {
          rdError.textContent = r.data.message || 'No rooms available for the selected dates. Please try different dates.';
          rdError.style.display = 'block';
        }
      })
      .catch(function() {
        rdBtn.disabled = false;
        rdBtn.textContent = 'Check Availability';
        rdError.textContent = 'Network error. Please check your connection and try again.';
        rdError.style.display = 'block';
      });
    });
  }

  /* Close handlers */
  if (closeBtn)     closeBtn.addEventListener('click', closePopup);
  if (successClose) successClose.addEventListener('click', closePopup);
  overlay.addEventListener('click', function(e) { if (e.target === overlay) closePopup(); });
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && overlay.classList.contains('is-open')) closePopup();
  });

  /* Popup form submission */
  var submitIcon  = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/></svg> ';
  var loadingIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> ';

  if (bkForm) {
    bkForm.addEventListener('submit', function(e) {
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

      fetch(bookingUrl, {
        method:  'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({
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
        }),
      })
      .then(function(res) { return res.json().then(function(d) { return { ok: res.ok, data: d }; }); })
      .then(function(r) {
        bkSubmit.disabled = false;
        bkSubmit.innerHTML = submitIcon + 'Submit Booking';
        if (r.ok && r.data.success) {
          if (formBody)  formBody.style.display = 'none';
          if (bkSuccess) bkSuccess.classList.add('is-visible');
        } else {
          var msg = (r.data.errors ? Object.values(r.data.errors).flat().join(' ') : null)
                    || r.data.message || 'Something went wrong. Please try again.';
          if (bkFormError) { bkFormError.textContent = msg; bkFormError.style.display = 'block'; }
        }
      })
      .catch(function() {
        bkSubmit.disabled = false;
        bkSubmit.innerHTML = submitIcon + 'Submit Booking';
        if (bkFormError) { bkFormError.textContent = 'Network error. Please check your connection and try again.'; bkFormError.style.display = 'block'; }
      });
    });
  }
});
</script>

@push('styles')
<style>
/* ── Related Rooms Carousel ── */
.rr-section {
  padding: 3.5rem 1.5rem 4rem;
  background: #F8F3EB;
}
.rr-header {
  text-align: center;
  margin-bottom: 2rem;
}
.rr-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(1.4rem, 2.5vw, 2rem);
  color: #1a2340;
  position: relative;
  display: inline-block;
  padding-bottom: .6rem;
}
.rr-title::after {
  content: '';
  position: absolute;
  left: 50%;
  bottom: 0;
  transform: translateX(-50%);
  width: 48px;
  height: 3px;
  background: #1a2340;
  border-radius: 2px;
}
.rr-carousel-wrap {
  display: flex;
  align-items: center;
  gap: 1rem;
  max-width: 1200px;
  margin: 0 auto;
}
.rr-viewport {
  overflow: hidden;
  flex: 1;
  min-width: 0;
}
.rr-track {
  display: flex;
  gap: 1.5rem;
  transition: transform .45s cubic-bezier(.4,0,.2,1);
  will-change: transform;
}
.rr-slide {
  flex: 0 0 calc((100% - 3rem) / 3);
  min-width: 0;
}
@media (max-width: 1023px) {
  .rr-slide { flex: 0 0 calc((100% - 1.5rem) / 2); }
}
@media (max-width: 639px) {
  .rr-slide { flex: 0 0 100%; }
}
.rr-slide .room-card { margin: 0; width: 100%; }

/* Arrows */
.rr-arrow {
  flex-shrink: 0;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  border: 2px solid #1a2340;
  background: #fff;
  color: #1a2340;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background .2s, color .2s;
}
.rr-arrow:hover:not(:disabled) { background: #1a2340; color: #fff; }
.rr-arrow:disabled { opacity: .3; cursor: default; }
.rr-arrow svg { width: 18px; height: 18px; }

/* Dots */
.rr-dots {
  display: flex;
  justify-content: center;
  gap: .5rem;
  margin-top: 1.75rem;
}
.rr-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  border: none;
  background: #c5c5c5;
  cursor: pointer;
  transition: background .2s, transform .2s;
  padding: 0;
}
.rr-dot.active {
  background: #1a2340;
  transform: scale(1.25);
}
</style>
@endpush
@endpush
