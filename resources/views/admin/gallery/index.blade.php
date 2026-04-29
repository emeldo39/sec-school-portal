@extends('layouts.portal')

@section('title', 'Gallery')
@section('page-title', 'Gallery')
@section('page-subtitle', 'Manage school photo gallery')

@section('breadcrumb-actions')
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="ri-upload-2-line me-1"></i> Upload Photos
    </button>
@endsection

@section('content')
<div class="row g-16">
    @forelse($items as $item)
    <div class="col-6 col-sm-4 col-md-3 col-xl-2">
        <div class="card shadow-1 radius-8 overflow-hidden h-100">
            <div style="position:relative;">
                <img src="{{ asset('storage/' . $item->image_path) }}"
                     class="w-100" style="aspect-ratio:1;object-fit:cover;" alt="{{ $item->caption }}">
                <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST"
                      style="position:absolute;top:6px;right:6px;">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="btn btn-sm btn-danger px-6 py-2 d-flex align-items-center"
                            onclick="return confirm('Delete this image?')"
                            title="Delete">
                        <i class="ri-delete-bin-line" style="font-size:.8rem;"></i>
                    </button>
                </form>
            </div>
            @if($item->caption)
            <div class="p-8">
                <p class="text-xs text-secondary-light mb-0">{{ $item->caption }}</p>
            </div>
            @endif
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card shadow-1 radius-8 p-32 text-center text-secondary-light">
            No gallery images yet. Upload some photos!
        </div>
    </div>
    @endforelse
</div>

@if($items->hasPages())
<div class="mt-20">{{ $items->links() }}</div>
@endif

{{-- Upload Modal --}}
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Upload Gallery Photos</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-12">
                        <label class="form-label text-sm fw-semibold">Images <span class="text-danger">*</span></label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*" required>
                        <p class="text-xs text-secondary-light mt-4">You can select multiple images at once.</p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-sm fw-semibold">Caption (optional)</label>
                        <input type="text" name="caption" class="form-control" placeholder="Caption for all selected photos">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
