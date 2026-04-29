@extends('layouts.portal')

@section('title', 'Result Sheets')
@section('page-title', 'Result Sheets')
@section('page-subtitle', 'Declare and manage student results for your form class')

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-20" role="alert">
    <i class="ri-checkbox-circle-line me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card shadow-1 radius-8 p-16 mb-20">
    <form method="GET" class="row g-12 align-items-end">
        <div class="col-sm-4">
            <label class="form-label text-sm fw-semibold mb-4">Term</label>
            <select name="term_id" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">— All Terms —</option>
                @foreach($terms as $term)
                    <option value="{{ $term->id }}" {{ $selectedTermId == $term->id ? 'selected' : '' }}>
                        {{ $term->name }} {{ $term->academic_year }}
                        @if($term->is_current) (Current) @endif
                    </option>
                @endforeach
            </select>
        </div>
    </form>
</div>

{{-- Legend --}}
@if($selectedTermId)
<div class="d-flex align-items-center gap-16 mb-16 text-xs" style="color:#7B79A0;">
    <span class="d-flex align-items-center gap-6">
        <span class="badge bg-success-100 text-success-600 px-8 py-4 radius-4">
            <i class="ri-global-line me-1"></i>Declared
        </span> Public — parents can view
    </span>
    <span class="d-flex align-items-center gap-6">
        <span class="badge bg-primary-100 text-primary-600 px-8 py-4 radius-4">
            <i class="ri-check-double-line me-1"></i>Complete
        </span> Ready to declare
    </span>
    <span class="d-flex align-items-center gap-6">
        <span class="badge bg-warning-100 text-warning-600 px-8 py-4 radius-4">
            <i class="ri-time-line me-1"></i>Pending
        </span> Awaiting approval
    </span>
</div>
@endif

