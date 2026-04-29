<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->status === 'suspended') {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been suspended.']);
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Unauthorized. You do not have access to this area.');
        }

        return $next($request);
    }
}
