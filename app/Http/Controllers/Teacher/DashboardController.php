<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Announcement;
use App\Models\Score;
use App\Models\Student;
use App\Models\TeacherAssignment;

class DashboardController extends Controller
{
    public function index()
    {
        $teacher     = auth()->user();
        $currentTerm = AcademicTerm::current();

        $assignments       = TeacherAssignment::where('user_id', $teacher->id)
            ->with(['schoolClass', 'subject'])
            ->get();

        $assignedClassIds   = $assignments->pluck('class_id')->unique();
        $assignedSubjectIds = $assignments->pluck('subject_id')->unique();

        $studentCount = Student::whereIn('class_id', $assignedClassIds)
            ->where('status', 'active')
            ->count();

        $stats = [
            'classes'  => $assignedClassIds->count(),
            'subjects' => $assignedSubjectIds->count(),
            'students' => $studentCount,
            'pending'  => Score::where('submitted_by', $teacher->id)
                ->where('status', 'submitted')->count(),
            'approved' => Score::where('submitted_by', $teacher->id)
                ->where('status', 'approved')->count(),
            'locked'   => Score::where('submitted_by', $teacher->id)
                ->where('status', 'locked')->count(),
            'returned' => Score::where('submitted_by', $teacher->id)
                ->where('status', 'returned')->count(),
        ];

        $recentScores = Score::with(['student', 'subject', 'schoolClass'])
            ->where('submitted_by', $teacher->id)
            ->latest('submitted_at')
            ->take(10)
            ->get();

        $announcements = Announcement::where(function ($q) use ($teacher) {
            $q->where('target', 'all')
              ->orWhere('target', 'teachers')
              ->orWhere(function ($q2) use ($teacher) {
                  $q2->where('target', 'class')
                     ->where('class_id', $teacher->form_class_id);
              });
        })->latest()->take(5)->get();

        return view('teacher.dashboard', compact(
            'teacher', 'currentTerm', 'stats', 'assignments', 'recentScores', 'announcements'
        ));
    }

    public function profile()
    {
        return view('teacher.profile', ['user' => auth()->user()]);
    }

    public function students()
    {
        $teacher     = auth()->user();
        $assignments = TeacherAssignment::where('user_id', $teacher->id)
            ->with(['schoolClass', 'subject'])
            ->get();

        $classIds   = $assignments->pluck('class_id')->unique();
        $subjectIds = $assignments->pluck('subject_id')->unique();

        $students = Student::with('schoolClass')
            ->whereIn('class_id', $classIds)
            ->where('status', 'active')
            ->orderBy('last_name')
            ->get();

        // Stats
        $stats = [
            'total_students' => $students->count(),
            'classes'        => $classIds->count(),
            'subjects'       => $subjectIds->count(),
        ];

        // Per-class student counts for stats breakdown
        $perClass = $students->groupBy('class_id')->map->count();

        // Unique classes and subjects for filter dropdowns
        $assignedClasses  = $assignments->map->schoolClass->filter()->unique('id')->sortBy('name');
        $assignedSubjects = $assignments->map->subject->filter()->unique('id')->sortBy('name');

        return view('teacher.students', compact(
            'students', 'stats', 'perClass', 'assignedClasses', 'assignedSubjects', 'assignments'
        ));
    }
}
