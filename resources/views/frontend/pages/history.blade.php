@extends('frontend.layouts.app')

@php
    if (!function_exists('getImgUrl')) {
        function getImgUrl($path, $default) {
            if (empty($path)) return asset($default);
            if (str_starts_with($path, 'assets/')) return asset($path);
            return asset('storage/' . $path);
        }
    }
@endphp

@if(isset($historyContent['history_page_seo']))
    @section('title', $historyContent['history_page_seo']['meta_title'] ?? 'Bondhan Living Ltd - History')
    @section('meta_description', $historyContent['history_page_seo']['meta_description'] ?? '')
    @section('meta_keywords', $historyContent['history_page_seo']['meta_keywords'] ?? '')
    @section('meta_author', $historyContent['history_page_seo']['meta_author'] ?? '')
@endif

@section('content')
<div class="breadcumb-wrapper" data-bg-src="{{ getImgUrl($historyContent['history_page_hero']['bg_image'] ?? null, 'assets/img/bg/breadcumb-bg.jpg') }}">
      <div class="container">
        <div class="breadcumb-content">
          <h1 class="breadcumb-title">{{ $historyContent['history_page_hero']['title'] ?? 'History' }}</h1>
          <ul class="breadcumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li>{{ $historyContent['history_page_hero']['title'] ?? 'History' }}</li>
          </ul>
        </div>
      </div>
    </div>

    <style>
      .timeline-section {
          padding: 100px 0;
          background-color: #fbfbfb;
      }
      .timeline {
          position: relative;
          max-width: 1200px;
          margin: 0 auto;
      }
      .timeline::after {
          content: '';
          position: absolute;
          width: 4px;
          background-color: var(--theme-color, #36317A);
          top: 0;
          bottom: 0;
          left: 50%;
          margin-left: -2px;
          border-radius: 5px;
      }
      .timeline-container {
          padding: 10px 40px;
          position: relative;
          background-color: inherit;
          width: 50%;
      }
      .timeline-container::after {
          content: '';
          position: absolute;
          width: 24px;
          height: 24px;
          right: -12px;
          background-color: #fff;
          border: 5px solid var(--theme-color, #36317A);
          top: 28px;
          border-radius: 50%;
          z-index: 1;
          box-shadow: 0 0 0 4px rgba(54, 49, 122, 0.1);
      }
      .left-timeline {
          left: 0;
      }
      .right-timeline {
          left: 50%;
      }
      
      /* Left Card Arrows (White) */
      .left-timeline::before {
          content: " ";
          height: 0;
          position: absolute;
          top: 26px;
          width: 0;
          z-index: 1;
          right: 30px;
          border: medium solid #fff;
          border-width: 10px 0 10px 10px;
          border-color: transparent transparent transparent #fff;
      }
      
      /* Right Card Arrows (Theme Color) */
      .right-timeline::before {
          content: " ";
          height: 0;
          position: absolute;
          top: 26px;
          width: 0;
          z-index: 1;
          left: 30px;
          border: medium solid var(--theme-color, #36317A);
          border-width: 10px 10px 10px 0;
          border-color: transparent var(--theme-color, #36317A) transparent transparent;
      }
      
      .right-timeline::after {
          left: -12px;
      }
      
      .timeline-content {
          padding: 35px;
          position: relative;
          border-radius: 10px;
          transition: all 0.3s ease;
          box-shadow: 0 5px 20px rgba(0,0,0,0.05);
      }
      
      /* Left Card Style */
      .left-timeline .timeline-content {
          background-color: #fff;
          border: 1px solid #eee;
      }
      
      /* Right Card Style */
      .right-timeline .timeline-content {
          background-color: var(--theme-color, #36317A);
          border: 1px solid var(--theme-color, #36317A);
          color: #fff;
      }
      .right-timeline .timeline-content h3, 
      .right-timeline .timeline-content p {
          color: #fff;
      }
      
      .timeline-content:hover {
          transform: translateY(-5px);
          box-shadow: 0 15px 30px rgba(0,0,0,0.1);
      }
      
      .timeline-date {
          display: inline-block;
          padding: 6px 20px;
          border-radius: 30px;
          font-size: 14px;
          font-weight: 700;
          margin-bottom: 20px;
          letter-spacing: 1px;
      }
      
      /* Left Card Date */
      .left-timeline .timeline-date {
          background-color: var(--theme-color, #36317A);
          color: #fff;
      }
      
      /* Right Card Date */
      .right-timeline .timeline-date {
          background-color: #fff;
          color: var(--theme-color, #36317A);
      }
      
      .timeline-img {
          width: 100%;
          height: 200px;
          object-fit: cover;
          border-radius: 8px;
          margin-top: 20px;
      }
      
      @media screen and (max-width: 991px) {
        .timeline::after {
          left: 31px;
        }
        .timeline-container {
          width: 100%;
          padding-left: 70px;
          padding-right: 25px;
        }
        
        /* On mobile, all arrows point right from the center line */
        .timeline-container::before {
          left: 60px;
          border-width: 10px 10px 10px 0;
        }
        .left-timeline::before {
          border: medium solid #fff;
          border-color: transparent #fff transparent transparent;
        }
        .right-timeline::before {
          border: medium solid var(--theme-color, #36317A);
          border-color: transparent var(--theme-color, #36317A) transparent transparent;
        }
        
        .left-timeline::after, .right-timeline::after {
          left: 19px;
        }
        .right-timeline {
          left: 0%;
        }
      }
    </style>

    <section class="timeline-section" id="history-timeline">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 text-center mb-50">
            <span class="sub-title4 justify-content-center">{{ $historyContent['history_page_main']['subtitle'] ?? 'Journey of Bondhan Living' }}</span>
            <h2 class="sec-title fw-semibold">{{ $historyContent['history_page_main']['title'] ?? 'Our Glorious History' }}</h2>
            <p class="mt-3">{{ $historyContent['history_page_main']['description'] ?? 'From a humble beginning to becoming one of the most trusted real estate developers in the country, explore the milestones that defined our success.' }}</p>
          </div>
        </div>
        
        <div class="timeline">
          @if(isset($historyContent['history_page_timeline']) && is_array($historyContent['history_page_timeline']))
            @foreach($historyContent['history_page_timeline'] as $index => $item)
            <div class="timeline-container {{ $index % 2 == 0 ? 'left-timeline' : 'right-timeline' }}">
              <div class="timeline-content">
                <span class="timeline-date">{{ $item['year'] ?? '' }}</span>
                <h3 class="h4">{{ $item['title'] ?? '' }}</h3>
                <p>{{ $item['description'] ?? '' }}</p>
                <img src="{{ getImgUrl($item['image'] ?? null, 'assets/img/project/project_1_1.jpg') }}" alt="{{ $item['title'] ?? 'History Image' }}" class="timeline-img">
              </div>
            </div>
            @endforeach
          @endif
        </div>
      </div>
    </section>

    <!-- Start Partner Area -->
    <div class="partner-area ptb-100">
      <div class="container">
        <div class="partner-slider owl-theme owl-carousel">
          <div class="partner-item">
            <a href="#">
              <img src="{{ asset('assets/img/client/cilent_1_1.png') }}" alt="Image" />
            </a>
          </div>

          <div class="partner-item">
            <a href="#">
              <img src="{{ asset('assets/img/client/cilent_1_2.png') }}" alt="Image" />
            </a>
          </div>

          <div class="partner-item">
            <a href="#">
              <img src="{{ asset('assets/img/client/cilent_1_3.png') }}" alt="Image" />
            </a>
          </div>

          <div class="partner-item">
            <a href="#">
              <img src="{{ asset('assets/img/client/cilent_1_4.png') }}" alt="Image" />
            </a>
          </div>

          <div class="partner-item">
            <a href="#">
              <img src="{{ asset('assets/img/client/cilent_1_5.png') }}" alt="Image" />
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- End Partner Area -->
@endsection
