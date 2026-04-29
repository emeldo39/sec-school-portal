@extends('layouts.public')

@section('title', \App\Models\SchoolSetting::get('school_name') . ' — Welcome')

@section('content')
@php
$school = \App\Models\SchoolSetting::get('school_name', 'Our School');
$motto = \App\Models\SchoolSetting::get('school_motto', '');
$about = \App\Models\SchoolSetting::get('about_text', '');
$principal = \App\Models\SchoolSetting::get('principal_name', 'The Principal');
@endphp

{{-- ── Hero Slider ────────────────────────────────────────────────────── --}}
@php
$arrowSvg = '<svg width="16" height="15" viewBox="0 0 16 15" fill="none">
    <path
        d="M15.0544 8.1364C15.4058 7.78492 15.4058 7.21508 15.0544 6.8636L9.3268 1.13604C8.97533 0.784567 8.40548 0.784567 8.05401 1.13604C7.70254 1.48751 7.70254 2.05736 8.05401 2.40883L13.1452 7.5L8.05401 12.5912C7.70254 12.9426 7.70254 13.5125 8.05401 13.864C8.40548 14.2154 8.97533 14.2154 9.3268 13.864L15.0544 8.1364ZM0.417969 7.5V8.4H14.418V7.5V6.6H0.417969V7.5Z"
        fill="currentcolor" />
</svg>';

// Default slides shown when none are configured in the admin
$defaultSlides = [
[
'title' => 'Building Brighter Futures Through',
'title_highlight' => 'Quality Education',
'description' => $about ? Str::limit($about, 140) : 'We are committed to nurturing the next generation of leaders
through quality education, discipline, and academic excellence.',
'button_text' => 'Apply Now',
'button_url' => route('admissions'),
'image' => null,
'default_image' => asset('template/img/slider/slider-1-2.jpg'),
],
[
'title' => "Shaping Tomorrow's",
'title_highlight' => 'Leaders Today',
'description' => 'Our dedicated faculty, modern facilities, and structured academic programmes ensure every student
reaches their full potential — academically, morally, and socially.',
'button_text' => 'Explore Academics',
'button_url' => route('academics'),
'image' => null,
'default_image' => asset('template/img/slider/slider-1-3.jpg'),
],
];

$activeSlides = $slides->count()
? $slides->map(fn($s) => [
'title' => $s->title,
'title_highlight' => $s->title_highlight,
'description' => $s->description,
'button_text' => $s->button_text,
'button_url' => $s->button_url,
'image' => $s->image ? asset('storage/' . $s->image) : null,
'default_image' => asset('template/img/slider/slider-1-2.jpg'),
])->all()
: $defaultSlides;
@endphp

<section class="it-slider-area">
    <div class="it-slider-wrap p-relative">
        <div class="swiper it-slider-active p-relative">
            <div class="swiper-wrapper">

                @foreach($activeSlides as $slide)
                <div class="swiper-slide">
                    <div class="it-slider-box it-slider-overlay z-index-1">
                        <img class="it-slider-shape-1" src="{{ asset('template/img/shape/slider-1-1.png') }}" alt="">
                        <img class="it-slider-shape-2" src="{{ asset('template/img/shape/slider-1-2.png') }}" alt="">
                        <img class="it-slider-shape-3" src="{{ asset('template/img/shape/slider-1-3.png') }}" alt="">
                        <div class="it-slider-bg">
                            <img src="{{ $slide['image'] ?? $slide['default_image'] }}" alt="">
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-xxl-7 col-xl-8 col-lg-9">
                                    <div class="it-slider-content">
                                        <h1 class="it-slider-title">
                                            {{ $slide['title'] }}
                                            @if($slide['title_highlight'])
                                            <br><span class="yellow-clr">{{ $slide['title_highlight'] }}</span>
                                            @endif
                                        </h1>
                                        @if($slide['description'])
                                        <div class="it-slider-content-text">
                                            <p>{{ $slide['description'] }}</p>
                                        </div>
                                        @endif
                                        <div class="it-slider-btn">
                                            <a href="{{ $slide['button_url'] }}"
                                                class="it-btn-yellow border-radius-100">
                                                <span>
                                                    <span class="text-1">{{ $slide['button_text'] }}</span>
                                                    <span class="text-2">{{ $slide['button_text'] }}</span>
                                                </span>
                                                <i>{!! $arrowSvg !!}</i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            {{-- Slider navigation --}}
            <div class="it-slider-arrow-box d-none d-lg-block">
                <button class="it-slider-btn-prev"><i class="fal fa-angle-left"></i></button>
                <button class="it-slider-btn-next"><i class="fal fa-angle-right"></i></button>
            </div>
        </div>
    </div>
