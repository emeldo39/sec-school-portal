<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\User;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::where('role', 'teacher')
                     ->where('status', 'active')
                     ->orderBy('name')
                     ->get();

        return view('public.staff', compact('staff'));
    }
}
