@extends('layouts.portal')

@section('title', 'Students')
@section('page-title', 'Students')
@section('page-subtitle', 'Manage enrolled students')

@section('breadcrumb-actions')
    <a href="{{ route('admin.students.bulk-promote') }}"
       class="btn btn-outline-warning btn-sm d-flex align-items-center gap-2">
        <i class="ri-arrow-up-double-line"></i> Bulk Promote
    </a>
    <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
        <i class="ri-add-line"></i> Add Student
    </a>
@endsection

@section('content')

{{-- Filters --}}
<div class="card shadow-1 radius-8 p-16 mb-16">
    <form method="GET" class="row g-12 align-items-end">
        <div class="col-sm-4">
            <label class="form-label text-sm fw-semibold mb-4">Class</label>
            <select name="class_id" class="form-select form-select-sm">
                <option value="">All Classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <label class="form-label text-sm fw-semibold mb-4">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="col-sm-4">
            <label class="form-label text-sm fw-semibold mb-4">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Name or admission no." class="form-control form-control-sm">
        </div>
        <div class="col-sm-1">
            <button type="submit" class="btn btn-primary btn-sm w-100">Go</button>
        </div>
    </form>
</div>

<div class="card shadow-1 radius-8">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-24 py-14 text-sm">#</th>
                        <th class="px-16 py-14 text-sm">Admission No.</th>
                        <th class="px-16 py-14 text-sm">Name</th>
                        <th class="px-16 py-14 text-sm">Class</th>
                        <th class="px-16 py-14 text-sm">Gender</th>
                        <th class="px-16 py-14 text-sm">Guardian</th>
                        <th class="px-16 py-14 text-sm">Status</th>
                        <th class="px-16 py-14 text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td class="px-24 py-12 text-sm">{{ $students->firstItem() + $loop->index }}</td>
                        <td class="px-16 py-12 text-sm fw-medium">{{ $student->admission_number }}</td>
                        <td class="px-16 py-12">
                            <div class="d-flex align-items-center gap-10">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" class="w-32-px h-32-px rounded-circle object-fit-cover" alt="">
                                @else
                                    <span class="w-32-px h-32-px rounded-circle bg-primary-100 text-primary-600 d-flex align-items-center justify-content-center fw-bold text-xs">
                                        {{ strtoupper(substr($student->first_name, 0, 1)) }}
                                    </span>
                                @endif
                                <span class="text-sm">{{ $student->full_name }}</span>
                            </div>
                        </td>
                        <td class="px-16 py-12 text-sm">{{ $student->schoolClass->name ?? '—' }}</td>
                        <td class="px-16 py-12 text-sm text-capitalize">{{ $student->gender }}</td>
                        <td class="px-16 py-12 text-sm">{{ $student->guardian_name }}</td>
                        <td class="px-16 py-12">
                            @if($student->status === 'active')
                                <span class="badge bg-success-100 text-success-600 px-8 py-4 radius-4">Active</span>
                            @else
                                <span class="badge bg-neutral-100 text-secondary-light px-8 py-4 radius-4">Inactive</span>
                            @endif
                        </td>
                        <td class="px-16 py-12">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.students.show', $student) }}"
                                   class="btn btn-sm btn-outline-info px-8 py-4" title="View">
                                    <i class="ri-eye-line"></i>
                                </a>
                                <a href="{{ route('admin.students.edit', $student) }}"
                                   class="btn btn-sm btn-outline-primary px-8 py-4" title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-8 py-4" title="Delete"
                                            onclick="return confirm('Delete {{ addslashes($student->full_name) }}? This cannot be undone.')">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-32 text-secondary-light">
                            No students found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($students->hasPages())
    <div class="card-footer px-24 py-12">
        {{ $students->links() }}
    </div>
    @endif
</div>
@endsection
