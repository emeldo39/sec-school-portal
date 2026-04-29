@extends('layouts.portal')

@section('title', 'Hero Slides')
@section('page-title', 'Hero Slides')
@section('page-subtitle', 'Manage homepage slider content and images')

@section('breadcrumb-actions')
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createSlideModal">
        <i class="ri-add-line me-1"></i> Add Slide
    </button>
@endsection

@section('content')


<div class="row g-16">
    @forelse($slides as $slide)
    <div class="col-12">
        <div class="card shadow-1 radius-8 overflow-hidden">
            <div class="row g-0">
                {{-- Thumbnail --}}
                <div class="col-md-3" style="min-height:160px;background:#1a1f2e;position:relative;">
                    @if($slide->image)
                        <img src="{{ asset('storage/' . $slide->image) }}"
                             style="width:100%;height:100%;object-fit:cover;min-height:160px;" alt="">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100" style="min-height:160px;">
                            <i class="ri-image-line" style="font-size:2.5rem;color:rgba(255,255,255,.3);"></i>
                        </div>
                    @endif
                    <span class="badge position-absolute" style="top:8px;left:8px;background:{{ $slide->is_active ? '#22c55e' : '#6b7280' }};font-size:.7rem;">
                        {{ $slide->is_active ? 'Active' : 'Hidden' }}
                    </span>
                    <span class="badge position-absolute" style="top:8px;right:8px;background:rgba(0,0,0,.55);font-size:.7rem;">
                        #{{ $slide->sort_order }}
                    </span>
                </div>

                {{-- Content --}}
                <div class="col-md-9">
                    <div class="card-body p-20 d-flex flex-column h-100">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-4" style="font-size:.95rem;">
                                {{ $slide->title }}
                                @if($slide->title_highlight)
                                    <span class="ms-1 px-6 py-1 radius-4" style="background:#FEF3C7;color:#92400E;font-size:.78rem;">
                                        {{ $slide->title_highlight }}
                                    </span>
                                @endif
                            </h6>
                            @if($slide->description)
                            <p class="text-secondary-light text-sm mb-8" style="line-height:1.5;">{{ Str::limit($slide->description, 140) }}</p>
                            @endif
                            <div class="d-flex align-items-center gap-8 flex-wrap">
                                <span class="badge" style="background:#EDE9FE;color:#5B21B6;font-size:.72rem;">
                                    <i class="ri-link me-1"></i>{{ $slide->button_text }} → {{ $slide->button_url }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex gap-8 mt-16 flex-wrap">
                            {{-- Toggle Active --}}
                            <form action="{{ route('admin.hero-slides.toggle', $slide) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $slide->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}">
                                    <i class="ri-{{ $slide->is_active ? 'eye-off' : 'eye' }}-line me-1"></i>
                                    {{ $slide->is_active ? 'Hide' : 'Show' }}
                                </button>
                            </form>
                            {{-- Edit --}}
                            <button class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal" data-bs-target="#editSlideModal{{ $slide->id }}">
                                <i class="ri-edit-line me-1"></i> Edit
                            </button>
                            {{-- Delete --}}
                            <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="POST"
                                  onsubmit="return confirm('Delete this slide?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="ri-delete-bin-line me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editSlideModal{{ $slide->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Edit Slide</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.hero-slides.update', $slide) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        @include('admin.hero-slides._form', ['slide' => $slide])
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @empty
    <div class="col-12">
        <div class="card shadow-1 radius-8 p-32 text-center text-secondary-light">
            <i class="ri-slideshow-line" style="font-size:2.5rem;display:block;margin-bottom:12px;"></i>
            No slides yet. Click <strong>Add Slide</strong> to create your first homepage slide.
        </div>
    </div>
    @endforelse
</div>

{{-- Create Modal --}}
<div class="modal fade" id="createSlideModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Add New Slide</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.hero-slides.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @include('admin.hero-slides._form', ['slide' => null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Create Slide</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function slideImgPreview(input, previewId) {
    const box   = document.getElementById(previewId);
    const img   = document.getElementById(previewId + '_img');
    const label = document.getElementById(previewId + '_label');
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function (e) {
        img.src           = e.target.result;
        label.textContent = input.files[0].name;
        box.style.display = '';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush
