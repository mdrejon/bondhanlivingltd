<!-- ===========
     FOOTER
=========== -->
@php
    $fQuickLinks   = $footerSettings['footer_quick_links']   ?? [];
    $fServiceLinks = $footerSettings['footer_service_links'] ?? [];

    $defaultQuickLinks = [
        ['label' => 'About Us',                  'url' => route('about')],
        ['label' => 'Rooms & Suites',             'url' => route('rooms')],
        ['label' => 'Facilities & Services',      'url' => route('services.front')],
        ['label' => 'Dew Drop Restaurant',        'url' => route('services.front') . '#restaurant'],
        ['label' => 'Conference & Banquet',       'url' => route('services.front') . '#conference'],
        ['label' => 'Gallery',                    'url' => route('gallery.front')],
        ['label' => 'Contact Us',                 'url' => route('contact')],
    ];

    $defaultServiceLinks = [
        ['label' => '24/7 Front Desk',            'url' => '#'],
        ['label' => 'Airport Pick Up & Drop',     'url' => '#'],
        ['label' => 'Free Wi-Fi',                 'url' => '#'],
        ['label' => 'Room Service',               'url' => '#'],
        ['label' => 'Restaurant & BBQ',           'url' => '#'],
        ['label' => 'Conference & Banquet',       'url' => '#'],
        ['label' => 'Complimentary Breakfast',    'url' => '#'],
    ];

    $quickLinks   = !empty($fQuickLinks)   ? $fQuickLinks   : $defaultQuickLinks;
    $serviceLinks = !empty($fServiceLinks) ? $fServiceLinks : $defaultServiceLinks;
@endphp

<footer class="site-footer" id="contact">

    <!-- Newsletter row -->
    <div class="footer-newsletter">
        <div class="fn-inner" data-reveal="up">
            <div class="fn-text">
                <h2>{{ $footerSettings['footer_newsletter_title'] ?? 'Stay Updated With Hotel Beach Way Special Offers & Events' }}</h2>
            </div>
            <form class="fn-form">
                <input type="email" placeholder="Enter your email" aria-label="Email address" />
                <button type="submit">Subscribe</button>
            </form>
        </div>
    </div>

    <!-- Footer main columns -->
    <div class="footer-main">
        <div class="footer-inner" data-reveal="up" data-reveal-delay="2">

            <!-- Column 1 – Brand -->
            <div class="footer-col footer-brand">
                <a href="{{ route('home') }}" class="footer-logo-link">
                    @if($footerSettings['footer_logo'])
                        <img src="{{ asset('storage/' . $footerSettings['footer_logo']) }}" alt="Hotel Beach Way" style="width: 180px;">
                    @else
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Hotel Beach Way" style="width: 180px;">
                    @endif
                </a>
                <p class="footer-brand-desc">
                    {{ $footerSettings['footer_brand_description'] ?? 'A 3-star boutique hotel near Kolatoli Beach with eco-friendly infrastructure, skilled staff & world-class hospitality at affordable prices in Cox\'s Bazar.' }}
                </p>
                <div class="footer-social">
                    @if($footerSettings['footer_facebook_url'])
                    <a href="{{ $footerSettings['footer_facebook_url'] }}" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                    </a>
                    @endif
                    @if($footerSettings['footer_twitter_url'])
                    <a href="{{ $footerSettings['footer_twitter_url'] }}" aria-label="Twitter/X">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                    @endif
                    @if($footerSettings['footer_instagram_url'])
                    <a href="{{ $footerSettings['footer_instagram_url'] }}" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                    @endif
                    @if($footerSettings['footer_youtube_url'])
                    <a href="{{ $footerSettings['footer_youtube_url'] }}" aria-label="YouTube">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="#09091A"/></svg>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Column 2 – Quick Links -->
            <div class="footer-col">
                <h3 class="footer-col-title">Quick Links</h3>
                <ul class="footer-links">
                    @foreach($quickLinks as $link)
                        <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Column 3 – Services -->
            <div class="footer-col">
                <h3 class="footer-col-title">Our Services</h3>
                <ul class="footer-links">
                    @foreach($serviceLinks as $link)
                        <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>

            <!-- Column 4 – Official Info -->
            <div class="footer-col footer-info">
                <h3 class="footer-col-title">Official Info</h3>
                <ul class="footer-info-list">
                    @if($footerSettings['footer_phone_1'] || $footerSettings['footer_phone_2'] || $footerSettings['footer_phone_3'])
                    <li>
                        <span class="fi-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                        </span>
                        <div>
                            @if($footerSettings['footer_phone_1'])
                                <a href="tel:{{ preg_replace('/\s+/', '', $footerSettings['footer_phone_1']) }}">{{ $footerSettings['footer_phone_1'] }}</a>
                            @endif
                            @if($footerSettings['footer_phone_2'])
                                <a href="tel:{{ preg_replace('/\s+/', '', $footerSettings['footer_phone_2']) }}">{{ $footerSettings['footer_phone_2'] }}</a>
                            @endif
                            @if($footerSettings['footer_phone_3'])
                                <a href="tel:{{ preg_replace('/\s+/', '', $footerSettings['footer_phone_3']) }}">{{ $footerSettings['footer_phone_3'] }}</a>
                            @endif
                        </div>
                    </li>
                    @else
                    <li>
                        <span class="fi-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                        </span>
                        <div>
                            <a href="tel:+880034164858">034164858 / 034164464</a>
                            <a href="tel:+8801777909595">01777-909595 / 01617-909595</a>
                            <a href="tel:+8801849900000">01849-900000 / 01967-122422</a>
                        </div>
                    </li>
                    @endif

                    @if($footerSettings['footer_email_1'] || $footerSettings['footer_email_2'])
                    <li>
                        <span class="fi-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </span>
                        <div>
                            @if($footerSettings['footer_email_1'])
                                <a href="mailto:{{ $footerSettings['footer_email_1'] }}">{{ $footerSettings['footer_email_1'] }}</a>
                            @endif
                            @if($footerSettings['footer_email_2'])
                                <a href="mailto:{{ $footerSettings['footer_email_2'] }}">{{ $footerSettings['footer_email_2'] }}</a>
                            @endif
                        </div>
                    </li>
                    @else
                    <li>
                        <span class="fi-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        </span>
                        <div>
                            <a href="mailto:info@hotelbeachway.com">info@hotelbeachway.com</a>
                            <a href="mailto:infohotelbeachway@gmail.com">infohotelbeachway@gmail.com</a>
                        </div>
                    </li>
                    @endif

                    <li>
                        <span class="fi-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </span>
                        <div>
                            <span>{{ $footerSettings['footer_address_line1'] ?? 'House #21, Block #C, Kolatoli Road' }}</span>
                            <span>{{ $footerSettings['footer_address_line2'] ?? "Cox's Bazar, Bangladesh" }}</span>
                            @if($footerSettings['footer_website_url'])
                                <a href="{{ $footerSettings['footer_website_url'] }}" target="_blank" rel="noopener">{{ $footerSettings['footer_website_url'] }}</a>
                            @else
                                <a href="http://www.hotelbeachway.com" target="_blank" rel="noopener">www.hotelbeachway.com</a>
                            @endif
                        </div>
                    </li>
                </ul>
            </div>

        </div><!-- /footer-inner -->
    </div><!-- /footer-main -->

    <!-- Footer bottom bar -->
    <div class="footer-bottom">
        <div class="fb-inner">
            <p class="fb-copy">
                &copy; 2013–{{ date('Y') }} <a href="{{ route('home') }}">Hotel Beach Way</a>. All Rights Reserved.
            </p>
            <nav class="fb-nav">
                <a href="{{ $footerSettings['footer_privacy_url'] ?? '#' }}">Privacy Policy</a>
                <a href="{{ $footerSettings['footer_terms_url'] ?? '#' }}">Terms &amp; Conditions</a>
                <a href="#">Developed By Smart Framework</a>
            </nav>
        </div>
    </div>

