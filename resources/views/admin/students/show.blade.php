@extends('layouts.portal')

@section('title', $student->full_name)
@section('page-title', $student->full_name)
@section('page-subtitle', 'Admission No: ' . $student->admission_number)

@section('breadcrumb-actions')
    <div class="d-flex gap-8 flex-wrap">
        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary btn-sm">
            <i class="ri-pencil-line me-1"></i> Edit
        </a>
        <button type="button" class="btn btn-success btn-sm"
                data-bs-toggle="modal" data-bs-target="#resultModal">
            <i class="ri-file-pdf-2-line me-1"></i> Generate Result
        </button>
        <a href="{{ route('admin.reports.student-transcript', $student) }}" class="btn btn-outline-primary btn-sm">
            <i class="ri-list-check-3 me-1"></i> Full Transcript
        </a>
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary btn-sm">
            Back to List
        </a>
    </div>
@endsection

@section('content')
<div class="row gy-24">

    {{-- ── Profile Card ── --}}
    <div class="col-lg-4">
        <div class="card shadow-1 radius-8 p-24 text-center">
            @if($student->photo)
                <img src="{{ asset('storage/' . $student->photo) }}"
                     class="w-80-px h-80-px rounded-circle object-fit-cover mx-auto mb-16" alt="">
            @else
                <div class="w-80-px h-80-px rounded-circle bg-primary-100 d-flex align-items-center justify-content-center mx-auto mb-16"
                     style="font-size:2rem; font-weight:700; color:#2A2567;">
                    {{ strtoupper(substr($student->first_name, 0, 1)) }}
                </div>
            @endif

            <h6 class="fw-semibold mb-4">{{ $student->full_name }}</h6>
            <p class="text-sm text-secondary-light mb-8">{{ $student->schoolClass->name ?? 'Unassigned' }}</p>

            @if($student->status === 'active')
                <span class="badge bg-success-100 text-success-600 px-12 py-6">Active</span>
            @else
                <span class="badge bg-neutral-100 text-secondary-light px-12 py-6">Inactive</span>
            @endif

            <hr class="my-16">
            <ul class="list-unstyled text-start text-sm">
                <li class="d-flex justify-content-between py-6 border-bottom">
                    <span class="text-secondary-light">Admission No.</span>
                    <strong>{{ $student->admission_number }}</strong>
                </li>
                <li class="d-flex justify-content-between py-6 border-bottom">
                    <span class="text-secondary-light">Gender</span>
                    <strong class="text-capitalize">{{ $student->gender }}</strong>
                </li>
                <li class="d-flex justify-content-between py-6 border-bottom">
                    <span class="text-secondary-light">Date of Birth</span>
                    <strong>{{ $student->date_of_birth?->format('d M Y') ?? '—' }}</strong>
                </li>
                <li class="d-flex justify-content-between py-6 border-bottom">
                    <span class="text-secondary-light">Guardian</span>
                    <strong>{{ $student->guardian_name }}</strong>
                </li>
                <li class="d-flex justify-content-between py-6">
                    <span class="text-secondary-light">Guardian Phone</span>
                    <strong>{{ $student->guardian_phone }}</strong>
                </li>
            </ul>
        </div>

        {{-- ── Quick Result Generator Card ── --}}
        @if($termsWithScores->isNotEmpty())
        <div class="card shadow-1 radius-8 mt-20">
            <div class="card-header py-14 px-20 border-bottom d-flex align-items-center gap-8">
                <i class="ri-file-pdf-2-line text-danger-600 fs-5"></i>
                <h6 class="fw-semibold mb-0 text-sm">Download Result Sheet</h6>
            </div>
            <div class="card-body p-20">
                <p class="text-xs text-secondary-light mb-12">
                    Select a term to download the student's official result sheet as a PDF.
                </p>
                <form action="{{ route('admin.students.result-pdf', $student) }}" method="GET" target="_blank">
                    <div class="mb-12">
                        <label class="form-label text-sm fw-semibold mb-4">Select Term</label>
                        <select name="term_id" class="form-select form-select-sm" required>
                            <option value="">— Choose a term —</option>
                            @foreach($termsWithScores as $term)
                                <option value="{{ $term->id }}">{{ $term->name }} {{ $term->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm w-100">
                        <i class="ri-download-2-line me-1"></i> Download PDF
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="card shadow-1 radius-8 mt-20">
            <div class="card-body p-20 text-center">
                <i class="ri-file-pdf-2-line d-block mb-8" style="font-size:1.8rem;color:#ccc;"></i>
                <p class="text-xs text-secondary-light mb-0">
                    No approved results available yet.<br>Scores must be approved before a PDF can be generated.
                </p>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Score History ── --}}
    <div class="col-lg-8">
        <div class="card shadow-1 radius-8">
            <div class="card-header py-16 px-24 border-bottom d-flex align-items-center justify-content-between">
                <h6 class="fw-semibold mb-0">Score History</h6>
                @if($termsWithScores->isNotEmpty())
                <button type="button" class="btn btn-sm btn-outline-danger"
                        data-bs-toggle="modal" data-bs-target="#resultModal">
                    <i class="ri-file-pdf-2-line me-1"></i> Generate Result PDF
                </button>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th class="px-24 py-12 text-sm">Subject</th>
                                <th class="px-16 py-12 text-sm">Term</th>
                                <th class="px-16 py-12 text-sm">Total</th>
                                <th class="px-16 py-12 text-sm">Grade</th>
                                <th class="px-16 py-12 text-sm">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($student->scores->sortByDesc('created_at') as $score)
                            <tr>
                                <td class="px-24 py-10 text-sm">{{ $score->subject->name ?? '—' }}</td>
                                <td class="px-16 py-10 text-sm text-secondary-light">
                                    {{ $score->term->name ?? '—' }} {{ $score->term->academic_year ?? '' }}
                                </td>
                                <td class="px-16 py-10 text-sm fw-semibold" style="color:#2A2567;">
                                    {{ $score->total_score ?? '—' }}
                                </td>
                                <td class="px-16 py-10 text-sm fw-bold">
                                    @php
                                        $gc = match($score->grade) {
                                            'A1'         => 'text-success-600',
                                            'B2','B3'    => 'text-primary-600',
                                            'C4','C5','C6'=> 'text-info-600',
                                            'D7','E8'    => 'text-warning-600',
                                            'F9'         => 'text-danger-600',
                                            default      => 'text-secondary-light',
                                        };
                                    @endphp
                                    <span class="{{ $gc }}">{{ $score->grade }}</span>
                                </td>
                                <td class="px-16 py-10">
                                    @php
                                        $badgeMap = ['submitted'=>'warning','approved'=>'success','locked'=>'primary','returned'=>'danger','draft'=>'secondary'];
                                        $b = $badgeMap[$score->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $b }}-100 text-{{ $b }}-600 px-8 py-4 radius-4 text-capitalize text-xs">
                                        {{ $score->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-32 text-secondary-light text-sm">
                                    <i class="ri-inbox-line d-block mb-8" style="font-size:1.6rem;opacity:.3;"></i>
                                    No scores recorded yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════ --}}
{{-- Generate Result PDF Modal                         --}}
{{-- ══════════════════════════════════════════════════ --}}
<div class="modal fade" id="resultModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-semibold">
                    <i class="ri-file-pdf-2-line text-danger-600 me-2"></i>Generate Result Sheet PDF
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- Student summary --}}
                <div class="d-flex align-items-center gap-12 p-16 radius-8 mb-16"
                     style="background:#F5F4FA; border:1px solid #EDEEF8;">
                    @if($student->photo)
                        <img src="{{ asset('storage/'.$student->photo) }}"
                             class="w-48-px h-48-px rounded-circle object-fit-cover flex-shrink-0" alt="">
                    @else
                        <div class="w-48-px h-48-px rounded-circle bg-primary-100 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="font-size:1.2rem; font-weight:700; color:#2A2567;">
                            {{ strtoupper(substr($student->first_name,0,1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="fw-semibold mb-0 text-sm" style="color:#2A2567;">{{ $student->full_name }}</p>
                        <p class="text-xs text-secondary-light mb-0">
                            {{ $student->schoolClass->name ?? 'Unassigned' }} &nbsp;·&nbsp; {{ $student->admission_number }}
                        </p>
                    </div>
                </div>

                @if($termsWithScores->isNotEmpty())
                <form action="{{ route('admin.students.result-pdf', $student) }}" method="GET"
                      target="_blank" id="pdfForm">
                    <div class="mb-16">
                        <label class="form-label fw-semibold text-sm mb-4">
                            Select Academic Term <span class="text-danger">*</span>
                        </label>
                        <select name="term_id" class="form-select" required>
                            <option value="">— Choose a term —</option>
                            @foreach($termsWithScores as $term)
                                <option value="{{ $term->id }}">
                                    {{ $term->name }} {{ $term->academic_year }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-secondary-light mt-4">
                            Only terms with approved/locked scores are listed.
                        </p>
                    </div>

                    <div class="d-flex align-items-center gap-8 p-12 radius-8"
                         style="background:rgba(220,53,69,.06); border:1px solid rgba(220,53,69,.15);">
                        <i class="ri-information-line text-danger-600 flex-shrink-0"></i>
                        <p class="text-xs mb-0" style="color:#555;">
                            The PDF will open in a new tab. Make sure pop-ups are allowed for this site.
                        </p>
                    </div>
                </form>
                @else
                <div class="text-center py-20">
                    <i class="ri-file-pdf-line d-block mb-8" style="font-size:2rem;color:#ccc;"></i>
                    <p class="text-sm text-secondary-light">
                        No approved results are available for this student yet.<br>
                        Scores must be submitted and approved before a result sheet can be generated.
                    </p>
                </div>
                @endif
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                @if($termsWithScores->isNotEmpty())
                <button type="submit" form="pdfForm" class="btn btn-danger btn-sm px-24">
                    <i class="ri-download-2-line me-1"></i> Download PDF
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
