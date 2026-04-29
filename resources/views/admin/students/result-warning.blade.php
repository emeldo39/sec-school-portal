<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Incomplete Result — {{ $student->full_name }}</title>
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: Arial, sans-serif; font-size: 14px;
    background: #F5F4FA; color: #1a1a2e;
    min-height: 100vh; display: flex; align-items: center; justify-content: center;
    padding: 24px;
  }
  .card {
    background: #fff; border-radius: 12px;
    box-shadow: 0 8px 32px rgba(42,37,103,.13);
    max-width: 680px; width: 100%; padding: 0; overflow: hidden;
  }

  /* ── Header ── */
  .card-head {
    background: #fff3cd; border-bottom: 3px solid #f4a900;
    padding: 20px 28px; display: flex; align-items: center; gap: 14px;
  }
  .warn-icon {
    width: 44px; height: 44px; border-radius: 50%;
    background: #f4a900; color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; flex-shrink: 0;
  }
  .card-head h1 { font-size: 17px; font-weight: 700; color: #7a4f00; }
  .card-head p  { font-size: 12px; color: #9c6500; margin-top: 2px; }

  /* ── Student info strip ── */
  .student-strip {
    background: #F0EFFE; border-bottom: 1px solid #EDEEF8;
    padding: 12px 28px; display: flex; align-items: center; gap: 12px;
  }
  .avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: #2A2567; color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 700; flex-shrink: 0;
  }
  .student-strip .name  { font-weight: 700; font-size: 13px; color: #2A2567; }
  .student-strip .meta  { font-size: 11px; color: #7B79A0; }
  .student-strip .term-badge {
    margin-left: auto; background: #2A2567; color: #fff;
    padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 600;
  }

  /* ── Body ── */
  .card-body { padding: 24px 28px; }

  .section { margin-bottom: 20px; }
  .section-title {
    font-size: 11px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .06em; color: #7B79A0; margin-bottom: 10px;
    display: flex; align-items: center; gap: 8px;
  }
  .section-title::after {
    content: ''; flex: 1; height: 1px; background: #EDEEF8;
  }

  /* ── Issue rows ── */
  .issue-list { display: flex; flex-direction: column; gap: 6px; }
  .issue-row {
    display: flex; align-items: center; gap-10px;
    padding: 9px 14px; border-radius: 8px; border: 1px solid;
    font-size: 13px; gap: 10px;
  }
  .issue-row.missing {
    background: #fff8f0; border-color: #ffd8a8; color: #7a3e00;
  }
  .issue-row.pending {
    background: #fffbeb; border-color: #ffe066; color: #664d00;
  }
  .issue-dot {
    width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
  }
  .dot-missing { background: #e8590c; }
  .dot-pending { background: #f4a900; }
  .issue-name  { font-weight: 600; flex: 1; }
  .status-badge {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    padding: 2px 8px; border-radius: 20px; letter-spacing: .04em;
  }
  .badge-draft     { background: #e9ecef; color: #495057; }
  .badge-submitted { background: #fff3cd; color: #856404; }
  .badge-returned  { background: #f8d7da; color: #842029; }

  /* ── Summary counts ── */
  .counts {
    display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;
  }
  .count-pill {
    flex: 1; min-width: 120px; padding: 12px 16px; border-radius: 8px;
    text-align: center;
  }
  .count-pill .num  { font-size: 22px; font-weight: 800; line-height: 1; }
  .count-pill .lbl  { font-size: 11px; color: #888; margin-top: 4px; }
  .pill-orange { background: #fff3e0; }
  .pill-orange .num { color: #e8590c; }
  .pill-yellow { background: #fffbeb; }
  .pill-yellow .num { color: #d4a017; }
  .pill-green  { background: #ebfbee; }
  .pill-green  .num { color: #2f9e44; }

  /* ── Action buttons ── */
  .card-foot {
    padding: 16px 28px 24px; display: flex; gap: 12px;
    border-top: 1px solid #EDEEF8; flex-wrap: wrap;
  }
  .btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 22px; border-radius: 8px; font-size: 13px;
    font-weight: 600; cursor: pointer; text-decoration: none;
    border: none; transition: filter .15s;
  }
  .btn:hover { filter: brightness(.92); }
  .btn-proceed {
    background: #e8590c; color: #fff; flex: 1; justify-content: center;
  }
  .btn-back {
    background: #F0EFFE; color: #2A2567; border: 1.5px solid #BDBAD8;
  }
  .btn-back:hover { background: #e4e1f5; filter: none; }

  .proceed-note {
    font-size: 11px; color: #999; text-align: center;
    margin-top: 8px; width: 100%;
  }
</style>
</head>
<body>

<div class="card">

  {{-- Header --}}
  <div class="card-head">
    <div class="warn-icon">&#9888;</div>
    <div>
      <h1>Incomplete Result Detected</h1>
      <p>Some subjects are missing or have unreviewed scores for this term.</p>
    </div>
  </div>

  {{-- Student strip --}}
  <div class="student-strip">
    <div class="avatar">{{ strtoupper(substr($student->first_name,0,1)) }}</div>
    <div>
      <div class="name">{{ $student->full_name }}</div>
      <div class="meta">{{ $student->admission_number }} &nbsp;·&nbsp; {{ optional($student->schoolClass)->name ?? 'N/A' }}</div>
    </div>
    <span class="term-badge">{{ $term->name }} {{ $term->academic_year }}</span>
  </div>

  <div class="card-body">

    {{-- Summary counts --}}
    @php
      $totalAssigned = \App\Models\TeacherAssignment::where('class_id', $student->class_id)
          ->distinct('subject_id')->count('subject_id');
      $approvedCount = \App\Models\Score::where('student_id', $student->id)
          ->where('class_id', $student->class_id)->where('term_id', $term->id)
          ->whereIn('status',['approved','locked'])->count();
    @endphp
    <div class="counts">
      <div class="count-pill pill-green">
        <div class="num">{{ $approvedCount }}</div>
        <div class="lbl">Approved / Locked</div>
      </div>
      <div class="count-pill pill-yellow">
        <div class="num">{{ $pendingScores->count() }}</div>
        <div class="lbl">Pending Approval</div>
      </div>
      <div class="count-pill pill-orange">
        <div class="num">{{ $missingSubjects->count() }}</div>
        <div class="lbl">No Score Entered</div>
      </div>
    </div>

    {{-- Missing subjects --}}
    @if($missingSubjects->isNotEmpty())
    <div class="section">
      <div class="section-title">&#9888; Subjects with no score entered ({{ $missingSubjects->count() }})</div>
      <div class="issue-list">
        @foreach($missingSubjects as $subject)
        <div class="issue-row missing">
          <span class="issue-dot dot-missing"></span>
          <span class="issue-name">{{ $subject->name }}</span>
          <span class="status-badge badge-draft">Not Started</span>
        </div>
        @endforeach
      </div>
    </div>
    @endif

    {{-- Pending scores --}}
    @if($pendingScores->isNotEmpty())
    <div class="section">
      <div class="section-title">&#8987; Scores pending review / approval ({{ $pendingScores->count() }})</div>
      <div class="issue-list">
        @foreach($pendingScores as $score)
        @php
          $badgeClass = match($score->status) {
            'submitted' => 'badge-submitted',
            'returned'  => 'badge-returned',
            default     => 'badge-draft',
          };
        @endphp
        <div class="issue-row pending">
          <span class="issue-dot dot-pending"></span>
          <span class="issue-name">{{ $score->subject->name ?? '—' }}</span>
          <span class="status-badge {{ $badgeClass }}">{{ ucfirst($score->status) }}</span>
        </div>
        @endforeach
      </div>
    </div>
    @endif

  </div>

  {{-- Action buttons --}}
  <div class="card-foot">
    <a href="javascript:window.close();" class="btn btn-back">
      &#8592; Go Back
    </a>
    <a href="{{ route('admin.students.result-pdf', $student) }}?term_id={{ $term->id }}&confirm=1"
       class="btn btn-proceed" target="_self">
      &#8681; Download Anyway (Incomplete)
    </a>
    <span class="proceed-note">
      The PDF will include an "INCOMPLETE RESULT" notice for the missing/pending subjects.
    </span>
  </div>

</div>

</body>
</html>
