<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.hero-slides.index', compact('slides'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:120',
            'title_highlight'  => 'nullable|string|max:80',
            'description'      => 'nullable|string|max:300',
            'button_text'      => 'required|string|max:50',
            'button_url'       => 'required|string|max:200',
            'image'            => 'nullable|image|max:5120',
            'sort_order'       => 'nullable|integer|min:0',
            'is_active'        => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('slides', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        HeroSlide::create($data);
        ActivityLog::record('admin_create_slide', "Created hero slide: {$data['title']}");

        return back()->with('success', 'Slide created successfully.');
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:120',
            'title_highlight'  => 'nullable|string|max:80',
            'description'      => 'nullable|string|max:300',
            'button_text'      => 'required|string|max:50',
            'button_url'       => 'required|string|max:200',
            'image'            => 'nullable|image|max:5120',
            'sort_order'       => 'nullable|integer|min:0',
            'is_active'        => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($heroSlide->image) {
                Storage::disk('public')->delete($heroSlide->image);
            }
            $data['image'] = $request->file('image')->store('slides', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? $heroSlide->sort_order;

        $heroSlide->update($data);
        ActivityLog::record('admin_update_slide', "Updated hero slide #{$heroSlide->id}");

        return back()->with('success', 'Slide updated.');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        if ($heroSlide->image) {
            Storage::disk('public')->delete($heroSlide->image);
        }
        $heroSlide->delete();
        ActivityLog::record('admin_delete_slide', "Deleted hero slide #{$heroSlide->id}");

        return back()->with('success', 'Slide deleted.');
    }

    public function toggleActive(HeroSlide $heroSlide)
    {
        $heroSlide->update(['is_active' => !$heroSlide->is_active]);
        return back()->with('success', 'Slide status updated.');
    }
}
