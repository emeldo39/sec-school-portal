@extends('layouts.public')

@section('title', 'Academics — ' . \App\Models\SchoolSetting::get('school_name'))

@section('content')
@php $school = \App\Models\SchoolSetting::get('school_name', 'Our School'); @endphp

{{-- ── Breadcrumb ──────────────────────────────────────────────────────── --}}
<section class="it-breadcrumb-dark" data-background="{{ asset('template/img/breadcrumb/course-v1-breadcrumb.jpg') }}">
    <div class="it-breadcrumb-dark-overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="text-center">
                    <h4 class="it-breadcrumb-title">Academics</h4>
                    <nav>
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Academics</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Intro ─────────────────────────────────────────────────────────────── --}}
<section class="pt-120 pb-80">
    <div class="container">
        <div class="row justify-content-center mb-50">
            <div class="col-lg-7 text-center">
                <span class="it-section-subtitle yellow-style">Our Programmes</span>
                <h4 class="it-section-title">Academic Excellence</h4>
                <p style="color:var(--it-text-body);">{{ $school }} offers a broad and balanced curriculum that prepares students for public examinations and beyond.</p>
            </div>
        </div>

        {{-- Exam Prep Cards --}}
        <div class="row g-4 mb-80">
            @foreach([
                ['name'=>'WAEC', 'full'=>'West African Senior School Certificate', 'icon'=>'fa-award',           'desc'=>'We prepare SS students thoroughly for the WASSCE, with focused revision, past question practice and mock examinations.'],
                ['name'=>'NECO', 'full'=>'National Examinations Council',           'icon'=>'fa-medal',           'desc'=>'Our students sit for NECO examinations with strong preparation, consistent with the national curriculum requirements.'],
                ['name'=>'JAMB', 'full'=>'Joint Admissions & Matriculation Board',  'icon'=>'fa-graduation-cap', 'desc'=>'We guide our SS3 students in UTME preparation to maximize their university admission prospects.'],
            ] as $exam)
            <div class="col-md-4 wow itfadeUp" data-wow-duration=".9s" data-wow-delay="{{ ($loop->index * 0.1) }}s">
                <div class="text-center p-4 p-lg-5 h-100" style="background:var(--it-gray-1);border-radius:16px;transition:transform .2s;border-bottom:4px solid var(--it-theme-2);"
                     onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div class="mb-20 mx-auto" style="width:64px;height:64px;border-radius:50%;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;">
                        <i class="fal {{ $exam['icon'] }}" style="color:#fff;font-size:1.6rem;"></i>
                    </div>
                    <h4 class="mb-5" style="font-weight:800;color:var(--it-theme-1);">{{ $exam['name'] }}</h4>
                    <small class="d-block mb-15" style="color:var(--it-text-body);font-size:.82rem;">{{ $exam['full'] }}</small>
                    <p style="font-size:.9rem;color:var(--it-text-body);margin:0;">{{ $exam['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        {{-- JSS Subjects --}}
        <div class="mb-60">
            <div class="d-flex align-items-center gap-3 mb-30">
                <div class="d-flex align-items-center justify-content-center text-white fw-bold"
                     style="width:48px;height:48px;border-radius:10px;background:var(--it-theme-1);font-size:1rem;flex-shrink:0;">JS</div>
                <div>
                    <h4 class="mb-0" style="font-weight:700;color:var(--it-common-black);">Junior Secondary School <span style="color:var(--it-theme-3);">(JSS1 – JSS3)</span></h4>
                    <small style="color:var(--it-text-body);">Foundation level — ages 10–15</small>
                </div>
            </div>
            @if($jssSubjects->count())
            <div class="row g-3">
                @foreach($jssSubjects as $sub)
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="d-flex align-items-center gap-2 p-3" style="background:#fff;border:1px solid #eee;border-radius:10px;transition:border-color .2s;"
                         onmouseover="this.style.borderColor='var(--it-theme-2)'" onmouseout="this.style.borderColor='#eee'">
                        <i class="fal fa-book-open" style="color:var(--it-theme-1);flex-shrink:0;"></i>
                        <span style="font-size:.9rem;font-weight:500;color:var(--it-common-black);">{{ $sub->name }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p style="color:var(--it-text-body);">Subjects will be listed once configured by the admin.</p>
            @endif
        </div>

        {{-- SS Subjects --}}
        <div>
            <div class="d-flex align-items-center gap-3 mb-30">
                <div class="d-flex align-items-center justify-content-center text-white fw-bold"
                     style="width:48px;height:48px;border-radius:10px;background:var(--it-theme-3);font-size:1rem;flex-shrink:0;">SS</div>
                <div>
                    <h4 class="mb-0" style="font-weight:700;color:var(--it-common-black);">Senior Secondary School <span style="color:var(--it-theme-3);">(SS1 – SS3)</span></h4>
                    <small style="color:var(--it-text-body);">Advanced level — ages 15–18</small>
                </div>
            </div>
            @if($ssSubjects->count())
            <div class="row g-3">
                @foreach($ssSubjects as $sub)
                <div class="col-lg-3 col-md-4 col-6">
                    <div class="d-flex align-items-center gap-2 p-3" style="background:#fff;border:1px solid #eee;border-radius:10px;transition:border-color .2s;"
                         onmouseover="this.style.borderColor='var(--it-theme-2)'" onmouseout="this.style.borderColor='#eee'">
                        <i class="fal fa-book-open" style="color:var(--it-theme-3);flex-shrink:0;"></i>
                        <span style="font-size:.9rem;font-weight:500;color:var(--it-common-black);">{{ $sub->name }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p style="color:var(--it-text-body);">Subjects will be listed once configured by the admin.</p>
            @endif
        </div>

    </div>
</section>

{{-- ── CTA ──────────────────────────────────────────────────────────────── --}}
<section class="pt-80 pb-80 theme-bg">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mb-4 mb-lg-0">
                <span class="it-section-subtitle" style="color:var(--it-theme-2);">Ready to Enrol?</span>
                <h3 class="it-section-title" style="color:#fff;margin-bottom:12px;">Start Your Academic Journey Today</h3>
                <p style="color:rgba(255,255,255,.8);margin:0;">Applications are open. Join a school committed to academic excellence and holistic development.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('admissions') }}" class="it-btn-yellow border-radius-100">
                    <span><span class="text-1">Apply Now</span><span class="text-2">Apply Now</span></span>
                    <i><svg width="16" height="15" viewBox="0 0 16 15" fill="none"><path d="M15.0544 8.1364C15.4058 7.78492 15.4058 7.21508 15.0544 6.8636L9.3268 1.13604C8.97533 0.784567 8.40548 0.784567 8.05401 1.13604C7.70254 1.48751 7.70254 2.05736 8.05401 2.40883L13.1452 7.5L8.05401 12.5912C7.70254 12.9426 7.70254 13.5125 8.05401 13.864C8.40548 14.2154 8.97533 14.2154 9.3268 13.864L15.0544 8.1364ZM0.417969 7.5V8.4H14.418V7.5V6.6H0.417969V7.5Z" fill="currentcolor"/></svg></i>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
