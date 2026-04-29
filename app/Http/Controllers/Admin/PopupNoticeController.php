<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopupNotice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PopupNoticeController extends Controller
{
    public function index()
    {
        $popup = PopupNotice::first();
        return view('admin.popup-notice.index', compact('popup'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'title'      => 'nullable|string|max:255',
            'image'      => 'nullable|image|max:4096',
            'link_url'   => 'nullable|url|max:500',
            'link_text'  => 'nullable|string|max:100',
            'is_active'  => 'nullable|boolean',
            'show_once'  => 'nullable|boolean',
        ]);

        $popup = PopupNotice::firstOrNew(['id' => 1]);

        if ($request->hasFile('image')) {
            if ($popup->image) {
                Storage::disk('public')->delete($popup->image);
            }
            $data['image'] = $request->file('image')->store('popups', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['show_once'] = $request->boolean('show_once');

        $popup->fill($data)->save();

        return back()->with('success', 'Popup notice updated.');
    }

    public function destroyImage()
    {
        $popup = PopupNotice::first();
        if ($popup && $popup->image) {
            Storage::disk('public')->delete($popup->image);
            $popup->update(['image' => null]);
        }
        return back()->with('success', 'Image removed.');
    }
}
