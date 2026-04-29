@extends('layouts.public')

@section('title', 'Contact Us — ' . \App\Models\SchoolSetting::get('school_name'))

@section('content')
@php
    $phone   = \App\Models\SchoolSetting::get('school_phone');
    $email   = \App\Models\SchoolSetting::get('school_email');
    $address = \App\Models\SchoolSetting::get('school_address');
    $school  = \App\Models\SchoolSetting::get('school_name', 'Our School');
@endphp

{{-- ── Breadcrumb ──────────────────────────────────────────────────────── --}}
<section class="it-breadcrumb-dark" data-background="{{ asset('template/img/breadcrumb/contact.jpg') }}">
    <div class="it-breadcrumb-dark-overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="text-center">
                    <h4 class="it-breadcrumb-title">Contact Us</h4>
                    <nav>
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Contact</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Contact Info Cards ───────────────────────────────────────────────── --}}
<section class="pt-120 pb-60">
    <div class="container">
        <div class="row justify-content-center mb-50">
            <div class="col-lg-7 text-center">
                <span class="it-section-subtitle yellow-style">Get In Touch</span>
                <h4 class="it-section-title">We'd Love to Hear From You</h4>
                <p style="color:var(--it-text-body);">Whether you have a question about admissions, want to reach a teacher, or just want to know more about our school — we're here to help.</p>
            </div>
        </div>

        <div class="row g-4 mb-60">
            @if($address)
            <div class="col-lg-3 col-md-6 wow itfadeUp" data-wow-duration=".9s" data-wow-delay=".1s">
                <div class="text-center p-4" style="background:var(--it-gray-1);border-radius:14px;height:100%;transition:transform .2s;"
                     onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div class="mb-15 mx-auto" style="width:56px;height:56px;border-radius:50%;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;">
                        <i class="fal fa-map-marker-alt" style="color:#fff;font-size:1.3rem;"></i>
                    </div>
                    <h6 style="font-weight:700;color:var(--it-common-black);margin-bottom:8px;">School Address</h6>
                    <p style="font-size:.88rem;color:var(--it-text-body);margin:0;">{{ $address }}</p>
                </div>
            </div>
            @endif
            @if($phone)
            <div class="col-lg-3 col-md-6 wow itfadeUp" data-wow-duration=".9s" data-wow-delay=".2s">
                <div class="text-center p-4" style="background:var(--it-gray-1);border-radius:14px;height:100%;transition:transform .2s;"
                     onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div class="mb-15 mx-auto" style="width:56px;height:56px;border-radius:50%;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;">
                        <i class="fal fa-phone" style="color:#fff;font-size:1.3rem;"></i>
                    </div>
                    <h6 style="font-weight:700;color:var(--it-common-black);margin-bottom:8px;">Phone</h6>
                    <a href="tel:{{ $phone }}" style="font-size:.88rem;color:var(--it-text-body);text-decoration:none;">{{ $phone }}</a>
                </div>
            </div>
            @endif
            @if($email)
            <div class="col-lg-3 col-md-6 wow itfadeUp" data-wow-duration=".9s" data-wow-delay=".3s">
                <div class="text-center p-4" style="background:var(--it-gray-1);border-radius:14px;height:100%;transition:transform .2s;"
                     onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div class="mb-15 mx-auto" style="width:56px;height:56px;border-radius:50%;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;">
                        <i class="fal fa-envelope" style="color:#fff;font-size:1.3rem;"></i>
                    </div>
                    <h6 style="font-weight:700;color:var(--it-common-black);margin-bottom:8px;">Email</h6>
                    <a href="mailto:{{ $email }}" style="font-size:.88rem;color:var(--it-text-body);text-decoration:none;">{{ $email }}</a>
                </div>
            </div>
            @endif
            <div class="col-lg-3 col-md-6 wow itfadeUp" data-wow-duration=".9s" data-wow-delay=".4s">
                <div class="text-center p-4" style="background:var(--it-gray-1);border-radius:14px;height:100%;transition:transform .2s;"
                     onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div class="mb-15 mx-auto" style="width:56px;height:56px;border-radius:50%;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;">
                        <i class="fal fa-clock" style="color:#fff;font-size:1.3rem;"></i>
                    </div>
                    <h6 style="font-weight:700;color:var(--it-common-black);margin-bottom:8px;">Office Hours</h6>
                    <p style="font-size:.88rem;color:var(--it-text-body);margin:0;">
                        Mon – Fri: 8:00 AM – 4:00 PM<br>
                        Saturday: 9:00 AM – 1:00 PM
                    </p>
                </div>
            </div>
        </div>

        {{-- Contact Form --}}
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if(session('success'))
                <div class="d-flex align-items-center gap-2 p-3 mb-30" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;color:#15803d;">
                    <i class="fal fa-check-circle" style="font-size:1.2rem;flex-shrink:0;"></i>
                    {{ session('success') }}
                </div>
                @endif

                <div class="p-4 p-lg-5 wow itfadeUp" style="background:var(--it-gray-1);border-radius:16px;" data-wow-duration=".9s" data-wow-delay=".3s">
                    <div class="mb-30">
                        <span class="it-section-subtitle yellow-style">Send a Message</span>
                        <h4 class="it-section-title">We'll Respond Shortly</h4>
                    </div>

                    <form action="{{ route('contact.send') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label style="font-size:.85rem;font-weight:600;color:var(--it-common-black);margin-bottom:8px;display:block;">
                                    Your Name <span style="color:#e53935;">*</span>
                                </label>
                                <input type="text"
                                       name="name"
                                       value="{{ old('name') }}"
                                       placeholder="Full name"
                                       required
                                       class="@error('name') is-invalid @enderror"
                                       style="width:100%;padding:12px 16px;border:1px solid #ddd;border-radius:8px;font-size:.9rem;outline:none;transition:border-color .2s;"
                                       onfocus="this.style.borderColor='var(--it-theme-1)'" onblur="this.style.borderColor='#ddd'">
                                @error('name')<div class="invalid-feedback d-block" style="font-size:.8rem;color:#e53935;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label style="font-size:.85rem;font-weight:600;color:var(--it-common-black);margin-bottom:8px;display:block;">
                                    Email Address <span style="color:#e53935;">*</span>
                                </label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       placeholder="your@email.com"
                                       required
                                       class="@error('email') is-invalid @enderror"
                                       style="width:100%;padding:12px 16px;border:1px solid #ddd;border-radius:8px;font-size:.9rem;outline:none;transition:border-color .2s;"
                                       onfocus="this.style.borderColor='var(--it-theme-1)'" onblur="this.style.borderColor='#ddd'">
                                @error('email')<div class="invalid-feedback d-block" style="font-size:.8rem;color:#e53935;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label style="font-size:.85rem;font-weight:600;color:var(--it-common-black);margin-bottom:8px;display:block;">
                                    Subject <span style="color:#e53935;">*</span>
                                </label>
                                <input type="text"
                                       name="subject"
                                       value="{{ old('subject') }}"
                                       placeholder="What is this about?"
                                       required
                                       class="@error('subject') is-invalid @enderror"
                                       style="width:100%;padding:12px 16px;border:1px solid #ddd;border-radius:8px;font-size:.9rem;outline:none;transition:border-color .2s;"
                                       onfocus="this.style.borderColor='var(--it-theme-1)'" onblur="this.style.borderColor='#ddd'">
                                @error('subject')<div class="invalid-feedback d-block" style="font-size:.8rem;color:#e53935;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label style="font-size:.85rem;font-weight:600;color:var(--it-common-black);margin-bottom:8px;display:block;">
                                    Message <span style="color:#e53935;">*</span>
                                </label>
                                <textarea name="message"
                                          rows="6"
                                          placeholder="Write your message here..."
                                          required
                                          class="@error('message') is-invalid @enderror"
                                          style="width:100%;padding:12px 16px;border:1px solid #ddd;border-radius:8px;font-size:.9rem;outline:none;transition:border-color .2s;resize:vertical;"
                                          onfocus="this.style.borderColor='var(--it-theme-1)'" onblur="this.style.borderColor='#ddd'">{{ old('message') }}</textarea>
                                @error('message')<div class="invalid-feedback d-block" style="font-size:.8rem;color:#e53935;margin-top:4px;">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <button type="submit" class="it-btn-yellow border-radius-100">
                                    <span><span class="text-1">Send Message</span><span class="text-2">Send Message</span></span>
                                    <i><svg width="16" height="15" viewBox="0 0 16 15" fill="none"><path d="M15.0544 8.1364C15.4058 7.78492 15.4058 7.21508 15.0544 6.8636L9.3268 1.13604C8.97533 0.784567 8.40548 0.784567 8.05401 1.13604C7.70254 1.48751 7.70254 2.05736 8.05401 2.40883L13.1452 7.5L8.05401 12.5912C7.70254 12.9426 7.70254 13.5125 8.05401 13.864C8.40548 14.2154 8.97533 14.2154 9.3268 13.864L15.0544 8.1364ZM0.417969 7.5V8.4H14.418V7.5V6.6H0.417969V7.5Z" fill="currentcolor"/></svg></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