</footer><!-- /site-footer -->


<!-- ===========
     CHAT WIDGET
=========== -->
<div class="chat-widget">
    <button class="chat-toggle-btn" id="chatToggle" aria-label="Open chat">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/>
        </svg>
    </button>

    <div class="chat-popup" id="chatPopup">
        <button class="chat-close" id="chatClose" aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
        <p class="chat-popup-intro">Have a query? Contact Hotel Beach Way — we are happy to help you plan your stay.</p>
        <p class="chat-form-error" id="chatFormError" style="display:none;color:#e74c3c;font-size:12.5px;margin:0 0 8px;"></p>
        <form class="chat-form" id="chatForm" action="{{ route('contact.submit') }}" method="POST">
            @csrf
            <x-honeypot />
            <input type="text"  name="name"    placeholder="Your Name"  required />
            <input type="email" name="email"   placeholder="Your Email" required />
            <textarea           name="message" placeholder="Your Message" rows="4" required></textarea>
            <button type="submit" class="chat-submit">Send Message</button>
        </form>
    </div>
</div><!-- /chat-widget -->

<script>
(function () {
    var form    = document.getElementById('chatForm');
    var errBox  = document.getElementById('chatFormError');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation(); // takes over from the legacy template's fake-success handler

        var btn = form.querySelector('.chat-submit');
        var originalText = btn.textContent;
        errBox.style.display = 'none';
        btn.disabled = true;
        btn.textContent = 'Sending...';

        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        var csrf = csrfMeta ? csrfMeta.content : form.querySelector('input[name="_token"]').value;
        var fd = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: fd,
        })
        .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
        .then(function (r) {
            btn.disabled = false;
            if (r.ok && r.data.success) {
                form.reset();
                btn.textContent = 'Sent! ✓';
                setTimeout(function () {
                    btn.textContent = originalText;
                    document.getElementById('chatPopup').classList.remove('open');
                }, 1500);
            } else {
                btn.textContent = originalText;
                var msg = (r.data.errors ? Object.values(r.data.errors).flat().join(' ') : null) || r.data.message || 'Something went wrong. Please try again.';
                errBox.textContent = msg;
                errBox.style.display = 'block';
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.textContent = originalText;
            errBox.textContent = 'Network error. Please check your connection and try again.';
            errBox.style.display = 'block';
        });
    });
})();
</script>


