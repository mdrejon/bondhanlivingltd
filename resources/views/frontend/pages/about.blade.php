@extends('frontend.layouts.app')

@php
function getImgUrl($path, $default) {
if (empty($path)) return asset($default);
if (str_starts_with($path, 'assets/')) return asset($path);
return asset('storage/' . $path);
}
@endphp

@if(isset($aboutContent['about_page_seo']))
@section('title', $aboutContent['about_page_seo']['meta_title'] ?? 'Bondhan Living Ltd - About Us')
@section('meta_description', $aboutContent['about_page_seo']['meta_description'] ?? '')
@section('meta_keywords', $aboutContent['about_page_seo']['meta_keywords'] ?? '')
@section('meta_author', $aboutContent['about_page_seo']['meta_author'] ?? '')
@endif

@section('content')
<div class="breadcumb-wrapper" data-bg-src="{{ getImgUrl($aboutContent['about_page_hero']['bg_image'] ?? null, 'assets/img/bg/breadcumb-bg.jpg') }}">
  <div class="container">
    <div class="breadcumb-content">
      <h1 class="breadcumb-title">{{ $aboutContent['about_page_hero']['title'] ?? 'About Us' }}</h1>
      <ul class="breadcumb-menu">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li>{{ $aboutContent['about_page_hero']['title'] ?? 'About Us' }}</li>
      </ul>
    </div>
  </div>
</div>
<div class="overflow-hidden space overflow-hidden" id="about-sec">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-xl-6">
        <div class="img-box1">
          <div class="img1">
            <img
              class="tilt-active"
              src="{{ getImgUrl($aboutContent['about_page_company']['image1'] ?? null, 'assets/img/normal/about_1_1.png') }}"
              alt="About" />
          </div>
          <div class="about-grid">
            <h3 class="about-grid_year">
              <span class="counter-number">{{ $aboutContent['about_page_company']['years_experience'] ?? '25' }}</span><span>+</span>
            </h3>
            <p class="about-grid_text">
              {{ $aboutContent['about_page_company']['years_text'] ?? 'Years Experiences Of Construction Company' }}
            </p>
          </div>
          <div class="img2">
            <img
              class="tilt-active"
              src="{{ getImgUrl($aboutContent['about_page_company']['image2'] ?? null, 'assets/img/normal/about_1_2.png') }}"
              alt="About" />
          </div>
          <div
            class="shape-mockup about-shape1 jump"
            data-left="-67px"
            data-bottom="0">
            <img src="{{ getImgUrl($aboutContent['about_page_company']['shape_image'] ?? null, 'assets/img/normal/about_1_shape1.png') }}" alt="img" />
          </div>
        </div>
      </div>
      <div class="col-xl-6">
        <div class="title-area mb-30">
          <span class="sub-title4">{{ $aboutContent['about_page_company']['subtitle'] ?? 'About Us Company' }}</span>
          <h2 class="sec-title fw-semibold">
            {{ $aboutContent['about_page_company']['title'] ?? 'We Are Always Think On Your Dream' }}
          </h2>
        </div>
        <p class="mt-n2 mb-35">
          {{ $aboutContent['about_page_company']['description'] ?? 'Many modern construction companies focus on sustainable building practices, incorporating eco-friendly material energy-efficient systems and environmental conscious designs to reduce the environmental impact of their projects.' }}
        </p>
        @if(isset($aboutContent['about_page_company']['features']) && is_array($aboutContent['about_page_company']['features']))
        @foreach($aboutContent['about_page_company']['features'] as $feature)
        <div class="about-grid2">
          <div class="icon">
            <img src="{{ getImgUrl($feature['icon'] ?? null, 'assets/img/icon/about-grid-icon1.svg') }}" alt="img" />
          </div>
          <div class="about-grid-details">
            <h3 class="about-grid_title h6">{{ $feature['title'] ?? '' }}</h3>
            <p>
              {{ $feature['text'] ?? '' }}
            </p>
          </div>
        </div>
        @endforeach
        @endif
      </div>
    </div>
  </div>
