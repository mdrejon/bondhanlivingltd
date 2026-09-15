@extends('frontend.layouts.app')
@section('content')
<!-- Breadcrumb -->
    <div class="breadcumb-wrapper" data-bg-src="assets/img/bg/breadcumb-bg.jpg">
      <div class="container">
        <div class="breadcumb-content">
          <h1 class="breadcumb-title">Our Services</h1>
          <ul class="breadcumb-menu">
            <li><a href="index.html">Home</a></li>
            <li>Our Services</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Services Section - Tab-based like Home Page -->
    <section
      class="service-area12 overflow-hidden space overflow-hidden"
      id="service-sec"
      data-bg-src="assets/img/update2//bg/service_bg_1.jpg"
    >
      <div class="container">
        <div class="row">
          <div class="title-area mb-0 text-center">
            <span class="sub-title9 justify-content-center">Our Services</span>
            <h2 class="sec-title">The Best Service For You</h2>
            <p class="mt-20 mb-0">
              Bondhan Living Ltd delivers end-to-end construction and real
              estate solutions — from concept to completion. Our expertise spans
              residential, commercial, and industrial sectors.
            </p>
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
          <!-- Commercial Tab -->
          <div class="tab-pane fade active show" id="nav-step1" role="tabpanel">
            <div class="row gy-4">
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
                    management services. Along with design architecture firms.
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
                    Our management services used in and around a home.
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
                    delivering various materials needed for a project.
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
                    <a href="service-details.html">Project Management</a>
                  </h3>
                  <p class="service-box_text">
                    We offer comprehensive project management to ensure your
                    construction stays on schedule and within budget.
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
                    <a href="service-details.html">Structural Engineering</a>
                  </h3>
                  <p class="service-box_text">
                    Our structural engineering services ensure safety,
                    durability, and compliance with all building codes and
                    standards.
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
                    <a href="service-details.html">Land Development</a>
                  </h3>
                  <p class="service-box_text">
                    From site preparation to final grading, we handle every
                    aspect of land development with precision.
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
            </div>
          </div>

          <!-- Residential Tab -->
          <div class="tab-pane fade" id="nav-step2" role="tabpanel">
            <div class="row gy-4">
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
                    <a href="service-details.html">Home Building</a>
                  </h3>
                  <p class="service-box_text">
                    We build custom homes that reflect your lifestyle, from
                    single-family residences to luxury estates.
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
                    <a href="service-details.html">Apartment Construction</a>
                  </h3>
                  <p class="service-box_text">
                    We specialize in multi-unit residential buildings designed
                    for comfort, functionality, and modern living.
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
                    <a href="service-details.html">Interior Fit-Out</a>
                  </h3>
                  <p class="service-box_text">
                    Our interior fit-out services transform bare spaces into
                    beautifully designed and fully functional living areas.
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
                    <a href="service-details.html"
                      >Roofing &amp; Waterproofing</a
                    >
                  </h3>
                  <p class="service-box_text">
                    We provide durable roofing and waterproofing solutions to
                    protect your home from the elements for years to come.
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
                    <a href="service-details.html">Plumbing &amp; Electrical</a>
                  </h3>
                  <p class="service-box_text">
                    Complete plumbing and electrical systems installed by
                    certified professionals ensuring safety and compliance.
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
                    <a href="service-details.html">Landscaping</a>
                  </h3>
                  <p class="service-box_text">
                    We create stunning outdoor environments that complement your
                    home and enhance your property's overall value.
                  </p>
                  <a class="line-btn" href="service-details.html"
                    >Read More <i class="fas fa-arrow-right ms-2"></i
                  ></a>
                </div>
              </div>
            </div>
          </div>

          <!-- Industrial Tab -->
          <div class="tab-pane fade" id="nav-step3" role="tabpanel">
            <div class="row gy-4">
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
                    <a href="service-details.html">Factory Construction</a>
                  </h3>
                  <p class="service-box_text">
                    We design and build efficient factory buildings that meet
                    operational needs and industry safety standards.
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
                    <a href="service-details.html">Warehouse Building</a>
                  </h3>
                  <p class="service-box_text">
                    Our warehouse construction solutions deliver spacious,
                    durable, and cost-effective storage facilities.
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
                    <a href="service-details.html">Civil Infrastructure</a>
                  </h3>
                  <p class="service-box_text">
                    We develop roads, bridges, and civil infrastructure that
                    support industrial operations and community growth.
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
                    <a href="service-details.html">Steel Structure Works</a>
                  </h3>
                  <p class="service-box_text">
                    Precision-engineered steel frameworks for industrial
                    facilities, offering strength and long-term reliability.
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
                    <a href="service-details.html">Industrial Renovation</a>
                  </h3>
                  <p class="service-box_text">
                    We upgrade and modernize existing industrial facilities to
                    enhance productivity, efficiency, and safety.
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
                    <a href="service-details.html">Utility Installation</a>
                  </h3>
                  <p class="service-box_text">
                    Complete utility systems including power, water, and waste
                    management for industrial complexes.
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

    <!-- CTA Section -->
    <section
      class="space-extra"
      data-bg-src="assets/img/update1/bg/cta_bg_1.jpg"
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