</section>

{{-- ── Stats / Feature Cards ────────────────────────────────────────────── --}}
<section class="it-feature-area">
    <div class="container">
        <div class="it-feature-wrap z-index-2 wow itfadeUp" data-wow-duration=".9s" data-wow-delay=".2s">
            <div class="row gx-0">

                <div class="col-xl-3 col-lg-3 col-md-6 col-6">
                    <div class="it-feature-item d-flex justify-content-center">
                        <div class="it-feature-icon">
                            <span style="background:var(--it-theme-1);border-color:rgba(42,37,103,.25);">
                                <i class="fal fa-user-graduate" style="color:#fff;font-size:1.5rem;"></i>
                            </span>
                        </div>
                        <div class="it-feature-content">
                            <h5 class="it-feature-title">
                                <i class="purecounter" data-purecounter-duration="1"
                                    data-purecounter-end="{{ $stats['students'] }}">0</i>+
                            </h5>
                            <p>Active Students</p>
                            <a href="{{ route('about') }}">Learn More
                                <svg width="15" height="14" viewBox="0 0 15 14" fill="none">
                                    <path
                                        d="M14.6364 7.6364C14.9879 7.28492 14.9879 6.71508 14.6364 6.3636L8.90883 0.636039C8.55736 0.284567 7.98751 0.284567 7.63604 0.636039C7.28457 0.987511 7.28457 1.55736 7.63604 1.90883L12.7272 7L7.63604 12.0912C7.28457 12.4426 7.28457 13.0125 7.63604 13.364C7.98751 13.7154 8.55736 13.7154 8.90883 13.364L14.6364 7.6364ZM0 7V7.9H14V7V6.1H0V7Z"
                                        fill="currentcolor" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6 col-6">
                    <div class="it-feature-item d-flex justify-content-center">
                        <div class="it-feature-icon">
                            <span style="background:var(--it-theme-1);border-color:rgba(42,37,103,.25);">
                                <i class="fal fa-chalkboard-teacher" style="color:#fff;font-size:1.5rem;"></i>
                            </span>
                        </div>
                        <div class="it-feature-content">
                            <h5 class="it-feature-title">
                                <i class="purecounter" data-purecounter-duration="1"
                                    data-purecounter-end="{{ $stats['teachers'] }}">0</i>+
                            </h5>
                            <p>Qualified Teachers</p>
                            <a href="{{ route('staff') }}">Learn More
                                <svg width="15" height="14" viewBox="0 0 15 14" fill="none">
                                    <path
                                        d="M14.6364 7.6364C14.9879 7.28492 14.9879 6.71508 14.6364 6.3636L8.90883 0.636039C8.55736 0.284567 7.98751 0.284567 7.63604 0.636039C7.28457 0.987511 7.28457 1.55736 7.63604 1.90883L12.7272 7L7.63604 12.0912C7.28457 12.4426 7.28457 13.0125 7.63604 13.364C7.98751 13.7154 8.55736 13.7154 8.90883 13.364L14.6364 7.6364ZM0 7V7.9H14V7V6.1H0V7Z"
                                        fill="currentcolor" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6 col-6">
                    <div class="it-feature-item d-flex justify-content-center">
                        <div class="it-feature-icon">
                            <span style="background:var(--it-theme-1);border-color:rgba(42,37,103,.25);">
                                <i class="fal fa-school" style="color:#fff;font-size:1.5rem;"></i>
                            </span>
                        </div>
                        <div class="it-feature-content">
                            <h5 class="it-feature-title">
                                <i class="purecounter" data-purecounter-duration="1"
                                    data-purecounter-end="{{ $stats['classes'] }}">0</i>
                            </h5>
                            <p>Active Classes</p>
                            <a href="{{ route('academics') }}">Learn More
                                <svg width="15" height="14" viewBox="0 0 15 14" fill="none">
                                    <path
                                        d="M14.6364 7.6364C14.9879 7.28492 14.9879 6.71508 14.6364 6.3636L8.90883 0.636039C8.55736 0.284567 7.98751 0.284567 7.63604 0.636039C7.28457 0.987511 7.28457 1.55736 7.63604 1.90883L12.7272 7L7.63604 12.0912C7.28457 12.4426 7.28457 13.0125 7.63604 13.364C7.98751 13.7154 8.55736 13.7154 8.90883 13.364L14.6364 7.6364ZM0 7V7.9H14V7V6.1H0V7Z"
                                        fill="currentcolor" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6 col-6">
                    <div class="it-feature-item d-flex justify-content-center">
                        <div class="it-feature-icon">
                            <span style="background:var(--it-theme-1);border-color:rgba(42,37,103,.25);">
                                <i class="fal fa-award" style="color:#fff;font-size:1.5rem;"></i>
                            </span>
                        </div>
                        <div class="it-feature-content">
                            <h5 class="it-feature-title">WAEC</h5>
                            <p>NECO &amp; JAMB Ready</p>
                            <a href="{{ route('academics') }}">Learn More
                                <svg width="15" height="14" viewBox="0 0 15 14" fill="none">
                                    <path
                                        d="M14.6364 7.6364C14.9879 7.28492 14.9879 6.71508 14.6364 6.3636L8.90883 0.636039C8.55736 0.284567 7.98751 0.284567 7.63604 0.636039C7.28457 0.987511 7.28457 1.55736 7.63604 1.90883L12.7272 7L7.63604 12.0912C7.28457 12.4426 7.28457 13.0125 7.63604 13.364C7.98751 13.7154 8.55736 13.7154 8.90883 13.364L14.6364 7.6364ZM0 7V7.9H14V7V6.1H0V7Z"
                                        fill="currentcolor" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- ── About Preview ───────────────────────────────────────────────────── --}}
