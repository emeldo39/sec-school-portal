@extends('layouts.portal')

@section('title', 'Student Transcript')
@section('page-title', 'Student Transcript')
@section('page-subtitle', $student->full_name . ' — All Terms')

@section('breadcrumb-actions')
    <a href="{{ route('admin.students.show', $student) }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2">
        <i class="ri-arrow-left-line"></i> Back to Student
    </a>
@endsection

@section('content')

{{-- Student header --}}
<div class="card shadow-1 radius-8 mb-24">
    <div class="card-body p-24 d-flex align-items-center gap-20">
        @if($student->photo)
        <img src="{{ asset('storage/' . $student->photo) }}"
             class="rounded-circle flex-shrink-0"
             style="width:64px; height:64px; object-fit:cover;">
        @else
        <div class="rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center"
             style="width:64px; height:64px; background:#EEF0FF;">
            <i class="ri-user-line text-primary-600" style="font-size:28px;"></i>
        </div>
        @endif
        <div>
            <h5 class="fw-bold mb-4" style="color:#2A2567;">{{ $student->full_name }}</h5>
            <div class="d-flex flex-wrap gap-12">
                <span class="text-sm text-secondary-light">
                    <i class="ri-building-line me-1"></i>{{ $student->schoolClass?->name ?? '—' }}
                </span>
                <span class="text-sm text-secondary-light">
                    <i class="ri-barcode-line me-1"></i>{{ $student->admission_number }}
                </span>
                <span class="text-sm text-secondary-light">
                    <i class="ri-user-line me-1"></i>{{ ucfirst($student->gender) }}
                </span>
                @if($student->date_of_birth)
                <span class="text-sm text-secondary-light">
                    <i class="ri-calendar-line me-1"></i>{{ $student->date_of_birth->format('d M Y') }}
                </span>
                @endif
            </div>
        </div>
        <div class="ms-auto text-end">
            <p class="text-xs text-secondary-light mb-4">TERMS ON RECORD</p>
            <p class="text-2xl fw-bold mb-0" style="color:#2A2567;">{{ $terms->count() }}</p>
        </div>
    </div>
</div>

@if($terms->isEmpty())
<div class="card shadow-1 radius-8 p-40 text-center">
    <i class="ri-inbox-line d-block mb-12" style="font-size:48px; color:#c5c5d9;"></i>
    <p class="text-sm text-secondary-light mb-0">No approved scores found for this student.</p>
</div>
@else

{{-- Per-term tables --}}
@foreach($terms as $term)
@php
    $termScores = collect($pivot[$term->id] ?? []);
    $validScores = $termScores->filter();
    $termTotal   = $validScores->sum('total_score');
    $termAvg     = $validScores->count() > 0 ? round($termTotal / $validScores->count(), 1) : null;
@endphp
<div class="card shadow-1 radius-8 mb-20">
    <div class="card-header py-14 px-24 border-bottom d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-10">
            <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8" style="background:#EEF0FF;">
                <i class="ri-calendar-line" style="font-size:14px; color:#2A2567;"></i>
            </span>
            <div>
                <h6 class="fw-semibold mb-0" style="color:#2A2567;">{{ $term->name }}</h6>
                <p class="text-xs text-secondary-light mb-0">{{ $term->academic_year }}</p>
            </div>
        </div>
        @if($termAvg !== null)
        <div class="text-end">
            <p class="text-xs text-secondary-light mb-1">Term Average</p>
            <p class="fw-bold text-sm mb-0" style="color:#2A2567;">{{ $termAvg }}<span class="text-secondary-light fw-normal">/100</span></p>
        </div>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-24 py-10 text-sm" style="width:40%;">Subject</th>
                        <th class="px-16 py-10 text-sm text-center">CA (/60)</th>
                        <th class="px-16 py-10 text-sm text-center">Exam (/40)</th>
                        <th class="px-16 py-10 text-sm text-center">Total (/100)</th>
                        <th class="px-16 py-10 text-sm text-center">Grade</th>
                        <th class="px-16 py-10 text-sm">Remark</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $subject)
                    @php $score = $pivot[$term->id][$subject->id] ?? null; @endphp
                    <tr>
                        <td class="px-24 py-10 text-sm fw-medium">{{ $subject->name }}</td>
                        @if($score)
                        <td class="px-16 py-10 text-sm text-center">{{ $score->ca_total ?? '—' }}</td>
                        <td class="px-16 py-10 text-sm text-center">{{ $score->exam_score ?? '—' }}</td>
                        <td class="px-16 py-10 text-sm text-center fw-bold" style="color:#2A2567;">{{ $score->total_score ?? '—' }}</td>
                        <td class="px-16 py-10 text-sm text-center">
                            @if($score->grade)
                            <span class="badge px-8 py-4 radius-4 text-xs
                                {{ in_array($score->grade, ['A','A1']) ? 'bg-success-100 text-success-600' :
                                   (in_array($score->grade, ['B2','B3','C4','C5','C6']) ? 'bg-primary-100 text-primary-600' :
                                   (in_array($score->grade, ['D7','E8']) ? 'bg-warning-100 text-warning-600' : 'bg-danger-100 text-danger-600')) }}">
                                {{ $score->grade }}
                            </span>
                            @else
                            <span class="text-secondary-light">—</span>
                            @endif
                        </td>
                        <td class="px-16 py-10 text-xs text-secondary-light">{{ $score->subject_remark ?? '' }}</td>
                        @else
                        <td class="px-16 py-10 text-sm text-center text-secondary-light">—</td>
                        <td class="px-16 py-10 text-sm text-center text-secondary-light">—</td>
                        <td class="px-16 py-10 text-sm text-center text-secondary-light">—</td>
                        <td class="px-16 py-10 text-sm text-center text-secondary-light">—</td>
                        <td class="px-16 py-10 text-sm text-secondary-light">—</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
                @if($validScores->count() > 0)
                <tfoot class="bg-neutral-50">
                    <tr>
                        <td class="px-24 py-10 text-sm fw-bold" style="color:#2A2567;">Term Summary</td>
                        <td colspan="2" class="px-16 py-10 text-sm text-center text-secondary-light">{{ $validScores->count() }} subject{{ $validScores->count() !== 1 ? 's' : '' }}</td>
                        <td class="px-16 py-10 text-sm text-center fw-bold" style="color:#2A2567;">{{ $termAvg }}/100 avg</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endforeach

@endif

@endsection
