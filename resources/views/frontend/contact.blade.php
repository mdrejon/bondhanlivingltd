@extends('layouts.frontend')

@section('title', !empty($settings['contact_seo_title']) ? $settings['contact_seo_title'] : 'Contact Us – Hotel Beach Way')
@section('meta_description', !empty($settings['contact_seo_description']) ? $settings['contact_seo_description'] : 'Get in touch with Hotel Beach Way — Cox\'s Bazar\'s premier coastal retreat. Reach our team for bookings, enquiries, and support.')
@if(!empty($settings['contact_seo_keywords']))
@section('meta_keywords', $settings['contact_seo_keywords'])
@endif
@section('og_title', !empty($settings['contact_seo_title']) ? $settings['contact_seo_title'] : 'Contact Us – Hotel Beach Way')
@section('og_description', !empty($settings['contact_seo_description']) ? $settings['contact_seo_description'] : 'Get in touch with Hotel Beach Way — Cox\'s Bazar\'s premier coastal retreat. Reach our team for bookings, enquiries, and support.')
@if(!empty($settings['contact_seo_og_image']))
@section('og_image', asset('storage/' . $settings['contact_seo_og_image']))
@elseif(!empty($settings['contact_hero_image']))
@section('og_image', asset('storage/' . $settings['contact_hero_image']))
@endif

