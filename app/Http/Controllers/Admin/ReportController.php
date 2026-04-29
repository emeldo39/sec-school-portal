<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Score;
use App\Models\Student;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index()
    {
        $terms   = AcademicTerm::orderByDesc('academic_year')->get();
        $classes = SchoolClass::orderBy('level')->orderBy('name')->get();
        $current = AcademicTerm::current();

        $stats = [
            'total_students'   => Student::where('status', 'active')->count(),
            'scores_approved'  => Score::where('status', 'approved')->count(),
            'scores_pending'   => Score::where('status', 'submitted')->count(),
            'scores_locked'    => Score::where('status', 'locked')->count(),
            'scores_returned'  => Score::where('status', 'returned')->count(),
            'scores_draft'     => Score::where('status', 'draft')->count(),
        ];

        return view('admin.reports.index', compact('terms', 'classes', 'current', 'stats'));
    }

    public function classReport(Request $request, SchoolClass $class)
    {
        $termId = $request->input('term_id', optional(AcademicTerm::current())->id);

        $scores = Score::with(['student', 'subject'])
            ->where('class_id', $class->id)
            ->when($termId, fn($q) => $q->where('term_id', $termId))
            ->whereIn('status', ['approved', 'locked'])
            ->get();

        $term     = $termId ? AcademicTerm::find($termId) : AcademicTerm::current();
        $terms    = AcademicTerm::orderByDesc('academic_year')->get();
        $students = Student::where('class_id', $class->id)
            ->where('status', 'active')
            ->orderBy('last_name')
            ->get();

        $pivot    = [];
        $subjects = $scores->pluck('subject')->unique('id')->sortBy('name');
        foreach ($students as $student) {
            $pivot[$student->id] = [];
            foreach ($subjects as $subject) {
                $pivot[$student->id][$subject->id] = $scores
                    ->where('student_id', $student->id)
                    ->where('subject_id', $subject->id)
                    ->first();
            }
        }

        return view('admin.reports.class', compact('class', 'term', 'terms', 'students', 'subjects', 'pivot'));
    }

    public function attendance(Request $request)
    {
        $classes = SchoolClass::orderBy('level')->orderBy('name')->get();
        $terms   = AcademicTerm::orderByDesc('academic_year')->get();
        $termId  = $request->input('term_id', optional(AcademicTerm::current())->id);
        $classId = $request->input('class_id');

        $baseQuery = Attendance::when($termId,  fn($q) => $q->where('term_id',  $termId))
            ->when($classId, fn($q) => $q->where('class_id', $classId));

        $attendanceSummary = (clone $baseQuery)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $records = Attendance::with(['student', 'schoolClass', 'term'])
            ->when($termId,  fn($q) => $q->where('term_id',  $termId))
            ->when($classId, fn($q) => $q->where('class_id', $classId))
            ->orderBy('date')
            ->paginate(50)
            ->withQueryString();

        return view('admin.reports.attendance', compact('classes', 'terms', 'records', 'attendanceSummary'));
    }

    public function studentTranscript(Student $student)
    {
        $scores = Score::with(['subject', 'term', 'schoolClass'])
            ->where('student_id', $student->id)
            ->whereIn('status', ['approved', 'locked'])
            ->get()
            ->sortBy(fn($s) => optional($s->term)->academic_year . optional($s->term)->name);

        $terms    = $scores->pluck('term')->filter()->unique('id')->sortBy('academic_year');
        $subjects = $scores->pluck('subject')->filter()->unique('id')->sortBy('name');

        $pivot = [];
        foreach ($terms as $term) {
            $pivot[$term->id] = [];
            foreach ($subjects as $subject) {
                $pivot[$term->id][$subject->id] = $scores
                    ->where('term_id', $term->id)
                    ->where('subject_id', $subject->id)
                    ->first();
            }
        }

        return view('admin.reports.student-transcript', compact('student', 'terms', 'subjects', 'pivot'));
    }

    // ── CSV Exports ───────────────────────────────────────────────────

    public function exportClassCsv(Request $request, SchoolClass $class): StreamedResponse
    {
        $termId   = $request->input('term_id', optional(AcademicTerm::current())->id);
        $term     = $termId ? AcademicTerm::find($termId) : null;

        $scores   = Score::with(['student', 'subject'])
            ->where('class_id', $class->id)
            ->when($termId, fn($q) => $q->where('term_id', $termId))
            ->whereIn('status', ['approved', 'locked'])
            ->get();

        $students = Student::where('class_id', $class->id)
            ->where('status', 'active')
            ->orderBy('last_name')
            ->get();

        $subjects = $scores->pluck('subject')->unique('id')->sortBy('name');

        $filename = "ClassReport_{$class->name}" . ($term ? "_{$term->name}_{$term->academic_year}" : '') . '.csv';
        $filename = preg_replace('/[^A-Za-z0-9_.\-]/', '_', $filename);

        return response()->streamDownload(function () use ($students, $subjects, $scores) {
            $handle = fopen('php://output', 'w');

            // Header row
            $headers = ['Admission No.', 'Student Name'];
            foreach ($subjects as $s) $headers[] = $s->name;
            $headers[] = 'Total';
            $headers[] = 'Average';
            fputcsv($handle, $headers);

            // Data rows
            foreach ($students as $student) {
                $row   = [$student->admission_number, $student->full_name];
                $sum   = 0;
                $count = 0;
                foreach ($subjects as $subject) {
                    $score = $scores->where('student_id', $student->id)
                        ->where('subject_id', $subject->id)
                        ->first();
                    $val = $score?->total_score;
                    $row[] = $val ?? '';
                    if ($val !== null) { $sum += $val; $count++; }
                }
                $row[] = $sum;
                $row[] = $count > 0 ? round($sum / $count, 1) : '';
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function exportAttendanceCsv(Request $request): StreamedResponse
    {
        $termId  = $request->input('term_id', optional(AcademicTerm::current())->id);
        $classId = $request->input('class_id');
        $term    = $termId ? AcademicTerm::find($termId) : null;
        $class   = $classId ? SchoolClass::find($classId) : null;

        $records = Attendance::with(['student'])
            ->when($termId,  fn($q) => $q->where('term_id',  $termId))
            ->when($classId, fn($q) => $q->where('class_id', $classId))
            ->orderBy('date')
            ->get();

        $filename = 'Attendance'
            . ($class ? "_{$class->name}" : '')
            . ($term  ? "_{$term->name}_{$term->academic_year}" : '')
            . '.csv';
        $filename = preg_replace('/[^A-Za-z0-9_.\-]/', '_', $filename);

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Student', 'Admission No.', 'Status']);
            foreach ($records as $rec) {
                fputcsv($handle, [
                    $rec->date->format('Y-m-d'),
                    $rec->student->full_name ?? '—',
                    $rec->student->admission_number ?? '',
                    $rec->status,
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

}
