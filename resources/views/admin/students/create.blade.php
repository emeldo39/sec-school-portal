@extends('layouts.portal')

@section('title', 'Add Student')
@section('page-title', 'Add Student')
@section('page-subtitle', 'Enrol a new student into the school portal')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">

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

        <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- ── Section 1: Academic Enrollment ── --}}
            <div class="card shadow-1 radius-8 mb-20">
                <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                    <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8"
                          style="background:#EEF0FF;">
                        <i class="ri-school-line text-primary-600" style="font-size:16px;"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color:#2A2567;">Academic Enrollment</h6>
                        <p class="text-xs text-secondary-light mb-0">Assign a unique admission number and class</p>
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="row gy-20">

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Admission Number <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-id-card-line text-secondary-light"></i>
                                </span>
                                <input type="text"
                                       name="admission_number"
                                       id="admission_number"
                                       value="{{ old('admission_number') }}"
                                       class="form-control @error('admission_number') is-invalid @enderror"
                                       placeholder="e.g. DRIC/{{ date('Y') }}/0001"
                                       maxlength="30"
                                       autocomplete="off"
                                       required>
                                @error('admission_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex align-items-center justify-content-between mt-6">
                                <small class="text-secondary-light" style="font-size:11px;">
                                    <i class="ri-information-line me-1"></i>
                                    Format: <code style="font-size:11px;">PREFIX/YEAR/SEQUENCE</code>
                                </small>
                                @if($lastAdmission)
                                <small class="text-secondary-light" style="font-size:11px;">
                                    Last: <span class="fw-semibold" style="color:#2A2567;">{{ $lastAdmission }}</span>
                                </small>
                                @endif
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Class <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-building-line text-secondary-light"></i>
                                </span>
                                <select name="class_id"
                                        class="form-select @error('class_id') is-invalid @enderror"
                                        required>
                                    <option value="">— Select Class —</option>
                                    @foreach($classes->groupBy('level') as $level => $levelClasses)
                                    <optgroup label="{{ $level }}">
                                        @foreach($levelClasses as $class)
                                        <option value="{{ $class->id }}"
                                            {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                </select>
                                @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-secondary-light mt-6 d-block" style="font-size:11px;">
                                <i class="ri-information-line me-1"></i>Classes are grouped by level (Junior / Senior)
                            </small>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Section 2: Personal Information ── --}}
            <div class="card shadow-1 radius-8 mb-20">
                <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                    <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8"
                          style="background:#FFF3E0;">
                        <i class="ri-user-line" style="font-size:16px; color:#E65100;"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color:#2A2567;">Personal Information</h6>
                        <p class="text-xs text-secondary-light mb-0">Student's legal name, date of birth and gender</p>
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="row gy-20">

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                First Name <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-text text-secondary-light"></i>
                                </span>
                                <input type="text"
                                       name="first_name"
                                       value="{{ old('first_name') }}"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       placeholder="e.g. Chinyere"
                                       maxlength="60"
                                       required>
                                @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-secondary-light mt-6 d-block" style="font-size:11px;">
                                As it appears on the birth certificate
                            </small>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Last Name <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-text text-secondary-light"></i>
                                </span>
                                <input type="text"
                                       name="last_name"
                                       value="{{ old('last_name') }}"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       placeholder="e.g. Okafor"
                                       maxlength="60"
                                       required>
                                @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-secondary-light mt-6 d-block" style="font-size:11px;">
                                Family / surname
                            </small>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Date of Birth <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-calendar-line text-secondary-light"></i>
                                </span>
                                <input type="date"
                                       name="date_of_birth"
                                       value="{{ old('date_of_birth') }}"
                                       class="form-control @error('date_of_birth') is-invalid @enderror"
                                       max="{{ date('Y-m-d', strtotime('-3 years')) }}"
                                       min="{{ date('Y-m-d', strtotime('-25 years')) }}"
                                       required>
                                @error('date_of_birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-secondary-light mt-6 d-block" style="font-size:11px;">
                                <i class="ri-information-line me-1"></i>Age range accepted: 3 – 25 years
                            </small>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Gender <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-men-line text-secondary-light"></i>
                                </span>
                                <select name="gender"
                                        class="form-select @error('gender') is-invalid @enderror"
                                        required>
                                    <option value="">— Select Gender —</option>
                                    <option value="male"   {{ old('gender') === 'male'   ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Section 3: Guardian / Parent Contact ── --}}
            <div class="card shadow-1 radius-8 mb-20">
                <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                    <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8"
                          style="background:#E8F5E9;">
                        <i class="ri-parent-line" style="font-size:16px; color:#2E7D32;"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color:#2A2567;">Guardian / Parent Contact</h6>
                        <p class="text-xs text-secondary-light mb-0">Primary contact for school communication</p>
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="row gy-20">

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Guardian Name <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-user-heart-line text-secondary-light"></i>
                                </span>
                                <input type="text"
                                       name="guardian_name"
                                       value="{{ old('guardian_name') }}"
                                       class="form-control @error('guardian_name') is-invalid @enderror"
                                       placeholder="e.g. Mrs. Ngozi Okafor"
                                       maxlength="100"
                                       required>
                                @error('guardian_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-secondary-light mt-6 d-block" style="font-size:11px;">
                                Full name of parent or legal guardian
                            </small>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Guardian Phone <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-phone-line text-secondary-light"></i>
                                </span>
                                <input type="tel"
                                       name="guardian_phone"
                                       value="{{ old('guardian_phone') }}"
                                       class="form-control @error('guardian_phone') is-invalid @enderror"
                                       placeholder="e.g. 08012345678"
                                       maxlength="20"
                                       required>
                                @error('guardian_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-secondary-light mt-6 d-block" style="font-size:11px;">
                                <i class="ri-information-line me-1"></i>Nigerian format: 080XXXXXXXX or +234XXXXXXXXXX
                            </small>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Section 4: Additional ── --}}
            <div class="card shadow-1 radius-8 mb-24">
                <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                    <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8"
                          style="background:#F3E5F5;">
                        <i class="ri-settings-3-line" style="font-size:16px; color:#6A1B9A;"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color:#2A2567;">Additional Details</h6>
                        <p class="text-xs text-secondary-light mb-0">Enrolment status and passport photograph</p>
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="row gy-20 align-items-start">

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Enrolment Status <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-toggle-line text-secondary-light"></i>
                                </span>
                                <select name="status" class="form-select" required>
                                    <option value="active"   {{ old('status', 'active') === 'active'   ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <small class="text-secondary-light mt-6 d-block" style="font-size:11px;">
                                Inactive students are hidden from result sheets and attendance
                            </small>
                        </div>

                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Passport Photo <span class="text-secondary-light fw-normal">(optional)</span>
                            </label>

                            {{-- Photo preview --}}
                            <div id="photoPreviewWrap"
                                 class="mb-10 d-none text-center">
                                <img id="photoPreview"
                                     src=""
                                     alt="Preview"
                                     class="rounded-circle shadow-sm"
                                     style="width:72px;height:72px;object-fit:cover;border:2px solid #2A2567;">
                            </div>

                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-image-line text-secondary-light"></i>
                                </span>
                                <input type="file"
                                       name="photo"
                                       id="photoInput"
                                       accept="image/jpeg,image/png,image/webp"
                                       class="form-control @error('photo') is-invalid @enderror">
                                @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-secondary-light mt-6 d-block" style="font-size:11px;">
                                <i class="ri-information-line me-1"></i>JPEG, PNG or WebP · Max 2 MB · Passport-style recommended
                            </small>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Actions ── --}}
            <div class="d-flex align-items-center justify-content-between gap-12">
                <p class="text-xs text-secondary-light mb-0">
                    <span class="text-danger">*</span> Required fields
                </p>
                <div class="d-flex gap-12">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary px-28">
                        <i class="ri-arrow-left-line me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary px-32">
                        <i class="ri-user-add-line me-1"></i> Add Student
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('photoInput').addEventListener('change', function () {
    const file = this.files[0];
    const wrap  = document.getElementById('photoPreviewWrap');
    const img   = document.getElementById('photoPreview');
    if (file) {
        img.src = URL.createObjectURL(file);
        wrap.classList.remove('d-none');
    } else {
        wrap.classList.add('d-none');
        img.src = '';
    }
});
</script>
@endpush
