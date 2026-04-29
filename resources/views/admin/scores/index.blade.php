@extends('layouts.portal')

@section('title', 'Score Approval')
@section('page-title', 'Score Approval')
@section('page-subtitle', 'Review and approve teacher score submissions')

@section('content')

{{-- ── Statistics ─────────────────────────────────────────────────── --}}
<div class="row gy-16 mb-20">
    @php
    $statCards = [
    ['label' => 'Total Scores', 'key' => null, 'icon' => 'ri-bar-chart-2-line', 'color' => 'primary'],
    ['label' => 'Submitted', 'key' => 'submitted', 'icon' => 'ri-send-plane-line', 'color' => 'warning'],
    ['label' => 'Approved', 'key' => 'approved', 'icon' => 'ri-checkbox-circle-line', 'color' => 'success'],
    ['label' => 'Locked', 'key' => 'locked', 'icon' => 'ri-lock-line', 'color' => 'primary'],
    ['label' => 'Returned', 'key' => 'returned', 'icon' => 'ri-arrow-go-back-line', 'color' => 'danger'],
    ['label' => 'Draft', 'key' => 'draft', 'icon' => 'ri-draft-line', 'color' => 'secondary'],
    ];
    @endphp
    @foreach($statCards as $card)
    @php $count = $card['key'] === null ? $statsAll : ($stats[$card['key']] ?? 0); @endphp
    <div class="col-6 col-sm-4 col-xl-2">
        <div class="card shadow-1 radius-8 h-100">
            <div class="card-body px-16 py-14 d-flex align-items-center gap-12">
                <span class="w-40-px h-40-px radius-8 d-flex align-items-center justify-content-center flex-shrink-0
                             bg-{{ $card['color'] }}-100 text-{{ $card['color'] }}-600" style="font-size:1.2rem;">
                    <i class="{{ $card['icon'] }}"></i>
                </span>
                <div>
                    <p class="fw-bold text-lg mb-0 lh-1" style="color:#2A2567;">{{ number_format($count) }}</p>
                    <p class="text-xs text-secondary-light mb-0 mt-2">{{ $card['label'] }}</p>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ── Filters ──────────────────────────────────────────────────────── --}}
