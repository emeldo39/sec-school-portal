<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::withCount('assignments')->orderBy('name')->get();

        $stats = [
            'total'       => $subjects->count(),
            'jss'         => $subjects->where('level', 'JSS')->count(),
            'sss'         => $subjects->where('level', 'SSS')->count(),
            'both'        => $subjects->where('level', 'Both')->count(),
            'assignments' => $subjects->sum('assignments_count'),
        ];

        return view('admin.subjects.index', compact('subjects', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:80|unique:subjects,name',
            'code'  => 'nullable|string|max:10|unique:subjects,code',
            'level' => 'required|in:JSS,SSS,Both',
        ]);

        $subject = Subject::create($data);

        ActivityLog::record('admin_create_subject', "Created subject: {$subject->name}");

        return back()->with('success', "Subject {$subject->name} created.");
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name'  => "required|string|max:80|unique:subjects,name,{$subject->id}",
            'code'  => "nullable|string|max:10|unique:subjects,code,{$subject->id}",
            'level' => 'required|in:JSS,SSS,Both',
        ]);

        $subject->update($data);

        ActivityLog::record('admin_update_subject', "Updated subject: {$subject->name}");

        return back()->with('success', "Subject updated.");
    }

    public function destroy(Subject $subject)
    {
        if ($subject->scores()->exists()) {
            return back()->with('error', "Cannot delete {$subject->name}: scores have been recorded for it.");
        }

        $name = $subject->name;
        $subject->assignments()->delete();
        $subject->delete();

        ActivityLog::record('admin_delete_subject', "Deleted subject: {$name}");

        return back()->with('success', "Subject {$name} deleted.");
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'No subjects selected.');
        }

        $subjects  = Subject::whereIn('id', $ids)->get();
        $blocked   = [];
        $deleted   = [];

        foreach ($subjects as $subject) {
            if ($subject->scores()->exists()) {
                $blocked[] = $subject->name;
                continue;
            }
            $subject->assignments()->delete();
            $subject->delete();
            $deleted[] = $subject->name;
        }

        if ($deleted) {
            ActivityLog::record('admin_bulk_delete_subjects', 'Bulk deleted subjects: ' . implode(', ', $deleted));
        }

        $msg = count($deleted) . ' subject(s) deleted.';
        if ($blocked) {
            $msg .= ' Could not delete (have scores): ' . implode(', ', $blocked) . '.';
        }

        return back()->with(count($blocked) && !count($deleted) ? 'error' : 'success', $msg);
    }
}
