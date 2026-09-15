@extends('layouts.frontend')

@section('title', !empty($settings['fac_seo_title']) ? $settings['fac_seo_title'] : 'Facilities – Hotel Beach Way')
@section('meta_description', !empty($settings['fac_seo_description']) ? $settings['fac_seo_description'] : 'Explore the facilities at Hotel Beach Way, Cox\'s Bazar — rooms, dining, leisure, and more.')
@if(!empty($settings['fac_seo_keywords']))
@section('meta_keywords', $settings['fac_seo_keywords'])
@endif
@section('og_title', !empty($settings['fac_seo_title']) ? $settings['fac_seo_title'] : 'Facilities – Hotel Beach Way')
@section('og_description', !empty($settings['fac_seo_description']) ? $settings['fac_seo_description'] : 'Explore the facilities at Hotel Beach Way, Cox\'s Bazar — rooms, dining, leisure, and more.')
@if(!empty($settings['fac_seo_og_image']))
@section('og_image', asset('storage/' . $settings['fac_seo_og_image']))
@endif

@section('content')

@php
  $facBadge = $settings['fac_badge'] ?? 'FACILITIES';
  $facTitle = $settings['fac_title'] ?? "Hotel's Facilities";

  $defaultFacilities = [
    [
      'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>',
      'title'    => 'Basic Facilities',
      'items'    => ['24 Hours Front Desk','24 Hours Room Service','Free Wi-Fi in Rooms &amp; Lobby','Basement Car Parking'],
    ],
    [
      'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
      'title'    => 'Room Amenities',
      'items'    => ['Mini Fridge with Mini Bar','Running Hot &amp; Cold Water','Cable LED TV &amp; Intercom','Air Condition &amp; Fan'],
    ],
    [
      'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64A9 9 0 115.64 19.36"/><path d="M4 12a8 8 0 0112.66-6.51"/><line x1="2" y1="12" x2="22" y2="12"/><line x1="12" y1="2" x2="12" y2="4"/><path d="M20 12c0 4.42-3.58 8-8 8s-8-3.58-8-8"/></svg>',
      'title'    => 'Dining Options',
      'items'    => ['Dew Drop Restaurant','BBQ &amp; Fresh Seafood','Complimentary Breakfast','24 Hours Room Service'],
    ],
    [
      'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M23 12a11.05 11.05 0 00-22 0zm-5 7a3 3 0 01-6 0v-7"/><line x1="12" y1="12" x2="12" y2="22"/></svg>',
      'title'    => 'Leisure Facilities',
      'items'    => ['Outdoor Swimming Pool','Fitness Center &amp; Gym','Sauna &amp; Steam Room','Snooker Table'],
    ],
    [
      'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="1"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/><line x1="7" y1="12" x2="7.01" y2="12"/><line x1="17" y1="12" x2="17.01" y2="12"/></svg>',
      'title'    => 'Family Facilities',
      'items'    => ['Connecting Rooms Available','Extra Bed on Request','Spacious Lobby Lounge','Spacious Luggage Room'],
    ],
    [
      'icon_svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>',
      'title'    => 'Additional Services',
      'items'    => ['24 Hours Concierge','Laundry Service','Airport Pick Up &amp; Drop','Safe Deposit Box'],
    ],
  ];

  $displayFacilities = $facilities->isNotEmpty() ? $facilities : collect($defaultFacilities);
@endphp


@php
  $heroImage = $settings['fac_hero_image'] ?? null;
  $heroTitle = $settings['fac_hero_title'] ?? 'Our Facilities';
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
  </section>


  <!-- ===========
       FACILITIES SECTION
  =========== -->
  <section class="fac-section">
    <div class="fac-inner">

      <div class="fac-header" data-reveal="up">
        <span class="section-badge">{{ $facBadge }}</span>
        <h2 class="fac-heading">{{ $facTitle }}</h2>
      </div>

      <div class="fac-grid">
        @foreach($displayFacilities as $idx => $fac)
          @php
            $isModel = is_object($fac) && method_exists($fac, 'getAttribute');
            $iconSvg = $isModel ? ($fac->icon_svg ?? '') : ($fac['icon_svg'] ?? '');
            $title   = $isModel ? ($fac->title ?? '')    : ($fac['title']    ?? '');
            $items   = $isModel ? ($fac->items ?? [])    : ($fac['items']    ?? []);
            $isActive = ($idx % 3 === 1);
          @endphp
          <div class="fac-card{{ $isActive ? ' fac-active' : '' }}" data-reveal="up" data-reveal-delay="{{ ($idx % 6) + 1 }}">
            @if($iconSvg)
              <div class="fac-icon-wrap">{!! $iconSvg !!}</div>
            @endif
            <h3 class="fac-card-title">{{ $title }}</h3>
            <ul class="fac-list">
              @foreach($items as $item)
                <li>{{ preg_replace('/^[\x{2022}\x{2023}\x{25E6}\x{2043}\x{2219}\s•·]+/u', '', $item) }}</li>
              @endforeach
            </ul>
          </div>
        @endforeach
      </div><!-- /fac-grid -->

    </div><!-- /fac-inner -->
  </section>

@endsection
