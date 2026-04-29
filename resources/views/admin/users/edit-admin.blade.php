@extends('layouts.portal')

@section('title', 'Edit Admin Account')
@section('page-title', 'Edit IT / Admin Account')
@section('page-subtitle', $user->name)

@section('content')
<div class="row gy-24 justify-content-center">
    <div class="col-lg-8">

        {{-- Error summary --}}
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-20" role="alert">
            <i class="ri-error-warning-line me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-6 ps-16">
                @foreach($errors->all() as $error)
                <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Account Details --}}
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="mb-20">
            @csrf @method('PUT')
            <div class="card shadow-1 radius-8">
                <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                    <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8" style="background:#FFF8E1;">
                        <i class="ri-shield-user-line" style="font-size:16px; color:#F57F17;"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color:#2A2567;">Account Details</h6>
                        <p class="text-xs text-secondary-light mb-0">IT / Administrator login information</p>
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="row gy-20">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-user-line text-secondary-light"></i>
                                </span>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="Full name" required maxlength="100">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-mail-line text-secondary-light"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="Email address" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Phone Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-phone-line text-secondary-light"></i>
                                </span>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       placeholder="e.g. 08012345678" maxlength="20">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm mb-8" style="color:#2A2567;">Role</label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-shield-line text-secondary-light"></i>
                                </span>
                                <input type="text" class="form-control bg-neutral-50" value="IT / Administrator" disabled>
                            </div>
                            <small class="text-secondary-light mt-4 d-block" style="font-size:11px;">
                                Role cannot be changed here
                            </small>
                        </div>
                    </div>
                </div>
                <div class="card-footer px-24 py-16 d-flex align-items-center gap-12">
                    <button type="submit" class="btn btn-primary px-32">
                        <i class="ri-save-line me-1"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-24">Cancel</a>
                </div>
            </div>
        </form>

        {{-- Reset Password --}}
        <div class="card shadow-1 radius-8">
            <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8" style="background:#FEE2E2;">
                    <i class="ri-lock-password-line" style="font-size:16px; color:#B91C1C;"></i>
                </span>
                <div>
                    <h6 class="fw-semibold mb-0" style="color:#2A2567;">Reset Password</h6>
                    <p class="text-xs text-secondary-light mb-0">Set a new login password for {{ $user->name }}</p>
                </div>
            </div>
            <div class="card-body p-24">
                <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
                    @csrf
                    <div class="row gy-20">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                New Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-lock-line text-secondary-light"></i>
                                </span>
                                <input type="password" name="password" id="newPassword"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Min. 6 characters" required minlength="6">
                                <button type="button" class="input-group-text bg-neutral-50 border-start-0"
                                        onclick="togglePw('newPassword','togglePwIcon')" tabindex="-1" style="cursor:pointer;">
                                    <i id="togglePwIcon" class="ri-eye-off-line text-secondary-light"></i>
                                </button>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Confirm Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-lock-2-line text-secondary-light"></i>
                                </span>
                                <input type="password" name="password_confirmation" id="confirmPassword"
                                       class="form-control"
                                       placeholder="Repeat password" required>
                                <button type="button" class="input-group-text bg-neutral-50 border-start-0"
                                        onclick="togglePw('confirmPassword','toggleConfIcon')" tabindex="-1" style="cursor:pointer;">
                                    <i id="toggleConfIcon" class="ri-eye-off-line text-secondary-light"></i>
                                </button>
                            </div>
                            <small id="matchHint" class="mt-6 d-block" style="font-size:11px; display:none !important;"></small>
                        </div>
                    </div>
                    <div class="mt-20">
                        <button type="submit" class="btn btn-danger px-32"
                                onclick="return confirm('Reset password for {{ $user->name }}?')">
                            <i class="ri-lock-password-line me-1"></i> Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
function togglePw(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    const show  = input.type === 'password';
    input.type  = show ? 'text' : 'password';
    icon.className = show ? 'ri-eye-line text-secondary-light' : 'ri-eye-off-line text-secondary-light';
}

const pw1   = document.getElementById('newPassword');
const pw2   = document.getElementById('confirmPassword');
const hint  = document.getElementById('matchHint');

function checkMatch() {
    if (!pw2.value) { hint.style.display = 'none'; return; }
    const ok = pw1.value === pw2.value;
    hint.style.cssText = 'font-size:11px; display:block !important;';
    hint.textContent   = ok ? '✓ Passwords match' : '✗ Passwords do not match';
    hint.style.color   = ok ? '#22c55e' : '#ef4444';
}
pw1.addEventListener('input', checkMatch);
pw2.addEventListener('input', checkMatch);
</script>
@endpush
