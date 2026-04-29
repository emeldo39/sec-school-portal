<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;

class AdmissionsController extends Controller
{
    public function index()
    {
        return view('public.admissions');
    }
}
