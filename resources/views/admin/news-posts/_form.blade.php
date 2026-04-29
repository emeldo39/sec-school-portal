<div class="row g-20">

    <div class="col-12">
        <label class="form-label text-sm fw-semibold">Post Title <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $post->title ?? '') }}"
               placeholder="e.g. School Sports Day 2025 Highlights" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label text-sm fw-semibold">
            Excerpt <small class="text-secondary-light fw-normal">(short summary shown on cards)</small>
        </label>
        <textarea name="excerpt" class="form-control @error('excerpt') is-invalid @enderror"
                  rows="2" maxlength="300"
                  placeholder="A one- or two-sentence summary of the post...">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
        @error('excerpt')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label text-sm fw-semibold">
            Full Article Body <small class="text-secondary-light fw-normal">(optional)</small>
        </label>
        <textarea name="body" id="postBody" class="form-control @error('body') is-invalid @enderror"
                  rows="12"
                  placeholder="Write the full news article here...">{{ old('body', $post->body ?? '') }}</textarea>
        @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label text-sm fw-semibold">Author Name <small class="text-secondary-light fw-normal">(optional)</small></label>
        <input type="text" name="author" class="form-control"
               value="{{ old('author', $post->author ?? auth()->user()->name) }}"
               placeholder="e.g. School Administration">
    </div>

    <div class="col-md-6">
        <label class="form-label text-sm fw-semibold">Cover Image <small class="text-secondary-light fw-normal">(optional)</small></label>
        @php $previewId = 'newsImgPreview_' . ($post->id ?? 'new'); @endphp
        <input type="file" name="image" id="newsImgInput" class="form-control" accept="image/*"
               onchange="newsImgPreview(this, '{{ $previewId }}')">
        <p class="text-xs text-secondary-light mt-4">Recommended: landscape, at least 800×500 px. Max 5 MB.</p>

        <div id="{{ $previewId }}" class="mt-8" style="{{ isset($post) && $post->image ? '' : 'display:none;' }}">
            <div style="position:relative;border-radius:8px;overflow:hidden;max-height:140px;background:#1a1f2e;">
                <img id="{{ $previewId }}_img"
                     src="{{ isset($post) && $post->image ? asset('storage/' . $post->image) : '' }}"
                     style="width:100%;max-height:140px;object-fit:cover;display:block;" alt="Preview">
                <div style="position:absolute;inset:0;background:linear-gradient(transparent 50%,rgba(0,0,0,.55));pointer-events:none;"></div>
                <span id="{{ $previewId }}_label"
                      style="position:absolute;bottom:8px;left:10px;color:#fff;font-size:.7rem;font-weight:600;">
                    {{ isset($post) && $post->image ? 'Current cover — upload to replace.' : '' }}
                </span>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_published" value="1"
                   id="is_published"
                   {{ old('is_published', ($post->is_published ?? false)) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold text-sm" for="is_published">
                Publish immediately
            </label>
            <p class="text-xs text-secondary-light mt-2 mb-0">When unchecked, the post is saved as a draft and hidden from the public website.</p>
        </div>
    </div>

</div>

<script>
function newsImgPreview(input, previewId) {
    const box   = document.getElementById(previewId);
    const img   = document.getElementById(previewId + '_img');
    const label = document.getElementById(previewId + '_label');
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        img.src           = e.target.result;
        label.textContent = input.files[0].name;
        box.style.display = '';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
