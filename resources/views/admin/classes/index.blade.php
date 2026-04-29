@extends('layouts.portal')

@section('title', 'Classes')
@section('page-title', 'Classes')
@section('page-subtitle', 'Manage school classes')

@section('breadcrumb-actions')
    <button class="btn btn-primary btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addClassModal">
        <i class="ri-add-line"></i> Add Class
    </button>
@endsection

@section('content')
<div class="row gy-4">
    @foreach($classes as $class)
    <div class="col-sm-6 col-lg-4 col-xl-3">
        <div class="card shadow-1 radius-8 p-20">
            <div class="d-flex align-items-center justify-content-between mb-12">
                <span class="badge {{ $class->level === 'JSS' ? 'bg-info-100 text-info-600' : 'bg-primary-100 text-primary-600' }} px-10 py-5 radius-4 text-sm">
                    {{ $class->level === 'JSS' ? 'Junior' : 'Senior' }}
                </span>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-primary px-8 py-4"
                            data-bs-toggle="modal" data-bs-target="#editModal{{ $class->id }}" title="Edit">
                        <i class="ri-pencil-line"></i>
                    </button>
                    <form action="{{ route('admin.classes.destroy', $class) }}" method="POST" class="m-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger px-8 py-4" title="Delete"
                                onclick="return confirm('Delete class {{ $class->name }}?')">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </form>
                </div>
            </div>
            <h6 class="fw-semibold mb-4">{{ $class->name }}</h6>
            <p class="text-sm text-secondary-light mb-0">{{ $class->students_count }} student(s)</p>
            @if($class->formTeacher)
                <p class="text-xs text-secondary-light mt-4 mb-0">
                    <i class="ri-user-line me-1"></i>Form Teacher: {{ $class->formTeacher->name }}
                </p>
            @endif
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal{{ $class->id }}" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Edit Class</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.classes.update', $class) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-12">
                            <label class="form-label text-sm fw-semibold">Name</label>
                            <input type="text" name="name" value="{{ $class->name }}" class="form-control" required>
                        </div>
                        <div class="mb-0">
                            <label class="form-label text-sm fw-semibold">Level</label>
                            <select name="level" class="form-select" required>
                                <option value="JSS" {{ $class->level === 'JSS' ? 'selected' : '' }}>Junior</option>
                                <option value="SSS" {{ $class->level === 'SSS' ? 'selected' : '' }}>Senior (Senior Secondary)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Add Class Modal --}}
<div class="modal fade" id="addClassModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Add Class</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.classes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-12">
                        <label class="form-label text-sm fw-semibold">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. JSS1C" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-sm fw-semibold">Level</label>
                        <select name="level" class="form-select" required>
                            <option value="JSS">Junior</option>
                            <option value="SSS">Senior (Senior Secondary)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
