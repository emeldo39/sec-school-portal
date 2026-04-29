@extends('layouts.portal')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')
@section('page-subtitle', 'School performance overview')

@section('content')

{{-- Stat Summary --}}
<div class="row gy-4 mb-24">
    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-1 radius-8 p-20 d-flex flex-row align-items-center gap-16">
            <div class="w-48-px h-48-px radius-8 bg-primary-100 d-flex align-items-center justify-content-center flex-shrink-0">
                <iconify-icon icon="ph:student" class="text-primary-600 text-xl"></iconify-icon>
            </div>
            <div>
                <p class="text-secondary-light text-xs mb-4">Active Students</p>
                <h5 class="fw-semibold mb-0">{{ number_format($stats['total_students']) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-1 radius-8 p-20 d-flex flex-row align-items-center gap-16">
            <div class="w-48-px h-48-px radius-8 bg-warning-100 d-flex align-items-center justify-content-center flex-shrink-0">
                <iconify-icon icon="ph:clock-countdown" class="text-warning-600 text-xl"></iconify-icon>
            </div>
            <div>
                <p class="text-secondary-light text-xs mb-4">Scores Pending</p>
                <h5 class="fw-semibold mb-0">{{ number_format($stats['scores_pending']) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-1 radius-8 p-20 d-flex flex-row align-items-center gap-16">
            <div class="w-48-px h-48-px radius-8 bg-success-100 d-flex align-items-center justify-content-center flex-shrink-0">
                <iconify-icon icon="ph:check-circle" class="text-success-600 text-xl"></iconify-icon>
            </div>
            <div>
                <p class="text-secondary-light text-xs mb-4">Scores Approved</p>
                <h5 class="fw-semibold mb-0">{{ number_format($stats['scores_approved']) }}</h5>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card shadow-1 radius-8 p-20 d-flex flex-row align-items-center gap-16">
            <div class="w-48-px h-48-px radius-8 bg-info-100 d-flex align-items-center justify-content-center flex-shrink-0">
                <iconify-icon icon="ph:lock-key" class="text-info-600 text-xl"></iconify-icon>
            </div>
            <div>
                <p class="text-secondary-light text-xs mb-4">Scores Locked</p>
                <h5 class="fw-semibold mb-0">{{ number_format($stats['scores_locked']) }}</h5>
            </div>
        </div>
    </div>
</div>

{{-- Score Workflow Chart --}}
<div class="row gy-4 mb-24">
    <div class="col-lg-5">
        <div class="card shadow-1 radius-8 p-20 h-100">
            <h6 class="fw-semibold mb-0">Score Workflow Status</h6>
            <p class="text-xs text-secondary-light mt-2 mb-4">Distribution of all scores by current status</p>
            <div id="scoreWorkflowChart"></div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card shadow-1 radius-8 p-20 h-100">
            <h6 class="fw-semibold mb-0">Score Approval Pipeline</h6>
            <p class="text-xs text-secondary-light mt-2 mb-4">Progress through the review workflow</p>
            <div id="scorePipelineBar"></div>
        </div>
    </div>
</div>

{{-- Quick Links --}}
<div class="row gy-4">
    <div class="col-lg-6">
        <div class="card shadow-1 radius-8 p-24">
            <h6 class="fw-semibold mb-16">Class Reports</h6>
            <p class="text-sm text-secondary-light mb-16">View consolidated score tables for any class and term.</p>
            <div class="row g-8">
                @foreach($classes->groupBy('level') as $level => $lvlClasses)
                <div class="col-12">
                    <p class="text-xs fw-bold text-secondary-light mb-8">{{ $level }}</p>
                    <div class="d-flex flex-wrap gap-8">
                        @foreach($lvlClasses as $class)
                        <a href="{{ route('admin.reports.class', $class) }}"
                           class="btn btn-sm btn-outline-primary px-12 py-6">
                            {{ $class->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-1 radius-8 p-24">
            <h6 class="fw-semibold mb-16">Other Reports</h6>
            <div class="d-flex flex-column gap-12">
                <a href="{{ route('admin.reports.attendance') }}"
                   class="d-flex align-items-center gap-12 p-12 radius-8 bg-neutral-50 text-decoration-none">
                    <div class="w-40-px h-40-px radius-8 bg-warning-100 d-flex align-items-center justify-content-center">
                        <iconify-icon icon="ph:calendar-check" class="text-warning-600 text-lg"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-sm fw-semibold mb-0">Attendance Report</p>
                        <p class="text-xs text-secondary-light mb-0">View attendance records by class and term</p>
                    </div>
                </a>
                <a href="{{ route('admin.reports.export.attendance-csv') }}"
                   class="d-flex align-items-center gap-12 p-12 radius-8 bg-neutral-50 text-decoration-none">
                    <div class="w-40-px h-40-px radius-8 bg-success-100 d-flex align-items-center justify-content-center">
                        <iconify-icon icon="ph:file-csv" class="text-success-600 text-lg"></iconify-icon>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-sm fw-semibold mb-0">Export Attendance CSV</p>
                        <p class="text-xs text-secondary-light mb-0">Download all attendance records as CSV</p>
                    </div>
                    <i class="ri-download-2-line text-secondary-light"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    new ApexCharts(document.querySelector('#scoreWorkflowChart'), {
        series: [{{ $stats['scores_pending'] }}, {{ $stats['scores_approved'] }}, {{ $stats['scores_locked'] }}, {{ $stats['scores_returned'] }}, {{ $stats['scores_draft'] }}],
        labels: ['Pending', 'Approved', 'Locked', 'Returned', 'Draft'],
        colors: ['#f6a823', '#28a745', '#2A2567', '#dc3545', '#adb5bd'],
        chart: { type: 'donut', height: 260, fontFamily: 'inherit' },
        legend: { position: 'bottom', fontSize: '12px' },
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', fontSize: '13px', fontWeight: 600, color: '#2A2567' } } } } },
        dataLabels: { enabled: false },
        stroke: { width: 0 }
    }).render();

    new ApexCharts(document.querySelector('#scorePipelineBar'), {
        series: [{
            name: 'Scores',
            data: [{{ $stats['scores_draft'] }}, {{ $stats['scores_pending'] }}, {{ $stats['scores_returned'] }}, {{ $stats['scores_approved'] }}, {{ $stats['scores_locked'] }}]
        }],
        chart: { type: 'bar', height: 240, fontFamily: 'inherit', toolbar: { show: false } },
        xaxis: { categories: ['Draft', 'Pending Review', 'Returned', 'Approved', 'Locked'], labels: { style: { fontSize: '12px' } } },
        yaxis: { labels: { style: { fontSize: '11px' } } },
        plotOptions: { bar: { borderRadius: 4, columnWidth: '45%', distributed: true } },
        colors: ['#adb5bd', '#f6a823', '#dc3545', '#28a745', '#2A2567'],
        legend: { show: false },
        dataLabels: { enabled: true, style: { fontSize: '11px', fontWeight: 600 } },
        grid: { borderColor: '#f0f0f0' }
    }).render();
})();
</script>
@endpush
