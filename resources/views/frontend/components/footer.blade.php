@php
function getFooterImgUrl($path, $default) {
    if (empty($path)) return asset($default);
    if (str_starts_with($path, 'assets/')) return asset($path);
    return asset('storage/' . $path);
}

$footer_partners = json_decode(\App\Models\GlobalSetting::get('footer_partners', '[]'), true);
$footer_quick_links = json_decode(\App\Models\GlobalSetting::get('footer_quick_links', '[]'), true);
$footer_gallery = json_decode(\App\Models\GlobalSetting::get('footer_gallery', '[]'), true);
@endphp

    <!-- Start Partner Area -->
    @if(is_array($footer_partners) && count($footer_partners) > 0)
    <div class="partner-area ptb-100">
      <div class="container">
        <div class="partner-slider owl-theme owl-carousel">
          @foreach($footer_partners as $partner)
          <div class="partner-item">
            <a href="{{ $partner['url'] ?? '#' }}">
              <img src="{{ getFooterImgUrl($partner['image'] ?? null, 'assets/img/client/cilent_1_1.png') }}" alt="Partner Logo" />
            </a>
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @endif
    <!-- End Partner Area -->

    <footer class="footer-wrapper footer-layout5 bg-title" id="contact-sec">
      <div class="footer-top">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-xl-3">
              <div class="footer-logo">
                <img src="{{ getFooterImgUrl(\App\Models\GlobalSetting::get('footer_logo'), 'assets/img/logo-white.png') }}" alt="Bondhon" />
              </div>
            </div>
            <div class="col-xl-9">
              <div class="subscribe-box">
                <div>
                  <p class="subscribe-box_text">{{ \App\Models\GlobalSetting::get('footer_newsletter_subtitle', 'READY FOR A SUBSCRIPTION?') }}</p>
                  <h4 class="subscribe-box_title">{{ \App\Models\GlobalSetting::get('footer_newsletter_title', 'Subcribe Our Latest News') }}</h4>
                </div>
                <form class="newsletter-form">
                  <input
                    class="form-control style-radious"
                    type="email"
                    placeholder="Enter Email Address"
                    required=""
                  />
                  <button
                    type="submit"
                    class="th-btn style3 style-new shadow-none"
                  >
                    SUBCRIBE<i class="fas fa-arrow-right ms-2"></i>
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="widget-area">
        <div class="container">
          <div class="row justify-content-between">
            <div class="col-md-6 col-xl-3">
              <div class="widget footer-widget">
                <h3 class="widget_title">ABOUT COMPANY</h3>
                <div class="th-widget-about">
                  <p class="about-text">
                    {{ \App\Models\GlobalSetting::get('footer_about_text', 'Bondhan Living Ltd is a trusted real estate and construction company based in Chittagong, committed to building quality homes and commercial spaces across Bangladesh.') }}
                  </p>
                  <div class="th-social style3">
                    <a href="{{ \App\Models\GlobalSetting::get('social_facebook', 'https://www.facebook.com/bondhanlivingltd/') }}"
                      ><i class="fab fa-facebook-f"></i
                    ></a>
                    <a href="{{ \App\Models\GlobalSetting::get('social_twitter', 'https://www.twitter.com/') }}"
                      ><i class="fab fa-twitter"></i
                    ></a>
                    <a href="{{ \App\Models\GlobalSetting::get('social_linkedin', 'https://www.linkedin.com/') }}"
                      ><i class="fab fa-linkedin-in"></i
                    ></a>
                    <a href="{{ \App\Models\GlobalSetting::get('footer_whatsapp', 'https://www.whatsapp.com/') }}"
                      ><i class="fab fa-whatsapp"></i
                    ></a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xl-auto">
              <div class="widget widget_nav_menu footer-widget">
                <h3 class="widget_title">QUICK LINKS</h3>
                <div class="menu-all-pages-container">
                  <ul class="menu">
                    @if(is_array($footer_quick_links) && count($footer_quick_links) > 0)
                        @foreach($footer_quick_links as $link)
                        <li><a href="{{ $link['url'] ?? '#' }}">{{ $link['name'] ?? '' }}</a></li>
                        @endforeach
                    @else
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('services.index') }}">Services</a></li>
                        <li><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li><a href="{{ route('blog') }}">Blog Post</a></li>
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                    @endif
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xl-3">
              <div class="widget footer-widget">
                <h3 class="widget_title">CONTACT NOW</h3>
                <div class="th-widget-contact">
                  <div class="info-box-wrap">
                    <div class="info-box_icon">
                      <i class="fas fa-location-dot"></i>
                    </div>
                    <p class="info-box_text">
                      {{ \App\Models\GlobalSetting::get('footer_address', 'House No. 18/B, (1st Floor) Mehedibag Road, Chittagong') }}
                    </p>
                  </div>
                  <div class="info-box-wrap">
                    <div class="info-box_icon">
                      <i class="fas fa-envelope"></i>
                    </div>
                    <a href="mailto:{{ \App\Models\GlobalSetting::get('footer_email', 'info@bondhanlivingltd.com') }}" class="info-box_link"
                      >{{ \App\Models\GlobalSetting::get('footer_email', 'info@bondhanlivingltd.com') }}</a
                    >
                  </div>
                  <div class="info-box-wrap">
                    <div class="info-box_icon">
                      <i class="fas fa-phone"></i>
                    </div>
                    <a href="tel:{{ \App\Models\GlobalSetting::get('footer_phone', '+8801740 574 490') }}" class="info-box_link"
                      >{{ \App\Models\GlobalSetting::get('footer_phone', '+8801740 574 490') }}</a
                    >
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-xl-auto">
              <div class="widget footer-widget">
                <h4 class="widget_title">GALLERY POSTS</h4>
                <div class="sidebar-gallery">
                    @if(is_array($footer_gallery) && count($footer_gallery) > 0)
                        @foreach($footer_gallery as $item)
                        <div class="gallery-thumb">
                            <img
                            src="{{ getFooterImgUrl($item['image'] ?? null, 'assets/img/update1/widget/gal-1-1.jpg') }}"
                            alt="Gallery Image"
                            class="w-100"
                            />
                        </div>
                        @endforeach
                    @else
                        <!-- Fallback Gallery -->
                        <div class="gallery-thumb">
                            <img src="{{ asset('assets/img/update1/widget/gal-1-1.jpg') }}" alt="Gallery Image" class="w-100" />
                        </div>
                        <div class="gallery-thumb">
                            <img src="{{ asset('assets/img/update1/widget/gal-1-2.jpg') }}" alt="Gallery Image" class="w-100" />
                        </div>
                        <div class="gallery-thumb">
                            <img src="{{ asset('assets/img/update1/widget/gal-1-3.jpg') }}" alt="Gallery Image" class="w-100" />
                        </div>
                        <div class="gallery-thumb">
                            <img src="{{ asset('assets/img/update1/widget/gal-1-4.jpg') }}" alt="Gallery Image" class="w-100" />
                        </div>
                        <div class="gallery-thumb">
                            <img src="{{ asset('assets/img/update1/widget/gal-1-5.jpg') }}" alt="Gallery Image" class="w-100" />
                        </div>
                        <div class="gallery-thumb">
                            <img src="{{ asset('assets/img/update1/widget/gal-1-6.jpg') }}" alt="Gallery Image" class="w-100" />
                        </div>
                    @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="copyright-wrap">
        <div class="container">
          <div class="row justify-content-between align-items-center">
            <div class="col-lg-6">
              <p class="copyright-text">
                {{ \App\Models\GlobalSetting::get('footer_copyright', '© 2026 Bondhan Living Limited. All Rights Reserved. | Design & Development by Wexnix Technologies Ltd.') }}
              </p>
            </div>
            <div class="col-lg-6 text-end d-none d-lg-block">
              <div class="footer-links">
                <ul>
                  <li><a href="{{ \App\Models\GlobalSetting::get('footer_privacy_url', '#') }}">Privacy Policy</a></li>
                  <li><a href="{{ \App\Models\GlobalSetting::get('footer_terms_url', '#') }}">Terms of Use</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <div class="scroll-top">
      <svg
        class="progress-circle svg-content"
        width="100%"
        height="100%"
        viewBox="-1 -1 102 102"
      >
        <path
          d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
          style="
            transition: stroke-dashoffset 10ms linear 0s;
            stroke-dasharray: 307.919, 307.919;
            stroke-dashoffset: 307.919;
          "
        ></path>
      </svg>
    </div>