</div>
<section
  class="z-index-common"
  data-pos-for=".why-sec-v5"
  data-sec-pos="bottom-half">
  <div class="container th-container2">
    <div class="counter-area-4 bg-white bg-shadow">
      <div class="row align-items-center justify-content-between">
        <div class="col-xl-5">
          <div class="title-area mb-xl-0 text-center text-xl-start mb-50">
            <span class="sub-title4">{{ $aboutContent['about_page_achievements']['subtitle'] ?? 'Our Company Achievements' }}</span>
            <h2 class="sec-title fw-semibold">
              {{ $aboutContent['about_page_achievements']['title'] ?? 'Industrial Strength, Global Impact' }}
            </h2>
            <a href="{{ $aboutContent['about_page_achievements']['button_url'] ?? 'contact.html' }}" class="th-btn mt-10">{{ $aboutContent['about_page_achievements']['button_text'] ?? 'Make An Appointment' }}
              <i class="fa-regular fa-arrow-right ms-2"></i></a>
          </div>
        </div>
        <div class="col-xl-7">
          <div class="counter-wrap4">
            <div class="row">
              @if(isset($aboutContent['about_page_achievements']['counters']) && is_array($aboutContent['about_page_achievements']['counters']))
              @foreach($aboutContent['about_page_achievements']['counters'] as $counter)
              <div class="col-sm-6 counter-grid-wrap">
                <div class="counter-grid style4">
                  <div class="counter-grid_icon">
                    <img
                      src="{{ getImgUrl($counter['icon'] ?? null, 'assets/img/icon/counter-icon4-1.svg') }}"
                      alt="img" />
                  </div>
                  <div class="details">
                    <h2 class="counter-grid_number">
                      <span class="counter-number">{{ $counter['number'] ?? '' }}</span>{{ $counter['suffix'] ?? '' }}
                    </h2>
                    <p class="counter-grid_text">{{ $counter['text'] ?? '' }}</p>
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
</section>
<div class="why-sec-v5 overflow-hidden bg-smoke space">
  <div
    class="shape-mockup service2-bg-shape1 jump"
    data-top="0"
    data-left="0">
    <img src="{{ getImgUrl($aboutContent['about_page_mission']['bg_shape'] ?? null, 'assets/img/service/service-bg-shape2-1.png') }}" alt="img" />
  </div>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-9 col-lg-10">
        <div class="title-area text-center">
          <span class="sub-title4">{{ $aboutContent['about_page_mission']['subtitle'] ?? 'Why Choose Us Our Company' }}</span>
          <h2 class="sec-title fw-semibold">
            {{ $aboutContent['about_page_mission']['title'] ?? 'We Help You Build On Your Past And Prepare For The Feature' }}
          </h2>
        </div>
      </div>
    </div>
    <div class="tab-menu1 filter-menu-active">
      @if(isset($aboutContent['about_page_mission']['tabs']) && is_array($aboutContent['about_page_mission']['tabs']))
      @foreach($aboutContent['about_page_mission']['tabs'] as $index => $tab)
      <button data-filter=".cat{{ $index + 1 }}" class="{{ $index === 0 ? 'active' : '' }}" type="button">
        {{ $tab['tab_name'] ?? 'Tab' }}
      </button>
      @endforeach
      @endif
    </div>

    <div class="mission-box-wrap mt-50 filter-active-cat1">
      @if(isset($aboutContent['about_page_mission']['tabs']) && is_array($aboutContent['about_page_mission']['tabs']))
      @foreach($aboutContent['about_page_mission']['tabs'] as $index => $tab)
      <div class="filter-item cat{{ $index + 1 }}">
        <div class="mission-grid">
          <div class="mission-img" data-overlay="title" data-opacity="2">
            <img
              src="{{ getImgUrl($tab['image'] ?? null, 'assets/img/normal/mission_1_2.jpg') }}"
              alt="mission img" />
            <a
              href="{{ $tab['video_url'] ?? '#' }}"
              class="play-btn style3 popup-video"><i class="fas fa-play"></i></a>
          </div>
          <div class="mission-content">
            <h3 class="mission-title">
              {{ $tab['title'] ?? '' }}
            </h3>
            <p class="mission-text">
              {{ $tab['text'] ?? '' }}
            </p>

            @if(isset($tab['checklist']) && is_array($tab['checklist']))
            <div class="checklist">
              <ul>
                @foreach($tab['checklist'] as $chk)
                <li>{{ $chk['text'] ?? '' }}</li>
                @endforeach
              </ul>
            </div>
            @endif

            @if(isset($tab['features']) && is_array($tab['features']))
            <div class="mission-feature-wrap">
              @foreach($tab['features'] as $feat)
              <div class="mission-feature">
                <div class="mission-feature_icon">
                  <img src="{{ getImgUrl($feat['icon'] ?? null, 'assets/img/icon/mission_1_1.svg') }}" alt="icon" />
                </div>
                <div class="media-body">
                  <p class="mission-feature_subtitle">{{ $feat['subtitle'] ?? '' }}</p>
                  <h4 class="mission-feature_title">{{ $feat['title'] ?? '' }}</h4>
                </div>
              </div>
              @endforeach
            </div>
            @endif
          </div>
        </div>
      </div>
      @endforeach
      @endif
    </div>
  </div>
