<!-- ===========
     TOP HEADER BAR
=========== -->
<div class="top-header">
    <div class="th-dark">
        <div class="th-contact-items">
            @if($headerSettings['header_phone'])
            <a href="tel:{{ $headerSettings['header_phone'] }}" class="th-item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                </svg>
                {{ $headerSettings['header_phone'] }}
            </a>
            @endif
            @if($headerSettings['header_email'])
            <span class="th-sep">|</span>
            <a href="mailto:{{ $headerSettings['header_email'] }}" class="th-item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                </svg>
                {{ $headerSettings['header_email'] }}
            </a>
            @endif
            @if($headerSettings['header_address'])
            <span class="th-sep">|</span>
            <span class="th-item">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                {{ $headerSettings['header_address'] }}
            </span>
            @endif
        </div>
    </div>

    <div class="th-gold">
        <div class="th-gold-right">
            <div class="th-social">
                @if($headerSettings['header_twitter_url'])
                <a href="{{ $headerSettings['header_twitter_url'] }}" aria-label="Twitter">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                @endif
                @if($headerSettings['header_facebook_url'])
                <a href="{{ $headerSettings['header_facebook_url'] }}" aria-label="Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                </a>
                @endif
                @if($headerSettings['header_instagram_url'])
                <a href="{{ $headerSettings['header_instagram_url'] }}" aria-label="Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
                    </svg>
                </a>
                @endif
                @if($headerSettings['header_pinterest_url'])
                <a href="{{ $headerSettings['header_pinterest_url'] }}" aria-label="Pinterest">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.632-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0z"/></svg>
                </a>
                @endif
            </div>
        </div>
    </div>
</div><!-- /top-header -->


<!-- ===========
     MAIN NAVIGATION
