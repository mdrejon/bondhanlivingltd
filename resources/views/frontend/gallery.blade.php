@extends('layouts.frontend')

@section('title', !empty($settings['gallery_seo_title']) ? $settings['gallery_seo_title'] : 'Gallery – Hotel Beach Way')
@section('meta_description', !empty($settings['gallery_seo_description']) ? $settings['gallery_seo_description'] : 'Browse our photo gallery of Hotel Beach Way, Cox\'s Bazar — rooms, facilities, and the beautiful Kolatoli Beach.')
@if(!empty($settings['gallery_seo_keywords']))
@section('meta_keywords', $settings['gallery_seo_keywords'])
@endif
@section('og_title', !empty($settings['gallery_seo_title']) ? $settings['gallery_seo_title'] : 'Gallery – Hotel Beach Way')
@section('og_description', !empty($settings['gallery_seo_description']) ? $settings['gallery_seo_description'] : 'Browse our photo gallery of Hotel Beach Way, Cox\'s Bazar — rooms, facilities, and the beautiful Kolatoli Beach.')
@if(!empty($settings['gallery_seo_og_image']))
@section('og_image', asset('storage/' . $settings['gallery_seo_og_image']))
@endif

@section('content')

@php
  $heroImage = $settings['gallery_hero_image'] ?? null;
  $heroTitle = $settings['gallery_hero_title'] ?? 'Gallery';

  $galBadge    = $settings['gallery_badge']    ?? 'OUR GALLERY';
  $galTitle    = $settings['gallery_title']    ?? 'Hotel Gallery';
  $galSubtitle = $settings['gallery_subtitle'] ?? 'Explore the beauty and comfort of Hotel Beach Way — from our elegantly furnished rooms to stunning beachside views and world-class facilities.';

  $topImages    = $images->take(5);
  $bottomImages = $images->slice(5);

  $expandIcon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h6v6"/><path d="M9 21H3v-6"/><path d="M21 3l-7 7"/><path d="M3 21l7-7"/></svg>';
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
        <path d="M0,40 C360,75 720,5 1080,40 C1260,58 1380,18 1440,40 L1440,80 L0,80 Z" fill="#F5EDE0"/>
      </svg>
    </div>
  </section><!-- /page-hero -->


  <!-- ===========
       GALLERY SECTION
  =========== -->
  <section class="hotel-gallery-section">

    <div class="hg-inner">

      <!-- Header -->
      <div class="hg-header" data-reveal="up">
        <span class="section-badge">{{ $galBadge }}</span>
        <h2 class="hg-title">{{ $galTitle }}</h2>
        @if($galSubtitle)<p class="hg-subtitle">{{ $galSubtitle }}</p>@endif
      </div>

      @if($images->isNotEmpty())

        <!-- Top grid: 1 tall left (featured) + 2×2 right -->
        <div class="hg-grid-top" data-reveal="scale" data-reveal-delay="1">
          @foreach($topImages as $idx => $image)
            <div class="hg-item{{ $idx === 0 ? ' hg-featured' : '' }}" data-index="{{ $idx }}">
              <img src="{{ asset('storage/' . $image->image) }}"
                   alt="{{ $image->alt ?: 'Hotel Beach Way Gallery' }}" loading="lazy">
              <div class="hg-overlay">{!! $expandIcon !!}</div>
            </div>
          @endforeach
        </div>

        @if($bottomImages->isNotEmpty())
          <!-- Bottom grid: equal columns -->
          <div class="hg-grid-bottom" data-reveal="up" data-reveal-delay="2">
            @foreach($bottomImages as $idx => $image)
              <div class="hg-item" data-index="{{ $idx + 5 }}">
                <img src="{{ asset('storage/' . $image->image) }}"
                     alt="{{ $image->alt ?: 'Hotel Beach Way Gallery' }}" loading="lazy">
                <div class="hg-overlay">{!! $expandIcon !!}</div>
              </div>
            @endforeach
          </div>
        @endif

      @else

        <!-- Static fallback when no images uploaded yet -->
        <div class="hg-grid-top" data-reveal="scale" data-reveal-delay="1">
          @foreach([
            ['src' => 'gallery-1.jpg', 'alt' => 'Deluxe Room Interior',    'featured' => true],
            ['src' => 'gallery-2.jpg', 'alt' => 'Premium King Room',        'featured' => false],
            ['src' => 'gallery-3.jpg', 'alt' => 'Elegant Suite',            'featured' => false],
            ['src' => 'gallery-4.jpg', 'alt' => 'Honeymoon Room',           'featured' => false],
            ['src' => 'gallery-5.jpg', 'alt' => 'Beachside Activities',     'featured' => false],
          ] as $idx => $item)
            <div class="hg-item{{ $item['featured'] ? ' hg-featured' : '' }}" data-index="{{ $idx }}">
              <img src="{{ asset('assets/images/' . $item['src']) }}" alt="{{ $item['alt'] }}" loading="lazy">
              <div class="hg-overlay">{!! $expandIcon !!}</div>
            </div>
          @endforeach
        </div>

        <div class="hg-grid-bottom" data-reveal="up" data-reveal-delay="2">
          @foreach([
            ['src' => 'gallery-6.jpg', 'alt' => 'Hotel Gym'],
            ['src' => 'gallery-7.jpg', 'alt' => 'Dew Drop Restaurant'],
            ['src' => 'gallery-8.jpg', 'alt' => 'Swimming Pool'],
          ] as $idx => $item)
            <div class="hg-item" data-index="{{ $idx + 5 }}">
              <img src="{{ asset('assets/images/' . $item['src']) }}" alt="{{ $item['alt'] }}" loading="lazy">
              <div class="hg-overlay">{!! $expandIcon !!}</div>
            </div>
          @endforeach
        </div>

      @endif

    </div><!-- /hg-inner -->
  </section><!-- /hotel-gallery-section -->

@endsection
