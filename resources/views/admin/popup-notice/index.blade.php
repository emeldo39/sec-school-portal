@extends('layouts.portal')

@section('title', 'Popup Notice')
@section('page-title', 'Popup Notice')
@section('page-subtitle', 'Manage the popup modal shown to visitors on the public website')

@section('content')

<div class="row gy-24">

    {{-- ── Left: Form ──────────────────────────────────────────────────── --}}
    <div class="col-xl-7 col-lg-8">
        <div class="card shadow-1 radius-8">

            {{-- Card header --}}
            <div class="card-header py-14 px-24 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-10">
                    <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8"
                        style="background:#EEF0FF;">
                        <i class="ri-notification-2-line" style="font-size:16px;color:#2A2567;"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color:#2A2567;">Popup Notice Settings</h6>
                        <p class="text-xs text-secondary-light mb-0">Configure the modal shown to website visitors</p>
                    </div>
                </div>
                @if($popup && $popup->is_active)
                <span class="badge px-10 py-6 radius-6 fw-semibold"
                    style="background:#dcfce7;color:#16a34a;font-size:.72rem;letter-spacing:.3px;">
                    <i class="ri-checkbox-circle-line me-1"></i> LIVE
                </span>
                @else
                <span class="badge px-10 py-6 radius-6 fw-semibold"
                    style="background:#f1f5f9;color:#64748b;font-size:.72rem;letter-spacing:.3px;">
                    <i class="ri-eye-off-line me-1"></i> INACTIVE
                </span>
                @endif
            </div>

            <div class="card-body p-24">
                <form method="POST" action="{{ route('admin.popup-notice.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- ── Section: Content ──────────────────────────────── --}}
                    <p class="text-xs fw-semibold text-secondary-light text-uppercase mb-12"
                        style="letter-spacing:.6px;">Content</p>

                    {{-- Notice Title --}}
                    <div class="mb-20">
                        <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                            Notice Title
                            <span class="text-secondary-light fw-normal ms-1">(optional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-neutral-50">
                                <i class="ri-text text-secondary-light"></i>
                            </span>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                placeholder="e.g. Admissions Open 2026/2027" value="{{ old('title', $popup?->title) }}">
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <p class="text-xs text-secondary-light mt-6 mb-0">Shown as a heading above the image inside the
                            popup.</p>
                    </div>

                    {{-- ── Section: Flyer Image ───────────────────────────── --}}
                    <div class="mb-20">
                        <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Flyer / Image</label>

                        @if($popup?->image)
                        <div class="d-flex align-items-center gap-16 p-16 radius-8 mb-12"
                            style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <img src="{{ asset('storage/' . $popup->image) }}" alt="Current flyer"
                                style="height:72px;width:auto;border-radius:6px;object-fit:cover;border:1px solid #e2e8f0;">
                            <div class="flex-grow-1">
                                <p class="fw-semibold text-sm mb-2" style="color:#1e293b;">Current flyer</p>
                                <p class="text-xs text-secondary-light mb-8">Upload a new image below to replace it.</p>
                                <button type="button" class="btn btn-outline-danger btn-sm px-12 py-6"
                                    style="font-size:.75rem;"
                                    onclick="if(confirm('Remove this image?')) document.getElementById('removeImageForm').submit()">
                                    <i class="ri-delete-bin-line me-1"></i> Remove Image
                                </button>
                            </div>
                        </div>
                        @endif

                        <label for="popupImageInput"
                            class="d-flex flex-column align-items-center justify-content-center p-24 radius-8 w-100"
                            style="border:2px dashed #cbd5e1;cursor:pointer;background:#f8fafc;transition:border-color .2s;"
                            onmouseover="this.style.borderColor='#2A2567'"
                            onmouseout="this.style.borderColor='#cbd5e1'">
                            <div id="uploadPlaceholder">
                                <i class="ri-image-add-line"
                                    style="font-size:2rem;color:#94a3b8;display:block;text-align:center;"></i>
                                <p class="text-sm fw-semibold mt-8 mb-2" style="color:#475569;text-align:center;">
                                    Click to upload a flyer image
                                </p>
                                <p class="text-xs text-secondary-light mb-0 text-center">
                                    Portrait format recommended (e.g. 600×850px) · PNG, JPG · Max 4 MB
                                </p>
                            </div>
                            <div id="popupImgPreview" style="display:none;text-align:center;">
                                <img id="popupPreviewImg" src="" alt="Preview"
                                    style="max-height:180px;border-radius:8px;border:1px solid #e2e8f0;">
                                <p class="text-xs text-secondary-light mt-8 mb-0">New image selected</p>
                            </div>
                        </label>
                        <input type="file" name="image" id="popupImageInput"
                            class="@error('image') is-invalid @enderror" accept="image/*"
                            onchange="previewPopupImage(this)" style="display:none;">
                        @error('image')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <hr class="my-20" style="border-color:#f1f5f9;">

                    {{-- ── Section: Call-to-Action Button ────────────────── --}}
                    <p class="text-xs fw-semibold text-secondary-light text-uppercase mb-12"
                        style="letter-spacing:.6px;">Call-to-Action Button <span
                            class="fw-normal normal-case">(optional)</span></p>

                    <div class="row g-16 mb-20">
                        <div class="col-sm-8">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Button Link</label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-links-line text-secondary-light"></i>
                                </span>
                                <input type="url" name="link_url"
                                    class="form-control @error('link_url') is-invalid @enderror"
                                    placeholder="https://yoursite.com/admissions"
                                    value="{{ old('link_url', $popup?->link_url) }}">
                                @error('link_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Button Text</label>
                            <div class="input-group">
                                <span class="input-group-text bg-neutral-50">
                                    <i class="ri-cursor-line text-secondary-light"></i>
                                </span>
                                <input type="text" name="link_text"
                                    class="form-control @error('link_text') is-invalid @enderror"
                                    placeholder="Apply Now" value="{{ old('link_text', $popup?->link_text) }}">
                                @error('link_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <hr class="my-20" style="border-color:#f1f5f9;">

                    {{-- ── Section: Visibility ────────────────────────────── --}}
                    <p class="text-xs fw-semibold text-secondary-light text-uppercase mb-12"
                        style="letter-spacing:.6px;">Visibility</p>

                    <div class="radius-8 overflow-hidden mb-24" style="border:1px solid #e2e8f0;">
                        {{-- Toggle: Show Popup --}}
                        <div class="d-flex align-items-center justify-content-between px-20 py-16"
                            style="background:#fff;">
                            <div class="d-flex align-items-center gap-12">
                                <span class="w-36 h-36 d-flex align-items-center justify-content-center radius-8"
                                    style="background:#EEF0FF;flex-shrink:0;">
                                    <i class="ri-eye-line" style="color:#2A2567;font-size:16px;"></i>
                                </span>
                                <div>
                                    <p class="fw-semibold text-sm mb-1" style="color:#1e293b;">Show Popup on Website</p>
                                    <p class="text-xs text-secondary-light mb-0">When off, popup is hidden for all
                                        visitors instantly.</p>
                                </div>
                            </div>
                            <div class="form-check form-switch mb-0 ms-12">
                                <input class="form-check-input" type="checkbox" role="switch" id="isActiveToggle"
                                    name="is_active" value="1"
                                    {{ old('is_active', $popup?->is_active) ? 'checked' : '' }}
                                    style="width:2.6rem;height:1.35rem;cursor:pointer;">
                            </div>
                        </div>

                        <div style="height:1px;background:#f1f5f9;"></div>

                        {{-- Toggle: Show Once --}}
                        <div class="d-flex align-items-center justify-content-between px-20 py-16"
                            style="background:#fff;">
                            <div class="d-flex align-items-center gap-12">
                                <span class="w-36 h-36 d-flex align-items-center justify-content-center radius-8"
                                    style="background:#FFF7ED;flex-shrink:0;">
                                    <i class="ri-timer-line" style="color:#ea580c;font-size:16px;"></i>
                                </span>
                                <div>
                                    <p class="fw-semibold text-sm mb-1" style="color:#1e293b;">Show Once Per Session</p>
                                    <p class="text-xs text-secondary-light mb-0">Won't reappear after visitor dismisses
                                        it until they reopen their browser.</p>
                                </div>
                            </div>
                            <div class="form-check form-switch mb-0 ms-12">
                                <input class="form-check-input" type="checkbox" role="switch" id="showOnceToggle"
                                    name="show_once" value="1"
                                    {{ old('show_once', $popup?->show_once ?? true) ? 'checked' : '' }}
                                    style="width:2.6rem;height:1.35rem;cursor:pointer;">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-12">
                        <button type="submit" class="btn btn-primary px-24 py-10 fw-semibold">
                            <i class="ri-save-line me-1"></i> Save Changes
                        </button>
                        @if($popup && $popup->is_active)
                        <span class="text-xs text-secondary-light">
                            <i class="ri-checkbox-circle-line text-success me-1"></i>Popup is live on the website
                        </span>
                        @else
                        <span class="text-xs text-secondary-light">
                            <i class="ri-information-line me-1"></i>Toggle "Show Popup" to publish
                        </span>
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>

    {{-- ── Right: Preview + Guide ───────────────────────────────────────── --}}
    <div class="col-xl-5 col-lg-4">

        {{-- Preview card --}}
        <div class="card shadow-1 radius-8 mb-24">
            <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8"
                    style="background:#F0FDF4;">
                    <i class="ri-eye-line" style="font-size:16px;color:#16a34a;"></i>
                </span>
                <div>
                    <h6 class="fw-semibold mb-0" style="color:#2A2567;">Live Preview</h6>
                    <p class="text-xs text-secondary-light mb-0">How the popup looks to visitors</p>
                </div>
            </div>

            {{-- Simulated browser viewport --}}
            <div class="p-20" style="background:#f1f5f9;">
                {{-- Browser chrome bar --}}
                <div class="radius-8 overflow-hidden" style="box-shadow:0 4px 24px rgba(0,0,0,.12);">
                    <div class="d-flex align-items-center gap-6 px-12 py-8" style="background:#e2e8f0;">
                        <span style="width:10px;height:10px;border-radius:50%;background:#fc5c5c;"></span>
                        <span style="width:10px;height:10px;border-radius:50%;background:#fdbc40;"></span>
                        <span style="width:10px;height:10px;border-radius:50%;background:#34c84a;"></span>
                        <span class="ms-auto text-xs text-secondary-light"
                            style="font-size:.65rem;">yourschool.com</span>
                    </div>

                    {{-- Page content + popup overlay --}}
                    <div
                        style="background:#dde1e7;min-height:240px;position:relative;display:flex;align-items:center;justify-content:center;">
                        {{-- Blurred page behind --}}
                        <div style="position:absolute;inset:0;background:rgba(0,0,0,.5);z-index:1;"></div>

                        @if($popup && ($popup->image || $popup->title))
                        <div
                            style="position:relative;z-index:2;background:#fff;border-radius:10px;overflow:hidden;width:200px;box-shadow:0 8px 30px rgba(0,0,0,.25);">
                            <button
                                style="position:absolute;top:6px;right:6px;background:rgba(0,0,0,.5);color:#fff;border:none;border-radius:50%; padding:8px; font-size:11px;cursor:default;z-index:3;line-height:1;">✕</button>
                            @if($popup->title)
                            <div style="padding:10px 12px 4px;text-align:center;">
                                <strong
                                    style="font-size:.72rem;color:#1a1a2e;">{{ Str::limit($popup->title, 30) }}</strong>
                            </div>
                            @endif
                            @if($popup->image)
                            <img src="{{ asset('storage/' . $popup->image) }}" style="width:100%;display:block;" alt="">
                            @else
                            <div
                                style="height:100px;background:#EEF0FF;display:flex;align-items:center;justify-content:center;">
                                <i class="ri-image-line" style="font-size:1.8rem;color:#a5b4fc;"></i>
                            </div>
                            @endif
                            @if($popup->link_url)
                            <div style="padding:8px 12px;text-align:center;background:#f8f9fa;">
                                <span
                                    style="display:inline-block;background:#2A2567;color:#fff;padding:4px 12px;border-radius:4px;font-size:.65rem;font-weight:600;">
                                    {{ Str::limit($popup->link_text ?: 'Learn More', 16) }}
                                </span>
                            </div>
                            @endif
                        </div>
                        @else
                        <div style="position:relative;z-index:2;text-align:center;color:rgba(255,255,255,.5);">
                            <i class="ri-notification-off-line" style="font-size:2rem;"></i>
                            <p style="font-size:.75rem;margin-top:6px;margin-bottom:0;">No popup configured</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- How it works --}}
        <div class="card shadow-1 radius-8">
            <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8"
                    style="background:#FFF7ED;">
                    <i class="ri-lightbulb-line" style="font-size:16px;color:#ea580c;"></i>
                </span>
                <div>
                    <h6 class="fw-semibold mb-0" style="color:#2A2567;">How It Works</h6>
                    <p class="text-xs text-secondary-light mb-0">Quick guide</p>
                </div>
            </div>
            <div class="card-body p-20">
                <div class="d-flex flex-column gap-16">
                    @foreach([
                    ['icon'=>'ri-image-add-line','color'=>'#EEF0FF','icolor'=>'#2A2567','title'=>'Upload a
                    Flyer','desc'=>'Upload a portrait-format image (your admissions flyer, announcement, etc.)'],
                    ['icon'=>'ri-cursor-line','color'=>'#F0FDF4','icolor'=>'#16a34a','title'=>'Add a Button
                    (optional)','desc'=>'Link the popup to your admissions or any page with a custom label.'],
                    ['icon'=>'ri-toggle-line','color'=>'#FFF7ED','icolor'=>'#ea580c','title'=>'Toggle to
                    Publish','desc'=>'Flip "Show Popup" on. Turn it off at any time without losing your content.'],
                    ['icon'=>'ri-timer-line','color'=>'#FDF4FF','icolor'=>'#9333ea','title'=>'Show Once Per
                    Session','desc'=>'Visitors see it once per browser session — keeps the experience friendly.'],
                    ] as $i => $step)
                    <div class="d-flex align-items-start gap-12">
                        <div
                            style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:{{ $step['color'] }};display:flex;align-items:center;justify-content:center;font-size:.7rem;font-weight:700;color:{{ $step['icolor'] }};">
                            {{ $i + 1 }}
                        </div>
                        <div>
                            <p class="fw-semibold text-sm mb-1" style="color:#1e293b;">{{ $step['title'] }}</p>
                            <p class="text-xs text-secondary-light mb-0">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Remove-image form — outside main form to avoid illegal nested forms --}}
@if($popup?->image)
<form id="removeImageForm" method="POST" action="{{ route('admin.popup-notice.destroy-image') }}" style="display:none;">
    @csrf
    @method('DELETE')
</form>
@endif

@endsection

@push('page-scripts')
<script>
function previewPopupImage(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('popupPreviewImg').src = e.target.result;
        document.getElementById('uploadPlaceholder').style.display = 'none';
        document.getElementById('popupImgPreview').style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush