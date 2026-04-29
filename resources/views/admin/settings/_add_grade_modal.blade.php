<div class="modal fade" id="{{ $modalId }}" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">{{ $modalTitle }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.grading-scales.store') }}" method="POST">
                @csrf
                <input type="hidden" name="level" value="{{ $level }}">
                <div class="modal-body row gy-10">
                    <div class="col-12">
                        <label class="form-label text-sm fw-semibold">
                            Grade Label
                            <span class="text-secondary-light fw-normal">
                                (e.g. {{ $level === 'JSS' ? 'A, B, C…' : 'A1, B2, C4…' }})
                            </span>
                        </label>
                        <input type="text" name="grade" class="form-control form-control-sm"
                               required maxlength="5" placeholder="{{ $level === 'JSS' ? 'A' : 'A1' }}">
                    </div>
                    <div class="col-6">
                        <label class="form-label text-sm fw-semibold">Min Score</label>
                        <input type="number" name="min_score" class="form-control form-control-sm"
                               required min="0" max="100">
                    </div>
                    <div class="col-6">
                        <label class="form-label text-sm fw-semibold">Max Score</label>
                        <input type="number" name="max_score" class="form-control form-control-sm"
                               required min="0" max="100">
                    </div>
                    <div class="col-12">
                        <label class="form-label text-sm fw-semibold">Remark</label>
                        <input type="text" name="remark" class="form-control form-control-sm"
                               required placeholder="{{ $level === 'JSS' ? 'Excellent' : 'Excellent' }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Add Grade</button>
                </div>
            </form>
        </div>
    </div>
</div>
