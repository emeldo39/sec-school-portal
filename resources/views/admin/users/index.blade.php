@extends('layouts.portal')

@section('title', 'Staff Accounts')
@section('page-title', 'Staff Accounts')
@section('page-subtitle', 'Manage teacher logins and class assignments')

@section('breadcrumb-actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-2">
        <i class="ri-add-line"></i> Add Staff
    </a>
@endsection

@section('content')

{{-- ── Teachers ──────────────────────────────────────────────── --}}
<div class="card shadow-1 radius-8 mb-24">
    <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10 flex-wrap">
        <i class="ri-user-2-line text-primary-600"></i>
        <h6 class="fw-semibold mb-0">Teachers <span class="badge bg-primary-100 text-primary-600 ms-2">{{ $teachers->count() }}</span></h6>
        <div class="ms-auto d-flex gap-8 flex-wrap">
            <button type="button" class="btn btn-sm btn-outline-secondary px-10 py-4 text-xs" id="filterFormTeachers">
                <i class="ri-user-star-line me-1"></i> Form Teachers Only
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary px-10 py-4 text-xs" id="clearFilter" style="display:none;">
                <i class="ri-close-line me-1"></i> Show All
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="teacherTable">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-24 py-14 text-sm">#</th>
                        <th class="px-16 py-14 text-sm">Name</th>
                        <th class="px-16 py-14 text-sm">Email</th>
                        <th class="px-16 py-14 text-sm">Phone</th>
                        <th class="px-16 py-14 text-sm">Form Class</th>
                        <th class="px-16 py-14 text-sm">Subjects</th>
                        <th class="px-16 py-14 text-sm">Status</th>
                        <th class="px-16 py-14 text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teachers as $i => $teacher)
                    <tr>
                        <td class="px-24 py-12 text-sm">{{ $i + 1 }}</td>
                        <td class="px-16 py-12">
                            <div class="d-flex align-items-center gap-10">
                                @if($teacher->photo)
                                    <img src="{{ asset('storage/' . $teacher->photo) }}" class="w-36-px h-36-px rounded-circle object-fit-cover" alt="">
                                @else
                                    <span class="w-36-px h-36-px rounded-circle bg-primary-100 text-primary-600 d-flex align-items-center justify-content-center fw-bold text-sm">
                                        {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                    </span>
                                @endif
                                <span class="text-sm fw-medium">{{ $teacher->name }}</span>
                            </div>
                        </td>
                        <td class="px-16 py-12 text-sm">{{ $teacher->email }}</td>
                        <td class="px-16 py-12 text-sm">{{ $teacher->phone ?? '—' }}</td>
                        <td class="px-16 py-12 text-sm" data-is-form-teacher="{{ $teacher->is_form_teacher ? '1' : '0' }}">
                            @if($teacher->is_form_teacher && $teacher->formClass)
                                <span class="badge bg-primary-100 text-primary-600 px-8 py-4 radius-4">
                                    {{ $teacher->formClass->name }}
                                </span>
                            @else
                                <span class="text-secondary-light">—</span>
                            @endif
                        </td>
                        <td class="px-16 py-12 text-sm">
                            @php
                                $subjectNames = $teacher->assignments->pluck('subject.name')->filter()->unique()->sort()->values();
                            @endphp
                            @if($subjectNames->isNotEmpty())
                                <span class="badge bg-info-100 text-info-600 px-8 py-4 radius-4"
                                      title="{{ $subjectNames->implode(', ') }}"
                                      data-bs-toggle="tooltip">
                                    {{ $subjectNames->count() }} subject{{ $subjectNames->count() === 1 ? '' : 's' }}
                                </span>
                            @else
                                <span class="text-secondary-light">—</span>
                            @endif
                        </td>
                        <td class="px-16 py-12">
                            @if($teacher->status === 'active')
                                <span class="badge bg-success-100 text-success-600 px-8 py-4 radius-4">Active</span>
                            @else
                                <span class="badge bg-danger-100 text-danger-600 px-8 py-4 radius-4">Suspended</span>
                            @endif
                        </td>
                        <td class="px-16 py-12">
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('admin.users.edit', $teacher) }}"
                                   class="btn btn-sm btn-outline-primary px-8 py-4" title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('admin.users.toggle-status', $teacher) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm {{ $teacher->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }} px-8 py-4"
                                            title="{{ $teacher->status === 'active' ? 'Suspend' : 'Activate' }}"
                                            onclick="return confirm('{{ $teacher->status === 'active' ? 'Suspend' : 'Activate' }} {{ $teacher->name }}?')">
                                        <i class="ri-{{ $teacher->status === 'active' ? 'lock-line' : 'lock-unlock-line' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.destroy', $teacher) }}" method="POST" class="m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-8 py-4" title="Delete"
                                            onclick="return confirm('Delete {{ $teacher->name }}? This cannot be undone.')">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── Admin (IT) Accounts — Principal only ─────────────────── --}}
