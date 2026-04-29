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
    <title>Sign In — {{ $schoolName }}</title>
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

    /* ── Layout ──────────────────────────────────────── */
    .auth-wrap {
        display: flex;
        min-height: 100vh;
    }

    /* ── Left branded panel ──────────────────────────── */
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
        color: #F4BC67 !important;
    }

    .auth-subline {
        color: rgba(255, 255, 255, .65);
        font-size: .9rem;
        line-height: 1.65;
        max-width: 360px;
        margin-bottom: 48px;
    }

    .auth-features {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .auth-feature {
        display: flex;
        align-items: center;
        gap: 14px;
        color: rgba(255, 255, 255, .8);
        font-size: .85rem;
    }

    .auth-feature-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(244, 188, 103, .15);
        border: 1px solid rgba(244, 188, 103, .25);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
        color: #F4BC67;
    }

    .auth-left-footer {
        position: relative;
        z-index: 1;
        border-top: 1px solid rgba(255, 255, 255, .1);
        padding-top: 24px;
        color: rgba(255, 255, 255, .4);
        font-size: .75rem;
    }

    /* ── Right form panel ────────────────────────────── */
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

    /* ── Form fields ─────────────────────────────────── */
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
        padding: 0 44px 0 42px;
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

    .field-input:focus+.field-icon,
    .field-input-wrap:focus-within .field-icon {
        color: #2A2567;
    }

    .pwd-toggle {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        padding: 0;
        cursor: pointer;
        color: #94a3b8;
        font-size: 1rem;
        line-height: 1;
        transition: color .2s;
    }

    .pwd-toggle:hover {
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

    /* ── Remember / Forgot row ───────────────────────── */
    .auth-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .auth-check {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .auth-check input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #2A2567;
        cursor: pointer;
    }

    .auth-check-label {
        font-size: .82rem;
        color: #64748b;
        cursor: pointer;
    }

    .auth-forgot {
        font-size: .82rem;
        color: #2A2567;
        font-weight: 600;
        text-decoration: none;
    }

    .auth-forgot:hover {
        text-decoration: underline;
    }

    /* ── Submit button ───────────────────────────────── */
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
        transition: background .2s, transform .1s, box-shadow .2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .auth-btn:hover {
        background: #3d3499;
        box-shadow: 0 4px 16px rgba(42, 37, 103, .25);
    }

    .auth-btn:active {
        transform: scale(.99);
    }

    /* ── Alert ───────────────────────────────────────── */
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

    /* ── Back link ───────────────────────────────────── */
    .auth-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: .82rem;
        color: #2A2567;
        font-weight: 600;
        text-decoration: none;
        margin-top: 28px;
    }

    .auth-back:hover {
        text-decoration: underline;
    }

    /* ── Responsive ──────────────────────────────────── */
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

        .auth-form-box {
            max-width: 100%;
        }
    }
    </style>
</head>

<body>

    <div class="auth-wrap">

        {{-- ── Left: Branded panel ─────────────────────────────────────── --}}
        <div class="auth-left">
            <div class="auth-left-bg"></div>

            <div class="auth-left-content">
                {{-- Brand --}}
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

                {{-- Headline --}}
                <h2 class="auth-headline">
                    Your <span>Staff Portal</span><br>Awaits You
                </h2>
                <p class="auth-subline">
                    Manage students, scores, attendance, and reports — all in one secure platform built for your school.
                </p>

                {{-- Features --}}
                <div class="auth-features">
                    <div class="auth-feature">
                        <div class="auth-feature-icon"><i class="ri-shield-check-line"></i></div>
                        <span>Secure role-based access for staff and administrators</span>
                    </div>
                    <div class="auth-feature">
                        <div class="auth-feature-icon"><i class="ri-bar-chart-grouped-line"></i></div>
                        <span>Real-time scores, results, and academic reports</span>
                    </div>
                    <div class="auth-feature">
                        <div class="auth-feature-icon"><i class="ri-notification-3-line"></i></div>
                        <span>Announcements and communication in one place</span>
                    </div>
                </div>
            </div>

            <div class="auth-left-footer">
                &copy; {{ date('Y') }} {{ $schoolName }}. All rights reserved.
            </div>
        </div>

        {{-- ── Right: Form ─────────────────────────────────────────────── --}}
        <div class="auth-right">
            <div class="auth-form-box">

                {{-- Mobile brand --}}
                <div class="auth-mobile-brand">
                    @if($schoolLogo)
                    <img src="{{ asset('storage/' . $schoolLogo) }}" alt="{{ $schoolName }}">
                    @endif
                    <span>{{ $schoolName }}</span>
                </div>

                <p class="auth-title">Welcome Back</p>
                <p class="auth-subtitle">Sign in to your staff portal account to continue.</p>

                {{-- Error --}}
                @if($errors->any())
                <div class="auth-alert danger">
                    <i class="ri-error-warning-line" style="font-size:1rem;flex-shrink:0;margin-top:1px;"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form action="{{ route('login') }}" method="POST" novalidate>
                    @csrf

                    {{-- Email --}}
                    <div class="field-group">
                        <label for="email" class="field-label">
                            Email Address <span class="req">*</span>
                        </label>
                        <div class="field-input-wrap">
                            <input type="email" id="email" name="email"
                                class="field-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                placeholder="you@school.edu.ng" value="{{ old('email') }}" required autofocus>
                            <i class="ri-mail-line field-icon"></i>
                        </div>
                        @error('email')
                        <div class="field-error"><i class="ri-error-warning-line"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="field-group">
                        <label for="password" class="field-label">
                            Password <span class="req">*</span>
                        </label>
                        <div class="field-input-wrap">
                            <input type="password" id="password" name="password"
                                class="field-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="Enter your password" required>
                            <i class="ri-lock-line field-icon"></i>
                            <button type="button" class="pwd-toggle" id="togglePassword"
                                aria-label="Show/hide password">
                                <i class="ri-eye-line" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                        @error('password')
                        <div class="field-error"><i class="ri-error-warning-line"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Remember / Forgot --}}
                    <div class="auth-meta">
                        <label class="auth-check">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="auth-check-label">Remember me</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="auth-forgot">Forgot password?</a>
                    </div>

                    <button type="submit" class="auth-btn">
                        <i class="ri-login-box-line"></i> Sign In
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script>
    (function() {
        var input = document.getElementById('password');
        var btn = document.getElementById('togglePassword');
        var icon = document.getElementById('togglePasswordIcon');
        if (!btn) return;
        btn.addEventListener('click', function() {
            var isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            icon.className = isPassword ? 'ri-eye-off-line' : 'ri-eye-line';
        });
    })();
    </script>

</body>

</html>