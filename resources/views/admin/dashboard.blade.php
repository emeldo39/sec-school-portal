@extends('layouts.portal')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name)

@section('breadcrumb-actions')
<span class="text-sm text-secondary-light">
    @if($currentTerm)
    Current Term: <strong>{{ $currentTerm->name }} {{ $currentTerm->academic_year }}</strong>
    @else
    <span class="text-danger">No active term set</span>
    @endif
</span>
@endsection

@section('content')

<style>
.dash-stat-card {
    border-radius: 12px;
    padding: 20px;
    height: 100%;
    transition: transform .18s ease, box-shadow .18s ease;
    position: relative;
    overflow: hidden;
    border: none;
}

.dash-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(42, 37, 103, .12) !important;
}

.dash-stat-card .stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.dash-stat-card .stat-accent {
    position: absolute;
    top: 0;
    right: 0;
    width: 80px;
    height: 80px;
    border-radius: 0 12px 0 80px;
    opacity: .08;
}

.dash-stat-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .6px;
    text-transform: uppercase;
}

.dash-stat-value {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1.1;
    color: #2A2567;
}

.dash-stat-sub {
    font-size: 11px;
    margin-top: 4px;
}
</style>

{{-- Stat Cards --}}
<div class="row g-16 mb-24">

    {{-- Active Students --}}
    <div class="col-6 col-md-4 col-lg-3 mt-4">
        <a href="{{ route('admin.students.index') }}" class="text-decoration-none d-block h-100">
            <div class="card shadow-1 dash-stat-card" style="background:linear-gradient(135deg,#f0f1ff 0%,#fff 60%);">
                <div class="stat-accent" style="background:#2A2567;"></div>
                <div class="d-flex align-items-start justify-content-between mb-14">
                    <div class="stat-icon" style="background:#EEF0FF;">
                        <iconify-icon icon="ph:student" style="font-size:22px;color:#2A2567;"></iconify-icon>
                    </div>
                    <i class="ri-arrow-right-up-line text-secondary-light" style="font-size:14px;margin-top:4px;"></i>
                </div>
                <p class="dash-stat-label text-secondary-light mb-6">Active Students</p>
                <p class="dash-stat-value mb-0">{{ number_format($stats['students']) }}</p>
            </div>
        </a>
    </div>

    {{-- Active Teachers --}}
    <div class="col-6 col-md-4 col-lg-3 mt-4">
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none d-block h-100">
            <div class="card shadow-1 dash-stat-card" style="background:linear-gradient(135deg,#f0fff4 0%,#fff 60%);">
                <div class="stat-accent" style="background:#28a745;"></div>
                <div class="d-flex align-items-start justify-content-between mb-14">
                    <div class="stat-icon" style="background:#e6f9ed;">
                        <iconify-icon icon="ph:chalkboard-teacher" style="font-size:22px;color:#28a745;"></iconify-icon>
                    </div>
                    <i class="ri-arrow-right-up-line text-secondary-light" style="font-size:14px;margin-top:4px;"></i>
                </div>
                <p class="dash-stat-label text-secondary-light mb-6">Active Teachers</p>
                <p class="dash-stat-value mb-0">{{ number_format($stats['teachers']) }}</p>
                <p class="dash-stat-sub text-secondary-light mb-0">{{ $stats['form_teachers'] }} form
                    teacher{{ $stats['form_teachers'] !== 1 ? 's' : '' }}</p>
            </div>
        </a>
    </div>

    {{-- Pending Scores --}}
    <div class="col-6 col-md-4 col-lg-3 mt-4">
        <a href="{{ route('admin.scores.index') }}" class="text-decoration-none d-block h-100">
            <div class="card shadow-1 dash-stat-card" style="background:linear-gradient(135deg,#fffbf0 0%,#fff 60%);">
                <div class="stat-accent" style="background:#f6a823;"></div>
                <div class="d-flex align-items-start justify-content-between mb-14">
                    <div class="stat-icon" style="background:#fff3d6;">
                        <iconify-icon icon="ph:clock-countdown" style="font-size:22px;color:#f6a823;"></iconify-icon>
                    </div>
                    @if($stats['returned_scores'] > 0)
                    <span class="badge"
                        style="background:#fde8e8;color:#dc3545;font-size:10px;padding:3px 7px;border-radius:6px;">
                        {{ $stats['returned_scores'] }} returned
                    </span>
                    @else
                    <i class="ri-arrow-right-up-line text-secondary-light" style="font-size:14px;margin-top:4px;"></i>
                    @endif
                </div>
                <p class="dash-stat-label text-secondary-light mb-6">Pending Scores</p>
                <p class="dash-stat-value mb-0">{{ number_format($stats['pending_scores']) }}</p>
            </div>
        </a>
    </div>

    {{-- Unread Messages --}}
    <div class="col-6 col-md-4 col-lg-3 mt-4">
        <a href="{{ route('admin.messages.index') }}" class="text-decoration-none d-block h-100">
            <div class="card shadow-1 dash-stat-card" style="background:linear-gradient(135deg,#f0faff 0%,#fff 60%);">
                <div class="stat-accent" style="background:#0dcaf0;"></div>
                <div class="d-flex align-items-start justify-content-between mb-14">
                    <div class="stat-icon" style="background:#e0f6fd;">
                        <iconify-icon icon="ph:envelope-simple" style="font-size:22px;color:#0dcaf0;"></iconify-icon>
                    </div>
                    @if($stats['unread_messages'] > 0)
                    <span class="badge"
                        style="background:#e0f6fd;color:#0dcaf0;font-size:10px;padding:3px 7px;border-radius:6px;">
                        New
                    </span>
                    @else
                    <i class="ri-arrow-right-up-line text-secondary-light" style="font-size:14px;margin-top:4px;"></i>
                    @endif
                </div>
                <p class="dash-stat-label text-secondary-light mb-6">Unread Messages</p>
                <p class="dash-stat-value mb-0">{{ number_format($stats['unread_messages']) }}</p>
            </div>
        </a>
    </div>

    {{-- Total Classes --}}
    <div class="col-6 col-md-4 col-lg-3 mt-4">
        <a href="{{ route('admin.classes.index') }}" class="text-decoration-none d-block h-100">
            <div class="card shadow-1 dash-stat-card" style="background:linear-gradient(135deg,#faf0ff 0%,#fff 60%);">
                <div class="stat-accent" style="background:#6A1B9A;"></div>
                <div class="d-flex align-items-start justify-content-between mb-14">
                    <div class="stat-icon" style="background:#f3e5f5;">
                        <iconify-icon icon="ph:chalkboard" style="font-size:22px;color:#6A1B9A;"></iconify-icon>
                    </div>
                    <i class="ri-arrow-right-up-line text-secondary-light" style="font-size:14px;margin-top:4px;"></i>
                </div>
                <p class="dash-stat-label text-secondary-light mb-6">Total Classes</p>
                <p class="dash-stat-value mb-0">{{ number_format($stats['classes']) }}</p>
            </div>
        </a>
    </div>

    {{-- Published Results --}}
    <div class="col-6 col-md-4 col-lg-3 mt-4">
        <a href="{{ route('admin.publications.index') }}" class="text-decoration-none d-block h-100">
            <div class="card shadow-1 dash-stat-card" style="background:linear-gradient(135deg,#f0f1ff 0%,#fff 60%);">
                <div class="stat-accent" style="background:#2A2567;"></div>
                <div class="d-flex align-items-start justify-content-between mb-14">
                    <div class="stat-icon" style="background:#EEF0FF;">
                        <iconify-icon icon="ph:certificate" style="font-size:22px;color:#2A2567;"></iconify-icon>
                    </div>
                    <i class="ri-arrow-right-up-line text-secondary-light" style="font-size:14px;margin-top:4px;"></i>
                </div>
                <p class="dash-stat-label text-secondary-light mb-6">Published Results</p>
                <p class="dash-stat-value mb-0">{{ number_format($stats['published_results']) }}</p>
            </div>
        </a>
    </div>

    {{-- Current Term --}}
    <div class="col-6 col-md-4 col-lg-3 mt-4">
        @if($currentTerm)
        <div class="card shadow-1 dash-stat-card" style="background:linear-gradient(135deg,#f0fff4 0%,#fff 60%);">
            <div class="stat-accent" style="background:#28a745;"></div>
            <div class="d-flex align-items-start justify-content-between mb-14">
                <div class="stat-icon" style="background:#e6f9ed;">
                    <iconify-icon icon="ph:calendar-check" style="font-size:22px;color:#28a745;"></iconify-icon>
                </div>
                <span class="badge"
                    style="background:#e6f9ed;color:#28a745;font-size:10px;padding:3px 7px;border-radius:6px;">Active</span>
            </div>
            <p class="dash-stat-label text-secondary-light mb-6">Current Term</p>
            <p class="dash-stat-value mb-0" style="font-size:1.1rem;">{{ $currentTerm->name }}</p>
            <p class="dash-stat-sub text-secondary-light mb-0">{{ $currentTerm->academic_year }}</p>
        </div>
        @else
        <a href="{{ route('admin.terms.index') }}" class="text-decoration-none d-block h-100">
            <div class="card shadow-1 dash-stat-card"
                style="background:linear-gradient(135deg,#fffbf0 0%,#fff 60%); border:1px dashed #f6a823 !important;">
                <div class="stat-accent" style="background:#f6a823;"></div>
                <div class="d-flex align-items-start justify-content-between mb-14">
                    <div class="stat-icon" style="background:#fff3d6;">
                        <iconify-icon icon="ph:warning" style="font-size:22px;color:#f6a823;"></iconify-icon>
                    </div>
                    <span class="badge"
                        style="background:#fff3d6;color:#f6a823;font-size:10px;padding:3px 7px;border-radius:6px;">Set
                        Now</span>
                </div>
                <p class="dash-stat-label text-secondary-light mb-6">Current Term</p>
                <p class="dash-stat-value mb-0" style="font-size:1.1rem;color:#f6a823;">Not Set</p>
            </div>
        </a>
        @endif
    </div>

    {{-- Reports --}}
    <div class="col-6 col-md-4 col-lg-3 mt-4">
        <a href="{{ route('admin.reports.index') }}" class="text-decoration-none d-block h-100">
            <div class="card shadow-1 dash-stat-card" style="background:linear-gradient(135deg,#f0faff 0%,#fff 60%);">
                <div class="stat-accent" style="background:#0dcaf0;"></div>
                <div class="d-flex align-items-start justify-content-between mb-14">
                    <div class="stat-icon" style="background:#e0f6fd;">
                        <iconify-icon icon="ph:chart-bar" style="font-size:22px;color:#0dcaf0;"></iconify-icon>
                    </div>
                    <i class="ri-arrow-right-up-line text-secondary-light" style="font-size:14px;margin-top:4px;"></i>
                </div>
                <p class="dash-stat-label text-secondary-light mb-6">Reports</p>
                <p class="dash-stat-value mb-0" style="font-size:1.1rem;">View Reports</p>
                <p class="dash-stat-sub text-secondary-light mb-0">Class &amp; attendance data</p>
            </div>
        </a>
    </div>

