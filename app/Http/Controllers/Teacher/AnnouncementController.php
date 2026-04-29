<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $teacher = auth()->user();

        $announcements = Announcement::with(['postedBy', 'schoolClass'])
            ->where(function ($q) use ($teacher) {
                $q->where('target', 'all')
                  ->orWhere('target', 'teachers')
                  ->orWhere(function ($q2) use ($teacher) {
                      $q2->where('target', 'class')
                         ->where('class_id', $teacher->form_class_id);
                  });
            })
            ->latest()
            ->paginate(20);

        return view('teacher.announcements', compact('announcements'));
    }
}
