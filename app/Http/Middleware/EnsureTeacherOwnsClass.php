<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTeacherOwnsClass
{
    /**
     * Verify the authenticated teacher is assigned to the class_id in the request.
     * Admins always pass through.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user->hasAdminAccess()) {
            return $next($request);
        }

        $classId = $request->route('class_id') ?? $request->input('class_id');

        if ($classId) {
            $assigned = $user->assignments()->where('class_id', $classId)->exists();
            if (!$assigned && !($user->is_form_teacher && $user->form_class_id == $classId)) {
                abort(403, 'You are not assigned to this class.');
            }
        }

        return $next($request);
    }
}
