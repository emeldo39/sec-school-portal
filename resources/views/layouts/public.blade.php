<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', \App\Models\SchoolSetting::get('school_name', 'School Portal'))</title>
    <meta name="description"
        content="@yield('meta-description', \App\Models\SchoolSetting::get('school_name') . ' — ' . \App\Models\SchoolSetting::get('school_motto', ''))">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}">

    {{-- Template CSS --}}
    <link rel="stylesheet" href="{{ asset('template/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/css/font-awesome-pro.css') }}">
    <link rel="stylesheet" href="{{ asset('template/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('template/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('template/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('template/css/custom-animation.css') }}">
    <link rel="stylesheet" href="{{ asset('template/css/spacing.css') }}">
    <link rel="stylesheet" href="{{ asset('template/css/main.css') }}">
    {{-- Color override — must be last --}}
    <link rel="stylesheet" href="{{ asset('template/css/portal-theme.css') }}">

    @stack('styles')
</head>

<body>

    {{-- ── Preloader ───────────────────────────────────────────────────── --}}
    <div id="preloader">
        <div class="preloader">
            <span></span>
            <span></span>
        </div>
    </div>

    {{-- ── Back to top ─────────────────────────────────────────────────── --}}
    <button class="scroll-top scroll-to-target" data-target="html">
        <i class="far fa-angle-double-up"></i>
    </button>

    {{-- ── Offcanvas (mobile menu) ─────────────────────────────────────── --}}
    <div class="it-offcanvas-area">
        <div class="itoffcanvas">
            <div class="itoffcanvas__close-btn">
                <button class="close-btn"><i class="fal fa-times"></i></button>
            </div>
            <div class="itoffcanvas__logo">
                @if(\App\Models\SchoolSetting::get('school_logo'))
                <a href="{{ route('home') }}">
                    <img src="{{ asset('storage/' . \App\Models\SchoolSetting::get('school_logo')) }}"
                        alt="{{ \App\Models\SchoolSetting::get('school_name') }}" style="width: 60px;">
                </a>
                @else
                <a href="{{ route('home') }}"
                    style="font-size:1.2rem;font-weight:700;color:var(--it-common-black);text-decoration:none;">
                    {{ \App\Models\SchoolSetting::get('school_name', 'School Portal') }}
                </a>
                @endif
            </div>
            <div class="itoffcanvas__text">
                <p>{{ Str::limit(\App\Models\SchoolSetting::get('about_text', 'Welcome to our school.'), 120) }}</p>
            </div>
            {{-- main.js clones .it-menu-content into this div --}}
            <div class="it-menu-mobile d-xl-none"></div>
            <div class="itoffcanvas__info">
                <h3 class="offcanva-title">Get In Touch</h3>
                @if(\App\Models\SchoolSetting::get('school_email'))
                <div class="it-info-wrapper mb-20 d-flex align-items-center">
                    <div class="itoffcanvas__info-icon">
                        <a href="#"><i class="fal fa-envelope"></i></a>
                    </div>
                    <div class="itoffcanvas__info-address">
                        <span>Email</span>
                        <a href="mailto:{{ \App\Models\SchoolSetting::get('school_email') }}" style="font-size: 14px;">
                            {{ \App\Models\SchoolSetting::get('school_email') }}
                        </a>
                    </div>
                </div>
                @endif
                @if(\App\Models\SchoolSetting::get('school_phone'))
                <div class="it-info-wrapper mb-20 d-flex align-items-center">
                    <div class="itoffcanvas__info-icon">
                        <a href="#"><i class="fal fa-phone-alt"></i></a>
                    </div>
                    <div class="itoffcanvas__info-address">
                        <span>Phone</span>
                        <a href="tel:{{ \App\Models\SchoolSetting::get('school_phone') }}" style="font-size: 14px;">
                            {{ \App\Models\SchoolSetting::get('school_phone') }}
                        </a>
                    </div>
                </div>
                @endif
                @if(\App\Models\SchoolSetting::get('school_address'))
                <div class="it-info-wrapper mb-20 d-flex align-items-center">
                    <div class="itoffcanvas__info-icon">
                        <a href="#"><i class="fas fa-map-marker-alt"></i></a>
                    </div>
                    <div class="itoffcanvas__info-address">
                        <span>Location</span>
                        <a href="#" style="font-size: 14px;">
                            {{ \App\Models\SchoolSetting::get('school_address') }}
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="body-overlay"></div>

    {{-- ── Header ──────────────────────────────────────────────────────── --}}
    @php
    $schoolName = \App\Models\SchoolSetting::get('school_name', 'School Portal');
    $schoolMotto = \App\Models\SchoolSetting::get('school_motto', '');
    $schoolLogo = \App\Models\SchoolSetting::get('school_logo');
    $schoolPhone = \App\Models\SchoolSetting::get('school_phone');
    $schoolEmail = \App\Models\SchoolSetting::get('school_email');
    @endphp

    <header class="it-header-height">

        {{-- Top bar --}}
        <div class="it-header-top-area black-bg it-header-top-ptb">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-7 col-sm-8">
                        <div
                            class="it-header-top-contact-wrap d-flex justify-content-center justify-content-sm-start align-items-center">
                            <ul>
                                @if($schoolPhone)
                                <li class="d-none d-sm-inline-block">
                                    <div class="it-header-top-contact d-flex align-items-center">
                                        <span><i class="fal fa-phone-alt"
                                                style="color:var(--it-theme-2);margin-right:6px;"></i></span>
                                        <a class="border-line" href="tel:{{ $schoolPhone }}">{{ $schoolPhone }}</a>
                                    </div>
                                </li>
                                @endif
                                @if($schoolEmail)
                                <li>
                                    <div class="it-header-top-contact d-flex align-items-center">
                                        <span><i class="fal fa-envelope"
                                                style="color:var(--it-theme-2);margin-right:6px;"></i></span>
                                        <a class="border-line" href="mailto:{{ $schoolEmail }}">{{ $schoolEmail }}</a>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-5 col-sm-4 d-none d-sm-block">
                        <div class="it-header-top-right-action d-flex align-items-center justify-content-end">
                            <div class="it-header-top-login-box d-none d-sm-block">
                                <a href="{{ route('login') }}" style="color:#fff;">
                                    <i class="fal fa-lock" style="margin-right:4px;"></i>Staff Portal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main navigation bar --}}
        <div id="header-sticky" class="it-header-area it-header-ptb p-relative">
            <div class="container">
                <div class="row align-items-center">

                    {{-- Logo --}}
                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-5 col-7">
                        <div class="it-header-logo">
                            <a href="{{ route('home') }}" class="d-flex align-items-center gap-2 text-decoration-none">
                                @if($schoolLogo)
                                <img src="{{ asset('storage/' . $schoolLogo) }}" alt="{{ $schoolName }}"
                                    style="max-height:60px;">
                                @else
                                <span style="display:inline-flex;align-items:center;justify-content:center;
                                             width:44px;height:44px;border-radius:8px;
                                             background:var(--it-theme-1);color:#fff;
                                             font-weight:700;font-size:1.1rem;flex-shrink:0;">
                                    {{ strtoupper(substr($schoolName, 0, 1)) }}
                                </span>
                                <span
                                    style="font-weight:700;color:var(--it-common-black);font-size:.95rem;line-height:1.2;">
                                    {{ $schoolName }}
                                    @if($schoolMotto)
                                    <small
                                        style="display:block;font-weight:400;font-size:.72rem;color:var(--it-text-body);font-style:italic;">{{ $schoolMotto }}</small>
                                    @endif
                                </span>
                                @endif
                            </a>
                        </div>
                    </div>

                    {{-- Desktop nav — cloned to mobile offcanvas by main.js --}}
                    <div class="col-xxl-7 col-xl-6 d-none d-xl-block">
                        <div class="it-header-menu it-header-dropdown">
                            <nav class="it-menu-content">
                                <ul>
                                    <li class="{{ request()->routeIs('home') ? 'it-current' : '' }}">
                                        <a href="{{ route('home') }}">Home</a>
                                    </li>
                                    <li class="{{ request()->routeIs('about') ? 'it-current' : '' }}">
                                        <a href="{{ route('about') }}">About Us</a>
                                    </li>
                                    <li class="{{ request()->routeIs('academics') ? 'it-current' : '' }}">
                                        <a href="{{ route('academics') }}">Academics</a>
                                    </li>
                                    <li class="{{ request()->routeIs('admissions') ? 'it-current' : '' }}">
                                        <a href="{{ route('admissions') }}">Admissions</a>
                                    </li>
                                    <li class="{{ request()->routeIs('staff') ? 'it-current' : '' }}">
                                        <a href="{{ route('staff') }}">Staff</a>
                                    </li>
                                    <li class="{{ request()->routeIs('gallery') ? 'it-current' : '' }}">
                                        <a href="{{ route('gallery') }}">Gallery</a>
                                    </li>
                                    <li class="{{ request()->routeIs('contact') ? 'it-current' : '' }}">
                                        <a href="{{ route('contact') }}">Contact</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>

                    {{-- Right actions: CTA + hamburger --}}
                    <div class="col-xxl-3 col-xl-3 col-lg-8 col-md-7 col-5">
                        <div class="it-header-right-action d-flex justify-content-end align-items-center">
                            <a href="{{ route('admissions') }}"
                                class="it-btn-yellow border-radius-100 d-none d-md-flex">
                                <span>
                                    <span class="text-1">Admission</span>
                                    <span class="text-2">Admission</span>
                                </span>
                                <i>
                                    <svg width="16" height="15" viewBox="0 0 16 15" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.0544 8.1364C15.4058 7.78492 15.4058 7.21508 15.0544 6.8636L9.3268 1.13604C8.97533 0.784567 8.40548 0.784567 8.05401 1.13604C7.70254 1.48751 7.70254 2.05736 8.05401 2.40883L13.1452 7.5L8.05401 12.5912C7.70254 12.9426 7.70254 13.5125 8.05401 13.864C8.40548 14.2154 8.97533 14.2154 9.3268 13.864L15.0544 8.1364ZM0.417969 7.5V8.4H14.418V7.5V6.6H0.417969V7.5Z"
                                            fill="currentcolor" />
                                    </svg>
                                </i>
                            </a>
                            <div class="it-header-bar d-xl-none">
                                <button class="it-menu-bar">
                                    <span><i class="fal fa-bars-staggered"></i></span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </header>

    {{-- ── Page Content ─────────────────────────────────────────────────── --}}
    @yield('content')



    {{-- ── Footer ──────────────────────────────────────────────────────── --}}
    <footer>
        <section class="it-footer-wrap fix">

            <div class="it-footer-area z-index-1 pt-130 pb-80"
                data-background="{{ asset('template/img/shape/footer-bg-1-1.jpg') }}">
                <img class="it-footer-shape-3" data-parallax='{"y": -80, "smoothness": 30}'
                    src="{{ asset('template/img/shape/footer-1-1.png') }}" alt="">
                <div class="it-footer-border"><span></span></div>
                <div class="container">
                    <div class="row">

                        {{-- Col 1: School info --}}
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-50 wow itfadeUp" data-wow-duration=".9s"
                            data-wow-delay=".3s">
                            <div class="it-footer-widget it-footer-col-1-1">
                                <div class="it-footer-widget-logo mb-30">
                                    @if($schoolLogo)
                                    <a href="{{ route('home') }}">
                                        <img src="{{ asset('storage/' . $schoolLogo) }}" alt="{{ $schoolName }}"
                                            style="max-height:52px;">
                                    </a>
                                    @else
                                    <a href="{{ route('home') }}"
                                        style="font-size:1.2rem;font-weight:800;color:var(--it-theme-1);text-decoration:none;">
                                        {{ $schoolName }}
                                    </a>
                                    @endif
                                </div>
                                <div class="it-footer-widget-text">
                                    <p>{{ Str::limit(\App\Models\SchoolSetting::get('about_text', 'Committed to nurturing the next generation of leaders through quality education, discipline, and academic excellence.'), 160) }}
                                    </p>
                                </div>
                                <div class="it-footer-widget-btn">
                                    <a href="{{ route('contact') }}" class="it-btn-yellow theme-bg border-radius-100">
                                        <span>
                                            <span class="text-1">Contact Us</span>
                                            <span class="text-2">Contact Us</span>
                                        </span>
                                        <i>
                                            <svg width="16" height="15" viewBox="0 0 16 15" fill="none">
                                                <path
                                                    d="M15.0544 8.1364C15.4058 7.78492 15.4058 7.21508 15.0544 6.8636L9.3268 1.13604C8.97533 0.784567 8.40548 0.784567 8.05401 1.13604C7.70254 1.48751 7.70254 2.05736 8.05401 2.40883L13.1452 7.5L8.05401 12.5912C7.70254 12.9426 7.70254 13.5125 8.05401 13.864C8.40548 14.2154 8.97533 14.2154 9.3268 13.864L15.0544 8.1364ZM0.417969 7.5V8.4H14.418V7.5V6.6H0.417969V7.5Z"
                                                    fill="currentcolor" />
                                            </svg>
                                        </i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Col 2: Quick links --}}
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-50 wow itfadeUp" data-wow-duration=".9s"
                            data-wow-delay=".5s">
                            <div class="it-footer-widget it-footer-col-1-2">
                                <h4 class="it-footer-widget-title">Quick Links</h4>
                                <div class="it-footer-widget-menu">
                                    <ul>
                                        <li><a href="{{ route('home') }}">Home</a></li>
                                        <li><a href="{{ route('about') }}">About Us</a></li>
                                        <li><a href="{{ route('academics') }}">Academics</a></li>
                                        <li><a href="{{ route('admissions') }}">Admissions</a></li>
                                        <li><a href="{{ route('gallery') }}">Gallery</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Col 3: Explore --}}
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-50 wow itfadeUp" data-wow-duration=".9s"
                            data-wow-delay=".7s">
                            <div class="it-footer-widget it-footer-col-1-3">
                                <h4 class="it-footer-widget-title">Our School</h4>
                                <div class="it-footer-widget-menu">
                                    <ul>
                                        <li><a href="{{ route('staff') }}">Our Staff</a></li>
                                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                                        <li><a href="{{ route('login') }}">Staff Portal</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Col 4: Contact info --}}
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 mb-50 wow itfadeUp" data-wow-duration=".9s"
                            data-wow-delay=".9s">
                            <div class="it-footer-widget it-footer-col-1-4 d-flex justify-content-lg-end">
                                <div>
                                    <h4 class="it-footer-widget-title">Get Contact</h4>
                                    <div class="it-footer-widget-contact">
                                        <ul>
                                            @if(\App\Models\SchoolSetting::get('school_phone'))
                                            <li>
                                                <span>Phone:</span>
                                                <a href="tel:{{ \App\Models\SchoolSetting::get('school_phone') }}">
                                                    {{ \App\Models\SchoolSetting::get('school_phone') }}
                                                </a>
                                            </li>
                                            @endif
                                            @if(\App\Models\SchoolSetting::get('school_email'))
                                            <li>
                                                <span>Email:</span>
                                                <a href="mailto:{{ \App\Models\SchoolSetting::get('school_email') }}">
                                                    {{ \App\Models\SchoolSetting::get('school_email') }}
                                                </a>
                                            </li>
                                            @endif
                                            @if(\App\Models\SchoolSetting::get('school_address'))
                                            <li>
                                                <span>Location:</span>
                                                <span>{{ \App\Models\SchoolSetting::get('school_address') }}</span>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Copyright bar --}}
            <div class="it-copyright-area it-copyright-ptb it-copyright-bg z-index-1 theme-bg">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-xl-6 col-lg-5">
                            <div class="it-copyright-left style-2 text-center text-lg-start">
                                <p class="mb-0" style="color: white;">
                                    Copyright &copy; {{ date('Y') }}
                                    <a href="{{ route('home') }}"
                                        style="color:var(--it-theme-2);">{{ $schoolName }}</a>.
                                    All Rights Reserved.
                                </p>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-7 text-center text-lg-end d-none d-lg-block">
                            <p class="mb-0" style="font-size:.83rem;color:rgba(255,255,255,.7);">
                                Powered by <strong style="color:var(--it-theme-2);">Dimconnect ICT</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </footer>


    {{-- ── JS Libraries ─────────────────────────────────────────────────── --}}
    <script src="{{ asset('template/js/jquery.js') }}"></script>
    <script src="{{ asset('template/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/js/purecounter.js') }}"></script>
    <script src="{{ asset('template/js/nice-select.js') }}"></script>
    <script src="{{ asset('template/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('template/js/isotope-pkgd.js') }}"></script>
    <script src="{{ asset('template/js/slick.min.js') }}"></script>
    <script src="{{ asset('template/js/wow.js') }}"></script>
    <script src="{{ asset('template/js/countdown.js') }}"></script>
    <script src="{{ asset('template/js/magnific-popup.js') }}"></script>
    <script src="{{ asset('template/js/imagesloaded-pkgd.js') }}"></script>
    <script src="{{ asset('template/js/parallax.js') }}"></script>
    <script src="{{ asset('template/js/slider.js') }}"></script>
    <script src="{{ asset('template/js/main.js') }}"></script>

    @stack('page-scripts')

</body>

</html>