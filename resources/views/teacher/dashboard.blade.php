@extends('layouts.portal')

@section('title', 'Teacher Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Welcome back, ' . auth()->user()->name)

@section('breadcrumb-actions')
@if($currentTerm)
<span class="text-sm text-secondary-light">
    Current Term: <strong>{{ $currentTerm->name }} {{ $currentTerm->academic_year }}</strong>
</span>
@else
<span class="text-sm text-danger">No active term set</span>
@endif
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

    {{-- My Classes --}}
    <div class="col-6 col-md-4 col-lg-3 mt-4">
        <a href="{{ route('teacher.scores.index') }}" class="text-decoration-none d-block h-100">
            <div class="card shadow-1 dash-stat-card" style="background:linear-gradient(135deg,#f0f1ff 0%,#fff 60%);">
                <div class="stat-accent" style="background:#2A2567;"></div>
                <div class="d-flex align-items-start justify-content-between mb-14">
                    <div class="stat-icon" style="background:#EEF0FF;">
                        <iconify-icon icon="ph:chalkboard" style="font-size:22px;color:#2A2567;"></iconify-icon>
                    </div>
                    <i class="ri-arrow-right-up-line text-secondary-light" style="font-size:14px;margin-top:4px;"></i>
                </div>
                <p class="dash-stat-label text-secondary-light mb-6">My Classes</p>
                <p class="dash-stat-value mb-0">{{ $stats['classes'] }}</p>
            </div>
        </a>
    </div>

    {{-- My Subjects --}}
    <div class="col-6 col-md-4 col-lg-3 mt-4">
        <a href="{{ route('teacher.scores.index') }}" class="text-decoration-none d-block h-100">
            <div class="card shadow-1 dash-stat-card" style="background:linear-gradient(135deg,#f0fff4 0%,#fff 60%);">
                <div class="stat-accent" style="background:#28a745;"></div>
                <div class="d-flex align-items-start justify-content-between mb-14">
                    <div class="stat-icon" style="background:#e6f9ed;">
                        <iconify-icon icon="ph:book-open" style="font-size:22px;color:#28a745;"></iconify-icon>
                    </div>
                    <i class="ri-arrow-right-up-line text-secondary-light" style="font-size:14px;margin-top:4px;"></i>
                </div>
                <p class="dash-stat-label text-secondary-light mb-6">My Subjects</p>
                <p class="dash-stat-value mb-0">{{ $stats['subjects'] }}</p>
            </div>
        </a>
    </div>

    {{-- My Students --}}
    <div class="col-6 col-md-4 col-lg-3 mt-4">
        <a href="{{ route('teacher.students') }}" class="text-decoration-none d-block h-100">
            <div class="card shadow-1 dash-stat-card" style="background:linear-gradient(135deg,#f0faff 0%,#fff 60%);">
                <div class="stat-accent" style="background:#0dcaf0;"></div>
                <div class="d-flex align-items-start justify-content-between mb-14">
                    <div class="stat-icon" style="background:#e0f6fd;">
                        <iconify-icon icon="ph:student" style="font-size:22px;color:#0dcaf0;"></iconify-icon>
                    </div>
                    <i class="ri-arrow-right-up-line text-secondary-light" style="font-size:14px;margin-top:4px;"></i>
                </div>
                <p class="dash-stat-label text-secondary-light mb-6">My Students</p>
                <p class="dash-stat-value mb-0">{{ $stats['students'] }}</p>
            </div>
        </a>
    </div>

    {{-- Awaiting Review --}}
    <div class="col-6 col-md-4 col-lg-3 mt-4">
        <a href="{{ route('teacher.scores.index') }}" class="text-decoration-none d-block h-100">
            <div class="card shadow-1 dash-stat-card" style="background:linear-gradient(135deg,#fffbf0 0%,#fff 60%);">
                <div class="stat-accent" style="background:#f6a823;"></div>
                <div class="d-flex align-items-start justify-content-between mb-14">
                    <div class="stat-icon" style="background:#fff3d6;">
                        <iconify-icon icon="ph:clock-countdown" style="font-size:22px;color:#f6a823;"></iconify-icon>
                    </div>
                    @if($stats['pending'] > 0)
                    <span class="badge"
                        style="background:#fff3d6;color:#f6a823;font-size:10px;padding:3px 7px;border-radius:6px;">Pending</span>
                    @else
                    <i class="ri-arrow-right-up-line text-secondary-light" style="font-size:14px;margin-top:4px;"></i>
                    @endif
                </div>
                <p class="dash-stat-label text-secondary-light mb-6">Awaiting Review</p>
                <p class="dash-stat-value mb-0">{{ $stats['pending'] }}</p>
            </div>
        </a>
    </div>

    {{-- Returned for Revision (always show) --}}
    <div class="col-6 col-md-4 col-lg-3 mt-4">
        <a href="{{ route('teacher.scores.index') }}" class="text-decoration-none d-block h-100">
            <div class="card shadow-1 dash-stat-card"
                style="background:linear-gradient(135deg,#fff5f5 0%,#fff 60%);{{ $stats['returned'] > 0 ? 'border:1px solid rgba(220,53,69,.25) !important;' : '' }}">
                <div class="stat-accent" style="background:#dc3545;"></div>
                <div class="d-flex align-items-start justify-content-between mb-14">
                    <div class="stat-icon" style="background:#fde8e8;">
                        <iconify-icon icon="ph:arrow-u-up-left" style="font-size:22px;color:#dc3545;"></iconify-icon>
                    </div>
                    @if($stats['returned'] > 0)
                    <span class="badge"
                        style="background:#fde8e8;color:#dc3545;font-size:10px;padding:3px 7px;border-radius:6px;">Action
                        needed</span>
                    @else
                    <i class="ri-check-line text-secondary-light" style="font-size:14px;margin-top:4px;"></i>
                    @endif
                </div>
                <p class="dash-stat-label text-secondary-light mb-6">Returned</p>
                <p class="dash-stat-value mb-0" style="{{ $stats['returned'] > 0 ? 'color:#dc3545;' : '' }}">
                    {{ $stats['returned'] }}</p>
            </div>
        </a>
    </div>

