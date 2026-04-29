@extends('layouts.portal')

@section('title', 'Score Detail')
@section('page-title', 'Score Detail')
@section('page-subtitle', ($score->student->full_name ?? '') . ' — ' . ($score->subject->name ?? ''))

@section('breadcrumb-actions')
    <a href="{{ route('admin.scores.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="ri-arrow-left-line me-1"></i> Back
    </a>
@endsection

@section('content')
<div class="row gy-24">
    <div class="col-lg-6">
        <div class="card shadow-1 radius-8 p-24">
            <h6 class="fw-semibold mb-16 text-primary-light">Score Breakdown</h6>
            <ul class="list-unstyled text-sm">
                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light">Student</span>
                    <strong>{{ $score->student->full_name ?? '—' }}</strong>
                </li>
                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light">Class</span>
                    <strong>{{ $score->schoolClass->name ?? '—' }}</strong>
                </li>
                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light">Subject</span>
                    <strong>{{ $score->subject->name ?? '—' }}</strong>
                </li>
                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light">Term</span>
                    <strong>{{ $score->term->name ?? '—' }} {{ $score->term->academic_year ?? '' }}</strong>
                </li>

                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light">1st Wkly Exercise <span class="text-muted fw-normal">/10</span></span>
                    <strong>{{ $score->weekly_exercise_1 ?? '—' }}</strong>
                </li>
                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light">Take Home <span class="text-muted fw-normal">/10</span></span>
                    <strong>{{ $score->take_home ?? '—' }}</strong>
                </li>
                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light">College Quiz <span class="text-muted fw-normal">/10</span></span>
                    <strong>{{ $score->college_quiz ?? '—' }}</strong>
                </li>
                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light">Project <span class="text-muted fw-normal">/10</span></span>
                    <strong>{{ $score->project ?? '—' }}</strong>
                </li>
                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light">2nd Wkly Exercise <span class="text-muted fw-normal">/10</span></span>
                    <strong>{{ $score->weekly_exercise_2 ?? '—' }}</strong>
                </li>
                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light">2nd Take Home <span class="text-muted fw-normal">/10</span></span>
                    <strong>{{ $score->take_home_2 ?? '—' }}</strong>
                </li>
                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light fw-semibold">Summary CA <span class="text-muted fw-normal">/60</span></span>
                    <strong>{{ $score->summary_ca ?? '—' }}</strong>
                </li>

                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light">Exam Score</span>
                    <strong>{{ $score->exam_score ?? '—' }}</strong>
                </li>
                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light fw-semibold">Total Score</span>
                    <strong class="text-primary-600 fs-5">{{ $score->total_score ?? '—' }}</strong>
                </li>
                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light">Grade</span>
                    <strong class="text-primary-600">{{ $score->grade }}</strong>
                </li>
                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light">Status</span>
                    <span class="badge bg-warning-100 text-warning-600 px-8 py-4 text-capitalize">{{ $score->status }}</span>
                </li>
                @if($score->subject_remark)
                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light">Subject Remark</span>
                    <strong>{{ $score->subject_remark }}</strong>
                </li>
                @endif
                <li class="d-flex justify-content-between py-8 border-bottom">
                    <span class="text-secondary-light">Submitted By</span>
                    <strong>{{ $score->submittedBy->name ?? '—' }}</strong>
                </li>
                <li class="d-flex justify-content-between py-8">
                    <span class="text-secondary-light">Submitted At</span>
                    <strong>{{ $score->submitted_at?->format('d M Y H:i') ?? '—' }}</strong>
                </li>
            </ul>

            @if($score->remarks)
            <div class="mt-12 p-12 bg-warning-50 radius-8">
                <p class="text-sm fw-semibold mb-4">Admin Remarks:</p>
                <p class="text-sm mb-0">{{ $score->remarks }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-1 radius-8 p-24">
            <h6 class="fw-semibold mb-16 text-primary-light">Actions</h6>

            @if(auth()->user()->isPrincipal())

            @if(in_array($score->status, ['submitted','returned']))
            <form action="{{ route('admin.scores.approve', $score) }}" method="POST" class="mb-12">
                @csrf
                <button type="submit" class="btn btn-success w-100">
                    <i class="ri-check-double-line me-2"></i> Approve Score
                </button>
            </form>
            @endif

            @if($score->status === 'submitted')
            <form action="{{ route('admin.scores.return', $score) }}" method="POST" class="mb-12" id="returnForm">
                @csrf
                <div class="mb-12">
                    <label class="form-label text-sm fw-semibold">Return Remarks <span class="text-danger">*</span></label>
                    <textarea name="remarks" rows="3" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-warning w-100">
                    <i class="ri-arrow-go-back-line me-2"></i> Return for Revision
                </button>
            </form>
            @endif

            @if($score->status === 'approved')
            <form action="{{ route('admin.scores.lock', $score) }}" method="POST" class="mb-12">
                @csrf
                <button type="submit" class="btn btn-primary w-100">
                    <i class="ri-lock-line me-2"></i> Lock Score
                </button>
            </form>
            @endif

            @if($score->status === 'locked')
            <form action="{{ route('admin.scores.unlock', $score) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-secondary w-100">
                    <i class="ri-lock-unlock-line me-2"></i> Unlock Score
                </button>
            </form>
            @endif

            @else
            {{-- IT Admin: read-only view --}}
            <div class="d-flex align-items-center gap-10 p-16 radius-8"
                 style="background:#F0EFFE; border:1px solid #BDBAD8;">
                <i class="ri-information-line text-primary-600 fs-5 flex-shrink-0"></i>
                <p class="text-sm mb-0" style="color:#2A2567;">
                    Score approval actions are restricted to the <strong>Principal</strong>.
                    You may view this score but cannot approve, return, lock, or unlock it.
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
