@extends('layouts.portal')

@section('title', $class->name . ' Report')
@section('page-title', $class->name . ' — Class Report')
@section('page-subtitle', $term ? $term->name . ' ' . $term->academic_year : 'All Terms')

@section('breadcrumb-actions')
    <div class="d-flex gap-8 align-items-center">
        <form method="GET" class="d-flex gap-8 align-items-center">
            <select name="term_id" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All Terms</option>
                @foreach($terms as $t)
                    <option value="{{ $t->id }}" {{ request('term_id') == $t->id ? 'selected' : '' }}>
                        {{ $t->name }} {{ $t->academic_year }}
                    </option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.reports.export.class-csv', $class) . (request('term_id') ? '?term_id='.request('term_id') : '') }}"
           class="btn btn-success btn-sm">
            <i class="ri-file-excel-2-line me-1"></i>Export CSV
        </a>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>
@endsection

@section('content')
@if($subjects->isNotEmpty() && $students->isNotEmpty())
{{-- Subject Performance Charts --}}
<div class="row gy-4 mb-20">
    <div class="col-lg-8">
        <div class="card shadow-1 radius-8 p-20">
            <h6 class="fw-semibold mb-0">Subject Averages</h6>
            <p class="text-xs text-secondary-light mt-2 mb-4">Average score per subject (approved/locked scores)</p>
            <div id="subjectAvgChart"></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-1 radius-8 p-20">
            <h6 class="fw-semibold mb-0">Grade Distribution</h6>
            <p class="text-xs text-secondary-light mt-2 mb-4">Pass vs fail across all subjects</p>
            <div id="gradeDistChart"></div>
        </div>
    </div>
</div>
@endif

<div class="card shadow-1 radius-8">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0 text-sm" style="min-width:900px;">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-16 py-12" style="min-width:180px;">Student</th>
                        @foreach($subjects as $subject)
                        <th class="px-12 py-12 text-center" style="min-width:80px;">{{ $subject->name }}</th>
                        @endforeach
                        <th class="px-12 py-12 text-center" style="min-width:60px;">Total</th>
                        <th class="px-12 py-12 text-center" style="min-width:60px;">Average</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    @php
                        $studentScores = $pivot[$student->id] ?? [];
                        $totalSum = 0; $countSubjects = 0;
                        foreach($studentScores as $s) { if($s && $s->total_score !== null) { $totalSum += $s->total_score; $countSubjects++; } }
                        $average = $countSubjects > 0 ? round($totalSum / $countSubjects, 1) : null;
                    @endphp
                    <tr>
                        <td class="px-16 py-10 fw-medium">{{ $student->full_name }}</td>
                        @foreach($subjects as $subject)
                        @php $sc = $studentScores[$subject->id] ?? null; @endphp
                        <td class="px-12 py-10 text-center">
                            @if($sc)
                                <span class="{{ $sc->total_score >= 50 ? 'text-success-600' : 'text-danger-600' }} fw-semibold">
                                    {{ $sc->total_score }}
                                </span>
                            @else
                                <span class="text-secondary-light">—</span>
                            @endif
                        </td>
                        @endforeach
                        <td class="px-12 py-10 text-center fw-semibold">{{ $totalSum > 0 ? $totalSum : '—' }}</td>
                        <td class="px-12 py-10 text-center">
                            @if($average !== null)
                                <span class="{{ $average >= 50 ? 'text-success-600' : 'text-danger-600' }} fw-semibold">
                                    {{ $average }}
                                </span>
                            @else
                                <span class="text-secondary-light">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $subjects->count() + 3 }}" class="text-center py-24 text-secondary-light">
                            No students in this class.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($subjects->isNotEmpty() && $students->isNotEmpty())
<script>
(function () {
    @php
        $chartSubjectNames = $subjects->values()->map(fn($s) => $s->name);
        $chartSubjectAvgs  = $subjects->values()->map(function($subject) use ($students, $pivot) {
            $vals = $students->map(fn($st) => optional($pivot[$st->id][$subject->id] ?? null)->total_score)->filter()->values();
            return $vals->count() > 0 ? round($vals->avg(), 1) : 0;
        });
        $chartPassCount = 0; $chartFailCount = 0;
        foreach ($students as $st) {
            foreach ($subjects as $subj) {
                $sc = $pivot[$st->id][$subj->id] ?? null;
                if ($sc && $sc->total_score !== null) {
                    $sc->total_score >= 50 ? $chartPassCount++ : $chartFailCount++;
                }
            }
        }
    @endphp

    new ApexCharts(document.querySelector('#subjectAvgChart'), {
        series: [{ name: 'Average', data: {!! $chartSubjectAvgs->toJson() !!} }],
        chart: { type: 'bar', height: 260, fontFamily: 'inherit', toolbar: { show: false } },
        xaxis: { categories: {!! $chartSubjectNames->toJson() !!}, labels: { style: { fontSize: '11px' }, rotate: -30 } },
        yaxis: { min: 0, max: 100, labels: { formatter: v => v + '/100', style: { fontSize: '11px' } } },
        plotOptions: { bar: { borderRadius: 4, columnWidth: '50%', distributed: true } },
        colors: {!! $chartSubjectAvgs->map(fn($a) => $a >= 50 ? '#28a745' : '#dc3545')->toJson() !!},
        legend: { show: false },
        dataLabels: { enabled: true, style: { fontSize: '10px', fontWeight: 600 } },
        grid: { borderColor: '#f0f0f0' },
        annotations: { yaxis: [{ y: 50, borderColor: '#f6a823', borderWidth: 2, strokeDashArray: 4, label: { text: 'Pass mark', style: { color: '#f6a823', fontSize: '10px' } } }] }
    }).render();

    new ApexCharts(document.querySelector('#gradeDistChart'), {
        series: [{{ $chartPassCount }}, {{ $chartFailCount }}],
        labels: ['Pass (≥50)', 'Fail (<50)'],
        colors: ['#28a745', '#dc3545'],
        chart: { type: 'donut', height: 260, fontFamily: 'inherit' },
        legend: { position: 'bottom', fontSize: '12px' },
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Scores', fontSize: '12px', fontWeight: 600, color: '#2A2567' } } } } },
        dataLabels: { enabled: false },
        stroke: { width: 0 }
    }).render();
})();
</script>
@endif
@endpush