@if(auth()->user()->isPrincipal())
<div class="card shadow-1 radius-8">
    <div class="card-header py-14 px-24 border-bottom d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-10">
            <i class="ri-shield-user-line text-warning-600"></i>
            <h6 class="fw-semibold mb-0">IT / Admin Accounts <span class="badge bg-warning-100 text-warning-600 ms-2">{{ $admins->count() }}</span></h6>
        </div>
        <a href="{{ route('admin.users.create') }}?account_role=admin"
           class="btn btn-sm btn-outline-warning d-flex align-items-center gap-2">
            <i class="ri-add-line"></i> Add Admin Account
        </a>
    </div>
    <div class="card-body p-0">
        @if($admins->isEmpty())
        <div class="text-center py-32 text-secondary-light">
            <i class="ri-shield-user-line d-block mb-8" style="font-size:2rem;opacity:.3;"></i>
            No IT/Admin accounts yet.
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="adminTable">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-24 py-14 text-sm">#</th>
                        <th class="px-16 py-14 text-sm">Name</th>
                        <th class="px-16 py-14 text-sm">Email</th>
                        <th class="px-16 py-14 text-sm">Phone</th>
                        <th class="px-16 py-14 text-sm">Status</th>
                        <th class="px-16 py-14 text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($admins as $i => $admin)
                    <tr>
                        <td class="px-24 py-12 text-sm">{{ $i + 1 }}</td>
                        <td class="px-16 py-12">
                            <div class="d-flex align-items-center gap-10">
                                @if($admin->photo)
                                    <img src="{{ asset('storage/' . $admin->photo) }}" class="w-36-px h-36-px rounded-circle object-fit-cover" alt="">
                                @else
                                    <span class="w-36-px h-36-px rounded-circle bg-warning-100 text-warning-600 d-flex align-items-center justify-content-center fw-bold text-sm">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </span>
                                @endif
                                <span class="text-sm fw-medium">{{ $admin->name }}</span>
                            </div>
                        </td>
                        <td class="px-16 py-12 text-sm">{{ $admin->email }}</td>
                        <td class="px-16 py-12 text-sm">{{ $admin->phone ?? '—' }}</td>
                        <td class="px-16 py-12">
                            @if($admin->status === 'active')
                                <span class="badge bg-success-100 text-success-600 px-8 py-4 radius-4">Active</span>
                            @else
                                <span class="badge bg-danger-100 text-danger-600 px-8 py-4 radius-4">Suspended</span>
                            @endif
                        </td>
                        <td class="px-16 py-12">
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('admin.users.edit', $admin) }}"
                                   class="btn btn-sm btn-outline-primary px-8 py-4" title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </a>
                                <form action="{{ route('admin.users.toggle-status', $admin) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm {{ $admin->status === 'active' ? 'btn-outline-warning' : 'btn-outline-success' }} px-8 py-4"
                                            title="{{ $admin->status === 'active' ? 'Suspend' : 'Activate' }}"
                                            onclick="return confirm('{{ $admin->status === 'active' ? 'Suspend' : 'Activate' }} {{ $admin->name }}?')">
                                        <i class="ri-{{ $admin->status === 'active' ? 'lock-line' : 'lock-unlock-line' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.users.destroy', $admin) }}" method="POST" class="m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-8 py-4" title="Delete"
                                            onclick="return confirm('Delete admin account for {{ $admin->name }}? This cannot be undone.')">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endif