<div class="card shadow-1 radius-8 p-16 mb-16">
    <form method="GET" id="filterForm" class="row g-12 align-items-end">

        <div class="col-sm-3">
            <label class="form-label text-sm fw-semibold mb-4">Status</label>
            <select name="status" class="form-select form-select-sm auto-submit">
                <option value="submitted" {{ request('status','submitted') === 'submitted' ? 'selected' : '' }}>
                    Submitted</option>
                <option value="approved" {{ request('status') === 'approved'  ? 'selected' : '' }}>Approved</option>
                <option value="returned" {{ request('status') === 'returned'  ? 'selected' : '' }}>Returned</option>
                <option value="locked" {{ request('status') === 'locked'    ? 'selected' : '' }}>Locked</option>
                <option value="draft" {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
            </select>
        </div>
        <div class="col-sm-3">
            <label class="form-label text-sm fw-semibold mb-4">Class</label>
            <select name="class_id" class="form-select form-select-sm auto-submit">
                <option value="">All Classes</option>
                @foreach($classes as $c)
                <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <label class="form-label text-sm fw-semibold mb-4">Term</label>
            <select name="term_id" class="form-select form-select-sm auto-submit">
                <option value="">All Terms</option>
                @foreach($terms as $t)
                <option value="{{ $t->id }}" {{ request('term_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}
                    {{ $t->academic_year }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3 d-flex gap-8">
            <button type="submit" class="btn btn-primary btn-sm flex-fill">
                <i class="ri-filter-3-line me-1"></i>Filter
            </button>
            <a href="{{ route('admin.scores.index') }}" class="btn btn-outline-secondary btn-sm px-12"
                title="Clear all filters">
                <i class="ri-refresh-line"></i>
            </a>
        </div>

        {{-- Search --}}
        <div class="col-12 mt-8">
            <div class="position-relative">
                <input type="text" name="search" id="scoreSearch" value="{{ request('search') }}"
                    class="form-control form-control-sm ps-32"
                    placeholder="Search by student name, admission no., subject or teacher…" autocomplete="off">
                <i class="ri-search-line position-absolute"
                    style="left:10px;top:50%;transform:translateY(-50%);color:#9B9AC0;font-size:14px;pointer-events:none;"></i>
                <button type="button" id="clearSearch" class="position-absolute border-0 bg-transparent p-0"
                    style="right:10px;top:50%;transform:translateY(-50%);color:#9B9AC0;font-size:14px;cursor:pointer;{{ request('search') ? '' : 'display:none;' }}"
                    title="Clear search">
                    <i class="ri-close-line"></i>
                </button>
            </div>
        </div>

    </form>
</div>

{{-- ── Bulk Action Toolbar (hidden until rows selected) ─────────────── --}}
<div id="bulkToolbar" class="card shadow-1 radius-8 px-20 py-12 mb-12 d-none">
    <div class="d-flex align-items-center gap-16 flex-wrap">
        <span class="fw-semibold text-sm" style="color:#2A2567;">
            <i class="ri-checkbox-multiple-line me-1"></i>
            <span id="selectedCount">0</span> selected
        </span>
        <div class="d-flex gap-8 ms-auto flex-wrap">
            {{-- Bulk Approve (principal only) --}}
            @if(auth()->user()->isPrincipal())
            <form id="bulkApproveForm" action="{{ route('admin.scores.bulk-approve') }}" method="POST" class="m-0">
                @csrf
                <div id="bulkApproveIds"></div>
                <button type="submit" class="btn btn-sm btn-success px-16"
                    onclick="return injectIds('bulkApproveIds') && confirm('Approve all selected submitted/returned scores?')">
                    <i class="ri-checkbox-circle-line me-1"></i>Approve Selected
                </button>
            </form>
            @endif
            {{-- Bulk Delete --}}
            <button type="button" class="btn btn-sm btn-danger px-16" onclick="openBulkDeleteModal()">
                <i class="ri-delete-bin-line me-1"></i>Delete Selected
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary px-12" onclick="clearSelection()">
                <i class="ri-close-line"></i>
            </button>
        </div>
    </div>
</div>

@if($errors->has('password'))
<div class="alert alert-danger alert-dismissible d-flex align-items-center gap-8 radius-8 mb-16" role="alert">
    <i class="ri-error-warning-line text-danger-600 fs-5"></i>
    <div class="text-sm">{{ $errors->first('password') }}</div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- ── Table ────────────────────────────────────────────────────────── --}}
<div class="card shadow-1 radius-8">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="scoresTable">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-16 py-14 text-sm" style="width:44px;">
                            <input type="checkbox" id="selectAll" class="form-check-input mt-0"
                                style="width:16px;height:16px;cursor:pointer;">
                        </th>
                        <th class="px-16 py-14 text-sm">Student</th>
                        <th class="px-16 py-14 text-sm">Subject</th>
                        <th class="px-16 py-14 text-sm">Class</th>
                        <th class="px-16 py-14 text-sm">Term</th>
                        <th class="px-16 py-14 text-sm">Total</th>
                        <th class="px-16 py-14 text-sm">Submitted By</th>
                        <th class="px-16 py-14 text-sm">Status</th>
                        <th class="px-16 py-14 text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scores as $score)
                    @php
                    $map=['submitted'=>['warning','Submitted'],'approved'=>['success','Approved'],'returned'=>['danger','Returned'],'locked'=>['primary','Locked'],'draft'=>['secondary','Draft']];
                    $s=$map[$score->status]??['secondary',$score->status]; @endphp
                    <tr data-id="{{ $score->id }}">
                        <td class="px-16 py-12">
                            <input type="checkbox" class="row-check form-check-input mt-0" value="{{ $score->id }}"
                                style="width:16px;height:16px;cursor:pointer;">
                        </td>
                        <td class="px-16 py-12 text-sm fw-medium">
                            <a href="{{ route('admin.scores.show', $score) }}" class="text-primary-600">
                                {{ $score->student->full_name ?? '—' }}
                            </a>
                        </td>
                        <td class="px-16 py-12 text-sm">{{ $score->subject->name ?? '—' }}</td>
                        <td class="px-16 py-12 text-sm">{{ $score->schoolClass->name ?? '—' }}</td>
                        <td class="px-16 py-12 text-sm text-secondary-light">{{ $score->term->name ?? '—' }}</td>
                        <td class="px-16 py-12 text-sm fw-bold" style="color:#2A2567;">{{ $score->total_score ?? '—' }}
                        </td>
                        <td class="px-16 py-12 text-sm">{{ $score->submittedBy->name ?? '—' }}</td>
                        <td class="px-16 py-12">
                            <span
                                class="badge bg-{{ $s[0] }}-100 text-{{ $s[0] }}-600 px-8 py-4 radius-4 text-xs fw-semibold">
                                {{ $s[1] }}
                            </span>
                        </td>
                        <td class="px-16 py-12">
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.scores.show', $score) }}"
                                    class="btn btn-sm btn-outline-info px-8 py-4" title="View details">
                                    <i class="ri-eye-line"></i>
                                </a>
                                @if(auth()->user()->isPrincipal())
                                @if(in_array($score->status, ['submitted','returned']))
                                <form action="{{ route('admin.scores.approve', $score) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success px-8 py-4"
                                        title="Approve">
                                        <i class="ri-check-line"></i>
                                    </button>
                                </form>
                                @endif
                                @if($score->status === 'submitted')
                                <button class="btn btn-sm btn-outline-warning px-8 py-4" title="Return for revision"
                                    data-bs-toggle="modal" data-bs-target="#returnModal{{ $score->id }}">
                                    <i class="ri-arrow-go-back-line"></i>
                                </button>
                                @endif
                                @if($score->status === 'approved')
                                <form action="{{ route('admin.scores.lock', $score) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary px-8 py-4"
                                        title="Lock score">
                                        <i class="ri-lock-line"></i>
                                    </button>
                                </form>
                                @endif
                                @if($score->status === 'locked')
                                <form action="{{ route('admin.scores.unlock', $score) }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary px-8 py-4"
                                        title="Unlock score">
                                        <i class="ri-lock-unlock-line"></i>
                                    </button>
                                </form>
                                @endif
                                @endif {{-- isPrincipal --}}
                            </div>
                        </td>
                    </tr>

                    {{-- Return Modal --}}
                    <div class="modal fade" id="returnModal{{ $score->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header border-bottom">
                                    <h6 class="modal-title fw-semibold">Return Score for Revision</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.scores.return', $score) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <p class="text-sm mb-12 text-secondary-light">
                                            Returning score for <strong
                                                class="text-neutral-900">{{ $score->student->full_name ?? '' }}</strong>
                                            — {{ $score->subject->name ?? '' }}
                                        </p>
                                        <label class="form-label text-sm fw-semibold mb-4">Reason / Remarks <span
                                                class="text-danger">*</span></label>
                                        <textarea name="remarks" rows="3" class="form-control" required
                                            placeholder="Explain what needs to be corrected…"></textarea>
                                    </div>
                                    <div class="modal-footer border-top">
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-sm btn-warning px-20">
                                            <i class="ri-arrow-go-back-line me-1"></i>Return
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-40 text-secondary-light">
                            <i class="ri-inbox-line d-block mb-8" style="font-size:2rem;opacity:.35;"></i>
                            No scores found for the selected filters.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($scores->hasPages())
    <div class="card-footer px-24 py-12 border-top">{{ $scores->links() }}</div>
    @endif
</div>

{{-- ── Bulk Delete Modal (password-protected) ────────────────────────── --}}
<div class="modal fade" id="bulkDeleteModal" tabindex="-1" data-reopen="{{ $errors->has('password') ? '1' : '0' }}">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h6 class="modal-title fw-semibold text-danger">
                    <i class="ri-delete-bin-line me-1"></i>Delete Selected Scores
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="bulkDeleteForm" action="{{ route('admin.scores.bulk-delete') }}" method="POST">
                @csrf
                <div id="bulkDeleteIds"></div>
                <div class="modal-body">
                    <div class="alert d-flex gap-10 radius-8 mb-16"
                        style="background:rgba(220,53,69,.08);border:1px solid rgba(220,53,69,.2);">
                        <i class="ri-error-warning-line text-danger-600 fs-5 flex-shrink-0 mt-1"></i>
                        <div>
                            <p class="fw-semibold text-sm mb-2 text-danger-700">This action is irreversible.</p>
                            <p class="text-xs mb-0 text-secondary-light">
                                <span id="deleteCountLabel" class="fw-semibold text-neutral-900">0</span> score
                                record(s) will be permanently deleted.
                                This cannot be undone.
                            </p>
                        </div>
                    </div>
                    <label class="form-label fw-semibold text-sm mb-4">
                        Enter your admin password to confirm <span class="text-danger">*</span>
                    </label>
                    <input type="password" name="password" id="deletePassword"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Your current password"
                        required autocomplete="current-password">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-sm btn-outline-secondary"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger px-20">
                        <i class="ri-delete-bin-line me-1"></i>Confirm Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ── Selection state ────────────────────────────────────────────
const selectAll = document.getElementById('selectAll');
const toolbar = document.getElementById('bulkToolbar');
const countLabel = document.getElementById('selectedCount');

function getChecked() {
    return [...document.querySelectorAll('.row-check:checked')];
}

function refreshToolbar() {
    const checked = getChecked();
    countLabel.textContent = checked.length;
    toolbar.classList.toggle('d-none', checked.length === 0);
    // sync selectAll indeterminate state
    const all = document.querySelectorAll('.row-check');
    selectAll.indeterminate = checked.length > 0 && checked.length < all.length;
    selectAll.checked = all.length > 0 && checked.length === all.length;
}

selectAll.addEventListener('change', function() {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
    refreshToolbar();
});

document.querySelectorAll('.row-check').forEach(cb => {
    cb.addEventListener('change', refreshToolbar);
});

function clearSelection() {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = false);
    selectAll.checked = false;
    refreshToolbar();
}

// ── Inject hidden inputs into a form before submit ─────────────
function injectIds(containerId) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    getChecked().forEach(cb => {
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'score_ids[]';
        inp.value = cb.value;
        container.appendChild(inp);
    });
    if (getChecked().length === 0) {
        alert('Please select at least one score.');
        return false;
    }
    return true;
}

// ── Bulk Delete Modal ──────────────────────────────────────────
function openBulkDeleteModal() {
    const checked = getChecked();
    if (checked.length === 0) {
        alert('Please select at least one score.');
        return;
    }

    // Inject ids
    const container = document.getElementById('bulkDeleteIds');
    container.innerHTML = '';
    checked.forEach(cb => {
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'score_ids[]';
        inp.value = cb.value;
        container.appendChild(inp);
    });

    document.getElementById('deleteCountLabel').textContent = checked.length;
    document.getElementById('deletePassword').value = '';

    const modal = new bootstrap.Modal(document.getElementById('bulkDeleteModal'));
    modal.show();
}

// Re-open delete modal if password error (validation failed)
if (document.getElementById('bulkDeleteModal').dataset.reopen === '1') {
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('bulkDeleteModal')).show();
    });
}

// ── Search: debounced auto-submit ─────────────────────────────
(function() {
    const form = document.getElementById('filterForm');
    const input = document.getElementById('scoreSearch');
    const clearBtn = document.getElementById('clearSearch');
    let timer = null;

    input.addEventListener('input', function() {
        clearBtn.style.display = this.value ? '' : 'none';
        clearTimeout(timer);
        timer = setTimeout(() => form.submit(), 380);
    });

    clearBtn.addEventListener('click', function() {
        input.value = '';
        this.style.display = 'none';
        clearTimeout(timer);
        form.submit();
    });

    // Auto-submit dropdowns immediately
    document.querySelectorAll('.auto-submit').forEach(sel => {
        sel.addEventListener('change', () => {
            clearTimeout(timer);
            form.submit();
        });
    });
})();
</script>
@endpush