<section class="it-about-area it-about-style-1 pt-120 pb-80">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 wow itfadeLeft" data-wow-duration=".9s" data-wow-delay=".3s">
                <div class="it-about-section-title-box mb-30">
                    <span class="it-section-subtitle yellow-style">Who We Are</span>
                    <h4 class="it-section-title">{{ $school }}</h4>
                </div>
                <div class="it-about-text mb-30">
                    <p>{{ $about ?: 'We are committed to nurturing the next generation of leaders through quality education, discipline, and academic excellence. Our school has grown to become a cornerstone of academic excellence in the community.' }}
                    </p>
                </div>
                @if($motto)
                <div class="d-flex align-items-center gap-3 p-4 mb-35"
                    style="background:var(--it-gray-1);border-radius:12px;border-left:4px solid var(--it-theme-1);">
                    <i class="fas fa-quote-left" style="font-size:2rem;color:var(--it-theme-2);flex-shrink:0;"></i>
                    <div>
                        <p class="mb-1 fw-medium fst-italic" style="color:var(--it-common-black);">"{{ $motto }}"</p>
                        <small style="color:var(--it-text-body);">— School Motto</small>
                    </div>
                </div>
                @endif
                <a href="{{ route('about') }}" class="it-btn-yellow border-radius-100">
                    <span><span class="text-1">Read Our Story</span><span class="text-2">Read Our Story</span></span>
                    <i><svg width="16" height="15" viewBox="0 0 16 15" fill="none">
                            <path
                                d="M15.0544 8.1364C15.4058 7.78492 15.4058 7.21508 15.0544 6.8636L9.3268 1.13604C8.97533 0.784567 8.40548 0.784567 8.05401 1.13604C7.70254 1.48751 7.70254 2.05736 8.05401 2.40883L13.1452 7.5L8.05401 12.5912C7.70254 12.9426 7.70254 13.5125 8.05401 13.864C8.40548 14.2154 8.97533 14.2154 9.3268 13.864L15.0544 8.1364ZM0.417969 7.5V8.4H14.418V7.5V6.6H0.417969V7.5Z"
                                fill="currentcolor" />
                        </svg></i>
                </a>
            </div>
            <div class="col-lg-6 wow itfadeRight" data-wow-duration=".9s" data-wow-delay=".5s">
                <div class="row g-3">
                    @foreach([
                    ['icon'=>'fa-book-open','title'=>'Quality Curriculum','desc'=>'WAEC, NECO &amp; JAMB-aligned
                    academic programmes for all levels.'],
                    ['icon'=>'fa-users','title'=>'Experienced Staff','desc'=>'Qualified and passionate educators
                    committed to student success.'],
                    ['icon'=>'fa-shield-check','title'=>'Safe Environment','desc'=>'A disciplined, safe and inclusive
                    school community.'],
                    ['icon'=>'fa-trophy','title'=>'Proven Results','desc'=>'Consistent strong performance in public
                    examinations.'],
                    ] as $feat)
                    <div class="col-6">
                        <div class="p-4 h-100"
                            style="background:var(--it-gray-1);border-radius:12px;transition:transform .2s;"
                            onmouseover="this.style.transform='translateY(-4px)'"
                            onmouseout="this.style.transform='translateY(0)'">
                            <div class="mb-15"
                                style="width:48px;height:48px;border-radius:10px;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;">
                                <i class="fal {{ $feat['icon'] }}" style="color:#fff;font-size:1.3rem;"></i>
                            </div>
                            <h6 style="font-weight:700;color:var(--it-common-black);margin-bottom:8px;">
                                {{ $feat['title'] }}</h6>
                            <p style="font-size:.85rem;color:var(--it-text-body);margin:0;">{!! $feat['desc'] !!}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Gallery Preview ─────────────────────────────────────────────────── --}}
@if($gallery->count())
<section class="pt-80 pb-80" style="background:var(--it-gray-1);">
    <div class="container">
        <div class="row justify-content-center mb-50">
            <div class="col-lg-6 text-center">
                <span class="it-section-subtitle yellow-style">School Life</span>
                <h4 class="it-section-title">Our Gallery</h4>
            </div>
        </div>
        <div class="row g-3">
            @foreach($gallery as $item)
            <div class="col-lg-4 col-md-6 col-6 wow itfadeUp" data-wow-duration=".9s"
                data-wow-delay="{{ ($loop->index * 0.1) }}s">
                <div class="overflow-hidden" style="border-radius:12px;">
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->caption ?? 'Gallery' }}"
                        style="width:100%;aspect-ratio:4/3;object-fit:cover;transition:transform .4s;"
                        onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-50">
            <a href="{{ route('gallery') }}" class="it-btn-yellow border-radius-100">
                <span><span class="text-1">View Full Gallery</span><span class="text-2">View Full Gallery</span></span>
                <i><svg width="16" height="15" viewBox="0 0 16 15" fill="none">
                        <path
                            d="M15.0544 8.1364C15.4058 7.78492 15.4058 7.21508 15.0544 6.8636L9.3268 1.13604C8.97533 0.784567 8.40548 0.784567 8.05401 1.13604C7.70254 1.48751 7.70254 2.05736 8.05401 2.40883L13.1452 7.5L8.05401 12.5912C7.70254 12.9426 7.70254 13.5125 8.05401 13.864C8.40548 14.2154 8.97533 14.2154 9.3268 13.864L15.0544 8.1364ZM0.417969 7.5V8.4H14.418V7.5V6.6H0.417969V7.5Z"
                            fill="currentcolor" />
                    </svg></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ── Latest News Posts ───────────────────────────────────────────────── --}}
