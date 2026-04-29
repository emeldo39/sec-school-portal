<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::withCount('students')
            ->with('formTeacher')
            ->orderBy('level')
            ->orderBy('name')
            ->get();

        return view('admin.classes.index', compact('classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:30|unique:classes,name',
            'level' => 'required|in:JSS,SSS',
        ]);

        $class = SchoolClass::create($data);

        ActivityLog::record('admin_create_class', "Created class: {$class->name} ({$class->level})");

        return back()->with('success', "Class {$class->name} created.");
    }

    public function update(Request $request, SchoolClass $class)
    {
        $data = $request->validate([
            'name'  => "required|string|max:30|unique:classes,name,{$class->id}",
            'level' => 'required|in:JSS,SSS',
        ]);

        $class->update($data);

        ActivityLog::record('admin_update_class', "Updated class: {$class->name}");

        return back()->with('success', "Class updated.");
    }

    public function destroy(SchoolClass $class)
    {
        if ($class->students()->exists()) {
            return back()->with('error', "Cannot delete {$class->name}: it still has students enrolled.");
        }

        $name = $class->name;
        $class->delete();

        ActivityLog::record('admin_delete_class', "Deleted class: {$name}");

        return back()->with('success', "Class {$name} deleted.");
    }
}