<div class="card shadow-1 radius-8">
    <div class="card-header py-16 px-24 border-bottom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-8 mb-12">
            <h6 class="fw-semibold mb-0">{{ $formClass->name }} — Students</h6>
            <div class="d-flex align-items-center gap-12">
                @if($selectedTermId)
                @php
                    $totalDeclared = $studentSummaries->filter(fn($r) => $r['publication'])->count();
                    $totalComplete = $studentSummaries->filter(fn($r) => $r['is_complete'])->count();
                @endphp
                <span class="text-xs" style="color:#7B79A0;">
                    <span class="fw-semibold text-success-600">{{ $totalDeclared }}</span> declared &bull;
                    <span class="fw-semibold text-primary-600">{{ $totalComplete }}</span> complete &bull;
                    <span id="totalCount">{{ $studentSummaries->count() }}</span> total
                </span>
                @endif
                <select id="perPageSel" class="form-select form-select-sm" style="width:auto;">
                    <option value="10">10 / page</option>
                    <option value="20" selected>20 / page</option>
                    <option value="50">50 / page</option>
                    <option value="100">All</option>
                </select>
            </div>
        </div>
        <div class="position-relative">
            <input type="text" id="searchInput"
                   class="form-control form-control-sm ps-32"
                   placeholder="Search by name or admission number…"
                   autocomplete="off">
            <i class="ri-search-line position-absolute" style="left:10px; top:50%; transform:translateY(-50%); color:#9B9AC0; font-size:14px; pointer-events:none;"></i>
            <button type="button" id="clearSearch"
                    class="position-absolute border-0 bg-transparent p-0"
                    style="right:10px; top:50%; transform:translateY(-50%); color:#9B9AC0; display:none; cursor:pointer; font-size:14px;">
                <i class="ri-close-line"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="resultsTable">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-24 py-12 text-sm">#</th>
                        <th class="px-16 py-12 text-sm">Student</th>
                        <th class="px-16 py-12 text-sm">Admission No.</th>
                        <th class="px-16 py-12 text-sm text-center">Subjects</th>
                        <th class="px-16 py-12 text-sm text-center">Avg</th>
                        <th class="px-16 py-12 text-sm text-center">Status</th>
                        <th class="px-16 py-12 text-sm text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studentSummaries as $i => $row)
                    @php
                        $pub        = $row['publication'];
                        $isComplete = $row['is_complete'];
                    @endphp
                    <tr>
                        <td class="px-24 py-10 text-sm">{{ $i + 1 }}</td>
                        <td class="px-16 py-10 text-sm fw-medium">{{ $row['student']->full_name }}</td>
                        <td class="px-16 py-10 text-sm" style="white-space:nowrap;">{{ $row['student']->admission_number }}</td>
                        <td class="px-16 py-10 text-center">
                            @if($row['score_count'] > 0)
                                <span class="badge bg-success-100 text-success-600 px-8 py-3 radius-4 text-xs">
                                    {{ $row['score_count'] }}
                                </span>
                            @else
                                <span class="badge bg-neutral-100 text-secondary-light px-8 py-3 radius-4 text-xs">0</span>
                            @endif
                        </td>
                        <td class="px-16 py-10 text-center">
                            @if($row['average'] !== null)
                                <span class="fw-semibold text-sm {{ $row['average'] >= 50 ? 'text-success-600' : 'text-danger-600' }}">
                                    {{ $row['average'] }}
                                </span>
                            @else
                                <span class="text-secondary-light">—</span>
                            @endif
                        </td>
                        <td class="px-16 py-10 text-center">
                            @if($pub)
                                <span class="badge bg-success-100 text-success-600 px-8 py-3 radius-4 text-xs">
                                    <i class="ri-global-line me-1"></i>Declared
                                </span>
                            @elseif($isComplete)
                                <span class="badge bg-primary-100 text-primary-600 px-8 py-3 radius-4 text-xs">
                                    <i class="ri-check-double-line me-1"></i>Complete
                                </span>
                            @elseif($row['score_count'] > 0)
                                <span class="badge bg-warning-100 text-warning-600 px-8 py-3 radius-4 text-xs">
                                    <i class="ri-time-line me-1"></i>Pending
                                </span>
                            @else
                                <span class="badge bg-neutral-100 text-secondary-light px-8 py-3 radius-4 text-xs">None</span>
                            @endif
                        </td>
                        <td class="px-16 py-10 text-center" style="white-space:nowrap;">
                            <div class="d-flex align-items-center justify-content-center gap-6">

                                {{-- Download PDF --}}
                                @if($row['score_count'] > 0 && $selectedTermId)
                                <form action="{{ route('teacher.results.generate') }}" method="POST" class="m-0 d-inline">
                                    @csrf
                                    <input type="hidden" name="student_id" value="{{ $row['student']->id }}">
                                    <input type="hidden" name="term_id"    value="{{ $selectedTermId }}">
                                    <button type="submit"
                                            class="btn btn-xs btn-outline-primary d-inline-flex align-items-center gap-4"
                                            style="padding:3px 8px; font-size:11px; line-height:1.4;"
                                            title="Download PDF">
                                        <i class="ri-file-pdf-line"></i> PDF
                                    </button>
                                </form>
                                @endif

                                {{-- Declare / Update --}}
                                @if($isComplete && $selectedTermId)
                                <a href="{{ route('teacher.results.publish.form', ['student_id' => $row['student']->id, 'term_id' => $selectedTermId]) }}"
                                   class="btn btn-xs d-inline-flex align-items-center gap-4 {{ $pub ? 'btn-outline-success' : 'btn-success' }}"
                                   style="padding:3px 8px; font-size:11px; line-height:1.4;"
                                   title="{{ $pub ? 'Update Declaration' : 'Declare for Public' }}">
                                    <i class="ri-global-line"></i>{{ $pub ? 'Update' : 'Declare' }}
                                </a>
                                @endif

                                {{-- Copy link --}}
                                @if($pub)
                                <button type="button"
                                        class="btn btn-xs btn-outline-secondary d-inline-flex align-items-center"
                                        style="padding:3px 7px; font-size:11px; line-height:1.4;"
                                        onclick="copyPublicLink('{{ route('result.public.show', $pub->token) }}')"
                                        title="Copy parent link">
                                    <i class="ri-link"></i>
                                </button>
                                @endif

                                {{-- No data --}}
                                @if($row['score_count'] === 0 || !$selectedTermId)
                                <span class="text-xs text-secondary-light">
                                    {{ !$selectedTermId ? 'Select term' : 'No scores' }}
                                </span>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="7" class="text-center py-32 text-secondary-light">No students in your form class.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination footer --}}
    <div class="card-footer px-24 py-12 d-flex align-items-center justify-content-between flex-wrap gap-8" id="paginationBar">
        <span class="text-xs text-secondary-light" id="paginationInfo"></span>
        <nav>
            <ul class="pagination pagination-sm mb-0" id="paginationNav"></ul>
        </nav>
    </div>
</div>

