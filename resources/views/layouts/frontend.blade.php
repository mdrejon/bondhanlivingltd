<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Hotel Beach Way – Luxury Hotel Near Kolatoli Beach, Cox\'s Bazar')</title>
    <meta name="description" content="@yield('meta_description', 'Hotel Beach Way – A boutique hotel with world-class amenities, just steps from the longest unbroken sandy sea beach in the world.')">
    @hasSection('meta_keywords')<meta name="keywords" content="@yield('meta_keywords')">@endif
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Open Graph / Social -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="Hotel Beach Way" />
    <meta property="og:title" content="@yield('og_title', config('app.name', 'Hotel Beach Way'))" />
    <meta property="og:description" content="@yield('og_description', 'Hotel Beach Way – A boutique hotel with world-class amenities, just steps from the longest unbroken sandy sea beach in the world.')" />
    <meta property="og:image" content="@yield('og_image', asset('assets/images/og-default.jpg'))" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield('og_title', config('app.name', 'Hotel Beach Way'))" />
    <meta name="twitter:description" content="@yield('og_description', 'Hotel Beach Way – A boutique hotel with world-class amenities, just steps from the longest unbroken sandy sea beach in the world.')" />
    <meta name="twitter:image" content="@yield('og_image', asset('assets/images/og-default.jpg'))" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/main.css') }}">
    <style>
        /* Floating back-to-top reuses the .cw-trigger look; hidden until the page is scrolled */
        #backToTop { opacity: 0; visibility: hidden; pointer-events: none; }
        #backToTop.visible { opacity: 1; visibility: visible; pointer-events: auto; }
    </style>
    <script defer src="{{ asset('assets/main.js') }}"></script>

    @stack('styles')
</head>
<body>

    @include('components.frontend.nav')

    <main>
        @yield('content')
    </main>

    @include('components.frontend.footer')

    {{-- ===========
         SIDEBAR POPUP (About Us + Get A Free Quote)
    =========== --}}
    <div class="sidebar-overlay" id="sidebarOverlay" aria-hidden="true"></div>
    <aside class="sidebar-drawer" id="sidebarDrawer" role="dialog" aria-modal="true" aria-label="Sidebar">
        <button class="sidebar-close" id="sidebarClose" aria-label="Close sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>

        <div class="sidebar-body">
            {{-- About section --}}
            <div class="sidebar-about">
                <h3 class="sidebar-section-title">{{ $popupSettings['sidebar_about_title'] ?? 'About Us' }}</h3>
                <div class="sidebar-divider"></div>
                <p class="sidebar-about-text">{{ $popupSettings['sidebar_about_text'] ?? "Hotel Beach Way is a premier luxury destination nestled along the golden sands of Kolatoli Beach, Cox's Bazar. We blend modern comfort with traditional hospitality, offering an unforgettable stay for every guest since 2013." }}</p>
            </div>

            {{-- Get A Free Quote form --}}
            <div class="sidebar-quote">
                <h3 class="sidebar-section-title">{{ $popupSettings['sidebar_quote_title'] ?? 'Get A Free Quote' }}</h3>
                <div class="sidebar-divider"></div>

                <div id="sidebarFormError" style="display:none;background:rgba(255,80,80,0.12);border:1px solid rgba(255,80,80,0.3);color:#ffaaaa;padding:10px 14px;border-radius:6px;font-size:13px;margin-bottom:12px;"></div>
                <div id="sidebarFormSuccess" style="display:none;background:rgba(100,220,100,0.12);border:1px solid rgba(100,220,100,0.3);color:#8de08d;padding:14px 16px;border-radius:6px;font-size:13.5px;margin-bottom:12px;text-align:center;"></div>

                <form id="sidebarQuoteForm" class="sidebar-form" novalidate>
                    <x-honeypot />
                    <div class="sf-field">
                        <input type="text" name="name" placeholder="Name" required>
                    </div>
                    <div class="sf-field">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="sf-field">
                        <textarea name="message" placeholder="Message..." rows="4" required></textarea>
                    </div>
                    <button type="submit" class="sf-submit" id="sidebarSubmitBtn">
                        {{ $popupSettings['sidebar_quote_btn_text'] ?? 'Submit Now' }}
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Floating back to top button --}}
    <button class="cw-trigger" id="backToTop" aria-label="Go back to top">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
    </button>

    @stack('scripts')

    {{-- Sidebar JS --}}
    <script>
    (function () {
        var csrfToken = document.querySelector('meta[name="csrf-token"]')
                        ? document.querySelector('meta[name="csrf-token"]').content : '';
        var inquiryUrl = '{{ route("inquiry.store") }}';

        // ── SIDEBAR ──────────────────────────────────────────
        var sidebarToggle  = document.getElementById('sidebarToggle');
        var sidebarOverlay = document.getElementById('sidebarOverlay');
        var sidebarDrawer  = document.getElementById('sidebarDrawer');
        var sidebarClose   = document.getElementById('sidebarClose');

        function openSidebar() {
            sidebarDrawer.classList.add('is-open');
            sidebarOverlay.classList.add('is-open');
            sidebarOverlay.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebarDrawer.classList.remove('is-open');
            sidebarOverlay.classList.remove('is-open');
            sidebarOverlay.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        if (sidebarToggle)  sidebarToggle.addEventListener('click', openSidebar);
        if (sidebarClose)   sidebarClose.addEventListener('click', closeSidebar);
        if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);

        // Sidebar quote form submit
        var sidebarForm    = document.getElementById('sidebarQuoteForm');
        var sidebarErrBox  = document.getElementById('sidebarFormError');
        var sidebarOkBox   = document.getElementById('sidebarFormSuccess');
        var sidebarSubmit  = document.getElementById('sidebarSubmitBtn');

        if (sidebarForm) {
            sidebarForm.addEventListener('submit', function (e) {
                e.preventDefault();
                if (!sidebarForm.checkValidity()) { sidebarForm.reportValidity(); return; }

                sidebarErrBox.style.display = 'none';
                sidebarOkBox.style.display  = 'none';
                sidebarSubmit.disabled = true;

                var fd = new FormData(sidebarForm);
                fetch(inquiryUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({
                        type: 'quote',
                        name: fd.get('name'),
                        email: fd.get('email'),
                        message: fd.get('message'),
                        company_website: fd.get('company_website'),
                        form_rendered_at: fd.get('form_rendered_at'),
                    }),
                })
                .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, data: d }; }); })
                .then(function (r) {
                    sidebarSubmit.disabled = false;
                    if (r.ok && r.data.success) {
                        sidebarForm.reset();
                        sidebarOkBox.textContent = r.data.message;
                        sidebarOkBox.style.display = 'block';
                    } else {
                        var msg = (r.data.errors ? Object.values(r.data.errors).flat().join(' ') : null) || r.data.message || 'Something went wrong.';
                        sidebarErrBox.textContent = msg;
                        sidebarErrBox.style.display = 'block';
                    }
                })
                .catch(function () {
                    sidebarSubmit.disabled = false;
                    sidebarErrBox.textContent = 'Network error. Please try again.';
                    sidebarErrBox.style.display = 'block';
                });
            });
        }

        // Escape key closes sidebar
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') { closeSidebar(); }
        });
    })();
    </script>

</body>
</html>