</div>

<div
  class="team-area overflow-hidden space"
  id="team-sec"
  data-bg-src="assets/img/bg/team_bg_1.png"
  data-overlay="title">
  <div class="container">
    <div class="title-area text-center">
      <span class="sub-title4">Team Members</span>
      <h2 class="sec-title text-white">Our Professional Team</h2>
    </div>
    <div
      class="row th-carousel arrow-style2"
      data-slide-show="4"
      data-lg-slide-show="3"
      data-md-slide-show="2"
      data-sm-slide-show="2"
      data-xs-slide-show="1"
      data-arrows="true">
      @if(isset($teams) && $teams->count() > 0)
      @foreach($teams as $team)
      <div class="col-lg-6">
        <div class="team-card">
          <div class="team-img-wrap">
            <div class="team-img">
              <img src="{{ getImgUrl($team->image, 'assets/img/team/team_1_1.jpg') }}" alt="{{ $team->name }}" />
            </div>
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
</div>

<section class="space">
  <div class="container">
    <div class="title-area text-center">
      <span class="sub-title4">{{ $aboutContent['about_page_process']['subtitle'] ?? 'How It Works' }}</span>
      <h2 class="sec-title fw-semibold">{{ $aboutContent['about_page_process']['title'] ?? 'Our Work Process' }}</h2>
    </div>
    <div class="row gy-50 justify-content-center">
      @if(isset($aboutContent['about_page_process']['cards']) && is_array($aboutContent['about_page_process']['cards']))
      @foreach($aboutContent['about_page_process']['cards'] as $card)
      <div class="col-md-6 col-xl-3">
        <div class="process-card">
          <div class="process-card_bg-shape">
            <img src="{{ getImgUrl($card['bg_shape'] ?? null, 'assets/img/bg/process_card_bg_1.png') }}" alt="img" />
          </div>
          <div class="process-card_icon">
            <img src="{{ getImgUrl($card['icon'] ?? null, 'assets/img/icon/process-icon-1-1.svg') }}" alt="icon" />
          </div>
          <span class="process-card_subtitle">{{ $card['subtitle'] ?? '' }}</span>
          <h4 class="process-card_title">{{ $card['title'] ?? '' }}</h4>
          <p class="process-card_text">
            {{ $card['text'] ?? '' }}
          </p>
        </div>
      </div>
      @endforeach
      @endif
    </div>
  </div>
</section>


<section class="testi-area-3 bg-smoke overflow-hidden bg-smoke space">
  <div class="container">
    <div class="title-area text-center">
      <span class="sub-title4">Our Testimonials</span>
      <h2 class="sec-title">What Our Clients Say?</h2>
    </div>
  </div>
  <div class="container">
    <div
      class="row slider-shadow th-carousel arrow-style6 testi-slider3"
      data-slide-show="3"
      data-ml-slide-show="2"
      data-lg-slide-show="2"
      data-md-slide-show="1"
      data-arrows="true"
      data-xl-arrows="true"
      data-ml-arrows="true">
      @if(isset($testimonials) && $testimonials->count() > 0)
        @foreach($testimonials as $testimonial)
        <div class="col-lg-6">
          <div class="testi-card style3">
            <div class="testi-card-icon">
              <img src="assets/img/icon/testi-quote3.svg" alt="img" />
            </div>
            <p class="testi-card_text">
              <span class="text-theme">â€œ</span>{{ $testimonial->text }}<span class="text-theme">â€ </span>
            </p>
            <div class="testi-card_content">
              <div class="testi-card_img">
                <img
                  src="{{ getImgUrl($testimonial->image, 'assets/img/testimonial/testi_2_1.jpg') }}"
                  alt="Avater" />
              </div>
              <div class="testi-card_bottom">
                <h3 class="testi-card_name">{{ $testimonial->name }}</h3>
                <span class="testi-card_desig">{{ $testimonial->designation }}</span>
                <div class="testi-card_review">
                  @for($i = 0; $i < $testimonial->rating; $i++)
                    <i class="fa-solid fa-star-sharp text-warning"></i>
                  @endfor
                  @for($i = $testimonial->rating; $i < 5; $i++)
                    <i class="fa-regular fa-star-sharp"></i>
                  @endfor
                </div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      @endif
    </div>
  </div>
</section>


@endsection