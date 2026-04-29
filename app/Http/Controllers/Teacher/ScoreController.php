<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\ActivityLog;
use App\Models\SchoolClass;
use App\Models\Score;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    /**
     * Show score entry grid for a class + subject + term combination.
     */
    public function index(Request $request)
    {
        $teacher = auth()->user();

        // All class+subject combos assigned to this teacher
        $assignments = TeacherAssignment::where('user_id', $teacher->id)
            ->with(['schoolClass', 'subject'])
            ->get();

        $classes  = $assignments->pluck('schoolClass')->unique('id')->sortBy('name');
        $terms    = AcademicTerm::orderByDesc('academic_year')->get();
        $current  = AcademicTerm::current();

        $selectedClassId   = $request->input('class_id');
        $selectedSubjectId = $request->input('subject_id');
        $selectedTermId    = $request->input('term_id', $current?->id);

        // Subjects available for the selected class
        $availableSubjects = collect();
        if ($selectedClassId) {
            $availableSubjects = $assignments
                ->where('class_id', $selectedClassId)
                ->pluck('subject')
                ->unique('id')
                ->sortBy('name');
        }

        $students   = collect();
        $scores     = collect();
        $classModel = null;

        if ($selectedClassId && $selectedSubjectId && $selectedTermId) {
            // Verify teacher is assigned to this class+subject
            $assigned = $assignments
                ->where('class_id', $selectedClassId)
                ->where('subject_id', $selectedSubjectId)
                ->isNotEmpty();

            abort_if(!$assigned, 403, 'You are not assigned to teach this subject in this class.');

            $classModel = SchoolClass::find($selectedClassId);
            $students   = Student::where('class_id', $selectedClassId)
                ->where('status', 'active')
                ->orderBy('last_name')
                ->get();

            $scores = Score::where('class_id', $selectedClassId)
                ->where('subject_id', $selectedSubjectId)
                ->where('term_id', $selectedTermId)
                ->get()
                ->keyBy('student_id');
        }

        return view('teacher.scores.index', compact(
            'assignments', 'classes', 'terms', 'current',
            'availableSubjects', 'students', 'scores', 'classModel',
            'selectedClassId', 'selectedSubjectId', 'selectedTermId'
        ));
    }

    /**
     * Save scores as draft (does not change status to submitted).
     */
    public function save(Request $request)
    {
        $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'term_id'    => 'required|exists:academic_terms,id',
            'scores'     => 'required|array',
            // Unified 6-component CA fields (same for JSS and SSS)
            'scores.*.weekly_exercise_1' => 'nullable|numeric|min:0|max:10',
            'scores.*.take_home'         => 'nullable|numeric|min:0|max:10',
            'scores.*.college_quiz'      => 'nullable|numeric|min:0|max:10',
            'scores.*.project'           => 'nullable|numeric|min:0|max:10',
            'scores.*.weekly_exercise_2' => 'nullable|numeric|min:0|max:10',
            'scores.*.take_home_2'       => 'nullable|numeric|min:0|max:10',
            // Exam
            'scores.*.exam_score'        => 'nullable|numeric|min:0|max:40',
            'scores.*.subject_remark'    => 'nullable|string|max:200',
        ]);

        $teacher   = auth()->user();
        $classId   = $request->class_id;
        $subjectId = $request->subject_id;
        $termId    = $request->term_id;

        $this->authorizeAssignment($teacher, $classId, $subjectId);

        // Build allowlist of student IDs that actually belong to this class
        $validStudentIds = Student::where('class_id', $classId)
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->all();

        foreach ($request->scores as $studentId => $fields) {
            // Reject any forged student IDs not belonging to this class
            if (!in_array((string) $studentId, $validStudentIds, true)) {
                continue;
            }
            $score = Score::firstOrNew([
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'class_id'   => $classId,
                'term_id'    => $termId,
            ]);

            // Don't allow editing locked or already-approved scores
            if (in_array($score->status, ['approved', 'locked'])) {
                continue;
            }

            $score->weekly_exercise_1 = $this->nullableFloat($fields['weekly_exercise_1'] ?? null);
            $score->take_home         = $this->nullableFloat($fields['take_home']         ?? null);
            $score->college_quiz      = $this->nullableFloat($fields['college_quiz']      ?? null);
            $score->project           = $this->nullableFloat($fields['project']           ?? null);
            $score->weekly_exercise_2 = $this->nullableFloat($fields['weekly_exercise_2'] ?? null);
            $score->take_home_2       = $this->nullableFloat($fields['take_home_2']       ?? null);
            $score->exam_score        = $this->nullableFloat($fields['exam_score']        ?? null);
            $score->subject_remark    = $fields['subject_remark'] ?? null;

            if (is_null($score->status) || $score->status === 'returned') {
                $score->status = 'draft';
            }
            $score->submitted_by = $teacher->id;

            $score->computeTotal();
            $score->save();
        }

        return back()->with('success', 'Scores saved as draft.');
    }

    /**
     * Save-and-submit: persists any unsaved score data then marks all
     * draft/returned scores for this class+subject+term as 'submitted'.
     * This handles the case where the teacher clicks Submit without first
     * clicking Save Draft.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'term_id'    => 'required|exists:academic_terms,id',
            'scores'     => 'nullable|array',
            // Unified 6-component CA fields (same for JSS and SSS)
            'scores.*.weekly_exercise_1' => 'nullable|numeric|min:0|max:10',
            'scores.*.take_home'         => 'nullable|numeric|min:0|max:10',
            'scores.*.college_quiz'      => 'nullable|numeric|min:0|max:10',
            'scores.*.project'           => 'nullable|numeric|min:0|max:10',
            'scores.*.weekly_exercise_2' => 'nullable|numeric|min:0|max:10',
            'scores.*.take_home_2'       => 'nullable|numeric|min:0|max:10',
            'scores.*.exam_score'        => 'nullable|numeric|min:0|max:40',
            'scores.*.subject_remark'    => 'nullable|string|max:200',
        ]);

        $teacher   = auth()->user();
        $classId   = $request->class_id;
        $subjectId = $request->subject_id;
        $termId    = $request->term_id;

        $this->authorizeAssignment($teacher, $classId, $subjectId);

        // ── Step 1: Save the score data that came with this request ───
        if ($request->has('scores')) {
            $validStudentIds = Student::where('class_id', $classId)
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->all();

            foreach ($request->scores as $studentId => $fields) {
                if (!in_array((string) $studentId, $validStudentIds, true)) {
                    continue;
                }

                $score = Score::firstOrNew([
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'class_id'   => $classId,
                    'term_id'    => $termId,
                ]);

                if (in_array($score->status, ['approved', 'locked'])) {
                    continue;
                }

                $score->weekly_exercise_1 = $this->nullableFloat($fields['weekly_exercise_1'] ?? null);
                $score->take_home         = $this->nullableFloat($fields['take_home']         ?? null);
                $score->college_quiz      = $this->nullableFloat($fields['college_quiz']      ?? null);
                $score->project           = $this->nullableFloat($fields['project']           ?? null);
                $score->weekly_exercise_2 = $this->nullableFloat($fields['weekly_exercise_2'] ?? null);
                $score->take_home_2       = $this->nullableFloat($fields['take_home_2']       ?? null);
                $score->exam_score        = $this->nullableFloat($fields['exam_score']        ?? null);
                $score->subject_remark    = $fields['subject_remark'] ?? null;

                // Ensure status and submitted_by are always set/updated
                if (is_null($score->status) || $score->status === 'returned') {
                    $score->status = 'draft';
                }
                $score->submitted_by = $teacher->id;

                $score->computeTotal();
                $score->save();
            }
        }

        // ── Step 2: Mark all draft/returned scores as submitted ───────
        // No submitted_by filter — authorizeAssignment() already verified
        // teacher owns this class+subject, so we submit all draft/returned scores.
        $updated = Score::where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('term_id', $termId)
            ->whereIn('status', ['draft', 'returned'])
            ->update([
                'status'       => 'submitted',
                'submitted_at' => now(),
                'submitted_by' => $teacher->id,
            ]);

        ActivityLog::record('teacher_submit_scores',
            "Submitted {$updated} score(s) for class #{$classId}, subject #{$subjectId}, term #{$termId}");

        return back()->with('success', "{$updated} score(s) submitted for admin review.");
    }

    // ── Helpers ───────────────────────────────────────────────────────

    private function authorizeAssignment($teacher, int $classId, int $subjectId): void
    {
        $assigned = TeacherAssignment::where('user_id', $teacher->id)
            ->where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->exists();

        abort_if(!$assigned, 403, 'You are not assigned to teach this subject in this class.');
    }

    private function nullableFloat(mixed $value): ?float
    {
        if ($value === null || $value === '') return null;
        return (float) $value;
    }
}
