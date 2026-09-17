@extends('frontend.layouts.app')

@section('meta_title', $serviceContent['service_page_seo']['meta_title'] ?? 'Our Services')
@section('meta_description', $serviceContent['service_page_seo']['meta_description'] ?? '')
@section('meta_keywords', $serviceContent['service_page_seo']['meta_keywords'] ?? '')
@section('meta_author', $serviceContent['service_page_seo']['meta_author'] ?? '')

@section('content')
<!-- Breadcrumb -->
    <div class="breadcumb-wrapper" data-bg-src="{{ !empty($serviceContent['service_page_hero']['bg_image']) ? asset('storage/' . $serviceContent['service_page_hero']['bg_image']) : asset('assets/img/bg/breadcumb-bg.jpg') }}">
      <div class="container">
        <div class="breadcumb-content">
          <h1 class="breadcumb-title">{{ $serviceContent['service_page_hero']['title'] ?? 'Our Services' }}</h1>
          <ul class="breadcumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li>{{ $serviceContent['service_page_hero']['title'] ?? 'Our Services' }}</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Services Section -->
    <section class="service-area12 space" id="service-sec">
      <div class="container">
        <div class="row">
          <div class="title-area mb-5 text-center">
            <span class="sub-title9 justify-content-center">What We Do</span>
            <h2 class="sec-title">Explore All Services</h2>
          </div>
        </div>
        
        <div class="row gy-4">
          @foreach($services as $index => $service)
          <div class="col-md-6 col-lg-4">
            <div class="service-box" data-bg-src="{{ asset('assets/img/update2/bg/shape_bg_1.png') }}">
              <div class="service-content">
                <div class="service-box_icon">
                  <img src="{{ !empty($service->icon) && str_starts_with($service->icon, 'assets/') ? asset($service->icon) : (!empty($service->icon) ? asset('storage/' . $service->icon) : asset('assets/img/update2/icon/service_1_' . ($index % 6 + 1) . '.svg')) }}" alt="icon" style="max-width: 50px; max-height: 50px;" />
                </div>
                <div class="service-box_number">{{ sprintf('%02d', $index + 1) }}</div>
              </div>
              <h3 class="box-title">
                <a href="{{ route('services.show', $service->slug) }}">{{ $service->title }}</a>
              </h3>
              <p class="service-box_text">
                {{ \Illuminate\Support\Str::limit($service->short_description, 100) }}
              </p>
              <a class="line-btn" href="{{ route('services.show', $service->slug) }}">Read More <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section
      class="space-extra"
      data-bg-src="{{ asset('assets/img/update1/bg/cta_bg_1.jpg') }}"
    >
      <div class="container">
        <div
          class="row align-items-center justify-content-center justify-content-lg-between"
        >
          <div
            class="col-lg-8 col-md-10 mb-4 mb-lg-0 text-center text-lg-start"
          >
            <h2 class="mb-0 text-white">
              Have Any Question For Project Plan In Your Mind?
            </h2>
          </div>
          <div class="col-lg-auto text-center text-lg-start">
            <a href="{{ route('contact') }}" class="th-btn style6 style-new"
              >GET IN TOUCH<i class="fas fa-arrow-right ms-2"></i
            ></a>
          </div>
        </div>
      </div>
    </section>

    <!-- Start Partner Area -->
    <div class="partner-area ptb-100">
      <div class="container">
        <div class="partner-slider owl-theme owl-carousel">
          <div class="partner-item">
            <a href="#"
              ><img src="{{ asset('assets/img/client/cilent_1_1.png') }}" alt="Image"
            /></a>
          </div>
          <div class="partner-item">
            <a href="#"
              ><img src="{{ asset('assets/img/client/cilent_1_2.png') }}" alt="Image"
            /></a>
          </div>
          <div class="partner-item">
            <a href="#"
              ><img src="{{ asset('assets/img/client/cilent_1_3.png') }}" alt="Image"
            /></a>
          </div>
          <div class="partner-item">
            <a href="#"
              ><img src="{{ asset('assets/img/client/cilent_1_4.png') }}" alt="Image"
            /></a>
          </div>
          <div class="partner-item">
            <a href="#"
              ><img src="{{ asset('assets/img/client/cilent_1_5.png') }}" alt="Image"
            /></a>
          </div>
        </div>
      </div>
    </div>
    <!-- End Partner Area -->
@endsection
