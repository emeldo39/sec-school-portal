@extends('layouts.portal')

@section('title', 'Announcements')
@section('page-title', 'Announcements')
@section('page-subtitle', 'Post and manage school announcements')

@section('breadcrumb-actions')
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
        <i class="ri-add-line"></i> New Announcement
    </button>
@endsection

@section('content')
<div class="row gy-4">
    <div class="col-lg-8">
        <div class="card shadow-1 radius-8">
            <div class="card-body p-0">
                @forelse($announcements as $ann)
                <div class="d-flex align-items-start gap-16 p-20 border-bottom">
                    <div class="w-44-px h-44-px radius-8 bg-primary-100 d-flex align-items-center justify-content-center flex-shrink-0">
                        <iconify-icon icon="ph:megaphone" class="text-primary-600 text-xl"></iconify-icon>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-start justify-content-between gap-8">
                            <div>
                                <h6 class="fw-semibold mb-4 text-sm">{{ $ann->title }}</h6>
                                <p class="text-sm text-secondary-light mb-8">{{ $ann->body }}</p>
                                <div class="d-flex flex-wrap gap-8">
                                    <span class="badge bg-neutral-100 text-secondary-light px-8 py-4 radius-4 text-xs">
                                        <i class="ri-user-3-line me-1"></i>{{ $ann->postedBy->name ?? 'Admin' }}
                                    </span>
                                    <span class="badge bg-neutral-100 text-secondary-light px-8 py-4 radius-4 text-xs">
                                        <i class="ri-time-line me-1"></i>{{ $ann->created_at->diffForHumans() }}
                                    </span>
                                    @php $targetMap = ['all'=>'Everyone','teachers'=>'Teachers Only','class'=>'Class: ' . ($ann->schoolClass->name ?? '—')]; @endphp
                                    <span class="badge bg-primary-100 text-primary-600 px-8 py-4 radius-4 text-xs">
                                        {{ $targetMap[$ann->target] ?? $ann->target }}
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex gap-6 flex-shrink-0">
                                <button type="button"
                                        class="btn btn-sm btn-outline-primary px-8 py-4"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editAnnModal{{ $ann->id }}"
                                        title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <form action="{{ route('admin.announcements.destroy', $ann) }}" method="POST" class="m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-8 py-4"
                                            onclick="return confirm('Delete this announcement?')" title="Delete">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-32 text-center text-secondary-light">
                    No announcements yet.
                </div>
                @endforelse
            </div>
            @if($announcements->hasPages())
            <div class="card-footer px-24 py-12">{{ $announcements->links() }}</div>
            @endif
        </div>
    </div>

    {{-- Post Form (sidebar) --}}
    <div class="col-lg-4">
        <div class="card shadow-1 radius-8 p-20">
            <h6 class="fw-semibold mb-16">Post Announcement</h6>
            <form action="{{ route('admin.announcements.store') }}" method="POST">
                @csrf
                <div class="mb-12">
                    <label class="form-label text-sm fw-semibold">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="form-control @error('title') is-invalid @enderror" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-12">
                    <label class="form-label text-sm fw-semibold">Body <span class="text-danger">*</span></label>
                    <textarea name="body" rows="4"
                              class="form-control @error('body') is-invalid @enderror" required>{{ old('body') }}</textarea>
                    @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-12">
                    <label class="form-label text-sm fw-semibold">Target Audience</label>
                    <select name="target" id="targetSelect" class="form-select" onchange="toggleClassField()">
                        <option value="all"      {{ old('target') === 'all'      ? 'selected' : '' }}>Everyone</option>
                        <option value="teachers" {{ old('target') === 'teachers' ? 'selected' : '' }}>Teachers Only</option>
                        <option value="class"    {{ old('target') === 'class'    ? 'selected' : '' }}>Specific Class</option>
                    </select>
                </div>
                <div class="mb-16" id="classField" style="display:none;">
                    <label class="form-label text-sm fw-semibold">Class</label>
                    <select name="class_id" class="form-select">
                        <option value="">— Select —</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Post Announcement</button>
            </form>
        </div>
    </div>
</div>

{{-- Per-announcement edit modals --}}
@foreach($announcements as $ann)
<div class="modal fade" id="editAnnModal{{ $ann->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-semibold">Edit Announcement</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.announcements.update', $ann) }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="mb-12">
                        <label class="form-label text-sm fw-semibold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ $ann->title }}" class="form-control" required maxlength="150">
                    </div>
                    <div class="mb-12">
                        <label class="form-label text-sm fw-semibold">Body <span class="text-danger">*</span></label>
                        <textarea name="body" rows="4" class="form-control" required maxlength="5000">{{ $ann->body }}</textarea>
                    </div>
                    <div class="mb-12">
                        <label class="form-label text-sm fw-semibold">Target Audience</label>
                        <select name="target" class="form-select" onchange="toggleEditClassField(this)">
                            <option value="all"      {{ $ann->target === 'all'      ? 'selected' : '' }}>Everyone</option>
                            <option value="teachers" {{ $ann->target === 'teachers' ? 'selected' : '' }}>Teachers Only</option>
                            <option value="class"    {{ $ann->target === 'class'    ? 'selected' : '' }}>Specific Class</option>
                        </select>
                    </div>
                    <div class="edit-class-field mb-4" style="{{ $ann->target === 'class' ? '' : 'display:none;' }}">
                        <label class="form-label text-sm fw-semibold">Class</label>
                        <select name="class_id" class="form-select">
                            <option value="">— Select —</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ $ann->class_id == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary px-20">
                        <i class="ri-save-line me-1"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

{{-- Modal version (for the header button) --}}
<div class="modal fade" id="addAnnouncementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">New Announcement</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.announcements.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-12">
                        <label class="form-label text-sm fw-semibold">Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-12">
                        <label class="form-label text-sm fw-semibold">Body</label>
                        <textarea name="body" rows="3" class="form-control" required></textarea>
                    </div>
                    <div class="mb-12">
                        <label class="form-label text-sm fw-semibold">Target</label>
                        <select name="target" class="form-select">
                            <option value="all">Everyone</option>
                            <option value="teachers">Teachers Only</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleClassField() {
    document.getElementById('classField').style.display =
        document.getElementById('targetSelect').value === 'class' ? 'block' : 'none';
}
toggleClassField();

function toggleEditClassField(select) {
    const modal = select.closest('.modal-body');
    modal.querySelector('.edit-class-field').style.display =
        select.value === 'class' ? '' : 'none';
}
</script>
@endpush
