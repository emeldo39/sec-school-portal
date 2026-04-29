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
        font-size: 7.5pt;
        color: #000;
    }

    @page {
        /* margin: 7mm 7mm 7mm 7mm; */
        margin: 0.2in !important;
        size: A4 landscape;
    }

    .bold {
        font-weight: bold;
    }

    .center {
        text-align: center;
    }

    /* ── Header ── */
    .header-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 3px;
    }

    .header-table td {
        padding: 2px 4px;
        vertical-align: middle;
    }

    .school-name {
        font-size: 14pt;
        font-weight: bold;
        color: #2A2567;
        line-height: 1.2;
    }

    .school-sub {
        font-size: 9pt;
        font-weight: bold;
        letter-spacing: 1px;
    }

    .school-addr {
        font-size: 6.5pt;
        color: #444;
    }

    .school-motto {
        font-size: 7pt;
        font-style: italic;
    }

    .header-divider {
        border-top: 3px solid #2A2567;
        border-bottom: 1px solid #F4BC67;
        margin: 3px 0;
    }

    /* ── Info bar ── */
    .info-bar {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2px;
    }

    .info-bar td {
        border: 1px solid #000;
        padding: 2.5px 4px;
        font-size: 7pt;
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
        font-size: 8.5pt;
        text-align: center;
        padding: 3px 0;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin: 3px 0 0 0;
    }

    /* ── Main two-column wrapper ── */
    .main-table {
        width: 100%;
        border-collapse: collapse;
    }

    .main-table>tbody>tr>td {
        vertical-align: top;
    }

    .col-scores {
        width: 63%;
        padding-right: 3px;
    }

    .col-domains {
        width: 37%;
        padding-left: 3px;
    }

    /* ── Subject score table ── */
    .score-table {
        width: 100%;
        border-collapse: collapse;
    }

    .score-table th,
    .score-table td {
        border: 1px solid #000;
        padding: 2px 2px;
        font-size: 6pt;
        text-align: center;
        vertical-align: middle;
    }

    .score-table thead tr:first-child th {
        background: #dce4f5;
        font-weight: bold;
        font-size: 6pt;
    }

    .score-table thead tr:last-child th {
        background: #F0EFFE;
        font-weight: bold;
        font-size: 5.8pt;
        line-height: 1.25;
    }

    .score-table td.subj-name {
        text-align: left;
        font-size: 6.8pt;
        padding-left: 4px;
    }

    .score-table td.remark-col {
        text-align: left;
        font-size: 6pt;
        padding-left: 2px;
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

    /* ── Domain tables ── */
    .domain-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 4px;
    }

    .domain-table th {
        background: #2A2567;
        color: #fff;
        font-size: 6.2pt;
        padding: 2px 3px;
        text-align: center;
        border: 1px solid #000;
    }

    .domain-table td {
        border: 1px solid #000;
        padding: 1.5px 2px;
        font-size: 6.2pt;
        vertical-align: middle;
    }

    .domain-table td.item-name {
        text-align: left;
    }

    .domain-table td.rating-cell {
        text-align: center;
        width: 13px;
        font-family: 'DejaVu Sans', sans-serif;
    }

    /* ── Remarks section ── */
    .remarks-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 3px;
    }

    .remarks-table td {
        border: 1px solid #000;
        padding: 3px 4px;
        font-size: 6.5pt;
        vertical-align: top;
    }

    /* ── Grading key ── */
    .grade-key-table {
        width: 100%;
        border-collapse: collapse;
    }

    .grade-key-table td {
        border: 1px solid #000;
        padding: 1.5px 3px;
        font-size: 6pt;
    }

    /* ── Footer ── */
    .footer-bar {
        margin-top: 3px;
        font-size: 6pt;
        text-align: center;
        color: #666;
        font-style: italic;
        border-top: 1px solid #ccc;
        padding-top: 2px;
    }
    </style>
</head>

