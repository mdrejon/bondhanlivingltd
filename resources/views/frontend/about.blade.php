@extends('layouts.frontend')

@section('title', !empty($settings['about_seo_title']) ? $settings['about_seo_title'] : 'About Us – Hotel Beach Way')
@section('meta_description', !empty($settings['about_seo_description']) ? $settings['about_seo_description'] : 'Learn about Hotel Beach Way, a boutique hotel near Kolatoli Beach, Cox\'s Bazar since 2013.')
@if(!empty($settings['about_seo_keywords']))
@section('meta_keywords', $settings['about_seo_keywords'])
@endif
@section('og_title', !empty($settings['about_seo_title']) ? $settings['about_seo_title'] : 'About Us – Hotel Beach Way')
@section('og_description', !empty($settings['about_seo_description']) ? $settings['about_seo_description'] : 'Learn about Hotel Beach Way, a boutique hotel near Kolatoli Beach, Cox\'s Bazar since 2013.')
@if(!empty($settings['about_seo_og_image']))
@section('og_image', asset('storage/' . $settings['about_seo_og_image']))
@endif

@section('content')

@php
  // Deco badges
  $decoBadgeValue = $settings['about_deco_badge_value'] ?? '24/7';
  $decoBadgeLabel = $settings['about_deco_badge_label'] ?? 'Front Desk';
  $decoYearsValue = $settings['about_deco_years_value'] ?? '13+';
  $decoYearsLabel = $settings['about_deco_years_label'] ?? 'Years of Trusted Service';

  // Left column
  $aboutBadge   = $settings['about_badge'] ?? 'OUR LUXURY RESORTS';
  $aboutTitle   = $settings['about_title'] ?? '3-Star Boutique Hotel<br>Near Kolatoli Beach';
  $aboutDesc    = $settings['about_desc']  ?? 'Hotel Beach Way is the most modern and well furnished with branded fixtures offering everything you need for a comfortable stay in Cox\'s Bazar — the world\'s longest unbroken sandy sea beach. 5 minutes walk from Kolatoli Circle &amp; 10 minutes from the Airport.';
  $aboutMainImg = $settings['about_main_image'] ?? null;
  $aboutMainAlt = $settings['about_main_image_alt'] ?? 'Hotel guests relaxing outdoors';

  // Right column
  $aboutTopImg  = $settings['about_top_image'] ?? null;
  $aboutTopAlt  = $settings['about_top_image_alt'] ?? 'Scenic view';

  // Mission tab
  $missionLabel    = $settings['about_mission_label'] ?? 'Our Mission';
  $missionText     = $settings['about_mission_text']  ?? 'We are committed to providing our guests with world-class hospitality within an affordable price. Eco-friendly infrastructure, skilled staff &amp; world-class service are our key features — making us a unique service provider in Cox\'s Bazar for families, honeymoon couples, corporate groups &amp; students.';
  $missionFeatures = !empty($settings['about_mission_features']) ? json_decode($settings['about_mission_features'], true) : [];
  $defaultMissionFeatures = ['Complimentary Breakfast &amp; Welcome Drinks','24 Hours Front Desk &amp; Room Service','Free Wi-Fi in Lobby &amp; All Rooms','Airport Pick Up &amp; Drop Service','Basement Car Parking','24 Hours CCTV Security'];
  $displayMissionFeatures = !empty($missionFeatures) ? $missionFeatures : $defaultMissionFeatures;

  // Vision tab
  $visionLabel    = $settings['about_vision_label'] ?? 'Our Vision';
  $visionText     = $settings['about_vision_text']  ?? 'Our vision is to be the most sought-after luxury hotel in Cox\'s Bazar, setting the standard for hospitality excellence, sustainability, and guest satisfaction across Bangladesh.';
  $visionFeatures = !empty($settings['about_vision_features']) ? json_decode($settings['about_vision_features'], true) : [];
  $defaultVisionFeatures = ['World-Class Amenities','Eco-Friendly Practices','Guest-First Philosophy','Sustainable Tourism','Community Engagement','Award-Winning Service'];
  $displayVisionFeatures = !empty($visionFeatures) ? $visionFeatures : $defaultVisionFeatures;

  // History tab
  $historyLabel    = $settings['about_history_label'] ?? 'Our History';
  $historyText     = $settings['about_history_text']  ?? 'Founded in 2013, Hotel Beach Way has grown into one of Cox\'s Bazar\'s premier destinations, earning recognition for authentic hospitality and an unwavering commitment to excellence.';
  $historyFeatures = !empty($settings['about_history_features']) ? json_decode($settings['about_history_features'], true) : [];
  $defaultHistoryFeatures = ['Established in 2013','13+ Years of Service','500+ Happy Guests Monthly','5 Room Categories','Restaurant &amp; Conference Hall','Trusted by Thousands'];
  $displayHistoryFeatures = !empty($historyFeatures) ? $historyFeatures : $defaultHistoryFeatures;

  // CTA button
  $moreBtnText = $settings['about_more_btn_text'] ?? 'More About';
  $moreBtnUrl  = $settings['about_more_btn_url']  ?? '#contact';

  // Stats
  $aboutStats = !empty($settings['about_stats']) ? json_decode($settings['about_stats'], true) : [];
  $defaultStats = [
    ['value' => '150', 'suffix' => '+', 'label' => 'Happy Customer'],
    ['value' => '42',  'suffix' => '+', 'label' => 'Special Rooms'],
    ['value' => '35',  'suffix' => '+', 'label' => 'Expert Teams'],
    ['value' => '10',  'suffix' => '+', 'label' => 'Awards Win'],
  ];
  $displayStats = !empty($aboutStats) ? $aboutStats : $defaultStats;

  // Video
  $videoThumb = $settings['about_video_thumb'] ?? null;
  $videoUrl   = $settings['about_video_url']   ?? '#';

  // Why Choose Us
  $whyBadge    = $settings['why_badge'] ?? 'WHY CHOOSE US';
  $whyTitle    = $settings['why_title'] ?? 'The Hotel Beach Way Difference';
  $whyDesc     = $settings['why_desc']  ?? 'We combine world-class hospitality with genuine warmth and eco-friendly values — making every stay truly memorable.';
  $whyFeatures = !empty($settings['why_features']) ? json_decode($settings['why_features'], true) : [];
  $defaultWhyFeatures = [
    [
      'icon_svg'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
      'title'       => 'Keeping You Safe',
      'description' => '24/7 CCTV security surveillance throughout the hotel premises, safe deposit boxes at the front desk, a standby generator, and fully trained staff — so you always feel secure during your stay.',
    ],
    [
      'icon_svg'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/><path d="M21.5 5.5a2 2 0 01-2 2"/></svg>',
      'title'       => 'Flexible Cancellation',
      'description' => 'Free cancellation up to 24 hours before check-in. All rates include Service Charges &amp; VAT. Check-in: 12:30 PM | Check-out: 11:30 AM. Express check-in &amp; check-out available.',
    ],
    [
      'icon_svg'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9h20M2 15h20M5 9V5a2 2 0 012-2h10a2 2 0 012 2v4M5 15v4a2 2 0 002 2h10a2 2 0 002-2v-4"/><path d="M9 12h6"/></svg>',
      'title'       => 'Full Room Amenities',
      'description' => 'Every room has A/C &amp; Fan, Cable LED TV, Mini Fridge with Mini Bar, Running Hot &amp; Cold Water, Intercom, complimentary Mineral Water &amp; Room Amenities, and hotel-wide high-speed Wi-Fi.',
    ],
  ];
  $displayWhyFeatures = !empty($whyFeatures) ? $whyFeatures : $defaultWhyFeatures;

  // FAQ section — data comes from FAQ Management (Faq model), not GlobalSettings
  $faqGroup    = collect($faqs)->first();
   
  $faqImage    = $faqGroup?->image ?? null;
  $faqBadge    = $faqGroup?->badge ?? "FAQ'S";
  $faqTitle    = $faqGroup?->title ?? 'Most Questions That Asked By People.';
  $faqDesc     = $faqGroup?->description ?? 'Find answers to the most common questions about our hotel, rooms, services, and your stay at Hotel Beach Way.';
  $allFaqItems = collect($faqs)->flatMap(fn($f) => $f->items ?? [])->values();
  $defaultFaqItems = [
    ['question' => 'What is the check-in and check-out time?',       'answer' => 'Standard Check-in time is 12:30 PM and Check-out time is 11:30 AM. Early check-in and late check-out can be arranged based on availability — please contact our 24-hour front desk in advance and we will do our best to accommodate your request.'],
    ['question' => 'Is breakfast included with the room rate?',       'answer' => 'Complimentary breakfast is included in select room packages. We offer a full buffet breakfast at our Dew Drop Restaurant from 7:00 AM to 10:30 AM. Please check your booking details or contact us for your specific package inclusions.'],
    ['question' => 'Do you offer airport pickup and drop service?',   'answer' => "Yes! We provide convenient airport pickup and drop service from Cox's Bazar Airport, which is just 10 minutes away. Simply share your flight details with our team at the time of booking and we will arrange a comfortable transfer for you."],
    ['question' => 'What is your cancellation policy?',               'answer' => 'We offer free cancellation up to 24 hours before your scheduled check-in date. Cancellations made within 24 hours of check-in may be subject to a one-night charge. For group bookings and special packages, different policies may apply — contact us for details.'],
  ];
  $displayFaqItems = $allFaqItems->isNotEmpty() ? $allFaqItems->toArray() : $defaultFaqItems;
