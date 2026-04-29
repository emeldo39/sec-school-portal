<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Models\User;
use App\Services\SignatureProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')
            ->with(['formClass', 'assignments.subject'])
            ->withCount('assignments')
            ->latest()
            ->get();

        // Admin (IT) accounts visible to principal only
        $admins = auth()->user()->isPrincipal()
            ? User::where('role', 'admin')->latest()->get()
            : collect();

        // Principal account(s) visible to IT admin for password reset only
        $principals = auth()->user()->isAdmin()
            ? User::where('role', 'principal')->get()
            : collect();

        return view('admin.users.index', compact('teachers', 'admins', 'principals'));
    }

    public function create()
    {
        $classes  = SchoolClass::orderBy('level')->orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.users.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        // Determine what role is being created
        $wantsAdmin = $request->input('account_role') === 'admin' && auth()->user()->isPrincipal();

        if ($wantsAdmin) {
            $data = $request->validate([
                'name'     => 'required|string|max:100',
                'email'    => 'required|email|unique:users,email',
                'phone'    => 'nullable|string|max:20',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone'    => $data['phone'] ?? null,
                'password' => Hash::make($data['password']),
                'role'     => 'admin',
                'status'   => 'active',
            ]);

            ActivityLog::record('principal_create_admin', "Created admin account: {$user->name} ({$user->email})");

            return redirect()->route('admin.users.index')
                ->with('success', "Admin account for {$user->name} created successfully.");
        }

        // Default: create a teacher account
        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'email'           => 'required|email|unique:users,email',
            'phone'           => 'nullable|string|max:20',
            'password'        => 'required|string|min:6|confirmed',
            'form_class_id'   => 'nullable|exists:classes,id',
            'is_form_teacher' => 'boolean',
            'assignments'     => 'nullable|array',
            'assignments.*'   => 'array',
            'assignments.*.*' => 'integer|exists:subjects,id',
        ]);

        $user = User::create([
            'name'            => $data['name'],
            'email'           => $data['email'],
            'phone'           => $data['phone'] ?? null,
            'password'        => Hash::make($data['password']),
            'role'            => 'teacher',
            'status'          => 'active',
            'form_class_id'   => $data['form_class_id'] ?? null,
            'is_form_teacher' => $request->boolean('is_form_teacher'),
        ]);

        $this->syncAssignments($user, $request->input('assignments', []));

        if ($request->hasFile('photo')) {
            $user->update(['photo' => $request->file('photo')->store('staff/photos', 'public')]);
        }
        if ($request->hasFile('signature')) {
            $stored = $request->file('signature')->store('staff/signatures', 'public');
            $user->update(['signature' => SignatureProcessor::removeBackground($stored)]);
        }

        ActivityLog::record('admin_create_teacher', "Created teacher account: {$user->name} ({$user->email})");

        return redirect()->route('admin.users.index')
            ->with('success', "Teacher account for {$user->name} created successfully.");
    }

    public function edit(User $user)
    {
        $this->authorizeManage($user);

        if ($user->role === 'admin') {
            // Simple edit for admin accounts (no class assignment)
            return view('admin.users.edit-admin', compact('user'));
        }

        $classes     = SchoolClass::orderBy('level')->orderBy('name')->get();
        $subjects    = Subject::orderBy('name')->get();
        $assignments = TeacherAssignment::where('user_id', $user->id)->get();

        // Map: class_id => [subject_id, ...] — used to pre-tick checkboxes in the per-class UI
        $existingAssignments = $assignments
            ->groupBy('class_id')
            ->map(fn($group) => $group->pluck('subject_id')->toArray());

        return view('admin.users.edit', compact('user', 'classes', 'subjects', 'existingAssignments'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeManage($user);

        if ($user->role === 'admin') {
            $data = $request->validate([
                'name'  => 'required|string|max:100',
                'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
                'phone' => 'nullable|string|max:20',
            ]);
            $user->update($data);
            ActivityLog::record('principal_update_admin', "Updated admin account: {$user->name}");
            return redirect()->route('admin.users.index')->with('success', "{$user->name}'s account updated.");
        }

        $data = $request->validate([
            'name'            => 'required|string|max:100',
            'email'           => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'           => 'nullable|string|max:20',
            'form_class_id'   => 'nullable|exists:classes,id',
            'is_form_teacher' => 'boolean',
            'assignments'     => 'nullable|array',
            'assignments.*'   => 'array',
            'assignments.*.*' => 'integer|exists:subjects,id',
        ]);

        $user->update([
            'name'            => $data['name'],
            'email'           => $data['email'],
            'phone'           => $data['phone'] ?? null,
            'form_class_id'   => $data['form_class_id'] ?? null,
            'is_form_teacher' => $request->boolean('is_form_teacher'),
        ]);

        $this->syncAssignments($user, $request->input('assignments', []));

        if ($request->hasFile('photo')) {
            if ($user->photo) Storage::disk('public')->delete($user->photo);
            $user->update(['photo' => $request->file('photo')->store('staff/photos', 'public')]);
        }
        if ($request->hasFile('signature')) {
            if ($user->signature) Storage::disk('public')->delete($user->signature);
            $stored = $request->file('signature')->store('staff/signatures', 'public');
            $user->update(['signature' => SignatureProcessor::removeBackground($stored)]);
        }

        ActivityLog::record('admin_update_teacher', "Updated teacher account: {$user->name} ({$user->email})");

        return redirect()->route('admin.users.index')
            ->with('success', "{$user->name}'s account updated successfully.");
    }

    public function resetPassword(Request $request, User $user)
    {
        $auth = auth()->user();

        // Principal: can reset teacher and admin passwords
        // Admin (IT): can reset teacher and principal passwords
        $canReset = ($user->role === 'teacher')
            || ($user->role === 'principal' && $auth->isAdmin())
            || ($user->role === 'admin' && $auth->isPrincipal());

        abort_if(!$canReset, 403, 'You do not have permission to reset this account\'s password.');

        $request->validate(['password' => 'required|string|min:6|confirmed']);

        $user->update(['password' => Hash::make($request->password)]);

        $actor = $auth->isPrincipal() ? 'principal' : 'admin';
        ActivityLog::record("{$actor}_reset_password", "Reset password for {$user->role}: {$user->name}");

        return back()->with('success', "Password for {$user->name} has been reset.");
    }

    public function toggleStatus(User $user)
    {
        $this->authorizeManage($user);

        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);

        ActivityLog::record('admin_toggle_user_status', "Set {$user->role} {$user->name} status to {$newStatus}");

        return back()->with('success', "{$user->name} has been {$newStatus}.");
    }

    public function destroy(User $user)
    {
        $this->authorizeManage($user);

        $name = $user->name;

        if ($user->role === 'teacher') {
            TeacherAssignment::where('user_id', $user->id)->delete();
        }

        if ($user->photo)     Storage::disk('public')->delete($user->photo);
        if ($user->signature) Storage::disk('public')->delete($user->signature);

        $user->delete();

        ActivityLog::record('admin_delete_user', "Deleted {$user->role} account: {$name}");

        return redirect()->route('admin.users.index')
            ->with('success', "{$name}'s account has been deleted.");
    }

    // ── Helpers ──────────────────────────────────────────────────────

    /**
     * Enforce role-based access for managing a given user.
     * - Both principal and admin can manage teachers.
     * - Only principal can manage admin accounts.
     * - Neither can manage another principal via this controller.
     */
    private function authorizeManage(User $user): void
    {
        $auth = auth()->user();

        if ($user->role === 'teacher') {
            abort_if(!$auth->hasAdminAccess(), 403);
            return;
        }

        if ($user->role === 'admin') {
            abort_if(!$auth->isPrincipal(), 403, 'Only the principal can manage admin accounts.');
            return;
        }

        // principal accounts cannot be managed through this controller
        abort(403, 'This account cannot be managed here.');
    }

    /**
     * Sync teacher assignments from a per-class subject map.
     * $assignments = [ class_id => [subject_id, ...], ... ]
     * Each class independently lists which subjects the teacher teaches in it.
     */
    private function syncAssignments(User $user, array $assignments): void
    {
        TeacherAssignment::where('user_id', $user->id)->delete();

        $rows = [];
        foreach ($assignments as $classId => $subjectIds) {
            foreach ((array) $subjectIds as $subjectId) {
                $rows[] = [
                    'user_id'    => $user->id,
                    'class_id'   => (int) $classId,
                    'subject_id' => (int) $subjectId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if ($rows) {
            TeacherAssignment::insert($rows);
        }
    }
}
