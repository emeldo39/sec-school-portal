@extends('layouts.portal')

@section('title', 'Bulk Student Promotion')
@section('page-title', 'Bulk Student Promotion')
@section('page-subtitle', 'Academic year transition — promote all students to their next class')

@section('breadcrumb-actions')
    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-2">
        <i class="ri-arrow-left-line"></i> Back to Students
    </a>
@endsection

@section('content')

{{-- ── Info Banner ───────────────────────────────────────────────── --}}
<div class="card shadow-1 radius-8 mb-20 border-0" style="border-left: 4px solid #F4BC67 !important;">
    <div class="card-body py-16 px-24">
        <div class="d-flex gap-16 align-items-start">
            <div class="flex-shrink-0 w-40-px h-40-px rounded-circle bg-warning-100 text-warning-600 d-flex align-items-center justify-content-center" style="font-size:1.25rem;">
                <i class="ri-information-line"></i>
            </div>
            <div>
                <h6 class="fw-semibold mb-4 text-neutral-900">What this page does</h6>
                <ul class="mb-0 ps-16 text-sm text-secondary-light" style="line-height:1.8;">
                    <li>Each active student is assigned a default action based on their current class.</li>
                    <li><strong>Promote</strong> — moves the student to the next class (e.g. JSS1A → JSS2A, JSS3B → SS1B).</li>
                    <li><strong>Graduate</strong> — applies to SS3 students; marks them <em>inactive</em> (they have completed school).</li>
                    <li><strong>Repeat Year</strong> — student stays in their current class; no change is made.</li>
                    <li><strong>Deactivate</strong> — marks the student <em>inactive</em> (left school, transferred, etc.).</li>
                    <li><strong>Exclude</strong> — uncheck the <i class="ri-checkbox-line"></i> checkbox on any row to exclude that student entirely from this batch; they will not be touched at all.</li>
                    <li>You can change any individual student's action before executing.</li>
                    <li><strong class="text-danger-600">This action cannot be automatically reversed.</strong> Verify before executing.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- ── Summary Stats ────────────────────────────────────────────── --}}
<div class="row g-16 mb-20">
    <div class="col-sm-4">
        <div class="card shadow-1 radius-8 p-16 text-center">
            <div class="text-2xl fw-bold text-primary-600" id="stat-promote">{{ $promoteCount }}</div>
            <div class="text-sm text-secondary-light mt-4">To be Promoted</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card shadow-1 radius-8 p-16 text-center">
            <div class="text-2xl fw-bold text-warning-600" id="stat-graduate">{{ $graduateCount }}</div>
            <div class="text-sm text-secondary-light mt-4">To Graduate (SS3)</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card shadow-1 radius-8 p-16 text-center">
            <div class="text-2xl fw-bold text-success-600" id="stat-total">{{ $totalStudents }}</div>
            <div class="text-sm text-secondary-light mt-4">Total Active Students</div>
        </div>
    </div>
</div>

