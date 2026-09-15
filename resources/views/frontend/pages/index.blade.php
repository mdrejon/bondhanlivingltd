@extends('frontend.layouts.app')

@php
    function getImgUrl($path, $default) {
        if (empty($path)) return asset($default);
        if (str_starts_with($path, 'assets/')) return asset($path);
        return asset('storage/' . $path);
    }
@endphp

@if(isset($homeContent['home_page_seo']))
    @section('title', $homeContent['home_page_seo']['meta_title'] ?? 'Bondhan Living Ltd - Home')
    @section('meta_description', $homeContent['home_page_seo']['meta_description'] ?? '')
    @section('meta_keywords', $homeContent['home_page_seo']['meta_keywords'] ?? '')
    @section('meta_author', $homeContent['home_page_seo']['meta_author'] ?? '')
@endif

@section('content')
<!-- 
    <div class="th-hero-wrapper hero-6" id="hero">
      <div class="th-hero-slide">
        <div
          class="th-hero-bg"
          data-bg-src="assets/img/update1/hero/hero_bg_4_1.jpg"
        >
          <img src="assets/img/update1/hero/hero_overlay_4.png" alt="overlay" />
        </div>
        <div class="container z-index-common">
          <div class="hero-style6">
            <span class="hero-subtitle"
              ><span>CONSTRUCTION BUSIBNESS</span></span
            >
            <h1 class="hero-title">Building Tomorrows<br />World Today.</h1>
            <p class="hero-text">
              Authoritatively unleash cross-media collaboration and idea-sharing
              after standards compliant action items. Compellingly enhance
              backend materials and low-risk high-yield ideas.
            </p>
            <div class="btn-group">
              <a href="about.html" class="th-btn style3 style-new"
                >Discover more<i class="fas fa-long-arrow-right ms-2"></i
              ></a>
              <a
                href="https://www.youtube.com/watch?v=_sI_Ps7JSEk"
                class="call-btn style-video popup-video"
                ><div class="play-btn style3"><i class="fas fa-play"></i></div>
                <div class="btn-content">
                  <p class="btn-title">Watch Our Story</p>
                  <span class="btn-text">Subscribe Now</span>
                </div></a
              >
            </div>
          </div>
        </div>
      </div>
      <div class="hero-img">
        <img src="assets/img/update1/hero/hero_4_1.png" alt="Hero Image" />
      </div>
    </div>
    -->

    <!-- Stat Hero Slider Area -->
    <div class="hero-slider-area hero-slider owl-carousel owl-theme">
      @foreach($sliders as $index => $slider)
      <div class="hero-slider-item bg-{{ ($index % 2) + 1 }}" @if($slider->background_image) style="background-image: url('{{ asset('storage/' . $slider->background_image) }}')" @endif>
        <div class="d-table">
          <div class="d-table-cell">
            <div class="container-fluid">
              <div class="hero-slider-content {{ $index === 0 ? 'one' : 'two' }}">
                <h1>{!! $slider->title !!}</h1>
                
                @if($slider->description)
                <p>{{ $slider->description }}</p>
                @endif

                @if($slider->button_text)
                <div class="hero-slider-btn">
                  <a href="{{ $slider->button_url ?? '#' }}" class="th-btn style3 style-new">
                    {{ $slider->button_text }}
                  </a>
                </div>
                @endif
              </div>
            </div>
          </div>
        </div>
        @if($slider->label)
        <span class="border-text">{{ $slider->label }}</span>
        @endif
      </div>
      @endforeach
    </div>
    <!-- End Hero Slider Area -->
     
    @if(isset($homeContent['home_page_why_choose_us']))
    <div class="why-sec-v2 overflow-hidden space">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-xl-6">
            <div class="wcu-img-2 tilt-active mb-50 mb-xl-0 me-xl-4">
              <img src="{{ getImgUrl($homeContent['home_page_why_choose_us']['image'] ?? null, 'assets/img/normal/wcu_2_1.png') }}" alt="img" />
              <div class="wcu-experience-wrap movingX">
                <span>SINCE</span>{{ $homeContent['home_page_why_choose_us']['since_year'] ?? '1998' }}
              </div>
            </div>
          </div>
          <div class="col-xl-6">
            <div class="wcu-wrap2 ms-xxl-5">
              <div class="title-area mb-xl-5">
                <span class="sub-title2"
                  ><img
                    class="me-1"
                    src="{{ asset('assets/img/icon/subtitle-img2-1.svg') }}"
                    alt="img"
                  />{{ $homeContent['home_page_why_choose_us']['subtitle'] ?? 'Why Choose Us' }}</span
                >
                <h2 class="sec-title">
                  {{ $homeContent['home_page_why_choose_us']['title'] ?? '' }}
                </h2>
                <p class="sec-text">
                  {{ $homeContent['home_page_why_choose_us']['description'] ?? '' }}
                </p>
              </div>
              <div class="row g-4">
                @if(isset($homeContent['home_page_why_choose_us']['features']) && is_array($homeContent['home_page_why_choose_us']['features']))
                  @foreach($homeContent['home_page_why_choose_us']['features'] as $feature)
                  <div class="col-sm-6">
                    <div class="wcu-box style2">
                      <div class="wcu-box_icon">
                        <img src="{{ getImgUrl($feature['icon'] ?? null, 'assets/img/icon/house-check.svg') }}" alt="img" />
                      </div>
                      <div class="wcu-box_details">
                        <h3 class="h5 wcu-box_title">
                          <a href="{{ $feature['url'] ?? '#' }}">{{ $feature['title'] ?? '' }}</a>
                        </h3>
                        <p class="wcu-box_text">
                          {{ $feature['text'] ?? '' }}
                        </p>
                      </div>
                    </div>
                  </div>
                  @endforeach
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif
    <section
      class="service-area12 overflow-hidden space overflow-hidden"
      id="service-sec"
      data-bg-src="{{ getImgUrl($homeContent['home_page_service']['bg_image'] ?? null, 'assets/img/update2/bg/service_bg_1.jpg') }}"
    >
      <div class="container">
        <div class="row">
          <div class="title-area mb-0 text-center">
            <span class="sub-title9 justify-content-center">{{ $homeContent['home_page_service']['subtitle'] ?? 'Our Services' }}</span>
            <h2 class="sec-title">{{ $homeContent['home_page_service']['title'] ?? 'The Best Service For You' }}</h2>
          </div>
        </div>
        <div class="nav nav-tabs service-tabs" id="nav-tab" role="tablist">
          <button
            class="nav-link th-btn active"
            id="nav-step1-tab"
            data-bs-toggle="tab"
            data-bs-target="#nav-step1"
            type="button"
          >
            <img
              src="assets/img/update2/icon/ser_icon_1.svg"
              alt=""
            />Commercial
          </button>
          <button
            class="nav-link th-btn"
            id="nav-step2-tab"
            data-bs-toggle="tab"
            data-bs-target="#nav-step2"
            type="button"
          >
            <img
              src="assets/img/update2/icon/ser_icon_2.svg"
              alt=""
            />Residential
          </button>
          <button
            class="nav-link th-btn"
            id="nav-step3-tab"
            data-bs-toggle="tab"
            data-bs-target="#nav-step3"
            type="button"
          >
            <img
              src="assets/img/update2/icon/ser_icon_3.svg"
              alt=""
            />Industrial
          </button>
        </div>
        <div class="tab-content" id="nav-tabContent">
          <div class="tab-pane fade active show" id="nav-step1" role="tabpanel">
            <div
              class="row slider-shadow th-carousel"
              data-slide-show="3"
              data-lg-slide-show="2"
              data-md-slide-show="2"
              data-xs-slide-show="1"
              data-arrows="true"
            >
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_1.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">01</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Building Construction</a>
                  </h3>
                  <p class="service-box_text">
                    Certainly, I can provide some general details about the
                    construction industry. If you have a specific aspect.
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_2.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">02</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Interior Designing</a>
                  </h3>
                  <p class="service-box_text">
                    Construction services also encompass renovating and existing
                    structures to update them change layout.
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_3.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">03</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">General Contracting</a>
                  </h3>
                  <p class="service-box_text">
                    This includes building homes, apartments, and housing units.
                    It can involve single-family homes and more.
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_4.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">04</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Architecture Design</a>
                  </h3>
                  <p class="service-box_text">
                    Architectural wonders await firms offer project Our
                    management services. Along with design architecture firms
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_5.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">05</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">House Renovation</a>
                  </h3>
                  <p class="service-box_text">
                    Where ideas take shape architecture often offer project in
                    Our management services used in and around a home
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_6.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">06</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Material Supply</a>
                  </h3>
                  <p class="service-box_text">
                    Material supply can refer to the process of providing and
                    delivering various materials needed for a project
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_1.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">07</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">General Contracting</a>
                  </h3>
                  <p class="service-box_text">
                    This includes building homes, apartments, and housing units.
                    It can involve single-family homes and more.
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_2.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">08</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Architecture Design</a>
                  </h3>
                  <p class="service-box_text">
                    Architectural wonders await firms offer project Our
                    management services. Along with design architecture firms
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_3.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">09</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">House Renovation</a>
                  </h3>
                  <p class="service-box_text">
                    Where ideas take shape architecture often offer project in
                    Our management services used in and around a home
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_4.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">10</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Material Supply</a>
                  </h3>
                  <p class="service-box_text">
                    Material supply can refer to the process of providing and
                    delivering various materials needed for a project
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="nav-step2" role="tabpanel">
            <div
              class="row slider-shadow th-carousel"
              data-slide-show="3"
              data-lg-slide-show="2"
              data-md-slide-show="2"
              data-xs-slide-show="1"
              data-arrows="true"
            >
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_1.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">01</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Building Construction</a>
                  </h3>
                  <p class="service-box_text">
                    Certainly, I can provide some general details about the
                    construction industry. If you have a specific aspect.
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_2.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">02</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Interior Designing</a>
                  </h3>
                  <p class="service-box_text">
                    Construction services also encompass renovating and existing
                    structures to update them change layout.
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_3.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">03</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">General Contracting</a>
                  </h3>
                  <p class="service-box_text">
                    This includes building homes, apartments, and housing units.
                    It can involve single-family homes and more.
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_4.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">04</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Architecture Design</a>
                  </h3>
                  <p class="service-box_text">
                    Architectural wonders await firms offer project Our
                    management services. Along with design architecture firms
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_5.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">05</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">House Renovation</a>
                  </h3>
                  <p class="service-box_text">
                    Where ideas take shape architecture often offer project in
                    Our management services used in and around a home
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_6.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">06</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Material Supply</a>
                  </h3>
                  <p class="service-box_text">
                    Material supply can refer to the process of providing and
                    delivering various materials needed for a project
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_1.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">07</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">General Contracting</a>
                  </h3>
                  <p class="service-box_text">
                    This includes building homes, apartments, and housing units.
                    It can involve single-family homes and more.
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_2.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">08</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Architecture Design</a>
                  </h3>
                  <p class="service-box_text">
                    Architectural wonders await firms offer project Our
                    management services. Along with design architecture firms
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_3.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">09</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">House Renovation</a>
                  </h3>
                  <p class="service-box_text">
                    Where ideas take shape architecture often offer project in
                    Our management services used in and around a home
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_4.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">10</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Material Supply</a>
                  </h3>
                  <p class="service-box_text">
                    Material supply can refer to the process of providing and
                    delivering various materials needed for a project
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
            </div>
          </div>
          <div class="tab-pane fade" id="nav-step3" role="tabpanel">
            <div
              class="row slider-shadow th-carousel"
              data-slide-show="3"
              data-lg-slide-show="2"
              data-md-slide-show="2"
              data-xs-slide-show="1"
              data-arrows="true"
            >
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_1.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">01</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Building Construction</a>
                  </h3>
                  <p class="service-box_text">
                    Certainly, I can provide some general details about the
                    construction industry. If you have a specific aspect.
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_2.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">02</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Interior Designing</a>
                  </h3>
                  <p class="service-box_text">
                    Construction services also encompass renovating and existing
                    structures to update them change layout.
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_3.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">03</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">General Contracting</a>
                  </h3>
                  <p class="service-box_text">
                    This includes building homes, apartments, and housing units.
                    It can involve single-family homes and more.
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_4.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">04</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Architecture Design</a>
                  </h3>
                  <p class="service-box_text">
                    Architectural wonders await firms offer project Our
                    management services. Along with design architecture firms
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_5.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">05</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">House Renovation</a>
                  </h3>
                  <p class="service-box_text">
                    Where ideas take shape architecture often offer project in
                    Our management services used in and around a home
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_6.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">06</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Material Supply</a>
                  </h3>
                  <p class="service-box_text">
                    Material supply can refer to the process of providing and
                    delivering various materials needed for a project
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_1.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">07</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">General Contracting</a>
                  </h3>
                  <p class="service-box_text">
                    This includes building homes, apartments, and housing units.
                    It can involve single-family homes and more.
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_2.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">08</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Architecture Design</a>
                  </h3>
                  <p class="service-box_text">
                    Architectural wonders await firms offer project Our
                    management services. Along with design architecture firms
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_3.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">09</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">House Renovation</a>
                  </h3>
                  <p class="service-box_text">
                    Where ideas take shape architecture often offer project in
                    Our management services used in and around a home
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
              <div class="col-md-6 col-lg-4">
                <div
                  class="service-box"
                  data-bg-src="assets/img/update2/bg/shape_bg_1.png"
                >
                  <div class="service-content">
                    <div class="service-box_icon">
                      <img
                        src="assets/img/update2/icon/service_1_4.svg"
                        alt="icon"
                      />
                    </div>
                    <div class="service-box_number">10</div>
                  </div>
                  <h3 class="box-title">
                    <a href="service-details.html">Material Supply</a>
                  </h3>
                  <p class="service-box_text">
                    Material supply can refer to the process of providing and
                    delivering various materials needed for a project
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section
      class="space-extra"
      data-bg-src="{{ getImgUrl($homeContent['home_page_cta']['bg_image'] ?? null, 'assets/img/update1/bg/cta_bg_1.jpg') }}"
    >
      <div class="container">
        <div
          class="row align-items-center justify-content-center justify-content-lg-between"
        >
          <div
            class="col-lg-8 col-md-10 mb-4 mb-lg-0 text-center text-lg-start"
          >
            <h2 class="mb-0 text-white">
              {{ $homeContent['home_page_cta']['title'] ?? 'Have Any Question For Project Plan In Your Mind?' }}
            </h2>
          </div>
          <div class="col-lg-auto text-center text-lg-start">
            <a href="{{ $homeContent['home_page_cta']['button_url'] ?? 'course.html' }}" class="th-btn style6 style-new"
              >{{ $homeContent['home_page_cta']['button_text'] ?? 'GET IN TOUCH' }}<i class="fas fa-arrow-right ms-2"></i
            ></a>
          </div>
        </div>
      </div>
    </section>
    <section
      class="space bg-auto"
      data-bg-src="{{ getImgUrl($homeContent['home_page_team']['bg_image'] ?? null, 'assets/img/update1/bg/team_bg_3.png') }}"
      id="team-sec"
    >
      <div class="container">
        <div class="title-area text-center">
          <span class="sub-title6 justify-content-center"
            ><span class="shape left"><span class="dots"></span></span> {{ $homeContent['home_page_team']['subtitle'] ?? 'Team Members' }}
             <span class="shape right"><span class="dots"></span></span
          ></span>
          <h2 class="sec-title">{{ $homeContent['home_page_team']['title'] ?? 'Our Professional Team' }}</h2>
        </div>
        <div
          class="row slider-shadow th-carousel"
          data-slide-show="4"
          data-ml-slide-show="4"
          data-lg-slide-show="3"
          data-md-slide-show="2"
          data-sm-slide-show="2"
          data-arrows="true"
        >
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="th-team team-grid">
              <div class="team-img">
                <img src="assets/img/update1/team/team_3_1.jpg" alt="Team" />
                <div class="team-social">
                  <button class="play-btn"><i class="fal fa-plus"></i></button>
                  <div class="th-social">
                    <a target="_blank" href="https://facebook.com/"
                      ><i class="fab fa-facebook-f"></i
                    ></a>
                    <a target="_blank" href="https://twitter.com/"
                      ><i class="fab fa-twitter"></i
                    ></a>
                    <a target="_blank" href="https://linkedin.com/"
                      ><i class="fab fa-linkedin-in"></i
                    ></a>
                  </div>
                </div>
              </div>
              <div class="team-content">
                <h3 class="team-title box-title">
                  <a href="team-details.html">Mishel Marsh</a>
                </h3>
                <span class="team-desig">Founder</span>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="th-team team-grid">
              <div class="team-img">
                <img src="assets/img/update1/team/team_3_2.jpg" alt="Team" />
                <div class="team-social">
                  <button class="play-btn"><i class="fal fa-plus"></i></button>
                  <div class="th-social">
                    <a target="_blank" href="https://facebook.com/"
                      ><i class="fab fa-facebook-f"></i
                    ></a>
                    <a target="_blank" href="https://twitter.com/"
                      ><i class="fab fa-twitter"></i
                    ></a>
                    <a target="_blank" href="https://linkedin.com/"
                      ><i class="fab fa-linkedin-in"></i
                    ></a>
                  </div>
                </div>
              </div>
              <div class="team-content">
                <h3 class="team-title box-title">
                  <a href="team-details.html">Michel Richard</a>
                </h3>
                <span class="team-desig">Architecture</span>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="th-team team-grid">
              <div class="team-img">
                <img src="assets/img/update1/team/team_3_3.jpg" alt="Team" />
                <div class="team-social">
                  <button class="play-btn"><i class="fal fa-plus"></i></button>
                  <div class="th-social">
                    <a target="_blank" href="https://facebook.com/"
                      ><i class="fab fa-facebook-f"></i
                    ></a>
                    <a target="_blank" href="https://twitter.com/"
                      ><i class="fab fa-twitter"></i
                    ></a>
                    <a target="_blank" href="https://linkedin.com/"
                      ><i class="fab fa-linkedin-in"></i
                    ></a>
                  </div>
                </div>
              </div>
              <div class="team-content">
                <h3 class="team-title box-title">
                  <a href="team-details.html">Famhida Ruko</a>
                </h3>
                <span class="team-desig">Engineer</span>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="th-team team-grid">
              <div class="team-img">
                <img src="assets/img/update1/team/team_3_4.jpg" alt="Team" />
                <div class="team-social">
                  <button class="play-btn"><i class="fal fa-plus"></i></button>
                  <div class="th-social">
                    <a target="_blank" href="https://facebook.com/"
                      ><i class="fab fa-facebook-f"></i
                    ></a>
                    <a target="_blank" href="https://twitter.com/"
                      ><i class="fab fa-twitter"></i
                    ></a>
                    <a target="_blank" href="https://linkedin.com/"
                      ><i class="fab fa-linkedin-in"></i
                    ></a>
                  </div>
                </div>
              </div>
              <div class="team-content">
                <h3 class="team-title box-title">
                  <a href="team-details.html">Alex Anfantino</a>
                </h3>
                <span class="team-desig">Site Manager</span>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 col-xl-3">
            <div class="th-team team-grid">
              <div class="team-img">
                <img src="assets/img/update1/team/team_3_5.jpg" alt="Team" />
                <div class="team-social">
                  <button class="play-btn"><i class="fal fa-plus"></i></button>
                  <div class="th-social">
                    <a target="_blank" href="https://facebook.com/"
                      ><i class="fab fa-facebook-f"></i
                    ></a>
                    <a target="_blank" href="https://twitter.com/"
                      ><i class="fab fa-twitter"></i
                    ></a>
                    <a target="_blank" href="https://linkedin.com/"
                      ><i class="fab fa-linkedin-in"></i
                    ></a>
                  </div>
                </div>
              </div>
              <div class="team-content">
                <h3 class="team-title box-title">
                  <a href="team-details.html">Jackline Farah</a>
                </h3>
                <span class="team-desig">Engineer</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section
      class="space-top overflow-hidden bg-top-center"
      data-bg-src="{{ getImgUrl($homeContent['home_page_project']['bg_image'] ?? null, 'assets/img/update1/bg/project_bg_3.jpg') }}"
    >
      <div class="container">
        <div
          class="row text-center text-lg-start justify-content-lg-between align-items-end"
        >
          <div class="col-lg-6 mb-n2 mb-lg-0">
            <div class="title-area">
              <span
                class="sub-title6 text-theme justify-content-lg-start justify-content-center"
                ><span class="shape left d-lg-none"
                  ><span class="dots"></span
                ></span>
                {{ $homeContent['home_page_project']['subtitle'] ?? 'CONSTRUCT PROJECTS' }}
                <span class="shape right"><span class="dots"></span></span
              ></span>
              <h2 class="sec-title text-white">{{ $homeContent['home_page_project']['title'] ?? 'Our Recent Projects' }}</h2>
            </div>
          </div>
          <div class="col-lg-auto">
            <div class="sec-btn">
              <div
                class="icon-box style2 justify-content-lg-end justify-content-center"
              >
                <button
                  data-slick-prev="#projectSlide4"
                  class="slick-arrow default"
                >
                  <i class="far fa-arrow-left"></i>
                </button>
                <button
                  data-slick-next="#projectSlide4"
                  class="slick-arrow default"
                >
                  <i class="far fa-arrow-right"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="th-container5">
        <div
          class="row slider-shadow th-carousel"
          id="projectSlide4"
          data-slide-show="3"
          data-md-slide-show="2"
          data-sm-slide-show="1"
        >
          <div class="col-md-6 col-lg-4">
            <div class="project-block">
              <div class="project-img">
                <img
                  src="assets/img/update1//project/project_4_1.jpg"
                  alt="project image"
                />
              </div>
              <div class="project-content">
                <div class="media-body">
                  <p class="project-subtitle">Constructions</p>
                  <h3 class="project-title">
                    <a href="project-details.html">Contemporary Villa</a>
                  </h3>
                </div>
                <a href="project-details.html" class="project-icon"
                  ><i class="far fa-arrow-right"></i
                ></a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="project-block">
              <div class="project-img">
                <img
                  src="assets/img/update1//project/project_4_2.jpg"
                  alt="project image"
                />
              </div>
              <div class="project-content">
                <div class="media-body">
                  <p class="project-subtitle">Constructions</p>
                  <h3 class="project-title">
                    <a href="project-details.html">Bridge Trangle Core</a>
                  </h3>
                </div>
                <a href="project-details.html" class="project-icon"
                  ><i class="far fa-arrow-right"></i
                ></a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="project-block">
              <div class="project-img">
                <img
                  src="assets/img/update1//project/project_4_3.jpg"
                  alt="project image"
                />
              </div>
              <div class="project-content">
                <div class="media-body">
                  <p class="project-subtitle">Constructions</p>
                  <h3 class="project-title">
                    <a href="project-details.html">Rowson Construction</a>
                  </h3>
                </div>
                <a href="project-details.html" class="project-icon"
                  ><i class="far fa-arrow-right"></i
                ></a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="project-block">
              <div class="project-img">
                <img
                  src="assets/img/update1//project/project_4_4.jpg"
                  alt="project image"
                />
              </div>
              <div class="project-content">
                <div class="media-body">
                  <p class="project-subtitle">Constructions</p>
                  <h3 class="project-title">
                    <a href="project-details.html">Interior Decoration</a>
                  </h3>
                </div>
                <a href="project-details.html" class="project-icon"
                  ><i class="far fa-arrow-right"></i
                ></a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-4">
            <div class="project-block">
              <div class="project-img">
                <img
                  src="assets/img/update1//project/project_4_5.jpg"
                  alt="project image"
                />
              </div>
              <div class="project-content">
                <div class="media-body">
                  <p class="project-subtitle">Constructions</p>
                  <h3 class="project-title">
                    <a href="project-details.html">Construction Planning</a>
                  </h3>
                </div>
                <a href="project-details.html" class="project-icon"
                  ><i class="far fa-arrow-right"></i
                ></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="space" data-bg-src="{{ getImgUrl($homeContent['home_page_achievements']['bg_image'] ?? null, 'assets/img/update1/bg/achive_bg_1.jpg') }}">
      <div class="container">
        <div class="row">
          <div class="col-xl-6">
            <div class="title-area mb-35 text-center text-xl-start">
              <span
                class="sub-title6 justify-content-xl-start justify-content-center"
                ><span class="shape left d-xl-none"
                  ><span class="dots"></span
                ></span>
                {{ $homeContent['home_page_achievements']['subtitle'] ?? 'Achivements' }}
                <span class="shape right"><span class="dots"></span></span
              ></span>
              <h2 class="sec-title">{{ $homeContent['home_page_achievements']['title'] ?? "Let's Start We Are On Building Of Dream" }}</h2>
            </div>
            <p class="mt-n2 mb-35 text-center text-xl-start">
              {{ $homeContent['home_page_achievements']['description'] ?? 'Globally engineer ubiquitous ROI whereas visionary web-readiness. Objectively matrix optimal e-markets vis-a-vis empowered leadership skills. Professionally leadership skills aggregate fully tested.' }}
            </p>
            <div class="achive-counter-wrap">
              @if(isset($homeContent['home_page_achievements']['counters']) && is_array($homeContent['home_page_achievements']['counters']))
                @foreach($homeContent['home_page_achievements']['counters'] as $counter)
                <div class="achive-counter">
                  <div class="achive-counter_icon">
                    <img
                      src="{{ getImgUrl($counter['icon'] ?? null, 'assets/img/update1/icon/achive_1_1.svg') }}"
                      alt="icon"
                    />
                  </div>
                  <h3 class="achive-counter_number">
                    <span class="counter-number">{{ $counter['number'] ?? '' }}</span>{{ $counter['suffix'] ?? '' }}
                  </h3>
                  <p class="achive-counter_text">{{ $counter['text'] ?? '' }}</p>
                </div>
                @endforeach
              @endif
            </div>
          </div>
          <div class="col-xl-6">
            <div class="ps-xl-5 mt-40 mt-xl-0">
              <div class="rounded-20">
                <img
                  class="w-100"
                  src="{{ getImgUrl($homeContent['home_page_achievements']['side_image'] ?? null, 'assets/img/update1/normal/achive_1_1.jpg') }}"
                  alt="Achive"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Testimonials Section -->
    <section class="space" data-bg-src="{{ getImgUrl($homeContent['home_page_testimonial']['bg_image'] ?? null, 'assets/img/update1/bg/testi_bg_4.jpg') }}">
      <div class="container">
        <div class="title-area text-center">
          <span class="sub-title6 text-theme justify-content-center"
            ><span class="shape left"><span class="dots"></span></span>
            {{ $homeContent['home_page_testimonial']['subtitle'] ?? 'Testimonials' }}
            <span class="shape right"><span class="dots"></span></span
          ></span>
          <h2 class="sec-title text-white">{{ $homeContent['home_page_testimonial']['title'] ?? 'What Our Client Say?' }}</h2>
        </div>
      </div>
      <div class="container space-bottom">
        <div
          class="row slider-shadow th-carousel"
          data-slide-show="2"
          data-lg-slide-show="2"
          data-md-slide-show="1"
        >
          <div class="col-lg-6">
            <div class="testi-grid">
              <p class="testi-grid_text">
                â€œEfficiently administrate effective outsourcing before
                process-centric deliverables. Phosfluorescently grow exceptional
                quality vectors and excellent core competency. Objectively mesh
                client-centric interfaces with tactical platforms. Progressively
                benchmark frictionless.â€
              </p>
              <div class="testi-grid_author">
                <div class="testi-grid_avater">
                  <img
                    src="assets/img/update1/testimonial/testi_1_1.jpg"
                    alt="Avater"
                  />
                </div>
                <div>
                  <div class="testi-grid_review">
                    <i class="fa-solid fa-star-sharp"></i
                    ><i class="fa-solid fa-star-sharp"></i
                    ><i class="fa-solid fa-star-sharp"></i
                    ><i class="fa-solid fa-star-sharp"></i
                    ><i class="fa-solid fa-star-sharp"></i>
                  </div>
                  <h3 class="testi-grid_name">Mary Cruzleen</h3>
                  <span class="testi-grid_desig">CEO of Maithon</span>
                </div>
              </div>
              <div class="testi-grid_quote">
                <img src="assets/img/update1/icon/quote_2.svg" alt="icon" />
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="testi-grid">
              <p class="testi-grid_text">
                â€œEfficiently administrate effective outsourcing before
                process-centric deliverables. Phosfluorescently grow exceptional
                quality vectors and excellent core competency. Objectively mesh
                client-centric interfaces with tactical platforms. Progressively
                benchmark frictionless.â€
              </p>
              <div class="testi-grid_author">
                <div class="testi-grid_avater">
                  <img
                    src="assets/img/update1/testimonial/testi_1_2.jpg"
                    alt="Avater"
                  />
                </div>
                <div>
                  <div class="testi-grid_review">
                    <i class="fa-solid fa-star-sharp"></i
                    ><i class="fa-solid fa-star-sharp"></i
                    ><i class="fa-solid fa-star-sharp"></i
                    ><i class="fa-solid fa-star-sharp"></i
                    ><i class="fa-solid fa-star-sharp"></i>
                  </div>
                  <h3 class="testi-grid_name">David Milton</h3>
                  <span class="testi-grid_desig">CEO of Goston</span>
                </div>
              </div>
              <div class="testi-grid_quote">
                <img src="assets/img/update1/icon/quote_2.svg" alt="icon" />
              </div>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="testi-grid">
              <p class="testi-grid_text">
                â€œEfficiently administrate effective outsourcing before
                process-centric deliverables. Phosfluorescently grow exceptional
                quality vectors and excellent core competency. Objectively mesh
                client-centric interfaces with tactical platforms. Progressively
                benchmark frictionless.â€
              </p>
              <div class="testi-grid_author">
                <div class="testi-grid_avater">
                  <img
                    src="assets/img/update1/testimonial/testi_1_3.jpg"
                    alt="Avater"
                  />
                </div>
                <div>
                  <div class="testi-grid_review">
                    <i class="fa-solid fa-star-sharp"></i
                    ><i class="fa-solid fa-star-sharp"></i
                    ><i class="fa-solid fa-star-sharp"></i
                    ><i class="fa-solid fa-star-sharp"></i
                    ><i class="fa-solid fa-star-sharp"></i>
                  </div>
                  <h3 class="testi-grid_name">Abraham Khalil</h3>
                  <span class="testi-grid_desig">CEO of Gogonti</span>
                </div>
              </div>
              <div class="testi-grid_quote">
                <img src="assets/img/update1/icon/quote_2.svg" alt="icon" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Testimonials section -->

    <!-- Blog Section -->
    <section class="blog-area-4 space" id="blog-sec">
      <div class="container">
        <div class="text-center text-md-start">
          <div class="row align-items-center justify-content-center">
            <div class="col-md-7">
              <div class="title-area text-center">
                <span class="sub-title9 justify-content-center"
                  >{{ $homeContent['home_page_blog']['subtitle'] ?? 'Latest Blog' }}</span
                >
                <h2 class="sec-title">
                  {{ $homeContent['home_page_blog']['title'] ?? 'Our Latest Construction News Blog & Articles' }}
                </h2>
              </div>
            </div>
          </div>
        </div>
        <div
          class="row th-carousel slider-shadow arrow-style8"
          data-slide-show="3"
          data-lg-slide-show="2"
          data-md-slide-show="2"
          data-sm-slide-show="1"
          data-arrows="true"
        >
          <div class="col-md-6 col-xl-4">
            <div class="th-blog blog-single style4 th-ani">
              <div class="blog-img">
                <a href="blog-details.html"
                  ><img
                    src="assets/img/update2/blog/blog_1_1.jpg"
                    alt="Blog Image"
                /></a>
                <div class="blog-date">
                  <span class="date">05</span> Jul, 2024
                </div>
                <div class="blog-shape"></div>
              </div>
              <div class="blog-content">
                <div class="blog-meta">
                  <a href="blog.html"
                    ><i class="fa-solid fa-user"></i>By Bondhon</a
                  >
                  <a class="author" href="blog.html"
                    ><i class="fa-solid fa-tags"></i>INDUSTRY</a
                  >
                </div>
                <h4 class="box-title">
                  <a href="blog-details.html"
                    >The beast team around and how we make it work</a
                  >
                </h4>
                <a href="blog-details.html" class="link-btn style2"
                  >Read More <i class="fas fa-arrow-right ms-1"></i
                ></a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="th-blog blog-single style4 th-ani">
              <div class="blog-img">
                <a href="blog-details.html"
                  ><img
                    src="assets/img/update2/blog/blog_1_2.jpg"
                    alt="Blog Image"
                /></a>
                <div class="blog-date">
                  <span class="date">06</span> Jul, 2024
                </div>
                <div class="blog-shape"></div>
              </div>
              <div class="blog-content">
                <div class="blog-meta">
                  <a href="blog.html"
                    ><i class="fa-solid fa-user"></i>By Bondhon</a
                  >
                  <a class="author" href="blog.html"
                    ><i class="fa-solid fa-tags"></i>INDUSTRY</a
                  >
                </div>
                <h4 class="box-title">
                  <a href="blog-details.html"
                    >Interior design is the Art and science Of Design</a
                  >
                </h4>
                <a href="blog-details.html" class="link-btn style2"
                  >Read More <i class="fas fa-arrow-right ms-1"></i
                ></a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="th-blog blog-single style4 th-ani">
              <div class="blog-img">
                <a href="blog-details.html"
                  ><img
                    src="assets/img/update2/blog/blog_1_3.jpg"
                    alt="Blog Image"
                /></a>
                <div class="blog-date">
                  <span class="date">07</span> Jul, 2024
                </div>
                <div class="blog-shape"></div>
              </div>
              <div class="blog-content">
                <div class="blog-meta">
                  <a href="blog.html"
                    ><i class="fa-solid fa-user"></i>By Bondhon</a
                  >
                  <a class="author" href="blog.html"
                    ><i class="fa-solid fa-tags"></i>INDUSTRY</a
                  >
                </div>
                <h4 class="box-title">
                  <a href="blog-details.html"
                    >Redefining Organizational Dynamics by Embracing</a
                  >
                </h4>
                <a href="blog-details.html" class="link-btn style2"
                  >Read More <i class="fas fa-arrow-right ms-1"></i
                ></a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="th-blog blog-single style4 th-ani">
              <div class="blog-img">
                <a href="blog-details.html"
                  ><img
                    src="assets/img/update2/blog/blog_1_1.jpg"
                    alt="Blog Image"
                /></a>
                <div class="blog-date">
                  <span class="date">09</span> Jul, 2024
                </div>
                <div class="blog-shape"></div>
              </div>
              <div class="blog-content">
                <div class="blog-meta">
                  <a href="blog.html"
                    ><i class="fa-solid fa-user"></i>By Bondhon</a
                  >
                  <a class="author" href="blog.html"
                    ><i class="fa-solid fa-tags"></i>INDUSTRY</a
                  >
                </div>
                <h4 class="box-title">
                  <a href="blog-details.html"
                    >The beast team around and how we make it work</a
                  >
                </h4>
                <a href="blog-details.html" class="link-btn style2"
                  >Read More <i class="fas fa-arrow-right ms-1"></i
                ></a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="th-blog blog-single style4 th-ani">
              <div class="blog-img">
                <a href="blog-details.html"
                  ><img
                    src="assets/img/update2/blog/blog_1_2.jpg"
                    alt="Blog Image"
                /></a>
                <div class="blog-date">
                  <span class="date">10</span> Jul, 2024
                </div>
                <div class="blog-shape"></div>
              </div>
              <div class="blog-content">
                <div class="blog-meta">
                  <a href="blog.html"
                    ><i class="fa-solid fa-user"></i>By Bondhon</a
                  >
                  <a class="author" href="blog.html"
                    ><i class="fa-solid fa-tags"></i>INDUSTRY</a
                  >
                </div>
                <h4 class="box-title">
                  <a href="blog-details.html"
                    >Interior design is the Art and science Of Design</a
                  >
                </h4>
                <a href="blog-details.html" class="link-btn style2"
                  >Read More <i class="fas fa-arrow-right ms-1"></i
                ></a>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-xl-4">
            <div class="th-blog blog-single style4 th-ani">
              <div class="blog-img">
                <a href="blog-details.html"
                  ><img
                    src="assets/img/update2/blog/blog_1_3.jpg"
                    alt="Blog Image"
                /></a>
                <div class="blog-date">
                  <span class="date">12</span> Jul, 2024
                </div>
                <div class="blog-shape"></div>
              </div>
              <div class="blog-content">
                <div class="blog-meta">
                  <a href="blog.html"
                    ><i class="fa-solid fa-user"></i>By Bondhon</a
                  >
                  <a class="author" href="blog.html"
                    ><i class="fa-solid fa-tags"></i>INDUSTRY</a
                  >
                </div>
                <h4 class="box-title">
                  <a href="blog-details.html"
                    >Redefining Organizational Dynamics by Embracing</a
                  >
                </h4>
                <a href="blog-details.html" class="link-btn style2"
                  >Read More <i class="fas fa-arrow-right ms-1"></i
                ></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- Blog Section -->
    <div
      class="th-modal modal fade"
      id="serviceModal"
      tabindex="-1"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <button
            type="button"
            class="icon-btn btn-close"
            data-bs-dismiss="modal"
            aria-label="Close"
          >
            <i class="fa-regular fa-xmark"></i>
          </button>
          <div class="page-single">
            <div class="page-img mb-30">
              <img
                class="w-100"
                src="assets/img/service/service-details-1-1.jpg"
                alt="Service Image"
              />
            </div>
            <div class="page-content">
              <h2 class="h3 page-title">Building Construction</h2>
              <div class="row gy-30">
                <div class="col-xl-7">
                  <p class="mb-20">
                    Globally optimize highly efficient solution whereas
                    open-source application. Completely strategize quality
                    internal or "organic" sources for virtual e-business.
                    Seamlessly restore inexpensive e-markets.
                  </p>
                  <p class="mb-0">
                    Authoritatively scale business meta-services before
                    client-based technologies. Collaboratively strategize
                    synergistic scenarios rather than flexible action items.
                    Continually deliver market positioning convergence and
                    mission-critical infrastructures.
                  </p>
                </div>
                <div class="col-xl-5">
                  <ul class="service-info-list">
                    <li>
                      <strong>Service Category:</strong> Rubix Carabil Tower
                    </li>
                    <li><strong>Clients:</strong> David Malan</li>
                    <li><strong>Project Date:</strong> 13 June, 2020</li>
                    <li><strong>Avenue End Date:</strong> 22 July, 2023</li>
                    <li>
                      <strong>Locations:</strong> NewYork - 2546 Firs, USA
                    </li>
                  </ul>
                </div>
              </div>
              <div class="row gy-30 align-items-center">
                <div class="col-xl-6">
                  <div class="page-img">
                    <img
                      class="w-100"
                      src="assets/img/service/service-details-1-2.jpg"
                      alt="Service Image"
                    />
                  </div>
                </div>
                <div class="col-xl-6">
                  <h4 class="box-title">Services Benefits:</h4>
                  <p>
                    An architecture company thrives on innovation and
                    creativity. Designers explore new materials, technologies,
                    and design trends to deliver fresh and unique solutions.
                  </p>
                  <div class="checklist style7">
                    <ul>
                      <li>We use the latest diagnostic equipment</li>
                      <li>Automotive service our clients receive</li>
                      <li>We are a member of Professional Service</li>
                      <li>Digital how will activities impact traditional</li>
                      <li>Architect and technical engineer</li>
                    </ul>
                  </div>
                </div>
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
