@extends('layouts.portal')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')
@section('page-subtitle', 'System-wide audit trail')

@section('content')

{{-- Filters --}}
<div class="card shadow-1 radius-8 p-16 mb-16">
    <form method="GET" class="row g-12 align-items-end">
        <div class="col-sm-3">
            <label class="form-label text-sm fw-semibold mb-4">User</label>
            <select name="user_id" class="form-select form-select-sm">
                <option value="">All Users</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <label class="form-label text-sm fw-semibold mb-4">Action</label>
            <input type="text" name="action" value="{{ request('action') }}" class="form-control form-control-sm" placeholder="e.g. login">
        </div>
        <div class="col-sm-3">
            <label class="form-label text-sm fw-semibold mb-4">Date</label>
            <input type="date" name="date" value="{{ request('date') }}" class="form-control form-control-sm">
        </div>
        <div class="col-sm-1">
            <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
        </div>
        <div class="col-sm-2">
            <a href="{{ route('admin.activity-logs') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
        </div>
    </form>
</div>

<div class="card shadow-1 radius-8">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-24 py-12 text-sm">Timestamp</th>
                        <th class="px-16 py-12 text-sm">User</th>
                        <th class="px-16 py-12 text-sm">Action</th>
                        <th class="px-16 py-12 text-sm">Description</th>
                        <th class="px-16 py-12 text-sm">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="px-24 py-10 text-xs text-secondary-light">{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y H:i:s') }}</td>
                        <td class="px-16 py-10 text-sm">{{ $log->user->name ?? 'System' }}</td>
                        <td class="px-16 py-10">
                            <span class="badge bg-primary-100 text-primary-600 px-8 py-4 radius-4 text-xs">{{ $log->action }}</span>
                        </td>
                        <td class="px-16 py-10 text-sm">{{ $log->description }}</td>
                        <td class="px-16 py-10 text-xs text-secondary-light">{{ $log->ip_address }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-32 text-secondary-light">No activity logs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
    <div class="card-footer px-24 py-12">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
