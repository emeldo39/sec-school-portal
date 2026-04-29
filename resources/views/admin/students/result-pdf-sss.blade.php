<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        font-size: 7pt;
        color: #000;
    }

    @page {
        /* margin: 6mm 6mm 6mm 6mm; */
        margin: 0.5in !important;
        size: A4 portrait;
    }

    .bold {
        font-weight: bold;
    }

    /* ── Header ── */
    .header-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 3px;
    }

    .header-table td {
        padding: 1px 3px;
        vertical-align: middle;
    }

    .school-name {
        font-size: 12pt;
        font-weight: bold;
        color: #2A2567;
        line-height: 1.2;
    }

    .school-sub {
        font-size: 7.5pt;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .school-addr {
        font-size: 6pt;
        color: #444;
    }

    .school-motto {
        font-size: 6.5pt;
        font-style: italic;
    }

    .header-divider {
        border-top: 3px solid #2A2567;
        border-bottom: 1px solid #F4BC67;
        margin: 2px 0;
    }

    /* ── Info bar ── */
    .info-bar {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2px;
    }

    .info-bar td {
        border: 1px solid #000;
        padding: 2px 3px;
        font-size: 6.5pt;
    }

    .info-label {
        font-weight: bold;
        white-space: nowrap;
    }

    /* ── Section title ── */
    .section-title {
        background: #2A2567;
        color: #fff;
        font-weight: bold;
        font-size: 7.5pt;
        text-align: center;
        padding: 2px 0;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin: 2px 0 0 0;
    }

    /* ── Two-column body wrapper ── */
    .body-wrap {
        width: 100%;
        border-collapse: collapse;
    }

    .body-wrap>tr>td {
        vertical-align: top;
        padding: 0;
    }

    .col-scores {
        width: 59%;
        padding-right: 2px;
    }

    .col-domains {
        width: 41%;
        padding-left: 2px;
    }

    /* ── Subject score table ── */
    .score-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: -30px;
        /* Adjust as needed to reduce gap with header */
    }

    .score-table th,
    .score-table td {
        border: 1px solid #000;
        padding: 1px 0.5px;
        font-size: 6.2pt;
        text-align: center;
        vertical-align: middle;
        height: 25px;
    }

    .score-table thead tr:first-child th {
        background: #dce4f5;
        font-weight: bold;
        font-size: 5pt;
    }

    .score-table thead tr:last-child th {
        background: #F0EFFE;
        font-weight: bold;
        font-size: 4.8pt;
        line-height: 1.2;
    }

    .score-table td.subj-name {
        text-align: left;
        font-size: 5.8pt;
        padding-left: 2px;
    }

    .score-table td.remark-col {
        text-align: left;
        font-size: 5pt;
        padding-left: 1px;
    }

    .score-table tr:nth-child(even) td {
        background: #FAFAFA;
    }

    .score-table tr td:first-child {
        background: #fff !important;
    }

    .grade-a1 {
        color: #1a6b1a;
        font-weight: bold;
    }

    .grade-b {
        color: #1a4db5;
        font-weight: bold;
    }

    .grade-c {
        color: #2A2567;
        font-weight: bold;
    }

    .grade-d {
        color: #9c6a00;
        font-weight: bold;
    }

    .grade-f {
        color: #c0392b;
        font-weight: bold;
    }

    /* ── Domain tables (right panel) ── */
    .domain-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 3px;
    }

    .domain-table th {
        background: #2A2567;
        color: #fff;
        font-size: 5.5pt;
        padding: 2px 2px;
        text-align: center;
        border: 1px solid #000;
    }

    .domain-table td {
        border: 1px solid #000;
        padding: 1px 2px;
        font-size: 5.5pt;
        vertical-align: middle;
    }

    .domain-table td.item-name {
        text-align: left;
    }

    .domain-table td.rating-cell {
        text-align: center;
        width: 10px;
        font-family: 'DejaVu Sans', sans-serif;
    }

    /* ── Key ratings / grading ── */
    .key-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 3px;
    }

    .key-table td {
        border: 1px solid #000;
        padding: 1.5px 3px;
        font-size: 5.5pt;
    }

    .grade-key-table {
        width: 100%;
        border-collapse: collapse;
    }

    .grade-key-table td {
        border: 1px solid #000;
        padding: 1px 2px;
        font-size: 5.3pt;
    }

    /* ── Photo box ── */
    .photo-box {
        width: 100%;
        border: 1px solid #000;
        text-align: center;
        font-size: 5.5pt;
        color: #888;
        padding: 3px 0;
        height: 30px;
        margin-bottom: 3px;
        vertical-align: middle;
    }

    /* ── Remarks ── */
    .remarks-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 3px;
    }

    .remarks-table td {
        border: 1px solid #000;
        padding: 2px 3px;
        font-size: 6pt;
        vertical-align: top;
    }

    .footer-bar {
        margin-top: 2px;
        font-size: 5.5pt;
        text-align: center;
        color: #666;
        font-style: italic;
        border-top: 1px solid #ccc;
        padding-top: 1px;
    }
    </style>
