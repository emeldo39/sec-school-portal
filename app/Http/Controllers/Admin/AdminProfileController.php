<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'phone'     => 'nullable|string|max:20',
            'photo'     => 'nullable|image|max:2048',
            'signature' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo) Storage::disk('public')->delete($user->photo);
            $data['photo'] = $request->file('photo')->store('admins', 'public');
        }

        if ($request->hasFile('signature')) {
            if ($user->signature) Storage::disk('public')->delete($user->signature);
            $data['signature'] = $request->file('signature')->store('signatures', 'public');
        }

        $user->update($data);

        ActivityLog::record('admin_update_profile', 'Updated profile information');

        return back()->with('success', 'Profile updated.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        ActivityLog::record('admin_change_password', 'Changed account password');

        return back()->with('success', 'Password changed successfully.');
    }
}
