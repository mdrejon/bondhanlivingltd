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

@if(isset($corporateContent['corporate_page_seo']))
    @section('title', $corporateContent['corporate_page_seo']['meta_title'] ?? 'Bondhan Living Ltd - Corporate Background')
    @section('meta_description', $corporateContent['corporate_page_seo']['meta_description'] ?? '')
    @section('meta_keywords', $corporateContent['corporate_page_seo']['meta_keywords'] ?? '')
    @section('meta_author', $corporateContent['corporate_page_seo']['meta_author'] ?? '')
@endif

@section('content')
<div class="breadcumb-wrapper" data-bg-src="{{ getImgUrl($corporateContent['corporate_page_hero']['bg_image'] ?? null, 'assets/img/bg/breadcumb-bg.jpg') }}">
      <div class="container">
        <div class="breadcumb-content">
          <h1 class="breadcumb-title">{{ $corporateContent['corporate_page_hero']['title'] ?? 'Corporate Background' }}</h1>
          <ul class="breadcumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li>{{ $corporateContent['corporate_page_hero']['title'] ?? 'Corporate Background' }}</li>
          </ul>
        </div>
      </div>
    </div>
    
    <div class="space" data-bg-src="{{ getImgUrl($corporateContent['corporate_page_main']['bg_image'] ?? null, 'assets/img/update1/bg/achive_bg_1.jpg') }}">
      <div class="container">
        <div class="row">
          <div class="col-xl-6">
            <div class="title-area mb-35 text-center text-xl-start">
              <span class="sub-title6 justify-content-xl-start justify-content-center">
                <span class="shape left d-xl-none"><span class="dots"></span></span>
                {{ $corporateContent['corporate_page_main']['subtitle'] ?? 'Corporate Background' }}
                <span class="shape right"><span class="dots"></span></span>
              </span>
              <h2 class="sec-title">{{ $corporateContent['corporate_page_main']['title'] ?? 'CORPORATE' }} <span style="color: red;">{{ $corporateContent['corporate_page_main']['highlight'] ?? 'BACKGROUND' }}</span></h2>
            </div>
            <p class="mt-n2 mb-35 text-center text-xl-start" style="text-align: justify;">
              {{ $corporateContent['corporate_page_main']['description'] ?? '' }}
            </p>
          </div>
          <div class="col-xl-6">
            <div class="ps-xl-5 mt-40 mt-xl-0">
              <div class="rounded-20">
                <img
                  class="w-100"
                  src="{{ getImgUrl($corporateContent['corporate_page_main']['image'] ?? null, 'assets/img/update1/normal/achive_1_1.jpg') }}"
                  alt="Corporate Background"
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
