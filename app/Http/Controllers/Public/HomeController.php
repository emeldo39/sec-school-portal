<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\GalleryItem;
use App\Models\HeroSlide;
use App\Models\NewsPost;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'students' => Student::where('status', 'active')->count(),
            'teachers' => User::where('role', 'teacher')->where('status', 'active')->count(),
            'classes'  => SchoolClass::count(),
        ];

        $gallery   = GalleryItem::latest()->take(6)->get();
        $newsPosts = NewsPost::published()->take(3)->get();
        $slides    = HeroSlide::active()->get();

        return view('public.home', compact('stats', 'gallery', 'newsPosts', 'slides'));
    }
}
