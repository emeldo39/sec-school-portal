@extends('layouts.public')

@section('title', $publication->student->full_name . ' — Result Sheet')

@section('content')
@php
    $student = $publication->student;
    $term    = $publication->term;
    $class   = $student->schoolClass;
    $school  = \App\Models\SchoolSetting::get('school_name', 'Our School');
    $logo    = \App\Models\SchoolSetting::get('school_logo');
@endphp

{{-- ── Breadcrumb ──────────────────────────────────────────────────────── --}}
<section class="it-breadcrumb-dark" data-background="{{ asset('template/img/breadcrumb/course-v9-breadcrumb.jpg') }}">
    <div class="it-breadcrumb-dark-overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="text-center">
                    <h4 class="it-breadcrumb-title">Result Sheet</h4>
                    <nav>
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Result</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Result Card ──────────────────────────────────────────────────────── --}}
<section class="pt-80 pb-80" style="background:var(--it-gray-1);">
    <div class="container" style="max-width:680px;">

        {{-- School header --}}
        <div class="text-center mb-40">
            @if($logo)
            <img src="{{ asset('storage/' . $logo) }}" alt="{{ $school }}"
                 style="height:64px;object-fit:contain;margin-bottom:12px;">
            @endif
            <h4 style="font-weight:800;color:var(--it-theme-1);margin-bottom:4px;">{{ $school }}</h4>
            <p style="color:var(--it-text-body);font-size:.9rem;margin:0;">Student Result Portal</p>
        </div>

        {{-- Result card --}}
        <div style="background:#fff;border-radius:16px;box-shadow:0 8px 30px rgba(42,37,103,.12);overflow:hidden;" class="wow itfadeUp" data-wow-duration=".9s" data-wow-delay=".2s">

            {{-- Gradient header --}}
            <div class="p-4 p-lg-5" style="background:linear-gradient(135deg,var(--it-theme-1) 0%,var(--it-theme-3) 100%);">
                <div class="d-flex align-items-center gap-3">
                    @if($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}"
                         style="width:64px;height:64px;border-radius:50%;object-fit:cover;border:3px solid var(--it-theme-2);" alt="">
                    @else
                    <div style="width:64px;height:64px;border-radius:50%;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;border:3px solid var(--it-theme-2);flex-shrink:0;">
                        <i class="fal fa-user" style="color:#fff;font-size:1.6rem;"></i>
                    </div>
                    @endif
                    <div>
                        <h5 style="font-weight:700;color:#fff;margin-bottom:4px;">{{ $student->full_name }}</h5>
                        <p style="color:rgba(255,255,255,.85);font-size:.85rem;margin:0;">
                            {{ $class->name }} &bull; {{ $student->admission_number }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Details --}}
            <div class="p-4 p-lg-5">
                <div class="row gy-4 mb-30">
                    <div class="col-6">
                        <p style="font-size:.75rem;font-weight:600;color:var(--it-text-body);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Academic Term</p>
                        <p style="font-weight:600;color:var(--it-theme-1);margin:0;">{{ $term->name }} {{ $term->academic_year }}</p>
                    </div>
                    <div class="col-6">
                        <p style="font-size:.75rem;font-weight:600;color:var(--it-text-body);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Class</p>
                        <p style="font-weight:600;color:var(--it-theme-1);margin:0;">{{ $class->name }}</p>
                    </div>
                    @if($publication->next_term_begins)
                    <div class="col-6">
                        <p style="font-size:.75rem;font-weight:600;color:var(--it-text-body);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Next Term Begins</p>
                        <p style="font-weight:600;color:#16a34a;margin:0;">
                            <i class="fal fa-calendar me-1"></i>{{ $publication->next_term_begins->format('d M Y') }}
                        </p>
                    </div>
                    @endif
                    <div class="col-6">
                        <p style="font-size:.75rem;font-weight:600;color:var(--it-text-body);text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Declared On</p>
                        <p style="font-weight:600;color:var(--it-theme-1);margin:0;">{{ $publication->published_at->format('d M Y') }}</p>
                    </div>
                </div>

                @if($publication->form_master_remarks || $publication->house_master_remarks || $publication->principal_remarks)
                <hr style="border-color:#EEF0FF;margin:24px 0;">
                @if($publication->form_master_remarks)
                <div class="mb-20">
                    <p style="font-size:.75rem;font-weight:600;color:var(--it-text-body);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Form Master/Mistress Remarks</p>
                    <p style="font-size:.9rem;color:#444;margin:0;">{{ $publication->form_master_remarks }}</p>
                </div>
                @endif
                @if($publication->house_master_remarks)
                <div class="mb-20">
                    <p style="font-size:.75rem;font-weight:600;color:var(--it-text-body);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">House Master/Mistress Remarks</p>
                    <p style="font-size:.9rem;color:#444;margin:0;">{{ $publication->house_master_remarks }}</p>
                </div>
                @endif
                @if($publication->principal_remarks)
                <div>
                    <p style="font-size:.75rem;font-weight:600;color:var(--it-text-body);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;">Principal's Remarks</p>
                    <p style="font-size:.9rem;color:#444;margin:0;">{{ $publication->principal_remarks }}</p>
                </div>
                @endif
                @endif

                {{-- Download button --}}
                <div class="text-center mt-35">
                    <a href="{{ route('result.public.pdf', $publication->token) }}" class="it-btn-yellow border-radius-100">
                        <span><span class="text-1">Download Full Result PDF</span><span class="text-2">Download Full Result PDF</span></span>
                        <i><svg width="16" height="15" viewBox="0 0 16 15" fill="none"><path d="M15.0544 8.1364C15.4058 7.78492 15.4058 7.21508 15.0544 6.8636L9.3268 1.13604C8.97533 0.784567 8.40548 0.784567 8.05401 1.13604C7.70254 1.48751 7.70254 2.05736 8.05401 2.40883L13.1452 7.5L8.05401 12.5912C7.70254 12.9426 7.70254 13.5125 8.05401 13.864C8.40548 14.2154 8.97533 14.2154 9.3268 13.864L15.0544 8.1364ZM0.417969 7.5V8.4H14.418V7.5V6.6H0.417969V7.5Z" fill="currentcolor"/></svg></i>
                    </a>
                    <p style="font-size:.8rem;color:var(--it-text-body);margin-top:10px;">Opens a PDF with the complete result sheet including all scores.</p>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
