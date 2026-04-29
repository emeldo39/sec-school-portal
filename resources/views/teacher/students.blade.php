@extends('layouts.portal')

@section('title', 'My Students')
@section('page-title', 'My Students')
@section('page-subtitle', 'Students in classes you teach')

@section('content')

{{-- ── Stat Cards ── --}}
<div class="row gy-4 mb-24">
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-1 radius-8 p-20 h-100 d-flex flex-row align-items-center gap-16">
            <div class="w-52-px h-52-px radius-8 bg-info-100 d-flex align-items-center justify-content-center flex-shrink-0">
                <iconify-icon icon="ph:student" class="text-info-600 text-2xl"></iconify-icon>
            </div>
            <div>
                <p class="text-secondary-light text-xs mb-4">Total Students</p>
                <h5 class="fw-semibold mb-0">{{ $stats['total_students'] }}</h5>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-1 radius-8 p-20 h-100 d-flex flex-row align-items-center gap-16">
            <div class="w-52-px h-52-px radius-8 bg-primary-100 d-flex align-items-center justify-content-center flex-shrink-0">
                <iconify-icon icon="ph:chalkboard" class="text-primary-600 text-2xl"></iconify-icon>
            </div>
            <div>
                <p class="text-secondary-light text-xs mb-4">Classes Assigned</p>
                <h5 class="fw-semibold mb-0">{{ $stats['classes'] }}</h5>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-1 radius-8 p-20 h-100 d-flex flex-row align-items-center gap-16">
            <div class="w-52-px h-52-px radius-8 bg-success-100 d-flex align-items-center justify-content-center flex-shrink-0">
                <iconify-icon icon="ph:book-open" class="text-success-600 text-2xl"></iconify-icon>
            </div>
            <div>
                <p class="text-secondary-light text-xs mb-4">Subjects Assigned</p>
                <h5 class="fw-semibold mb-0">{{ $stats['subjects'] }}</h5>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-1 radius-8 p-20 h-100 d-flex flex-column">
            {{-- Header --}}
            <div class="d-flex align-items-center justify-content-between mb-12 flex-shrink-0">
                <p class="text-secondary-light text-xs fw-semibold text-uppercase mb-0">Students per Class</p>
                <span class="badge bg-neutral-100 text-neutral-600 px-8 py-3 radius-4 text-xs">
                    {{ $assignedClasses->count() }} classes
                </span>
            </div>
            {{-- Scrollable list --}}
            <div style="overflow-y:auto; max-height:96px; flex:1;">
                @forelse($assignedClasses as $cls)
                @php
                    $count   = $perClass[$cls->id] ?? 0;
                    $isJss   = str_starts_with($cls->name, 'JSS');
                    $barColor = $isJss ? '#3b82f6' : '#f59e0b';
                    $pct     = $count > 0 ? min(100, round(($count / max($perClass->max(), 1)) * 100)) : 0;
                @endphp
                <div class="mb-8">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-xs fw-semibold" style="color:#2A2567;">{{ $cls->name }}</span>
                        <span class="text-xs fw-semibold text-secondary-light">{{ $count }}</span>
                    </div>
                    <div style="height:4px; background:#f0f0f0; border-radius:2px; overflow:hidden;">
                        <div style="height:100%; width:{{ $pct }}%; background:{{ $barColor }}; border-radius:2px; transition:width .3s;"></div>
                    </div>
                </div>
                @empty
                <p class="text-xs text-secondary-light mb-0">No classes assigned.</p>
                @endforelse
            </div>
            {{-- Footer total --}}
            <div class="border-top pt-10 mt-8 flex-shrink-0 d-flex justify-content-between align-items-center">
                <span class="text-xs text-secondary-light">Total</span>
                <span class="fw-semibold text-sm" style="color:#2A2567;">{{ $assignedClasses->sum(fn($c) => $perClass[$c->id] ?? 0) }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ── Subjects Assigned Badges ── --}}
@if($assignedSubjects->isNotEmpty())
<div class="card shadow-1 radius-8 mb-20">
    <div class="card-body px-24 py-14 d-flex align-items-center gap-12 flex-wrap">
        <span class="text-xs fw-semibold text-secondary-light text-uppercase me-4">Subjects I Teach:</span>
        @foreach($assignedSubjects as $subj)
        <span class="badge bg-success-100 text-success-600 px-10 py-4 radius-4 text-xs">{{ $subj->name }}</span>
        @endforeach
    </div>
</div>
@endif