{{-- Toast --}}
<div id="linkToast" class="position-fixed bottom-0 end-0 p-3" style="z-index:9999;">
    <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body"><i class="ri-check-line me-2"></i> Parent link copied!</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const tbody      = document.querySelector('#resultsTable tbody');
    const allRows    = Array.from(tbody.querySelectorAll('tr')).filter(r => r.id !== 'emptyRow');
    const perPageSel = document.getElementById('perPageSel');
    const navEl      = document.getElementById('paginationNav');
    const infoEl     = document.getElementById('paginationInfo');
    const barEl      = document.getElementById('paginationBar');
    const searchEl   = document.getElementById('searchInput');
    const clearBtn   = document.getElementById('clearSearch');
    const totalCount = document.getElementById('totalCount');

    let perPage   = 20;
    let curPage   = 1;
    let matched   = allRows.slice(); // rows that pass the current search

    /* ── Search ─────────────────────────────────────────── */
    function getMatched() {
        const q = searchEl.value.trim().toLowerCase();
        if (!q) return allRows.slice();
        return allRows.filter(r => {
            const name   = (r.cells[1]?.textContent || '').toLowerCase();
            const admNo  = (r.cells[2]?.textContent || '').toLowerCase();
            return name.includes(q) || admNo.includes(q);
        });
    }

    searchEl.addEventListener('input', () => {
        clearBtn.style.display = searchEl.value ? '' : 'none';
        curPage = 1;
        refresh();
    });

    clearBtn.addEventListener('click', () => {
        searchEl.value = '';
        clearBtn.style.display = 'none';
        curPage = 1;
        searchEl.focus();
        refresh();
    });

    /* ── Render ──────────────────────────────────────────── */
    function refresh() {
        matched = getMatched();
        renderPage();
    }

    function renderPage() {
        const total = matched.length;
        const pages = perPage >= total ? 1 : Math.ceil(total / perPage);
        curPage     = Math.min(curPage, pages || 1);
        const start = (curPage - 1) * perPage;
        const end   = Math.min(start + perPage, total);

        // Show/hide all rows
        allRows.forEach(r => r.style.display = 'none');
        matched.forEach((r, i) => {
            r.style.display = (i >= start && i < end) ? '' : 'none';
        });

        // Re-number visible rows
        let n = start;
        matched.forEach(r => {
            if (r.style.display !== 'none') r.cells[0].textContent = ++n;
        });

        // Info text
        if (totalCount) totalCount.textContent = matched.length < allRows.length
            ? `${matched.length} of ${allRows.length}`
            : allRows.length;

        infoEl.textContent = total === 0
            ? (searchEl.value ? 'No matching students.' : '')
            : `Showing ${start + 1}–${end} of ${total}`;

        barEl.style.display = pages <= 1 ? 'none' : '';
        renderNav(pages);
    }

    /* ── Pagination nav ──────────────────────────────────── */
    function renderNav(pages) {
        navEl.innerHTML = '';
        const nums = getPageNumbers(pages);
        appendBtn('‹', curPage > 1 ? curPage - 1 : null);
        nums.forEach(n => {
            if (n === '…') {
                const li = document.createElement('li');
                li.className = 'page-item disabled';
                li.innerHTML = '<span class="page-link px-8">…</span>';
                navEl.appendChild(li);
            } else {
                appendBtn(n, n, n === curPage);
            }
        });
        appendBtn('›', curPage < pages ? curPage + 1 : null);
    }

    function getPageNumbers(pages) {
        if (pages <= 7) return Array.from({length: pages}, (_, i) => i + 1);
        const set = new Set([1, pages, curPage, curPage - 1, curPage + 1].filter(n => n >= 1 && n <= pages));
        const sorted = [...set].sort((a, b) => a - b);
        const out = [];
        sorted.forEach((n, i) => {
            if (i > 0 && n - sorted[i - 1] > 1) out.push('…');
            out.push(n);
        });
        return out;
    }

    function appendBtn(label, targetPage, active = false) {
        const li = document.createElement('li');
        li.className = 'page-item' + (active ? ' active' : '') + (targetPage === null ? ' disabled' : '');
        const el = document.createElement(targetPage !== null ? 'button' : 'span');
        el.className = 'page-link px-8';
        el.textContent = label;
        if (targetPage !== null) {
            el.addEventListener('click', () => { curPage = targetPage; renderPage(); });
        }
        li.appendChild(el);
        navEl.appendChild(li);
    }

    perPageSel.addEventListener('change', () => {
        perPage = parseInt(perPageSel.value) || allRows.length;
        curPage = 1;
        renderPage();
    });

    if (allRows.length > 0) refresh();
    else barEl.style.display = 'none';
})();

function copyPublicLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        const toastEl = document.querySelector('#linkToast .toast');
        new bootstrap.Toast(toastEl, { delay: 2500 }).show();
    });
}
</script>
@endpush
