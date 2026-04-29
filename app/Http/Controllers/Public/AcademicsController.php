<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Subject;

class AcademicsController extends Controller
{
    public function index()
    {
        $jssSubjects = Subject::whereIn('level', ['JSS', 'Both'])->orderBy('name')->get();
        $ssSubjects  = Subject::whereIn('level', ['SSS', 'Both'])->orderBy('name')->get();

        return view('public.academics', compact('jssSubjects', 'ssSubjects'));
    }
}
