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

@section('title', $galleryContent['gallery_page_seo']['meta_title'] ?? 'Work Gallery')
@section('meta_description', $galleryContent['gallery_page_seo']['meta_description'] ?? '')
@section('meta_keywords', $galleryContent['gallery_page_seo']['meta_keywords'] ?? '')
@section('meta_author', $galleryContent['gallery_page_seo']['meta_author'] ?? '')

@section('content')
<div class="breadcumb-wrapper" data-bg-src="{{ getImgUrl($galleryContent['gallery_page_hero']['bg_image'] ?? null, 'assets/img/bg/breadcumb-bg.jpg') }}">
      <div class="container">
        <div class="breadcumb-content">
          <h1 class="breadcumb-title">{{ $galleryContent['gallery_page_hero']['title'] ?? 'WORK GALLERY' }}</h1>
          <ul class="breadcumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li>{{ $galleryContent['gallery_page_hero']['title'] ?? 'GALLERY' }}</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="space">
      <div class="container">
        <div class="row gy-4 masonary-active">
          @if(isset($galleryContent['gallery_page_images']) && is_array($galleryContent['gallery_page_images']))
            @foreach($galleryContent['gallery_page_images'] as $imgItem)
              @if(!empty($imgItem['image']))
                <div class="col-md-6 col-xxl-auto filter-item">
                  <div class="gallery-card">
                    <div class="gallery-img">
                      <img
                        src="{{ getImgUrl($imgItem['image'], 'assets/img/gallery/gallery_1_1.jpg') }}"
                        alt="gallery image"
                      />
                      <a
                        href="{{ getImgUrl($imgItem['image'], 'assets/img/gallery/gallery_1_1.jpg') }}"
                        class="gallery-btn popup-image"
                        ><i class="fas fa-plus"></i
                      ></a>
                    </div>
                  </div>
                </div>
              @endif
            @endforeach
          @endif
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
