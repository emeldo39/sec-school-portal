@extends('layouts.portal')

@section('title', 'Attendance Report')
@section('page-title', 'Attendance Report')
@section('page-subtitle', 'View attendance summary by class and term')

@section('content')

<div class="card shadow-1 radius-8 p-16 mb-20">
    <form method="GET" class="row g-12 align-items-end">
        <div class="col-sm-4">
            <label class="form-label text-sm fw-semibold mb-4">Class</label>
            <select name="class_id" class="form-select form-select-sm">
                <option value="">— Select —</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-4">
            <label class="form-label text-sm fw-semibold mb-4">Term</label>
            <select name="term_id" class="form-select form-select-sm">
                <option value="">— Select —</option>
                @foreach($terms as $term)
                    <option value="{{ $term->id }}" {{ $selectedTermId == $term->id ? 'selected' : '' }}>
                        {{ $term->name }} {{ $term->academic_year }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">View</button>
        </div>
    </form>
</div>

@if($summary->isNotEmpty())
{{-- Attendance Charts --}}
<div class="row gy-4 mb-20">
    <div class="col-lg-4">
        <div class="card shadow-1 radius-8 p-20 h-100">
            <h6 class="fw-semibold mb-0">Status Breakdown</h6>
            <p class="text-xs text-secondary-light mt-2 mb-4">Total records by status</p>
            <div id="attendanceStatusDonut"></div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-1 radius-8 p-20 h-100">
            <h6 class="fw-semibold mb-0">Attendance Rate per Student</h6>
            <p class="text-xs text-secondary-light mt-2 mb-4">Percentage of days present</p>
            <div id="attendanceRateBar"></div>
        </div>
    </div>
</div>

<div class="card shadow-1 radius-8">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-24 py-12 text-sm">Student</th>
                        <th class="px-16 py-12 text-sm text-center">Present</th>
                        <th class="px-16 py-12 text-sm text-center">Absent</th>
                        <th class="px-16 py-12 text-sm text-center">Late</th>
                        <th class="px-16 py-12 text-sm text-center">Total Days</th>
                        <th class="px-16 py-12 text-sm text-center">Attendance %</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summary as $row)
                    @php
                        $pct = $row['total'] > 0 ? round(($row['present'] / $row['total']) * 100) : 0;
                        $color = $pct >= 75 ? 'success' : ($pct >= 50 ? 'warning' : 'danger');
                    @endphp
                    <tr>
                        <td class="px-24 py-12 text-sm fw-medium">{{ $row['student']->full_name ?? '—' }}</td>
                        <td class="px-16 py-12 text-center">
                            <span class="badge bg-success-100 text-success-600 px-8 py-4 radius-4">{{ $row['present'] }}</span>
                        </td>
                        <td class="px-16 py-12 text-center">
                            <span class="badge bg-danger-100 text-danger-600 px-8 py-4 radius-4">{{ $row['absent'] }}</span>
                        </td>
                        <td class="px-16 py-12 text-center">
                            <span class="badge bg-warning-100 text-warning-600 px-8 py-4 radius-4">{{ $row['late'] }}</span>
                        </td>
                        <td class="px-16 py-12 text-center text-sm">{{ $row['total'] }}</td>
                        <td class="px-16 py-12 text-center">
                            <span class="badge bg-{{ $color }}-100 text-{{ $color }}-600 px-8 py-4 radius-4 fw-semibold">
                                {{ $pct }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@elseif($selectedClassId && $selectedTermId)
<div class="card shadow-1 radius-8 p-32 text-center text-secondary-light">
    No attendance records found for the selected class and term.
</div>
@endif

@endsection

@push('scripts')
@if($summary->isNotEmpty())
<script>
(function () {
    @php
        $totalPresent = $summary->sum('present');
        $totalAbsent  = $summary->sum('absent');
        $totalLate    = $summary->sum('late');
        $attNames     = $summary->map(fn($r) => $r['student']->full_name ?? '—')->values();
        $attRates     = $summary->map(fn($r) => $r['total'] > 0 ? round(($r['present'] / $r['total']) * 100) : 0)->values();
    @endphp

    new ApexCharts(document.querySelector('#attendanceStatusDonut'), {
        series: [{{ $totalPresent }}, {{ $totalAbsent }}, {{ $totalLate }}],
        labels: ['Present', 'Absent', 'Late'],
        colors: ['#28a745', '#dc3545', '#f6a823'],
        chart: { type: 'donut', height: 230, fontFamily: 'inherit' },
        legend: { position: 'bottom', fontSize: '12px' },
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Records', fontSize: '12px', fontWeight: 600, color: '#2A2567' } } } } },
        dataLabels: { enabled: false },
        stroke: { width: 0 }
    }).render();

    new ApexCharts(document.querySelector('#attendanceRateBar'), {
        series: [{ name: 'Attendance %', data: {!! $attRates->toJson() !!} }],
        chart: { type: 'bar', height: 220, fontFamily: 'inherit', toolbar: { show: false } },
        xaxis: { categories: {!! $attNames->toJson() !!}, labels: { style: { fontSize: '11px' }, rotate: -30 } },
        yaxis: { min: 0, max: 100, labels: { formatter: v => v + '%', style: { fontSize: '11px' } } },
        plotOptions: { bar: { borderRadius: 3, columnWidth: '55%', distributed: true } },
        colors: {!! $attRates->map(fn($r) => $r >= 75 ? '#28a745' : ($r >= 50 ? '#f6a823' : '#dc3545'))->toJson() !!},
        legend: { show: false },
        dataLabels: { enabled: true, formatter: v => v + '%', style: { fontSize: '10px', fontWeight: 600 } },
        grid: { borderColor: '#f0f0f0' },
        annotations: { yaxis: [{ y: 75, borderColor: '#2A2567', borderWidth: 1, strokeDashArray: 4, label: { text: '75% target', style: { color: '#2A2567', fontSize: '10px' } } }] }
    }).render();
})();
</script>
@endif
@endpush
