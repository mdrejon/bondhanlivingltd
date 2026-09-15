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

@if(isset($chairmanContent['chairman_page_seo']))
    @section('title', $chairmanContent['chairman_page_seo']['meta_title'] ?? 'Bondhan Living Ltd - Chairman Message')
    @section('meta_description', $chairmanContent['chairman_page_seo']['meta_description'] ?? '')
    @section('meta_keywords', $chairmanContent['chairman_page_seo']['meta_keywords'] ?? '')
    @section('meta_author', $chairmanContent['chairman_page_seo']['meta_author'] ?? '')
@endif

@section('content')
<div class="breadcumb-wrapper" data-bg-src="{{ getImgUrl($chairmanContent['chairman_page_hero']['bg_image'] ?? null, 'assets/img/bg/breadcumb-bg.jpg') }}">
      <div class="container">
        <div class="breadcumb-content">
          <h1 class="breadcumb-title">{{ $chairmanContent['chairman_page_hero']['title'] ?? 'CHAIRMAN MESSAGE' }}</h1>
          <ul class="breadcumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li>{{ $chairmanContent['chairman_page_hero']['title'] ?? 'CHAIRMAN MESSAGE' }}</li>
          </ul>
        </div>
      </div>
    </div>
    
    <div class="space" data-bg-src="{{ getImgUrl($chairmanContent['chairman_page_main']['bg_image'] ?? null, 'assets/img/update1/bg/achive_bg_1.jpg') }}" id="chairman-sec">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-xl-6">
            <div class="title-area mb-35 text-center text-xl-start">
              <span class="sub-title6 justify-content-xl-start justify-content-center">
                <span class="shape left d-xl-none"><span class="dots"></span></span>
                {{ $chairmanContent['chairman_page_main']['subtitle'] ?? 'Message from the Chairman' }}
                <span class="shape right"><span class="dots"></span></span>
              </span>
              <h2 class="sec-title">{{ $chairmanContent['chairman_page_main']['title'] ?? 'CHAIRMAN' }} <span style="color: red;">{{ $chairmanContent['chairman_page_main']['highlight'] ?? 'MESSAGE' }}</span></h2>
            </div>
            <p class="mt-n2 mb-35 text-center text-xl-start" style="text-align: justify;">
              {{ $chairmanContent['chairman_page_main']['description'] ?? '' }}
            </p>
            <div class="chairman-info mt-40 text-center text-xl-start">
              <p class="mb-1" style="color: #666; font-size: 15px">
                {{ $chairmanContent['chairman_page_info']['salutation'] ?? 'Thanking you.' }}
              </p>
              <h5 class="mb-0" style="color: #333; font-size: 18px; font-weight: 600">
                {{ $chairmanContent['chairman_page_info']['name'] ?? 'Md. Ferdous Hasan' }}
              </h5>
              <p class="mb-0" style="color: #666; font-size: 15px">
                {{ $chairmanContent['chairman_page_info']['designation'] ?? 'Managing Director' }}
              </p>
              <p class="mb-0" style="color: #666; font-size: 15px">
                {{ $chairmanContent['chairman_page_info']['company'] ?? 'Bondhan Living Ltd.' }}
              </p>
            </div>
          </div>
          <div class="col-xl-6">
            <div class="ps-xl-5 mt-40 mt-xl-0">
              <div class="rounded-20">
                <img
                  class="w-100"
                  src="{{ getImgUrl($chairmanContent['chairman_page_main']['image'] ?? null, 'assets/img/team/team_1_1.jpg') }}"
                  alt="Chairman"
                  style="border-radius: 20px;"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

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
