@php
$schoolName = \App\Models\SchoolSetting::get('school_name', 'School Portal');
$schoolMotto = \App\Models\SchoolSetting::get('school_motto', '');
$schoolLogo = \App\Models\SchoolSetting::get('school_logo');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — {{ $schoolName }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" sizes="16x16">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <style>
    *,
    *::before,
    *::after {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #f8fafc;
        font-family: 'Open Sans', sans-serif;
    }

    .auth-wrap {
        display: flex;
        min-height: 100vh;
    }

    .auth-left {
        width: 45%;
        flex-shrink: 0;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 48px 40px;
        overflow: hidden;
        background: #1a1547;
    }

    .auth-left-bg {
        position: absolute;
        inset: 0;
        background-image: url('{{ asset('assets/images/thumbs/login-img.png') }}');
        background-size: cover;
        background-position: center;
        opacity: .12;
    }

    .auth-left-content {
        position: relative;
        z-index: 1;
    }

    .auth-brand {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 56px;
    }

    .auth-brand img {
        height: 52px;
        filter: brightness(0) invert(1);
    }

    .auth-brand-text {
        color: #fff;
    }

    .auth-brand-text strong {
        display: block;
        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .auth-brand-text span {
        font-size: .78rem;
        opacity: .65;
    }

    .auth-headline {
        color: #fff !important;
        font-size: 2rem !important;
        font-weight: 800 !important;
        line-height: 1.25 !important;
        margin-bottom: 16px !important;
        margin-top: 80px;
    }

    .auth-headline span {
        color: #F4BC67;
    }

    .auth-subline {
        color: rgba(255, 255, 255, .65);
        font-size: .9rem;
        line-height: 1.65;
        max-width: 360px;
        margin-bottom: 48px;
    }

    .auth-steps {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .auth-step {
        display: flex;
        align-items: flex-start;
        gap: 16px;
    }

    .auth-step-num {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(244, 188, 103, .2);
        border: 1px solid rgba(244, 188, 103, .3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        font-weight: 700;
        color: #F4BC67;
        flex-shrink: 0;
    }

    .auth-step-body {
        color: rgba(255, 255, 255, .75);
    }

    .auth-step-body strong {
        display: block;
        color: #fff;
        font-size: .85rem;
        margin-bottom: 2px;
    }

    .auth-step-body span {
        font-size: .78rem;
        line-height: 1.4;
    }

    .auth-left-footer {
        position: relative;
        z-index: 1;
        border-top: 1px solid rgba(255, 255, 255, .1);
        padding-top: 24px;
        color: rgba(255, 255, 255, .4);
        font-size: .75rem;
    }

    .auth-right {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 24px;
        background: #fff;
    }

    .auth-form-box {
        width: 100%;
        max-width: 440px;
    }

    .auth-mobile-brand {
        display: none;
        align-items: center;
        gap: 12px;
        margin-bottom: 32px;
    }

    .auth-mobile-brand img {
        height: 60px;
    }

    .auth-mobile-brand span {
        font-size: .95rem;
        font-weight: 700;
        color: #1e293b;
    }

    /* Icon badge above title */
    .auth-icon-badge {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: #EEF0FF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #2A2567;
        margin-bottom: 20px;
    }

    .auth-title {
        font-size: 1.55rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 6px;
    }

    .auth-subtitle {
        font-size: .875rem;
        color: #64748b;
        margin-bottom: 32px;
    }

    .field-group {
        margin-bottom: 20px;
    }

    .field-label {
        display: block;
        font-size: .8rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
        letter-spacing: .2px;
    }

    .field-label .req {
        color: #ef4444;
        margin-left: 2px;
    }

    .field-input-wrap {
        position: relative;
    }

    .field-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1rem;
        pointer-events: none;
        transition: color .2s;
    }

    .field-input {
        width: 100%;
        height: 48px;
        padding: 0 16px 0 42px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        font-size: .875rem;
        color: #1e293b;
        background: #f8fafc;
        transition: border-color .2s, box-shadow .2s, background .2s;
        outline: none;
    }

    .field-input:focus {
        border-color: #2A2567;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(42, 37, 103, .08);
    }

    .field-input.is-invalid {
        border-color: #ef4444;
    }

    .field-input-wrap:focus-within .field-icon {
        color: #2A2567;
    }

    .field-error {
        font-size: .75rem;
        color: #ef4444;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .auth-alert {
        padding: 12px 16px;
        border-radius: 10px;
        font-size: .82rem;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .auth-alert.danger {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
    }

    .auth-alert.success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #15803d;
    }

    .auth-btn {
        width: 100%;
        height: 50px;
        background: #2A2567;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: .9rem;
        font-weight: 700;
        letter-spacing: .3px;
        cursor: pointer;
        transition: background .2s, box-shadow .2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .auth-btn:hover {
        background: #3d3499;
        box-shadow: 0 4px 16px rgba(42, 37, 103, .25);
    }

    .auth-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: .82rem;
        color: #2A2567;
        font-weight: 600;
        text-decoration: none;
        margin-top: 24px;
    }

    .auth-back:hover {
        text-decoration: underline;
    }

    @media (max-width: 991px) {
        .auth-left {
            display: none;
        }

        .auth-right {
            background: #f8fafc;
        }

        .auth-mobile-brand {
            display: flex;
        }
    }

    @media (max-width: 480px) {
        .auth-right {
            padding: 32px 20px;
            align-items: flex-start;
        }
    }
    </style>
</head>

<body>

    <div class="auth-wrap">

        {{-- Left: Branded panel --}}
        <div class="auth-left">
            <div class="auth-left-bg"></div>
            <div class="auth-left-content">
                <div class="auth-brand">
                    @if($schoolLogo)
                    <img src="{{ asset('storage/' . $schoolLogo) }}" alt="{{ $schoolName }}">
                    @else
                    <div
                        style="width:48px;height:48px;border-radius:12px;background:rgba(244,188,103,.2);border:1px solid rgba(244,188,103,.3);display:flex;align-items:center;justify-content:center;font-size:1.2rem;font-weight:800;color:#F4BC67;">
                        {{ strtoupper(substr($schoolName, 0, 1)) }}
                    </div>
                    @endif
                    <div class="auth-brand-text">
                        <strong>{{ $schoolName }}</strong>
                        @if($schoolMotto)<span>{{ $schoolMotto }}</span>@endif
                    </div>
                </div>

                <h2 class="auth-headline">
                    Regain<br><span>Access</span> in<br>3 Simple Steps
                </h2>
                <p class="auth-subline">No need to call IT. Reset your password securely from your inbox.</p>

                <div class="auth-steps">
                    <div class="auth-step">
                        <div class="auth-step-num">1</div>
                        <div class="auth-step-body">
                            <strong>Enter your email</strong>
                            <span>Provide the email address linked to your staff account.</span>
                        </div>
                    </div>
                    <div class="auth-step">
                        <div class="auth-step-num">2</div>
                        <div class="auth-step-body">
                            <strong>Check your inbox</strong>
                            <span>We'll send you a secure password reset link immediately.</span>
                        </div>
                    </div>
                    <div class="auth-step">
                        <div class="auth-step-num">3</div>
                        <div class="auth-step-body">
                            <strong>Set a new password</strong>
                            <span>Click the link in the email and choose a strong new password.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth-left-footer">
                &copy; {{ date('Y') }} {{ $schoolName }}. All rights reserved.
            </div>
        </div>

        {{-- Right: Form --}}
        <div class="auth-right">
            <div class="auth-form-box">

                <div class="auth-mobile-brand">
                    @if($schoolLogo)
                    <img src="{{ asset('storage/' . $schoolLogo) }}" alt="{{ $schoolName }}">
                    @endif
                    <span>{{ $schoolName }}</span>
                </div>

                <div class="auth-icon-badge">
                    <i class="ri-lock-password-line"></i>
                </div>

                <p class="auth-title">Forgot Password?</p>
                <p class="auth-subtitle">
                    Enter your registered email and we'll send a reset link to your inbox.
                </p>

                @if(session('success'))
                <div class="auth-alert success">
                    <i class="ri-checkbox-circle-line" style="font-size:1rem;flex-shrink:0;margin-top:1px;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if($errors->any())
                <div class="auth-alert danger">
                    <i class="ri-error-warning-line" style="font-size:1rem;flex-shrink:0;margin-top:1px;"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST" novalidate>
                    @csrf

                    <div class="field-group">
                        <label for="email" class="field-label">
                            Email Address <span class="req">*</span>
                        </label>
                        <div class="field-input-wrap">
                            <input type="email" id="email" name="email"
                                class="field-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                placeholder="Enter your registered email" value="{{ old('email') }}" required autofocus>
                            <i class="ri-mail-line field-icon"></i>
                        </div>
                        @error('email')
                        <div class="field-error"><i class="ri-error-warning-line"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="auth-btn">
                        <i class="ri-mail-send-line"></i> Send Reset Link
                    </button>
                </form>

                <a href="{{ route('login') }}" class="auth-back">
                    <i class="ri-arrow-left-line"></i> Back to Sign In
                </a>

            </div>
        </div>
    </div>

</body>

</html>