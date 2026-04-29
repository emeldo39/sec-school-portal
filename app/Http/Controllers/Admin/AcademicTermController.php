<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AcademicTermController extends Controller
{
    public function index()
    {
        $terms = AcademicTerm::orderByDesc('academic_year')->orderBy('name')->get();
        return view('admin.terms.index', compact('terms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
        ]);

        $data['is_current'] = false;
        $term = AcademicTerm::create($data);

        ActivityLog::record('admin_create_term', "Created term: {$term->name} {$term->academic_year}");

        return back()->with('success', "Term {$term->name} {$term->academic_year} created.");
    }

    public function update(Request $request, AcademicTerm $term)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
        ]);

        $term->update($data);

        ActivityLog::record('admin_update_term', "Updated term: {$term->name} {$term->academic_year}");

        return back()->with('success', "Term updated.");
    }

    public function setCurrent(AcademicTerm $term)
    {
        // Unset all others first
        AcademicTerm::query()->update(['is_current' => false]);
        $term->update(['is_current' => true]);

        ActivityLog::record('admin_set_current_term', "Set current term: {$term->name} {$term->academic_year}");

        return back()->with('success', "{$term->name} {$term->academic_year} is now the current term.");
    }
}
