@extends('layouts.portal')

@section('title', 'Score Entry')
@section('page-title', 'Score Entry')
@section('page-subtitle', 'Enter and submit student scores')

@section('content')

{{-- Selector --}}
<div class="card shadow-1 radius-8 p-16 mb-20">
    <form method="GET" id="selectorForm" class="row g-12 align-items-end">
        <div class="col-sm-3">
            <label class="form-label text-sm fw-semibold mb-4">Class <span class="text-danger">*</span></label>
            <select name="class_id" id="classSelect" class="form-select form-select-sm" required onchange="reloadSubjects()">
                <option value="">— Select Class —</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <label class="form-label text-sm fw-semibold mb-4">Subject <span class="text-danger">*</span></label>
            <select name="subject_id" id="subjectSelect" class="form-select form-select-sm" required>
                <option value="">— Select Subject —</option>
                @foreach($availableSubjects as $subj)
                    <option value="{{ $subj->id }}" {{ $selectedSubjectId == $subj->id ? 'selected' : '' }}>
                        {{ $subj->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <label class="form-label text-sm fw-semibold mb-4">Term <span class="text-danger">*</span></label>
            <select name="term_id" class="form-select form-select-sm" required>
                <option value="">— Select Term —</option>
                @foreach($terms as $term)
                    <option value="{{ $term->id }}" {{ $selectedTermId == $term->id ? 'selected' : '' }}>
                        {{ $term->name }} {{ $term->academic_year }}
                        @if($term->is_current) (Current) @endif
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2">
            <button type="submit" class="btn btn-primary btn-sm w-100">Load Students</button>
        </div>
    </form>
</div>

@if($classModel && $students->isNotEmpty())

{{-- Score entry grid --}}
<form action="{{ route('teacher.scores.save') }}" method="POST" id="scoresForm">
    @csrf
    <input type="hidden" name="class_id"   value="{{ $selectedClassId }}">
    <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}">
    <input type="hidden" name="term_id"    value="{{ $selectedTermId }}">

    <div class="card shadow-1 radius-8">
        <div class="card-header py-14 px-24 border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h6 class="fw-semibold mb-0">{{ $classModel->name }} — {{ $availableSubjects->firstWhere('id', $selectedSubjectId)->name ?? '' }}</h6>
                <p class="text-xs text-secondary-light mb-0">{{ $students->count() }} student(s)</p>
            </div>
            <div class="d-flex gap-8">
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="ri-save-line me-1"></i> Save Draft
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0" style="min-width:1100px;">
                    <thead class="bg-neutral-50 text-center">
                        <tr>
                            <th rowspan="2" class="px-16 py-8 text-sm text-start align-middle" style="min-width:180px;">Student</th>
                            <th colspan="6" class="py-6 text-xs bg-primary-50 text-primary-600">
                                CONTINUOUS ASSESSMENT (CA)
                            </th>
                            <th rowspan="2" class="py-8 text-sm bg-primary-100 align-middle" style="width:80px;">
                                Summary CA<br><span class="text-xs fw-normal text-secondary-light">/60</span>
                            </th>
                            <th rowspan="2" class="py-8 text-sm align-middle" style="width:85px;">
                                Exam<br><span class="text-xs fw-normal text-secondary-light">/40</span>
                            </th>
                            <th rowspan="2" class="py-8 text-sm bg-success-50 align-middle" style="width:70px;">
                                Total<br><span class="text-xs fw-normal text-secondary-light">/100</span>
                            </th>
                            <th rowspan="2" class="py-8 text-sm align-middle" style="min-width:140px;">Remark</th>
                            <th rowspan="2" class="py-8 text-sm align-middle" style="width:80px;">Status</th>
                        </tr>
                        <tr>
                            <th class="py-6 text-xs bg-primary-50" style="width:80px;">1st Wkly Ex.<br><span class="fw-normal text-secondary-light">/10</span></th>
                            <th class="py-6 text-xs bg-primary-50" style="width:80px;">Take Home<br><span class="fw-normal text-secondary-light">/10</span></th>
                            <th class="py-6 text-xs bg-primary-50" style="width:80px;">College Quiz<br><span class="fw-normal text-secondary-light">/10</span></th>
                            <th class="py-6 text-xs bg-primary-50" style="width:80px;">Project<br><span class="fw-normal text-secondary-light">/10</span></th>
                            <th class="py-6 text-xs bg-primary-50" style="width:80px;">2nd Wkly Ex.<br><span class="fw-normal text-secondary-light">/10</span></th>
                            <th class="py-6 text-xs bg-primary-50" style="width:80px;">2nd Take Home<br><span class="fw-normal text-secondary-light">/10</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        @php
                            $score  = $scores[$student->id] ?? null;
                            $locked = $score && in_array($score->status, ['approved','locked']);
                        @endphp
                        <tr class="{{ $locked ? 'table-light' : '' }}">
                            <td class="px-16 py-8 text-sm fw-medium">
                                {{ $student->full_name }}
                                <div class="text-xs text-secondary-light">{{ $student->admission_number }}</div>
                            </td>

                            {{-- 1st Weekly Exercise --}}
                            <td class="py-6 px-4 text-center">
                                <input type="number" name="scores[{{ $student->id }}][weekly_exercise_1]"
                                       value="{{ $score?->weekly_exercise_1 }}"
                                       class="form-control form-control-sm text-center score-ca" min="0" max="10" step="0.5"
                                       data-row="{{ $student->id }}" {{ $locked ? 'disabled' : '' }}>
                            </td>

                            {{-- Take Home Assignment --}}
                            <td class="py-6 px-4 text-center">
                                <input type="number" name="scores[{{ $student->id }}][take_home]"
                                       value="{{ $score?->take_home }}"
                                       class="form-control form-control-sm text-center score-ca" min="0" max="10" step="0.5"
                                       data-row="{{ $student->id }}" {{ $locked ? 'disabled' : '' }}>
                            </td>

                            {{-- College Quiz --}}
                            <td class="py-6 px-4 text-center">
                                <input type="number" name="scores[{{ $student->id }}][college_quiz]"
                                       value="{{ $score?->college_quiz }}"
                                       class="form-control form-control-sm text-center score-ca" min="0" max="10" step="0.5"
                                       data-row="{{ $student->id }}" {{ $locked ? 'disabled' : '' }}>
                            </td>

                            {{-- Project --}}
                            <td class="py-6 px-4 text-center">
                                <input type="number" name="scores[{{ $student->id }}][project]"
                                       value="{{ $score?->project }}"
                                       class="form-control form-control-sm text-center score-ca" min="0" max="10" step="0.5"
                                       data-row="{{ $student->id }}" {{ $locked ? 'disabled' : '' }}>
                            </td>

                            {{-- 2nd Weekly Exercise --}}
                            <td class="py-6 px-4 text-center">
                                <input type="number" name="scores[{{ $student->id }}][weekly_exercise_2]"
                                       value="{{ $score?->weekly_exercise_2 }}"
                                       class="form-control form-control-sm text-center score-ca" min="0" max="10" step="0.5"
                                       data-row="{{ $student->id }}" {{ $locked ? 'disabled' : '' }}>
                            </td>

                            {{-- 2nd Take Home --}}
                            <td class="py-6 px-4 text-center">
                                <input type="number" name="scores[{{ $student->id }}][take_home_2]"
                                       value="{{ $score?->take_home_2 }}"
                                       class="form-control form-control-sm text-center score-ca" min="0" max="10" step="0.5"
                                       data-row="{{ $student->id }}" {{ $locked ? 'disabled' : '' }}>
                            </td>

                            {{-- Summary CA (read-only auto-sum) --}}
                            <td class="py-6 px-4 text-center bg-primary-50">
                                <span class="fw-semibold text-sm" id="summary_{{ $student->id }}">
                                    {{ $score?->summary_ca ?? '—' }}
                                </span>
                            </td>

                            {{-- Exam --}}
                            <td class="py-6 px-4 text-center">
                                <input type="number" name="scores[{{ $student->id }}][exam_score]"
                                       value="{{ $score?->exam_score }}"
                                       class="form-control form-control-sm text-center score-exam" min="0" max="40" step="0.5"
                                       data-row="{{ $student->id }}" {{ $locked ? 'disabled' : '' }}>
                            </td>

                            {{-- Total (read-only live preview) --}}
                            <td class="py-6 px-4 text-center bg-success-50">
                                <span class="fw-semibold text-sm" id="total_{{ $student->id }}">
                                    {{ $score?->total_score ?? '—' }}
                                </span>
                            </td>

                            {{-- Remark --}}
                            <td class="py-6 px-4">
                                <input type="text" name="scores[{{ $student->id }}][subject_remark]"
                                       value="{{ $score?->subject_remark }}"
                                       class="form-control form-control-sm" placeholder="Remark"
                                       {{ $locked ? 'disabled' : '' }}>
                            </td>

                            {{-- Status --}}
                            <td class="py-8 px-4 text-center">
                                @if($score)
                                    @php $map=['draft'=>['secondary','Draft'],'submitted'=>['warning','Submitted'],'approved'=>['success','Approved'],'returned'=>['danger','Returned'],'locked'=>['primary','Locked']]; $s=$map[$score->status]??['secondary',$score->status]; @endphp
                                    <span class="badge bg-{{ $s[0] }}-100 text-{{ $s[0] }}-600 px-6 py-3 radius-4 text-xs">{{ $s[1] }}</span>
                                @else
                                    <span class="text-xs text-secondary-light">New</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer px-24 py-16 d-flex gap-12 align-items-center">
            <button type="submit" class="btn btn-outline-secondary px-24">
                <i class="ri-save-line me-1"></i> Save Draft
            </button>
            <button type="button" class="btn btn-primary px-24" onclick="submitScores()">
                <i class="ri-send-plane-line me-1"></i> Submit for Review
            </button>
            <p class="text-xs text-secondary-light mb-0 ms-auto">
                Save draft to keep working. Submit when all scores are final.
            </p>
        </div>
    </div>
</form>

@elseif($selectedClassId && $selectedSubjectId && $selectedTermId)
<div class="card shadow-1 radius-8 p-32 text-center text-secondary-light">
    No active students in this class.
</div>
@endif

@endsection

@push('scripts')
<script>
// Auto-sum Summary CA and live Total preview
document.addEventListener('input', function(e) {
    const isCA   = e.target.classList.contains('score-ca');
    const isExam = e.target.classList.contains('score-exam');
    if (!isCA && !isExam) return;

    const row = e.target.dataset.row;

    // Sum the 6 CA inputs
    const caInputs = document.querySelectorAll(`.score-ca[data-row="${row}"]`);
    let caSum = 0;
    caInputs.forEach(i => caSum += parseFloat(i.value) || 0);
    caSum = Math.min(caSum, 60); // cap at 60

    const summaryEl = document.getElementById(`summary_${row}`);
    if (summaryEl) summaryEl.textContent = caSum.toFixed(1);

    // Total = summary CA + exam
    const examEl = document.querySelector(`.score-exam[data-row="${row}"]`);
    const exam   = parseFloat(examEl?.value) || 0;
    const totalEl = document.getElementById(`total_${row}`);
    if (totalEl) totalEl.textContent = (caSum + exam).toFixed(1);
});

function submitScores() {
    if (confirm('Submit all scores for admin review? You cannot edit them after submitting.')) {
        const form = document.getElementById('scoresForm');
        form.action = '{{ route("teacher.scores.submit") }}';
        form.submit();
    }
}

function reloadSubjects() {
    const classId = document.getElementById('classSelect').value;
    window.location.href = '{{ route("teacher.scores.index") }}?class_id=' + classId;
}
</script>
@endpush
