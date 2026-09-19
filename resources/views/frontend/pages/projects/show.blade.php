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
@section('content')
<div class="breadcumb-wrapper" data-bg-src="assets/img/bg/breadcumb-bg.jpg">
      <div class="container">
        <div class="breadcumb-content">
          <h1 class="breadcumb-title">Project Details</h1>
          <ul class="breadcumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li>Project Details</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="site-main">
      <div class="site-main">
        <section class="space">
          <div class="container">
            <!-- Main Slider Section -->
            <div class="row mb-5">
              <div class="col-12">
                <div class="owl-carousel owl-theme project-slider-main">
                  <div class="item">
                    <img
                      src="{{ getImgUrl($project->thumbnail, 'assets/img/project/project-3-1.png') }}"
                      alt="Project Image"
                      class="w-100 rounded"
                      style="height: 550px; object-fit: cover"
                    />
                  </div>
                </div>

                <!-- Thumbnails -->
                <div class="row g-3 mt-1 thumb-gallery">
                  <div class="col-3">
                    <img
                      src="{{ getImgUrl($project->thumbnail, 'assets/img/project/project-3-1.png') }}"
                      alt="Thumbnail"
                      class="w-100 rounded"
                      style="height: 120px; object-fit: cover; cursor: pointer"
                    />
                  </div>
                </div>
              </div>
            </div>

            <div class="row gy-4">
              <!-- Left Column: Main Content -->
              <div class="col-lg-12">
                <!-- Title Box -->
                <div class="bg-smoke p-4 rounded mb-4">
                  <div class="row align-items-center">
                    <div class="col-md-8 mb-3 mb-md-0">
                      <h2 class="h3 mb-2 fw-bold text-title">
                        {{ $project->title }}
                      </h2>
                      <p class="text-body mb-2" style="font-size: 15px">
                        @if($project->location)
                        <i class="fa-solid fa-location-dot text-theme me-2"></i>
                        {{ $project->location }}
                        @endif
                        <i class="fa-solid fa-clock text-theme ms-3 me-2"></i>
                        {{ $project->created_at->diffForHumans() }}
                        <span class="badge bg-theme ms-2 px-3 py-2 text-white text-capitalize"
                          >{{ $project->status }}</span
                        >
                      </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                      <div class="d-flex gap-2 justify-content-md-end">
                        <a
                          href="#"
                          class="icon-btn border bg-white d-inline-flex align-items-center justify-content-center text-title"
                          style="width: 40px; height: 40px; border-radius: 50%"
                          ><i class="fa-solid fa-share-nodes"></i
                        ></a>
                        <a
                          href="#"
                          onclick="window.print()"
                          class="icon-btn border bg-white d-inline-flex align-items-center justify-content-center text-title"
                          style="width: 40px; height: 40px; border-radius: 50%"
                          ><i class="fa-solid fa-print"></i
                        ></a>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Description -->
                <div class="bg-smoke p-4 p-lg-5 rounded mb-4">
                  <h3 class="h4 mb-3 fw-bold text-title">Description</h3>
                  <div class="text-body mb-4">
                    {!! nl2br(e($project->description)) !!}
                  </div>
                </div>

                <div class="row gy-4">
                  <div class="col-lg-6">
                    <!-- Property Details -->
                    <div class="bg-smoke p-4 p-lg-5 rounded mb-4">
                      <h3 class="h4 mb-4 fw-bold text-title">
                        Project Details
                      </h3>
                      <div class="row">
                        <div class="col-sm-6">
                          <ul class="list-unstyled text-body">
                            <li class="mb-3">
                              <strong class="text-title fw-semibold"
                                >Client:</strong
                              >
                              {{ $project->client ?? 'N/A' }}
                            </li>
                            <li class="mb-3">
                              <strong class="text-title fw-semibold"
                                >Category:</strong
                              >
                              {{ $project->category ?? 'Constructions' }}
                            </li>
                          </ul>
                        </div>
                        <div class="col-sm-6">
                          <ul class="list-unstyled text-body">
                            <li class="mb-3">
                              <strong class="text-title fw-semibold"
                                >Status:</strong
                              >
                              <span class="text-capitalize">{{ $project->status }}</span>
                            </li>
                            <li class="mb-3">
                              <strong class="text-title fw-semibold"
                                >Location:</strong
                              >
                              {{ $project->location ?? 'N/A' }}
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- end col-lg-6 Property Details -->
                  <div class="col-lg-6">
                    <!-- Facilities (Static Placeholder for now) -->
                    <div class="bg-smoke p-4 p-lg-5 rounded mb-4">
                      <h3 class="h4 mb-4 fw-bold text-title">Facilities</h3>
                      <div class="row">
                        <div class="col-sm-6">
                          <ul class="list-unstyled text-body">
                            <li class="mb-3">
                              <i
                                class="fa-solid fa-circle-check text-theme me-2"
                              ></i>
                              Modern Design
                            </li>
                            <li class="mb-3">
                              <i
                                class="fa-solid fa-circle-check text-theme me-2"
                              ></i>
                              High Quality Build
                            </li>
                            <li class="mb-3">
                              <i
                                class="fa-solid fa-circle-check text-theme me-2"
                              ></i>
                              Eco-friendly
                            </li>
                          </ul>
                        </div>
                        <div class="col-sm-6">
                          <ul class="list-unstyled text-body">
                            <li class="mb-3">
                              <i
                                class="fa-solid fa-circle-check text-theme me-2"
                              ></i>
                              Fast Delivery
                            </li>
                            <li class="mb-3">
                              <i
                                class="fa-solid fa-circle-check text-theme me-2"
                              ></i>
                              Experienced Team
                            </li>
                            <li class="mb-3">
                              <i
                                class="fa-solid fa-circle-check text-theme me-2"
                              ></i>
                              Support
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- end col-lg-6 Facilities -->
                </div>
                <!-- end row two-column -->

              </div>
            </div>
          </div>
        </section>
      </div>
      <section class="ttm-row financial-section space-bottom clearfix">
        <div class="container">
          <div class="col-lg-12">
            <div class="text-center bg-smoke p-5 rounded">
              <h3 class="sec-title">
                We Offer Financial Strategies &amp; Superior Services
              </h3>
              <div class="mt-4">
                <a class="th-btn" href="#"
                  >Get A Quote<i class="fa-solid fa-long-arrow-right ms-2"></i
                ></a>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!--financial-strategies-section-End -->
    </div>

@endsection