</div>

{{-- Chart data bridge (linter-safe) --}}
@php
    $chartPipeline = json_encode(['pending' => $stats['pending_scores'], 'approved' => $stats['approved_scores'], 'locked' => $stats['locked_scores'], 'returned' => $stats['returned_scores']]);
    $chartOverview = json_encode(['students' => $stats['students'], 'teachers' => $stats['teachers'], 'classes' => $stats['classes'], 'published' => $stats['published_results']]);
@endphp
<span id="dashChartData" data-pipeline='{!! $chartPipeline !!}' data-overview='{!! $chartOverview !!}' style="display:none;"></span>

{{-- Charts Row --}}
<div class="row gy-4 mb-24">
    {{-- Score Pipeline Donut --}}
    <div class="col-lg-5">
        <div class="card shadow-1 radius-8 p-20 h-100">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h6 class="fw-semibold mb-0">Score Pipeline</h6>
                    <p class="text-xs text-secondary-light mt-2 mb-0">Current status of all scores</p>
                </div>
                <a href="{{ route('admin.scores.index') }}" class="text-xs text-primary-600">Manage</a>
            </div>
            <div id="scorePipelineChart"></div>
        </div>
    </div>
    {{-- Enrollment Overview Bar --}}
    <div class="col-lg-7">
        <div class="card shadow-1 radius-8 p-20 h-100">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h6 class="fw-semibold mb-0">School Overview</h6>
                    <p class="text-xs text-secondary-light mt-2 mb-0">Key numbers at a glance</p>
                </div>
            </div>
            <div id="schoolOverviewChart"></div>
        </div>
    </div>
