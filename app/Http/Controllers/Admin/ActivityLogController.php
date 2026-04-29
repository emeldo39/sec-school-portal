<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|integer|exists:users,id',
            'action'  => 'nullable|string|max:100',
            'date'    => 'nullable|date_format:Y-m-d',
        ]);

        $query = ActivityLog::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $logs  = $query->orderByDesc('created_at')->paginate(50)->withQueryString();
        $users = \App\Models\User::orderBy('name')->get(['id', 'name']);

        return view('admin.activity-logs.index', compact('logs', 'users'));
    }
}
