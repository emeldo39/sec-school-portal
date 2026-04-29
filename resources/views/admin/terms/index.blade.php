@extends('layouts.portal')

@section('title', 'Academic Terms')
@section('page-title', 'Academic Terms')
@section('page-subtitle', 'Manage academic sessions and terms')

@section('breadcrumb-actions')
    <button class="btn btn-primary btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addTermModal">
        <i class="ri-add-line"></i> Add Term
    </button>
@endsection

@section('content')

{{-- Current Term Banner --}}
@php $currentTerm = $terms->firstWhere('is_current', true); @endphp
@if($currentTerm)
<div class="card shadow-1 radius-8 mb-20 border-start border-success" style="border-left-width:4px !important;">
    <div class="card-body py-16 px-24 d-flex align-items-center gap-16">
        <div class="w-44-px h-44-px radius-8 bg-success-100 d-flex align-items-center justify-content-center flex-shrink-0">
            <i class="ri-calendar-check-line text-success-600 text-xl"></i>
        </div>
        <div>
            <p class="text-xs text-secondary-light mb-2">ACTIVE TERM</p>
            <p class="fw-bold text-sm mb-0" style="color:#2A2567;">
                {{ $currentTerm->name }} &mdash; {{ $currentTerm->academic_year }}
                <span class="badge bg-success-100 text-success-600 ms-8 px-8 py-3 radius-4">Current</span>
            </p>
            <p class="text-xs text-secondary-light mb-0 mt-2">
                {{ $currentTerm->start_date->format('d M Y') }} &ndash; {{ $currentTerm->end_date->format('d M Y') }}
            </p>
        </div>
    </div>
</div>
@else
<div class="alert alert-warning d-flex align-items-center gap-10 mb-20" role="alert">
    <i class="ri-error-warning-line fs-5 flex-shrink-0"></i>
    <div>
        <strong>No active term set.</strong> Select a term below and click <strong>Set Current</strong> to activate it.
    </div>
</div>
@endif

<div class="card shadow-1 radius-8">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-24 py-14 text-sm">Term</th>
                        <th class="px-16 py-14 text-sm">Academic Year</th>
                        <th class="px-16 py-14 text-sm">Start Date</th>
                        <th class="px-16 py-14 text-sm">End Date</th>
                        <th class="px-16 py-14 text-sm">Status</th>
                        <th class="px-16 py-14 text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($terms as $term)
                    <tr class="{{ $term->is_current ? 'table-primary' : '' }}">
                        <td class="px-24 py-12 text-sm fw-medium">{{ $term->name }}</td>
                        <td class="px-16 py-12 text-sm">{{ $term->academic_year }}</td>
                        <td class="px-16 py-12 text-sm">{{ $term->start_date->format('d M Y') }}</td>
                        <td class="px-16 py-12 text-sm">{{ $term->end_date->format('d M Y') }}</td>
                        <td class="px-16 py-12">
                            @if($term->is_current)
                                <span class="badge bg-success-100 text-success-600 px-8 py-4 radius-4">Current</span>
                            @else
                                <span class="badge bg-neutral-100 text-secondary-light px-8 py-4 radius-4">—</span>
                            @endif
                        </td>
                        <td class="px-16 py-12">
                            <div class="d-flex gap-2 align-items-center">
                                @unless($term->is_current)
                                <form action="{{ route('admin.terms.set-current', $term) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success px-8 py-4"
                                            onclick="return confirm('Set \'{{ $term->name }} {{ $term->academic_year }}\' as current term?')">
                                        <i class="ri-check-line"></i> Set Current
                                    </button>
                                </form>
                                @endunless
                                <button class="btn btn-sm btn-outline-primary px-8 py-4"
                                        data-bs-toggle="modal" data-bs-target="#editTerm{{ $term->id }}">
                                    <i class="ri-pencil-line"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editTerm{{ $term->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title">Edit Term</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.terms.update', $term) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body row gy-12">
                                        <div class="col-sm-6">
                                            <label class="form-label text-sm fw-semibold">Term Name</label>
                                            <input type="text" name="name" value="{{ $term->name }}" class="form-control" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label text-sm fw-semibold">Academic Year</label>
                                            <input type="text" name="academic_year" value="{{ $term->academic_year }}" class="form-control" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label text-sm fw-semibold">Start Date</label>
                                            <input type="date" name="start_date" value="{{ $term->start_date->format('Y-m-d') }}" class="form-control" required>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="form-label text-sm fw-semibold">End Date</label>
                                            <input type="date" name="end_date" value="{{ $term->end_date->format('Y-m-d') }}" class="form-control" required>
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
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-32 text-secondary-light">No terms created yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Term Modal --}}
<div class="modal fade" id="addTermModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Add Academic Term</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.terms.store') }}" method="POST">
                @csrf
                <div class="modal-body row gy-12">
                    <div class="col-sm-6">
                        <label class="form-label text-sm fw-semibold">Term Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. First Term" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-sm fw-semibold">Academic Year <span class="text-danger">*</span></label>
                        <input type="text" name="academic_year" class="form-control" placeholder="e.g. 2025/2026" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-sm fw-semibold">Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-sm fw-semibold">End Date <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Create Term</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