{{-- ── Table Card ── --}}
<div class="card shadow-1 radius-8">
    {{-- Toolbar --}}
    <div class="card-header py-16 px-24 border-bottom d-flex flex-wrap align-items-center gap-12">
        <h6 class="fw-semibold mb-0 me-auto">Student List</h6>

        {{-- Search --}}
        <div class="input-group" style="width:220px;">
            <span class="input-group-text bg-base border-end-0 pe-0">
                <iconify-icon icon="ph:magnifying-glass" class="text-secondary-light"></iconify-icon>
            </span>
            <input type="text" id="searchInput" class="form-control border-start-0 ps-0 text-sm"
                   placeholder="Search student…">
        </div>

        {{-- Class filter --}}
        <select id="classFilter" class="form-select form-select-sm" style="width:150px;">
            <option value="">All Classes</option>
            @foreach($assignedClasses as $cls)
            <option value="{{ $cls->name }}">{{ $cls->name }}</option>
            @endforeach
        </select>

        {{-- Gender filter --}}
        <select id="genderFilter" class="form-select form-select-sm" style="width:120px;">
            <option value="">All Genders</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>

        {{-- Subject filter (shows which class teaches which subject) --}}
        <select id="subjectFilter" class="form-select form-select-sm" style="width:170px;">
            <option value="">All Subjects</option>
            @foreach($assignedSubjects as $subj)
            <option value="{{ $subj->id }}">{{ $subj->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="studentsTable">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-24 py-14 text-sm">#</th>
                        <th class="px-16 py-14 text-sm">Admission No.</th>
                        <th class="px-16 py-14 text-sm">Name</th>
                        <th class="px-16 py-14 text-sm">Class</th>
                        <th class="px-16 py-14 text-sm">Gender</th>
                        <th class="px-16 py-14 text-sm">Guardian Phone</th>
                        <th class="px-16 py-14 text-sm">Subjects I Teach</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $i => $student)
                    @php
                        // Subjects this teacher teaches specifically to this student's class
                        $mySubjectsInClass = $assignments
                            ->where('class_id', $student->class_id)
                            ->map->subject->filter()->unique('id');
                    @endphp
                    <tr data-subject-ids="{{ $mySubjectsInClass->pluck('id')->implode(',') }}">
                        <td class="px-24 py-12 text-sm">{{ $i + 1 }}</td>
                        <td class="px-16 py-12 text-sm fw-medium">{{ $student->admission_number }}</td>
                        <td class="px-16 py-12">
                            <div class="d-flex align-items-center gap-10">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}"
                                         class="w-32-px h-32-px rounded-circle object-fit-cover" alt="">
                                @else
                                    <span class="w-32-px h-32-px rounded-circle bg-primary-100 text-primary-600
                                                 d-flex align-items-center justify-content-center fw-bold text-xs">
                                        {{ strtoupper(substr($student->first_name, 0, 1)) }}
                                    </span>
                                @endif
                                <span class="text-sm">{{ $student->full_name }}</span>
                            </div>
                        </td>
                        <td class="px-16 py-12 text-sm">{{ $student->schoolClass->name ?? '—' }}</td>
                        <td class="px-16 py-12 text-sm text-capitalize">{{ $student->gender }}</td>
                        <td class="px-16 py-12 text-sm">{{ $student->guardian_phone }}</td>
                        <td class="px-16 py-12">
                            <div class="d-flex flex-wrap gap-4">
                                @foreach($mySubjectsInClass as $subj)
                                <span class="badge bg-success-100 text-success-600 px-8 py-2 radius-4 text-xs">
                                    {{ $subj->name }}
                                </span>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Footer: count + pagination --}}
        <div class="px-24 py-12 border-top d-flex align-items-center justify-content-between flex-wrap gap-12">
            <span class="text-xs text-secondary-light">
                Showing <span class="fw-semibold text-dark" id="rangeStart">1</span>–<span class="fw-semibold text-dark" id="rangeEnd">1</span>
                of <span class="fw-semibold text-dark" id="filteredCount">{{ $students->count() }}</span> students
            </span>

            <div class="d-flex align-items-center gap-8">
                {{-- Per-page selector --}}
                <select id="perPageSelect" class="form-select form-select-sm" style="width:90px;">
                    <option value="10">10 / pg</option>
                    <option value="20" selected>20 / pg</option>
                    <option value="50">50 / pg</option>
                    <option value="100">100 / pg</option>
                </select>

                {{-- Pagination controls --}}
                <nav id="paginationNav" aria-label="Student pagination">
                    <ul class="pagination pagination-sm mb-0 gap-4" id="paginationList"></ul>
                </nav>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
  #paginationList .page-item .page-link {
    min-width: 32px; height: 32px; line-height: 1;
    display: flex; align-items: center; justify-content: center;
    border-radius: 6px !important; font-size: .75rem; font-weight: 600;
    border: 1px solid #e5e7eb; color: #6b7280; background: #fff;
    padding: 0 8px;
  }
  #paginationList .page-item.active .page-link {
    background: #2A2567; border-color: #2A2567; color: #fff;
  }
  #paginationList .page-item.disabled .page-link { opacity: .45; pointer-events: none; }
  #paginationList .page-item .page-link:hover:not(.active-link) { background: #f3f4f6; }
