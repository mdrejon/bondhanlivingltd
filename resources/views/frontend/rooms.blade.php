@extends('layouts.frontend')

@section('title', !empty($settings['rooms_seo_title']) ? $settings['rooms_seo_title'] : 'Rooms & Suites – Hotel Beach Way')
@section('meta_description', !empty($settings['rooms_seo_description']) ? $settings['rooms_seo_description'] : 'Explore our elegantly furnished rooms and suites at Hotel Beach Way, Cox\'s Bazar.')
@if(!empty($settings['rooms_seo_keywords']))
@section('meta_keywords', $settings['rooms_seo_keywords'])
@endif
@section('og_title', !empty($settings['rooms_seo_title']) ? $settings['rooms_seo_title'] : 'Rooms & Suites – Hotel Beach Way')
@section('og_description', !empty($settings['rooms_seo_description']) ? $settings['rooms_seo_description'] : 'Explore our elegantly furnished rooms and suites at Hotel Beach Way, Cox\'s Bazar.')
@if(!empty($settings['rooms_seo_og_image']))
@section('og_image', asset('storage/' . $settings['rooms_seo_og_image']))
@endif

@section('content')

@php
  $heroImage = $settings['rooms_hero_image'] ?? null;
  $heroTitle = $settings['rooms_hero_title'] ?? 'Rooms & Suites';
  $roomsBadge = $settings['rooms_badge'] ?? 'OUR ROOMS & SUITES';
  $roomsTitle = $settings['rooms_title'] ?? 'Book Your Stay And Relax In<br>Luxury Resort';
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
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </span>
        <span class="bc-current">{{ $heroTitle }}</span>
      </nav>
    </div>

    <div class="page-hero-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,40 C360,75 720,5 1080,40 C1260,58 1380,18 1440,40 L1440,80 L0,80 Z" fill="#F8F3EB"/>
      </svg>
    </div>
  </section><!-- /page-hero -->


  <!-- ===========
       ROOMS & SUITES SECTION
  =========== -->
  <section class="rooms-section" id="rooms">

    <div class="rooms-header" data-reveal="up">
      <span class="section-badge">{{ $roomsBadge }}</span>
      <h2 class="rooms-title">{!! $roomsTitle !!}</h2>
    </div>

    @if($rooms->isNotEmpty())
    <div class="rooms-grid">
      @foreach($rooms as $index => $room)
      <div class="room-card room-card--link" data-reveal="up" data-reveal-delay="{{ ($index % 3) + 1 }}" onclick="location.href='{{ route('rooms.detail', $room->slug) }}'" style="cursor:pointer;">
        <div class="room-card-img">
          @if($room->feature_image)
            <img src="{{ asset('storage/' . $room->feature_image) }}" alt="{{ $room->name }}" loading="lazy">
          @else
            <img src="{{ asset('assets/images/room-4.jpg') }}" alt="{{ $room->name }}" loading="lazy">
          @endif

          @if($room->rating)
          <div class="room-rating">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            {{ number_format($room->rating, 1) }}
          </div>
          @endif

          @if($room->is_featured)
          <div class="room-featured-badge">Featured</div>
          @endif

          @if(!empty($room->amenities))
          <div class="room-amenities">
            @foreach(array_slice($room->amenities, 0, 5) as $amenity)
            <span class="ram-icon" title="{{ $amenity['label'] ?? '' }}">
              @if(!empty($amenity['icon_svg']))
                {!! $amenity['icon_svg'] !!}
              @else
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              @endif
            </span>
            @endforeach
          </div>
          @endif
        </div>

        <div class="room-card-body">
          <h3 class="room-card-title">{{ $room->name }}</h3>
          @if($room->short_desc)
          <p class="room-card-desc">{{ Str::limit($room->short_desc, 100) }}</p>
          @endif
          <div class="room-card-footer">
            @if($room->price)
            <div class="room-price-wrap">
              @if($room->discounted_price_bdt || $room->discounted_price_usd)
              <div class="room-offer-badge">Special Offer</div>
              @endif
              @if($room->discounted_price_bdt)
              <s class="room-price-original">BDT {{ number_format($room->price) }}</s>
              <div class="room-price">BDT {{ number_format($room->discounted_price_bdt) }}<span>/{{ ltrim($room->price_unit ?? 'Night', '/') }}</span></div>
              @else
              <div class="room-price">BDT {{ number_format($room->price) }}<span>/{{ ltrim($room->price_unit ?? 'Night', '/') }}</span></div>
              @endif
              @if($room->price_usd)
              <div class="room-price-usd">
                @if($room->discounted_price_usd)<s>${{ number_format($room->price_usd, 2) }}</s> ${{ number_format($room->discounted_price_usd, 2) }}@else${{ number_format($room->price_usd, 2) }}@endif
                <span>/{{ ltrim($room->price_unit ?? 'Night', '/') }}</span>
              </div>
              @endif
            </div>
            @endif
            <a href="{{ route('rooms.detail', $room->slug) }}" class="btn-room-details" onclick="event.stopPropagation()">View Details</a>
          </div>
        </div>
      </div>
      @endforeach
    </div><!-- /rooms-grid -->

    @else
    <p class="text-center text-gray-500 py-16">No rooms available at the moment. Please check back soon.</p>
    @endif

  </section><!-- /rooms-section -->


@endsection
