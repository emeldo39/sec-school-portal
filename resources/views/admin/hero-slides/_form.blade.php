<div class="row g-16">

    <div class="col-12">
        <label class="form-label text-sm fw-semibold">Slide Title <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control"
               value="{{ old('title', $slide?->title) }}"
               placeholder="e.g. Building Brighter Futures Through" required>
        <p class="text-xs text-secondary-light mt-4">The main heading shown on the slide.</p>
    </div>

    <div class="col-12">
        <label class="form-label text-sm fw-semibold">Highlighted Words <small class="text-secondary-light">(optional)</small></label>
        <input type="text" name="title_highlight" class="form-control"
               value="{{ old('title_highlight', $slide?->title_highlight) }}"
               placeholder="e.g. Quality Education">
        <p class="text-xs text-secondary-light mt-4">These words appear in <span style="color:#D97706;font-weight:600;">gold colour</span> after the main title.</p>
    </div>

    <div class="col-12">
        <label class="form-label text-sm fw-semibold">Description <small class="text-secondary-light">(optional)</small></label>
        <textarea name="description" class="form-control" rows="3"
                  placeholder="A short sentence about the school shown under the heading...">{{ old('description', $slide?->description) }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label text-sm fw-semibold">Button Label <span class="text-danger">*</span></label>
        <input type="text" name="button_text" class="form-control"
               value="{{ old('button_text', $slide?->button_text ?? 'Learn More') }}"
               placeholder="e.g. Apply Now" required>
    </div>

    <div class="col-md-6">
        <label class="form-label text-sm fw-semibold">Button Link <span class="text-danger">*</span></label>
        <input type="text" name="button_url" class="form-control"
               value="{{ old('button_url', $slide?->button_url ?? '/about') }}"
               placeholder="e.g. /admissions  or  /about" required>
        <p class="text-xs text-secondary-light mt-4">Use a relative path like <code>/admissions</code> or <code>/academics</code>.</p>
    </div>

    <div class="col-md-8">
        <label class="form-label text-sm fw-semibold">Background Image</label>
        @php $previewId = 'imgPreview_' . ($slide?->id ?? 'new'); $inputId = 'imgInput_' . ($slide?->id ?? 'new'); @endphp
        <input type="file" name="image" id="{{ $inputId }}" class="form-control" accept="image/*"
               onchange="slideImgPreview(this, '{{ $previewId }}')">
        <p class="text-xs text-secondary-light mt-4">Recommended: landscape image, at least 1400×700 px. Max 5 MB.</p>

        {{-- Preview box --}}
        <div id="{{ $previewId }}" class="mt-8"
             style="{{ $slide?->image ? '' : 'display:none;' }}">
            <div style="position:relative;border-radius:10px;overflow:hidden;max-height:160px;background:#1a1f2e;">
                <img id="{{ $previewId }}_img"
                     src="{{ $slide?->image ? asset('storage/' . $slide->image) : '' }}"
                     alt="Preview"
                     style="width:100%;max-height:160px;object-fit:cover;display:block;">
                <div style="position:absolute;inset:0;background:linear-gradient(transparent 55%,rgba(0,0,0,.6));pointer-events:none;"></div>
                <span id="{{ $previewId }}_label"
                      style="position:absolute;bottom:8px;left:10px;color:#fff;font-size:.72rem;font-weight:600;letter-spacing:.3px;">
                    {{ $slide?->image ? 'Current image — upload a new one to replace.' : '' }}
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-2">
        <label class="form-label text-sm fw-semibold">Order</label>
        <input type="number" name="sort_order" class="form-control" min="0"
               value="{{ old('sort_order', $slide?->sort_order ?? 0) }}">
        <p class="text-xs text-secondary-light mt-4">Lower = first.</p>
    </div>

    <div class="col-md-2 d-flex align-items-end pb-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   id="is_active_{{ $slide?->id ?? 'new' }}"
                   {{ old('is_active', $slide?->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label text-sm fw-semibold" for="is_active_{{ $slide?->id ?? 'new' }}">
                Active
            </label>
        </div>
    </div>

</div>
