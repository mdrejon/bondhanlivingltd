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
                <p>{!! $slider->description !!}</p>
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
          @if(isset($servicesByCategory) && count($servicesByCategory) > 0)
            @php $i = 0; @endphp
            @foreach($servicesByCategory as $category => $services)
            <button
              class="nav-link th-btn {{ $i == 0 ? 'active' : '' }}"
              id="nav-step{{ $i }}-tab"
              data-bs-toggle="tab"
              data-bs-target="#nav-step{{ $i }}"
              type="button"
            >
              <img src="{{ asset('assets/img/update2/icon/ser_icon_' . ($i % 3 + 1) . '.svg') }}" alt="" />{{ $category ?: 'Other' }}
            </button>
            @php $i++; @endphp
            @endforeach
          @endif
        </div>
        <div class="tab-content" id="nav-tabContent">
          @if(isset($servicesByCategory) && count($servicesByCategory) > 0)
            @php $j = 0; @endphp
            @foreach($servicesByCategory as $category => $services)
            <div class="tab-pane fade {{ $j == 0 ? 'active show' : '' }}" id="nav-step{{ $j }}" role="tabpanel">
              <div
                class="row slider-shadow th-carousel"
                data-slide-show="3"
                data-lg-slide-show="2"
                data-md-slide-show="2"
                data-xs-slide-show="1"
                data-arrows="true"
              >
                @foreach($services as $index => $service)
                <div class="col-md-6 col-lg-4">
                  <div
                    class="service-box"
                    data-bg-src="{{ asset('assets/img/update2/bg/shape_bg_1.png') }}"
                  >
                    <div class="service-content">
                      <div class="service-box_icon">
                        <img
                          src="{{ getImgUrl($service->icon, 'assets/img/update2/icon/service_1_' . ($index % 6 + 1) . '.svg') }}"
                          alt="icon"
                          style="max-width: 50px; max-height: 50px;"
                        />
                      </div>
                      <div class="service-box_number">{{ sprintf('%02d', $index + 1) }}</div>
                    </div>
                    <h3 class="box-title">
                      <a href="{{ route('services.show', $service->slug) }}">{{ $service->title }}</a>
                    </h3>
                    <p class="service-box_text">
                      {{ \Illuminate\Support\Str::limit($service->short_description, 100) }}
                    </p>
                    <a class="line-btn" href="{{ route('services.show', $service->slug) }}"
                      >Read More <i class="fas fa-arrow-right ms-2"></i
                    ></a>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
            @php $j++; @endphp
            @endforeach
          @endif
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
          @if(isset($teams) && $teams->count() > 0)
            @foreach($teams as $team)
            <div class="col-md-6 col-lg-4 col-xl-3">
              <div class="th-team team-grid">
                <div class="team-img">
                  <img src="{{ getImgUrl($team->image, 'assets/img/update1/team/team_3_1.jpg') }}" alt="{{ $team->name }}" />
                  <div class="team-social">
                    <button class="play-btn"><i class="fal fa-plus"></i></button>
                    <div class="th-social">
                      @if($team->facebook)
                      <a target="_blank" href="{{ $team->facebook }}"><i class="fab fa-facebook-f"></i></a>
                      @endif
                      @if($team->twitter)
                      <a target="_blank" href="{{ $team->twitter }}"><i class="fab fa-twitter"></i></a>
                      @endif
                      @if($team->linkedin)
                      <a target="_blank" href="{{ $team->linkedin }}"><i class="fab fa-linkedin-in"></i></a>
                      @endif
                      @if($team->instagram)
                      <a target="_blank" href="{{ $team->instagram }}"><i class="fab fa-instagram"></i></a>
                      @endif
                    </div>
                  </div>
                </div>
                <div class="team-content">
                  <h3 class="team-title box-title">
                    <a href="#">{{ $team->name }}</a>
                  </h3>
                  <span class="team-desig">{{ $team->designation }}</span>
                </div>
              </div>
            </div>
            @endforeach
          @endif
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
          @if(isset($projects) && $projects->count() > 0)
            @foreach($projects as $project)
            <div class="col-md-6 col-lg-4">
              <div class="project-block">
                <div class="project-img">
                  <img
                    src="{{ getImgUrl($project->thumbnail, 'assets/img/update1//project/project_4_1.jpg') }}"
                    alt="{{ $project->title }}"
                  />
                </div>
                <div class="project-content">
                  <div class="media-body">
                    <p class="project-subtitle">{{ $project->category ?? 'Constructions' }}</p>
                    <h3 class="project-title">
                      <a href="{{ route('projects.show', $project->slug) }}">{{ $project->title }}</a>
                    </h3>
                  </div>
                  <a href="{{ route('projects.show', $project->slug) }}" class="project-icon"
                    ><i class="far fa-arrow-right"></i
                  ></a>
                </div>
              </div>
            </div>
            @endforeach
          @else
            <div class="col-12 text-center text-white">
              <p>No projects found.</p>
            </div>
          @endif
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
          @if(isset($testimonials) && $testimonials->count() > 0)
            @foreach($testimonials as $testimonial)
            <div class="col-lg-6">
              <div class="testi-grid">
                <p class="testi-grid_text">
                  â€œ{{ $testimonial->text }}â€ 
                </p>
                <div class="testi-grid_author">
                  <div class="testi-grid_avater">
                    <img
                      src="{{ getImgUrl($testimonial->image, 'assets/img/update1/testimonial/testi_1_1.jpg') }}"
                      alt="Avater"
                    />
                  </div>
                  <div>
                    <div class="testi-grid_review">
                      @for($i = 0; $i < $testimonial->rating; $i++)
                        <i class="fa-solid fa-star-sharp"></i>
                      @endfor
                      @for($i = $testimonial->rating; $i < 5; $i++)
                        <i class="fa-regular fa-star-sharp text-muted"></i>
                      @endfor
                    </div>
                    <h3 class="testi-grid_name">{{ $testimonial->name }}</h3>
                    <span class="testi-grid_desig">{{ $testimonial->designation }}</span>
                  </div>
                </div>
                <div class="testi-grid_quote">
                  <img src="assets/img/update1/icon/quote_2.svg" alt="icon" />
                </div>
              </div>
            </div>
            @endforeach
          @endif
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
          @if(isset($blogs) && $blogs->count() > 0)
            @foreach($blogs as $blog)
            <div class="col-md-6 col-xl-4">
              <div class="th-blog blog-single style4 th-ani">
                <div class="blog-img">
                  <a href="{{ route('blog') }}"
                    ><img
                      src="{{ getImgUrl($blog->image, 'assets/img/update2/blog/blog_1_1.jpg') }}"
                      alt="{{ $blog->title }}"
                  /></a>
                  <div class="blog-date">
                    <span class="date">{{ optional($blog->published_at)->format('d') ?? '01' }}</span> {{ optional($blog->published_at)->format('M, Y') ?? 'Jan, 2024' }}
                  </div>
                  <div class="blog-shape"></div>
                </div>
                <div class="blog-content">
                  <div class="blog-meta">
                    <a href="{{ route('blog') }}"
                      ><i class="fa-solid fa-user"></i>By {{ $blog->author }}</a
                    >
                    <a class="author" href="{{ route('blog') }}"
                      ><i class="fa-solid fa-tags"></i>{{ $blog->category }}</a
                    >
                  </div>
                  <h4 class="box-title">
                    <a href="{{ route('blog') }}"
                      >{{ $blog->title }}</a
                    >
                  </h4>
                  <a href="{{ route('blog') }}" class="link-btn style2"
                    >Read More <i class="fas fa-arrow-right ms-1"></i
                  ></a>
                </div>
              </div>
            </div>
            @endforeach
          @endif
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


@endsection
