<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsPostController extends Controller
{
    public function index()
    {
        $posts = NewsPost::latest()->paginate(15);
        return view('admin.news-posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.news-posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:200',
            'excerpt'      => 'nullable|string|max:300',
            'body'         => 'nullable|string',
            'author'       => 'nullable|string|max:100',
            'image'        => 'nullable|image|max:5120',
            'is_published' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $data['is_published'] = $request->boolean('is_published');
        if ($data['is_published']) {
            $data['published_at'] = now();
        }

        $post = NewsPost::create($data);
        ActivityLog::record('admin_create_news', "Created news post: {$post->title}");

        return redirect()->route('admin.news-posts.index')
            ->with('success', 'Post published successfully.');
    }

    public function edit(NewsPost $newsPost)
    {
        return view('admin.news-posts.edit', compact('newsPost'));
    }

    public function update(Request $request, NewsPost $newsPost)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:200',
            'excerpt'      => 'nullable|string|max:300',
            'body'         => 'nullable|string',
            'author'       => 'nullable|string|max:100',
            'image'        => 'nullable|image|max:5120',
            'is_published' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($newsPost->image) {
                Storage::disk('public')->delete($newsPost->image);
            }
            $data['image'] = $request->file('image')->store('news', 'public');
        }

        $wasPublished = $newsPost->is_published;
        $data['is_published'] = $request->boolean('is_published');
        if ($data['is_published'] && !$wasPublished) {
            $data['published_at'] = now();
        } elseif (!$data['is_published']) {
            $data['published_at'] = null;
        }

        // Re-generate slug only if title changed
        if ($data['title'] !== $newsPost->title) {
            $base = Str::slug($data['title']);
            $slug = $base;
            $i    = 1;
            while (NewsPost::where('slug', $slug)->where('id', '!=', $newsPost->id)->exists()) {
                $slug = "{$base}-{$i}";
                $i++;
            }
            $data['slug'] = $slug;
        }

        $newsPost->update($data);
        ActivityLog::record('admin_update_news', "Updated news post #{$newsPost->id}");

        return redirect()->route('admin.news-posts.index')
            ->with('success', 'Post updated.');
    }

    public function destroy(NewsPost $newsPost)
    {
        if ($newsPost->image) {
            Storage::disk('public')->delete($newsPost->image);
        }
        $newsPost->delete();
        ActivityLog::record('admin_delete_news', "Deleted news post #{$newsPost->id}");

        return back()->with('success', 'Post deleted.');
    }
}