</div>

{{-- Charts Row --}}
<div class="row gy-4 mb-24">
    {{-- Score Status Donut --}}
    <div class="col-lg-4">
        <div class="card shadow-1 radius-8 p-20 h-100">
            <h6 class="fw-semibold mb-0">My Score Status</h6>
            <p class="text-xs text-secondary-light mt-2 mb-4">Breakdown of your submitted scores</p>
            <div id="teacherScoreDonut"></div>
        </div>
    </div>
    {{-- Subject Assignment Load --}}
    <div class="col-lg-8">
        <div class="card shadow-1 radius-8 p-20 h-100">
            <h6 class="fw-semibold mb-0">Assignment Load</h6>
            <p class="text-xs text-secondary-light mt-2 mb-4">Students per assigned class</p>
            <div id="teacherAssignmentChart"></div>
        </div>
    </div>
</div>

<div class="row gy-4">
    {{-- My Assignments --}}
    <div class="col-lg-5">
        <div class="card shadow-1 radius-8 d-flex flex-column" style="height:420px;">
            <div
                class="card-header py-16 px-24 border-bottom d-flex justify-content-between align-items-center flex-shrink-0">
                <div>
                    <h6 class="fw-semibold mb-0">My Assignments</h6>
                    @php $grouped = $assignments->groupBy(fn($a) => $a->schoolClass->name ?? '—') @endphp
                    <p class="text-xs text-secondary-light mb-0 mt-2">{{ $grouped->count() }}
                        class{{ $grouped->count() !== 1 ? 'es' : '' }}</p>
                </div>
                <a href="{{ route('teacher.scores.index') }}" class="btn btn-sm btn-outline-primary px-12 py-4 text-xs">
                    <i class="ri-pencil-line me-1"></i> Enter Scores
                </a>
            </div>
            <div class="flex-grow-1" style="overflow-y:auto; overflow-x:hidden;">
                @forelse($grouped->take(10) as $className => $classAssignments)
                <div class="px-20 py-14 border-bottom pt-8 pb-8">
                    <p class="text-xs fw-bold mb-8 text-uppercase" style="color:#2A2567; letter-spacing:.5px;">
                        <i class="ri-building-line me-1 text-secondary-light"></i>{{ $className }}
                    </p>
                    <div class="d-flex flex-wrap gap-6">
                        @foreach($classAssignments->unique('subject_id') as $a)
                        <span class="badge bg-primary-100 text-primary-600 px-8 py-4 radius-4 text-xs fw-medium">
                            {{ $a->subject->name ?? '—' }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @empty
                <div class="d-flex flex-column align-items-center justify-content-center h-100 py-32 text-center">
                    <i class="ri-book-open-line d-block mb-8 text-secondary-light" style="font-size:2rem;"></i>
                    <p class="text-sm text-secondary-light mb-0">No assignments yet.</p>
                    <p class="text-xs text-secondary-light mb-0">Contact your administrator.</p>
                </div>
                @endforelse
            </div>
            @if($grouped->count() > 10)
            <div class="px-20 py-10 border-top flex-shrink-0 text-center">
                <a href="{{ route('teacher.scores.index') }}" class="text-xs text-primary-600">
                    View all {{ $grouped->count() }} classes <i class="ri-arrow-right-line"></i>
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- Recent Score Submissions --}}
    <div class="col-lg-7">
        <div class="card shadow-1 radius-8 d-flex flex-column" style="height:420px;">
            <div
                class="card-header py-16 px-24 border-bottom d-flex justify-content-between align-items-center flex-shrink-0">
                <div>
                    <h6 class="fw-semibold mb-0">Recent Score Submissions</h6>
                    <p class="text-xs text-secondary-light mb-0 mt-2">Last 10 entries</p>
                </div>
                <a href="{{ route('teacher.scores.index') }}"
                    class="btn btn-sm btn-outline-secondary px-12 py-4 text-xs">
                    <i class="ri-eye-line me-1"></i> View All
                </a>
            </div>
            <div class="flex-grow-1" style="overflow:auto;">
                <table class="table table-sm mb-0" style="min-width:500px;">
                    <thead style="position:sticky; top:0; z-index:1;">
                        <tr class="bg-neutral-50">
                            <th class="px-20 py-10 text-sm fw-semibold" style="color:#2A2567;">Student</th>
                            <th class="px-12 py-10 text-sm fw-semibold" style="color:#2A2567;">Subject</th>
                            <th class="px-12 py-10 text-sm fw-semibold text-center" style="color:#2A2567;">Score</th>
                            <th class="px-12 py-10 text-sm fw-semibold" style="color:#2A2567;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentScores as $score)
                        <tr>
                            <td class="px-20 py-10 text-sm fw-medium" style="color:#2A2567;">
                                {{ $score->student->full_name ?? '—' }}</td>
                            <td class="px-12 py-10 text-sm text-secondary-light">{{ $score->subject->name ?? '—' }}</td>
                            <td class="px-12 py-10 text-sm fw-bold text-center" style="color:#2A2567;">
                                {{ $score->total_score ?? '—' }}</td>
                            <td class="px-12 py-10">
                                @php
                                $map=['submitted'=>['warning','Submitted'],'approved'=>['success','Approved'],'returned'=>['danger','Returned'],'locked'=>['primary','Locked'],'draft'=>['secondary','Draft']];
                                $s=$map[$score->status]??['secondary',$score->status]; @endphp
                                <span
                                    class="badge bg-{{ $s[0] }}-100 text-{{ $s[0] }}-600 px-8 py-4 radius-4 text-xs fw-medium">{{ $s[1] }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-40 text-secondary-light text-sm">
                                <i class="ri-inbox-line d-block mb-8" style="font-size:2rem;opacity:.4;"></i>
                                No scores submitted yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-20 py-10 border-top flex-shrink-0 text-center">
                <a href="{{ route('teacher.scores.index') }}" class="text-xs text-secondary-light">
                    Showing last 10 entries &middot; <span class="text-primary-600">View all scores <i
                            class="ri-arrow-right-line"></i></span>
                </a>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="col-12">
        <div class="card shadow-1 radius-8">
            <div class="card-header py-16 px-24 border-bottom">
                <h6 class="fw-semibold mb-0">Quick Actions</h6>
            </div>
            <div class="card-body p-20">
                <div class="row gy-12">
                    <div class="col-6 col-md-3">
                        <a href="{{ route('teacher.scores.index') }}"
                            class="d-flex flex-column align-items-center gap-8 p-16 radius-8 text-decoration-none border text-center"
                            style="background:#F4F4FF;">
                            <span
                                class="w-44-px h-44-px radius-8 bg-primary-100 d-flex align-items-center justify-content-center">
                                <iconify-icon icon="ph:pencil-simple" class="text-primary-600 text-xl"></iconify-icon>
                            </span>
                            <p class="text-sm fw-semibold mb-0" style="color:#2A2567;">Enter Scores</p>
                            @if($stats['pending'] > 0)
                            <span
                                class="badge bg-warning-100 text-warning-600 text-xs px-6 py-2">{{ $stats['pending'] }}
                                awaiting review</span>
                            @endif
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('teacher.attendance.index') }}"
                            class="d-flex flex-column align-items-center gap-8 p-16 radius-8 text-decoration-none border text-center"
                            style="background:#F0FFF4;">
                            <span
                                class="w-44-px h-44-px radius-8 bg-success-100 d-flex align-items-center justify-content-center">
                                <iconify-icon icon="ph:check-square" class="text-success-600 text-xl"></iconify-icon>
                            </span>
                            <p class="text-sm fw-semibold mb-0" style="color:#2A2567;">Mark Attendance</p>
                            <span class="text-xs text-secondary-light">Today: {{ now()->format('d M') }}</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('teacher.results.index') }}"
                            class="d-flex flex-column align-items-center gap-8 p-16 radius-8 text-decoration-none border text-center"
                            style="background:#FFF8E1;">
                            <span
                                class="w-44-px h-44-px radius-8 bg-warning-100 d-flex align-items-center justify-content-center">
                                <iconify-icon icon="ph:certificate" class="text-warning-600 text-xl"></iconify-icon>
                            </span>
                            <p class="text-sm fw-semibold mb-0" style="color:#2A2567;">Results</p>
                            <span class="text-xs text-secondary-light">Generate &amp; publish</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('teacher.attendance.report') }}"
                            class="d-flex flex-column align-items-center gap-8 p-16 radius-8 text-decoration-none border text-center"
                            style="background:#F0F8FF;">
                            <span
                                class="w-44-px h-44-px radius-8 bg-info-100 d-flex align-items-center justify-content-center">
                                <iconify-icon icon="ph:chart-bar" class="text-info-600 text-xl"></iconify-icon>
                            </span>
                            <p class="text-sm fw-semibold mb-0" style="color:#2A2567;">Attendance Report</p>
                            <span class="text-xs text-secondary-light">View summary</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Announcements --}}
    @if($announcements->isNotEmpty())
    <div class="col-12">
        <div class="card shadow-1 radius-8">
            <div class="card-header py-16 px-24 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="fw-semibold mb-0">Recent Announcements</h6>
                    <p class="text-xs text-secondary-light mb-0 mt-2">Showing last {{ $announcements->count() }}
                        announcement{{ $announcements->count() !== 1 ? 's' : '' }}</p>
                </div>
                <a href="{{ route('teacher.announcements') }}"
                    class="btn btn-sm btn-outline-primary px-12 py-4 text-xs">
                    <i class="ri-megaphone-line me-1"></i> View All
                </a>
            </div>
            <div class="card-body p-16">
                <div class="d-flex flex-column gap-12">
                    @foreach($announcements as $ann)
                    <div class="d-flex align-items-start gap-16 p-16 radius-8"
                        style="background:#F8F9FF; border:1px solid #EDEEF8;">
                        <div
                            class="w-40-px h-40-px radius-8 bg-primary-100 d-flex align-items-center justify-content-center flex-shrink-0">
                            <iconify-icon icon="ph:megaphone" class="text-primary-600 text-lg"></iconify-icon>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <p class="text-sm fw-semibold mb-4" style="color:#2A2567;">{{ $ann->title }}</p>
                            @if($ann->body)
                            <p class="text-xs text-secondary-light mb-6"
                                style="overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">
                                {{ $ann->body }}</p>
                            @endif
                            <span class="text-xs text-secondary-light">
                                <i class="ri-time-line me-1"></i>{{ $ann->created_at->diffForHumans() }}
                            </span>
                        </div>
                        @if($ann->target !== 'all')
                        <span
                            class="badge bg-info-100 text-info-600 px-8 py-4 radius-4 text-xs flex-shrink-0 text-capitalize">{{ $ann->target }}</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@php
