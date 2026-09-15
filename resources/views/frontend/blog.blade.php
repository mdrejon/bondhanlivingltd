@extends('layouts.frontend')

@section('title', !empty($settings['blog_seo_title']) ? $settings['blog_seo_title'] : 'Blog & Articles – Hotel Beach Way')
@section('meta_description', !empty($settings['blog_seo_description']) ? $settings['blog_seo_description'] : 'Read the latest travel tips, hospitality insights and hotel news from Hotel Beach Way, Cox\'s Bazar.')
@if(!empty($settings['blog_seo_keywords']))
@section('meta_keywords', $settings['blog_seo_keywords'])
@endif
@section('og_title', !empty($settings['blog_seo_title']) ? $settings['blog_seo_title'] : 'Blog & Articles – Hotel Beach Way')
@section('og_description', !empty($settings['blog_seo_description']) ? $settings['blog_seo_description'] : 'Read the latest travel tips, hospitality insights and hotel news from Hotel Beach Way, Cox\'s Bazar.')
@if(!empty($settings['blog_seo_og_image']))
@section('og_image', asset('storage/' . $settings['blog_seo_og_image']))
@endif

@section('content')

@php
  $heroImage = $settings['blog_hero_image'] ?? null;
  $heroTitle = $settings['blog_hero_title'] ?? 'Blog & Articles';
@endphp

  <!-- ===========
       PAGE BREADCRUMB HERO
  =========== -->
  <section class="page-hero">
    <div class="page-hero-bg"
      @if($heroImage)
        style="background-image: url('{{ asset('storage/' . $heroImage) }}'); background-size: cover; background-position: center;"
      @endif
    ></div>
    <div class="page-hero-overlay"></div>

    <div class="page-hero-content">
      <h1 class="page-hero-title">{{ $heroTitle }}</h1>
      <nav class="page-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="bc-sep">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="9 18 15 12 9 6"/>
          </svg>
        </span>
        <span class="bc-current">{{ $heroTitle }}</span>
      </nav>
    </div>

    <div class="page-hero-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,40 C360,75 720,5 1080,40 C1260,58 1380,18 1440,40 L1440,80 L0,80 Z" fill="#ffffff"/>
      </svg>
    </div>
  </section><!-- /page-hero -->

  <!-- ===========
       BLOG LISTING
  =========== -->
  <section class="bp-section">
    <div class="bp-inner">

      @if($blogs->isEmpty())
        <div class="bp-empty" data-reveal="up">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
          <h3>No posts found</h3>
          <p>Check back soon for new articles and travel insights.</p>
          @if(request()->filled('category') || request()->filled('tag'))
            <a href="{{ route('blog.front') }}" class="bp-page-btn active" style="display:inline-flex;margin-top:12px;">View All Posts</a>
          @endif
        </div>
      @else

        <!-- Grid of posts -->
        <div class="bp-grid" data-reveal="up">
          @foreach($blogs as $post)
          <article class="bp-card">
            <a href="{{ route('blog.detail', $post->slug) }}" class="bp-card-img-wrap">
              @if($post->feature_image)
                <img src="{{ asset('storage/' . $post->feature_image) }}"
                     alt="{{ $post->title }}"
                     loading="lazy">
              @else
                <img src="{{ asset('assets/images/blog-placeholder.jpg') }}"
                     alt="{{ $post->title }}"
                     loading="lazy">
              @endif
            </a>
            <div class="bp-card-body">
              <div class="bp-card-meta">
                <span class="bp-meta-line"></span>
                <span class="bp-meta-author">{{ $post->author_name ?: 'Admin' }}</span>
                <span class="bp-meta-dot"></span>
                <span class="bp-meta-date">{{ $post->published_at?->format('F d, Y') }}</span>
              </div>
              <h3 class="bp-card-title">
                <a href="{{ route('blog.detail', $post->slug) }}">{{ $post->title }}</a>
              </h3>
            </div>
          </article>
          @endforeach
        </div><!-- /bp-grid -->

        <!-- Pagination -->
        @if($blogs->hasPages())
        <div class="bp-pagination" data-reveal="up">

          {{-- Previous --}}
          @if($blogs->onFirstPage())
            <span class="bp-page-btn bp-page-prev bp-page-disabled" aria-disabled="true">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </span>
          @else
            <a href="{{ $blogs->previousPageUrl() }}" class="bp-page-btn bp-page-prev" aria-label="Previous">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </a>
          @endif

          {{-- Page numbers --}}
          @foreach($blogs->getUrlRange(1, $blogs->lastPage()) as $page => $url)
            @if($page == $blogs->currentPage())
              <span class="bp-page-btn active">{{ $page }}</span>
            @else
              <a href="{{ $url }}" class="bp-page-btn">{{ $page }}</a>
            @endif
          @endforeach

          {{-- Next --}}
          @if($blogs->hasMorePages())
            <a href="{{ $blogs->nextPageUrl() }}" class="bp-page-btn bp-page-next" aria-label="Next">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
          @else
            <span class="bp-page-btn bp-page-next bp-page-disabled" aria-disabled="true">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </span>
          @endif

        </div>
        @endif

      @endif

    </div><!-- /bp-inner -->
  </section><!-- /bp-section -->

@endsection
