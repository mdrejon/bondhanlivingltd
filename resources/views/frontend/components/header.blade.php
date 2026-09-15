    <div class="preloader">
      <button class="th-btn style3 preloaderCls">Cancel Preloader</button>
      <div class="preloader-inner"><span class="loader"></span></div>
    </div>
    <div class="popup-search-box d-none d-lg-block">
      <button class="searchClose"><i class="fal fa-times"></i></button>
      <form action="#">
        <input type="text" placeholder="What are you looking for?" />
        <button type="submit"><i class="fal fa-search"></i></button>
      </form>
    </div>
    <div class="th-menu-wrapper">
      <div class="th-menu-area text-center">
        <button class="th-menu-toggle"><i class="fal fa-times"></i></button>
        <div class="mobile-logo">
          <a href="{{ route('home') }}"
            ><img src="{{ asset('assets/img/logo-white.png') }}" alt="Bondhon"
          /></a>
        </div>
        <div class="th-mobile-menu">
          <ul>
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="menu-item-has-children">
              <a href="{{ route('about') }}">About Us</a>
              <ul class="sub-menu">
                <li><a href="{{ route('about') }}">Companey Profile</a></li>
                <li><a href="{{ route('chairman-message') }}">Chairman message</a></li>
                <li><a href="{{ route('history') }}">History</a></li>
                <li><a href="{{ route('corporate-background') }}">Corporate Background</a></li>
              </ul>
            </li>
            <li class="menu-item-has-children">
              <a href="{{ route('projects.index') }}">Project</a>
              <ul class="sub-menu">
                <li><a href="{{ route('projects.running') }}">Running Project</a></li>
                <li><a href="{{ route('projects.completed') }}">Complete Project</a></li>
                <li><a href="{{ route('projects.upcoming') }}">Upcoming Project</a></li>
              </ul>
            </li>
            <li class="menu-item-has-children">
              <a href="{{ route('services.index') }}">Our Services</a>
              <ul class="sub-menu">
                <li><a href="{{ route('features-amenities') }}">Features & Amenities</a></li>
                <li><a href="#">Business Development</a></li>
                <li><a href="{{ route('terms-and-conditions') }}">Terms & Condition</a></li>
              </ul>
            </li>
            <li><a href="{{ route('gallery') }}">Picture Gallery</a></li>
            <li class="menu-item-has-children">
              <a href="#">Media Center</a>
              <ul class="sub-menu">
                <li><a href="#">News & Events</a></li>
                <li><a href="#">Our Associate</a></li>
                <li><a href="#">Career</a></li>
                <li><a href="{{ route('loan-calculator') }}">Loan Calculator</a></li>
              </ul>
            </li>
            <li><a href="{{ route('contact') }}">Contact Us</a></li>
          </ul>
        </div>
      </div>
    </div>
    <header class="th-header header-layout5">
      <div class="header-top">
        <div class="container">
          <div class="header-top-inner">
            <div
              class="row justify-content-center justify-content-md-between align-items-center gy-2"
            >
              <div class="col-auto d-none d-md-block">
                <div class="header-links d-none d-md-block">
                  <ul>
                    <li>
                      <i class="fal fa-phone"></i
                      ><a href="tel:+8801740574490">+8801740 574 490</a>
                    </li>
                    <li>
                      <i class="fal fa-envelope"></i
                      ><a href="mailto:info@bondhanlivingltd.com">info@bondhanlivingltd.com</a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-auto">
                <div class="header-links">
                  <ul>
                    <li>
                      <div class="header-social">
                        <a href="https://www.facebook.com/bondhanlivingltd/"
                          ><i class="fab fa-facebook-f"></i
                        ></a>
                        <a href="https://www.twitter.com/"
                          ><i class="fab fa-twitter"></i
                        ></a>
                        <a href="https://www.linkedin.com/"
                          ><i class="fab fa-linkedin-in"></i
                        ></a>
                        <a href="https://www.instagram.com/"
                          ><i class="fab fa-instagram"></i
                        ></a>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="sticky-wrapper">
        <div class="sticky-active">
          <div class="menu-area">
            <div class="container">
              <div class="row align-items-center justify-content-between">
                <div class="col-auto">
                  <div class="logo-style3">
                    <a href="{{ route('home') }}"
                      ><img src="{{ asset('assets/img/logo-white.png') }}" alt="Bondhon"
                    /></a>
                  </div>
                </div>
                <div class="col-auto">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <nav class="main-menu d-none d-lg-inline-block">
                        <ul>
                          <li><a href="{{ route('home') }}">Home</a></li>
                          <li class="menu-item-has-children">
                            <a href="{{ route('about') }}">About Us</a>
                            <ul class="sub-menu">
                              <li><a href="{{ route('about') }}">Companey Profile</a></li>
                              <li><a href="{{ route('chairman-message') }}">Chairman message</a></li>
                              <li><a href="{{ route('history') }}">History</a></li>
                              <li><a href="{{ route('corporate-background') }}">Corporate Background</a></li>
                            </ul>
                          </li>
                          <li class="menu-item-has-children">
                            <a href="{{ route('projects.index') }}">Project</a>
                            <ul class="sub-menu">
                              <li><a href="{{ route('projects.running') }}">Running Project</a></li>
                              <li><a href="{{ route('projects.completed') }}">Complete Project</a></li>
                              <li><a href="{{ route('projects.upcoming') }}">Upcoming Project</a></li>
                            </ul>
                          </li>
                          <li class="menu-item-has-children">
                            <a href="{{ route('services.index') }}">Our Services</a>
                            <ul class="sub-menu">
                              <li><a href="{{ route('features-amenities') }}">Features & Amenities</a></li>
                              <li><a href="#">Business Development</a></li>
                              <li><a href="{{ route('terms-and-conditions') }}">Terms & Condition</a></li>
                            </ul>
                          </li>
                          <li><a href="{{ route('gallery') }}">Picture Gallery</a></li>
                          <li class="menu-item-has-children">
                            <a href="#">Media Center</a>
                            <ul class="sub-menu">
                              <li><a href="#">News & Events</a></li>
                              <li><a href="#">Our Associate</a></li>
                              <li><a href="#">Career</a></li>
                              <li><a href="{{ route('loan-calculator') }}">Loan Calculator</a></li>
                            </ul>
                          </li>
                          <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        </ul>
                      </nav>
                      <button
                        type="button"
                        class="th-menu-toggle d-inline-block d-lg-none"
                      >
                        <i class="far fa-bars"></i>
                      </button>
                    </div>
                    <div class="col-auto d-none d-xl-block">
                      <div class="header-button">
                        <button type="button" class="icon-btn searchBoxToggler">
                          <i class="far fa-search"></i>
                        </button>
                        <a href="{{ route('contact') }}" class="th-btn style-new ml-15"
                          >GET A QUOTE<i class="fas fa-arrow-right ms-2"></i
                        ></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
