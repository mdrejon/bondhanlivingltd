@extends('frontend.layouts.app')
@php
function getImgUrl($path, $default) {
if (empty($path)) return asset($default);
if (str_starts_with($path, 'assets/')) return asset($path);
return asset('storage/' . $path);
}
@endphp

@if(isset($projectContent['project_page_seo']))
@section('title', $projectContent['project_page_seo']['meta_title'] ?? 'Bondhan Living Ltd - Projects')
@section('meta_description', $projectContent['project_page_seo']['meta_description'] ?? '')
@section('meta_keywords', $projectContent['project_page_seo']['meta_keywords'] ?? '')
@section('meta_author', $projectContent['project_page_seo']['meta_author'] ?? '')
@endif

@section('content')
<div class="breadcumb-wrapper" data-bg-src="{{ getImgUrl($projectContent['project_page_hero']['bg_image'] ?? null, 'assets/img/bg/breadcumb-bg.jpg') }}">
      <div class="container">
        <div class="breadcumb-content">
          <h1 class="breadcumb-title">Upcoming Project</h1>
          <ul class="breadcumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li>Upcoming Project</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="site-main">
      <!--grid-section-->
      <div class="ttm-row grid-section clearfix space">
        <div class="container">
          <!-- row -->
          <div class="row g-4 ttm-boxes-spacing-30px">
            @if(isset($projects) && $projects->count() > 0)
              @foreach($projects as $project)
              <div class="col-lg-4 col-md-6 col-sm-6 ttm-box-col-wrapper pb-0">
                <!-- featured-imagebox-portfolio -->
                <div class="featured-imagebox featured-imagebox-portfolio style2">
                  <!-- ttm-box-view-overlay -->
                  <div class="featured-thumbnail">
                    <img
                      class="img-fluid"
                      src="{{ getImgUrl($project->thumbnail, 'assets/img/project/project-3-1.png') }}"
                      alt="{{ $project->title }}"
                    />
                  </div>
                  <div class="ttm-box-view-overlay">
                    <div class="ttm-media-link">
                      <a
                        class="ttm_prettyphoto ttm_image"
                        title="{{ $project->title }}"
                        data-rel="prettyPhoto"
                        href="{{ getImgUrl($project->thumbnail, 'assets/img/project/project-3-1.png') }}"
                      >
                        <i class="fa-solid fa-magnifying-glass"></i>
                      </a>
                      <a href="{{ route('projects.show', $project->slug) }}" class="ttm_link">
                        <i class="fa-solid fa-link"></i>
                      </a>
                    </div>
                  </div>
                  <!-- ttm-box-view-overlay end-->
                  <div class="featured-content">
                    <div class="featured-title">
                      <h3><a href="{{ route('projects.show', $project->slug) }}">{{ $project->title }}</a></h3>
                    </div>
                  </div>
                </div>
                <!-- featured-imagebox-portfolio -->
              </div>
              @endforeach
            @else
              <div class="col-12 text-center text-muted py-5">
                <p>No upcoming projects found.</p>
              </div>
            @endif
          </div>
          
          <div class="mt-4 text-center">
            {{ $projects->links() ?? '' }}
          </div>
          <!-- row end -->
        </div>
      </div>
      <!--grid-section end-->
      <!--financial-strategies-section -->
      <section class="ttm-row financial-section space-bottom clearfix">
        <div class="container">
          <div class="col-lg-12">
            <div class="text-center bg-smoke p-5 rounded">
              <h3 class="sec-title">
                We Offer Financial Strategies &amp; Superior Services
              </h3>
              <div class="mt-4">
                <a class="th-btn" href="contact.html"
                  >Get A Quote<i class="fa-solid fa-long-arrow-right ms-2"></i
                ></a>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!--financial-strategies-section-End -->
    </div>

    <!-- Start Partner Area -->
    <div class="partner-area ptb-100">
      <div class="container">
        <div class="partner-slider owl-theme owl-carousel">
          <div class="partner-item">
            <a href="#">
              <img src="assets/img/client/cilent_1_1.png" alt="Image" />
            </a>
          </div>

          <div class="partner-item">
            <a href="#">
              <img src="assets/img/client/cilent_1_2.png" alt="Image" />
            </a>
          </div>

          <div class="partner-item">
            <a href="#">
              <img src="assets/img/client/cilent_1_3.png" alt="Image" />
            </a>
          </div>

          <div class="partner-item">
            <a href="#">
              <img src="assets/img/client/cilent_1_4.png" alt="Image" />
            </a>
          </div>

          <div class="partner-item">
            <a href="#">
              <img src="assets/img/client/cilent_1_5.png" alt="Image" />
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- End Partner Area -->
@endsection
