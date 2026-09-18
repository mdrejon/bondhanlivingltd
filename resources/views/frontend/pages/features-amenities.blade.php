@extends('frontend.layouts.app')
@section('content')
<style>
  .amenity-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
  }
  .banner-overlay-box {
      background: rgba(255, 255, 255, 0.9);
      padding: 50px 40px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      margin-top: 100px;
      margin-bottom: 100px;
      border-radius: 8px;
  }
</style>

    
    <div class="breadcumb-wrapper" data-bg-src="assets/img/bg/breadcumb-bg.jpg">
      <div class="container">
        <div class="breadcumb-content">
          <h1 class="breadcumb-title">FEATURES AND AMENITIES</h1>
          <ul class="breadcumb-menu">
            <li><a href="index.html">Home</a></li>
            <li>Features and Amenities</li>
          </ul>
        </div>
      </div>
    </div>
<!-- Features Section -->
    <section class="space" id="features-sec" style="background-color: #fbfbfb;">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-xl-10">
            <div class="title-area mb-40 text-center">
              <h2 class="sec-title text-uppercase">FEATURES OF <span class="text-theme">BONDHAN LIVING PRODUCTS</span></h2>
            </div>
            <div class="features-box p-5" style="background: #fff; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.03);">
              <ul class="list-unstyled mb-0 columns-2" style="column-count: 2; column-gap: 40px;">
                    @if(!empty($features))
                      @foreach($features as $feature)
                        <li class="mb-2" style="font-size: 15px; color: #555;"><i class="fa-solid fa-angles-right text-theme me-2"></i> {{ $feature }}</li>
                      @endforeach
                    @else
                        <li class="mb-2" style="font-size: 15px; color: #555;">No features added yet.</li>
                    @endif
              </ul>
              <style>
                @media (max-width: 768px) {
                  .columns-2 { column-count: 1 !important; }
                }
              </style>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Amenities Section -->
    <section class="space-bottom" id="amenities-sec" style="background-color: #fbfbfb;">
      <div class="container">
        <div class="title-area mb-50 text-center">
          <h2 class="sec-title text-uppercase">AMENITIES</h2>
        </div>
        <div class="row">
            @if(!empty($amenities))
              @foreach($amenities as $amenityGroup)
                <div class="{{ (isset($amenityGroup['category']) && $amenityGroup['category'] === 'GENERAL AMENITIES OF THE COMPLEX') ? 'col-lg-12' : 'col-lg-6' }} col-md-12 mb-4">
                  <div class="amenity-card p-4 h-100" style="background: #fff; border: 1px solid #eee; border-radius: 8px; box-shadow: 0 5px 20px rgba(0,0,0,0.03); transition: all 0.3s ease;">
                    <h4 class="mb-4 pb-2" style="border-bottom: 2px solid var(--theme-color); display: inline-block; color: var(--theme-color); font-weight: 700; font-size: 18px; text-transform: uppercase;">{{ $amenityGroup['category'] ?? '' }}</h4>
                    <ul class="list-unstyled mb-0" {!! (isset($amenityGroup['category']) && $amenityGroup['category'] === 'GENERAL AMENITIES OF THE COMPLEX') ? 'style="column-count: 2; column-gap: 40px;"' : '' !!}>
                      @if(!empty($amenityGroup['items']))
                        @foreach($amenityGroup['items'] as $item)
                          <li class="mb-3" style="font-size: 14px; color: #666; display: flex; align-items: flex-start;">
                            <i class="fa-solid fa-check text-theme me-2 mt-1"></i> 
                            <span>{{ $item }}</span>
                          </li>
                        @endforeach
                      @endif
                    </ul>
                    @if(isset($amenityGroup['category']) && $amenityGroup['category'] === 'GENERAL AMENITIES OF THE COMPLEX')
                      <style>
                        @media (max-width: 991px) {
                          .amenity-card .list-unstyled { column-count: 1 !important; }
                        }
                      </style>
                    @endif
                  </div>
                </div>
              @endforeach
            @else
              <div class="col-12 text-center text-muted">No amenities added yet.</div>
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
