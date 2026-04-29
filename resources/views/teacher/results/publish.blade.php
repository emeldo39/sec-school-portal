@extends('layouts.portal')

@section('title', 'Declare Result for ' . $student->full_name)
@section('page-title', 'Declare Result for Public')
@section('page-subtitle', $student->full_name . ' — ' . $term->name . ' ' . $term->academic_year)

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-20" role="alert">
    <i class="ri-checkbox-circle-line me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('teacher.results.publish.store') }}" method="POST">
    @csrf
    <input type="hidden" name="student_id" value="{{ $student->id }}">
    <input type="hidden" name="term_id" value="{{ $term->id }}">

    <div class="row gy-20">

        {{-- ── Left column ── --}}
        <div class="col-lg-8">

            {{-- Student header --}}
            <div class="card shadow-1 radius-8 mb-20">
                <div class="card-body px-24 py-16 d-flex align-items-center gap-16">
                    @if($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}" class="rounded-circle"
                        style="width:48px;height:48px;object-fit:cover;">
                    @else
                    <div class="radius-8 d-flex align-items-center justify-content-center"
                        style="width:48px;height:48px;background:#EEF0FF;">
                        <i class="ri-user-line text-primary-600" style="font-size:22px;"></i>
                    </div>
                    @endif
                    <div>
                        <p class="fw-semibold mb-1" style="color:#2A2567;">{{ $student->full_name }}</p>
                        <p class="text-xs text-secondary-light mb-0">
                            {{ $class->name }} &bull; {{ $student->admission_number }} &bull; {{ $term->name }}
                            {{ $term->academic_year }}
                        </p>
                    </div>
                    @if($publication)
                    <span class="badge bg-success-100 text-success-600 ms-auto px-12 py-6 radius-6">
                        <i class="ri-global-line me-1"></i> Already Declared — Editing
                    </span>
                    @else
                    <span class="badge bg-warning-100 text-warning-600 ms-auto px-12 py-6 radius-6">
                        <i class="ri-eye-off-line me-1"></i> Not Yet Declared
                    </span>
                    @endif
                </div>
            </div>

            {{-- Next Term Begins --}}
            <div class="card shadow-1 radius-8 mb-20">
                <div class="card-header py-16 px-24 border-bottom">
                    <h6 class="fw-semibold mb-0"><i class="ri-calendar-line me-2 text-primary-600"></i>Next Term Begins
                    </h6>
                </div>
                <div class="card-body p-24">
                    <div class="col-sm-6">
                        <label class="form-label fw-semibold text-sm" style="color:#2A2567;">Date</label>
                        <input type="date" name="next_term_begins" class="form-control"
                            value="{{ old('next_term_begins', $publication?->next_term_begins?->format('Y-m-d')) }}">
                        <p class="text-xs mt-6" style="color:#7B79A0;">Leave blank if not yet decided.</p>
                    </div>
                </div>
            </div>

            {{-- Psychomotor Domain --}}
            <div class="card shadow-1 radius-8 mb-20">
                <div class="card-header py-16 px-24 border-bottom">
                    <h6 class="fw-semibold mb-0"><i class="ri-run-line me-2 text-primary-600"></i>Psychomotor Domain
                    </h6>
                    <p class="text-xs mt-4 mb-0" style="color:#7B79A0;">Rate each activity: 5 = Excellent → 1 = No
                        regard</p>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th class="px-24 py-10 text-sm" style="width:45%;">Activity</th>
                                @foreach([5,4,3,2,1] as $r)
                                <th class="py-10 text-center text-sm" style="width:11%;">{{ $r }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($psychomotorItems as $idx => $item)
                            @php $existingVal = old("psychomotor.{$idx}", $publication?->psychomotor[$idx] ?? null);
                            @endphp
                            <tr>
                                <td class="px-24 py-10 text-sm">{{ $idx + 1 }}. {{ $item }}</td>
                                @foreach([5,4,3,2,1] as $r)
                                <td class="py-10 text-center">
                                    <input type="radio" name="psychomotor[{{ $idx }}]" value="{{ $r }}"
                                        class="form-check-input"
                                        {{ (string)$existingVal === (string)$r ? 'checked' : '' }}>
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Affective Domain --}}
            <div class="card shadow-1 radius-8 mb-20">
                <div class="card-header py-16 px-24 border-bottom">
                    <h6 class="fw-semibold mb-0"><i class="ri-heart-pulse-line me-2 text-primary-600"></i>Affective
                        Domain</h6>
                    <p class="text-xs mt-4 mb-0" style="color:#7B79A0;">Rate each trait: 5 = Excellent → 1 = No regard
                    </p>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead class="bg-neutral-50">
                            <tr>
                                <th class="px-24 py-10 text-sm" style="width:45%;">Trait</th>
                                @foreach([5,4,3,2,1] as $r)
                                <th class="py-10 text-center text-sm" style="width:11%;">{{ $r }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($affectiveItems as $idx => $item)
                            @php $existingVal = old("affective.{$idx}", $publication?->affective[$idx] ?? null); @endphp
                            <tr>
                                <td class="px-24 py-10 text-sm">{{ $idx + 1 }}. {{ $item }}</td>
                                @foreach([5,4,3,2,1] as $r)
                                <td class="py-10 text-center">
                                    <input type="radio" name="affective[{{ $idx }}]" value="{{ $r }}"
                                        class="form-check-input"
                                        {{ (string)$existingVal === (string)$r ? 'checked' : '' }}>
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Remarks --}}
            <div class="card shadow-1 radius-8">
                <div class="card-header py-16 px-24 border-bottom">
                    <h6 class="fw-semibold mb-0"><i class="ri-edit-line me-2 text-primary-600"></i>Remarks</h6>
                </div>
                <div class="card-body p-24">
                    <div class="row gy-16">
                        <div class="col-12">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                Form Master's / Mistress's Remarks
                            </label>
                            <textarea name="form_master_remarks" rows="3"
                                class="form-control @error('form_master_remarks') is-invalid @enderror" maxlength="500"
                                placeholder="Enter remarks for this student…">{{ old('form_master_remarks', $publication?->form_master_remarks) }}</textarea>
                            @error('form_master_remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 mt-8">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                House Master's / Mistress's Remarks
                            </label>
                            <textarea name="house_master_remarks" rows="3"
                                class="form-control @error('house_master_remarks') is-invalid @enderror" maxlength="500"
                                placeholder="Enter remarks from house master…">{{ old('house_master_remarks', $publication?->house_master_remarks) }}</textarea>
                            @error('house_master_remarks')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer px-24 py-16 d-flex align-items-center gap-12">
                    <button type="submit" class="btn btn-success px-32">
                        <i class="ri-global-line me-1"></i>
                        {{ $publication ? 'Update Declaration' : 'Declare Result Public' }}
                    </button>
                    <a href="{{ route('teacher.results.index', ['term_id' => $term->id]) }}"
                        class="btn btn-outline-secondary px-24">Cancel</a>
                </div>
            </div>

        </div>{{-- end col-lg-8 --}}

        {{-- ── Right column: info + link ── --}}
        <div class="col-lg-4">

            <div class="card shadow-1 radius-8 mb-20">
                <div class="card-header py-16 px-24 border-bottom">
                    <h6 class="fw-semibold mb-0">Key Rating Guide</h6>
                </div>
                <div class="card-body p-24">
                    <ul class="list-unstyled mb-0 text-sm">
                        <li class="mb-8 d-flex gap-10 align-items-start">
                            <span class="badge bg-success-100 text-success-600 px-8 py-4 radius-4 fw-bold">5</span>
                            <span style="color:#444;">Maintaining an excellent degree of observable traits</span>
                        </li>
                        <li class="mb-8 d-flex gap-10 align-items-start">
                            <span class="badge bg-primary-100 text-primary-600 px-8 py-4 radius-4 fw-bold">4</span>
                            <span style="color:#444;">Maintaining high level of observable traits</span>
                        </li>
                        <li class="mb-8 d-flex gap-10 align-items-start">
                            <span class="badge bg-warning-100 text-warning-600 px-8 py-4 radius-4 fw-bold">3</span>
                            <span style="color:#444;">Acceptable level of observable traits</span>
                        </li>
                        <li class="mb-8 d-flex gap-10 align-items-start">
                            <span class="badge bg-orange-100 text-orange-600 px-8 py-4 radius-4 fw-bold"
                                style="color: #9e5404;">2</span>
                            <span style="color:#444;">Shows minimal regard for the observable traits</span>
                        </li>
                        <li class="d-flex gap-10 align-items-start">
                            <span class="badge bg-danger-100 text-danger-600 px-8 py-4 radius-4 fw-bold">1</span>
                            <span style="color:#444;">Has no regard for the observable traits</span>
                        </li>
                    </ul>
                </div>
            </div>

            @if($publication)
            <div class="card shadow-1 radius-8 border border-success-200">
                <div class="card-header py-16 px-24 border-bottom">
                    <h6 class="fw-semibold mb-0 text-success-600"><i class="ri-link me-2"></i>Public Link</h6>
                </div>
                <div class="card-body p-24">
                    <p class="text-xs mb-12" style="color:#7B79A0;">
                        Share this link with the parent so they can view and download the result.
                    </p>
                    <div class="d-flex gap-8">
                        <input type="text" class="form-control form-control-sm" id="publicUrl"
                            value="{{ route('result.public.show', $publication->token) }}" readonly>
                        <button type="button" class="btn btn-sm btn-outline-primary px-12" onclick="copyLink()">
                            <i class="ri-file-copy-line"></i>
                        </button>
                    </div>
                    <p class="text-xs mt-8 mb-0 text-success-600" id="copyMsg" style="display:none;">
                        <i class="ri-check-line me-1"></i> Copied!
                    </p>
                    <div class="mt-12">
                        <a href="{{ route('result.public.show', $publication->token) }}" target="_blank"
                            class="btn btn-sm btn-success w-100">
                            <i class="ri-external-link-line me-1"></i> Preview Public Result
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="card shadow-1 radius-8">
                <div class="card-body p-24 text-center">
                    <i class="ri-information-line" style="font-size:32px; color:#7B79A0;"></i>
                    <p class="text-sm mt-12 mb-0" style="color:#7B79A0;">
                        After declaring, a unique shareable link will appear here for the parent.
                    </p>
                </div>
            </div>
            @endif

        </div>{{-- end col-lg-4 --}}

    </div>{{-- end row --}}
</form>

@endsection

@push('scripts')
<script>
function copyLink() {
    const input = document.getElementById('publicUrl');
    input.select();
    navigator.clipboard.writeText(input.value).then(() => {
        const msg = document.getElementById('copyMsg');
        msg.style.display = 'block';
        setTimeout(() => msg.style.display = 'none', 2500);
    });
}
</script>
@endpush