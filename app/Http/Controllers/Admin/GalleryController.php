<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index()
    {
        $items = GalleryItem::with('uploadedBy')->latest()->paginate(24);
        return view('admin.gallery.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'images'   => 'required|array|min:1',
            'images.*' => 'image|max:4096',
            'caption'  => 'nullable|string|max:150',
        ]);

        $count = 0;
        foreach ($request->file('images') as $image) {
            GalleryItem::create([
                'image_path'  => $image->store('gallery', 'public'),
                'caption'     => $request->caption,
                'uploaded_by' => auth()->id(),
            ]);
            $count++;
        }

        ActivityLog::record('admin_upload_gallery', "Uploaded {$count} gallery image(s)");

        return back()->with('success', "{$count} image(s) uploaded to gallery.");
    }

    public function destroy(GalleryItem $item)
    {
        Storage::disk('public')->delete($item->image_path);
        $item->delete();

        ActivityLog::record('admin_delete_gallery', "Deleted gallery image #{$item->id}");

        return back()->with('success', 'Image removed from gallery.');
    }
}