</div>

<div class="row gy-4">
    {{-- Recent Score Submissions --}}
    <div class="col-lg-7">
        <div class="card shadow-1 radius-8">
            <div class="card-header d-flex align-items-center justify-content-between py-16 px-24 border-bottom">
                <h6 class="fw-semibold mb-0">Recent Score Submissions</h6>
                <a href="{{ route('admin.scores.index') }}" class="text-sm text-primary-600">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th class="px-24 py-12 text-sm">Student</th>
                                <th class="px-16 py-12 text-sm">Subject</th>
                                <th class="px-16 py-12 text-sm">Class</th>
                                <th class="px-16 py-12 text-sm">Score</th>
                                <th class="px-16 py-12 text-sm">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentScores as $score)
                            <tr>
                                <td class="px-24 py-12 text-sm">{{ $score->student->full_name ?? '—' }}</td>
                                <td class="px-16 py-12 text-sm">{{ $score->subject->name ?? '—' }}</td>
                                <td class="px-16 py-12 text-sm">{{ $score->schoolClass->name ?? '—' }}</td>
                                <td class="px-16 py-12 text-sm fw-semibold">{{ $score->total_score ?? '—' }}</td>
                                <td class="px-16 py-12">
                                    <span
                                        class="badge text-sm fw-medium bg-warning-100 text-warning-600 px-8 py-4 radius-4">
                                        Submitted
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-24 py-16 text-sm text-secondary-light text-center">No pending
                                    submissions.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Announcements --}}
    <div class="col-lg-5">
        <div class="card shadow-1 radius-8">
            <div class="card-header d-flex align-items-center justify-content-between py-16 px-24 border-bottom">
                <h6 class="fw-semibold mb-0">Recent Announcements</h6>
                <a href="{{ route('admin.announcements.index') }}" class="text-sm text-primary-600">View All</a>
            </div>
            <div class="card-body p-0">
                @forelse($announcements as $ann)
                <div class="d-flex align-items-start gap-12 p-16 border-bottom">
                    <div
                        class="w-32-px h-32-px radius-8 bg-primary-100 d-flex align-items-center justify-content-center flex-shrink-0">
                        <iconify-icon icon="ph:megaphone" class="text-primary-600"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-sm fw-semibold mb-2">{{ $ann->title }}</p>
                        <p class="text-xs text-secondary-light mb-0">
                            {{ $ann->postedBy->name ?? 'Admin' }} &middot; {{ $ann->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @empty
                <p class="text-sm text-secondary-light p-16">No announcements yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    var p = JSON.parse(document.getElementById('dashChartData').dataset.pipeline);
    var o = JSON.parse(document.getElementById('dashChartData').dataset.overview);

    new ApexCharts(document.querySelector('#scorePipelineChart'), {
        series: [p.pending, p.approved, p.locked, p.returned],
        labels: ['Pending', 'Approved', 'Locked', 'Returned'],
        colors: ['#f6a823', '#28a745', '#2A2567', '#dc3545'],
        chart: { type: 'donut', height: 260, fontFamily: 'inherit' },
        legend: { position: 'bottom', fontSize: '12px' },
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', fontSize: '13px', fontWeight: 600, color: '#2A2567' } } } } },
        dataLabels: { enabled: false },
        stroke: { width: 0 }
    }).render();

    new ApexCharts(document.querySelector('#schoolOverviewChart'), {
        series: [{ name: 'Count', data: [o.students, o.teachers, o.classes, o.published] }],
        chart: { type: 'bar', height: 240, fontFamily: 'inherit', toolbar: { show: false } },
        xaxis: { categories: ['Students', 'Teachers', 'Classes', 'Published Results'], labels: { style: { fontSize: '12px' } } },
        yaxis: { labels: { style: { fontSize: '11px' } } },
        plotOptions: { bar: { borderRadius: 4, columnWidth: '45%', distributed: true } },
        colors: ['#2A2567', '#28a745', '#6A1B9A', '#0dcaf0'],
        legend: { show: false },
        dataLabels: { enabled: true, style: { fontSize: '11px', fontWeight: 600 } },
        grid: { borderColor: '#f0f0f0' }
    }).render();
})();
</script>
@endpush