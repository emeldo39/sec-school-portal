<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\ActivityLog;
use App\Models\GradingScale;
use App\Models\SchoolClass;
use App\Models\Score;
use App\Models\Student;
use App\Models\ResultPublication;
use App\Models\TeacherAssignment;
use App\Http\Controllers\Teacher\ResultController as TeacherResultController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('schoolClass');

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($w) use ($q) {
                $w->where('first_name', 'like', "%{$q}%")
                  ->orWhere('last_name', 'like', "%{$q}%")
                  ->orWhere('admission_number', 'like', "%{$q}%");
            });
        }

        $students = $query->orderBy('last_name')->paginate(25)->withQueryString();
        $classes  = SchoolClass::orderBy('level')->orderBy('name')->get();

        return view('admin.students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes        = SchoolClass::orderBy('level')->orderBy('name')->get();
        $lastAdmission  = Student::orderByDesc('id')->value('admission_number');
        return view('admin.students.create', compact('classes', 'lastAdmission'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'admission_number' => 'required|string|max:30|unique:students,admission_number',
            'first_name'       => 'required|string|max:60',
            'last_name'        => 'required|string|max:60',
            'date_of_birth'    => 'required|date',
            'gender'           => 'required|in:male,female',
            'class_id'         => 'required|exists:classes,id',
            'guardian_name'    => 'required|string|max:100',
            'guardian_phone'   => 'required|string|max:20',
            'photo'            => 'nullable|image|max:2048',
            'status'           => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student = Student::create($data);

        ActivityLog::record('admin_create_student', "Created student: {$student->full_name} ({$student->admission_number})");

        return redirect()->route('admin.students.index')
            ->with('success', "Student {$student->full_name} added successfully.");
    }

    public function show(Student $student)
    {
        $student->load(['schoolClass', 'scores.subject', 'scores.term']);

        $termsWithScores = AcademicTerm::whereHas('scores', function ($q) use ($student) {
            $q->where('student_id', $student->id)->whereIn('status', ['approved', 'locked']);
        })->orderByDesc('academic_year')->orderBy('name')->get();

        return view('admin.students.show', compact('student', 'termsWithScores'));
    }

    public function resultPdf(Request $request, Student $student)
    {
        $request->validate(['term_id' => 'required|integer|exists:academic_terms,id']);

        $term  = AcademicTerm::findOrFail($request->term_id);
        $class = $student->schoolClass;
        $isJss = $class && $class->level === 'JSS';

        // ── Completeness check ───────────────────────────────────────
        // Subjects assigned to this class by any teacher
        $assignedSubjectIds = TeacherAssignment::where('class_id', $student->class_id)
            ->distinct()->pluck('subject_id');

        // All score rows for this student in this term (any status)
        $allScores = Score::with('subject')
            ->where('student_id', $student->id)
            ->where('class_id', $student->class_id)
            ->where('term_id', $term->id)
            ->get();
        // Stamp schoolClass on every score so getGradeAttribute uses the right scale
        $allScores->each(fn($s) => $s->setRelation('schoolClass', $class));

        $enteredSubjectIds = $allScores->pluck('subject_id');

        // Subjects with no score entry at all
        $missingSubjects = \App\Models\Subject::whereIn('id',
                $assignedSubjectIds->diff($enteredSubjectIds)
            )->orderBy('name')->get();

        // Scores entered but not yet approved/locked
        $pendingScores = $allScores->filter(
            fn ($s) => in_array($s->status, ['draft', 'submitted', 'returned'])
        )->sortBy('subject.name')->values();

        $hasIssues = $missingSubjects->isNotEmpty() || $pendingScores->isNotEmpty();

        // Show warning page unless admin explicitly confirmed
        if ($hasIssues && !$request->boolean('confirm')) {
            return view('admin.students.result-warning', compact(
                'student', 'term', 'missingSubjects', 'pendingScores'
            ));
        }

        // ── Approved / locked scores only (for the actual PDF) ───────
        $scores = $allScores->filter(
            fn ($s) => in_array($s->status, ['approved', 'locked'])
        )->sortBy('subject.name')->values();

        // ── School settings ─────────────────────────────────────────
        $settings = DB::table('school_settings')->pluck('value', 'key');

        // ── Grading scales (level-specific for the result PDF key) ──
        $gradingScales = GradingScale::where('level', $isJss ? 'JSS' : 'SSS')
            ->orderByDesc('min_score')->get();

        // ── Statistics (prefix-based: works for both JSS A/B/C and SSS A1/B2…) ─
        $distinctions = $scores->filter(fn($s) => str_starts_with($s->grade, 'A'))->count();
        $credits      = $scores->filter(fn($s) => str_starts_with($s->grade, 'B') || str_starts_with($s->grade, 'C'))->count();
        $passes       = $scores->filter(fn($s) => str_starts_with($s->grade, 'D') || str_starts_with($s->grade, 'E'))->count();
        $failures     = $scores->filter(fn($s) => str_starts_with($s->grade, 'F'))->count();

        // ── Class size ───────────────────────────────────────────────
        $classSize = Student::where('class_id', $student->class_id)
            ->where('status', 'active')->count();

        // ── Per-subject positions ────────────────────────────────────
        $subjectPositions = [];
        foreach ($scores as $score) {
            $better = Score::where('class_id', $student->class_id)
                ->where('term_id', $term->id)
                ->where('subject_id', $score->subject_id)
                ->whereIn('status', ['approved', 'locked'])
                ->where('total_score', '>', $score->total_score ?? 0)
                ->count();
            $subjectPositions[$score->subject_id] = $better + 1;
        }

        // ── Overall class position ───────────────────────────────────
        $myTotal   = $scores->sum('total_score');
        $allTotals = Score::where('class_id', $student->class_id)
            ->where('term_id', $term->id)
            ->whereIn('status', ['approved', 'locked'])
            ->groupBy('student_id')
            ->selectRaw('student_id, SUM(total_score) as grand_total')
            ->get()->sortByDesc('grand_total')->values();
        $posIdx          = $allTotals->search(fn ($r) => $r->student_id == $student->id);
        $overallPosition = $posIdx !== false ? $posIdx + 1 : '-';

        // ── Form teacher / logo path ─────────────────────────────────
        $formTeacher = $class?->formTeacher;
        $logoPath    = $settings['school_logo']
            ? TeacherResultController::imageToDataUri(storage_path('app/public/' . $settings['school_logo']))
            : null;

        $studentPhotoPath = $student->photo
            ? TeacherResultController::imageToDataUri(storage_path('app/public/' . $student->photo))
            : null;

        // ── Signature paths (base64 data URIs for DomPDF) ────────────
        $principalUser      = \App\Models\User::where('role', 'principal')->first();
        $principalSignPath  = ($principalUser?->signature)
            ? TeacherResultController::imageToDataUri(storage_path('app/public/' . $principalUser->signature))
            : null;
        $formTeacherSignPath = ($formTeacher?->signature)
            ? TeacherResultController::imageToDataUri(storage_path('app/public/' . $formTeacher->signature))
            : null;

        $age      = $student->date_of_birth ? $student->date_of_birth->age : '-';
        $warnings = $hasIssues ? compact('missingSubjects', 'pendingScores') : null;

        $publication      = ResultPublication::where('student_id', $student->id)
            ->where('term_id', $term->id)->first();
        $psychomotorItems = TeacherResultController::$psychomotorItems;
        $affectiveItems   = TeacherResultController::$affectiveItems;

        $data = compact(
            'student', 'term', 'class', 'scores', 'settings', 'gradingScales',
            'distinctions', 'credits', 'passes', 'failures',
            'classSize', 'overallPosition', 'subjectPositions',
            'formTeacher', 'isJss', 'age', 'myTotal', 'logoPath', 'warnings',
            'principalSignPath', 'formTeacherSignPath', 'studentPhotoPath',
            'publication', 'psychomotorItems', 'affectiveItems'
        );

        $view        = $isJss ? 'admin.students.result-pdf-jss' : 'admin.students.result-pdf-sss';
        $orientation = $isJss ? 'landscape' : 'portrait';

        $pdf = Pdf::loadView($view, $data)->setPaper('a4', $orientation);

        $filename = preg_replace('/[\/\\\\ ]/', '-',
            'result_' . $student->admission_number . '_' . $term->academic_year . '_' . $term->name
        ) . '.pdf';

        return $pdf->stream($filename);
    }

    public function edit(Student $student)
    {
        $classes = SchoolClass::orderBy('level')->orderBy('name')->get();
        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'admission_number' => "required|string|max:30|unique:students,admission_number,{$student->id}",
            'first_name'       => 'required|string|max:60',
            'last_name'        => 'required|string|max:60',
            'date_of_birth'    => 'required|date',
            'gender'           => 'required|in:male,female',
            'class_id'         => 'required|exists:classes,id',
            'guardian_name'    => 'required|string|max:100',
            'guardian_phone'   => 'required|string|max:20',
            'photo'            => 'nullable|image|max:2048',
            'status'           => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('photo')) {
            if ($student->photo) Storage::disk('public')->delete($student->photo);
            $data['photo'] = $request->file('photo')->store('students', 'public');
        }

        $student->update($data);

        ActivityLog::record('admin_update_student', "Updated student: {$student->full_name}");

        return redirect()->route('admin.students.index')
            ->with('success', "Student {$student->full_name} updated successfully.");
    }

    public function destroy(Student $student)
    {
        $name = $student->full_name;
        $admNo = $student->admission_number;

        if ($student->photo) Storage::disk('public')->delete($student->photo);
        $student->delete();

        ActivityLog::record('admin_delete_student', "Deleted student: {$name} ({$admNo})");

        return redirect()->route('admin.students.index')
            ->with('success', "Student {$name} has been deleted.");
    }

    public function promote(Request $request, Student $student)
    {
        $request->validate([
            'new_class_id' => 'required|exists:classes,id',
        ]);

        $old = optional($student->schoolClass)->name ?? 'N/A';
        $student->update(['class_id' => $request->new_class_id]);
        $new = optional($student->fresh()->schoolClass)->name ?? 'N/A';

        ActivityLog::record('admin_promote_student', "Promoted {$student->full_name} from {$old} to {$new}");

        return back()->with('success', "{$student->full_name} promoted to {$new}.");
    }

    // ── Bulk Promotion ────────────────────────────────────────────────

    public function promotionPreview()
    {
        $classes      = SchoolClass::orderBy('level')->orderBy('name')->get();
        $promotionMap = $this->buildPromotionMap($classes);

        // Only active students, grouped by class_id
        $studentsByClass = Student::where('status', 'active')
            ->with('schoolClass')
            ->orderBy('last_name')->orderBy('first_name')
            ->get()
            ->groupBy('class_id');

        // Summary counts for the info banner
        $totalStudents  = $studentsByClass->flatten()->count();
        $graduateCount  = 0;
        $promoteCount   = 0;
        foreach ($promotionMap as $classId => $map) {
            $cnt = $studentsByClass->get($classId, collect())->count();
            if ($map['graduate']) {
                $graduateCount += $cnt;
            } else {
                $promoteCount  += $cnt;
            }
        }

        return view('admin.students.promote', compact(
            'classes', 'promotionMap', 'studentsByClass',
            'totalStudents', 'promoteCount', 'graduateCount'
        ));
    }

    public function promotionExecute(Request $request)
    {
        $actions = $request->input('actions', []); // [student_id => 'promote'|'repeat'|'deactivate']

        if (empty($actions)) {
            return back()->with('error', 'No students were included in the promotion.');
        }

        $classes      = SchoolClass::all();
        $promotionMap = $this->buildPromotionMap($classes);

        $promoted    = 0;
        $graduated   = 0;
        $repeated    = 0;
        $deactivated = 0;
        $details     = [];

        foreach ($actions as $studentId => $action) {
            $student = Student::with('schoolClass')->find((int) $studentId);
            if (! $student) continue;

            if ($action === 'promote') {
                $map = $promotionMap[$student->class_id] ?? null;

                if ($map && ! $map['graduate'] && $map['target']) {
                    $oldName = $student->schoolClass->name ?? '?';
                    $newName = $map['target']->name;
                    $student->update(['class_id' => $map['target']->id]);
                    $promoted++;
                    $details[] = "{$student->full_name}: {$oldName} → {$newName}";

                } elseif ($map && $map['graduate']) {
                    $student->update(['status' => 'inactive']);
                    $graduated++;
                    $details[] = "{$student->full_name}: Graduated (inactive)";
                }
                // If no map exists, treat as repeat (class stays same)

            } elseif ($action === 'deactivate') {
                $student->update(['status' => 'inactive']);
                $deactivated++;
                $details[] = "{$student->full_name}: Deactivated";

            } else {
                // 'repeat' — stay in current class, no change
                $repeated++;
            }
        }

        $summary = implode('; ', array_slice($details, 0, 20));
        ActivityLog::record(
            'admin_bulk_promote',
            "Bulk promotion: {$promoted} promoted, {$graduated} graduated, " .
            "{$deactivated} deactivated, {$repeated} held back. {$summary}"
        );

        $msg = "Bulk promotion complete — "
             . "{$promoted} promoted, {$graduated} graduated, "
             . "{$deactivated} deactivated, {$repeated} held back.";

        return redirect()->route('admin.students.index')->with('success', $msg);
    }

    /**
     * Build a map of class_id → ['target' => SchoolClass|null, 'graduate' => bool]
     * Matches streams by extracting (level)(year)(suffix) from the class name.
     * e.g. JSS1A→JSS2A, JSS3A→SS1A, SS3A→graduate
     */
    private function buildPromotionMap($classes): array
    {
        // Lookup: [LEVEL][year][stream] => SchoolClass
        $lookup = [];
        foreach ($classes as $class) {
            if (preg_match('/^(JSS|SS)(\d+)(.*)$/i', $class->name, $m)) {
                $lookup[strtoupper($m[1])][(int) $m[2]][$m[3]] = $class;
            }
        }

        $map = [];
        foreach ($classes as $class) {
            if (! preg_match('/^(JSS|SS)(\d+)(.*)$/i', $class->name, $m)) continue;

            $level    = strtoupper($m[1]);
            $year     = (int) $m[2];
            $stream   = $m[3];
            $nextYear = $year + 1;

            if ($level === 'JSS') {
                if (isset($lookup['JSS'][$nextYear][$stream])) {
                    // JSS1→JSS2, JSS2→JSS3
                    $map[$class->id] = ['target' => $lookup['JSS'][$nextYear][$stream], 'graduate' => false];
                } elseif (isset($lookup['SS'][1][$stream])) {
                    // JSS3→SS1 (cross-level)
                    $map[$class->id] = ['target' => $lookup['SS'][1][$stream], 'graduate' => false];
                }
                // else: no target found for this class
            } elseif ($level === 'SS') {
                if (isset($lookup['SS'][$nextYear][$stream])) {
                    // SS1→SS2, SS2→SS3
                    $map[$class->id] = ['target' => $lookup['SS'][$nextYear][$stream], 'graduate' => false];
                } else {
                    // SS3→Graduate
                    $map[$class->id] = ['target' => null, 'graduate' => true];
                }
            }
        }

        return $map;
    }
}
