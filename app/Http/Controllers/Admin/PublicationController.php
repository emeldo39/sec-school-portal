<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Teacher\ResultController as TeacherResultController;
use App\Models\AcademicTerm;
use App\Models\ActivityLog;
use App\Models\ResultPublication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    /** Principal: list all published results awaiting/with remarks. */
    public function index(Request $request)
    {
        abort_if(!auth()->user()->isPrincipal(), 403, 'Only the principal can manage remarks.');

        $terms          = AcademicTerm::orderByDesc('academic_year')->get();
        $currentTerm    = AcademicTerm::current();
        $selectedTermId = $request->input('term_id', $currentTerm?->id);

        $search = $request->input('search');

        $publications = ResultPublication::with(['student.schoolClass', 'term', 'publisher'])
            ->when($selectedTermId, fn($q) => $q->where('term_id', $selectedTermId))
            ->when($search, fn($q) => $q->whereHas('student', function ($sq) use ($search) {
                $sq->where('first_name', 'like', "%{$search}%")
                   ->orWhere('last_name', 'like', "%{$search}%")
                   ->orWhere('admission_number', 'like', "%{$search}%");
            }))
            ->latest('published_at')
            ->paginate(15)
            ->withQueryString();

        $psychomotorItems = TeacherResultController::$psychomotorItems;
        $affectiveItems   = TeacherResultController::$affectiveItems;

        return view('admin.publications.index', compact(
            'publications', 'terms', 'currentTerm', 'selectedTermId',
            'psychomotorItems', 'affectiveItems', 'search'
        ));
    }

    /** Principal: save remarks for a published result. */
    public function addRemarks(Request $request, ResultPublication $publication)
    {
        abort_if(!auth()->user()->isPrincipal(), 403);

        $data = $request->validate([
            'principal_remarks' => 'nullable|string|max:500',
        ]);

        $publication->update(['principal_remarks' => $data['principal_remarks'] ?? null]);

        ActivityLog::record(
            'principal_add_remarks',
            "Added principal remarks for {$publication->student->full_name} — term_id:{$publication->term_id}"
        );

        return back()->with('success', 'Remarks saved for ' . $publication->student->full_name . '.');
    }
}