{{-- ── Main Form ────────────────────────────────────────────────── --}}
<form method="POST" action="{{ route('admin.students.bulk-promote.execute') }}" id="promotionForm">
    @csrf

    {{-- ── Sticky Action Bar ──────────────────────────────────────── --}}
    <div class="card shadow-1 radius-8 mb-20 sticky-top" style="z-index:10; top:72px;">
        <div class="card-body py-12 px-24 d-flex align-items-center flex-wrap gap-12 justify-content-between">
            <div class="d-flex align-items-center gap-16 flex-wrap">
                <span class="text-sm text-secondary-light">
                    <i class="ri-arrow-up-circle-line text-success-600 me-1"></i>
                    <span id="live-promote">0</span> promote &nbsp;·&nbsp;
                    <i class="ri-graduation-cap-line text-success-600 me-1"></i>
                    <span id="live-graduate">0</span> graduate &nbsp;·&nbsp;
                    <i class="ri-restart-line text-warning-600 me-1"></i>
                    <span id="live-repeat">0</span> repeat &nbsp;·&nbsp;
                    <i class="ri-user-unfollow-line text-danger-600 me-1"></i>
                    <span id="live-deactivate">0</span> deactivate
                </span>
            </div>
            <div class="d-flex align-items-center gap-8 flex-wrap">
                <button type="button" id="btnPromoteAll"
                        class="btn btn-sm btn-primary d-flex align-items-center gap-2">
                    <i class="ri-arrow-up-double-line"></i> Promote All
                </button>
                <button type="submit" id="btnExecute"
                        class="btn btn-sm btn-success d-flex align-items-center gap-2">
                    <i class="ri-check-double-line"></i> Execute Promotion
                </button>
            </div>
        </div>
    </div>

    {{-- ── Class Cards ─────────────────────────────────────────────── --}}
    @php
        $classesWithStudents = 0;
    @endphp

    @foreach($promotionMap as $classId => $mapping)
        @php
            $students   = $studentsByClass->get($classId, collect());
            $sourceClass = $classes->firstWhere('id', $classId);
            if ($students->isEmpty() || !$sourceClass) continue;
            $classesWithStudents++;
            $isGraduate  = $mapping['graduate'];
            $targetClass = $mapping['target'] ?? null;
            $actionLabel = $isGraduate ? 'Graduate' : 'Promote';
            $headerColor = $isGraduate ? 'text-warning-600' : 'text-primary-600';
            $headerBg    = $isGraduate ? 'bg-warning-100' : 'bg-primary-100';
        @endphp

        <div class="card shadow-1 radius-8 mb-16 class-card" data-class-id="{{ $classId }}">
            {{-- Card Header --}}
            <div class="card-header py-12 px-24 d-flex align-items-center justify-content-between flex-wrap gap-8">
                <div class="d-flex align-items-center gap-10">
                    <span class="w-36-px h-36-px rounded-circle {{ $headerBg }} {{ $headerColor }} d-flex align-items-center justify-content-center fw-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($sourceClass->name, 0, 2)) }}
                    </span>
                    <div>
                        <div class="d-flex align-items-center gap-8 flex-wrap">
                            <span class="fw-semibold text-sm">{{ $sourceClass->name }}</span>
                            <i class="ri-arrow-right-line text-secondary-light text-sm"></i>
                            @if($isGraduate)
                                <span class="badge bg-warning-100 text-warning-600 px-8 py-4 radius-4">
                                    <i class="ri-graduation-cap-line me-1"></i> Graduate
                                </span>
                            @else
                                <span class="badge bg-primary-100 text-primary-600 px-8 py-4 radius-4">
                                    {{ $targetClass->name }}
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-secondary-light mt-2">{{ $students->count() }} active student{{ $students->count() !== 1 ? 's' : '' }}</div>
                    </div>
                </div>
                {{-- Quick-select buttons --}}
                <div class="d-flex align-items-center gap-6 flex-wrap">
                    {{-- "All Promote/Graduate" starts filled/active since all radios default to promote --}}
                    <button type="button"
                            class="btn btn-xs btn-success px-8 py-3 quick-select"
                            data-class="{{ $classId }}" data-action="promote"
                            data-active-class="btn-success"
                            data-inactive-class="btn-outline-success"
                            title="Set all included students in this class to {{ strtolower($actionLabel) }}">
                        {{ $actionLabel }}
                    </button>
                    <button type="button"
                            class="btn btn-xs btn-outline-warning px-8 py-3 quick-select"
                            data-class="{{ $classId }}" data-action="repeat"
                            data-active-class="btn-warning"
                            data-inactive-class="btn-outline-warning"
                            title="Set all included students in this class to repeat the year">
                        Repeat
                    </button>
                    <button type="button"
                            class="btn btn-xs btn-outline-danger px-8 py-3 quick-select"
                            data-class="{{ $classId }}" data-action="deactivate"
                            data-active-class="btn-danger"
                            data-inactive-class="btn-outline-danger"
                            title="Set all included students in this class to deactivate">
                        Deactivate
                    </button>
                    <button type="button" class="btn btn-xs btn-outline-secondary px-8 py-3 card-toggle"
                            data-target="class-body-{{ $classId }}"
                            title="Collapse / expand">
                        <i class="ri-arrow-up-s-line"></i>
                    </button>
                </div>
            </div>

            {{-- Student Table --}}
            <div class="card-body p-0" id="class-body-{{ $classId }}">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th class="px-8 py-10 text-center" style="width:76px;">
                                    <label class="d-flex align-items-center justify-content-center gap-6 mb-4"
                                           style="cursor:pointer;white-space:nowrap;"
                                           title="Check to include all / uncheck to exclude all students in this class">
                                        <input type="checkbox"
                                               class="master-checkbox"
                                               data-class="{{ $classId }}"
                                               checked
                                               style="all:revert;width:15px;height:15px;cursor:pointer;accent-color:#2A2567;flex-shrink:0;">
                                        <span class="fw-bold text-primary-600"
                                              style="font-size:0.67rem;letter-spacing:.04em;">INCL.</span>
                                    </label>
                                    {{-- Bulk All / None text-links --}}
                                    <div class="d-flex align-items-center justify-content-center gap-4">
                                        <button type="button"
                                                class="include-all-btn fw-semibold text-primary-600"
                                                data-class="{{ $classId }}" data-include="1"
                                                style="font-size:0.62rem;background:none;border:none;padding:0;cursor:pointer;text-decoration:underline;"
                                                title="Include all students in this class">All</button>
                                        <span style="font-size:0.62rem;color:#ccc;line-height:1;">|</span>
                                        <button type="button"
                                                class="include-all-btn text-secondary-light fw-semibold"
                                                data-class="{{ $classId }}" data-include="0"
                                                style="font-size:0.62rem;background:none;border:none;padding:0;cursor:pointer;text-decoration:underline;"
                                                title="Exclude all students in this class">None</button>
                                    </div>
                                </th>
                                <th class="px-16 py-12 text-sm" style="width:36px;">#</th>
                                <th class="px-16 py-12 text-sm">Student</th>
                                <th class="px-16 py-12 text-sm">Adm. No.</th>
                                <th class="px-16 py-12 text-sm text-center" style="min-width:100px;">
                                    <span class="badge bg-success-100 text-success-600 px-6 py-3">
                                        {{ $actionLabel }}
                                    </span>
                                </th>
                                <th class="px-16 py-12 text-sm text-center" style="min-width:100px;">
                                    <span class="badge bg-warning-100 text-warning-600 px-6 py-3">Repeat Year</span>
                                </th>
                                <th class="px-16 py-12 text-sm text-center" style="min-width:100px;">
                                    <span class="badge bg-danger-100 text-danger-600 px-6 py-3">Deactivate</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $idx => $student)
                            <tr class="student-row" data-class="{{ $classId }}" data-graduate="{{ $isGraduate ? '1' : '0' }}" data-student-id="{{ $student->id }}">
                                {{-- Include / Exclude checkbox --}}
                                <td class="px-16 py-10 text-center">
                                    <input type="checkbox"
                                           class="include-checkbox"
                                           data-student="{{ $student->id }}"
                                           data-class="{{ $classId }}"
                                           checked
                                           style="all:revert;width:18px;height:18px;cursor:pointer;accent-color:#2A2567;display:block;margin:0 auto;"
                                           title="Uncheck to exclude {{ $student->full_name }} from this promotion batch">
                                </td>
                                <td class="px-16 py-10 text-sm text-secondary-light">{{ $idx + 1 }}</td>
                                <td class="px-16 py-10">
                                    <div class="d-flex align-items-center gap-8">
                                        @if($student->photo)
                                            <img src="{{ asset('storage/' . $student->photo) }}"
                                                 class="w-32-px h-32-px rounded-circle object-fit-cover flex-shrink-0" alt="">
                                        @else
                                            <span class="w-32-px h-32-px rounded-circle bg-primary-100 text-primary-600 d-flex align-items-center justify-content-center fw-bold text-xs flex-shrink-0">
                                                {{ strtoupper(substr($student->first_name, 0, 1)) }}
                                            </span>
                                        @endif
                                        <span class="text-sm fw-medium">{{ $student->full_name }}</span>
                                    </div>
                                </td>
                                <td class="px-16 py-10 text-sm text-secondary-light">{{ $student->admission_number }}</td>

                                {{-- Promote / Graduate --}}
                                <td class="px-16 py-10 text-center">
                                    <label class="d-flex justify-content-center align-items-center" style="cursor:pointer;">
                                        <input type="radio"
                                               name="actions[{{ $student->id }}]"
                                               value="promote"
                                               class="action-radio"
                                               data-class="{{ $classId }}"
                                               data-graduate="{{ $isGraduate ? '1' : '0' }}"
                                               checked
                                               style="width:18px;height:18px;cursor:pointer;accent-color: {{ $isGraduate ? '#C08D2A' : '#2A2567' }};">
                                    </label>
                                </td>

                                {{-- Repeat Year --}}
                                <td class="px-16 py-10 text-center">
                                    <label class="d-flex justify-content-center align-items-center" style="cursor:pointer;">
                                        <input type="radio"
                                               name="actions[{{ $student->id }}]"
                                               value="repeat"
                                               class="action-radio"
                                               data-class="{{ $classId }}"
                                               data-graduate="{{ $isGraduate ? '1' : '0' }}"
                                               style="width:18px;height:18px;cursor:pointer;accent-color:#00B69B;">
                                    </label>
                                </td>

                                {{-- Deactivate --}}
                                <td class="px-16 py-10 text-center">
                                    <label class="d-flex justify-content-center align-items-center" style="cursor:pointer;">
                                        <input type="radio"
                                               name="actions[{{ $student->id }}]"
                                               value="deactivate"
                                               class="action-radio"
                                               data-class="{{ $classId }}"
                                               data-graduate="{{ $isGraduate ? '1' : '0' }}"
                                               style="width:18px;height:18px;cursor:pointer;accent-color:#EF4770;">
                                    </label>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach

    {{-- ── No students notice ──────────────────────────────────────── --}}
    @if($classesWithStudents === 0)
    <div class="card shadow-1 radius-8 p-32 text-center">
        <i class="ri-user-smile-line d-block mb-8 text-secondary-light" style="font-size:2.5rem;opacity:.3;"></i>
        <p class="text-secondary-light mb-0">No active students found across any class.</p>
    </div>
    @endif

    {{-- ── Bottom Execute ──────────────────────────────────────────── --}}
    @if($classesWithStudents > 0)
    <div class="card shadow-1 radius-8 mt-8">
        <div class="card-body py-16 px-24 d-flex align-items-center justify-content-between flex-wrap gap-12">
            <p class="text-sm text-secondary-light mb-0">
                <i class="ri-shield-check-line text-success-600 me-1"></i>
                Review all selections above, then click <strong>Execute Promotion</strong> to apply changes.
            </p>
            <div class="d-flex gap-8">
                <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-sm btn-success px-20 d-flex align-items-center gap-2">
                    <i class="ri-check-double-line"></i> Execute Promotion
                </button>
            </div>
        </div>
    </div>
    @endif

