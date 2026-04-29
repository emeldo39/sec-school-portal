@extends('layouts.portal')

@section('title', 'Subjects')
@section('page-title', 'Subjects')
@section('page-subtitle', 'Manage school subjects')

@section('breadcrumb-actions')
<button class="btn btn-primary btn-sm d-flex align-items-center gap-2"
        data-bs-toggle="modal" data-bs-target="#addSubjectModal">
    <i class="ri-add-line"></i> Add Subject
</button>
@endsection

@section('content')

{{-- ── Stat Cards ── --}}
<div class="row gy-4 mb-24">
    <div class="col-6 col-xl">
        <div class="card shadow-1 radius-8 p-20 d-flex flex-row align-items-center gap-16">
            <div class="w-52-px h-52-px radius-8 bg-primary-100 d-flex align-items-center justify-content-center flex-shrink-0">
                <iconify-icon icon="ph:books" class="text-primary-600 text-2xl"></iconify-icon>
            </div>
            <div>
                <p class="text-secondary-light text-xs mb-4">Total Subjects</p>
                <h5 class="fw-semibold mb-0">{{ $stats['total'] }}</h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="card shadow-1 radius-8 p-20 d-flex flex-row align-items-center gap-16">
            <div class="w-52-px h-52-px radius-8 bg-info-100 d-flex align-items-center justify-content-center flex-shrink-0">
                <iconify-icon icon="ph:student" class="text-info-600 text-2xl"></iconify-icon>
            </div>
            <div>
                <p class="text-secondary-light text-xs mb-4">Junior Only</p>
                <h5 class="fw-semibold mb-0">{{ $stats['jss'] }}</h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="card shadow-1 radius-8 p-20 d-flex flex-row align-items-center gap-16">
            <div class="w-52-px h-52-px radius-8 bg-warning-100 d-flex align-items-center justify-content-center flex-shrink-0">
                <iconify-icon icon="ph:graduation-cap" class="text-warning-600 text-2xl"></iconify-icon>
            </div>
            <div>
                <p class="text-secondary-light text-xs mb-4">Senior Only</p>
                <h5 class="fw-semibold mb-0">{{ $stats['sss'] }}</h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="card shadow-1 radius-8 p-20 d-flex flex-row align-items-center gap-16">
            <div class="w-52-px h-52-px radius-8 bg-success-100 d-flex align-items-center justify-content-center flex-shrink-0">
                <iconify-icon icon="ph:intersect" class="text-success-600 text-2xl"></iconify-icon>
            </div>
            <div>
                <p class="text-secondary-light text-xs mb-4">Both Levels</p>
                <h5 class="fw-semibold mb-0">{{ $stats['both'] }}</h5>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl">
        <div class="card shadow-1 radius-8 p-20 d-flex flex-row align-items-center gap-16">
            <div class="w-52-px h-52-px radius-8 bg-neutral-100 d-flex align-items-center justify-content-center flex-shrink-0">
                <iconify-icon icon="ph:chalkboard-teacher" class="text-neutral-600 text-2xl"></iconify-icon>
            </div>
            <div>
                <p class="text-secondary-light text-xs mb-4">Teacher Assignments</p>
                <h5 class="fw-semibold mb-0">{{ $stats['assignments'] }}</h5>
            </div>
        </div>
    </div>
</div>

