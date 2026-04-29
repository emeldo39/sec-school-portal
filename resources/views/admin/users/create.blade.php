@extends('layouts.portal')

@php $creatingAdmin = auth()->user()->isPrincipal() && request('account_role') === 'admin'; @endphp

@section('title', $creatingAdmin ? 'Add IT / Admin Account' : 'Add Staff Account')
@section('page-title', $creatingAdmin ? 'Add IT / Admin Account' : 'Add Staff Account')
@section('page-subtitle', $creatingAdmin ? 'Create a new IT / office administrator login' : 'Create a new teacher login and assign classes')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">

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

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <input type="hidden" name="account_role" value="{{ $creatingAdmin ? 'admin' : 'teacher' }}">

            {{-- ── Account type switcher (principal only) ── --}}
            @if(auth()->user()->isPrincipal())
            <div class="card shadow-1 radius-8 mb-20">
                <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                    <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8"
                          style="background:#EEF0FF;">
                        <i class="ri-switch-line text-primary-600" style="font-size:16px;"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color:#2A2567;">Account Type</h6>
                        <p class="text-xs text-secondary-light mb-0">Choose the type of staff account to create</p>
                    </div>
                </div>
                <div class="card-body p-20">
                    <div class="row gy-12">
                        <div class="col-sm-6">
                            <a href="{{ route('admin.users.create') }}"
                               class="d-flex align-items-start gap-14 p-16 radius-8 text-decoration-none border
                                      {{ !$creatingAdmin ? 'border-primary-400' : 'border' }}"
                               style="{{ !$creatingAdmin ? 'background:#F4F4FF;' : 'background:#fff;' }}">
                                <span class="w-36 h-36 flex-shrink-0 d-flex align-items-center justify-content-center radius-8
                                             {{ !$creatingAdmin ? 'bg-primary-600' : 'bg-neutral-100' }}">
                                    <i class="ri-user-2-line {{ !$creatingAdmin ? 'text-white' : 'text-secondary-light' }}" style="font-size:17px;"></i>
                                </span>
                                <div>
                                    <p class="fw-semibold text-sm mb-1 {{ !$creatingAdmin ? 'text-primary-600' : '' }}" style="{{ $creatingAdmin ? 'color:#2A2567;' : '' }}">
                                        Teacher
                                    </p>
                                    <p class="text-xs mb-0 text-secondary-light">Can enter scores, manage attendance and generate result sheets</p>
                                </div>
                                @if(!$creatingAdmin)
                                <i class="ri-checkbox-circle-fill text-primary-600 ms-auto flex-shrink-0" style="font-size:18px;"></i>
                                @endif
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('admin.users.create') }}?account_role=admin"
                               class="d-flex align-items-start gap-14 p-16 radius-8 text-decoration-none border
                                      {{ $creatingAdmin ? 'border-warning-400' : 'border' }}"
                               style="{{ $creatingAdmin ? 'background:#FFFBF0;' : 'background:#fff;' }}">
                                <span class="w-36 h-36 flex-shrink-0 d-flex align-items-center justify-content-center radius-8
                                             {{ $creatingAdmin ? 'bg-warning-500' : 'bg-neutral-100' }}">
                                    <i class="ri-shield-user-line {{ $creatingAdmin ? 'text-white' : 'text-secondary-light' }}" style="font-size:17px;"></i>
                                </span>
                                <div>
                                    <p class="fw-semibold text-sm mb-1 {{ $creatingAdmin ? 'text-warning-600' : '' }}" style="{{ !$creatingAdmin ? 'color:#2A2567;' : '' }}">
                                        IT / Admin
                                    </p>
                                    <p class="text-xs mb-0 text-secondary-light">Can view and manage school data but cannot approve scores or change settings</p>
                                </div>
                                @if($creatingAdmin)
                                <i class="ri-checkbox-circle-fill text-warning-600 ms-auto flex-shrink-0" style="font-size:18px;"></i>
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ── Personal Information ── --}}
            <div class="card shadow-1 radius-8 mb-20">
                <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                    <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8"
                          style="background:#FFF3E0;">
                        <i class="ri-user-line" style="font-size:16px; color:#E65100;"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color:#2A2567;">Personal Information</h6>
                        <p class="text-xs text-secondary-light mb-0">
                            {{ $creatingAdmin ? 'IT / Admin staff details and login credentials' : 'Teacher\'s details and login credentials' }}
                        </p>
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
                                <input type="text" name="name" value="{{ old('name') }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="e.g. Chukwuemeka Obi"
                                       maxlength="100" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <small class="text-secondary-light mt-6 d-block" style="font-size:11px;">
                                As it should appear on official documents
                            </small>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Email Address <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-mail-line text-secondary-light"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="{{ $creatingAdmin ? 'ict@school.edu.ng' : 'teacher@school.edu.ng' }}"
                                       autocomplete="off" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <small class="text-secondary-light mt-6 d-block" style="font-size:11px;">
                                Used as the login username — must be unique
                            </small>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Phone Number <span class="text-secondary-light fw-normal">(optional)</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-phone-line text-secondary-light"></i>
                                </span>
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       placeholder="e.g. 08012345678"
                                       maxlength="20">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <small class="text-secondary-light mt-6 d-block" style="font-size:11px;">
                                Nigerian format: 080XXXXXXXX or +234XXXXXXXXXX
                            </small>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Profile Photo <span class="text-secondary-light fw-normal">(optional)</span>
                            </label>
                            <input type="file" name="photo" id="staffPhoto" accept="image/*" class="form-control">
                            <small class="text-secondary-light mt-6 d-block" style="font-size:11px;">
                                JPG / PNG / WebP &mdash; will appear on staff pages and result sheets
                            </small>
                            <img id="staffPhotoPreview" src="" alt=""
                                 class="mt-8 rounded" style="height:64px; display:none;">
                        </div>

                        @if(!$creatingAdmin)
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Signature Image <span class="text-secondary-light fw-normal">(optional)</span>
                            </label>
                            <input type="file" name="signature" accept="image/*" class="form-control">
                            <small class="text-secondary-light mt-6 d-block" style="font-size:11px;">
                                Upload a scanned signature — background will be auto-removed
                            </small>
                        </div>
                        @endif

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-lock-line text-secondary-light"></i>
                                </span>
                                <input type="password" name="password" id="passwordInput"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Min. 6 characters"
                                       minlength="6" autocomplete="new-password" required>
                                <button type="button" class="input-group-text bg-neutral-50 border-start-0"
                                        id="togglePassword" tabindex="-1" style="cursor:pointer;">
                                    <i class="ri-eye-off-line text-secondary-light" id="togglePasswordIcon"></i>
                                </button>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            {{-- Strength bar --}}
                            <div class="mt-8" id="strengthWrap" style="display:none;">
                                <div class="d-flex gap-4 mb-4">
                                    <div class="strength-bar flex-1 radius-4" id="sb1" style="height:4px;background:#e0e0e0;"></div>
                                    <div class="strength-bar flex-1 radius-4" id="sb2" style="height:4px;background:#e0e0e0;"></div>
                                    <div class="strength-bar flex-1 radius-4" id="sb3" style="height:4px;background:#e0e0e0;"></div>
                                    <div class="strength-bar flex-1 radius-4" id="sb4" style="height:4px;background:#e0e0e0;"></div>
                                </div>
                                <small id="strengthLabel" class="text-secondary-light" style="font-size:11px;"></small>
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
                                <input type="password" name="password_confirmation" id="passwordConfirm"
                                       class="form-control"
                                       placeholder="Repeat password"
                                       autocomplete="new-password" required>
                                <button type="button" class="input-group-text bg-neutral-50 border-start-0"
                                        id="toggleConfirm" tabindex="-1" style="cursor:pointer;">
                                    <i class="ri-eye-off-line text-secondary-light" id="toggleConfirmIcon"></i>
                                </button>
                            </div>
                            <small id="matchHint" class="mt-6 d-block" style="font-size:11px; display:none !important;"></small>
                        </div>

                    </div>
                </div>

                {{-- Admin: submit inside this card --}}
                @if($creatingAdmin)
                <div class="card-footer px-24 py-16 d-flex align-items-center justify-content-between">
                    <p class="text-xs text-secondary-light mb-0">
                        <span class="text-danger">*</span> Required fields
                    </p>
                    <div class="d-flex gap-12">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-24">
                            <i class="ri-arrow-left-line me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-warning px-32 text-white">
                            <i class="ri-shield-user-line me-1"></i> Create Admin Account
                        </button>
                    </div>
                </div>
                @endif
            </div>

            {{-- ── Class Assignment (teachers only) ── --}}
            @if(!$creatingAdmin)

            {{-- Form Teacher --}}
            <div class="card shadow-1 radius-8 mb-20">
                <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                    <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8"
                          style="background:#E8F5E9;">
                        <i class="ri-user-star-line" style="font-size:16px; color:#2E7D32;"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color:#2A2567;">Form Teacher Assignment</h6>
                        <p class="text-xs text-secondary-light mb-0">
                            A form teacher has overall responsibility for one class
                        </p>
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="row gy-20">

                        <div class="col-12">
                            <label class="form-teacher-toggle w-100" for="isFormTeacher">
                                <input type="checkbox" name="is_form_teacher" id="isFormTeacher"
                                       value="1" {{ old('is_form_teacher') ? 'checked' : '' }}
                                       style="position:absolute;opacity:0;width:0;height:0;">
                                <span class="toggle-icon">
                                    <i class="ri-user-star-line"></i>
                                </span>
                                <div>
                                    <p class="fw-semibold text-sm mb-1" style="color:#2A2567;">
                                        Assign as Form Teacher
                                    </p>
                                    <p class="text-xs mb-0" style="color:#7B79A0;">
                                        Form teachers can generate and download result sheets, declare results public and add psychomotor / affective ratings for students in their class.
                                    </p>
                                </div>
                            </label>
                        </div>

                        <div class="col-sm-6" id="formClassField"
                             style="{{ old('is_form_teacher') ? '' : 'display:none;' }}">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Form Class <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-building-line text-secondary-light"></i>
                                </span>
                                <select name="form_class_id"
                                        class="form-select @error('form_class_id') is-invalid @enderror">
                                    <option value="">— Select a class —</option>
                                    @foreach($classes->groupBy('level') as $level => $levelClasses)
                                    <optgroup label="{{ $level }}">
                                        @foreach($levelClasses as $class)
                                        <option value="{{ $class->id }}"
                                            {{ old('form_class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                </select>
                                @error('form_class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-secondary-light mt-6 d-block" style="font-size:11px;">
                                <i class="ri-information-line me-1"></i>
                                Each class can only have one form teacher at a time
                            </small>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Subject Assignments --}}
            <div class="card shadow-1 radius-8 mb-24">
                <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                    <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8"
                          style="background:#E3F2FD;">
                        <i class="ri-book-open-line" style="font-size:16px; color:#1565C0;"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color:#2A2567;">Class &amp; Subject Assignments</h6>
                        <p class="text-xs text-secondary-light mb-0">
                            Select the subjects this teacher teaches in each class. Leave a class blank to exclude it.
                        </p>
                    </div>
                </div>
                <div class="card-body p-24">

                    @php $grouped = $classes->groupBy('level'); @endphp

                    @foreach($grouped as $level => $levelClasses)
                    <div class="mb-20">
                        <div class="d-flex align-items-center gap-10 mb-12">
                            <span class="badge px-10 py-5 radius-4 fw-bold text-xs"
                                  style="background:{{ $level === 'JSS' ? '#EEF0FF' : '#FFF3E0' }};
                                         color:{{ $level === 'JSS' ? '#2A2567' : '#E65100' }}; letter-spacing:1px;">
                                {{ $level }}
                            </span>
                            <div class="flex-1" style="height:1px; background:#f0f0f0;"></div>
                            <button type="button"
                                    class="btn btn-xs btn-outline-secondary px-8 py-2 text-xs level-select-all"
                                    data-level="{{ $level }}" style="font-size:11px;">
                                Select All
                            </button>
                            <button type="button"
                                    class="btn btn-xs btn-outline-secondary px-8 py-2 text-xs level-clear-all"
                                    data-level="{{ $level }}" style="font-size:11px;">
                                Clear
                            </button>
                        </div>

                        <div class="border radius-8 overflow-hidden">
                            @foreach($levelClasses as $class)
                            @php $tickedSubjectIds = old("assignments.{$class->id}", []); @endphp
                            <div class="d-flex align-items-start gap-16 px-16 py-12
                                        {{ !$loop->last ? 'border-bottom' : '' }}
                                        class-row" data-level="{{ $level }}">
                                <div style="min-width:68px; padding-top:3px; flex-shrink:0;">
                                    <span class="badge bg-primary-100 text-primary-600 px-10 py-4 radius-4 fw-semibold text-xs">
                                        {{ $class->name }}
                                    </span>
                                </div>
                                @php
                                    $levelSubjects = $subjects->filter(fn($s) =>
                                        $s->level === 'Both' ||
                                        ($class->level === 'JSS' && $s->level === 'JSS') ||
                                        ($class->level === 'SSS' && $s->level === 'SSS')
                                    );
                                @endphp
                                <div class="chip-grid" style="flex:1;" data-class="{{ $class->id }}">
                                    @foreach($levelSubjects as $subject)
                                    <label class="chip-label"
                                           for="asgn_{{ $class->id }}_{{ $subject->id }}">
                                        <input type="checkbox"
                                               name="assignments[{{ $class->id }}][]"
                                               id="asgn_{{ $class->id }}_{{ $subject->id }}"
                                               value="{{ $subject->id }}"
                                               {{ in_array($subject->id, $tickedSubjectIds) ? 'checked' : '' }}>
                                        <span class="chip-text">
                                            <i class="ri-check-line chip-check-icon"></i>
                                            {{ $subject->name }}
                                        </span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                </div>

                <div class="card-footer px-24 py-16 d-flex align-items-center justify-content-between">
                    <p class="text-xs text-secondary-light mb-0">
                        <span class="text-danger">*</span> Required fields
                    </p>
                    <div class="d-flex gap-12">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-24">
                            <i class="ri-arrow-left-line me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-32">
                            <i class="ri-user-add-line me-1"></i> Create Account
                        </button>
                    </div>
                </div>
            </div>

            @endif
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// ── Photo preview ────────────────────────────────────────────
const photoInput   = document.getElementById('staffPhoto');
const photoPreview = document.getElementById('staffPhotoPreview');
if (photoInput) {
    photoInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            photoPreview.src = URL.createObjectURL(this.files[0]);
            photoPreview.style.display = '';
        }
    });
}