@endphp


  <!-- ===========
       PAGE BREADCRUMB HERO
  =========== -->
  @php
    $heroImage = $settings['about_hero_image'] ?? null;
    $heroTitle = $settings['about_hero_title'] ?? 'About Us';
  @endphp
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
        <a href="{{ url('/') }}">Home</a>
        <span class="bc-sep">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </span>
        <span class="bc-current">{{ $heroTitle }}</span>
      </nav>
    </div>

    <!-- Wave shape -->
    <div class="page-hero-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,40 C360,75 720,5 1080,40 C1260,58 1380,18 1440,40 L1440,80 L0,80 Z" fill="#ffffff"/>
      </svg>
    </div>
  </section><!-- /page-hero -->

  <!-- ===========
       ABOUT SECTION
  =========== -->
  <section class="about-section" id="about">

    <!-- Floating deco badges -->
    <div class="about-deco about-deco-tl" data-reveal="fade" data-reveal-delay="2">
      <div class="deco-badge">
        <div class="deco-badge-icon">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
          </svg>
        </div>
        <div class="deco-badge-text">
          <strong>{{ $decoBadgeValue }}</strong>
          <span>{{ $decoBadgeLabel }}</span>
        </div>
      </div>
    </div>

    <div class="about-deco about-deco-br" data-reveal="fade" data-reveal-delay="3">
      <div class="deco-years">
        <strong>{!! $decoYearsValue !!}</strong>
        <span>{{ $decoYearsLabel }}</span>
      </div>
    </div>

    <div class="about-inner">

      <!-- ── Left Column ── -->
      <div class="about-left" data-reveal="left">
        <div>
          <span class="section-badge">{{ $aboutBadge }}</span>
        </div>
        <h2 class="about-title">{!! $aboutTitle !!}</h2>
        <div class="about-desc">{!! $aboutDesc !!}</div>

        <!-- Main image -->
        <div class="about-img-wrap about-img-main">
          @if($aboutMainImg)
            <img src="{{ asset('storage/' . $aboutMainImg) }}" alt="{{ $aboutMainAlt }}" loading="lazy">
          @else
            <img src="{{ asset('assets/images/about-2-1.jpg') }}" alt="{{ $aboutMainAlt }}" loading="lazy">
          @endif
        </div>
      </div>

      <!-- ── Right Column ── -->
      <div class="about-right" data-reveal="right">

        <!-- Top image -->
        <div class="about-img-wrap about-img-top">
          @if($aboutTopImg)
            <img src="{{ asset('storage/' . $aboutTopImg) }}" alt="{{ $aboutTopAlt }}" loading="lazy">
          @else
            <img src="{{ asset('assets/images/about-2-2.jpg') }}" alt="{{ $aboutTopAlt }}" loading="lazy">
          @endif
        </div>

        <!-- Tab section -->
        <div class="about-tabs-wrap">
          <div class="about-tab-nav">
            <button class="about-tab-btn active" data-tab="mission">{{ $missionLabel }}</button>
            <button class="about-tab-btn" data-tab="vision">{{ $visionLabel }}</button>
            <button class="about-tab-btn" data-tab="history">{{ $historyLabel }}</button>
          </div>

          <div class="about-tab-pane active" data-tab="mission">
            <div>{!! $missionText !!}</div>
            <ul class="about-features">
              @foreach($displayMissionFeatures as $feature)
                <li>{!! $feature !!}</li>
              @endforeach
            </ul>
          </div>

          <div class="about-tab-pane" data-tab="vision">
            <div>{!! $visionText !!}</div>
            <ul class="about-features">
              @foreach($displayVisionFeatures as $feature)
                <li>{!! $feature !!}</li>
              @endforeach
            </ul>
          </div>

          <div class="about-tab-pane" data-tab="history">
            <div>{!! $historyText !!}</div>
            <ul class="about-features">
              @foreach($displayHistoryFeatures as $feature)
                <li>{!! $feature !!}</li>
              @endforeach
            </ul>
          </div>

          <div class="about-tab-footer">
            <a href="{{ $moreBtnUrl }}" class="btn-about-more">{{ $moreBtnText }}</a>
            <div class="about-scroll-dot">
              <span></span>
            </div>
          </div>
        </div>

      </div><!-- /about-right -->
    </div><!-- /about-inner -->
  </section><!-- /about-section -->


  <!-- ===========
       STATS ROW
  =========== -->
  <section class="about-stats-section">
    <div class="about-stats-inner" data-reveal="up">
      @foreach($displayStats as $stat)
        <div class="stat-card">
          <div class="stat-number">{{ $stat['value'] ?? '' }}<sup>{{ $stat['suffix'] ?? '' }}</sup></div>
          <div class="stat-label">{{ $stat['label'] ?? '' }}</div>
        </div>
      @endforeach
    </div>
  </section><!-- /about-stats-section -->


  <!-- ===========
       VIDEO CTA
  =========== -->
  <section class="video-cta-section">
    <div class="video-cta-inner">

      <div class="video-cta-bg"@if($videoThumb) style="background-image:url('{{ asset('storage/' . $videoThumb) }}')"@endif></div>
      <div class="video-cta-overlay"></div>

      <div class="video-cta-play">
        <a href="{{ $videoUrl ?: '#' }}" class="video-play-btn" aria-label="Watch Hotel Video"@if($videoUrl && $videoUrl !== '#') target="_blank" rel="noopener"@endif>
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
            <polygon points="5 3 19 12 5 21 5 3"/>
          </svg>
        </a>
      </div>

    </div>
  </section><!-- /video-cta-section -->


  <!-- ===========
       WHY CHOOSE US
  =========== -->
  <section class="why-us-section">

    <div class="section-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,55 C240,80 480,20 720,50 C960,80 1200,15 1440,55 L1440,0 L0,0 Z" fill="#EDF3F9"/>
      </svg>
    </div>

    <div class="why-us-inner">

      <div class="why-us-header" data-reveal="up">
        <span class="section-badge">{{ $whyBadge }}</span>
        <h2 class="why-us-title">{{ $whyTitle }}</h2>
        <p class="why-us-desc">{{ $whyDesc }}</p>
      </div>

      <div class="why-us-grid">
        @foreach($displayWhyFeatures as $idx => $feat)
          <div class="why-card{{ $idx === 1 ? ' is-active' : '' }}" data-reveal="up" data-reveal-delay="{{ $idx + 1 }}">
            <div class="wc-icon">
              {!! $feat['icon_svg'] ?? '' !!}
            </div>
            <h3 class="wc-title">{{ $feat['title'] ?? '' }}</h3>
            <div class="wc-underline"></div>
            <p class="wc-desc">{!! $feat['description'] ?? '' !!}</p>
          </div>
        @endforeach
      </div><!-- /why-us-grid -->
    </div><!-- /why-us-inner -->
  </section><!-- /why-us-section -->


  <!-- ===========
       FAQ SECTION
  =========== -->
  <section class="about-faq-section">

    <div class="section-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,45 C360,80 720,5 1080,45 C1260,65 1380,20 1440,45 L1440,0 L0,0 Z" fill="#F5EDE0"/>
      </svg>
    </div>

    <div class="about-faq-inner">

      <!-- Left: image -->
      <div class="about-faq-img" data-reveal="left"> 
        @if($faqImage)
          <img src="{{ asset('storage/' . $faqImage) }}" alt="Hotel Beach Way FAQ" loading="lazy">
        @else
          <img src="{{ asset('assets/images/about-2-1.jpg') }}" alt="Hotel Beach Way FAQ" loading="lazy">
        @endif
      </div>

      <!-- Right: accordion content -->
      <div class="about-faq-content" data-reveal="right">
        <span class="section-badge">{{ $faqBadge }}</span>
        <h2 class="about-faq-title">{{ $faqTitle }}</h2>
        <p class="about-faq-desc">{{ $faqDesc }}</p>

        <div class="afaq-list">
          @foreach($displayFaqItems as $idx => $item)
            <div class="afaq-item{{ $idx === 0 ? ' active' : '' }}">
              <button class="afaq-question" aria-expanded="{{ $idx === 0 ? 'true' : 'false' }}">
                {{ $item['question'] ?? '' }}
                <span class="afaq-icon">
                  <svg class="icon-plus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                  <svg class="icon-minus" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </span>
              </button>
              <div class="afaq-answer"@if($idx === 0) style="display:block;"@endif>
                <p>{{ $item['answer'] ?? '' }}</p>
              </div>
            </div>
          @endforeach
        </div><!-- /afaq-list -->
      </div><!-- /about-faq-content -->

    </div><!-- /about-faq-inner -->
  </section><!-- /about-faq-section -->

@endsection
