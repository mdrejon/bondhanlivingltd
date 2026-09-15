@extends('frontend.layouts.app')
@section('content')
<div class="breadcumb-wrapper" data-bg-src="assets/img/bg/breadcumb-bg.jpg">
      <div class="container">
        <div class="breadcumb-content">
          <h1 class="breadcumb-title">Project Details</h1>
          <ul class="breadcumb-menu">
            <li><a href="index.html">Home</a></li>
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
                      src="assets/img/project/project-3-1.png"
                      alt="Project Image"
                      class="w-100 rounded"
                      style="height: 550px; object-fit: cover"
                    />
                  </div>
                  <div class="item">
                    <img
                      src="assets/img/project/project-3-2.png"
                      alt="Project Image"
                      class="w-100 rounded"
                      style="height: 550px; object-fit: cover"
                    />
                  </div>
                  <div class="item">
                    <img
                      src="assets/img/project/project-3-3.png"
                      alt="Project Image"
                      class="w-100 rounded"
                      style="height: 550px; object-fit: cover"
                    />
                  </div>
                  <div class="item">
                    <img
                      src="assets/img/project/project-3-4.png"
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
                      src="assets/img/project/project-3-1.png"
                      alt="Thumbnail"
                      class="w-100 rounded"
                      style="height: 120px; object-fit: cover; cursor: pointer"
                    />
                  </div>
                  <div class="col-3">
                    <img
                      src="assets/img/project/project-3-2.png"
                      alt="Thumbnail"
                      class="w-100 rounded"
                      style="height: 120px; object-fit: cover; cursor: pointer"
                    />
                  </div>
                  <div class="col-3">
                    <img
                      src="assets/img/project/project-3-3.png"
                      alt="Thumbnail"
                      class="w-100 rounded"
                      style="height: 120px; object-fit: cover; cursor: pointer"
                    />
                  </div>
                  <div class="col-3">
                    <img
                      src="assets/img/project/project-3-4.png"
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
                        Stunning mansion in Reno
                      </h2>
                      <p class="text-body mb-2" style="font-size: 15px">
                        <i class="fa-solid fa-location-dot text-theme me-2"></i>
                        28B Highgate Road, London
                        <i class="fa-solid fa-clock text-theme ms-3 me-2"></i>
                        10 months ago
                        <span class="badge bg-theme ms-2 px-3 py-2 text-white"
                          >For Rent</span
                        >
                      </p>
                      <div
                        class="d-flex flex-wrap gap-4 text-body mt-3 fw-medium"
                      >
                        <span
                          ><i class="fa-solid fa-bed text-theme me-2"></i> 8
                          Beds</span
                        >
                        <span
                          ><i class="fa-solid fa-bath text-theme me-2"></i> 6
                          Baths</span
                        >
                        <span
                          ><i
                            class="fa-solid fa-vector-square text-theme me-2"
                          ></i>
                          2400 Sq Ft</span
                        >
                      </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                      <div class="d-flex gap-2 justify-content-md-end">
                        <a
                          href="#"
                          class="icon-btn border bg-white d-inline-flex align-items-center justify-content-center text-title"
                          style="width: 40px; height: 40px; border-radius: 50%"
                          ><i class="fa-solid fa-heart"></i
                        ></a>
                        <a
                          href="#"
                          class="icon-btn border bg-white d-inline-flex align-items-center justify-content-center text-title"
                          style="width: 40px; height: 40px; border-radius: 50%"
                          ><i class="fa-solid fa-plus"></i
                        ></a>
                        <a
                          href="#"
                          class="icon-btn border bg-white d-inline-flex align-items-center justify-content-center text-title"
                          style="width: 40px; height: 40px; border-radius: 50%"
                          ><i class="fa-solid fa-share-nodes"></i
                        ></a>
                        <a
                          href="#"
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
                  <p class="text-body mb-4">
                    Nullam metus metus, imperdiet ut ex quis, ultrices feugiat
                    neque. Etiam vitae accumsan neque, id gravida ligula. Donec
                    ut tincidunt velit. Sed gravida erat nunc, ac vehicula orci
                    dignissim id. Praesent diam magna. Sed tincidunt mi libero.
                  </p>
                  <p class="text-body mb-0">
                    Phasellus sed pellentesque neque, sit amet porta quam. Donec
                    sapien odio, eleifend mattis tristique ut, finibus ac augue.
                    Suspendisse potenti. Etiam porttitor mi lorem, ut mattis
                    mauris rutrum in. Mauris nibh sapien, ornare at dui ac,
                    placerat pulvinar nisi.
                  </p>
                </div>

                <!-- Property Details & Facilities - Two Column Layout -->
                <div class="row gy-4">
                  <div class="col-lg-6">
                    <!-- Property Details -->
                    <div class="bg-smoke p-4 p-lg-5 rounded mb-4">
                      <h3 class="h4 mb-4 fw-bold text-title">
                        Property Details
                      </h3>
                      <div class="row">
                        <div class="col-sm-6 col-md-4">
                          <ul class="list-unstyled text-body">
                            <li class="mb-3">
                              <strong class="text-title fw-semibold"
                                >Property ID:</strong
                              >
                              F257481
                            </li>
                            <li class="mb-3">
                              <strong class="text-title fw-semibold"
                                >Property Price:</strong
                              >
                              $340,00
                            </li>
                            <li class="mb-3">
                              <strong class="text-title fw-semibold"
                                >Property Type:</strong
                              >
                              Garden House
                            </li>
                          </ul>
                        </div>
                        <div class="col-sm-6 col-md-4">
                          <ul class="list-unstyled text-body">
                            <li class="mb-3">
                              <strong class="text-title fw-semibold"
                                >Rooms:</strong
                              >
                              5
                            </li>
                            <li class="mb-3">
                              <strong class="text-title fw-semibold"
                                >Garages:</strong
                              >
                              2
                            </li>
                            <li class="mb-3">
                              <strong class="text-title fw-semibold"
                                >Baths:</strong
                              >
                              5
                            </li>
                          </ul>
                        </div>
                        <div class="col-sm-6 col-md-4">
                          <ul class="list-unstyled text-body">
                            <li class="mb-3">
                              <strong class="text-title fw-semibold"
                                >Property Status:</strong
                              >
                              For Sale
                            </li>
                            <li class="mb-3">
                              <strong class="text-title fw-semibold"
                                >Bedrooms:</strong
                              >
                              5
                            </li>
                            <li class="mb-3">
                              <strong class="text-title fw-semibold"
                                >Originating Year:</strong
                              >
                              2022
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- end col-lg-6 Property Details -->
                  <div class="col-lg-6">
                    <!-- Facilities -->
                    <div class="bg-smoke p-4 p-lg-5 rounded mb-4">
                      <h3 class="h4 mb-4 fw-bold text-title">Facilities</h3>
                      <div class="row">
                        <div class="col-sm-6 col-md-4">
                          <ul class="list-unstyled text-body">
                            <li class="mb-3">
                              <i
                                class="fa-solid fa-circle-check text-theme me-2"
                              ></i>
                              Air Cond
                            </li>
                            <li class="mb-3">
                              <i
                                class="fa-solid fa-circle-check text-theme me-2"
                              ></i>
                              Dishwasher
                            </li>
                            <li class="mb-3">
                              <i
                                class="fa-solid fa-circle-check text-theme me-2"
                              ></i>
                              Parking
                            </li>
                          </ul>
                        </div>
                        <div class="col-sm-6 col-md-4">
                          <ul class="list-unstyled text-body">
                            <li class="mb-3">
                              <i
                                class="fa-solid fa-circle-check text-theme me-2"
                              ></i>
                              Balcony
                            </li>
                            <li class="mb-3">
                              <i
                                class="fa-solid fa-circle-check text-theme me-2"
                              ></i>
                              Bedding
                            </li>
                            <li class="mb-3">
                              <i
                                class="fa-solid fa-circle-check text-theme me-2"
                              ></i>
                              Pool
                            </li>
                          </ul>
                        </div>
                        <div class="col-sm-6 col-md-4">
                          <ul class="list-unstyled text-body">
                            <li class="mb-3">
                              <i
                                class="fa-solid fa-circle-check text-theme me-2"
                              ></i>
                              Internet
                            </li>
                            <li class="mb-3">
                              <i
                                class="fa-solid fa-circle-check text-theme me-2"
                              ></i>
                              Cable TV
                            </li>
                            <li class="mb-3">
                              <i
                                class="fa-solid fa-circle-check text-theme me-2"
                              ></i>
                              Fridge
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- end col-lg-6 Facilities -->
                </div>
                <!-- end row two-column -->

                <!-- Floor Plans -->
                <div class="bg-smoke p-4 p-lg-5 rounded mb-4">
                  <h3 class="h4 mb-4 fw-bold text-title">Floor Plans</h3>
                  <div class="bg-white p-3 rounded border">
                    <img
                      src="assets/img/project/project-3-3.png"
                      alt="Floor Plan"
                      class="img-fluid w-100 rounded"
                      style="filter: grayscale(100%)"
                    />
                  </div>
                </div>

                <!-- Map -->
                <div class="bg-smoke p-4 p-lg-5 rounded mb-4">
                  <h3 class="h4 mb-4 fw-bold text-title">Map Location</h3>
                  <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3644.7310056272386!2d89.2286059153658!3d24.00527418490799!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fe9b97badc6151%3A0x30b048c9fb2129bc!2sth!5e0!3m2!1sen!2sbd!4v1651028958211!5m2!1sen!2sbd"
                    width="100%"
                    height="350"
                    style="border: 0; border-radius: 5px"
                    allowfullscreen=""
                    loading="lazy"
                  ></iframe>
                </div>

                <!-- Video -->
                <div class="bg-smoke p-4 p-lg-5 rounded mb-4">
                  <h3 class="h4 mb-4 fw-bold text-title">Property Video</h3>
                  <div class="position-relative overflow-hidden rounded">
                    <img
                      src="assets/img/project/project-3-5.png"
                      alt="Video"
                      class="img-fluid w-100"
                      style="height: 350px; object-fit: cover"
                    />
                    <div
                      class="position-absolute top-0 start-0 w-100 h-100 bg-black opacity-50"
                    ></div>
                    <a
                      href="https://www.youtube.com/watch?v=_sI_Ps7JSEk"
                      class="play-btn popup-video position-absolute top-50 start-50 translate-middle text-decoration-none"
                    >
                      <i
                        class="fa-brands fa-youtube text-danger"
                        style="font-size: 4rem"
                      ></i>
                    </a>
                  </div>
                </div>
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
