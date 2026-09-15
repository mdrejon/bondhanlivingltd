<!doctype html>
<html class="no-js" lang="zxx">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>@yield('title', 'Bondhan Living Ltd')</title>
    <meta name="author" content="@yield('meta_author', 'Bondhan Living Ltd')" />
    <meta
      name="description"
      content="@yield('meta_description', 'Bondhan Living Ltd - Construction & Real Estate Company')"
    />
    <meta
      name="keywords"
      content="@yield('meta_keywords', 'Bondhan Living Ltd - Construction & Real Estate Company')"
    />
    <meta name="robots" content="INDEX,FOLLOW" />
    <meta
      name="viewport"
      content="width=device-width,initial-scale=1,shrink-to-fit=no"
    />
    
    <link
      rel="apple-touch-icon"
      sizes="57x57"
      href="{{ asset('assets/img/favicons/apple-icon-57x57.png') }}"
    />
    <link
      rel="apple-touch-icon"
      sizes="60x60"
      href="{{ asset('assets/img/favicons/apple-icon-60x60.png') }}"
    />
    <link
      rel="apple-touch-icon"
      sizes="72x72"
      href="{{ asset('assets/img/favicons/apple-icon-72x72.png') }}"
    />
    <link
      rel="apple-touch-icon"
      sizes="76x76"
      href="{{ asset('assets/img/favicons/apple-icon-76x76.png') }}"
    />
    <link
      rel="apple-touch-icon"
      sizes="114x114"
      href="{{ asset('assets/img/favicons/apple-icon-114x114.png') }}"
    />
    <link
      rel="apple-touch-icon"
      sizes="120x120"
      href="{{ asset('assets/img/favicons/apple-icon-120x120.png') }}"
    />
    <link
      rel="apple-touch-icon"
      sizes="144x144"
      href="{{ asset('assets/img/favicons/apple-icon-144x144.png') }}"
    />
    <link
      rel="apple-touch-icon"
      sizes="152x152"
      href="{{ asset('assets/img/favicons/apple-icon-152x152.png') }}"
    />
    <link
      rel="apple-touch-icon"
      sizes="180x180"
      href="{{ asset('assets/img/favicons/apple-icon-180x180.png') }}"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="192x192"
      href="{{ asset('assets/img/favicons/android-icon-192x192.png') }}"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="32x32"
      href="{{ asset('assets/img/favicons/favicon-32x32.png') }}"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="96x96"
      href="{{ asset('assets/img/favicons/favicon-96x96.png') }}"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="16x16"
      href="{{ asset('assets/img/favicons/favicon-16x16.png') }}"
    />
    <link rel="manifest" href="{{ asset('assets/img/favicons/manifest.json') }}" />
    
    <meta name="msapplication-TileColor" content="#ffffff" />
    <meta
      name="msapplication-TileImage"
      content="{{ asset('assets/img/favicons/ms-icon-144x144.png') }}"
    />
    <meta name="theme-color" content="#ffffff" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Exo:wght@300;400;500;600;700;800;900&family=Public+Sans:wght@100;200;300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('assets/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/owl.theme.default.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/wexnix.css') }}" />
    @stack('styles')
  </head>
  <body class="wexnix-site">
    
    @include('frontend.components.header')

    @yield('content')

    @include('frontend.components.footer')

    <script src="{{ asset('assets/js/vendor/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('scripts')
  </body>
</html>