$chartClassLabels = $assignments->groupBy('class_id')->map(fn($g) => $g->first()->schoolClass->name ?? '—')->values();
$chartClassCounts = $assignments->groupBy('class_id')->map(function($g) {
return \App\Models\Student::where('class_id', $g->first()->class_id)->where('status','active')->count();
})->values();
$teacherChartData = json_encode([
'donut' => [$stats['pending'], $stats['approved'], $stats['locked'], $stats['returned']],
'classLabels' => $chartClassLabels->values(),
'classCounts' => $chartClassCounts->values(),
]);
@endphp
<span id="teacherChartData" data-charts='{!! $teacherChartData !!}' style="display:none;"></span>

@endsection

@push('scripts')
<script>
(function() {
    var d = JSON.parse(document.getElementById('teacherChartData').dataset.charts);

    new ApexCharts(document.querySelector('#teacherScoreDonut'), {
        series: d.donut,
        labels: ['Submitted', 'Approved', 'Locked', 'Returned'],
        colors: ['#f6a823', '#28a745', '#2A2567', '#dc3545'],
        chart: {
            type: 'donut',
            height: 240,
            fontFamily: 'inherit'
        },
        legend: {
            position: 'bottom',
            fontSize: '12px'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '65%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '12px',
                            fontWeight: 600,
                            color: '#2A2567'
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 0
        }
    }).render();

    new ApexCharts(document.querySelector('#teacherAssignmentChart'), {
        series: [{
            name: 'Students',
            data: d.classCounts
        }],
        chart: {
            type: 'bar',
            height: 220,
            fontFamily: 'inherit',
            toolbar: {
                show: false
            }
        },
        xaxis: {
            categories: d.classLabels,
            labels: {
                style: {
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    fontSize: '11px'
                }
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                columnWidth: '40%',
                distributed: true
            }
        },
        colors: ['#2A2567', '#28a745', '#0dcaf0', '#f6a823', '#6A1B9A', '#dc3545'],
        legend: {
            show: false
        },
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '11px',
                fontWeight: 600
            }
        },
        grid: {
            borderColor: '#f0f0f0'
        }
    }).render();
})();
</script>
@endpush