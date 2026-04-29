@extends('layouts.portal')

@section('title', 'Edit Student')
@section('page-title', 'Edit Student')
@section('page-subtitle', $student->full_name)

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

        <form action="{{ route('admin.students.update', $student) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- ── Academic Enrollment ── --}}
            <div class="card shadow-1 radius-8 mb-20">
                <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                    <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8" style="background:#EEF0FF;">
                        <i class="ri-graduation-cap-line" style="font-size:16px; color:#2A2567;"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color:#2A2567;">Academic Enrollment</h6>
                        <p class="text-xs text-secondary-light mb-0">Class and admission number</p>
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
                                    <i class="ri-barcode-line text-secondary-light"></i>
                                </span>
                                <input type="text" name="admission_number"
                                       value="{{ old('admission_number', $student->admission_number) }}"
                                       class="form-control @error('admission_number') is-invalid @enderror"
                                       placeholder="e.g. DRIC/2024/0001" required>
                                @error('admission_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                                <select name="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
                                    <option value="">— Select Class —</option>
                                    @foreach($classes->groupBy('level') as $level => $levelClasses)
                                    <optgroup label="{{ $level }}">
                                        @foreach($levelClasses as $class)
                                        <option value="{{ $class->id }}"
                                            {{ old('class_id', $student->class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                </select>
                                @error('class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Personal Information ── --}}
            <div class="card shadow-1 radius-8 mb-20">
                <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                    <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8" style="background:#FFF3E0;">
                        <i class="ri-user-line" style="font-size:16px; color:#E65100;"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color:#2A2567;">Personal Information</h6>
                        <p class="text-xs text-secondary-light mb-0">Name, gender, date of birth, and photo</p>
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
                                    <i class="ri-user-line text-secondary-light"></i>
                                </span>
                                <input type="text" name="first_name"
                                       value="{{ old('first_name', $student->first_name) }}"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       placeholder="e.g. Chukwuemeka" required>
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Last Name <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-user-line text-secondary-light"></i>
                                </span>
                                <input type="text" name="last_name"
                                       value="{{ old('last_name', $student->last_name) }}"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       placeholder="e.g. Obi" required>
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Gender <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-gender-line text-secondary-light"></i>
                                </span>
                                <select name="gender" class="form-select" required>
                                    <option value="male"   {{ old('gender', $student->gender) === 'male'   ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $student->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Date of Birth <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-calendar-line text-secondary-light"></i>
                                </span>
                                <input type="date" name="date_of_birth"
                                       value="{{ old('date_of_birth', $student->date_of_birth?->format('Y-m-d')) }}"
                                       class="form-control @error('date_of_birth') is-invalid @enderror"
                                       min="{{ date('Y-m-d', strtotime('-25 years')) }}"
                                       max="{{ date('Y-m-d', strtotime('-3 years')) }}"
                                       required>
                                @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Replace Photo
                            </label>
                            <input type="file" name="photo" id="studentPhoto" accept="image/*" class="form-control">
                            @if($student->photo)
                            <img id="photoPreview" src="{{ asset('storage/' . $student->photo) }}"
                                 class="mt-8 rounded" style="height:64px;" alt="Current photo">
                            @else
                            <img id="photoPreview" src="" alt="" class="mt-8 rounded" style="height:64px; display:none;">
                            @endif
                            <small class="text-secondary-light mt-4 d-block" style="font-size:11px;">
                                Upload to replace current photo
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Guardian / Parent Contact ── --}}
            <div class="card shadow-1 radius-8 mb-20">
                <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                    <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8" style="background:#E8F5E9;">
                        <i class="ri-parent-line" style="font-size:16px; color:#2E7D32;"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color:#2A2567;">Guardian / Parent Contact</h6>
                        <p class="text-xs text-secondary-light mb-0">Emergency contact details</p>
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
                                    <i class="ri-account-circle-line text-secondary-light"></i>
                                </span>
                                <input type="text" name="guardian_name"
                                       value="{{ old('guardian_name', $student->guardian_name) }}"
                                       class="form-control @error('guardian_name') is-invalid @enderror"
                                       placeholder="Parent or guardian's full name" required>
                                @error('guardian_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Guardian Phone <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-phone-line text-secondary-light"></i>
                                </span>
                                <input type="tel" name="guardian_phone"
                                       value="{{ old('guardian_phone', $student->guardian_phone) }}"
                                       class="form-control @error('guardian_phone') is-invalid @enderror"
                                       placeholder="e.g. 08012345678" required>
                                @error('guardian_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Status ── --}}
            <div class="card shadow-1 radius-8 mb-24">
                <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                    <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8" style="background:#F3E5F5;">
                        <i class="ri-toggle-line" style="font-size:16px; color:#6A1B9A;"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color:#2A2567;">Enrollment Status</h6>
                        <p class="text-xs text-secondary-light mb-0">Active students appear on class lists and reports</p>
                    </div>
                </div>
                <div class="card-body p-24">
                    <div class="row gy-20">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Status</label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-checkbox-circle-line text-secondary-light"></i>
                                </span>
                                <select name="status" class="form-select">
                                    <option value="active"   {{ old('status', $student->status) === 'active'   ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $student->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer px-24 py-16 d-flex align-items-center gap-12">
                    <button type="submit" class="btn btn-primary px-32">
                        <i class="ri-save-line me-1"></i> Save Changes
                    </button>
                    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-outline-secondary px-24">Cancel</a>
                </div>
            </div>

        </form>
    </div>

    {{-- ── Promote ── --}}
    <div class="col-lg-4">
        <div class="card shadow-1 radius-8">
            <div class="card-header py-16 px-24 border-bottom d-flex align-items-center gap-10">
                <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8" style="background:#FFF8E1;">
                    <i class="ri-arrow-up-circle-line" style="font-size:16px; color:#F57F17;"></i>
                </span>
                <h6 class="fw-semibold mb-0" style="color:#2A2567;">Promote to New Class</h6>
            </div>
            <div class="card-body p-24">
                <p class="text-sm text-secondary-light mb-16">
                    Current class: <strong style="color:#2A2567;">{{ $student->schoolClass->name ?? '—' }}</strong>
                </p>
                <form action="{{ route('admin.students.promote', $student) }}" method="POST">
                    @csrf
                    <div class="mb-16">
                        <label class="form-label fw-semibold text-sm" style="color:#2A2567;">New Class <span class="text-danger">*</span></label>
                        <select name="new_class_id" class="form-select" required>
                            <option value="">— Select —</option>
                            @foreach($classes->groupBy('level') as $level => $levelClasses)
                            <optgroup label="{{ $level }}">
                                @foreach($levelClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning w-100"
                            onclick="return confirm('Promote {{ $student->full_name }} to the selected class?')">
                        <i class="ri-arrow-up-line me-1"></i> Promote Student
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const photoInput   = document.getElementById('studentPhoto');
const photoPreview = document.getElementById('photoPreview');
if (photoInput) {
    photoInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            photoPreview.src = URL.createObjectURL(this.files[0]);
            photoPreview.style.display = '';
        }
    });
}
</script>
@endpush