{{-- ── Table Card ── --}}
<div class="card shadow-1 radius-8">

    {{-- Toolbar --}}
    <div class="card-header py-16 px-24 border-bottom d-flex flex-wrap align-items-center gap-12">

        {{-- Bulk action bar (hidden until rows selected) --}}
        <div id="bulkBar" class="d-none d-flex align-items-center gap-10 me-auto">
            <span class="text-sm fw-semibold text-primary-600" id="selectedCount">0</span>
            <span class="text-sm text-secondary-light">selected</span>
            <form id="bulkDeleteForm" action="{{ route('admin.subjects.bulk-destroy') }}" method="POST" class="m-0">
                @csrf @method('DELETE')
                <div id="bulkIdsContainer"></div>
                <button type="submit" class="btn btn-sm btn-danger px-12"
                        onclick="return confirm('Delete selected subjects? This cannot be undone.')">
                    <i class="ri-delete-bin-line me-1"></i> Delete Selected
                </button>
            </form>
        </div>

        <span class="text-sm text-secondary-light me-auto" id="normalLabel">
            <span class="fw-semibold text-dark" id="visibleCount">{{ $subjects->count() }}</span>
            of {{ $subjects->count() }} subjects
        </span>

        {{-- Search --}}
        <div class="input-group" style="width:210px;">
            <span class="input-group-text bg-base border-end-0 pe-0">
                <iconify-icon icon="ph:magnifying-glass" class="text-secondary-light"></iconify-icon>
            </span>
            <input type="text" id="searchInput" class="form-control border-start-0 ps-0 text-sm"
                   placeholder="Search subject…">
        </div>

        {{-- Level filter --}}
        <select id="levelFilter" class="form-select form-select-sm" style="width:140px;">
            <option value="">All Levels</option>
            <option value="JSS">Junior Only</option>
            <option value="SSS">Senior Only</option>
            <option value="Both">Both</option>
        </select>

        {{-- Sort --}}
        <select id="sortSelect" class="form-select form-select-sm" style="width:150px;">
            <option value="name-asc">Name A → Z</option>
            <option value="name-desc">Name Z → A</option>
            <option value="assign-desc">Most Assigned</option>
            <option value="assign-asc">Least Assigned</option>
        </select>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="subjectsTable">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-16 py-14" style="width:40px;">
                            <input type="checkbox" id="selectAll" class="form-check-input mt-0">
                        </th>
                        <th class="px-16 py-14 text-sm">#</th>
                        <th class="px-16 py-14 text-sm">Subject Name</th>
                        <th class="px-16 py-14 text-sm">Code</th>
                        <th class="px-16 py-14 text-sm">Level</th>
                        <th class="px-16 py-14 text-sm">Assignments</th>
                        <th class="px-16 py-14 text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subjects as $i => $subject)
                    @php
                        $lc = match($subject->level) {
                            'JSS'  => 'info',
                            'SSS'  => 'warning',
                            default => 'success',   // 'Both'
                        };
                    @endphp
                    <tr data-name="{{ strtolower($subject->name) }}"
                        data-code="{{ strtolower($subject->code ?? '') }}"
                        data-level="{{ $subject->level }}"
                        data-assign="{{ $subject->assignments_count }}"
                        data-id="{{ $subject->id }}">
                        <td class="px-16 py-12">
                            <input type="checkbox" class="form-check-input row-check mt-0"
                                   value="{{ $subject->id }}">
                        </td>
                        <td class="px-16 py-12 text-sm row-num">{{ $i + 1 }}</td>
                        <td class="px-16 py-12 text-sm fw-medium">{{ $subject->name }}</td>
                        <td class="px-16 py-12 text-sm">
                            <span class="badge bg-neutral-100 text-neutral-600 px-8 py-3 radius-4 text-xs">
                                {{ $subject->code ?? '—' }}
                            </span>
                        </td>
                        <td class="px-16 py-12">
                            <span class="badge bg-{{ $lc }}-100 text-{{ $lc }}-600 px-8 py-4 radius-4 text-sm">
                                {{ ['JSS' => 'Junior', 'SSS' => 'Senior', 'Both' => 'Both'][$subject->level] ?? $subject->level }}
                            </span>
                        </td>
                        <td class="px-16 py-12 text-sm">
                            @if($subject->assignments_count > 0)
                                <span class="fw-semibold text-primary-600">{{ $subject->assignments_count }}</span>
                                <span class="text-secondary-light"> teacher(s)</span>
                            @else
                                <span class="text-secondary-light text-xs">Unassigned</span>
                            @endif
                        </td>
                        <td class="px-16 py-12">
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary px-8 py-4"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editSubj{{ $subject->id }}"
                                        title="Edit">
                                    <i class="ri-pencil-line"></i>
                                </button>
                                <form action="{{ route('admin.subjects.destroy', $subject) }}"
                                      method="POST" class="m-0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-8 py-4"
                                            title="Delete"
                                            onclick="return confirm('Delete {{ $subject->name }}?')">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Edit Modal --}}
                    <div class="modal fade" id="editSubj{{ $subject->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title">Edit Subject — {{ $subject->name }}</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.subjects.update', $subject) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-12">
                                            <label class="form-label text-sm fw-semibold">Name</label>
                                            <input type="text" name="name" value="{{ $subject->name }}"
                                                   class="form-control" required>
                                        </div>
                                        <div class="mb-12">
                                            <label class="form-label text-sm fw-semibold">Code</label>
                                            <input type="text" name="code" value="{{ $subject->code }}"
                                                   class="form-control" placeholder="e.g. PHY">
                                        </div>
                                        <div class="mb-0">
                                            <label class="form-label text-sm fw-semibold">Level</label>
                                            <select name="level" class="form-select" required>
                                                <option value="JSS"  {{ $subject->level === 'JSS'  ? 'selected' : '' }}>Junior only</option>
                                                <option value="SSS"  {{ $subject->level === 'SSS'  ? 'selected' : '' }}>Senior only</option>
                                                <option value="Both" {{ $subject->level === 'Both' ? 'selected' : '' }}>Both (Junior &amp; Senior)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Empty state (shown by JS when filters match nothing) --}}
        <div id="emptyState" class="d-none text-center py-48 px-24">
            <iconify-icon icon="ph:books" class="text-secondary-light" style="font-size:2.5rem;"></iconify-icon>
            <p class="text-sm text-secondary-light mt-12 mb-0">No subjects match your search or filter.</p>
        </div>
    </div>
