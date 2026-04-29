<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;

class AboutController extends Controller
{
    public function index()
    {
        $stats = [
            'students' => Student::where('status', 'active')->count(),
            'teachers' => User::where('role', 'teacher')->where('status', 'active')->count(),
            'classes'  => SchoolClass::count(),
        ];

        return view('public.about', compact('stats'));
    }
}
