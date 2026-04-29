@extends('layouts.portal')

@section('title', "Principal's Remarks")
@section('page-title', "Principal's Remarks")
@section('page-subtitle', 'Add your remarks to declared student results')

@section('content')

{{-- Filters --}}
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
        <div class="col-sm-5">
            <label class="form-label text-sm fw-semibold mb-4">Search Student</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="ri-search-line"></i></span>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                       class="form-control" placeholder="Name or admission number…">
                @if($search)
                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                   class="input-group-text text-danger" title="Clear search">
                    <i class="ri-close-line"></i>
                </a>
                @endif
            </div>
        </div>
        <div class="col-sm-3">
            <button type="submit" class="btn btn-primary btn-sm w-100">
                <i class="ri-filter-line me-1"></i> Filter
            </button>
        </div>
    </form>
</div>

{{-- Stats --}}
@if($publications->total() > 0)
<div class="row gy-16 mb-20">
    @php
        $withRemarks    = $publications->filter(fn($p) => $p->principal_remarks)->count();
        $withoutRemarks = $publications->filter(fn($p) => !$p->principal_remarks)->count();
    @endphp
    <div class="col-sm-4">
        <div class="card shadow-1 radius-8 p-20 text-center">
            <p class="text-2xl fw-bold mb-4" style="color:#2A2567;">{{ $publications->total() }}</p>
            <p class="text-xs text-secondary-light mb-0">Total Declared</p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card shadow-1 radius-8 p-20 text-center">
            <p class="text-2xl fw-bold mb-4 text-success-600">{{ $withRemarks }}</p>
            <p class="text-xs text-secondary-light mb-0">Remarks Added</p>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card shadow-1 radius-8 p-20 text-center">
            <p class="text-2xl fw-bold mb-4 text-warning-600">{{ $withoutRemarks }}</p>
            <p class="text-xs text-secondary-light mb-0">Awaiting Remarks</p>
        </div>
    </div>
</div>
@endif

