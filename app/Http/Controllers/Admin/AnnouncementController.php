<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Announcement;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::with(['postedBy', 'schoolClass'])
            ->latest()
            ->paginate(20);

        $classes = SchoolClass::orderBy('level')->orderBy('name')->get();

        return view('admin.announcements.index', compact('announcements', 'classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:150',
            'body'     => 'required|string|max:5000',
            'target'   => 'required|in:all,teachers,class',
            'class_id' => 'nullable|exists:classes,id|required_if:target,class',
        ]);

        $data['posted_by'] = auth()->id();
        if ($data['target'] !== 'class') {
            $data['class_id'] = null;
        }

        $ann = Announcement::create($data);

        ActivityLog::record('admin_create_announcement', "Posted announcement: {$ann->title}");

        return back()->with('success', 'Announcement posted.');
    }

    public function update(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:150',
            'body'     => 'required|string|max:5000',
            'target'   => 'required|in:all,teachers,class',
            'class_id' => 'nullable|exists:classes,id|required_if:target,class',
        ]);

        if ($data['target'] !== 'class') {
            $data['class_id'] = null;
        }

        $announcement->update($data);

        ActivityLog::record('admin_update_announcement', "Updated announcement: {$announcement->title}");

        return back()->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        $title = $announcement->title;
        $announcement->delete();

        ActivityLog::record('admin_delete_announcement', "Deleted announcement: {$title}");

        return back()->with('success', 'Announcement deleted.');
    }
}
