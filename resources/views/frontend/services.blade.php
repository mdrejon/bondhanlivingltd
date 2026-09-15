@extends('layouts.frontend')

@section('title', !empty($settings['svc_seo_title']) ? $settings['svc_seo_title'] : 'Services & Facilities – Hotel Beach Way')
@section('meta_description', !empty($settings['svc_seo_description']) ? $settings['svc_seo_description'] : 'Discover the world-class services and facilities at Hotel Beach Way, Cox\'s Bazar.')
@if(!empty($settings['svc_seo_keywords']))
@section('meta_keywords', $settings['svc_seo_keywords'])
@endif
@section('og_title', !empty($settings['svc_seo_title']) ? $settings['svc_seo_title'] : 'Services & Facilities – Hotel Beach Way')
@section('og_description', !empty($settings['svc_seo_description']) ? $settings['svc_seo_description'] : 'Discover the world-class services and facilities at Hotel Beach Way, Cox\'s Bazar.')
@if(!empty($settings['svc_seo_og_image']))
@section('og_image', asset('storage/' . $settings['svc_seo_og_image']))
@endif

@section('content')

  <!-- ===========
       PAGE BREADCRUMB HERO
  =========== -->
  <section class="page-hero">
    <div class="page-hero-bg"
      @if(!empty($settings['svc_page_hero_image']))
        style="background-image: url('{{ asset('storage/' . $settings['svc_page_hero_image']) }}'); background-size: cover; background-position: center;"
      @endif
    ></div>
    <div class="page-hero-overlay"></div>
    <div class="page-hero-content">
      <h1 class="page-hero-title">{{ !empty($settings['svc_page_hero_title']) ? $settings['svc_page_hero_title'] : 'Our Services' }}</h1>
      <nav class="page-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="bc-sep">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
        <span class="bc-current">{{ !empty($settings['svc_page_hero_title']) ? $settings['svc_page_hero_title'] : 'Services' }}</span>
      </nav>
    </div>
    <div class="page-hero-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,40 C360,75 720,5 1080,40 C1260,58 1380,18 1440,40 L1440,80 L0,80 Z" fill="#ffffff"/>
      </svg>
    </div>
  </section>


  <!-- ===========
       SERVICES SECTION
  =========== -->
  <section class="svc-page-section">
    <div class="svc-page-inner">

      <div class="svc-page-header" data-reveal="up">
        @if(!empty($settings['svc_badge']))
          <span class="section-badge">{{ $settings['svc_badge'] }}</span>
        @endif
        @if(!empty($settings['svc_title']))
          <h2 class="svc-page-heading">{!! $settings['svc_title'] !!}</h2>
        @else
          <h2 class="svc-page-heading">Our Hotel Services</h2>
        @endif
      </div>

      @if($services->count())
      <div class="svc-page-grid">
        @foreach($services as $index => $service)
        <div class="svc-page-card" data-reveal="up" data-reveal-delay="{{ ($index % 6) + 1 }}">
          <div class="svc-card-img">
            @if($service->image)
              <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}" loading="lazy">
            @else
              <img src="{{ asset('assets/images/gallery-' . (($index % 6) + 1) . '.jpg') }}" alt="{{ $service->title }}" loading="lazy">
            @endif
          </div>
          <div class="svc-card-body">
            <div class="svc-card-meta">
              @if($service->icon_svg)
              <div class="svc-card-icon">
                {!! $service->icon_svg !!}
              </div>
              @endif
              <h3 class="svc-card-title">{{ $service->title }}</h3>
            </div>
            @if($service->short_desc)
            <p class="svc-card-desc">{{ $service->short_desc }}</p>
            @endif
            <a href="{{ route('service.detail', $service->slug) }}" class="btn-view-svc">View Service</a>
          </div>
        </div>
        @endforeach
      </div><!-- /svc-page-grid -->
      @else
      <p class="text-center text-gray-500 py-12">No services available at the moment.</p>
      @endif

    </div><!-- /svc-page-inner -->
  </section>


@endsection