</style>
<script>
(function () {
    const searchInput   = document.getElementById('searchInput');
    const classFilter   = document.getElementById('classFilter');
    const genderFilter  = document.getElementById('genderFilter');
    const subjectFilter = document.getElementById('subjectFilter');
    const perPageSelect = document.getElementById('perPageSelect');
    const tbody         = document.querySelector('#studentsTable tbody');
    const allRows       = Array.from(tbody.querySelectorAll('tr'));

    // Footer elements
    const rangeStart    = document.getElementById('rangeStart');
    const rangeEnd      = document.getElementById('rangeEnd');
    const filteredCount = document.getElementById('filteredCount');
    const paginationList= document.getElementById('paginationList');

    let currentPage   = 1;
    let matchedRows   = [];

    // ── Filter logic ──────────────────────────────────────────
    function getMatchedRows() {
        const search  = searchInput.value.toLowerCase().trim();
        const cls     = classFilter.value.toLowerCase();
        const gender  = genderFilter.value.toLowerCase();
        const subject = subjectFilter.value;

        return allRows.filter(row => {
            const cells      = row.querySelectorAll('td');
            const admNo      = (cells[1]?.textContent ?? '').toLowerCase();
            const name       = (cells[2]?.textContent ?? '').toLowerCase();
            const rowClass   = (cells[3]?.textContent ?? '').toLowerCase().trim();
            const rowGender  = (cells[4]?.textContent ?? '').toLowerCase().trim();
            const subjectIds = (row.dataset.subjectIds ?? '').split(',');

            return (!search  || admNo.includes(search) || name.includes(search))
                && (!cls     || rowClass === cls)
                && (!gender  || rowGender === gender)
                && (!subject || subjectIds.includes(subject));
        });
    }

    // ── Pagination renderer ───────────────────────────────────
    function renderPage() {
        const perPage  = parseInt(perPageSelect.value);
        const total    = matchedRows.length;
        const maxPage  = Math.max(1, Math.ceil(total / perPage));
        if (currentPage > maxPage) currentPage = maxPage;

        const start = (currentPage - 1) * perPage;
        const end   = Math.min(start + perPage, total);

        // Show / hide rows
        allRows.forEach(r => r.style.display = 'none');
        matchedRows.forEach((r, i) => {
            r.style.display = (i >= start && i < end) ? '' : 'none';
        });

        // Re-number visible rows
        matchedRows.slice(start, end).forEach((r, i) => {
            const numCell = r.querySelector('td:first-child');
            if (numCell) numCell.textContent = start + i + 1;
        });

        // Footer info
        rangeStart.textContent  = total === 0 ? 0 : start + 1;
        rangeEnd.textContent    = end;
        filteredCount.textContent = total;

        // Build pagination controls
        renderPagination(maxPage);
    }

    function renderPagination(maxPage) {
        paginationList.innerHTML = '';

        // Prev
        appendBtn('&lsaquo;', currentPage - 1, currentPage === 1, 'Prev');

        // Page numbers with ellipsis
        const pages = getPageNumbers(currentPage, maxPage);
        pages.forEach(p => {
            if (p === '…') {
                const li = document.createElement('li');
                li.className = 'page-item disabled';
                li.innerHTML = `<span class="page-link">…</span>`;
                paginationList.appendChild(li);
            } else {
                appendBtn(p, p, false, null, p === currentPage);
            }
        });

        // Next
        appendBtn('&rsaquo;', currentPage + 1, currentPage === maxPage, 'Next');
    }

    function getPageNumbers(current, max) {
        if (max <= 7) return Array.from({length: max}, (_, i) => i + 1);
        const pages = [1];
        if (current > 3) pages.push('…');
        for (let p = Math.max(2, current - 1); p <= Math.min(max - 1, current + 1); p++) pages.push(p);
        if (current < max - 2) pages.push('…');
        pages.push(max);
        return pages;
    }

    function appendBtn(label, page, disabled, ariaLabel, active = false) {
        const li  = document.createElement('li');
        li.className = 'page-item' + (disabled ? ' disabled' : '') + (active ? ' active' : '');
        const a   = document.createElement('a');
        a.className = 'page-link';
        a.innerHTML = label;
        if (ariaLabel) a.setAttribute('aria-label', ariaLabel);
        if (!disabled && !active) {
            a.href = '#';
            a.addEventListener('click', e => {
                e.preventDefault();
                currentPage = page;
                renderPage();
            });
        }
        li.appendChild(a);
        paginationList.appendChild(li);
    }

    // ── Trigger on filter changes ─────────────────────────────
    function applyFilters() {
        currentPage = 1;
        matchedRows = getMatchedRows();
        renderPage();
    }

    searchInput.addEventListener('input',  applyFilters);
    classFilter.addEventListener('change', applyFilters);
    genderFilter.addEventListener('change',applyFilters);
    subjectFilter.addEventListener('change',applyFilters);
    perPageSelect.addEventListener('change', () => { currentPage = 1; renderPage(); });

    // Init
    applyFilters();
})();
</script>
@endpush
