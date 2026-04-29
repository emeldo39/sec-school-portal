<div class="card shadow-1 radius-8">
    <div class="card-header py-16 px-24 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h6 class="fw-semibold mb-0">{{ $panelTitle }}</h6>
            <p class="text-xs text-secondary-light mb-0">
                @if($panelLevel === 'JSS')
                    A, B, C, D, E, F &mdash; used on all Junior result sheets
                @else
                    A1, B2 … F9 &mdash; used on all Senior result sheets
                @endif
            </p>
        </div>
        <button class="btn btn-sm btn-primary px-10 py-5"
                data-bs-toggle="modal" data-bs-target="#{{ $addModalId }}">
            <i class="ri-add-line"></i>
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-sm mb-0">
                <thead class="bg-neutral-50">
                    <tr>
                        <th class="px-16 py-10 text-sm">Grade</th>
                        <th class="px-12 py-10 text-sm">Range</th>
                        <th class="px-12 py-10 text-sm">Remark</th>
                        <th class="px-12 py-10 text-sm"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scales as $scale)
                    <tr>
                        <td class="px-16 py-8 text-sm fw-bold">
                            <span class="badge {{ $panelBadge }} px-8 py-4 radius-4">{{ $scale->grade }}</span>
                        </td>
                        <td class="px-12 py-8 text-sm">{{ $scale->min_score }}–{{ $scale->max_score }}</td>
                        <td class="px-12 py-8 text-sm">{{ $scale->remark }}</td>
                        <td class="px-12 py-8 text-end">
                            <button class="btn btn-sm btn-outline-primary px-6 py-3"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editGrade{{ $scale->id }}"
                                    title="Edit">
                                <i class="ri-pencil-line" style="font-size:.75rem;"></i>
                            </button>
                            <form action="{{ route('admin.grading-scales.destroy', $scale) }}"
                                  method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger px-6 py-3"
                                        onclick="return confirm('Delete grade {{ $scale->grade }} ({{ $scale->level }})?')">
                                    <i class="ri-delete-bin-line" style="font-size:.75rem;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- Edit modal --}}
                    <div class="modal fade" id="editGrade{{ $scale->id }}" tabindex="-1">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title">Edit {{ $scale->level }} Grade &mdash; {{ $scale->grade }}</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('admin.grading-scales.update', $scale) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body row gy-10">
                                        <div class="col-12">
                                            <label class="form-label text-sm fw-semibold">Grade Label</label>
                                            <input type="text" name="grade" value="{{ $scale->grade }}"
                                                   class="form-control form-control-sm" required maxlength="5">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label text-sm fw-semibold">Min Score</label>
                                            <input type="number" name="min_score" value="{{ $scale->min_score }}"
                                                   class="form-control form-control-sm" required min="0" max="100">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label text-sm fw-semibold">Max Score</label>
                                            <input type="number" name="max_score" value="{{ $scale->max_score }}"
                                                   class="form-control form-control-sm" required min="0" max="100">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label text-sm fw-semibold">Remark</label>
                                            <input type="text" name="remark" value="{{ $scale->remark }}"
                                                   class="form-control form-control-sm" required>
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
                    @empty
                    <tr>
                        <td colspan="4" class="px-16 py-12 text-sm text-secondary-light text-center">
                            No grades defined yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
