@extends('layouts.portal')

@section('title', 'Attendance Report')
@section('page-title', 'Attendance Report')
@section('page-subtitle', 'View attendance records by class and term')

@section('breadcrumb-actions')
    <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm">Back to Reports</a>
@endsection

@section('content')

{{-- Filters --}}
<div class="card shadow-1 radius-8 p-16 mb-16">
    <form method="GET" class="row g-12 align-items-end">
        <div class="col-sm-4">
            <label class="form-label text-sm fw-semibold mb-4">Class</label>
            <select name="class_id" class="form-select form-select-sm">
                <option value="">All Classes</option>
                @foreach($classes as $c)
                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-4">
            <label class="form-label text-sm fw-semibold mb-4">Term</label>
            <select name="term_id" class="form-select form-select-sm">
                <option value="">All Terms</option>
                @foreach($terms as $t)
                    <option value="{{ $t->id }}" {{ request('term_id') == $t->id ? 'selected' : '' }}>
                        {{ $t->name }} {{ $t->academic_year }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
        </div>
    </form>
</div>

@php
    $totalPresent = $attendanceSummary['present'] ?? 0;
    $totalAbsent  = $attendanceSummary['absent']  ?? 0;
    $totalLate    = $attendanceSummary['late']    ?? 0;
    $hasChartData = ($totalPresent + $totalAbsent + $totalLate) > 0;
@endphp
@if($hasChartData)
<div class="row gy-4 mb-16">
    <div class="col-lg-4">
        <div class="card shadow-1 radius-8 p-20">
            <h6 class="fw-semibold mb-0">Status Distribution</h6>
            <p class="text-xs text-secondary-light mt-2 mb-4">All filtered records</p>
            <div id="adminAttDonut"></div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-1 radius-8 p-20">
            <h6 class="fw-semibold mb-0">Attendance Summary</h6>
            <p class="text-xs text-secondary-light mt-2 mb-4">Total records by status</p>
            <div id="adminAttBar"></div>
        </div>
    </div>
</div>
@endif

<div class="card shadow-1 radius-8">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-24 py-12 text-sm">Date</th>
                        <th class="px-16 py-12 text-sm">Student</th>
                        <th class="px-16 py-12 text-sm">Class</th>
                        <th class="px-16 py-12 text-sm">Term</th>
                        <th class="px-16 py-12 text-sm">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $rec)
                    <tr>
                        <td class="px-24 py-10 text-sm">{{ $rec->date->format('d M Y') }}</td>
                        <td class="px-16 py-10 text-sm">{{ $rec->student->full_name ?? '—' }}</td>
                        <td class="px-16 py-10 text-sm">{{ $rec->schoolClass->name ?? '—' }}</td>
                        <td class="px-16 py-10 text-sm">{{ $rec->term->name ?? '—' }}</td>
                        <td class="px-16 py-10">
                            @php $sc = $rec->status === 'present' ? 'success' : ($rec->status === 'absent' ? 'danger' : 'warning'); @endphp
                            <span class="badge bg-{{ $sc }}-100 text-{{ $sc }}-600 px-8 py-4 radius-4 text-xs text-capitalize">
                                {{ $rec->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-32 text-secondary-light">No attendance records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($records->hasPages())
    <div class="card-footer px-24 py-12">{{ $records->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
@if($hasChartData)
<script>
(function () {
    new ApexCharts(document.querySelector('#adminAttDonut'), {
        series: [{{ $totalPresent }}, {{ $totalAbsent }}, {{ $totalLate }}],
        labels: ['Present', 'Absent', 'Late'],
        colors: ['#28a745', '#dc3545', '#f6a823'],
        chart: { type: 'donut', height: 230, fontFamily: 'inherit' },
        legend: { position: 'bottom', fontSize: '12px' },
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Records', fontSize: '12px', fontWeight: 600, color: '#2A2567' } } } } },
        dataLabels: { enabled: false },
        stroke: { width: 0 }
    }).render();

    new ApexCharts(document.querySelector('#adminAttBar'), {
        series: [{ name: 'Records', data: [{{ $totalPresent }}, {{ $totalAbsent }}, {{ $totalLate }}] }],
        chart: { type: 'bar', height: 220, fontFamily: 'inherit', toolbar: { show: false } },
        xaxis: { categories: ['Present', 'Absent', 'Late'], labels: { style: { fontSize: '13px' } } },
        yaxis: { labels: { style: { fontSize: '11px' } } },
        plotOptions: { bar: { borderRadius: 5, columnWidth: '40%', distributed: true } },
        colors: ['#28a745', '#dc3545', '#f6a823'],
        legend: { show: false },
        dataLabels: { enabled: true, style: { fontSize: '12px', fontWeight: 700 } },
        grid: { borderColor: '#f0f0f0' }
    }).render();
})();
</script>
@endif
@endpush
