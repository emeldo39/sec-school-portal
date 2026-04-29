<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\ActivityLog;
use App\Models\GradingScale;
use App\Models\ResultPublication;
use App\Models\Score;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ResultController extends Controller
{
    /** Psychomotor & affective item lists (shared across form + PDFs). */
    public static array $psychomotorItems = [
        'Hand Writing',
        'Games / Sports',
        'Manual Dexterity',
        'Drawing and Painting',
        'Crafts',
        'Musical Skill',
    ];

    public static array $affectiveItems = [
        'Punctuality',
        'Attendance at Class',
        'Politeness',
        'Neatness',
        'Communication',
        'Relationship with Peers',
        'Relationship with Teachers',
        'Spirit of Co-operation',
        'Carrying out Instructions',
        'Attentiveness',
        'Honesty',
        'Obedience',
    ];

    public function index(Request $request)
    {
        $teacher = auth()->user();
        abort_if(!$teacher->is_form_teacher, 403, 'Only form teachers can access result sheets.');

        $formClass      = $teacher->formClass;
        $terms          = AcademicTerm::orderByDesc('academic_year')->get();
        $currentTerm    = AcademicTerm::current();
        $selectedTermId = $request->input('term_id', $currentTerm?->id);

        $students = Student::where('class_id', $formClass->id)
            ->where('status', 'active')
            ->orderBy('last_name')
            ->get();

        // Subjects assigned to this class (for completeness check)
        $assignedSubjectIds = TeacherAssignment::where('class_id', $formClass->id)
            ->distinct()->pluck('subject_id');

        // Load existing publications for selected term
        $publications = $selectedTermId
            ? ResultPublication::where('term_id', $selectedTermId)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()->keyBy('student_id')
            : collect();

        $studentSummaries = $students->map(function ($student) use ($selectedTermId, $formClass, $assignedSubjectIds, $publications) {
            $scores = Score::with('subject')
                ->where('student_id', $student->id)
                ->where('class_id', $formClass->id)
                ->when($selectedTermId, fn($q) => $q->where('term_id', $selectedTermId))
                ->whereIn('status', ['approved', 'locked'])
                ->get();

            $approvedSubjectIds = $scores->pluck('subject_id');
            $isComplete = $selectedTermId
                && $assignedSubjectIds->isNotEmpty()
                && $assignedSubjectIds->diff($approvedSubjectIds)->isEmpty();

            return [
                'student'     => $student,
                'score_count' => $scores->count(),
                'average'     => $scores->count() > 0 ? round($scores->avg('total_score'), 1) : null,
                'is_complete' => $isComplete,
                'publication' => $publications->get($student->id),
            ];
        });

        return view('teacher.results.index', compact(
            'formClass', 'terms', 'currentTerm',
            'selectedTermId', 'studentSummaries'
        ));
    }

    public function publishForm(Request $request)
    {
        $teacher = auth()->user();
        abort_if(!$teacher->is_form_teacher, 403);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'term_id'    => 'required|exists:academic_terms,id',
        ]);

        $student = Student::with('schoolClass')->findOrFail($request->student_id);
        $term    = AcademicTerm::findOrFail($request->term_id);
        $class   = $teacher->formClass;

        abort_if(!$class, 403, 'No form class is assigned to your account.');
        abort_if($student->class_id !== $class->id, 403, 'Student not in your form class.');

        // Must be complete before publishing
        $assignedSubjectIds = TeacherAssignment::where('class_id', $class->id)
            ->distinct()->pluck('subject_id');
        $approvedSubjectIds = Score::where('student_id', $student->id)
            ->where('class_id', $class->id)
            ->where('term_id', $term->id)
            ->whereIn('status', ['approved', 'locked'])
            ->pluck('subject_id');

        abort_if(
            $assignedSubjectIds->isEmpty(),
            422,
            'No subjects are assigned to this class. Please assign subjects before publishing results.'
        );

        abort_if(
            $assignedSubjectIds->diff($approvedSubjectIds)->isNotEmpty(),
            422,
            'Result is not complete — some subjects are missing approved scores.'
        );

        $publication = ResultPublication::where('student_id', $student->id)
            ->where('term_id', $term->id)
            ->first();

        $psychomotorItems = self::$psychomotorItems;
        $affectiveItems   = self::$affectiveItems;

        return view('teacher.results.publish', compact(
            'student', 'term', 'class', 'publication',
            'psychomotorItems', 'affectiveItems'
        ));
    }

    public function storePublication(Request $request)
    {
        $teacher = auth()->user();
        abort_if(!$teacher->is_form_teacher, 403);

        $data = $request->validate([
            'student_id'           => 'required|exists:students,id',
            'term_id'              => 'required|exists:academic_terms,id',
            'next_term_begins'     => 'nullable|date',
            'psychomotor'          => 'nullable|array',
            'psychomotor.*'        => 'nullable|integer|between:1,5',
            'affective'            => 'nullable|array',
            'affective.*'          => 'nullable|integer|between:1,5',
            'form_master_remarks'  => 'nullable|string|max:500',
            'house_master_remarks' => 'nullable|string|max:500',
        ]);

        $student   = Student::findOrFail($data['student_id']);
        $formClass = $teacher->formClass;
        abort_if(!$formClass, 403, 'No form class is assigned to your account.');
        abort_if($student->class_id !== $formClass->id, 403);

        $publication = ResultPublication::firstOrNew([
            'student_id' => $data['student_id'],
            'term_id'    => $data['term_id'],
        ]);

        if (!$publication->exists) {
            $publication->token        = Str::random(48);
            $publication->published_by = $teacher->id;
            $publication->published_at = now();
        }

        $publication->fill([
            'next_term_begins'     => $data['next_term_begins'] ?? null,
            'psychomotor'          => $data['psychomotor'] ?? null,
            'affective'            => $data['affective'] ?? null,
            'form_master_remarks'  => $data['form_master_remarks'] ?? null,
            'house_master_remarks' => $data['house_master_remarks'] ?? null,
        ]);

        $publication->save();

        ActivityLog::record(
            'teacher_publish_result',
            "Published result for {$student->full_name} — term_id:{$data['term_id']}"
        );

        return redirect()->route('teacher.results.index', ['term_id' => $data['term_id']])
            ->with('success', "Result for {$student->full_name} published. Share the public link with the parent.");
    }

    public function generate(Request $request)
    {
        $teacher = auth()->user();
        abort_if(!$teacher->is_form_teacher, 403);

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'term_id'    => 'required|exists:academic_terms,id',
        ]);

        $student = Student::with('schoolClass')->findOrFail($request->student_id);
        $term    = AcademicTerm::findOrFail($request->term_id);
        $class   = $teacher->formClass;

        abort_if($student->class_id !== $class->id, 403, 'Student not in your form class.');

        return $this->buildPdf($student, $term, $class, $request->boolean('confirm'));
    }

    /**
     * Shared PDF build logic used by generate() and the public PDF route.
     */
    public static function buildPdf(Student $student, AcademicTerm $term, \App\Models\SchoolClass $class, bool $skipWarning = false, ?ResultPublication $publication = null)
    {
        $assignedSubjectIds = TeacherAssignment::where('class_id', $class->id)
            ->distinct()->pluck('subject_id');

        $allScores = Score::with('subject')
            ->where('student_id', $student->id)
            ->where('class_id', $class->id)
            ->where('term_id', $term->id)
            ->get();
        $allScores->each(fn($s) => $s->setRelation('schoolClass', $class));

        $enteredSubjectIds = $allScores->pluck('subject_id');
        $missingSubjects   = Subject::whereIn('id', $assignedSubjectIds->diff($enteredSubjectIds))
            ->orderBy('name')->get();
        $pendingScores     = $allScores->filter(
            fn($s) => in_array($s->status, ['draft', 'submitted', 'returned'])
        )->sortBy('subject.name')->values();
        $hasIssues = $missingSubjects->isNotEmpty() || $pendingScores->isNotEmpty();

        if ($hasIssues && !$skipWarning) {
            return view('teacher.results.warning', compact('student', 'term', 'missingSubjects', 'pendingScores'));
        }

        $scores = $allScores->filter(
            fn($s) => in_array($s->status, ['approved', 'locked'])
        )->sortBy(fn($s) => $s->subject->name)->values();

        $settings = DB::table('school_settings')->pluck('value', 'key');

        $isJss         = $class->level === 'JSS';
        $gradingScales = GradingScale::where('level', $isJss ? 'JSS' : 'SSS')
            ->orderByDesc('min_score')->get();

        $distinctions = $scores->filter(fn($s) => str_starts_with($s->grade, 'A'))->count();
        $credits      = $scores->filter(fn($s) => str_starts_with($s->grade, 'B') || str_starts_with($s->grade, 'C'))->count();
        $passes       = $scores->filter(fn($s) => str_starts_with($s->grade, 'D') || str_starts_with($s->grade, 'E'))->count();
        $failures     = $scores->filter(fn($s) => str_starts_with($s->grade, 'F'))->count();

        $classSize = Student::where('class_id', $student->class_id)->where('status', 'active')->count();

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

        $myTotal   = $scores->sum('total_score');
        $allTotals = Score::where('class_id', $student->class_id)
            ->where('term_id', $term->id)
            ->whereIn('status', ['approved', 'locked'])
            ->groupBy('student_id')
            ->selectRaw('student_id, SUM(total_score) as grand_total')
            ->get()->sortByDesc('grand_total')->values();
        $posIdx          = $allTotals->search(fn($r) => $r->student_id == $student->id);
        $overallPosition = $posIdx !== false ? $posIdx + 1 : '-';

        $formTeacher = $class->formTeacher;

        $logoPath = $settings['school_logo']
            ? self::imageToDataUri(storage_path('app/public/' . $settings['school_logo']))
            : null;

        $studentPhotoPath = $student->photo
            ? self::imageToDataUri(storage_path('app/public/' . $student->photo))
            : null;

        $principalUser     = User::where('role', 'principal')->first();
        $principalSignPath = ($principalUser?->signature)
            ? self::imageToDataUri(storage_path('app/public/' . $principalUser->signature))
            : null;
        $formTeacherSignPath = ($formTeacher?->signature)
            ? self::imageToDataUri(storage_path('app/public/' . $formTeacher->signature))
            : null;

        $age      = $student->date_of_birth ? $student->date_of_birth->age : '-';
        $warnings = $hasIssues ? compact('missingSubjects', 'pendingScores') : null;

        // Publication data (psychomotor, affective, remarks, next term)
        if (!$publication) {
            $publication = ResultPublication::where('student_id', $student->id)
                ->where('term_id', $term->id)
                ->first();
        }

        $psychomotorItems = self::$psychomotorItems;
        $affectiveItems   = self::$affectiveItems;

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

        ActivityLog::record('teacher_generate_result',
            "Generated result PDF for {$student->full_name} — {$term->name} {$term->academic_year}");

        return $pdf->stream($filename);
    }

    /**
     * Convert an image file to a base64 data URI safe for DomPDF.
     * WebP is not supported by DomPDF, so it is converted to PNG via GD.
     */
    public static function imageToDataUri(string $filePath): ?string
    {
        if (!file_exists($filePath)) {
            return null;
        }

        $mime = mime_content_type($filePath);

        if ($mime === 'image/webp') {
            if (!function_exists('imagecreatefromwebp')) {
                return null;
            }
            $img = @imagecreatefromwebp($filePath);
            if (!$img) return null;
            ob_start();
            imagepng($img);
            $data = ob_get_clean();
            imagedestroy($img);
            return 'data:image/png;base64,' . base64_encode($data);
        }

        $data = file_get_contents($filePath);
        return $data !== false ? 'data:' . $mime . ';base64,' . base64_encode($data) : null;
    }
}