<body>

    @php
    if (!function_exists('jssOrdinal')) {
    function jssOrdinal($n) {
    if (!is_numeric($n)) return $n;
    $n = (int) $n;
    $mod = $n % 100;
    if ($mod >= 11 && $mod <= 13) return $n . 'th' ; return $n . match($n % 10) { 1=> 'st', 2 => 'nd', 3 => 'rd',
        default => 'th' };
        }
        }
        if (!function_exists('jssGradeClass')) {
        function jssGradeClass($g) {
        // Handles JSS single-letter grades (A,B,C,D,E,F)
        // AND legacy SSS numeric grades (A1,B2…F9) for backward compat
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
        @endphp

        {{-- INCOMPLETE RESULT NOTICE --}}
        @if(!empty($warnings))
        <table width="100%" style="border-collapse:collapse; margin-bottom:5px;">
            <tr>
                <td style="background:#fff3cd; border:2px solid #f4a900; padding:5px 10px;">
                    <span style="font-weight:bold; font-size:7.5pt; color:#7a4f00;">&#9888; INCOMPLETE RESULT &mdash;
                    </span>
                    <span style="font-size:7pt; color:#7a4f00;">
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

        {{-- ══ HEADER ══ --}}
        <table class="header-table">
            <tr>
                <td style="width:11%; text-align:center;">
                    @if($logoPath)
                    <img src="{{ $logoPath }}" style="height:56px; width:auto;" alt="">
                    @else
                    <div style="width:56px;height:56px;border:1px solid #ccc;display:inline-block;"></div>
                    @endif
                </td>
                <td style="text-align:center; width:78%;">
                    <div class="school-name">{{ strtoupper($settings['school_name'] ?? 'DIVINE ROYAL INT\'L COLLEGE') }}
                    </div>
                    <div class="school-sub">{{ strtoupper($settings['school_address'] ?? 'Nkpor, Anambra State') }}
                    </div>
                    <div class="school-addr">Tel: {{ $settings['school_phone'] ?? '' }} &nbsp;|&nbsp;
                        {{ $settings['school_email'] ?? '' }}</div>
                    <div class="school-motto"><em>"{{ $settings['school_motto'] ?? 'Light of the World' }}"</em></div>
                </td>
                <td style="width:11%; text-align:center;">
                    @if($logoPath)
                    <img src="{{ $logoPath }}" style="height:56px; width:auto;" alt="">
                    @endif
                </td>
            </tr>
        </table>
        <div class="header-divider"></div>

        <div style="text-align:center; font-size:10pt; font-weight:bold; color:#2A2567; margin:2px 0;">
            {{ strtoupper($term->name) }} RESULT SHEET &mdash; {{ $term->academic_year }}
        </div>

        {{-- ══ STUDENT INFO BAR ══ --}}
        <table class="info-bar" style="margin-top:3px;">
            <tr>
                <td style="width:40%;"><span class="info-label">NAME OF STUDENT:</span>
                    {{ strtoupper($student->full_name) }}</td>
                <td style="width:20%;"><span class="info-label">SESSION:</span> {{ $term->academic_year }}</td>
                <td style="width:20%;"><span class="info-label">CLASS:</span> {{ $class->name ?? '' }}</td>
                <td><span class="info-label">AGE:</span> {{ $age }}</td>
            </tr>
            <tr>
                <td><span class="info-label">ADMISSION NO:</span> {{ $student->admission_number }}</td>
                <td><span class="info-label">NO. IN CLASS:</span> {{ $classSize }}</td>
                <td colspan="2"><span class="info-label">GENDER:</span> {{ strtoupper($student->gender ?? '') }}</td>
            </tr>
            <tr>
                <td><span class="info-label">NO. OF DISTINCTIONS:</span> {{ $distinctions }}</td>
                <td><span class="info-label">NO. OF CREDITS:</span> {{ $credits }}</td>
                <td><span class="info-label">NO. OF PASSES:</span> {{ $passes }}</td>
                <td><span class="info-label">NO. OF FAILED:</span> {{ $failures }}</td>
            </tr>
            <tr>
                <td><span class="info-label">GRAND TOTAL:</span> {{ number_format($myTotal, 1) }}</td>
                <td colspan="2"><span class="info-label">NEXT TERM BEGINS:</span>
                    {{ $publication?->next_term_begins ? $publication->next_term_begins->format('d/m/Y') : 'TBD' }}
                </td>
                <td><span class="info-label">POSITION:</span> {{ jssOrdinal($overallPosition) }} / {{ $classSize }}</td>
            </tr>
        </table>

        <!-- <div class="section-title">&#9733; TERMLY RESULT &#9733;</div> -->
        <div class="section-title">TERMLY RESULT</div>

        {{-- ══ MAIN TWO-COLUMN LAYOUT ══ --}}
        <table class="main-table" style="margin-top:2px;">
            <tr>

                {{-- ── LEFT: SCORE TABLE ── --}}
                <td class="col-scores">
                    <table class="score-table">
                        <thead>
                            {{-- Row 1: group header + rowspan headers --}}
                            <tr>
                                <th rowspan="2"
                                    style="width:22%; text-align:left; padding-left:4px; vertical-align:middle; font-size:7pt; background:#fff;">
                                    SUBJECTS</th>
                                <th colspan="6" style="background:#dce4f5; font-size:6pt; letter-spacing:0.5px;">
                                    CONTINUOUS ASSESSMENT (CA)</th>
                                <th rowspan="2" style="width:6%; background:#dce4f5; vertical-align:middle;">
                                    SUMMARY<br>OF CA<br><em style="font-weight:normal;">/60</em></th>
                                <th rowspan="2" style="width:7%; vertical-align:middle;">END OF<br>TERM EXAM<br><em
                                        style="font-weight:normal;">/40</em></th>
                                <th rowspan="2" style="width:7%; background:#dce4f5; vertical-align:middle;"
                                    class="bold">TOTAL<br>SCORE<br><em style="font-weight:normal;">/100</em></th>
                                <th rowspan="2" style="width:5.5%; vertical-align:middle;">GRADE</th>
                                <th rowspan="2" style="width:4.5%; vertical-align:middle;">POS.</th>
                                <th rowspan="2" style="text-align:left; padding-left:3px; vertical-align:middle;">
                                    SUBJECT MASTER'S<br>REMARK</th>
                                <th rowspan="2" style="width:6%; vertical-align:middle;">SUBJECT<br>MASTER'S<br>SIGN.
                                </th>
                            </tr>
                            {{-- Row 2: 6 CA sub-headers --}}
                            <tr>
                                <th style="width:5.5%;">1ST<br>WKLY EX.<br><em style="font-weight:normal;">/10</em></th>
                                <th style="width:5.5%;">TAKE<br>HOME<br><em style="font-weight:normal;">/10</em></th>
                                <th style="width:5.5%;">COLLEGE<br>QUIZ<br><em style="font-weight:normal;">/10</em></th>
                                <th style="width:5.5%;">PROJECT<br><em style="font-weight:normal;">/10</em></th>
                                <th style="width:5.5%;">2ND<br>WKLY EX.<br><em style="font-weight:normal;">/10</em></th>
                                <th style="width:5.5%;">2ND<br>TAKE HOME<br><em style="font-weight:normal;">/10</em>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($scores as $score)
                            <tr>
                                <td class="subj-name">{{ strtoupper($score->subject->name ?? '—') }}</td>
                                <td>{{ $score->weekly_exercise_1 !== null ? number_format($score->weekly_exercise_1,1) : '' }}
                                </td>
                                <td>{{ $score->take_home !== null ? number_format($score->take_home,1) : '' }}</td>
                                <td>{{ $score->college_quiz !== null ? number_format($score->college_quiz,1) : '' }}
                                </td>
                                <td>{{ $score->project !== null ? number_format($score->project,1) : '' }}</td>
                                <td>{{ $score->weekly_exercise_2 !== null ? number_format($score->weekly_exercise_2,1) : '' }}
                                </td>
                                <td>{{ $score->take_home_2 !== null ? number_format($score->take_home_2,1) : '' }}</td>
                                <td style="background:#F0EFFE;">
                                    {{ $score->summary_ca !== null ? number_format($score->summary_ca,1) : '' }}</td>
                                <td>{{ $score->exam_score !== null ? number_format($score->exam_score,1) : '' }}</td>
                                <td class="bold" style="background:#F0EFFE;">
                                    {{ $score->total_score !== null ? number_format($score->total_score,1) : '' }}</td>
                                <td class="{{ jssGradeClass($score->grade) }}">{{ $score->grade }}</td>
                                <td>{{ jssOrdinal($subjectPositions[$score->subject_id] ?? '-') }}</td>
                                <td class="remark-col">{{ $score->subject_remark ?? $score->grade_remark ?? '' }}</td>
                                <td>&nbsp;</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="14" style="text-align:center; padding:8px; font-style:italic; color:#666;">
                                    No approved scores found for this term.
                                </td>
                            </tr>
                            @endforelse
                            {{-- Blank filler rows for visual consistency --}}
                            @for($i = $scores->count(); $i < 15; $i++) <tr>
                                <td class="subj-name">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td style="background:#F0EFFE;">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td style="background:#F0EFFE;">&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
            </tr>
            @endfor
            </tbody>
        </table>
        </td>

        {{-- ── RIGHT: DOMAINS ONLY (no key ratings for JSS) ── --}}
        <td class="col-domains">

            {{-- Passport photo box --}}
            <table style="width:100%; border-collapse:collapse; margin-bottom:4px;">
                <tr>
                    <td style="border:1px solid #000; height:56px; text-align:center; vertical-align:middle; color:#aaa; font-size:6.5pt;">
                        @if($studentPhotoPath)
                        <img src="{{ $studentPhotoPath }}" style="max-height:56px; max-width:100%; object-fit:cover;" alt="">
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
                        <th colspan="6" style="font-size:6.5pt; padding:3px;">PSYCHOMOTOR DOMAIN</th>
                    </tr>
                    <tr>
                        <th style="width:55%; text-align:left; padding-left:3px;">&nbsp;</th>
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
            <table class="domain-table" style="margin-top:3px;">
                <thead>
                    <tr>
                        <th colspan="6" style="font-size:6.5pt; padding:3px;">AFFECTIVE DOMAIN</th>
                    </tr>
                    <tr>
                        <th style="width:55%; text-align:left; padding-left:3px;">&nbsp;</th>
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

        </td>
        </tr>
        </table>

        {{-- ══ REMARKS + GRADING KEY ══ --}}
        <table class="remarks-table" style="margin-top:3px;">
            <tr>
                <td style="width:65%; vertical-align:top;">
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="padding:2px 0; font-size:6.5pt; border:none;">
                                <span class="bold">FORM MASTER'S/MISTRESS'S REMARKS:</span>
                                @if($publication?->form_master_remarks)
                                <span style="font-size:6pt;">{{ $publication->form_master_remarks }}</span>
                                @else
                                <span
                                    style="display:inline-block; width:68%; border-bottom:1px dotted #000;">&nbsp;</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:2px 0; font-size:6.5pt; border:none;">
                                <span class="bold">HOUSE MASTER'S/MISTRESS'S REMARKS:</span>
                                @if($publication?->house_master_remarks)
                                <span style="font-size:6pt;">{{ $publication->house_master_remarks }}</span>
                                @else
                                <span
                                    style="display:inline-block; width:66%; border-bottom:1px dotted #000;">&nbsp;</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:2px 0; font-size:6.5pt; border:none;">
                                <span class="bold">PRINCIPAL'S REMARKS:</span>
                                @if($publication?->principal_remarks)
                                <span style="font-size:6pt;">{{ $publication->principal_remarks }}</span>
                                @else
                                <span
                                    style="display:inline-block; width:79%; border-bottom:1px dotted #000;">&nbsp;</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0 2px 0; font-size:6.5pt; border:none;">
                                <span class="bold">FORM MASTER'S SIGNATURE:</span>
                                @if(!empty($formTeacherSignPath))
                                <img src="{{ $formTeacherSignPath }}"
                                    style="height:25px; max-width:90px; vertical-align:middle; margin:0 3px;">
                                @else
                                <span
                                    style="display:inline-block; width:34%; border-bottom:1px solid #000;">&nbsp;</span>
                                @endif
                                &nbsp;&nbsp;
                                <span class="bold">DATE:</span>
                                <span
                                    style="font-size:6pt;">{{ $publication?->published_at?->format('d/m/Y') ?? '' }}</span>
                                @if(!$publication?->published_at)
                                <span
                                    style="display:inline-block; width:16%; border-bottom:1px solid #000;">&nbsp;</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:2px 0; font-size:6.5pt; border:none;">
                                <span class="bold">PRINCIPAL'S SIGNATURE:</span>
                                @if(!empty($principalSignPath))
                                <img src="{{ $principalSignPath }}"
                                    style="height:25px; max-width:90px; vertical-align:middle; margin:0 3px;">
                                @else
                                <span
                                    style="display:inline-block; width:38%; border-bottom:1px solid #000;">&nbsp;</span>
                                @endif
                                &nbsp;&nbsp;
                                <span class="bold">DATE:</span>
                                <span
                                    style="font-size:6pt;">{{ $publication?->published_at?->format('d/m/Y') ?? '' }}</span>
                                @if(!$publication?->published_at)
                                <span
                                    style="display:inline-block; width:16%; border-bottom:1px solid #000;">&nbsp;</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width:35%; vertical-align:top; padding-left:5px;">
                    <table class="grade-key-table">
                        <tr>
                            <td colspan="3"
                                style="background:#2A2567; color:#fff; font-weight:bold; font-size:6.5pt; text-align:center; padding:3px;">
                                GRADING SYSTEM
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:bold; width:18%;">GRADE</td>
                            <td style="width:32%;">SCORE</td>
                            <td>REMARK</td>
                        </tr>
                        @foreach($gradingScales as $gs)
                        <tr>
                            <td class="bold {{ jssGradeClass($gs->grade) }}">{{ $gs->grade }}</td>
                            <td>{{ $gs->min_score }} – {{ $gs->max_score }}</td>
                            <td>{{ $gs->remark }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>

        {{-- ── FOOTER ── --}}
        <div class="footer-bar">
            {{ $settings['result_sheet_footer'] ?? 'This result is subject to correction of any clerical error.' }}
            &nbsp;|&nbsp; Printed: {{ now()->format('d M Y, h:i A') }}
        </div>

</body>

</html>