// ── Form teacher toggle ──────────────────────────────────────
const toggle     = document.getElementById('isFormTeacher');
const classField = document.getElementById('formClassField');

if (toggle && classField) {
    function syncFormClass() {
        const show = toggle.checked;
        classField.style.display = show ? '' : 'none';
        const sel = classField.querySelector('select');
        show ? sel.setAttribute('required', 'required') : sel.removeAttribute('required');
        if (!show) sel.value = '';
    }
    toggle.addEventListener('change', syncFormClass);
    syncFormClass();
}

// ── Password show / hide ─────────────────────────────────────
function bindToggle(btnId, iconId, inputId) {
    const btn   = document.getElementById(btnId);
    const icon  = document.getElementById(iconId);
    const input = document.getElementById(inputId);
    if (!btn) return;
    btn.addEventListener('click', () => {
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        icon.className = show ? 'ri-eye-line text-secondary-light' : 'ri-eye-off-line text-secondary-light';
    });
}
bindToggle('togglePassword', 'togglePasswordIcon', 'passwordInput');
bindToggle('toggleConfirm',  'toggleConfirmIcon',  'passwordConfirm');

// ── Password strength ────────────────────────────────────────
const pwInput      = document.getElementById('passwordInput');
const strengthWrap = document.getElementById('strengthWrap');
const strengthLbl  = document.getElementById('strengthLabel');
const bars         = [1,2,3,4].map(i => document.getElementById('sb' + i));
const barColors    = ['#ef4444','#f97316','#eab308','#22c55e'];
const barLabels    = ['Too weak','Weak','Fair','Strong'];

