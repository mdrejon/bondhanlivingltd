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
              News
            </button>
          </div>
        </div>

        <div class="row gy-4">
          <!-- Blog Post 1 -->
          <div class="col-md-6 col-xl-4 blog-item" data-category="industry">
            <div class="th-blog blog-single style4 th-ani">
              <div class="blog-img">
                <a href="blog-details.html">
                  <img
                    src="assets/img/update2/blog/blog_1_1.jpg"
                    alt="Blog Image"
                  />
                </a>
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
                    >The best team around and how we make it work</a
                  >
                </h4>
                <a href="blog-details.html" class="link-btn style2"
                  >Read More <i class="fas fa-arrow-right ms-1"></i
                ></a>
              </div>
            </div>
          </div>

          <!-- Blog Post 2 -->
          <div class="col-md-6 col-xl-4 blog-item" data-category="design">
            <div class="th-blog blog-single style4 th-ani">
              <div class="blog-img">
                <a href="blog-details.html">
                  <img
                    src="assets/img/update2/blog/blog_1_2.jpg"
                    alt="Blog Image"
                  />
                </a>
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
                    ><i class="fa-solid fa-tags"></i>DESIGN</a
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

          <!-- Blog Post 3 -->
          <div class="col-md-6 col-xl-4 blog-item" data-category="news">
            <div class="th-blog blog-single style4 th-ani">
              <div class="blog-img">
                <a href="blog-details.html">
                  <img
                    src="assets/img/update2/blog/blog_1_3.jpg"
                    alt="Blog Image"
                  />
                </a>
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
                    ><i class="fa-solid fa-tags"></i>NEWS</a
                  >
                </div>
                <h4 class="box-title">
                  <a href="blog-details.html"
                    >Redefining Organizational Dynamics by Embracing Change</a
                  >
                </h4>
                <a href="blog-details.html" class="link-btn style2"
                  >Read More <i class="fas fa-arrow-right ms-1"></i
                ></a>
              </div>
            </div>
          </div>

          <!-- Blog Post 4 -->
          <div class="col-md-6 col-xl-4 blog-item" data-category="industry">
            <div class="th-blog blog-single style4 th-ani">
              <div class="blog-img">
                <a href="blog-details.html">
                  <img
                    src="assets/img/update2/blog/blog_1_1.jpg"
                    alt="Blog Image"
                  />
                </a>
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
                    >Top Construction Trends Shaping the Industry in 2024</a
                  >
                </h4>
                <a href="blog-details.html" class="link-btn style2"
                  >Read More <i class="fas fa-arrow-right ms-1"></i
                ></a>
              </div>
            </div>
          </div>

          <!-- Blog Post 5 -->
          <div class="col-md-6 col-xl-4 blog-item" data-category="design">
            <div class="th-blog blog-single style4 th-ani">
              <div class="blog-img">
                <a href="blog-details.html">
                  <img
                    src="assets/img/update2/blog/blog_1_2.jpg"
                    alt="Blog Image"
                  />
                </a>
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
                    ><i class="fa-solid fa-tags"></i>DESIGN</a
                  >
                </div>
                <h4 class="box-title">
                  <a href="blog-details.html"
                    >Sustainable Architecture: Building a Greener Tomorrow</a
                  >
                </h4>
                <a href="blog-details.html" class="link-btn style2"
                  >Read More <i class="fas fa-arrow-right ms-1"></i
                ></a>
              </div>
            </div>
          </div>

          <!-- Blog Post 6 -->
          <div class="col-md-6 col-xl-4 blog-item" data-category="news">
            <div class="th-blog blog-single style4 th-ani">
              <div class="blog-img">
                <a href="blog-details.html">
                  <img
                    src="assets/img/update2/blog/blog_1_3.jpg"
                    alt="Blog Image"
                  />
                </a>
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
                    ><i class="fa-solid fa-tags"></i>NEWS</a
                  >
                </div>
                <h4 class="box-title">
                  <a href="blog-details.html"
                    >Bondhan Living Wins Excellence in Construction Award
                    2024</a
                  >
                </h4>
                <a href="blog-details.html" class="link-btn style2"
                  >Read More <i class="fas fa-arrow-right ms-1"></i
                ></a>
              </div>
            </div>
          </div>

          <!-- Blog Post 7 -->
          <div class="col-md-6 col-xl-4 blog-item" data-category="industry">
            <div class="th-blog blog-single style4 th-ani">
              <div class="blog-img">
                <a href="blog-details.html">
                  <img
                    src="assets/img/update2/blog/blog_1_1.jpg"
                    alt="Blog Image"
                  />
                </a>
                <div class="blog-date">
                  <span class="date">15</span> Jul, 2024
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
                    >How Smart Technology is Revolutionizing Modern
                    Construction</a
                  >
                </h4>
                <a href="blog-details.html" class="link-btn style2"
                  >Read More <i class="fas fa-arrow-right ms-1"></i
                ></a>
              </div>
            </div>
          </div>

          <!-- Blog Post 8 -->
          <div class="col-md-6 col-xl-4 blog-item" data-category="design">
            <div class="th-blog blog-single style4 th-ani">
              <div class="blog-img">
                <a href="blog-details.html">
                  <img
                    src="assets/img/update2/blog/blog_1_2.jpg"
                    alt="Blog Image"
                  />
                </a>
                <div class="blog-date">
                  <span class="date">18</span> Jul, 2024
                </div>
                <div class="blog-shape"></div>
              </div>
              <div class="blog-content">
                <div class="blog-meta">
                  <a href="blog.html"
                    ><i class="fa-solid fa-user"></i>By Bondhon</a
                  >
                  <a class="author" href="blog.html"
                    ><i class="fa-solid fa-tags"></i>DESIGN</a
                  >
                </div>
                <h4 class="box-title">
                  <a href="blog-details.html"
                    >5 Essential Tips for Choosing the Right Building
                    Materials</a
                  >
                </h4>
                <a href="blog-details.html" class="link-btn style2"
                  >Read More <i class="fas fa-arrow-right ms-1"></i
                ></a>
              </div>
            </div>
          </div>

          <!-- Blog Post 9 -->
          <div class="col-md-6 col-xl-4 blog-item" data-category="news">
            <div class="th-blog blog-single style4 th-ani">
              <div class="blog-img">
                <a href="blog-details.html">
                  <img
                    src="assets/img/update2/blog/blog_1_3.jpg"
                    alt="Blog Image"
                  />
                </a>
                <div class="blog-date">
                  <span class="date">20</span> Jul, 2024
                </div>
                <div class="blog-shape"></div>
              </div>
              <div class="blog-content">
                <div class="blog-meta">
                  <a href="blog.html"
                    ><i class="fa-solid fa-user"></i>By Bondhon</a
                  >
                  <a class="author" href="blog.html"
                    ><i class="fa-solid fa-tags"></i>NEWS</a
                  >
                </div>
                <h4 class="box-title">
                  <a href="blog-details.html"
                    >New Residential Complex Launched in Gulshan, Dhaka</a
                  >
                </h4>
                <a href="blog-details.html" class="link-btn style2"
                  >Read More <i class="fas fa-arrow-right ms-1"></i
                ></a>
              </div>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div class="th-pagination text-center mt-50">
          <ul>
            <li>
              <a href="#" id="pagination-prev"
                ><i class="fas fa-angle-left"></i
              ></a>
            </li>
            <li><a href="#" class="active">1</a></li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li>
              <a href="#" id="pagination-next"
                ><i class="fas fa-angle-right"></i
              ></a>
            </li>
          </ul>
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
