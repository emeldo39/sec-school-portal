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
    <title>Reset Password — {{ $schoolName }}</title>
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

    .pwd-tips {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .pwd-tip {
        display: flex;
        align-items: center;
        gap: 12px;
        color: rgba(255, 255, 255, .7);
        font-size: .82rem;
    }

    .pwd-tip-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(244, 188, 103, .15);
        border: 1px solid rgba(244, 188, 103, .2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #F4BC67;
        font-size: .9rem;
        flex-shrink: 0;
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

    .field-input-wrap:focus-within .field-icon {
        color: #2A2567;
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

    .field-error {
        font-size: .75rem;
        color: #ef4444;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 4px;
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

    /* Strength meter */
    .strength-bar {
        height: 4px;
        border-radius: 99px;
        background: #e2e8f0;
        margin-top: 8px;
        overflow: hidden;
    }

    .strength-bar-fill {
        height: 100%;
        border-radius: 99px;
        width: 0;
        transition: width .3s, background .3s;
    }

    .strength-label {
        font-size: .72rem;
        margin-top: 4px;
        font-weight: 600;
        transition: color .3s;
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
        margin-top: 8px;
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
                    Choose a<br><span>Strong</span><br>New Password
                </h2>
                <p class="auth-subline">A strong password keeps your student data and school records safe.</p>

                <div class="pwd-tips">
                    <div class="pwd-tip">
                        <div class="pwd-tip-icon"><i class="ri-check-line"></i></div>
                        <span>At least 8 characters long</span>
                    </div>
                    <div class="pwd-tip">
                        <div class="pwd-tip-icon"><i class="ri-check-line"></i></div>
                        <span>Mix of uppercase and lowercase letters</span>
                    </div>
                    <div class="pwd-tip">
                        <div class="pwd-tip-icon"><i class="ri-check-line"></i></div>
                        <span>Include at least one number or symbol</span>
                    </div>
                    <div class="pwd-tip">
                        <div class="pwd-tip-icon"><i class="ri-check-line"></i></div>
                        <span>Don't reuse passwords from other sites</span>
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
                    <i class="ri-shield-keyhole-line"></i>
                </div>

                <p class="auth-title">Set New Password</p>
                <p class="auth-subtitle">Your identity is verified. Choose a strong new password below.</p>

                @if($errors->any())
                <div class="auth-alert danger">
                    <i class="ri-error-warning-line" style="font-size:1rem;flex-shrink:0;margin-top:1px;"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    {{-- Email (pre-filled / read-only feel) --}}
                    <div class="field-group">
                        <label for="email" class="field-label">
                            Email Address <span class="req">*</span>
                        </label>
                        <div class="field-input-wrap">
                            <input type="email" id="email" name="email"
                                class="field-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                placeholder="Your email address" value="{{ old('email', $email ?? '') }}" required>
                            <i class="ri-mail-line field-icon"></i>
                        </div>
                        @error('email')
                        <div class="field-error"><i class="ri-error-warning-line"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- New Password + strength meter --}}
                    <div class="field-group">
                        <label for="password" class="field-label">
                            New Password <span class="req">*</span>
                        </label>
                        <div class="field-input-wrap">
                            <input type="password" id="password" name="password"
                                class="field-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="Min. 8 characters" required minlength="8"
                                oninput="checkStrength(this.value)">
                            <i class="ri-lock-line field-icon"></i>
                            <button type="button" class="pwd-toggle" id="togglePwd1" aria-label="Show/hide password">
                                <i class="ri-eye-line" id="togglePwd1Icon"></i>
                            </button>
                        </div>
                        <div class="strength-bar">
                            <div class="strength-bar-fill" id="strengthFill"></div>
                        </div>
                        <div class="strength-label" id="strengthLabel" style="color:#94a3b8;">Enter a password</div>
                        @error('password')
                        <div class="field-error"><i class="ri-error-warning-line"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="field-group">
                        <label for="password_confirmation" class="field-label">
                            Confirm Password <span class="req">*</span>
                        </label>
                        <div class="field-input-wrap">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="field-input" placeholder="Repeat new password" required>
                            <i class="ri-lock-2-line field-icon"></i>
                            <button type="button" class="pwd-toggle" id="togglePwd2"
                                aria-label="Show/hide confirm password">
                                <i class="ri-eye-line" id="togglePwd2Icon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="auth-btn">
                        <i class="ri-lock-unlock-line"></i> Reset Password
                    </button>
                </form>

                <a href="{{ route('login') }}" class="auth-back">
                    <i class="ri-arrow-left-line"></i> Back to Sign In
                </a>

            </div>
        </div>
    </div>

    <script>
    (function() {
        function togglePwd(inputId, btnId, iconId) {
            var input = document.getElementById(inputId);
            var btn = document.getElementById(btnId);
            var icon = document.getElementById(iconId);
            if (!btn) return;
            btn.addEventListener('click', function() {
                var show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                icon.className = show ? 'ri-eye-off-line' : 'ri-eye-line';
            });
        }
        togglePwd('password', 'togglePwd1', 'togglePwd1Icon');
        togglePwd('password_confirmation', 'togglePwd2', 'togglePwd2Icon');
    })();

    function checkStrength(val) {
        var fill = document.getElementById('strengthFill');
        var label = document.getElementById('strengthLabel');
        if (!fill) return;
        var score = 0;
        if (val.length >= 8) score++;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
        if (/\d/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        var levels = [{
                pct: '0%',
                color: '#e2e8f0',
                text: 'Enter a password',
                tc: '#94a3b8'
            },
            {
                pct: '25%',
                color: '#ef4444',
                text: 'Weak',
                tc: '#ef4444'
            },
            {
                pct: '50%',
                color: '#f97316',
                text: 'Fair',
                tc: '#f97316'
            },
            {
                pct: '75%',
                color: '#eab308',
                text: 'Good',
                tc: '#ca8a04'
            },
            {
                pct: '100%',
                color: '#22c55e',
                text: 'Strong',
                tc: '#16a34a'
            },
        ];
        var lv = val.length === 0 ? levels[0] : levels[Math.min(score, 4)];
        fill.style.width = lv.pct;
        fill.style.background = lv.color;
        label.textContent = lv.text;
        label.style.color = lv.tc;
    }
    </script>

</body>

</html>