if (pwInput) {
    pwInput.addEventListener('input', function () {
        const v = this.value;
        if (!v) { strengthWrap.style.display = 'none'; return; }
        strengthWrap.style.display = '';
        let score = 0;
        if (v.length >= 6)  score++;
        if (v.length >= 10) score++;
        if (/[A-Z]/.test(v) && /[0-9]/.test(v)) score++;
        if (/[^A-Za-z0-9]/.test(v)) score++;
        bars.forEach((b, i) => b.style.background = i < score ? barColors[score - 1] : '#e0e0e0');
        strengthLbl.textContent = barLabels[score - 1] || '';
        strengthLbl.style.color = barColors[score - 1] || '#9e9e9e';
    });
}

// ── Password match hint ──────────────────────────────────────
const pwConfirm = document.getElementById('passwordConfirm');
const matchHint = document.getElementById('matchHint');

if (pwConfirm && pwInput) {
    function checkMatch() {
        if (!pwConfirm.value) { matchHint.style.display = 'none'; return; }
        const ok = pwConfirm.value === pwInput.value;
        matchHint.style.cssText = 'font-size:11px; display:block !important;';
        matchHint.textContent   = ok ? '✓ Passwords match' : '✗ Passwords do not match';
        matchHint.style.color   = ok ? '#22c55e' : '#ef4444';
    }
    pwConfirm.addEventListener('input', checkMatch);
    pwInput.addEventListener('input', checkMatch);
}

// ── Level select-all / clear ─────────────────────────────────
document.querySelectorAll('.level-select-all').forEach(btn => {
    btn.addEventListener('click', () => {
        const level = btn.dataset.level;
        document.querySelectorAll(`.class-row[data-level="${level}"] input[type="checkbox"]`)
                .forEach(cb => cb.checked = true);
    });
});
document.querySelectorAll('.level-clear-all').forEach(btn => {
    btn.addEventListener('click', () => {
        const level = btn.dataset.level;
        document.querySelectorAll(`.class-row[data-level="${level}"] input[type="checkbox"]`)
                .forEach(cb => cb.checked = false);
    });
});
</script>
@endpush
