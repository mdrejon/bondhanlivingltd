@extends('layouts.frontend')

@section('title', $service->title . ' – Services | Hotel Beach Way')
@section('meta_description', $service->short_desc ?? 'Learn more about ' . $service->title . ' at Hotel Beach Way, Cox\'s Bazar.')
@section('og_title', $service->title . ' – Services | Hotel Beach Way')
@section('og_description', $service->short_desc ?? 'Learn more about ' . $service->title . ' at Hotel Beach Way, Cox\'s Bazar.')
@if($service->image)
@section('og_image', asset('storage/' . $service->image))
@endif

@section('content')

@php
  $phone = $settings['footer_phone_1'] ?? '+88 01777-909595';
  $email = $settings['footer_email_1'] ?? 'info@hotelbeachway.com';
@endphp

  <!-- ===========
       PAGE BREADCRUMB HERO
  =========== -->
  <section class="page-hero">
    <div class="page-hero-bg"
      @if($service->image)
        style="background-image: url('{{ asset('storage/' . $service->image) }}'); background-size: cover; background-position: center;"
      @endif
    ></div>
    <div class="page-hero-overlay"></div>
    <div class="page-hero-content">
      <h1 class="page-hero-title">{{ $service->title }}</h1>
      <nav class="page-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="bc-sep">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
        <a href="{{ route('services.front') }}">Services</a>
        <span class="bc-sep">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
        <span class="bc-current">{{ $service->title }}</span>
      </nav>
    </div>
    <div class="page-hero-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,40 C360,75 720,5 1080,40 C1260,58 1380,18 1440,40 L1440,80 L0,80 Z" fill="#ffffff"/>
      </svg>
    </div>
  </section>


  <!-- ===========
       SERVICE DETAILS
  =========== -->
  <section class="sd-section">
    <div class="sd-inner">

      <!-- =========== LEFT: Sticky Sidebar =========== -->
      <aside class="sd-left">

        <!-- All Services Navigation -->
        <div class="sd-sidebar-box" data-reveal="left">
          <h3 class="sd-box-title">All Services</h3>
          <ul class="sd-cat-list">
            @foreach($allServices as $svc)
            <li>
              <a href="{{ route('service.detail', $svc->slug) }}"
                 class="sd-cat-item {{ $svc->id === $service->id ? 'active' : '' }}">
                <span>{{ $svc->title }}</span>
              </a>
            </li>
            @endforeach
          </ul>
        </div>

        <!-- Needs Any Help -->
        <div class="sd-sidebar-box" data-reveal="left" data-reveal-delay="1">
          <h3 class="sd-box-title">{{ !empty($settings['svc_help_title']) ? $settings['svc_help_title'] : 'Needs Any Help?' }}</h3>
          <p class="rd-card-desc">{{ !empty($settings['svc_help_desc']) ? $settings['svc_help_desc'] : 'Our client care team is available 24/7 to answer your questions and assist with any service inquiry or reservation.' }}</p>
          <div class="sd-help-info">
            <div class="sd-help-item">
              <div class="sd-help-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
              </div>
              <div>
                <div class="sd-help-label">Phone Number</div>
                <div class="sd-help-value">{{ $phone }}</div>
              </div>
            </div>
            <div class="sd-help-item">
              <div class="sd-help-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              </div>
              <div>
                <div class="sd-help-label">Email Address</div>
                <div class="sd-help-value">{{ $email }}</div>
              </div>
            </div>
          </div>
        </div>

      </aside><!-- /sd-left -->


      <!-- =========== RIGHT: Content =========== -->
      <div class="sd-right" data-reveal="right">

        <!-- Main image -->
        @if($service->image)
        <div class="sd-main-img">
          <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->title }}">
          @php $galleryCount = ($service->gallery_image_1 ? 1 : 0) + ($service->gallery_image_2 ? 1 : 0); @endphp
          @if($galleryCount)
          <div class="sd-img-dots">
            <button class="sd-img-dot active" aria-label="Image 1"></button>
            @if($service->gallery_image_1)<button class="sd-img-dot" aria-label="Image 2"></button>@endif
            @if($service->gallery_image_2)<button class="sd-img-dot" aria-label="Image 3"></button>@endif
          </div>
          @endif
        </div>
        @endif

        <!-- Title + main description -->
        <h2 class="sd-content-title">{{ $service->title }}</h2>

        @if($service->description)
          <div class="sd-text">{!! $service->description !!}</div>
        @endif

        <!-- Benefits section -->
        @if($service->benefits_title || $service->benefits_text)
          <h3 class="sd-content-sub">{{ $service->benefits_title ?: 'We Give The Best Services' }}</h3>
          @if($service->benefits_text)
            <div class="sd-text">{!! $service->benefits_text !!}</div>
          @endif
        @endif

        <!-- Gallery images -->
        @if($service->gallery_image_1 || $service->gallery_image_2)
        <div class="sd-img-grid">
          @if($service->gallery_image_1)
            <img src="{{ asset('storage/' . $service->gallery_image_1) }}" alt="{{ $service->title }} gallery">
          @endif
          @if($service->gallery_image_2)
            <img src="{{ asset('storage/' . $service->gallery_image_2) }}" alt="{{ $service->title }} gallery">
          @endif
        </div>
        @endif

        <!-- Features / Why Choose This Service -->
        @if(!empty($service->features))
        <h3 class="sd-content-sub">Why Choose This Service</h3>
        <ul class="sd-why-list">
          @foreach($service->features as $feature)
            <li>{{ $feature }}</li>
          @endforeach
        </ul>
        @endif

 

        <!-- FAQ accordion -->
        @if(!empty($service->faqs))
        <div class="sd-faq-section">
          <h3 class="sd-faq-title">Frequently Asked Questions</h3>
          <div class="sd-faq-list">
            @foreach($service->faqs as $i => $faq)
            @if(!empty($faq['question']))
            <div class="sd-faq-item {{ $i === 0 ? 'active' : '' }}">
              <button class="sd-faq-question" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                <span>{{ $faq['question'] }}</span>
                <span class="sd-faq-icon">+</span>
              </button>
              <div class="sd-faq-answer"@if($i === 0) style="display:block;"@endif>
                {{ $faq['answer'] ?? '' }}
              </div>
            </div>
            @endif
            @endforeach
          </div>
        </div>
        @endif

      </div><!-- /sd-right -->

    </div><!-- /sd-inner -->
  </section>


@endsection

@push('scripts')
<script>
  document.querySelectorAll('.sd-faq-question').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var item = btn.closest('.sd-faq-item');
      var isOpen = item.classList.contains('active');
      document.querySelectorAll('.sd-faq-item').forEach(function(el) {
        el.classList.remove('active');
        el.querySelector('.sd-faq-answer').style.display = 'none';
        el.querySelector('.sd-faq-question').setAttribute('aria-expanded', 'false');
      });
      if (!isOpen) {
        item.classList.add('active');
        item.querySelector('.sd-faq-answer').style.display = 'block';
        btn.setAttribute('aria-expanded', 'true');
      }
    });
  });
</script>
@endpush