@php
  $heroImage   = $settings['contact_hero_image'] ?? null;
  $heroTitle   = $settings['contact_hero_title'] ?? 'Contact Us';

  $phone1      = $settings['footer_phone_1']      ?? '+88 01777-909595';
  $email1      = $settings['footer_email_1']      ?? 'info@hotelbeachway.com';
  $addrLine1   = $settings['footer_address_line1'] ?? 'House #21, Block #C, Kolatoli Road';
  $addrLine2   = $settings['footer_address_line2'] ?? "Cox's Bazar, Bangladesh";

  $fbUrl       = $settings['footer_facebook_url']   ?? '#';
  $twUrl       = $settings['footer_twitter_url']    ?? '#';
  $igUrl       = $settings['footer_instagram_url']  ?? '#';

  $agentName   = $settings['contact_agent_name']     ?? 'Md. Kamal Hossain';
  $agentDesc   = $settings['contact_agent_desc']     ?? "I'm your dedicated reservation manager for Hotel Beach Way. Here to answer all your questions and assist with your booking needs around the clock.";
  $agentAvatar = $settings['contact_agent_avatar']   ?? null;
  $agentWa     = $settings['contact_agent_whatsapp'] ?? 'https://wa.me/8801777909595';

  $formBadge   = $settings['contact_form_badge']    ?? 'SEND US EMAIL';
  $formTitle   = $settings['contact_form_title']    ?? 'Feel Free To Write';
  $formBtn     = $settings['contact_form_btn_text'] ?? 'Send Message';

  $mapEmbed    = $settings['contact_map_embed'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3691.4!2d92.0014!3d21.4272!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30ada27fae0614f1%3A0x50e5e20eb3e9ec7!2sKolatoli%20Beach%2C%20Cox\'s%20Bazar%2C%20Bangladesh!5e0!3m2!1sen!2sbd!4v1718000000000!5m2!1sen!2sbd';
@endphp

@section('title', !empty($settings['contact_seo_title']) ? $settings['contact_seo_title'] : 'Contact Us – Hotel Beach Way')
@section('meta_description', !empty($settings['contact_seo_description']) ? $settings['contact_seo_description'] : 'Get in touch with Hotel Beach Way, Cox\'s Bazar. We\'re happy to help you plan your perfect stay.')
@if(!empty($settings['contact_seo_keywords']))
@section('meta_keywords', $settings['contact_seo_keywords'])
@endif
@section('og_title', !empty($settings['contact_seo_title']) ? $settings['contact_seo_title'] : 'Contact Us – Hotel Beach Way')
@section('og_description', !empty($settings['contact_seo_description']) ? $settings['contact_seo_description'] : 'Get in touch with Hotel Beach Way, Cox\'s Bazar. We\'re happy to help you plan your perfect stay.')
@if(!empty($settings['contact_seo_og_image']))
@section('og_image', asset('storage/' . $settings['contact_seo_og_image']))
@endif

@section('content')

  <!-- ===========
       PAGE BREADCRUMB HERO
  =========== -->
  <section class="page-hero">
    <div class="page-hero-bg"
      @if($heroImage)
        style="background-image: url('{{ asset('storage/' . $heroImage) }}'); background-size: cover; background-position: center;"
      @endif
    ></div>
    <div class="page-hero-overlay"></div>
    <div class="page-hero-content">
      <h1 class="page-hero-title">{{ $heroTitle }}</h1>
      <nav class="page-breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span class="bc-sep">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </span>
        <span class="bc-current">{{ $heroTitle }}</span>
      </nav>
    </div>
    <div class="page-hero-wave" aria-hidden="true">
      <svg viewBox="0 0 1440 80" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,40 C360,75 720,5 1080,40 C1260,58 1380,18 1440,40 L1440,80 L0,80 Z" fill="#ffffff"/>
      </svg>
    </div>
  </section>


  <!-- ===========
       CONTACT SECTION
  =========== -->
  <section class="contact-section">
    <div class="contact-inner">

      <!-- =========== LEFT COLUMN =========== -->
      <div class="contact-left">

        <!-- Info card: Call Now -->
        <div class="ct-info-card" data-reveal="left">
          <div class="ct-info-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81a19.79 19.79 0 01-3.07-8.67A2 2 0 012 1h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 8.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
          </div>
          <div>
            <div class="ct-info-label">Call Now</div>
            <div class="ct-info-value">{{ $phone1 }}</div>
          </div>
        </div>

        <!-- Info card: Email -->
        <div class="ct-info-card" data-reveal="left" data-reveal-delay="1">
          <div class="ct-info-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </div>
          <div>
            <div class="ct-info-label">Email</div>
            <div class="ct-info-value">{{ $email1 }}</div>
          </div>
        </div>

        <!-- Info card: Address -->
        <div class="ct-info-card" data-reveal="left" data-reveal-delay="2">
          <div class="ct-info-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <div>
            <div class="ct-info-label">Address</div>
            <div class="ct-info-value">{{ $addrLine1 }}<br>{{ $addrLine2 }}</div>
          </div>
        </div>

        <!-- Divider -->
        <hr class="ct-divider" data-reveal="fade" data-reveal-delay="3">

        <!-- Follow Us Today -->
        <div class="ct-social-row" data-reveal="up" data-reveal-delay="3">
          <span class="ct-social-label">Follow Us Today :</span>
          <div class="ct-social-links">
            @if($igUrl)
            <a href="{{ $igUrl }}" target="_blank" rel="noopener" class="ct-social-btn" aria-label="Instagram">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
            </a>
            @endif
            @if($fbUrl)
            <a href="{{ $fbUrl }}" target="_blank" rel="noopener" class="ct-social-btn" aria-label="Facebook">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
            </a>
            @endif
            @if($twUrl)
            <a href="{{ $twUrl }}" target="_blank" rel="noopener" class="ct-social-btn" aria-label="Twitter / X">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.746l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
            @endif
          </div>
        </div>

        <!-- Agent card -->
        <div class="ct-agent-card" data-reveal="up" data-reveal-delay="4">
          <div class="ct-agent-avatar">
            @if($agentAvatar)
              <img src="{{ asset('storage/' . $agentAvatar) }}" alt="{{ $agentName }}">
            @else
              <img src="{{ asset('assets/images/testimonials-3-1.jpg') }}" alt="{{ $agentName }}">
            @endif
          </div>
          <h3 class="ct-agent-name">{{ $agentName }}</h3>
          <p class="ct-agent-desc">{{ $agentDesc }}</p>
          <a href="{{ $agentWa }}" target="_blank" rel="noopener" class="btn-whatsapp">Ask On WhatsApp</a>
        </div>

      </div><!-- /contact-left -->


      <!-- =========== RIGHT COLUMN =========== -->
      <div class="contact-right">

        <!-- Dark green email form -->
        <div class="ct-form-card" data-reveal="right">
          <span class="ct-form-badge">{{ $formBadge }}</span>
          <h2 class="ct-form-title">{{ $formTitle }}</h2>

          {{-- Success / Error feedback --}}
          <div id="ctFormSuccess" style="display:none;background:rgba(100,220,100,0.14);border:1px solid rgba(100,220,100,0.4);color:#8de08d;padding:14px 16px;border-radius:8px;font-size:14px;font-weight:600;margin-bottom:16px;text-align:center;"></div>
          <div id="ctFormError" style="display:none;background:rgba(255,80,80,0.14);border:1px solid rgba(255,80,80,0.4);color:#ffaaaa;padding:12px 14px;border-radius:8px;font-size:13px;font-weight:600;margin-bottom:16px;"></div>

          <form id="contactPageForm" action="{{ route('contact.submit') }}" method="POST" novalidate>
            @csrf
            <x-honeypot />
            <div class="ct-form-grid">
              <div class="ct-field">
                <label for="ctName">Name*</label>
                <input type="text" id="ctName" name="name" value="{{ old('name') }}" placeholder="Ex. John Smith" required>
              </div>
              <div class="ct-field">
                <label for="ctEmail">Email*</label>
                <input type="email" id="ctEmail" name="email" value="{{ old('email') }}" placeholder="Ex. info@example.com" required>
              </div>
              <div class="ct-field">
                <label for="ctPhone">Phone Number</label>
                <input type="tel" id="ctPhone" name="phone" value="{{ old('phone') }}" placeholder="Ex. 01777-909595">
              </div>
              <div class="ct-field">
                <label for="ctSubject">Subject*</label>
                <input type="text" id="ctSubject" name="subject" value="{{ old('subject') }}" placeholder="Ex. Room Inquiry" required>
              </div>
            </div>
            <div class="ct-field ct-field-full">
              <label for="ctMessage">Message*</label>
              <textarea id="ctMessage" name="message" placeholder="Ex. I would like to inquire about..." required>{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="btn-send-msg" id="ctSubmitBtn">{{ $formBtn }}</button>
          </form>
        </div>

        <!-- Google Map -->
        <div class="ct-map" data-reveal="up" data-reveal-delay="2">
          <iframe
            src="{{ $mapEmbed }}"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="Hotel Beach Way – Cox's Bazar">
          </iframe>
        </div>

      </div><!-- /contact-right -->

    </div><!-- /contact-inner -->
  </section>

@endsection

@push('scripts')
<script>
(function () {
    var form     = document.getElementById('contactPageForm');
    var okBox    = document.getElementById('ctFormSuccess');
    var errBox   = document.getElementById('ctFormError');
    var submitBtn = document.getElementById('ctSubmitBtn');
    var originalBtnText = submitBtn ? submitBtn.textContent : 'Send Message';

    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!form.checkValidity()) { form.reportValidity(); return; }

        okBox.style.display  = 'none';
        errBox.style.display = 'none';
        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending…';

        var csrf = document.querySelector('meta[name="csrf-token"]').content;
        var fd   = new FormData(form);

        fetch('{{ route("contact.submit") }}', {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: fd,
        })
        .then(function (r) {
            if (r.redirected || r.status === 302) {
                // Standard redirect response – treat as success
                form.reset();
                okBox.textContent = 'Thank you! Your message has been sent. We will get back to you within 24 hours.';
                okBox.style.display = 'block';
                okBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
                return;
            }
            return r.json().then(function (d) {
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
                if (r.ok) {
                    form.reset();
                    okBox.textContent = d.message || 'Thank you! Your message has been sent.';
                    okBox.style.display = 'block';
                    okBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                } else {
                    var msg = d.errors ? Object.values(d.errors).flat().join(' ') : (d.message || 'Something went wrong. Please try again.');
                    errBox.textContent = msg;
                    errBox.style.display = 'block';
                }
            });
        })
        .catch(function () {
            submitBtn.disabled = false;
            submitBtn.textContent = originalBtnText;
            errBox.textContent = 'Network error. Please check your connection and try again.';
            errBox.style.display = 'block';
        });
    });
})();
</script>
@endpush
