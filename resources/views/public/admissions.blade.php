@extends('layouts.public')

@section('title', 'Admissions — ' . \App\Models\SchoolSetting::get('school_name'))

@section('content')
@php $school = \App\Models\SchoolSetting::get('school_name', 'Our School'); @endphp

{{-- ── Breadcrumb ──────────────────────────────────────────────────────── --}}
<section class="it-breadcrumb-dark" data-background="{{ asset('template/img/breadcrumb/course-v4-breadcrumb.jpg') }}">
    <div class="it-breadcrumb-dark-overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="text-center">
                    <h4 class="it-breadcrumb-title">Admissions</h4>
                    <nav>
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Admissions</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Intro ────────────────────────────────────────────────────────────── --}}
<section class="pt-120 pb-60">
    <div class="container">
        <div class="row justify-content-center mb-50">
            <div class="col-lg-7 text-center">
                <span class="it-section-subtitle yellow-style">Join Our School</span>
                <h4 class="it-section-title">Admission Information</h4>
                <p style="color:var(--it-text-body);">We welcome applications from qualified students who are ready to commit to academic excellence and uphold the values of {{ $school }}.</p>
            </div>
        </div>

        {{-- How to Apply Steps --}}
        <div class="row justify-content-center mb-60">
            <div class="col-lg-10">
                <div class="mb-30">
                    <span class="it-section-subtitle yellow-style">Step by Step</span>
                    <h4 class="it-section-title">How to Apply</h4>
                </div>
                <div class="row g-4">
                    @foreach([
                        ['step'=>'01', 'icon'=>'fa-map-marker-alt', 'title'=>'Visit the School Office',    'desc'=>'Come to the school office during working hours to obtain or enquire about the admission form.'],
                        ['step'=>'02', 'icon'=>'fa-file-alt',       'title'=>'Complete the Application',   'desc'=>'Fill out the admission form with accurate personal and academic details. Attach all required documents.'],
                        ['step'=>'03', 'icon'=>'fa-paper-plane',    'title'=>'Submit & Assessment',        'desc'=>'Submit the completed form. Eligible candidates may be invited for an entrance assessment or interview.'],
                        ['step'=>'04', 'icon'=>'fa-check-circle',   'title'=>'Receive Offer & Enrol',      'desc'=>'Successful applicants will receive an admission offer. Pay the required fees and complete enrolment.'],
                    ] as $s)
                    <div class="col-md-6 wow itfadeUp" data-wow-duration=".9s" data-wow-delay="{{ ($loop->index * 0.1) }}s">
                        <div class="d-flex gap-3 p-4 h-100" style="background:var(--it-gray-1);border-radius:14px;border-bottom:3px solid var(--it-theme-2);">
                            <div style="width:52px;height:52px;border-radius:50%;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="fal {{ $s['icon'] }}" style="color:#fff;font-size:1.2rem;"></i>
                            </div>
                            <div>
                                <small style="color:var(--it-theme-2);font-weight:700;font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;">Step {{ $s['step'] }}</small>
                                <h6 style="font-weight:700;color:var(--it-common-black);margin:4px 0 8px;">{{ $s['title'] }}</h6>
                                <p style="font-size:.88rem;color:var(--it-text-body);margin:0;">{{ $s['desc'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Requirements / Documents / Contact --}}
        <div class="row g-4">
            {{-- Requirements --}}
            <div class="col-lg-4 wow itfadeLeft" data-wow-duration=".9s" data-wow-delay=".3s">
                <div class="p-4 h-100" style="background:var(--it-gray-1);border-radius:14px;">
                    <div class="d-flex align-items-center gap-3 mb-25">
                        <div style="width:48px;height:48px;border-radius:10px;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fal fa-tasks" style="color:#fff;font-size:1.2rem;"></i>
                        </div>
                        <h5 style="font-weight:700;color:var(--it-common-black);margin:0;">Requirements</h5>
                    </div>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0" style="font-size:.9rem;">
                        @foreach([
                            'Minimum age of 10 years for Junior Secondary 1 entry',
                            'Primary school leaving certificate (Junior entry)',
                            'Junior Secondary 3 result / BECE for Senior Secondary 1 entry',
                            'Transfer letter from previous school (if applicable)',
                            'Birth certificate or sworn affidavit',
                            'Passport photographs (4 copies)',
                        ] as $req)
                        <li class="d-flex align-items-start gap-2" style="color:var(--it-text-body);">
                            <i class="fal fa-check" style="color:var(--it-theme-2);margin-top:3px;flex-shrink:0;font-weight:700;"></i>
                            {{ $req }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Documents Needed --}}
            <div class="col-lg-4 wow itfadeUp" data-wow-duration=".9s" data-wow-delay=".4s">
                <div class="p-4 h-100" style="background:var(--it-gray-1);border-radius:14px;">
                    <div class="d-flex align-items-center gap-3 mb-25">
                        <div style="width:48px;height:48px;border-radius:10px;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fal fa-copy" style="color:#fff;font-size:1.2rem;"></i>
                        </div>
                        <h5 style="font-weight:700;color:var(--it-common-black);margin:0;">Documents Needed</h5>
                    </div>
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0" style="font-size:.9rem;">
                        @foreach([
                            'Completed admission form',
                            'Birth certificate (original + photocopy)',
                            'Previous school results (last 2 terms)',
                            'Primary school leaving certificate',
                            'Transfer certificate (if applicable)',
                            'Immunisation card',
                            '4 recent passport photographs',
                            'Evidence of payment of admission fee',
                        ] as $doc)
                        <li class="d-flex align-items-start gap-2" style="color:var(--it-text-body);">
                            <i class="fal fa-file" style="color:var(--it-theme-2);margin-top:3px;flex-shrink:0;"></i>
                            {{ $doc }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Contact for Admissions --}}
            <div class="col-lg-4 wow itfadeRight" data-wow-duration=".9s" data-wow-delay=".5s">
                <div class="p-4 h-100" style="background:var(--it-theme-1);border-radius:14px;">
                    <i class="fal fa-info-circle mb-15 d-block" style="font-size:2rem;color:var(--it-theme-2);"></i>
                    <h5 style="font-weight:700;color:#fff;margin-bottom:10px;">Need More Info?</h5>
                    <p style="color:rgba(255,255,255,.8);font-size:.9rem;margin-bottom:25px;">Contact the school office for detailed information on fees structure, resumption dates, and available spaces.</p>
                    <div class="d-flex flex-column gap-15">
                        @if(\App\Models\SchoolSetting::get('school_phone'))
                        <div class="d-flex align-items-center gap-2" style="color:rgba(255,255,255,.9);font-size:.9rem;">
                            <i class="fal fa-phone" style="color:var(--it-theme-2);flex-shrink:0;"></i>
                            <span>{{ \App\Models\SchoolSetting::get('school_phone') }}</span>
                        </div>
                        @endif
                        @if(\App\Models\SchoolSetting::get('school_email'))
                        <div class="d-flex align-items-center gap-2" style="color:rgba(255,255,255,.9);font-size:.9rem;">
                            <i class="fal fa-envelope" style="color:var(--it-theme-2);flex-shrink:0;"></i>
                            <span>{{ \App\Models\SchoolSetting::get('school_email') }}</span>
                        </div>
                        @endif
                        @if(\App\Models\SchoolSetting::get('school_address'))
                        <div class="d-flex align-items-start gap-2" style="color:rgba(255,255,255,.9);font-size:.9rem;">
                            <i class="fal fa-map-marker-alt" style="color:var(--it-theme-2);flex-shrink:0;margin-top:2px;"></i>
                            <span>{{ \App\Models\SchoolSetting::get('school_address') }}</span>
                        </div>
                        @endif
                    </div>
                    <a href="{{ route('contact') }}" class="it-btn-yellow border-radius-100 d-inline-flex mt-30" style="width:100%;justify-content:center;">
                        <span><span class="text-1">Send an Enquiry</span><span class="text-2">Send an Enquiry</span></span>
                        <i><svg width="16" height="15" viewBox="0 0 16 15" fill="none"><path d="M15.0544 8.1364C15.4058 7.78492 15.4058 7.21508 15.0544 6.8636L9.3268 1.13604C8.97533 0.784567 8.40548 0.784567 8.05401 1.13604C7.70254 1.48751 7.70254 2.05736 8.05401 2.40883L13.1452 7.5L8.05401 12.5912C7.70254 12.9426 7.70254 13.5125 8.05401 13.864C8.40548 14.2154 8.97533 14.2154 9.3268 13.864L15.0544 8.1364ZM0.417969 7.5V8.4H14.418V7.5V6.6H0.417969V7.5Z" fill="currentcolor"/></svg></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
