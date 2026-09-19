@extends('frontend.layouts.app')
@section('content')
<!-- Breadcrumb -->
    <div class="breadcumb-wrapper" data-bg-src="assets/img/bg/breadcumb-bg.jpg">
      <div class="container">
        <div class="breadcumb-content">
          <h1 class="breadcumb-title">Blog &amp; News</h1>
          <ul class="breadcumb-menu">
            <li><a href="index.html">Home</a></li>
            <li>Blog &amp; News</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Blog Grid Section -->
    <section class="blog-area-4 space" id="blog-sec">
      <div class="container">
        <div class="text-center">
          <div class="title-area">
            <span class="sub-title9 justify-content-center">Latest Blog</span>
            <h2 class="sec-title">
              Our Latest Construction News Blog &amp; Articles
            </h2>
            <p class="mt-10 mb-0">
              Stay updated with the latest industry insights, project
              highlights, and construction tips from the Bondhan Living Ltd
              team.
            </p>
          </div>
        </div>

        <!-- Blog Filter Buttons -->
        <div class="text-center mb-40">
          <div class="btn-group" role="group" aria-label="Blog Filter">
            <button
              type="button"
              class="th-btn style3 me-2 active"
              onclick="filterBlog(this, 'all')"
            >
              All Posts
            </button>
            <button
              type="button"
              class="th-btn style2 me-2"
              onclick="filterBlog(this, 'industry')"
            >
              Industry
            </button>
            <button
              type="button"
              class="th-btn style2 me-2"
              onclick="filterBlog(this, 'design')"
            >
              Design
            </button>
            <button
              type="button"
              class="th-btn style2"
              onclick="filterBlog(this, 'news')"
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
            <a href="contact.html" class="th-btn style6 style-new"
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
              ><img src="assets/img/client/cilent_1_1.png" alt="Image"
            /></a>
          </div>
          <div class="partner-item">
            <a href="#"
              ><img src="assets/img/client/cilent_1_2.png" alt="Image"
            /></a>
          </div>
          <div class="partner-item">
            <a href="#"
              ><img src="assets/img/client/cilent_1_3.png" alt="Image"
            /></a>
          </div>
          <div class="partner-item">
            <a href="#"
              ><img src="assets/img/client/cilent_1_4.png" alt="Image"
            /></a>
          </div>
          <div class="partner-item">
            <a href="#"
              ><img src="assets/img/client/cilent_1_5.png" alt="Image"
            /></a>
          </div>
        </div>
      </div>
    </div>
    <!-- End Partner Area -->
@endsection
