@extends('layouts.frontend')

@section('title', !empty($settings['hist_seo_title']) ? $settings['hist_seo_title'] : 'Our History – Hotel Beach Way')
@section('meta_description', !empty($settings['hist_seo_description']) ? $settings['hist_seo_description'] : 'Discover the history of Hotel Beach Way — Cox\'s Bazar\'s premier coastal retreat since 2013.')
@if(!empty($settings['hist_seo_keywords']))
@section('meta_keywords', $settings['hist_seo_keywords'])
@endif
@section('og_title', !empty($settings['hist_seo_title']) ? $settings['hist_seo_title'] : 'Our History – Hotel Beach Way')
@section('og_description', !empty($settings['hist_seo_description']) ? $settings['hist_seo_description'] : 'Discover the history of Hotel Beach Way — Cox\'s Bazar\'s premier coastal retreat since 2013.')
@if(!empty($settings['hist_seo_og_image']))
@section('og_image', asset('storage/' . $settings['hist_seo_og_image']))
@endif

@section('content')

@php
  $heroImage = $settings['hist_hero_image'] ?? null;
  $heroTitle = $settings['hist_hero_title'] ?? 'Our History';
  $histBadge = $settings['hist_badge']      ?? 'OUR JOURNEY';
  $histTitle = $settings['hist_title']      ?? 'A Decade of Warmth,<br>Hospitality &amp; Excellence';
  $histDesc  = $settings['hist_desc']       ?? 'From a humble dream to Cox\'s Bazar\'s most beloved coastal retreat — explore the milestones that shaped Hotel Beach Way into the award-winning destination it is today.';

  $defaultTimeline = [
    ['year'=>'2013','tag'=>'Foundation',      'heading'=>'Grand Opening — Hotel Beach Way Is Born',        'content'=>'With a vision to redefine coastal hospitality, Hotel Beach Way opened its doors on Kolatoli Road, Cox\'s Bazar in 2013. Built to blend modern comfort with the natural beauty of Bangladesh\'s longest sea beach, the hotel welcomed its first guests with 30 premium rooms, a 24-hour front desk, and a commitment to world-class service.','badges'=>['30 Rooms Launched','24/7 Front Desk','Kolatoli Beach Access'],'image'=>'','reversed'=>false],
    ['year'=>'2015','tag'=>'Dining',          'heading'=>'Dew Drop Restaurant Opens Its Doors',            'content'=>'In 2015, Hotel Beach Way unveiled the Dew Drop Restaurant — a full-service dining experience offering a rich spread of local Bangladeshi flavors and international cuisine. The restaurant quickly became a landmark on Kolatoli Road, attracting both hotel guests and city locals.','badges'=>['Dew Drop Restaurant','Buffet Breakfast','BBQ Evenings'],'image'=>'','reversed'=>true],
    ['year'=>'2017','tag'=>'Expansion',       'heading'=>'Premium Room Renovation &amp; Suite Expansion',  'content'=>'2017 marked a major transformation. All existing rooms were upgraded with premium furnishings, smart TV systems, improved air conditioning, and sea-inspired interior décor. A new wing was added, introducing the Honeymoon Suite and the Executive Suite.','badges'=>['Honeymoon Suite Added','Executive Suite','Smart Room Upgrades'],'image'=>'','reversed'=>false],
    ['year'=>'2019','tag'=>'Recreation',      'heading'=>'Swimming Pool &amp; Recreation Centre Inaugurated','content'=>'2019 saw the launch of our outdoor swimming pool and dedicated jogging track — completing Hotel Beach Way\'s transformation into a true resort-style destination.','badges'=>['Outdoor Pool','Jogging Track','Resort Experience'],'image'=>'','reversed'=>true],
    ['year'=>'2021','tag'=>'Recognition',     'heading'=>'Award-Winning Hospitality &amp; Guest Excellence','content'=>'By 2021, Hotel Beach Way had earned the trust of thousands of guests from across Bangladesh and beyond, receiving formal recognition as one of Cox\'s Bazar\'s highest-rated hospitality destinations.','badges'=>['Best Hotel Award','Top-Rated on Travel Platforms','Guest Excellence'],'image'=>'','reversed'=>false],
    ['year'=>'2023','tag'=>'Wellness &amp; Events','heading'=>'Spa, Gym &amp; Conference Hall Launched',   'content'=>'2023 brought our most ambitious expansion yet with a state-of-the-art Spa &amp; Massage Centre, a fully equipped modern Gym, and a multipurpose Conference &amp; Banquet Hall.','badges'=>['Spa &amp; Massage','Gym Centre','Conference Hall'],'image'=>'','reversed'=>true],
    ['year'=>'2025','tag'=>'A Decade &amp; Beyond','heading'=>'Celebrating Excellence — The Journey Continues','content'=>'Over a decade after opening our doors, Hotel Beach Way stands as a symbol of warmth, quality, and coastal luxury in Cox\'s Bazar. We look forward to the next chapter — growing stronger, serving better, and welcoming every guest like family.','badges'=>['10+ Years of Service','10,000+ Happy Guests','Continuously Growing'],'image'=>'','reversed'=>false],
  ];

  $displayTimeline = !empty($timeline) ? $timeline : $defaultTimeline;
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
        <a href="{{ route('about') }}">About Us</a>
        <span class="bc-sep">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
        <span class="bc-current">{{ $heroTitle }}</span>
      </nav>
    </div>
    <div class="page-hero-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,40 C360,75 720,5 1080,40 C1260,58 1380,18 1440,40 L1440,80 L0,80 Z" fill="#ffffff"/>
      </svg>
    </div>
  </section><!-- /page-hero -->


  <!-- ===========
       HISTORY TIMELINE SECTION
  =========== -->
  <section class="history-section">
    <div class="history-inner">

      <!-- Section header -->
      <div class="history-head" data-reveal="up">
        <span class="section-badge">{{ $histBadge }}</span>
        <h2 class="history-head-title">{!! $histTitle !!}</h2>
        @if($histDesc)
          <p class="history-head-desc">{{ $histDesc }}</p>
        @endif
      </div>

      <!-- Timeline -->
      <div class="ht-timeline">

        @foreach($displayTimeline as $item)
        @php
          $reversed = !empty($item['reversed']);
          $image    = $item['image'] ?? '';
          $badges   = $item['badges'] ?? [];
        @endphp
        <div class="ht-item{{ $reversed ? ' ht-reverse' : '' }}" data-reveal="up">

          @if(!$reversed)
          <div class="ht-media">
            @if($image)
              <img src="{{ asset('storage/' . $image) }}" alt="{{ $item['heading'] ?? '' }}" loading="lazy">
            @else
              <img src="{{ asset('assets/images/slider-1.jpg') }}" alt="{{ $item['heading'] ?? '' }}" loading="lazy">
            @endif
          </div>
          @endif

          <div class="ht-node">
            <div class="ht-dot"></div>
            <div class="ht-year-circle">{{ $item['year'] ?? '' }}</div>
            <div class="ht-dot"></div>
          </div>

          <div class="ht-body">
            @if(!empty($item['tag']))
              <span class="ht-tag">{{ $item['tag'] }}</span>
            @endif
            <h3>{{ $item['heading'] ?? '' }}</h3>
            <p>{{ $item['content'] ?? '' }}</p>
            @if(!empty($badges))
            <div class="ht-badge-list">
              @foreach($badges as $badge)
                @if($badge)
                  <span class="ht-badge">{{ $badge }}</span>
                @endif
              @endforeach
            </div>
            @endif
          </div>

          @if($reversed)
          <div class="ht-media">
            @if($image)
              <img src="{{ asset('storage/' . $image) }}" alt="{{ $item['heading'] ?? '' }}" loading="lazy">
            @else
              <img src="{{ asset('assets/images/slider-1.jpg') }}" alt="{{ $item['heading'] ?? '' }}" loading="lazy">
            @endif
          </div>
          @endif

        </div>
        @endforeach

      </div><!-- /ht-timeline -->
    </div><!-- /history-inner -->
  </section><!-- /history-section -->


@endsection
