<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;

class NewsController extends Controller
{
    public function index()
    {
        $posts = NewsPost::published()->paginate(9);
        return view('public.news.index', compact('posts'));
    }

    public function show(NewsPost $newsPost)
    {
        abort_unless($newsPost->is_published, 404);
        $related = NewsPost::published()
            ->where('id', '!=', $newsPost->id)
            ->take(3)->get();
        return view('public.news.show', compact('newsPost', 'related'));
    }
}
