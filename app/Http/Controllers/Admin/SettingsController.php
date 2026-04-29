<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GradingScale;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $jssGradingScales = GradingScale::forJss()->orderByDesc('min_score')->get();
        $sssGradingScales = GradingScale::forSss()->orderByDesc('min_score')->get();
        return view('admin.settings.index', compact('jssGradingScales', 'sssGradingScales'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'school_name'         => 'required|string|max:150',
            'school_motto'        => 'nullable|string|max:150',
            'school_address'      => 'nullable|string|max:255',
            'school_phone'        => 'nullable|string|max:30',
            'school_email'        => 'nullable|email|max:100',
            'principal_name'      => 'nullable|string|max:100',
            'about_text'          => 'nullable|string|max:5000',
            'result_sheet_footer' => 'nullable|string|max:300',
            'school_logo'         => 'nullable|image|max:2048',
        ]);

        $textKeys = [
            'school_name', 'school_motto', 'school_address',
            'school_phone', 'school_email', 'principal_name',
            'about_text', 'result_sheet_footer',
        ];

        foreach ($textKeys as $key) {
            if ($request->has($key)) {
                SchoolSetting::set($key, $request->input($key) ?? '');
            }
        }

        if ($request->hasFile('school_logo')) {
            $old = SchoolSetting::get('school_logo');
            if ($old) Storage::disk('public')->delete($old);

            $path = $request->file('school_logo')->store('', 'public');
            SchoolSetting::set('school_logo', $path);
        }

        ActivityLog::record('admin_update_settings', 'Updated school settings');

        return back()->with('success', 'Settings saved successfully.');
    }

    // ── Grading Scale ────────────────────────────────────────────────

    public function storeGradingScale(Request $request)
    {
        $data = $request->validate([
            'level'     => 'required|in:JSS,SSS',
            'grade'     => 'required|string|max:5',
            'min_score' => 'required|numeric|min:0|max:100',
            'max_score' => 'required|numeric|min:0|max:100|gte:min_score',
            'remark'    => 'required|string|max:50',
        ]);

        // Grade must be unique within its level
        $exists = GradingScale::where('level', $data['level'])
            ->where('grade', $data['grade'])->exists();
        if ($exists) {
            return back()->withErrors(['grade' => "Grade {$data['grade']} already exists for {$data['level']}."]);
        }

        GradingScale::create($data);

        ActivityLog::record('admin_create_grade', "Created {$data['level']} grade scale: {$data['grade']}");

        return back()->with('success', "Grade {$data['grade']} ({$data['level']}) added.");
    }

    public function updateGradingScale(Request $request, GradingScale $scale)
    {
        $data = $request->validate([
            'grade'     => "required|string|max:5",
            'min_score' => 'required|numeric|min:0|max:100',
            'max_score' => 'required|numeric|min:0|max:100|gte:min_score',
            'remark'    => 'required|string|max:50',
        ]);

        // Grade must be unique within its level (excluding self)
        $exists = GradingScale::where('level', $scale->level)
            ->where('grade', $data['grade'])
            ->where('id', '!=', $scale->id)->exists();
        if ($exists) {
            return back()->withErrors(['grade' => "Grade {$data['grade']} already exists for {$scale->level}."]);
        }

        $scale->update($data);

        ActivityLog::record('admin_update_grade', "Updated {$scale->level} grade scale: {$scale->grade}");

        return back()->with('success', 'Grade scale updated.');
    }

    public function destroyGradingScale(GradingScale $scale)
    {
        $grade = $scale->grade;
        $level = $scale->level;
        $scale->delete();

        ActivityLog::record('admin_delete_grade', "Deleted {$level} grade scale: {$grade}");

        return back()->with('success', "Grade {$grade} ({$level}) deleted.");
    }
}
