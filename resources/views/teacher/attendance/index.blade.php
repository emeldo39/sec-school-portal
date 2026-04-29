@extends('layouts.portal')

@section('title', 'Mark Attendance')
@section('page-title', 'Attendance')
@section('page-subtitle', 'Mark daily class attendance')

@section('breadcrumb-actions')
    <a href="{{ route('teacher.attendance.report') }}" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-6">
        <iconify-icon icon="ph:chart-bar" class="text-base"></iconify-icon>
        Attendance Report
    </a>
@endsection

@section('content')

{{-- Class & Date selector --}}
<div class="card shadow-1 radius-8 p-16 mb-20">
    <form method="GET" class="row g-12 align-items-end">
        <div class="col-sm-4">
            <label class="form-label text-sm fw-semibold mb-4">Class <span class="text-danger">*</span></label>
            <select name="class_id" class="form-select form-select-sm" required onchange="this.form.submit()">
                <option value="">— Select Class —</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <label class="form-label text-sm fw-semibold mb-4">Date <span class="text-danger">*</span></label>
            <input type="date" name="date" value="{{ $selectedDate }}" class="form-control form-control-sm"
                   onchange="this.form.submit()">
        </div>
        <div class="col-sm-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">Load</button>
        </div>
    </form>
</div>

@if($selectedClassId && $existing->isNotEmpty())
<div class="alert alert-warning alert-dismissible fade show mb-20 d-flex align-items-center gap-10" role="alert">
    <i class="ri-error-warning-line fs-5 flex-shrink-0"></i>
    <div>
        <strong>Attendance already recorded</strong> for
        {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}.
        Saving will <strong>overwrite</strong> the existing records.
    </div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

@if($selectedClassId && $students->isNotEmpty())
<div class="card shadow-1 radius-8">
    <div class="card-header py-16 px-24 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0">
            Mark Attendance — {{ $classes->firstWhere('id', $selectedClassId)->name ?? '' }}
            <span class="text-secondary-light fw-normal ms-2">{{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}</span>
        </h6>
        <div class="d-flex gap-8">
            <button type="button" class="btn btn-sm btn-outline-success" onclick="markAll('present')">Mark All Present</button>
            <button type="button" class="btn btn-sm btn-outline-danger"  onclick="markAll('absent')">Mark All Absent</button>
        </div>
    </div>
    <form action="{{ route('teacher.attendance.store') }}" method="POST">
        @csrf
        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
        <input type="hidden" name="date" value="{{ $selectedDate }}">
        <input type="hidden" name="term_id" value="{{ $currentTerm?->id }}">

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-24 py-12 text-sm" style="width:50px;">#</th>
                            <th class="px-16 py-12 text-sm">Student</th>
                            <th class="px-16 py-12 text-sm">Admission No.</th>
                            <th class="px-16 py-12 text-sm text-center">Present</th>
                            <th class="px-16 py-12 text-sm text-center">Absent</th>
                            <th class="px-16 py-12 text-sm text-center">Late</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $i => $student)
                        @php $existing = $existing[$student->id] ?? null; $status = $existing?->status ?? 'present'; @endphp
                        <tr>
                            <td class="px-24 py-12 text-sm">{{ $i + 1 }}</td>
                            <td class="px-16 py-12 text-sm fw-medium">{{ $student->full_name }}</td>
                            <td class="px-16 py-12 text-sm">{{ $student->admission_number }}</td>
                            <td class="px-16 py-12 text-center">
                                <input class="form-check-input att-radio" type="radio"
                                       name="attendance[{{ $student->id }}]"
                                       value="present" {{ $status === 'present' ? 'checked' : '' }}
                                       style="width:18px;height:18px;accent-color:#198754;">
                            </td>
                            <td class="px-16 py-12 text-center">
                                <input class="form-check-input att-radio" type="radio"
                                       name="attendance[{{ $student->id }}]"
                                       value="absent" {{ $status === 'absent' ? 'checked' : '' }}
                                       style="width:18px;height:18px;accent-color:#dc3545;">
                            </td>
                            <td class="px-16 py-12 text-center">
                                <input class="form-check-input att-radio" type="radio"
                                       name="attendance[{{ $student->id }}]"
                                       value="late" {{ $status === 'late' ? 'checked' : '' }}
                                       style="width:18px;height:18px;accent-color:#ffc107;">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer px-24 py-16 d-flex gap-12">
            <button type="submit" class="btn btn-primary px-32">Save Attendance</button>
        </div>
    </form>
</div>
@elseif($selectedClassId)
<div class="card shadow-1 radius-8 p-32 text-center text-secondary-light">
    No active students in the selected class.
</div>
@endif

@endsection

@push('scripts')
<script>
function markAll(status) {
    document.querySelectorAll(`input[type="radio"][value="${status}"]`).forEach(r => r.checked = true);
}
</script>
@endpush