@if($newsPosts->count())
<section class="it-blog-2-area z-index-1 pt-120 pb-80 fix">
    <img class="it-blog-shape-1" src="{{ asset('template/img/shape/blog-3-1.png') }}" alt="">
    <img class="it-blog-shape-2 d-none d-lg-block" data-parallax='{"x": 200, "smoothness": 30}'
        src="{{ asset('template/img/shape/blog-1-2.png') }}" alt="">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="it-blog-section-title-box text-center mb-65 wow itfadeUp" data-wow-duration=".9s"
                    data-wow-delay=".2s">
                    <span class="it-section-subtitle yellow-style">News Post</span>
                    <h4 class="it-section-title">Latest News &amp; Updates</h4>
                </div>
            </div>
        </div>
        <div class="row gx-35">
            @foreach($newsPosts as $i => $post)
            <div class="col-xl-4 col-lg-6 col-md-6 wow itfadeUp" data-wow-duration=".9s"
                data-wow-delay="{{ ($i * 0.2 + 0.3) }}s">
                <div class="it-blog-2-item mb-35">
                    <div class="it-blog-thumb p-relative">
                        @if($post->image)
                        <img class="w-100" src="{{ asset('storage/' . $post->image) }}"
                            style="height:230px;object-fit:cover;" alt="{{ $post->title }}">
                        @else
                        <div
                            style="height:230px;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;">
                            <i class="fal fa-newspaper" style="font-size:3rem;color:rgba(255,255,255,.25);"></i>
                        </div>
                        @endif
                    </div>
                    <div class="it-blog-2-content z-index-2">
                        <div class="it-blog-meta mb-25">
                            <span>
                                <i class="fal fa-calendar me-1"></i>
                                {{ $post->published_at?->format('M d, Y') ?? $post->created_at->format('M d, Y') }}
                            </span>
                            @if($post->author)
                            <span><i class="fal fa-user me-1"></i>{{ $post->author }}</span>
                            @endif
                        </div>
                        <h5 class="it-blog-title mb-15">
                            <a class="border-line" href="{{ route('news.show', $post) }}">{{ $post->title }}</a>
                        </h5>
                        <div class="it-blog-btn">
                            <a href="{{ route('news.show', $post) }}" class="it-btn-yellow">
                                <span>
                                    <span class="text-1">More Details</span>
                                    <span class="text-2">More Details</span>
                                </span>
                                <i><svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                        <path
                                            d="M14.6364 8.24235C14.9879 7.89088 14.9879 7.32103 14.6364 6.96956L8.90883 1.242C8.55736 0.890524 7.98751 0.890524 7.63604 1.242C7.28457 1.59347 7.28457 2.16332 7.63604 2.51479L12.7272 7.60596L7.63604 12.6971C7.28457 13.0486 7.28457 13.6184 7.63604 13.9699C7.98751 14.3214 8.55736 14.3214 8.90883 13.9699L14.6364 8.24235ZM0 7.60596V8.50596H14V7.60596V6.70596H0V7.60596Z"
                                            fill="currentcolor" />
                                    </svg></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-10 wow itfadeUp" data-wow-duration=".9s" data-wow-delay=".5s">
            <a href="{{ route('news.index') }}" class="it-btn-yellow border-radius-100">
                <span><span class="text-1">View All News</span><span class="text-2">View All News</span></span>
                <i><svg width="16" height="15" viewBox="0 0 16 15" fill="none">
                        <path
                            d="M15.0544 8.1364C15.4058 7.78492 15.4058 7.21508 15.0544 6.8636L9.3268 1.13604C8.97533 0.784567 8.40548 0.784567 8.05401 1.13604C7.70254 1.48751 7.70254 2.05736 8.05401 2.40883L13.1452 7.5L8.05401 12.5912C7.70254 12.9426 7.70254 13.5125 8.05401 13.864C8.40548 14.2154 8.97533 14.2154 9.3268 13.864L15.0544 8.1364ZM0.417969 7.5V8.4H14.418V7.5V6.6H0.417969V7.5Z"
                            fill="currentcolor" />
                    </svg></i>
            </a>
        </div>
    </div>
