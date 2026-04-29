@extends('layouts.public')

@section('title', 'Our Staff — ' . \App\Models\SchoolSetting::get('school_name'))

@section('content')

{{-- ── Breadcrumb ──────────────────────────────────────────────────────── --}}
<section class="it-breadcrumb-dark" data-background="{{ asset('template/img/breadcrumb/team.jpg') }}">
    <div class="it-breadcrumb-dark-overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="text-center">
                    <h4 class="it-breadcrumb-title">Our Staff</h4>
                    <nav>
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Staff</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Staff Grid ───────────────────────────────────────────────────────── --}}
<section class="pt-120 pb-80">
    <div class="container">
        <div class="row justify-content-center mb-50">
            <div class="col-lg-7 text-center">
                <span class="it-section-subtitle yellow-style">Meet the Team</span>
                <h4 class="it-section-title">Our Teaching Staff</h4>
                <p style="color:var(--it-text-body);">Dedicated, qualified educators committed to student excellence.</p>
            </div>
        </div>

        @if($staff->count())
        <div class="row g-4">
            @foreach($staff as $member)
            <div class="col-xl-3 col-lg-4 col-md-6 wow itfadeUp" data-wow-duration=".9s" data-wow-delay="{{ ($loop->index % 4 * 0.1) }}s">
                <div class="text-center p-4" style="background:var(--it-gray-1);border-radius:16px;transition:transform .25s;position:relative;overflow:hidden;"
                     onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform='translateY(0)'">
                    {{-- Gold top bar --}}
                    <div style="position:absolute;top:0;left:0;right:0;height:4px;background:var(--it-theme-2);"></div>

                    {{-- Avatar --}}
                    @if($member->photo)
                        <img src="{{ asset('storage/' . $member->photo) }}"
                             alt="{{ $member->name }}"
                             style="width:90px;height:90px;border-radius:50%;object-fit:cover;margin-bottom:16px;border:3px solid var(--it-theme-2);">
                    @else
                        <div style="width:90px;height:90px;border-radius:50%;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;border:3px solid var(--it-theme-2);">
                            <span style="color:#fff;font-size:2rem;font-weight:700;">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                        </div>
                    @endif

                    <h6 style="font-weight:700;color:var(--it-common-black);margin-bottom:4px;">{{ $member->name }}</h6>
                    <span style="font-size:.8rem;font-weight:600;color:var(--it-theme-1);display:block;margin-bottom:8px;">
                        {{ $member->is_form_teacher ? 'Form Teacher' : 'Subject Teacher' }}
                    </span>
                    @if($member->phone)
                    <p style="font-size:.82rem;color:var(--it-text-body);margin:0;">
                        <i class="fal fa-phone me-1" style="color:var(--it-theme-2);"></i>{{ $member->phone }}
                    </p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <div style="width:80px;height:80px;border-radius:50%;background:var(--it-gray-1);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                <i class="fal fa-users" style="font-size:2rem;color:var(--it-theme-3);"></i>
            </div>
            <h5 style="color:var(--it-common-black);">Staff profiles coming soon.</h5>
            <p style="color:var(--it-text-body);">Staff information will appear here once accounts are set up.</p>
        </div>
        @endif
    </div>
</section>

@endsection