</div>

{{-- ── Add Subject Modal ── --}}
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Add Subject</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-12">
                        <label class="form-label text-sm fw-semibold">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Further Mathematics">
                    </div>
                    <div class="mb-12">
                        <label class="form-label text-sm fw-semibold">Code</label>
                        <input type="text" name="code" class="form-control" placeholder="e.g. FMT">
                    </div>
                    <div class="mb-0">
                        <label class="form-label text-sm fw-semibold">Level <span class="text-danger">*</span></label>
                        <select name="level" class="form-select" required>
                            <option value="Both">Both (Junior &amp; Senior)</option>
                            <option value="JSS">Junior only</option>
                            <option value="SSS">Senior only</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Create Subject</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const tbody        = document.querySelector('#subjectsTable tbody');
    const rows         = () => Array.from(tbody.querySelectorAll('tr'));
    const searchInput  = document.getElementById('searchInput');
    const levelFilter  = document.getElementById('levelFilter');
    const sortSelect   = document.getElementById('sortSelect');
    const selectAll    = document.getElementById('selectAll');
    const bulkBar      = document.getElementById('bulkBar');
    const normalLabel  = document.getElementById('normalLabel');
    const selectedCount= document.getElementById('selectedCount');
    const visibleCount = document.getElementById('visibleCount');
    const bulkIdsContainer = document.getElementById('bulkIdsContainer');
    const emptyState   = document.getElementById('emptyState');

    // ── Filter & sort ──────────────────────────────────────────
    function applyFilters() {
        const search = searchInput.value.toLowerCase().trim();
        const level  = levelFilter.value;

        let visibleRows = rows().filter(row => {
            const matchSearch = !search ||
                row.dataset.name.includes(search) ||
                row.dataset.code.includes(search);
            const matchLevel = !level || row.dataset.level === level;
            return matchSearch && matchLevel;
        });

        const hiddenRows = rows().filter(r => !visibleRows.includes(r));
        hiddenRows.forEach(r => r.style.display = 'none');
        visibleRows.forEach(r => r.style.display = '');

        // Sort visible rows
        const sort = sortSelect.value;
        visibleRows.sort((a, b) => {
            if (sort === 'name-asc')    return a.dataset.name.localeCompare(b.dataset.name);
            if (sort === 'name-desc')   return b.dataset.name.localeCompare(a.dataset.name);
            if (sort === 'assign-desc') return parseInt(b.dataset.assign) - parseInt(a.dataset.assign);
            if (sort === 'assign-asc')  return parseInt(a.dataset.assign) - parseInt(b.dataset.assign);
            return 0;
        });
        visibleRows.forEach(r => tbody.appendChild(r));

        // Re-number visible rows
        visibleRows.forEach((r, i) => {
            const numCell = r.querySelector('.row-num');
            if (numCell) numCell.textContent = i + 1;
        });

        visibleCount.textContent = visibleRows.length;
        emptyState.classList.toggle('d-none', visibleRows.length > 0);

        updateBulkBar();
    }

    searchInput.addEventListener('input', applyFilters);
    levelFilter.addEventListener('change', applyFilters);
    sortSelect.addEventListener('change', applyFilters);

    // ── Bulk selection ─────────────────────────────────────────
    function getChecked() {
        return Array.from(tbody.querySelectorAll('.row-check:checked'));
    }

    function updateBulkBar() {
        const checked = getChecked();
        const count   = checked.length;

        if (count > 0) {
            bulkBar.classList.remove('d-none');
            bulkBar.classList.add('d-flex');
            normalLabel.classList.add('d-none');
        } else {
            bulkBar.classList.add('d-none');
            bulkBar.classList.remove('d-flex');
            normalLabel.classList.remove('d-none');
        }

        selectedCount.textContent = count;

        // Rebuild hidden inputs for bulk form
        bulkIdsContainer.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type  = 'hidden';
            input.name  = 'ids[]';
            input.value = cb.value;
            bulkIdsContainer.appendChild(input);
        });
    }

    selectAll.addEventListener('change', function () {
        const visibleChecks = Array.from(tbody.querySelectorAll('tr:not([style*="display: none"]) .row-check'));
        visibleChecks.forEach(cb => cb.checked = selectAll.checked);
        updateBulkBar();
    });

    tbody.addEventListener('change', function (e) {
        if (e.target.classList.contains('row-check')) {
            // Sync select-all state
            const allVisible = Array.from(tbody.querySelectorAll('tr:not([style*="display: none"]) .row-check'));
            selectAll.checked = allVisible.length > 0 && allVisible.every(cb => cb.checked);
            updateBulkBar();
        }
    });
})();
</script>
@endpush
