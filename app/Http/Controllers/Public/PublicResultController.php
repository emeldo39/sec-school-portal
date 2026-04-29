<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Teacher\ResultController;
use App\Models\AcademicTerm;
use App\Models\ResultPublication;
use App\Models\Student;

class PublicResultController extends Controller
{
    /** Parent-facing landing page (no login required). */
    public function show(string $token)
    {
        $publication = ResultPublication::where('token', $token)
            ->with(['student.schoolClass', 'term', 'publisher'])
            ->firstOrFail();

        return view('public.result', compact('publication'));
    }

    /** Streams the filled PDF for parents. */
    public function pdf(string $token)
    {
        $publication = ResultPublication::where('token', $token)
            ->with(['student.schoolClass', 'term'])
            ->firstOrFail();

        $student = $publication->student;
        $term    = $publication->term;
        $class   = $student->schoolClass;

        return ResultController::buildPdf($student, $term, $class, true, $publication);
    }
}
