<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\ActivityLog;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $teacher  = auth()->user();
        $classIds = TeacherAssignment::where('user_id', $teacher->id)
            ->pluck('class_id')->unique();

        $classes     = SchoolClass::whereIn('id', $classIds)->orderBy('name')->get();
        $currentTerm = AcademicTerm::current();
        $terms       = AcademicTerm::orderByDesc('academic_year')->get();

        $selectedClassId = $request->input('class_id', $classes->first()?->id);
        $selectedDate    = $request->input('date', now()->toDateString());

        $students = collect();
        $existing = collect();

        if ($selectedClassId) {
            $students = Student::where('class_id', $selectedClassId)
                ->where('status', 'active')
                ->orderBy('last_name')
                ->get();

            // Load existing attendance for this class/date
            $existing = Attendance::where('class_id', $selectedClassId)
                ->whereDate('date', $selectedDate)
                ->get()
                ->keyBy('student_id');
        }

        return view('teacher.attendance.index', compact(
            'classes', 'students', 'existing',
            'selectedClassId', 'selectedDate', 'currentTerm', 'terms'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id'   => 'required|exists:classes,id',
            'term_id'    => 'required|exists:academic_terms,id',
            'date'       => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent,late',
        ]);

        $teacher  = auth()->user();
        $classId  = $data['class_id'];
        $termId   = $data['term_id'];
        $date     = $data['date'];

        // Verify teacher is assigned to this class
        $hasAccess = TeacherAssignment::where('user_id', $teacher->id)
            ->where('class_id', $classId)
            ->exists();

        // Also allow form teacher of this class
        if (!$hasAccess && $teacher->form_class_id == $classId) {
            $hasAccess = true;
        }

        abort_if(!$hasAccess, 403, 'You are not assigned to this class.');

        foreach ($data['attendance'] as $studentId => $status) {
            Attendance::updateOrCreate(
                ['student_id' => $studentId, 'class_id' => $classId, 'date' => $date],
                ['term_id' => $termId, 'status' => $status, 'marked_by' => $teacher->id]
            );
        }

        ActivityLog::record('teacher_mark_attendance',
            "Marked attendance for class #{$classId} on {$date}");

        return back()->with('success', 'Attendance saved for ' . $date);
    }

    public function report(Request $request)
    {
        $teacher  = auth()->user();
        $classIds = TeacherAssignment::where('user_id', $teacher->id)
            ->pluck('class_id')->unique();

        $classes     = SchoolClass::whereIn('id', $classIds)->orderBy('name')->get();
        $terms       = AcademicTerm::orderByDesc('academic_year')->get();
        $currentTerm = AcademicTerm::current();

        $selectedClassId = $request->input('class_id', $classes->first()?->id);
        $selectedTermId  = $request->input('term_id', $currentTerm?->id);

        $records = collect();
        $summary = collect();

        if ($selectedClassId && $selectedTermId) {
            $records = Attendance::with('student')
                ->where('class_id', $selectedClassId)
                ->where('term_id', $selectedTermId)
                ->orderBy('date')
                ->get();

            // Per-student summary
            $summary = $records->groupBy('student_id')->map(function ($recs) {
                return [
                    'present' => $recs->where('status', 'present')->count(),
                    'absent'  => $recs->where('status', 'absent')->count(),
                    'late'    => $recs->where('status', 'late')->count(),
                    'total'   => $recs->count(),
                    'student' => $recs->first()->student,
                ];
            });
        }

        return view('teacher.attendance.report', compact(
            'classes', 'terms', 'records', 'summary',
            'selectedClassId', 'selectedTermId'
        ));
    }
}
