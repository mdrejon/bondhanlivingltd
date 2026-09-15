@extends('layouts.frontend')

@section('title', !empty($settings['faq_seo_title']) ? $settings['faq_seo_title'] : 'FAQs – Hotel Beach Way')
@section('meta_description', !empty($settings['faq_seo_description']) ? $settings['faq_seo_description'] : 'Find answers to frequently asked questions about Hotel Beach Way — Cox\'s Bazar\'s premier coastal retreat.')
@if(!empty($settings['faq_seo_keywords']))
@section('meta_keywords', $settings['faq_seo_keywords'])
@endif
@section('og_title', !empty($settings['faq_seo_title']) ? $settings['faq_seo_title'] : 'FAQs – Hotel Beach Way')
@section('og_description', !empty($settings['faq_seo_description']) ? $settings['faq_seo_description'] : 'Find answers to frequently asked questions about Hotel Beach Way — Cox\'s Bazar\'s premier coastal retreat.')
@if(!empty($settings['faq_seo_og_image']))
@section('og_image', asset('storage/' . $settings['faq_seo_og_image']))
@endif

@section('content')

@php
  $heroImage = $settings['faq_hero_image'] ?? null;
  $heroTitle = $settings['faq_hero_title'] ?? 'Frequently Asked Questions';

  // FAQ section — data comes from FAQ Management (Faq model), not GlobalSettings
  $faqGroup    = collect($faqs)->first();

  $faqImage    = $faqGroup?->image ?? null;
  $faqBadge    = $faqGroup?->badge ?? "FAQ'S";
  $faqTitle    = $faqGroup?->title ?? 'Most Questions That Asked By People.';
  $faqDesc     = $faqGroup?->description ?? 'Find answers to the most common questions about our hotel, rooms, services, and your stay at Hotel Beach Way — Cox\'s Bazar\'s premier coastal retreat.';
  $allFaqItems = collect($faqs)->flatMap(fn($f) => $f->items ?? [])->values();
  $defaultFaqItems = [
    ['question' => 'What is the check-in and check-out time?',
     'answer'   => 'Standard Check-in time is 12:30 PM and Check-out time is 11:30 AM. Early check-in and late check-out can be arranged based on availability — please contact our 24-hour front desk in advance.'],
    ['question' => 'Is breakfast included with the room rate?',
     'answer'   => 'Complimentary breakfast is included in select room packages. We offer a full buffet breakfast at our Dew Drop Restaurant from 7:00 AM to 10:30 AM. Please check your booking details for your specific package inclusions.'],
    ['question' => 'Do you offer airport pickup and drop service?',
     'answer'   => 'Yes! We provide convenient airport pickup and drop service from Cox\'s Bazar Airport, which is just 10 minutes away. Simply share your flight details with our team at the time of booking.'],
    ['question' => 'What is your cancellation policy?',
     'answer'   => 'We offer free cancellation up to 24 hours before your scheduled check-in date. Cancellations made within 24 hours of check-in may be subject to a one-night charge. Contact us for group booking policies.'],
    ['question' => 'Is free Wi-Fi available throughout the hotel?',
     'answer'   => 'Yes, complimentary high-speed Wi-Fi is available in all rooms and public areas including the lobby, restaurant, and conference hall. No password needed — simply connect to the Hotel Beach Way network.'],
    ['question' => 'Is parking available at the hotel?',
     'answer'   => 'Yes, we have dedicated on-site parking available for our guests at no extra charge. Our parking area is monitored and secure. Please inform us in advance if you are arriving with a vehicle.'],
    ['question' => 'Do you have facilities for conferences or events?',
     'answer'   => 'Hotel Beach Way features a fully equipped conference and banquet hall ideal for corporate meetings, seminars, weddings, and social events. Our team provides dedicated event support including AV equipment, catering, and décor.'],
    ['question' => 'What recreational facilities are available for guests?',
     'answer'   => 'We offer a swimming pool, gym centre, spa &amp; massage, jogging track, and the Dew Drop Restaurant &amp; BBQ. Our hotel is also steps away from Kolatoli Beach, giving you direct access to Cox\'s Bazar\'s stunning coastline.'],
  ];

  $displayFaqItems = $allFaqItems->isNotEmpty() ? $allFaqItems->toArray() : $defaultFaqItems;
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
        <path d="M0,40 C360,75 720,5 1080,40 C1260,58 1380,18 1440,40 L1440,80 L0,80 Z" fill="#E1EDE8"/>
      </svg>
    </div>
  </section><!-- /page-hero -->


  <!-- ===========
       FAQ SECTION
  =========== -->
  <section class="about-faq-section">

    <div class="section-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,45 C360,80 720,5 1080,45 C1260,65 1380,20 1440,45 L1440,0 L0,0 Z" fill="#e1ede8"/>
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
