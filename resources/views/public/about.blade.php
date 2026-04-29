@extends('layouts.public')

@section('title', 'About Us — ' . \App\Models\SchoolSetting::get('school_name'))

@section('content')
@php
    $school    = \App\Models\SchoolSetting::get('school_name', 'Our School');
    $motto     = \App\Models\SchoolSetting::get('school_motto', '');
    $about     = \App\Models\SchoolSetting::get('about_text', '');
    $principal = \App\Models\SchoolSetting::get('principal_name', 'The Principal');
@endphp

{{-- ── Breadcrumb ──────────────────────────────────────────────────────── --}}
<section class="it-breadcrumb-dark" data-background="{{ asset('template/img/breadcrumb/about-v4.jpg') }}">
    <div class="it-breadcrumb-dark-overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="text-center">
                    <h4 class="it-breadcrumb-title">About Us</h4>
                    <nav>
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">About Us</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Our Story ────────────────────────────────────────────────────────── --}}
<section class="it-about-area it-about-style-1 pt-120 pb-80">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 wow itfadeLeft" data-wow-duration=".9s" data-wow-delay=".3s">
                <div class="it-about-section-title-box mb-30">
                    <span class="it-section-subtitle yellow-style">Our Story</span>
                    <h4 class="it-section-title">Welcome to<br>{{ $school }}</h4>
                </div>
                <div class="it-about-text mb-30">
                    <p>{{ $about ?: 'We are committed to nurturing the next generation of leaders through quality education, discipline, and academic excellence. Our school has grown to become a cornerstone of excellence in the community.' }}</p>
                    <p class="mb-0">We provide a structured, safe, and supportive learning environment for students from Junior to Senior Secondary, developing young people ready for higher education and life.</p>
                </div>
                @if($motto)
                <div class="d-flex align-items-center gap-3 p-4 mb-35" style="background:var(--it-gray-1);border-radius:12px;border-left:4px solid var(--it-theme-1);">
                    <i class="fas fa-quote-left" style="font-size:2rem;color:var(--it-theme-2);flex-shrink:0;"></i>
                    <div>
                        <p class="mb-1 fw-medium fst-italic" style="color:var(--it-common-black);">"{{ $motto }}"</p>
                        <small style="color:var(--it-text-body);">— School Motto</small>
                    </div>
                </div>
                @endif
                <a href="{{ route('contact') }}" class="it-btn-yellow border-radius-100">
                    <span><span class="text-1">Get In Touch</span><span class="text-2">Get In Touch</span></span>
                    <i><svg width="16" height="15" viewBox="0 0 16 15" fill="none"><path d="M15.0544 8.1364C15.4058 7.78492 15.4058 7.21508 15.0544 6.8636L9.3268 1.13604C8.97533 0.784567 8.40548 0.784567 8.05401 1.13604C7.70254 1.48751 7.70254 2.05736 8.05401 2.40883L13.1452 7.5L8.05401 12.5912C7.70254 12.9426 7.70254 13.5125 8.05401 13.864C8.40548 14.2154 8.97533 14.2154 9.3268 13.864L15.0544 8.1364ZM0.417969 7.5V8.4H14.418V7.5V6.6H0.417969V7.5Z" fill="currentcolor"/></svg></i>
                </a>
            </div>
            <div class="col-lg-6 wow itfadeRight" data-wow-duration=".9s" data-wow-delay=".5s">
                <div class="row g-3">
                    @foreach([
                        ['icon'=>'fa-eye',         'title'=>'Our Vision',    'desc'=>'To be a leading secondary school that produces well-rounded, academically excellent, and morally upright citizens.'],
                        ['icon'=>'fa-bullseye',     'title'=>'Our Mission',   'desc'=>'To provide quality education in a safe, disciplined environment that inspires lifelong learning and responsible citizenship.'],
                        ['icon'=>'fa-heart',        'title'=>'Core Values',   'desc'=>'Integrity, Excellence, Discipline, Respect and Innovation are the pillars that define our school community.'],
                        ['icon'=>'fa-users',        'title'=>'Our Community', 'desc'=>'A vibrant community of students, teachers, parents and alumni united by a common purpose of excellence.'],
                    ] as $feat)
                    <div class="col-6">
                        <div class="p-4 h-100" style="background:var(--it-gray-1);border-radius:12px;transition:transform .2s;"
                             onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                            <div class="mb-15" style="width:48px;height:48px;border-radius:10px;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;">
                                <i class="fal {{ $feat['icon'] }}" style="color:#fff;font-size:1.3rem;"></i>
                            </div>
                            <h6 style="font-weight:700;color:var(--it-common-black);margin-bottom:8px;">{{ $feat['title'] }}</h6>
                            <p style="font-size:.85rem;color:var(--it-text-body);margin:0;">{{ $feat['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Why Choose Us ────────────────────────────────────────────────────── --}}
<section class="pt-80 pb-80" style="background:var(--it-gray-1);">
    <div class="container">
        <div class="row justify-content-center mb-50">
            <div class="col-lg-7 text-center">
                <span class="it-section-subtitle yellow-style">Why Choose Us</span>
                <h4 class="it-section-title">What Makes Us Different</h4>
            </div>
        </div>
        <div class="row g-4">
            @foreach([
                ['icon'=>'fa-book',          'title'=>'Strong Academic Programme', 'desc'=>'Comprehensive curriculum aligned with WAEC, NECO and JAMB requirements across all subject areas.'],
                ['icon'=>'fa-user-check',    'title'=>'Qualified Teachers',        'desc'=>'Experienced, certified educators who are passionate about student success and academic growth.'],
                ['icon'=>'fa-desktop',       'title'=>'Modern Facilities',         'desc'=>'Well-equipped classrooms, science laboratories, library and other learning resources.'],
                ['icon'=>'fa-shield-check',  'title'=>'Safe & Disciplined',        'desc'=>'A structured, secure environment that promotes focus, respect and responsible behaviour.'],
                ['icon'=>'fa-trophy',        'title'=>'Excellent Track Record',    'desc'=>'Consistent strong performance in public examinations and extracurricular competitions.'],
                ['icon'=>'fa-heartbeat',     'title'=>'Holistic Development',      'desc'=>'Sports, clubs, events and co-curricular activities to develop well-rounded students.'],
            ] as $item)
            <div class="col-lg-4 col-md-6 wow itfadeUp" data-wow-duration=".9s" data-wow-delay="{{ ($loop->index * 0.1) }}s">
                <div class="p-4 h-100" style="background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.06);transition:transform .2s;"
                     onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div class="mb-20" style="width:52px;height:52px;border-radius:12px;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;">
                        <i class="fal {{ $item['icon'] }}" style="color:#fff;font-size:1.4rem;"></i>
                    </div>
                    <h6 style="font-weight:700;color:var(--it-common-black);margin-bottom:8px;">{{ $item['title'] }}</h6>
                    <p style="font-size:.88rem;color:var(--it-text-body);margin:0;">{{ $item['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Principal's Message ──────────────────────────────────────────────── --}}
<section class="pt-80 pb-80">
    <div class="container">
        <div class="row justify-content-center mb-50">
            <div class="col-lg-7 text-center">
                <span class="it-section-subtitle yellow-style">Leadership</span>
                <h4 class="it-section-title">Principal's Message</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8 wow itfadeUp" data-wow-duration=".9s" data-wow-delay=".3s">
                <div class="p-4 p-lg-5" style="background:var(--it-gray-1);border-radius:16px;border-left:5px solid var(--it-theme-2);">
                    <i class="fas fa-quote-left mb-20 d-block" style="font-size:2.5rem;color:var(--it-theme-2);"></i>
                    <p class="mb-20" style="font-size:1.05rem;line-height:1.8;color:var(--it-common-black);">
                        Welcome to {{ $school }}. We are proud of our tradition of academic excellence and our commitment to developing the whole child — intellectually, morally, and socially.
                    </p>
                    <p class="mb-20" style="font-size:1.05rem;line-height:1.8;color:var(--it-common-black);">
                        Our dedicated teachers, structured curriculum, and supportive community create an environment where every student can thrive. We prepare our students not just to pass examinations, but to succeed in life.
                    </p>
                    <p class="mb-30" style="font-size:1.05rem;line-height:1.8;color:var(--it-common-black);">
                        We invite you to be part of our school family. Together, we will unlock the potential of every child entrusted to our care.
                    </p>
                    <div class="d-flex align-items-center gap-15">
                        <div style="width:48px;height:48px;border-radius:50%;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span style="color:#fff;font-size:1.3rem;font-weight:700;">{{ strtoupper(substr($principal, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h6 style="font-weight:700;color:var(--it-common-black);margin:0;">{{ $principal }}</h6>
                            <small style="color:var(--it-text-body);">Principal, {{ $school }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Stats ────────────────────────────────────────────────────────────── --}}
<section class="it-funfact-area theme-bg">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="it-funfact-item style-1 d-flex align-items-center">
                    <div class="it-funfact-icon">
                        <span><i class="fal fa-user-graduate" style="font-size:2.2rem;color:#fff;"></i></span>
                    </div>
                    <div class="it-funfact-content">
                        <h6 class="it-funfact-number">
                            <i class="purecounter" data-purecounter-duration="1" data-purecounter-end="{{ $stats['students'] }}">0</i>+
                        </h6>
                        <span>Active Students</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="it-funfact-item style-2 d-flex align-items-center">
                    <div class="it-funfact-icon">
                        <span><i class="fal fa-chalkboard-teacher" style="font-size:2.2rem;color:#fff;"></i></span>
                    </div>
                    <div class="it-funfact-content">
                        <h6 class="it-funfact-number">
                            <i class="purecounter" data-purecounter-duration="1" data-purecounter-end="{{ $stats['teachers'] }}">0</i>+
                        </h6>
                        <span>Qualified Teachers</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="it-funfact-item style-3 d-flex align-items-center">
                    <div class="it-funfact-icon">
                        <span><i class="fal fa-school" style="font-size:2.2rem;color:#fff;"></i></span>
                    </div>
                    <div class="it-funfact-content">
                        <h6 class="it-funfact-number">
                            <i class="purecounter" data-purecounter-duration="1" data-purecounter-end="{{ $stats['classes'] }}">0</i>
                        </h6>
                        <span>Active Classes</span>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="it-funfact-item style-4 d-flex align-items-center">
                    <div class="it-funfact-icon">
                        <span><i class="fal fa-award" style="font-size:2.2rem;color:#fff;"></i></span>
                    </div>
                    <div class="it-funfact-content">
                        <h6 class="it-funfact-number" style="font-size:1.4rem;">WAEC</h6>
                        <span>NECO &amp; JAMB Ready</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