=========== -->
<header class="main-header">
    <nav class="main-nav">
        <!-- Logo -->
        <div class="nav-logo">
            <a href="{{ route('home') }}" class="logo-oval">
                @if($headerSettings['header_logo'])
                    <img src="{{ asset('storage/' . $headerSettings['header_logo']) }}" alt="Hotel Beach Way">
                @else
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Hotel Beach Way">
                @endif
            </a>
        </div>

        <!-- Desktop menu -->
        <ul class="nav-menu" id="navMenu">

            <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}" class="nav-link">Home</a>
            </li>

            {{-- <li class="nav-item {{ request()->routeIs('about') ? 'active' : '' }}">
                <a href="{{ route('about') }}" class="nav-link">About Us</a>
            </li> --}}
            <li class="nav-item has-dropdown {{ request()->routeIs('about') || request()->routeIs('history') || request()->routeIs('facilities.front') || request()->routeIs('faqs.front') ? 'active' : '' }}">
                <a href="{{ route('about') }}" class="nav-link">About Us <svg class="chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg></a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">Company Profile</a></li>
                    <li><a href="{{ route('history') }}" class="{{ request()->routeIs('history') ? 'active' : '' }}">Our History</a></li>
                    <li><a href="{{ route('facilities.front') }}" class="{{ request()->routeIs('facilities.front') ? 'active' : '' }}">Our Facilities</a></li>
                    <li><a href="{{ route('faqs.front') }}" class="{{ request()->routeIs('faqs.front') ? 'active' : '' }}">FAQs</a></li>
                </ul>
            </li>

            <li class="nav-item has-dropdown {{ request()->routeIs('rooms*') ? 'active' : '' }}">
                <a href="{{ route('rooms') }}" class="nav-link">
                    Rooms &amp; Suites
                    <svg class="chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('rooms') }}">All Rooms</a></li>
                    @foreach($navRoomTypes as $rt)
                    <li><a href="{{ route('rooms.detail', $rt->slug) }}" class="{{ request()->is('rooms/' . $rt->slug) ? 'active' : '' }}">{{ $rt->name }}</a></li>
                    @endforeach
                </ul>
            </li>

            <li class="nav-item has-dropdown {{ request()->routeIs('services.front') || request()->routeIs('service.detail') ? 'active' : '' }}">
                <a href="{{ route('services.front') }}" class="nav-link">
                    Services
                    <svg class="chevron" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="{{ route('services.front') }}">All Services</a></li>
                    @foreach($navServices as $svc)
                    <li><a href="{{ route('service.detail', $svc->slug) }}">{{ $svc->title }}</a></li>
                    @endforeach
                </ul>
            </li>

            <li class="nav-item {{ request()->routeIs('gallery.front') ? 'active' : '' }}">
                <a href="{{ route('gallery.front') }}" class="nav-link">Gallery</a>
            </li>

            <li class="nav-item {{ request()->routeIs('blog*') ? 'active' : '' }}">
                <a href="{{ route('blog.front') }}" class="nav-link">Blog</a>
            </li>

            <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                <a href="{{ route('contact') }}" class="nav-link">Contact</a>
            </li>

        </ul>

        <!-- Nav right side -->
        <div class="nav-right">
            <button class="nav-icon-btn hamburger-btn" aria-label="Menu" id="mobileMenuToggle">
                <span></span><span></span><span></span>
            </button>

            <button class="sidebar-toggle-btn" aria-label="Open sidebar" id="sidebarToggle">
                <span></span><span></span><span></span>
            </button>

            <a href="{{ !empty($headerSettings['header_book_btn_url']) && !str_starts_with($headerSettings['header_book_btn_url'], '#') ? $headerSettings['header_book_btn_url'] : route('booking') }}" class="btn-booking">
                {{ $headerSettings['header_book_btn_text'] ?? 'Book Online' }}
            </a>
        </div>
    </nav>

    <!-- Mobile menu drawer -->
    <div class="mobile-menu" id="mobileMenu">
        <ul class="mobile-nav-list">
            <li class="mobile-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}">Home</a>
            </li>
            <li class="mobile-item has-sub {{ request()->routeIs('about') || request()->routeIs('history') || request()->routeIs('facilities.front') || request()->routeIs('faqs.front') ? 'active' : '' }}">
                <a href="{{ route('about') }}">About Us</a>
                <ul class="mobile-sub">
                    <li><a href="{{ route('about') }}">Company Profile</a></li>
                    <li><a href="{{ route('history') }}">Our History</a></li>
                    <li><a href="{{ route('facilities.front') }}">Our Facilities</a></li>
                    <li><a href="{{ route('faqs.front') }}">FAQs</a></li>
                </ul>
            </li>
            <li class="mobile-item has-sub {{ request()->routeIs('rooms*') ? 'active' : '' }}">
                <a href="{{ route('rooms') }}">Rooms &amp; Suites</a>
                <ul class="mobile-sub">
                    <li><a href="{{ route('rooms') }}">All Rooms</a></li>
                    @foreach($navRoomTypes as $rt)
                    <li><a href="{{ route('rooms.detail', $rt->slug) }}">{{ $rt->name }}</a></li>
                    @endforeach
                </ul>
            </li>
            <li class="mobile-item has-sub {{ request()->routeIs('services.front') || request()->routeIs('service.detail') ? 'active' : '' }}">
                <a href="{{ route('services.front') }}">Services</a>
                <ul class="mobile-sub">
                    <li><a href="{{ route('services.front') }}">All Services</a></li>
                    @foreach($navServices as $svc)
                    <li><a href="{{ route('service.detail', $svc->slug) }}">{{ $svc->title }}</a></li>
                    @endforeach
                </ul>
            </li>
            <li class="mobile-item {{ request()->routeIs('gallery.front') ? 'active' : '' }}">
                <a href="{{ route('gallery.front') }}">Gallery</a>
            </li>
            <li class="mobile-item {{ request()->routeIs('blog*') ? 'active' : '' }}">
                <a href="{{ route('blog.front') }}">Blog</a>
            </li>
            <li class="mobile-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                <a href="{{ route('contact') }}">Contact</a>
            </li>
        </ul>
    </div>
</header><!-- /main-header -->