</head>

<body>

    @php
    if (!function_exists('sssOrdinal')) {
    function sssOrdinal($n) {
    if (!is_numeric($n)) return $n;
    $n = (int) $n;
    $mod = $n % 100;
    if ($mod >= 11 && $mod <= 13) return $n . 'th' ; return $n . match($n % 10) { 1=> 'st', 2 => 'nd', 3 => 'rd',
        default => 'th' };
        }
        }
        if (!function_exists('sssGradeClass')) {
        function sssGradeClass($g) {
        $prefix = strtoupper(substr((string)$g, 0, 1));
        return match($prefix) {
        'A' => 'grade-a1',
        'B' => 'grade-b',
        'C' => 'grade-c',
        'D','E' => 'grade-d',
        'F' => 'grade-f',
        default => '',
        };
        }
        }
        /**
        * Look up grade directly from the $gradingScales collection (bypasses model accessor).
        * Avoids any relation-loading issues.
        */
        if (!function_exists('sssGetGrade')) {
        function sssGetGrade($total, $scales) {
        if ($total === null) return '-';
        foreach ($scales as $gs) {
        if ($total >= $gs->min_score && $total <= $gs->max_score) {
            return $gs->grade;
            }
            }
            return '-';
            }
            }
            @endphp

            {{-- ══════════════════════════════════════════════════════════════ --}}
            {{-- INCOMPLETE RESULT NOTICE                                      --}}
            {{-- ══════════════════════════════════════════════════════════════ --}}
            @if(!empty($warnings))
            <table width="100%" style="border-collapse:collapse; margin-bottom:4px;">
                <tr>
                    <td style="background:#fff3cd; border:2px solid #f4a900; padding:4px 8px;">
                        <span style="font-weight:bold; font-size:7pt; color:#7a4f00;">&#9888; INCOMPLETE RESULT &mdash;
                        </span>
                        <span style="font-size:6.5pt; color:#7a4f00;">
                            This result sheet is incomplete.
                            @if(!empty($warnings['missingSubjects']) && $warnings['missingSubjects']->isNotEmpty())
                            No scores entered for:
                            <strong>{{ $warnings['missingSubjects']->pluck('name')->implode(', ') }}</strong>.
                            @endif
                            @if(!empty($warnings['pendingScores']) && $warnings['pendingScores']->isNotEmpty())
                            Pending approval:
                            <strong>{{ $warnings['pendingScores']->map(fn($s)=>($s->subject->name ?? '?').' ('.ucfirst($s->status).')')->implode(', ') }}</strong>.
                            @endif
                        </span>
                    </td>
                </tr>
            </table>
            @endif

            {{-- ══════════════════════════════════════════════════════════════ --}}
            {{-- HEADER                                                        --}}
            {{-- ══════════════════════════════════════════════════════════════ --}}
            <table class="header-table">
                <tr>
                    <td style="width:11%; text-align:center;">
                        @if($logoPath)
                        <img src="{{ $logoPath }}" style="height:48px; width:auto;" alt="">
                        @else
                        <div style="width:48px;height:48px;border:1px solid #ccc;display:inline-block;"></div>
                        @endif
                    </td>
                    <td style="text-align:center; width:78%;">
                        <div class="school-name">
                            {{ strtoupper($settings['school_name'] ?? 'DIVINE ROYAL INT\'L COLLEGE') }}</div>
                        <div class="school-sub">{{ strtoupper($settings['school_address'] ?? 'Nkpor, Anambra State') }}
                        </div>
                        <div class="school-addr">Tel: {{ $settings['school_phone'] ?? '' }} &nbsp;|&nbsp;
                            {{ $settings['school_email'] ?? '' }}</div>
                        <div class="school-motto"><em>"{{ $settings['school_motto'] ?? 'Light of the World' }}"</em>
                        </div>
                    </td>
                    <td style="width:11%; text-align:center;">
                        @if($logoPath)
                        <img src="{{ $logoPath }}" style="height:48px; width:auto;" alt="">
                        @endif
                    </td>
                </tr>
            </table>
            <div class="header-divider"></div>

            <div style="text-align:center; font-size:9pt; font-weight:bold; color:#2A2567; margin:1px 0 2px 0;">
                {{ strtoupper($term->name) }} RESULT SHEET &mdash; {{ $term->academic_year }}
            </div>

            {{-- ══════════════════════════════════════════════════════════════ --}}
            {{-- STUDENT INFO BAR                                              --}}
            {{-- ══════════════════════════════════════════════════════════════ --}}
            <table class="info-bar">
                <tr>
                    <td style="width:42%;"><span class="info-label">NAME OF STUDENT:</span>
                        {{ strtoupper($student->full_name) }}</td>
                    <td style="width:22%;"><span class="info-label">SESSION:</span> {{ $term->academic_year }}</td>
                    <td style="width:18%;"><span class="info-label">CLASS:</span> {{ $class->name ?? '' }}</td>
                    <td style="width:18%;"><span class="info-label">ADMISSION NO:</span>
                        {{ $student->admission_number }}</td>
                </tr>
                <tr>
                    <td><span class="info-label">NO. OF DISTINCTIONS:</span> {{ $distinctions }}</td>
                    <td><span class="info-label">NO. OF CREDITS:</span> {{ $credits }}</td>
                    <td><span class="info-label">NO. OF PASSES:</span> {{ $passes }}</td>
                    <td><span class="info-label">NO. OF FAILED:</span> {{ $failures }}</td>
                </tr>
                <tr>
                    <td><span class="info-label">GRAND TOTAL:</span> {{ number_format($myTotal, 1) }}</td>
                    <td><span class="info-label">NO. IN CLASS:</span> {{ $classSize }}</td>
                    <td><span class="info-label">POSITION:</span> {{ sssOrdinal($overallPosition) }} / {{ $classSize }}
                    </td>
                    <td><span class="info-label">NEXT TERM BEGINS:</span>
                        {{ $publication?->next_term_begins ? $publication->next_term_begins->format('d/m/Y') : 'TBD' }}
                    </td>
                </tr>
            </table>

            <div class="section-title"> TERMLY RESULT</div>

            {{-- ══════════════════════════════════════════════════════════════ --}}
            {{-- TWO-COLUMN BODY: score table (left) + domains (right)        --}}
            {{-- ══════════════════════════════════════════════════════════════ --}}
            <table class="body-wrap">
                <tr>

                    {{-- ── LEFT: SCORE TABLE ── --}}
                    <td class="col-scores">
                        <table class="score-table">
                            <thead>
                                <tr>
                                    <th rowspan="2"
                                        style="width:22%; text-align:left; padding-left:4px; vertical-align:middle; font-size:6pt; background:#fff;">
                                        SUBJECTS</th>
                                    <th colspan="6"
                                        style="background:#dce4f5; font-size:6pt; letter-spacing:0.3px; padding:1.5px 0;">
                                        CONTINUOUS ASSESSMENT (CA)</th>
                                    <th rowspan="2" style="width:5%; background:#dce4f5; vertical-align:middle;">
                                        SUM.<br>CA<br><em style="font-weight:normal;">/60</em></th>
                                    <th rowspan="2" style="width:5%; vertical-align:middle;">EXAM<br><em
                                            style="font-weight:normal;">/40</em></th>
                                    <th rowspan="2" style="width:5%; background:#dce4f5; vertical-align:middle;"
                                        class="bold">TOT.<br><em style="font-weight:normal;">/100</em></th>
                                    <th rowspan="2" style="width:4%; vertical-align:middle;">GRD</th>
                                    <th rowspan="2" style="width:3.5%; vertical-align:middle;">POS.</th>
                                    <th rowspan="2" style="width:7%; vertical-align:middle; font-size:5pt;">
                                        REMARK</th>
                                    <th rowspan="2" style="width:7%; vertical-align:middle; font-size:5pt;">
                                        SUBJ.<br>SIGN.</th>
                                </tr>
                                <tr>
                                    <th style="width:5.5%;">1ST<br>EX.<br><em style="font-weight:normal;">/10</em></th>
                                    <th style="width:5.5%;">T.<br>HOME<br><em style="font-weight:normal;">/10</em></th>
                                    <th style="width:5.5%;">QUIZ<br><em style="font-weight:normal;">/10</em></th>
                                    <th style="width:5.5%;">PRJ.<br><em style="font-weight:normal;">/10</em></th>
                                    <th style="width:5.5%;">2ND<br>EX.<br><em style="font-weight:normal;">/10</em></th>
                                    <th style="width:5.5%;">2ND<br>T.HM<br><em style="font-weight:normal;">/10</em></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($scores as $score)
                                @php $sssGrd = sssGetGrade($score->total_score, $gradingScales); @endphp
                                <tr>
                                    <td class="subj-name">
                                        {{ strtoupper($score->subject->name ?? '—') }}</td>
                                    <td>{{ $score->weekly_exercise_1 !== null ? number_format($score->weekly_exercise_1,1) : '' }}
                                    </td>
                                    <td>{{ $score->take_home !== null ? number_format($score->take_home,1) : '' }}</td>
                                    <td>{{ $score->college_quiz !== null ? number_format($score->college_quiz,1) : '' }}
                                    </td>
                                    <td>{{ $score->project !== null ? number_format($score->project,1) : '' }}</td>
                                    <td>{{ $score->weekly_exercise_2 !== null ? number_format($score->weekly_exercise_2,1) : '' }}
                                    </td>
                                    <td>{{ $score->take_home_2 !== null ? number_format($score->take_home_2,1) : '' }}
                                    </td>
                                    <td style="background:#f0f4fd;">
                                        {{ $score->summary_ca !== null ? number_format($score->summary_ca,1) : '' }}
                                    </td>
                                    <td>{{ $score->exam_score !== null ? number_format($score->exam_score,1) : '' }}
                                    </td>
                                    <td class="bold" style="background:#f0f4fd;">
                                        {{ $score->total_score !== null ? number_format($score->total_score,1) : '' }}
                                    </td>
                                    <td class="{{ sssGradeClass($sssGrd) }}">{{ $sssGrd }}</td>
                                    <td>{{ sssOrdinal($subjectPositions[$score->subject_id] ?? '-') }}</td>
                                    <td class="remark-col">{{ $score->subject_remark ?? '' }}</td>
                                    <td>&nbsp;</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="14"
                                        style="text-align:center; padding:6px; font-style:italic; color:#666; font-size:6pt;">
                                        No approved scores found for this term.
                                    </td>
                                </tr>
                                @endforelse
                                {{-- Blank filler rows --}}
                                @for($i = $scores->count(); $i < 15; $i++) <tr>
                                    <td class="subj-name">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td style="background:#f0f4fd;">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td style="background:#f0f4fd;">&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                </tr>
                @endfor
                </tbody>
            </table>
            </td>

            {{-- ── RIGHT: PHOTO + DOMAINS + KEY RATINGS + GRADING ── --}}
            <td class="col-domains">

                {{-- Photo box --}}
                <table style="width:100%; border-collapse:collapse; margin-bottom:3px;">
                    <tr>
                        <td style="border:1px solid #000; height:50px; text-align:center; vertical-align:middle; color:#aaa; font-size:5.5pt;">
                            @if($studentPhotoPath)
                            <img src="{{ $studentPhotoPath }}" style="max-height:50px; max-width:100%; object-fit:cover;" alt="">
                            @else
                            PASSPORT<br>PHOTO
                            @endif
                        </td>
                    </tr>
                </table>

                {{-- PSYCHOMOTOR DOMAIN --}}
                <table class="domain-table">
                    <thead>
                        <tr>
                            <th colspan="6" style="font-size:5.5pt; padding:2px; letter-spacing:0.5px;">PSYCHOMOTOR
                                DOMAIN</th>
                        </tr>
                        <tr>
                            <th style="width:56%; text-align:left; padding-left:2px; font-size:5pt;">ACTIVITY</th>
                            <th class="rating-cell">5</th>
                            <th class="rating-cell">4</th>
                            <th class="rating-cell">3</th>
                            <th class="rating-cell">2</th>
                            <th class="rating-cell">1</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($psychomotorItems as $i => $item)
                        @php $pRating = $publication?->psychomotor[$i] ?? null; @endphp
                        <tr>
                            <td class="item-name">{{ $i+1 }}. {{ $item }}</td>
                            @foreach([5,4,3,2,1] as $r)
                            <td class="rating-cell">{{ (string)$pRating === (string)$r ? '✓' : '' }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- AFFECTIVE DOMAIN --}}
                <table class="domain-table">
                    <thead>
                        <tr>
                            <th colspan="6" style="font-size:5.5pt; padding:2px; letter-spacing:0.5px;">AFFECTIVE DOMAIN
                            </th>
                        </tr>
                        <tr>
                            <th style="width:56%; text-align:left; padding-left:2px; font-size:5pt;">TRAIT</th>
                            <th class="rating-cell">5</th>
                            <th class="rating-cell">4</th>
                            <th class="rating-cell">3</th>
                            <th class="rating-cell">2</th>
                            <th class="rating-cell">1</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($affectiveItems as $i => $item)
                        @php $aRating = $publication?->affective[$i] ?? null; @endphp
                        <tr>
                            <td class="item-name">{{ $i+1 }}. {{ $item }}</td>
                            @foreach([5,4,3,2,1] as $r)
                            <td class="rating-cell">{{ (string)$aRating === (string)$r ? '✓' : '' }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- KEY RATINGS --}}
                <table class="key-table">
                    <tr>
                        <td style="padding:2px 3px;">
                            <span style="font-weight:bold; font-size:5.5pt; display:block; margin-bottom:1px;">KEY
                                RATINGS:</span>
                            <span style="display:block; font-size:5.3pt;">5 – Maintaining an excellent degree of
                                observable traits</span>
                            <span style="display:block; font-size:5.3pt;">4 – Maintaining high level of observable
                                traits</span>
                            <span style="display:block; font-size:5.3pt;">3 – Acceptable level of observable
                                traits</span>
                            <span style="display:block; font-size:5.3pt;">2 – Shows minimal regard for the observable
                                traits</span>
                            <span style="display:block; font-size:5.3pt;">1 – Has no regard for the observable
                                traits</span>
                        </td>
                    </tr>
                </table>

                {{-- GRADING SYSTEM --}}
                <table class="grade-key-table">
                    <tr>
                        <td colspan="3"
                            style="background:#2A2567; color:#fff; font-weight:bold; font-size:5.5pt; text-align:center; padding:2px; letter-spacing:0.5px;">
                            GRADING SYSTEM
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight:bold; width:18%;">GRD</td>
                        <td style="width:36%;">SCORE</td>
                        <td>REMARK</td>
                    </tr>
                    @foreach($gradingScales as $gs)
                    <tr>
                        <td class="bold {{ sssGradeClass($gs->grade) }}">{{ $gs->grade }}</td>
                        <td>{{ $gs->min_score }}–{{ $gs->max_score }}</td>
                        <td>{{ $gs->remark }}</td>
                    </tr>
                    @endforeach
                </table>

            </td>{{-- end col-domains --}}
            </tr>
            </table>

            {{-- ══════════════════════════════════════════════════════════════ --}}
            {{-- REMARKS + SIGNATURES                                          --}}
            {{-- ══════════════════════════════════════════════════════════════ --}}
            <table class="remarks-table" style="margin-top:20px;">
                <tr>
                    <td style="width:100%; border:none; padding:0;">
                        <table style="width:100%; border-collapse:collapse;">
                            <tr>
                                <td style="padding:1px 0; font-size:6pt; border:none; width:50%;">
                                    <span class="bold">FORM MASTER'S/MISTRESS'S REMARKS:</span>
                                    @if($publication?->form_master_remarks)
                                    <span style="font-size:5.5pt;">{{ $publication->form_master_remarks }}</span>
                                    @else
                                    <span
                                        style="display:inline-block; width:44%; border-bottom:1px dotted #000;">&nbsp;</span>
                                    @endif
                                </td>
                                <td style="padding:1px 0; font-size:6pt; border:none; width:50%;">
                                    <span class="bold">HOUSE MASTER'S/MISTRESS'S REMARKS:</span>
                                    @if($publication?->house_master_remarks)
                                    <span style="font-size:5.5pt;">{{ $publication->house_master_remarks }}</span>
                                    @else
                                    <span
                                        style="display:inline-block; width:42%; border-bottom:1px dotted #000;">&nbsp;</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding:1px 0; font-size:6pt; border:none;">
                                    <span class="bold">PRINCIPAL'S REMARKS:</span>
                                    @if($publication?->principal_remarks)
                                    <span style="font-size:5.5pt;">{{ $publication->principal_remarks }}</span>
                                    @else
                                    <span
                                        style="display:inline-block; width:82%; border-bottom:1px dotted #000;">&nbsp;</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:2px 0 1px 0; font-size:6pt; border:none;">
                                    <span class="bold">FORM MASTER'S SIGNATURE:</span>
                                    @if(!empty($formTeacherSignPath))
                                    <img src="{{ $formTeacherSignPath }}"
                                        style="height:25px; max-width:70px; vertical-align:middle; margin: 0 2px;">
                                    @else
                                    <span
                                        style="display:inline-block; width:30%; border-bottom:1px solid #000;">&nbsp;</span>
                                    @endif
                                    &nbsp;
                                    <span class="bold">DATE:</span>
                                    <span
                                        style="font-size:5.8pt;">{{ $publication?->published_at?->format('d/m/Y') ?? '' }}</span>
                                    @if(!$publication?->published_at)
                                    <span
                                        style="display:inline-block; width:14%; border-bottom:1px solid #000;">&nbsp;</span>
                                    @endif
                                </td>
                                <td style="padding:2px 0 1px 0; font-size:6pt; border:none;">
                                    <span class="bold">PRINCIPAL'S SIGNATURE:</span>
                                    @if(!empty($principalSignPath))
                                    <img src="{{ $principalSignPath }}"
                                        style="height:25px; max-width:70px; vertical-align:middle; margin: 0 2px;">
                                    @else
                                    <span
                                        style="display:inline-block; width:28%; border-bottom:1px solid #000;">&nbsp;</span>
                                    @endif
                                    &nbsp;
                                    <span class="bold">DATE:</span>
                                    <span
                                        style="font-size:5.8pt;">{{ $publication?->published_at?->format('d/m/Y') ?? '' }}</span>
                                    @if(!$publication?->published_at)
                                    <span
                                        style="display:inline-block; width:14%; border-bottom:1px solid #000;">&nbsp;</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <div class="footer-bar">
                {{ $settings['result_sheet_footer'] ?? 'This result is subject to correction of any clerical error.' }}
                &nbsp;|&nbsp; Printed: {{ now()->format('d M Y, h:i A') }}
            </div>

</body>

</html>