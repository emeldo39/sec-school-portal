@extends('layouts.portal')

@section('title', 'School Settings')
@section('page-title', 'School Settings')
@section('page-subtitle', 'Configure school information and grading scales')

@section('content')

<div class="row gy-24">

    {{-- School Information --}}
    <div class="col-lg-6">
        <div class="card shadow-1 radius-8 h-100">
            <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8" style="background:#EEF0FF;">
                    <i class="ri-building-line" style="font-size:16px; color:#2A2567;"></i>
                </span>
                <div>
                    <h6 class="fw-semibold mb-0" style="color:#2A2567;">School Information</h6>
                    <p class="text-xs text-secondary-light mb-0">Name, contact details, and branding</p>
                </div>
            </div>
            <div class="card-body p-24">
                <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row gy-20">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                School Name <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-building-line text-secondary-light"></i>
                                </span>
                                <input type="text" name="school_name"
                                    value="{{ old('school_name', \App\Models\SchoolSetting::get('school_name')) }}"
                                    class="form-control @error('school_name') is-invalid @enderror" required>
                                @error('school_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">School Motto</label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-double-quotes-l text-secondary-light"></i>
                                </span>
                                <input type="text" name="school_motto"
                                    value="{{ old('school_motto', \App\Models\SchoolSetting::get('school_motto')) }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Phone</label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-phone-line text-secondary-light"></i>
                                </span>
                                <input type="text" name="school_phone"
                                    value="{{ old('school_phone', \App\Models\SchoolSetting::get('school_phone')) }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-mail-line text-secondary-light"></i>
                                </span>
                                <input type="email" name="school_email"
                                    value="{{ old('school_email', \App\Models\SchoolSetting::get('school_email')) }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-map-pin-line text-secondary-light"></i>
                                </span>
                                <input type="text" name="school_address"
                                    value="{{ old('school_address', \App\Models\SchoolSetting::get('school_address')) }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Principal's Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-user-star-line text-secondary-light"></i>
                                </span>
                                <input type="text" name="principal_name"
                                    value="{{ old('principal_name', \App\Models\SchoolSetting::get('principal_name')) }}"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">About Text (Public Website)</label>
                            <textarea name="about_text" rows="3" class="form-control">{{ old('about_text', \App\Models\SchoolSetting::get('about_text')) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Result Sheet Footer Note</label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-file-text-line text-secondary-light"></i>
                                </span>
                                <input type="text" name="result_sheet_footer"
                                    value="{{ old('result_sheet_footer', \App\Models\SchoolSetting::get('result_sheet_footer')) }}"
                                    class="form-control">
                            </div>
                        </div>

                        {{-- Logo Upload --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">School Logo</label>
                            <div class="d-flex align-items-start gap-16 p-16 radius-8 border" style="background:#FAFAFA;">
                                <div class="flex-shrink-0">
                                    @if(\App\Models\SchoolSetting::get('school_logo'))
                                    <img id="logoPreview"
                                         src="{{ asset('storage/' . \App\Models\SchoolSetting::get('school_logo')) }}"
                                         style="height:72px; width:72px; object-fit:contain; border-radius:8px; border:1px solid #e5e5e5;"
                                         alt="Current logo">
                                    @else
                                    <div id="logoPlaceholder"
                                         class="d-flex align-items-center justify-content-center radius-8"
                                         style="height:72px; width:72px; background:#EEF0FF; border:1px dashed #a0a0c8;">
                                        <i class="ri-image-line text-primary-400 text-2xl"></i>
                                    </div>
                                    <img id="logoPreview" src="" alt="" style="height:72px; display:none; border-radius:8px;">
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" name="school_logo" id="logoInput" accept="image/*" class="form-control form-control-sm mb-8">
                                    <small class="text-secondary-light" style="font-size:11px;">
                                        <i class="ri-information-line me-1"></i>
                                        PNG or JPG recommended &mdash; appears on result sheets and portal header
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-24 pt-16 border-top">
                        <button type="submit" class="btn btn-primary px-32">
                            <i class="ri-save-line me-1"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Grading Scales --}}
    <div class="col-lg-3">
        @include('admin.settings._grading_panel', [
            'panelTitle' => 'Junior Key To Grades',
            'panelLevel' => 'JSS',
            'panelBadge' => 'bg-success-100 text-success-600',
            'scales'     => $jssGradingScales,
            'addModalId' => 'addJssGradeModal',
        ])
    </div>

    <div class="col-lg-3">
        @include('admin.settings._grading_panel', [
            'panelTitle' => 'Senior Key To Grades',
            'panelLevel' => 'SSS',
            'panelBadge' => 'bg-primary-100 text-primary-600',
            'scales'     => $sssGradingScales,
            'addModalId' => 'addSssGradeModal',
        ])
    </div>
</div>

{{-- Grade Modals --}}
@include('admin.settings._add_grade_modal', ['modalId' => 'addJssGradeModal', 'level' => 'JSS', 'modalTitle' => 'Add Junior Grade'])
@include('admin.settings._add_grade_modal', ['modalId' => 'addSssGradeModal', 'level' => 'SSS', 'modalTitle' => 'Add Senior Grade'])

@endsection

@push('scripts')
<script>
const logoInput   = document.getElementById('logoInput');
const logoPreview = document.getElementById('logoPreview');
if (logoInput && logoPreview) {
    logoInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            logoPreview.src = URL.createObjectURL(this.files[0]);
            logoPreview.style.display = '';
            const placeholder = document.getElementById('logoPlaceholder');
            if (placeholder) placeholder.style.display = 'none';
        }
    });
}
</script>
@endpush