</section>
@endif


{{-- ── CTA ──────────────────────────────────────────────────────────────── --}}
<section class="pt-80 pb-80 theme-bg">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <span class="it-section-subtitle" style="color:var(--it-theme-2);">Join Our School Family</span>
                <h3 class="it-section-title" style="color:#fff;margin-bottom:12px;">Ready to Join {{ $school }}?</h3>
                <p style="color:rgba(255,255,255,.8);margin:0;">Applications are open. Take the first step towards a
                    quality education that prepares for life.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('admissions') }}" class="it-btn-yellow border-radius-100 me-3">
                    <span><span class="text-1">Apply Now</span><span class="text-2">Apply Now</span></span>
                    <i><svg width="16" height="15" viewBox="0 0 16 15" fill="none">
                            <path
                                d="M15.0544 8.1364C15.4058 7.78492 15.4058 7.21508 15.0544 6.8636L9.3268 1.13604C8.97533 0.784567 8.40548 0.784567 8.05401 1.13604C7.70254 1.48751 7.70254 2.05736 8.05401 2.40883L13.1452 7.5L8.05401 12.5912C7.70254 12.9426 7.70254 13.5125 8.05401 13.864C8.40548 14.2154 8.97533 14.2154 9.3268 13.864L15.0544 8.1364ZM0.417969 7.5V8.4H14.418V7.5V6.6H0.417969V7.5Z"
                                fill="currentcolor" />
                        </svg></i>
                </a>
            </div>
        </div>
    </div>