</form>

{{-- ── Confirm Modal ────────────────────────────────────────────── --}}
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-semibold">Confirm Bulk Promotion</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-20 px-24">
                <div class="d-flex gap-12 mb-16">
                    <i class="ri-error-warning-line text-warning-600" style="font-size:1.75rem;flex-shrink:0;margin-top:2px;"></i>
                    <p class="text-sm text-secondary-light mb-0">
                        You are about to apply the following changes to <strong class="text-neutral-900">all students</strong>.
                        This will move students to their next class, graduate SS3 students, and/or deactivate those marked.
                        <strong class="text-danger-600">This cannot be automatically reversed.</strong>
                    </p>
                </div>
                <div class="radius-8 p-16" style="background:var(--neutral-50, #F5F4FA);">
                    <div class="row g-8 text-center">
                        <div class="col-3">
                            <div class="fw-bold text-primary-600 text-lg" id="modal-promote">—</div>
                            <div class="text-xs text-secondary-light">Promote</div>
                        </div>
                        <div class="col-3">
                            <div class="fw-bold text-warning-600 text-lg" id="modal-graduate">—</div>
                            <div class="text-xs text-secondary-light">Graduate</div>
                        </div>
                        <div class="col-3">
                            <div class="fw-bold text-success-600 text-lg" id="modal-repeat">—</div>
                            <div class="text-xs text-secondary-light">Repeat</div>
                        </div>
                        <div class="col-3">
                            <div class="fw-bold text-danger-600 text-lg" id="modal-deactivate">—</div>
                            <div class="text-xs text-secondary-light">Deactivate</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Go Back & Review</button>
                <button type="button" id="btnConfirmExecute" class="btn btn-sm btn-success px-20">
                    <i class="ri-check-double-line me-1"></i> Yes, Execute
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {

    // ── Helpers ──────────────────────────────────────────────────────

    /**
     * Count only active (included) radios that are checked.
     * Excluded rows have their radios disabled, so :disabled skips them.
     */
    function updateCounters() {
        var counts = { promote: 0, repeat: 0, deactivate: 0, graduate_promote: 0 };

        $('.action-radio:checked:not(:disabled)').each(function () {
            var val      = $(this).val();
            var graduate = $(this).data('graduate') == '1';
            if      (val === 'promote' && graduate) counts.graduate_promote++;
            else if (val === 'promote')             counts.promote++;
            else if (val === 'repeat')              counts.repeat++;
            else if (val === 'deactivate')          counts.deactivate++;
        });

        $('#live-promote').text(counts.promote);
        $('#live-graduate').text(counts.graduate_promote);
        $('#live-repeat').text(counts.repeat);
        $('#live-deactivate').text(counts.deactivate);

        // Mirror into confirm modal
        $('#modal-promote').text(counts.promote);
        $('#modal-graduate').text(counts.graduate_promote);
        $('#modal-repeat').text(counts.repeat);
        $('#modal-deactivate').text(counts.deactivate);
    }

    /**
     * Highlight/de-highlight the quick-select buttons for a given card.
     * If activeAction is null, all three buttons go to their outline (inactive) style.
     */
    function syncCardButtons(classId, activeAction) {
        $('.quick-select[data-class="' + classId + '"]').each(function () {
            var $btn         = $(this);
            var btnAction    = $btn.data('action');
            var activeClass  = $btn.data('active-class');
            var inactiveClass = $btn.data('inactive-class');

            if (activeAction !== null && btnAction === activeAction) {
                $btn.removeClass(inactiveClass).addClass(activeClass);
            } else {
                $btn.removeClass(activeClass).addClass(inactiveClass);
            }
        });
    }

    /**
     * If every included student in a card has the same action, return it.
     * Returns null if the card is mixed (or has no included students).
     */
    function getCardUniformAction(classId) {
        var actions = [];
        $('.action-radio[data-class="' + classId + '"]:checked:not(:disabled)').each(function () {
            actions.push($(this).val());
        });
        if (actions.length === 0) return null;
        var first = actions[0];
        return actions.every(function (v) { return v === first; }) ? first : null;
    }

    /**
     * Apply row-level colour tint matching the action colour:
     *   promote   → green  (table-success)
     *   repeat    → yellow (table-warning)
     *   deactivate→ red    (table-danger)
     * Excluded rows (opacity 0.38) get no tint.
     */
    function applyRowHighlight($row, action) {
        $row.removeClass('table-success table-warning table-danger');
        if (action === 'promote')    $row.addClass('table-success');
        if (action === 'repeat')     $row.addClass('table-warning');
        if (action === 'deactivate') $row.addClass('table-danger');
    }

    /**
     * Sync the master checkbox in the column header for a given card:
     *   all included  → checked
     *   all excluded  → unchecked
     *   mixed         → indeterminate
     */
    function updateMasterCheckbox(classId) {
        var $boxes   = $('.include-checkbox[data-class="' + classId + '"]');
        var total    = $boxes.length;
        var included = $boxes.filter(':checked').length;
        var $master  = $('.master-checkbox[data-class="' + classId + '"]');
        if (included === total) {
            $master.prop('indeterminate', false).prop('checked', true);
        } else if (included === 0) {
            $master.prop('indeterminate', false).prop('checked', false);
        } else {
            $master.prop('indeterminate', true).prop('checked', false);
        }
    }

    // ── Initial state ─────────────────────────────────────────────────
    // All radios default to "promote" → green row tint on every row.
    // All cards start with "All Promote" button active.
    $('.student-row').each(function () {
        $(this).addClass('table-success');
    });
    $('.class-card').each(function () {
        var classId = $(this).data('class-id');
        syncCardButtons(classId, 'promote');
    });
    updateCounters();

    // ── Radio change ──────────────────────────────────────────────────
    $(document).on('change', '.action-radio', function () {
        var classId = $(this).data('class');
        applyRowHighlight($(this).closest('tr'), $(this).val());
        syncCardButtons(classId, getCardUniformAction(classId));
        updateCounters();
    });

    // ── Individual include / exclude checkbox ────────────────────────
    $(document).on('change', '.include-checkbox', function () {
        var $cb      = $(this);
        var classId  = $cb.data('class');
        var $row     = $cb.closest('tr');
        var included = $cb.is(':checked');

        // Enable / disable the three radio inputs in this row
        $row.find('.action-radio').prop('disabled', !included);

        // Visual: dim + italic when excluded
        $row.css('opacity', included ? '' : '0.38');
        $row.find('td').css('font-style', included ? '' : 'italic');

        // Restore / remove row tint
        if (included) {
            applyRowHighlight($row, $row.find('.action-radio:checked').val());
        } else {
            $row.removeClass('table-success table-warning table-danger');
        }

        updateMasterCheckbox(classId);
        syncCardButtons(classId, getCardUniformAction(classId));
        updateCounters();
    });

    // ── Master checkbox (column header) ──────────────────────────────
    // Checks or unchecks every include-checkbox in the card at once.
    $(document).on('change', '.master-checkbox', function () {
        var classId = $(this).data('class');
        var include = $(this).is(':checked');
        // Trigger each row's include-checkbox so all the row-level logic fires
        $('.include-checkbox[data-class="' + classId + '"]').each(function () {
            if ($(this).is(':checked') !== include) {
                $(this).prop('checked', include).trigger('change');
            }
        });
    });

    // ── "Include All" / "Exclude All" buttons ─────────────────────────
    $(document).on('click', '.include-all-btn', function () {
        var classId = $(this).data('class');
        var include = parseInt($(this).data('include')) === 1;
        $('.include-checkbox[data-class="' + classId + '"]').each(function () {
            if ($(this).is(':checked') !== include) {
                $(this).prop('checked', include).trigger('change');
            }
        });
    });

    // ── Quick-select per card ─────────────────────────────────────────
    $(document).on('click', '.quick-select', function () {
        var classId = $(this).data('class');
        var action  = $(this).data('action');

        // Only affect included (enabled) radios in this card
        $('input.action-radio[data-class="' + classId + '"][value="' + action + '"]:not(:disabled)')
            .prop('checked', true);

        // Refresh row highlights for all rows in this card
        $('.student-row[data-class="' + classId + '"]').each(function () {
            var $row     = $(this);
            var $checked = $row.find('.action-radio:checked:not(:disabled)');
            if ($checked.length) {
                applyRowHighlight($row, $checked.val());
            }
        });

        syncCardButtons(classId, getCardUniformAction(classId));
        updateCounters();
    });

    // ── Collapse / expand card body ───────────────────────────────────
    $(document).on('click', '.card-toggle', function () {
        var targetId = $(this).data('target');
        var $body    = $('#' + targetId);
        var $icon    = $(this).find('i');
        if ($body.is(':visible')) {
            $body.slideUp(150);
            $icon.removeClass('ri-arrow-up-s-line').addClass('ri-arrow-down-s-line');
        } else {
            $body.slideDown(150);
            $icon.removeClass('ri-arrow-down-s-line').addClass('ri-arrow-up-s-line');
        }
    });

    // ── "Promote All" global button ───────────────────────────────────
    // Sets every INCLUDED student across ALL cards to 'promote'
    $('#btnPromoteAll').on('click', function () {
        $('.action-radio[value="promote"]:not(:disabled)').prop('checked', true);

        // Refresh all row highlights and card buttons
        $('.student-row').each(function () {
            var $row     = $(this);
            var $checked = $row.find('.action-radio:checked:not(:disabled)');
            if ($checked.length) {
                applyRowHighlight($row, $checked.val());
            }
        });
        $('.class-card').each(function () {
            syncCardButtons($(this).data('class-id'), getCardUniformAction($(this).data('class-id')));
        });

        updateCounters();
        $('#confirmModal').modal('show');
    });

    // ── Execute — show confirm modal first ────────────────────────────
    $('#btnExecute, #promotionForm button[type="submit"]').on('click', function (e) {
        e.preventDefault();
        updateCounters();
        $('#confirmModal').modal('show');
    });

    // ── Confirmed — actually submit ───────────────────────────────────
    $('#btnConfirmExecute').on('click', function () {
        $('#confirmModal').modal('hide');
        setTimeout(function () {
            $('#promotionForm').off('submit').submit();
        }, 200);
    });

});
</script>
@endpush
