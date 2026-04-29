@extends('layouts.portal')

@section('title', 'Edit Staff Account')
@section('page-title', 'Edit Staff Account')
@section('page-subtitle', $user->name)

@section('content')
<div class="row gy-20 justify-content-center">

    {{-- ── Left: Edit Form ── --}}
    <div class="col-lg-8">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')

            {{-- Personal Info --}}
            <div class="card shadow-1 radius-8 mb-20">
                <div class="card-header py-16 px-24 border-bottom">
                    <h6 class="fw-semibold mb-0">Personal Information</h6>
                </div>
                <div class="card-body p-24">
                    <div class="row gy-16">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="form-control @error('phone') is-invalid @enderror">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Profile Photo</label>
                            <input type="file" name="photo" id="editStaffPhoto" accept="image/*" class="form-control">
                            @if($user->photo)
                            <img id="editPhotoPreview" src="{{ asset('storage/' . $user->photo) }}"
                                 class="mt-8 rounded" style="height:60px;" alt="Current photo">
                            @else
                            <img id="editPhotoPreview" src="" alt="" class="mt-8 rounded" style="height:60px; display:none;">
                            @endif
                            <small class="text-secondary-light mt-4 d-block" style="font-size:11px;">Upload to replace current photo</small>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Signature Image</label>
                            <input type="file" name="signature" accept="image/*" class="form-control">
                            @if($user->signature)
                            <img src="{{ asset('storage/' . $user->signature) }}"
                                 class="mt-8 rounded border" style="height:48px; background:#f5f5f5;" alt="Current signature">
                            @endif
                            <small class="text-secondary-light mt-4 d-block" style="font-size:11px;">Upload to replace — background auto-removed</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Class Assignment --}}
            <div class="card shadow-1 radius-8">
                <div class="card-header py-16 px-24 border-bottom">
                    <h6 class="fw-semibold mb-0">Class Assignment</h6>
                </div>
                <div class="card-body p-24">
                    <div class="row gy-20">

                        {{-- Form Teacher Toggle --}}
                        <div class="col-12">
                            <p class="form-section-title mb-16">Form Teacher</p>
                            <label class="form-teacher-toggle w-100" for="isFormTeacher">
                                <input type="checkbox" name="is_form_teacher" id="isFormTeacher"
                                       value="1"
                                       {{ old('is_form_teacher', $user->is_form_teacher) ? 'checked' : '' }}
                                       style="position:absolute;opacity:0;width:0;height:0;">
                                <span class="toggle-icon">
                                    <i class="ri-user-star-line"></i>
                                </span>
                                <div>
                                    <p class="fw-semibold text-sm mb-1" style="color:#2A2567;">Assign as Form Teacher</p>
                                    <p class="text-xs mb-0" style="color:#7B79A0;">Form teachers can generate and download result sheets for their class.</p>
                                </div>
                            </label>
                        </div>

                        {{-- Form Class (visible only when Form Teacher is checked) --}}
                        <div class="col-sm-6" id="formClassField"
                             style="{{ old('is_form_teacher', $user->is_form_teacher) ? '' : 'display:none;' }}">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Form Class <span class="text-danger">*</span></label>
                            <p class="text-xs mb-8" style="color:#7B79A0;">Select the class this teacher is responsible for.</p>
                            <select name="form_class_id" class="form-select">
                                <option value="">— Select a class —</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ old('form_class_id', $user->form_class_id) == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }} ({{ $class->level }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Per-Class Subject Assignment --}}
                        <div class="col-12">
                            <p class="form-section-title mb-4">Class &amp; Subject Assignments</p>
                            <p class="text-xs mb-16" style="color:#7B79A0;">
                                For each class this teacher teaches, tick the subjects they handle in that class.
                                Leave a class row blank to exclude it entirely.
                            </p>

                            @php
                                $oldAssignments = old('assignments', $existingAssignments->toArray());
                                $grouped = $classes->groupBy('level');
                            @endphp

                            @foreach($grouped as $level => $levelClasses)
                            <p class="text-xs fw-bold text-uppercase mb-8" style="color:#2A2567; margin-top:12px;">
                                {{ $level }} Classes
                            </p>
                            <div class="border radius-8 overflow-hidden mb-12">
                                @foreach($levelClasses as $class)
                                @php
                                    $tickedSubjectIds = $oldAssignments[$class->id] ?? [];
                                @endphp
                                <div class="d-flex align-items-start gap-16 px-16 py-12
                                            {{ !$loop->last ? 'border-bottom' : '' }}">
                                    {{-- Class label --}}
                                    <div style="min-width:72px; padding-top:2px;">
                                        <span class="badge bg-primary-100 text-primary-600 px-10 py-4 radius-4 fw-semibold text-xs">
                                            {{ $class->name }}
                                        </span>
                                    </div>
                                    {{-- Subjects for this class (filtered by level) --}}
                                    @php
                                        $classLevel = $class->level; // 'JSS' or 'SSS'
                                        $levelSubjects = $subjects->filter(fn($s) =>
                                            $s->level === 'Both' ||
                                            ($classLevel === 'JSS' && $s->level === 'JSS') ||
                                            ($classLevel === 'SSS' && $s->level === 'SSS')
                                        );
                                    @endphp
                                    <div class="chip-grid" style="flex:1;">
                                        @foreach($levelSubjects as $subject)
                                        <label class="chip-label" for="asgn_{{ $class->id }}_{{ $subject->id }}">
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
                            @endforeach
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
    </div>

    {{-- ── Right: Reset Password ── --}}
    <div class="col-lg-4">
        <div class="card shadow-1 radius-8">
            <div class="card-header py-16 px-24 border-bottom">
                <h6 class="fw-semibold mb-0">Reset Password</h6>
            </div>
            <div class="card-body p-24">
                <p class="text-xs mb-16" style="color:#7B79A0;">
                    Set a new password for <strong>{{ $user->name }}</strong>. The teacher will need to use this to log in.
                </p>
                <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
                    @csrf
                    <div class="mb-16">
                        <label class="form-label fw-semibold text-sm" style="color:#2A2567;">New Password</label>
                        <input type="password" name="password" class="form-control"
                               placeholder="Min. 6 characters" required minlength="6">
                    </div>
                    <div class="mb-20">
                        <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="Repeat password" required>
                    </div>
                    <button type="submit" class="btn btn-warning w-100"
                            onclick="return confirm('Reset password for {{ $user->name }}?')">
                        <i class="ri-lock-password-line me-1"></i> Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Photo preview
    const editPhotoInput   = document.getElementById('editStaffPhoto');
    const editPhotoPreview = document.getElementById('editPhotoPreview');
    if (editPhotoInput) {
        editPhotoInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                editPhotoPreview.src = URL.createObjectURL(this.files[0]);
                editPhotoPreview.style.display = '';
            }
        });
    }

    const toggle     = document.getElementById('isFormTeacher');
    const classField = document.getElementById('formClassField');

    function syncFormClass() {
        if (toggle.checked) {
            classField.style.display = '';
        } else {
            classField.style.display = 'none';
            classField.querySelector('select').value = '';
        }
    }

    toggle.addEventListener('change', syncFormClass);
    syncFormClass();
</script>
@endpush