{{-- Publications list --}}
<div class="card shadow-1 radius-8">
    <div class="card-header py-16 px-24 border-bottom">
        <h6 class="fw-semibold mb-0">Declared Results</h6>
    </div>
    <div class="card-body p-0">
        @forelse($publications as $pub)
        @php $student = $pub->student; $class = $student->schoolClass; @endphp
        <div class="border-bottom px-24 py-20 {{ $loop->last ? 'border-0' : '' }}">
            <div class="row align-items-start gy-12">

                {{-- Student info --}}
                <div class="col-lg-4 d-flex align-items-center gap-12">
                    @if($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}"
                         class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                    @else
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                         style="width:40px;height:40px;background:#EEF0FF;flex-shrink:0;">
                        <i class="ri-user-line text-primary-600" style="font-size:18px;"></i>
                    </div>
                    @endif
                    <div>
                        <p class="fw-semibold text-sm mb-1" style="color:#2A2567;">{{ $student->full_name }}</p>
                        <p class="text-xs text-secondary-light mb-1">
                            {{ $class?->name }} &bull; {{ $student->admission_number }}
                        </p>
                        <p class="text-xs text-secondary-light mb-0">
                            {{ $pub->term?->name }} {{ $pub->term?->academic_year }}
                        </p>
                        <div class="d-flex gap-8 mt-6 flex-wrap">
                            @if($pub->principal_remarks)
                            <span class="badge bg-success-100 text-success-600 px-8 py-4 radius-4 text-xs">
                                <i class="ri-check-line me-1"></i>Remarks Added
                            </span>
                            @else
                            <span class="badge bg-warning-100 text-warning-600 px-8 py-4 radius-4 text-xs">
                                <i class="ri-time-line me-1"></i>Awaiting Remarks
                            </span>
                            @endif
                            <button type="button"
                                class="badge bg-info-100 text-info-600 px-8 py-4 radius-4 text-xs border-0"
                                style="cursor:pointer;"
                                data-bs-toggle="modal"
                                data-bs-target="#detailsModal{{ $pub->id }}">
                                <i class="ri-eye-line me-1"></i>View Details
                            </button>
                            <a href="{{ route('result.public.show', $pub->token) }}"
                               target="_blank"
                               class="badge bg-primary-100 text-primary-600 px-8 py-4 radius-4 text-xs text-decoration-none">
                                <i class="ri-external-link-line me-1"></i>Public Link
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Remarks form --}}
                <div class="col-lg-8">
                    <form action="{{ route('admin.publications.remarks', $pub) }}" method="POST"
                          class="d-flex gap-12 align-items-end">
                        @csrf
                        <div class="flex-1" style="flex:1;">
                            <label class="form-label fw-semibold text-xs mb-4" style="color:#2A2567;">
                                Principal's Remarks
                            </label>
                            <textarea name="principal_remarks"
                                      rows="2"
                                      class="form-control form-control-sm"
                                      maxlength="500"
                                      placeholder="Enter your remarks for this student…">{{ old('principal_remarks_' . $pub->id, $pub->principal_remarks) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary px-16" style="white-space:nowrap;">
                            <i class="ri-save-line me-1"></i> Save
                        </button>
                    </form>
                </div>

            </div>
        </div>
        @empty
        <div class="text-center py-48">
            <i class="ri-inbox-line" style="font-size:40px; color:#c5c5d9;"></i>
            <p class="text-sm text-secondary-light mt-12 mb-0">
                {{ $search ? 'No results match your search.' : ($selectedTermId ? 'No results declared yet for this term.' : 'Select a term to view declared results.') }}
            </p>
        </div>
        @endforelse
    </div>
    @if($publications->hasPages())
    <div class="card-footer px-24 py-12">
        {{ $publications->links() }}
    </div>
    @endif
</div>

@endsection

{{-- ── Per-publication detail modals ── --}}
@foreach($publications as $pub)
@php $student = $pub->student; $class = $student->schoolClass; @endphp
<div class="modal fade" id="detailsModal{{ $pub->id }}" tabindex="-1" aria-labelledby="detailsModalLabel{{ $pub->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header" style="background:#2A2567;">
                <div>
                    <h6 class="modal-title fw-semibold text-white mb-1" id="detailsModalLabel{{ $pub->id }}">
                        {{ $student->full_name }}
                    </h6>
                    <p class="text-xs mb-0" style="color:#c5c3e8;">
                        {{ $class?->name }} &bull; {{ $student->admission_number }} &bull;
                        {{ $pub->term?->name }} {{ $pub->term?->academic_year }}
                    </p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-24">

                {{-- Next Term / Declared --}}
                <div class="row gy-12 mb-20">
                    <div class="col-sm-6">
                        <p class="text-xs fw-semibold text-secondary-light mb-4">NEXT TERM BEGINS</p>
                        <p class="text-sm fw-semibold mb-0" style="color:#2A2567;">
                            {{ $pub->next_term_begins?->format('d M Y') ?? '—' }}
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-xs fw-semibold text-secondary-light mb-4">DECLARED ON</p>
                        <p class="text-sm fw-semibold mb-0" style="color:#2A2567;">
                            {{ $pub->published_at?->format('d M Y, h:i A') ?? '—' }}
                            <span class="text-secondary-light fw-normal">by {{ $pub->publisher?->name ?? '—' }}</span>
                        </p>
                    </div>
                </div>

                {{-- Psychomotor domain --}}
                <p class="text-xs fw-semibold text-secondary-light mb-8">PSYCHOMOTOR DOMAIN</p>
                <div class="table-responsive mb-20">
                    <table class="table table-sm table-bordered mb-0" style="font-size:12px;">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th style="width:55%;">Activity</th>
                                @foreach([5,4,3,2,1] as $r)
                                <th class="text-center">{{ $r }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($psychomotorItems as $i => $item)
                            @php $pVal = $pub->psychomotor[$i] ?? null; @endphp
                            <tr>
                                <td>{{ $i+1 }}. {{ $item }}</td>
                                @foreach([5,4,3,2,1] as $r)
                                <td class="text-center">
                                    @if((string)$pVal === (string)$r)
                                    <i class="ri-check-line text-success-600 fw-bold"></i>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Affective domain --}}
                <p class="text-xs fw-semibold text-secondary-light mb-8">AFFECTIVE DOMAIN</p>
                <div class="table-responsive mb-20">
                    <table class="table table-sm table-bordered mb-0" style="font-size:12px;">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th style="width:55%;">Trait</th>
                                @foreach([5,4,3,2,1] as $r)
                                <th class="text-center">{{ $r }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($affectiveItems as $i => $item)
                            @php $aVal = $pub->affective[$i] ?? null; @endphp
                            <tr>
                                <td>{{ $i+1 }}. {{ $item }}</td>
                                @foreach([5,4,3,2,1] as $r)
                                <td class="text-center">
                                    @if((string)$aVal === (string)$r)
                                    <i class="ri-check-line text-success-600 fw-bold"></i>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Remarks --}}
                <p class="text-xs fw-semibold text-secondary-light mb-8">REMARKS</p>
                <div class="row gy-12">
                    <div class="col-sm-6">
                        <p class="text-xs text-secondary-light mb-4">Form Master's / Mistress's</p>
                        <p class="text-sm mb-0" style="color:#2A2567;">
                            {{ $pub->form_master_remarks ?: '—' }}
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-xs text-secondary-light mb-4">House Master's / Mistress's</p>
                        <p class="text-sm mb-0" style="color:#2A2567;">
                            {{ $pub->house_master_remarks ?: '—' }}
                        </p>
                    </div>
                    @if($pub->principal_remarks)
                    <div class="col-12">
                        <p class="text-xs text-secondary-light mb-4">Principal's</p>
                        <p class="text-sm mb-0" style="color:#2A2567;">{{ $pub->principal_remarks }}</p>
                    </div>
                    @endif
                </div>

            </div>

            <div class="modal-footer">
                <a href="{{ route('result.public.show', $pub->token) }}" target="_blank"
                   class="btn btn-sm btn-primary px-16">
                    <i class="ri-external-link-line me-1"></i> Open Public Result
                </a>
                <button type="button" class="btn btn-sm btn-outline-secondary px-16" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
@endforeach
