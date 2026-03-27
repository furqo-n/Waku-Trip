<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description"
        content="{{ $meta_description ?? $__env->yieldContent('meta_description', 'Waku Trip – Discover Japan like never before. Explore curated tours, private experiences, and seasonal adventures across Tokyo, Kyoto, Osaka, and beyond.') }}">
    <title>{{ $page_title ?? $__env->yieldContent('page_title', 'Waku Trip - Discover the Soul of Japan') }}</title>
    <link rel="icon" href="{{ asset('img/Asset 2.png') }}" type="image/png">
    <link rel="canonical" href="{{ url()->current() }}">
    @include('partials.currency_init')

    <!-- Template Styles (Legacy) -->
    <!-- <link rel="stylesheet" href="{{ asset('vendors/bootstrap/bootstrap.min.css') }}"
    <link rel="stylesheet" href="{{ asset('vendors/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/themify-icons/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/linericon/style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/owl-carousel/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/owl-carousel/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/flat-icon/font/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/nice-select/nice-select.css') }}">> -->

    <!-- Modern Standards -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Fonts (Non-blocking via print media trick) -->
    <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Rock+Salt&family=Noto+Sans:wght@400;500;700&display=swap"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Rock+Salt&family=Noto+Sans:wght@400;500;700&display=swap">
    </noscript>

    <!-- Icons & Frameworks -->
    <!-- Bootstrap CSS is render-critical for layout, kept synchronous -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- Boxicons (used in various parts) - non-blocking -->
    <link rel="preload" as="style" href="https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.boxicons.com/3.0.6/fonts/basic/boxicons.min.css">
    </noscript>

    <!-- Material Icons (used by chatbot & others) - non-blocking -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/icon?family=Material+Icons"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    </noscript>

    <!-- Material Symbols – non-blocking (icons are not render-critical) -->
    <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
            rel="stylesheet">
    </noscript>

    <!-- Main Styles -->
    @vite('resources/css/style.css')
    @vite('resources/css/dashboard.css')
    @vite('resources/css/magnific-popup.css')
</head>