@extends('frontend.layouts.app')

<?php
    if (!function_exists('getImgUrl')) {
        function getImgUrl($path, $default) {
            if (empty($path)) return asset($default);
            if (str_starts_with($path, 'assets/')) return asset($path);
            return asset('storage/' . $path);
        }
    }
?>

@section('title', $blog->title . ' - Blog')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($blog->content), 150))
@section('meta_author', $blog->author)

@section('content')
<!-- Breadcrumb -->
<div class="breadcumb-wrapper" data-bg-src="{{ asset('assets/img/bg/breadcumb-bg.jpg') }}">
  <div class="container">
    <div class="breadcumb-content">
      <h1 class="breadcumb-title">{{ $blog->title }}</h1>
      <ul class="breadcumb-menu">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('blog') }}">Blog &amp; News</a></li>
        <li>Blog Details</li>
      </ul>
    </div>
  </div>
</div>

<section class="space th-blog-extra-bottom">
  <div class="container">
    <div class="row">
      <div class="col-xxl-8 col-lg-7">
        <div class="th-blog blog-single">
          <div class="blog-img">
            <img src="{{ getImgUrl($blog->image, 'assets/img/update2/blog/blog_1_1.jpg') }}" alt="{{ $blog->title }}" class="w-100 rounded">
          </div>
          <div class="blog-content">
            <div class="blog-meta">
              <a href="#"><i class="fa-solid fa-user"></i>By {{ $blog->author }}</a>
              <a href="#"><i class="fa-solid fa-calendar-days"></i>{{ optional($blog->published_at)->format('d M, Y') ?? 'Jan 01, 2024' }}</a>
              <a href="#"><i class="fa-solid fa-tags"></i>{{ $blog->category }}</a>
            </div>
            <h2 class="blog-title">{{ $blog->title }}</h2>
            
            <div class="blog-text">
                {!! $blog->content !!}
            </div>
            
          </div>
        </div>
      </div>
      
      <div class="col-xxl-4 col-lg-5">
        <aside class="sidebar-area">
          <div class="widget widget_search">
            <form class="search-form">
              <input type="text" placeholder="Search...">
              <button type="submit"><i class="far fa-search"></i></button>
            </form>
          </div>
          
          <div class="widget widget_recent_post">
            <h3 class="widget_title">Recent Posts</h3>
            <div class="recent-post-wrap">
              @foreach($recentBlogs as $recent)
              <div class="recent-post">
                <div class="media-img">
                  <a href="{{ route('blog.details', $recent->slug) }}">
                    <img src="{{ getImgUrl($recent->image, 'assets/img/update2/blog/blog_1_1.jpg') }}" alt="Blog Image">
                  </a>
                </div>
                <div class="media-body">
                  <h4 class="post-title">
                    <a class="text-inherit" href="{{ route('blog.details', $recent->slug) }}">{{ $recent->title }}</a>
                  </h4>
                  <div class="recent-post-meta">
                    <a href="{{ route('blog.details', $recent->slug) }}"><i class="fal fa-calendar-days"></i>{{ optional($recent->published_at)->format('d M, Y') }}</a>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          
        </aside>
      </div>
    </div>
  </div>
</section>
@endsection
