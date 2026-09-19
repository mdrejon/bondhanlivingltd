@extends('frontend.layouts.app')

<?php
    if (!function_exists('getImgUrl')) {
        function getImgUrl($path, $default) {
            if (!$path) return asset($default);
            if (str_starts_with($path, 'assets/')) return asset($path);
            return asset('storage/' . $path);
        }
    }
?>

@section('title', $termsContent['terms_page_seo']['meta_title'] ?? 'Terms and Conditions')
@section('meta_description', $termsContent['terms_page_seo']['meta_description'] ?? '')
@section('meta_keywords', $termsContent['terms_page_seo']['meta_keywords'] ?? '')
@section('meta_author', $termsContent['terms_page_seo']['meta_author'] ?? '')

@section('content')
<div class="breadcumb-wrapper" data-bg-src="{{ getImgUrl($termsContent['terms_page_hero']['bg_image'] ?? null, 'assets/img/bg/breadcumb-bg.jpg') }}">
      <div class="container">
        <div class="breadcumb-content">
          <h1 class="breadcumb-title">{{ $termsContent['terms_page_hero']['title'] ?? 'Terms and Condition' }}</h1>
          <ul class="breadcumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li>{{ $termsContent['terms_page_hero']['title'] ?? 'Terms and Condition' }}</li>
          </ul>
        </div>
      </div>
    </div>

    
    <section class="space" id="terms-sec">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-9 col-lg-10">
            <div class="title-area mb-50 text-center">
              <span class="sub-title6 justify-content-center">
                <span class="shape left"><span class="dots"></span></span>
                Bondhan Living Ltd
                <span class="shape right"><span class="dots"></span></span>
              </span>
              <h2 class="sec-title">{{ $termsContent['terms_page_hero']['title'] ?? 'Terms and Condition' }}</h2>
            </div>
            <div class="terms-wrap">

            @if(isset($termsContent['terms_page_items']) && is_array($termsContent['terms_page_items']))
                @foreach($termsContent['terms_page_items'] as $item)
                    <div class="term-item mb-30 p-4" style="background: #fbfbfb; border-left: 4px solid var(--theme-color, #36317A); border-radius: 4px; box-shadow: 0 5px 15px rgba(0,0,0,0.03);">
                        <h4 class="mb-3" style="font-size: 20px; color: var(--title-color, #0e121d);">{{ $item['title'] ?? '' }}</h4>
                        <p class="mb-0 text-muted" style="line-height: 1.7; text-align: justify;">{{ $item['description'] ?? '' }}</p>
                    </div>
                @endforeach
            @endif

            </div>
          </div>
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