</section>




{{-- ── Popup Notice Modal ─────────────────────────────────────────── --}}
@php $popup = \App\Models\PopupNotice::active(); @endphp
@if($popup && ($popup->image || $popup->title))
<div id="popupNoticeModal" data-popup-id="{{ $popup->id }}" data-popup-ts="{{ $popup->updated_at?->timestamp }}"
    data-show-once="{{ $popup->show_once ? '1' : '0' }}"
    style="display:none;position:fixed;inset:0;z-index:99999;align-items:center;justify-content:center;padding:16px;">
    <div id="popupBackdrop" style="position:absolute;inset:0;background:rgba(0,0,0,.65);"></div>
    <div
        style="position:relative;z-index:1;background:#fff;border-radius:16px;overflow:hidden;width:100%;max-width:460px;box-shadow:0 20px 60px rgba(0,0,0,.35);animation:popupIn .35s ease;">
        {{-- Close --}}
        <button onclick="dismissPopup()" aria-label="Close"
            style="position:absolute;top:10px;right:10px;background:rgba(0,0,0,.55);color:#fff;border:none;border-radius:50%;width:32px;height:32px;font-size:16px;cursor:pointer;z-index:2;line-height:1;">✕</button>

        {{-- Title --}}
        @if($popup->title)
        <div style="padding:16px 20px 8px;text-align:center;border-bottom:1px solid #f0f0f0;">
            <strong style="font-size:1rem;color:#1a1a2e;">{{ $popup->title }}</strong>
        </div>
        @endif

        {{-- Image --}}
        @if($popup->image)
        <img src="{{ asset('storage/' . $popup->image) }}" alt="{{ $popup->title }}"
            style="width:100%;display:block;max-height:580px;object-fit:contain;">
        @endif

        {{-- Button --}}
        @if($popup->link_url)
        <div style="padding:14px 20px;text-align:center;background:#f8f9fa;">
            <a href="{{ $popup->link_url }}" target="_blank" rel="noopener"
                style="display:inline-block;background:var(--it-theme-1);color:#fff;padding:10px 32px;border-radius:6px;font-weight:600;font-size:.9rem;text-decoration:none;">
                {{ $popup->link_text ?: 'Learn More' }}
            </a>
        </div>
        @endif
    </div>
</div>
<style>
@keyframes popupIn {
    from {
        opacity: 0;
        transform: scale(.88) translateY(20px);
    }

    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
</style>
<script>
(function() {
    var modal = document.getElementById('popupNoticeModal');
    if (!modal) return;
    var storageKey = 'popup_dismissed_' + modal.dataset.popupId + '_' + modal.dataset.popupTs;
    var showOnce = modal.dataset.showOnce === '1';
    if (showOnce && sessionStorage.getItem(storageKey)) return;
    modal.style.display = 'flex';
    document.getElementById('popupBackdrop').addEventListener('click', dismissPopup);
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') dismissPopup();
    });

    function dismissPopup() {
        modal.style.display = 'none';
        if (showOnce) sessionStorage.setItem(storageKey, '1');
    }
    window.dismissPopup = dismissPopup;
})();
</script>
@endif


@endsection