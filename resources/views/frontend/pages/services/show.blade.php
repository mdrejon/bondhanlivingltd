@extends('frontend.layouts.app')

@section('meta_title', $service->seo_title ?: $service->title)
@section('meta_description', $service->seo_description ?: '')
@section('meta_keywords', $service->seo_keywords ?: '')

@section('content')
<div class="breadcumb-wrapper" data-bg-src="{{ asset('assets/img/bg/breadcumb-bg.jpg') }}">
      <div class="container">
        <div class="breadcumb-content">
          <h1 class="breadcumb-title">{{ $service->title }}</h1>
          <ul class="breadcumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('services.index') }}">Services</a></li>
            <li>{{ $service->title }}</li>
          </ul>
        </div>
      </div>
    </div>
    <section class="space-top space-extra-bottom">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 widget-area sidebar-left">
            <aside class="widget widget_categories">
              <h3 class="widget_title">Services</h3>
              <ul>
                @foreach($allServices as $sidebarService)
                <li class="{{ $service->id == $sidebarService->id ? 'active' : '' }}">
                  <a href="{{ route('services.show', $sidebarService->slug) }}">{{ $sidebarService->title }}</a>
                </li>
                @endforeach
              </ul>
            </aside>
            <aside class="widget widget_banner">
              <div
                class="widget-banner-inner bg-title p-4 text-white text-center rounded"
              >
                <h3 class="text-white mt-0">Our Office Location</h3>
                <p>2406 Beverley Rd Brooklyn New York 11456 United States</p>
                <h3 class="text-white mt-4">Quick Contact Us</h3>
                <p class="mb-0">Email: info@yourdomain.com</p>
                <p>Call Us: 123 456 7890</p>
                <h3 class="text-white mt-4">Office Opening Hours</h3>
                <p class="mb-0">Monday - Friday</p>
                <p>09:00 Am - 06:00 PM</p>
                <a class="th-btn mt-4 w-100" href="{{ route('contact') }}"
                  >Book Appointment</a
                >
              </div>
            </aside>
          </div>
          
          <div class="col-lg-8 content-area">
            <div class="page-single">
              @if($service->image)
              <div class="page-img mb-40">
                <img
                  src="{{ !empty($service->image) && str_starts_with($service->image, 'assets/') ? asset($service->image) : (!empty($service->image) ? asset('storage/' . $service->image) : '') }}"
                  alt="{{ $service->title }}"
                  class="w-100 rounded"
                />
              </div>
              @endif
              
              <div class="page-content">
                <h3 class="h3 page-title">{{ $service->title }}</h3>
                
                @if($service->description)
                    {!! nl2br(e($service->description)) !!}
                @else
                    <p>{{ $service->short_description }}</p>
                @endif
                
              </div>
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
