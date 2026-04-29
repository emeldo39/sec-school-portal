<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; font-size: 9.5pt; color: #111; }

    /* ── Page Layout ── */
    .page { width: 100%; padding: 14px 18px; }

    /* ── Header ── */
    .header { display: flex; align-items: center; border-bottom: 3px solid #2A2567; padding-bottom: 8px; margin-bottom: 8px; }
    .header .logo { width: 64px; height: 64px; margin-right: 12px; }
    .header .logo img { width: 64px; height: 64px; object-fit: contain; }
    .header .school-info { flex: 1; text-align: center; }
    .header .school-name { font-size: 14pt; font-weight: bold; color: #2A2567; text-transform: uppercase; letter-spacing: .5px; }
    .header .school-motto { font-size: 9pt; color: #F4BC67; font-style: italic; margin: 2px 0; }
    .header .school-address { font-size: 8pt; color: #555; }
    .header .report-title { width: 90px; text-align: right; }
    .header .report-title span { background: #2A2567; color: #fff; font-size: 8pt; font-weight: bold; padding: 3px 7px; border-radius: 3px; text-transform: uppercase; }

    /* ── Student Info Bar ── */
    .info-bar { background: #f0eef9; border-radius: 4px; padding: 6px 12px; margin-bottom: 8px; display: flex; flex-wrap: wrap; gap: 16px; }
    .info-item { font-size: 8.5pt; }
    .info-item strong { color: #2A2567; }
    .info-item span { font-weight: bold; }

    /* ── Score Table ── */
    .score-table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
    .score-table th, .score-table td { border: 1px solid #ccc; padding: 4px 5px; text-align: center; vertical-align: middle; font-size: 8.5pt; }
    .score-table thead tr th { background: #2A2567; color: #fff; font-weight: bold; font-size: 8pt; }
    .score-table tbody tr:nth-child(even) { background: #f9f8ff; }
    .score-table tbody tr td:first-child { text-align: left; padding-left: 8px; }
    .score-table .total-row td { background: #2A2567; color: #fff; font-weight: bold; }
    .score-table .avg-row td { background: #e8e6f5; font-weight: bold; color: #2A2567; }

    /* ── Grade badge ── */
    .grade-a1 { color: #155724; font-weight: bold; }
    .grade-b  { color: #004085; font-weight: bold; }
    .grade-c  { color: #856404; font-weight: bold; }
    .grade-d  { color: #721c24; font-weight: bold; }
    .grade-f  { color: #fff; background: #dc3545; padding: 1px 4px; border-radius: 2px; font-weight: bold; }

    /* ── Domains Section ── */
    .domains-wrap { display: flex; gap: 10px; margin-bottom: 8px; }
    .domain-box { flex: 1; }
    .domain-box table { width: 100%; border-collapse: collapse; }
    .domain-box table th { background: #2A2567; color: #fff; font-size: 8pt; padding: 4px 6px; text-align: left; }
    .domain-box table td { border: 1px solid #ddd; padding: 3px 6px; font-size: 8pt; }
    .domain-box table td:last-child { text-align: center; font-weight: bold; width: 36px; }
    .domain-title { font-size: 8pt; font-weight: bold; color: #2A2567; margin-bottom: 3px; text-transform: uppercase; }

    /* ── Attendance & Summary ── */
    .bottom-row { display: flex; gap: 10px; margin-bottom: 10px; }
    .att-box, .summary-box, .grade-key-box { border: 1px solid #ccc; border-radius: 4px; padding: 6px 10px; font-size: 8pt; }
    .att-box { flex: 0 0 130px; }
    .summary-box { flex: 1; }
    .grade-key-box { flex: 0 0 180px; }
    .box-title { font-weight: bold; color: #2A2567; margin-bottom: 4px; font-size: 8pt; text-transform: uppercase; }
    .att-row { display: flex; justify-content: space-between; margin-bottom: 2px; }

    /* ── Signatures ── */
    .signatures { display: flex; gap: 20px; margin-top: 6px; }
    .sig-block { flex: 1; text-align: center; }
    .sig-block .sig-line { border-top: 1px solid #333; margin-top: 24px; padding-top: 3px; font-size: 8pt; }

    /* ── Footer ── */
    .footer-note { margin-top: 8px; padding-top: 6px; border-top: 1px solid #ddd; font-size: 7.5pt; color: #777; font-style: italic; text-align: center; }
    .gold-stripe { height: 4px; background: #F4BC67; margin: 4px 0; border-radius: 2px; }
</style>
</head>
<body>
<div class="page">

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="logo">
            @if(!empty($schoolSettings['school_logo']))
                <img src="{{ public_path('storage/' . $schoolSettings['school_logo']) }}" alt="Logo">
            @endif
        </div>
        <div class="school-info">
            <div class="school-name">{{ $schoolSettings['school_name'] ?? 'DIVINE ROYAL INT\'L COLLEGE' }}</div>
            <div class="school-motto">{{ $schoolSettings['school_motto'] ?? 'Light of the World' }}</div>
            <div class="school-address">{{ $schoolSettings['school_address'] ?? '' }}</div>
        </div>
        <div class="report-title">
            <span>Student Result</span>
        </div>
    </div>
    <div class="gold-stripe"></div>

    {{-- ── STUDENT INFO ── --}}
    <div class="info-bar">
        <div class="info-item">Student Name: <span>{{ $student->full_name }}</span></div>
        <div class="info-item">Admission No.: <span>{{ $student->admission_number }}</span></div>
        <div class="info-item">Class: <span>{{ $formClass->name }}</span></div>
        <div class="info-item">Term: <span>{{ $term->name }}</span></div>
        <div class="info-item">Session: <span>{{ $term->academic_year }}</span></div>
        <div class="info-item">Gender: <span class="text-capitalize">{{ $student->gender }}</span></div>
    </div>

    {{-- ── SCORE TABLE ── --}}
    @php
        $totalSum = 0;
        $count    = 0;
        $subjectCount = $scores->count();
    @endphp
    <table class="score-table">
        <thead>
            <tr>
                <th style="text-align:left;width:30%;">Subject</th>
                <th style="width:9%;">1st CA<br>/30</th>
                <th style="width:9%;">2nd CA<br>/30</th>
                <th style="width:9%;">Exam<br>/40</th>
                <th style="width:9%;">Total<br>/100</th>
                <th style="width:7%;">Grade</th>
                <th style="width:11%;">Remark</th>
                <th style="width:16%;">Subject Pos.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scores as $score)
            @php
                $total = $score->total_score ?? 0;
                $totalSum += $total;
                $count++;
                $grade = $score->grade;
                $gradeClass = str_starts_with($grade, 'A') ? 'grade-a1'
                    : (str_starts_with($grade, 'B') ? 'grade-b'
                    : (str_starts_with($grade, 'C') ? 'grade-c'
                    : (str_starts_with($grade, 'D') ? 'grade-d' : 'grade-f')));
            @endphp
            <tr>
                <td>{{ $score->subject->name ?? '—' }}</td>
                <td>{{ $score->ca_score ?? '' }}</td>
                <td>{{ $score->ca_score_2 ?? '' }}</td>
                <td>{{ $score->exam_score ?? '' }}</td>
                <td><strong>{{ $score->total_score ?? '' }}</strong></td>
                <td><span class="{{ $gradeClass }}">{{ $grade }}</span></td>
                <td>{{ $score->grade_remark }}</td>
                <td></td>
            </tr>
            @endforeach

            {{-- Totals row --}}
            @php $average = $count > 0 ? round($totalSum / $count, 1) : 0; @endphp
            <tr class="avg-row">
                <td style="text-align:left;"><strong>TOTAL / AVERAGE</strong></td>
                <td colspan="3" style="text-align:right;padding-right:8px;">Subjects Sat: {{ $count }}</td>
                <td><strong>{{ $totalSum }}</strong></td>
                <td colspan="3"><strong>Average: {{ $average }}</strong></td>
            </tr>
        </tbody>
    </table>

    {{-- ── DOMAINS + ATTENDANCE + GRADE KEY ── --}}
    <div class="domains-wrap">
        {{-- Psychomotor --}}
        <div class="domain-box">
            <div class="domain-title">Psychomotor Domain</div>
            <table>
                <tr><th>Skill</th><th>Rating</th></tr>
                @foreach(['Handwriting','Drawing/Painting','Sport/Games','Laboratory Skills','Computer Skills'] as $skill)
                <tr><td>{{ $skill }}</td><td></td></tr>
                @endforeach
            </table>
        </div>
        {{-- Affective --}}
        <div class="domain-box">
            <div class="domain-title">Affective Domain</div>
            <table>
                <tr><th>Trait</th><th>Rating</th></tr>
                @foreach(['Punctuality','Attentiveness','Neatness','Honesty','Politeness','Co-operation'] as $trait)
                <tr><td>{{ $trait }}</td><td></td></tr>
                @endforeach
            </table>
        </div>
        {{-- Attendance & Summary --}}
        <div style="flex:1; display:flex; flex-direction:column; gap:8px;">
            <div class="att-box" style="flex:0 0 auto;">
                <div class="box-title">Attendance</div>
                <div class="att-row"><span>Days in Term:</span><span><strong>{{ $attendanceDays ?: '—' }}</strong></span></div>
                <div class="att-row"><span>Days Present:</span><span><strong>{{ $presentDays ?: '—' }}</strong></span></div>
                <div class="att-row"><span>Days Absent:</span><span><strong>{{ $attendanceDays && $presentDays !== null ? $attendanceDays - $presentDays : '—' }}</strong></span></div>
            </div>
            <div class="summary-box">
                <div class="box-title">Summary</div>
                <div class="att-row"><span>Subjects:</span><span><strong>{{ $count }}</strong></span></div>
                <div class="att-row"><span>Total Score:</span><span><strong>{{ $totalSum }}</strong></span></div>
                <div class="att-row"><span>Average:</span><span><strong>{{ $average }}</strong></span></div>
                <div class="att-row"><span>Position:</span><span><strong>&nbsp;</strong></span></div>
            </div>
        </div>
        {{-- Grade Key --}}
        <div class="grade-key-box">
            <div class="box-title">Grading Scale</div>
            @foreach($gradingScales as $gs)
            <div class="att-row">
                <span><strong>{{ $gs->grade }}</strong> — {{ $gs->remark }}</span>
                <span>{{ $gs->min_score }}–{{ $gs->max_score }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── TEACHER COMMENT ── --}}
    <div style="border:1px solid #ccc;border-radius:4px;padding:6px 10px;margin-bottom:8px;font-size:8.5pt;">
        <strong style="color:#2A2567;">Form Teacher's Comment:</strong>
        <div style="margin-top:14px;border-bottom:1px dotted #ccc;"></div>
    </div>

    {{-- ── SIGNATURES ── --}}
    <div class="signatures">
        <div class="sig-block">
            <div class="sig-line">Form Teacher's Signature</div>
        </div>
        <div class="sig-block">
            <div class="sig-line">Date</div>
        </div>
        <div class="sig-block">
            <div class="sig-line">Principal / Vice-Principal</div>
        </div>
        <div class="sig-block">
            <div class="sig-line">School Stamp</div>
        </div>
    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer-note">
        {{ $schoolSettings['result_sheet_footer'] ?? 'This result is subject to correction of any clerical error.' }}
        &nbsp;&nbsp;&bull;&nbsp;&nbsp;
        Generated: {{ now()->format('d M Y') }}
    </div>

</div>
</body>
</html>