{{-- ── Principal Account — IT Admin only (password reset only) ── --}}
@if(auth()->user()->isAdmin() && $principals->isNotEmpty())
<div class="card shadow-1 radius-8 mt-24">
    <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
        <i class="ri-user-star-line text-primary-600"></i>
        <h6 class="fw-semibold mb-0">Principal Account</h6>
        <span class="badge bg-primary-100 text-primary-600 ms-1">{{ $principals->count() }}</span>
        <span class="ms-auto text-xs text-secondary-light">
            <i class="ri-information-line me-1"></i>Password reset only — full management is restricted to the Principal
        </span>
    </div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead class="bg-neutral-50">
                <tr>
                    <th class="px-24 py-14 text-sm">#</th>
                    <th class="px-16 py-14 text-sm">Name</th>
                    <th class="px-16 py-14 text-sm">Email</th>
                    <th class="px-16 py-14 text-sm">Phone</th>
                    <th class="px-16 py-14 text-sm">Status</th>
                    <th class="px-16 py-14 text-sm">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($principals as $i => $principal)
                <tr>
                    <td class="px-24 py-12 text-sm">{{ $i + 1 }}</td>
                    <td class="px-16 py-12">
                        <div class="d-flex align-items-center gap-10">
                            @if($principal->photo)
                                <img src="{{ asset('storage/' . $principal->photo) }}" class="w-36-px h-36-px rounded-circle object-fit-cover" alt="">
                            @else
                                <span class="w-36-px h-36-px rounded-circle bg-primary-600 text-white d-flex align-items-center justify-content-center fw-bold text-sm">
                                    {{ strtoupper(substr($principal->name, 0, 1)) }}
                                </span>
                            @endif
                            <div>
                                <span class="text-sm fw-medium d-block">{{ $principal->name }}</span>
                                <span class="text-xs text-secondary-light">Principal</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-16 py-12 text-sm">{{ $principal->email }}</td>
                    <td class="px-16 py-12 text-sm">{{ $principal->phone ?? '—' }}</td>
                    <td class="px-16 py-12">
                        @if($principal->status === 'active')
                            <span class="badge bg-success-100 text-success-600 px-8 py-4 radius-4">Active</span>
                        @else
                            <span class="badge bg-danger-100 text-danger-600 px-8 py-4 radius-4">Suspended</span>
                        @endif
                    </td>
                    <td class="px-16 py-12">
                        <button type="button" class="btn btn-sm btn-outline-warning px-12"
                                data-bs-toggle="modal" data-bs-target="#resetPrincipalModal{{ $principal->id }}">
                            <i class="ri-lock-password-line me-1"></i> Reset Password
                        </button>
                    </td>
                </tr>

                {{-- Reset Password Modal --}}
                <div class="modal fade" id="resetPrincipalModal{{ $principal->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-bottom">
                                <h6 class="modal-title fw-semibold">Reset Principal Password</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('admin.users.reset-password', $principal) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <p class="text-sm text-secondary-light mb-16">
                                        Setting a new password for <strong class="text-neutral-900">{{ $principal->name }}</strong>.
                                        They will need to use this new password to log in.
                                    </p>
                                    <div class="mb-12">
                                        <label class="form-label fw-semibold text-sm mb-4">New Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control" required minlength="6" placeholder="Min. 6 characters">
                                    </div>
                                    <div>
                                        <label class="form-label fw-semibold text-sm mb-4">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" class="form-control" required placeholder="Repeat password">
                                    </div>
                                </div>
                                <div class="modal-footer border-top">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-sm btn-warning px-20 text-white">
                                        <i class="ri-lock-password-line me-1"></i> Reset Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        var teacherTable = $('#teacherTable').DataTable({
            order: [[1, 'asc']],
            pageLength: 25,
            language: {
                emptyTable: 'No teacher accounts found.',
                zeroRecords: 'No matching teachers found.'
            },
            columnDefs: [{ orderable: false, targets: [0, 7] }]
        });

        // Bootstrap tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Form teacher filter
        var filteringFormTeachers = false;
        $('#filterFormTeachers').on('click', function () {
            filteringFormTeachers = true;
            $(this).hide();
            $('#clearFilter').show();
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'teacherTable') return true;
                var row = teacherTable.row(dataIndex).node();
                return $(row).find('[data-is-form-teacher]').data('is-form-teacher') == '1';
            });
            teacherTable.draw();
        });
        $('#clearFilter').on('click', function () {
            filteringFormTeachers = false;
            $(this).hide();
            $('#filterFormTeachers').show();
            $.fn.dataTable.ext.search.pop();
            teacherTable.draw();
        });
        @if(auth()->user()->isPrincipal() && $admins->isNotEmpty())
        $('#adminTable').DataTable({
            order: [[1, 'asc']],
            pageLength: 10,
            language: {
                emptyTable: 'No admin accounts found.',
                zeroRecords: 'No matching admins found.'
            },
            columnDefs: [{ orderable: false, targets: [0, 5] }]
        });
        @endif
    });
</script>
@endpush
