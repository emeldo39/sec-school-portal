<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Announcement;
use App\Models\ContactMessage;
use App\Models\ResultPublication;
use App\Models\SchoolClass;
use App\Models\Score;
use App\Models\Student;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'students'         => Student::where('status', 'active')->count(),
            'teachers'         => User::where('role', 'teacher')->where('status', 'active')->count(),
            'pending_scores'   => Score::where('status', 'submitted')->count(),
            'approved_scores'  => Score::where('status', 'approved')->count(),
            'locked_scores'    => Score::where('status', 'locked')->count(),
            'unread_messages'  => ContactMessage::where('is_read', false)->count(),
            'classes'          => SchoolClass::count(),
            'published_results'=> ResultPublication::count(),
            'form_teachers'    => User::where('is_form_teacher', true)->where('status', 'active')->count(),
            'returned_scores'  => Score::where('status', 'returned')->count(),
        ];

        $currentTerm     = AcademicTerm::current();
        $recentScores    = Score::with(['student', 'subject', 'schoolClass'])
                               ->where('status', 'submitted')
                               ->latest()
                               ->take(5)
                               ->get();
        $announcements   = Announcement::with('postedBy')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'currentTerm', 'recentScores', 'announcements'));
    }

    public function profile()
    {
        return view('admin.profile', ['user' => auth()->user()]);
    }
}
