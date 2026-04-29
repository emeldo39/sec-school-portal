@extends('layouts.portal')

@section('title', 'Backup & Reset')
@section('page-title', 'Backup & Database Reset')
@section('page-subtitle', 'Create backups, download or delete them, and clear database records')

@section('content')

@php
function fmtSize(int $bytes): string {
if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
if ($bytes >= 1024) return round($bytes / 1024, 0) . ' KB';
return $bytes . ' B';
}
@endphp

<div class="row gy-24">

    {{-- ── STAT CARDS ─────────────────────────────────────────────────────── --}}
    <div class="col-12">
        <div class="row gy-16">

            <div class="col-sm-4">
                <div class="card shadow-1 radius-8 p-20 d-flex flex-row align-items-center gap-16 m-1">
                    <span
                        class="w-48-px h-48-px d-flex align-items-center justify-content-center radius-8 flex-shrink-0"
                        style="background:#EEF0FF;">
                        <i class="ri-archive-2-line" style="font-size:22px;color:#2A2567;"></i>
                    </span>
                    <div>
                        <p class="text-secondary-light text-xs mb-2">Total Backups</p>
                        <h5 class="fw-bold mb-0" style="color:#1e293b;font-size:1.2rem !important;">{{ $totalCount }}
                        </h5>
                        <p class="text-xs text-secondary-light mb-0">Max 12 kept</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card shadow-1 radius-8 p-20 d-flex flex-row align-items-center gap-16 m-1">
                    <span
                        class="w-48-px h-48-px d-flex align-items-center justify-content-center radius-8 flex-shrink-0"
                        style="background:#fef9ee;">
                        <i class="ri-hard-drive-2-line" style="font-size:22px;color:#d97706;"></i>
                    </span>
                    <div>
                        <p class="text-secondary-light text-xs mb-2">Total Size</p>
                        <h5 class="fw-bold mb-0" style="color:#1e293b;font-size:1.2rem !important;">
                            {{ $totalSize > 0 ? fmtSize($totalSize) : '—' }}
                        </h5>
                        <p class="text-xs text-secondary-light mb-0">Across all backups</p>
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card shadow-1 radius-8 p-20 d-flex flex-row align-items-center gap-16 m-1">
                    <span
                        class="w-48-px h-48-px d-flex align-items-center justify-content-center radius-8 flex-shrink-0"
                        style="background:#f0fdf4;">
                        <i class="ri-time-line" style="font-size:22px;color:#16a34a;"></i>
                    </span>
                    <div>
                        <p class="text-secondary-light text-xs mb-2">Last Backup</p>
                        <h5 class="fw-bold mb-0" style="color:#1e293b;font-size:1.2rem !important;">
                            {{ $latest ? \Carbon\Carbon::createFromTimestamp($latest)->format('d M Y') : '—' }}
                        </h5>
                        <p class="text-xs text-secondary-light mb-0">
                            {{ $latest ? \Carbon\Carbon::createFromTimestamp($latest)->format('H:i') : 'No backups yet' }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── LEFT COLUMN: Create + History ──────────────────────────────────── --}}
    <div class="col-xl-7 mt-40">

        {{-- ── Create Backup ──────────────────────────────────────────────── --}}
        <div class="card shadow-1 radius-8 mb-24">
            <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8"
                    style="background:#EEF0FF;">
                    <i class="ri-download-cloud-2-line" style="font-size:16px;color:#2A2567;"></i>
                </span>
                <div>
                    <h6 class="fw-semibold mb-0" style="color:#2A2567;">Create New Backup</h6>
                    <p class="text-xs text-secondary-light mb-0">Choose what to include in the backup archive</p>
                </div>
            </div>
            <div class="card-body p-24">

                @if($errors->has('error'))
                <div class="alert d-flex align-items-start gap-10 mb-20 p-14 radius-8"
                    style="background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;">
                    <i class="ri-error-warning-line mt-1" style="flex-shrink:0;"></i>
                    <span class="text-sm">{{ $errors->first('error') }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('admin.backup.store') }}" id="backupCreateForm">
                    @csrf

                    <div class="row gy-12 mb-20">
                        <div class="col-sm-4">
                            <label class="backup-type-card d-block p-16 radius-8 cursor-pointer"
                                style="border:2px solid #e2e8f0;transition:all .15s;" for="type_full">
                                <input type="radio" name="type" id="type_full" value="full" class="d-none" checked>
                                <i class="ri-database-2-line d-block mb-8" style="font-size:20px;color:#2A2567;"></i>
                                <p class="fw-semibold text-sm mb-2" style="color:#1e293b;">Full Backup</p>
                                <p class="text-xs text-secondary-light mb-0">Database + all uploaded files</p>
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <label class="backup-type-card d-block p-16 radius-8 cursor-pointer"
                                style="border:2px solid #e2e8f0;transition:all .15s;" for="type_db">
                                <input type="radio" name="type" id="type_db" value="db" class="d-none">
                                <i class="ri-table-line d-block mb-8" style="font-size:20px;color:#2A2567;"></i>
                                <p class="fw-semibold text-sm mb-2" style="color:#1e293b;">Database Only</p>
                                <p class="text-xs text-secondary-light mb-0">All tables & records (.sql)</p>
                            </label>
                        </div>
                        <div class="col-sm-4">
                            <label class="backup-type-card d-block p-16 radius-8 cursor-pointer"
                                style="border:2px solid #e2e8f0;transition:all .15s;" for="type_files">
                                <input type="radio" name="type" id="type_files" value="files" class="d-none">
                                <i class="ri-folder-image-line d-block mb-8" style="font-size:20px;color:#2A2567;"></i>
                                <p class="fw-semibold text-sm mb-2" style="color:#1e293b;">Files Only</p>
                                <p class="text-xs text-secondary-light mb-0">Uploaded media & images</p>
                            </label>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-12">
                        <button type="submit" class="btn btn-primary-600 px-20" id="createBackupBtn">
                            <i class="ri-download-cloud-2-line me-6"></i>
                            <span id="createBackupLabel">Create Backup</span>
                        </button>
                        <p class="text-xs text-secondary-light mb-0">
                            <i class="ri-information-line me-4"></i>
                            Backups over the limit of 12 are auto-deleted (oldest first).
                        </p>
                    </div>

                </form>
            </div>
        </div>

        {{-- ── Backup History ──────────────────────────────────────────────── --}}
        <div class="card shadow-1 radius-8">
            <div class="card-header py-14 px-24 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-10">
                    <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8"
                        style="background:#EEF0FF;">
                        <i class="ri-history-line" style="font-size:16px;color:#2A2567;"></i>
                    </span>
                    <div>
                        <h6 class="fw-semibold mb-0" style="color:#2A2567;">Backup History</h6>
                        <p class="text-xs text-secondary-light mb-0">{{ $totalCount }}
                            backup{{ $totalCount !== 1 ? 's' : '' }} stored on this server</p>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">
                @if($totalCount === 0)
                <div class="text-center py-40 px-24">
                    <i class="ri-inbox-2-line d-block mb-10" style="font-size:40px;color:#cbd5e1;"></i>
                    <p class="text-secondary-light text-sm mb-0">No backups yet. Create your first one above.</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0" style="font-size:.82rem;">
                        <thead style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                            <tr>
                                <th class="px-20 py-12 fw-semibold text-secondary-light">Filename</th>
                                <th class="px-12 py-12 fw-semibold text-secondary-light">Type</th>
                                <th class="px-12 py-12 fw-semibold text-secondary-light">Size</th>
                                <th class="px-12 py-12 fw-semibold text-secondary-light">Created</th>
                                <th class="px-12 py-12 fw-semibold text-secondary-light text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($backups as $backup)
                            <tr>
                                <td class="px-20 py-12" style="max-width:220px;">
                                    <span class="text-truncate d-block" style="max-width:210px;"
                                        title="{{ $backup['filename'] }}">
                                        <i class="ri-file-zip-line me-6 text-secondary-light"></i>
                                        {{ $backup['filename'] }}
                                    </span>
                                </td>
                                <td class="px-12 py-12">
                                    @php
                                    $typeClass = ['full' => 'btype-full', 'db' => 'btype-db', 'files' =>
                                    'btype-files'][$backup['type']] ?? 'btype-unknown';
                                    @endphp
                                    <span class="badge px-8 py-4 radius-6 fw-semibold text-xs {{ $typeClass }}">
                                        {{ ucfirst($backup['type']) }}
                                    </span>
                                </td>
                                <td class="px-12 py-12 text-secondary-light">{{ fmtSize($backup['size']) }}</td>
                                <td class="px-12 py-12 text-secondary-light">
                                    {{ \Carbon\Carbon::createFromTimestamp($backup['created'])->format('d M Y, H:i') }}
                                </td>
                                <td class="px-12 py-12 text-end">
                                    <div class="d-flex align-items-center justify-content-end gap-8">
                                        <a href="{{ route('admin.backup.download', $backup['filename']) }}"
                                            class="btn btn-sm px-10 py-6 radius-6 fw-semibold"
                                            style="background:#EEF0FF;color:#2A2567;font-size:.72rem;text-decoration:none;"
                                            title="Download">
                                            <i class="ri-download-2-line me-4"></i> Download
                                        </a>
                                        <form method="POST"
                                            action="{{ route('admin.backup.destroy', $backup['filename']) }}"
                                            onsubmit="return confirm('Delete this backup? This cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm px-10 py-6 radius-6 fw-semibold"
                                                style="background:#fef2f2;color:#dc2626;font-size:.72rem;"
                                                title="Delete">
                                                <i class="ri-delete-bin-6-line"></i>
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

                {{-- Off-site notice --}}
                <div class="mx-20 my-16 p-14 radius-8 d-flex align-items-start gap-10"
                    style="background:#fef9ee;border:1px solid #fde68a; padding: 15px;">
                    <i class="ri-alert-line mt-1 flex-shrink-0" style="color:#d97706;font-size:14px;"></i>
                    <p class="text-xs mb-0" style="color:#92400e;">
                        <strong>Off-site storage recommended.</strong>
                        Backups are stored on this server. For disaster recovery, download a copy and store it
                        externally (USB drive, Google Drive, email) after each backup.
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- ── RIGHT COLUMN: DB Clear ──────────────────────────────────────────── --}}
    <div class="col-xl-5 mt-40">

        {{-- ── Soft Clear ─────────────────────────────────────────────────── --}}
        <div class="card shadow-1 radius-8 mb-24" style="border-top:3px solid #f59e0b;">
            <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8"
                    style="background:#fef9ee;">
                    <i class="ri-eraser-line" style="font-size:16px;color:#d97706;"></i>
                </span>
                <div>
                    <h6 class="fw-semibold mb-0" style="color:#92400e;">Soft Clear</h6>
                    <p class="text-xs mb-0" style="color:#b45309;">New academic year preparation</p>
                </div>
            </div>
            <div class="card-body p-24">

                {{-- What is cleared / kept --}}
                <div class="row gy-12 mb-20">
                    <div class="col-6">
                        <p class="text-xs fw-semibold mb-8" style="color:#dc2626;letter-spacing:.4px;">CLEARED</p>
                        @foreach(['Scores & attendance','Results & publications','Announcements','Contact
                        messages','Activity logs'] as $item)
                        <div class="d-flex align-items-center gap-6 mb-6">
                            <i class="ri-close-circle-line" style="color:#dc2626;font-size:13px;flex-shrink:0;"></i>
                            <span class="text-xs text-secondary-light">{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="col-6">
                        <p class="text-xs fw-semibold mb-8" style="color:#16a34a;letter-spacing:.4px;">KEPT</p>
                        @foreach(['Students list','Teachers & staff','Classes & subjects','Academic terms','Grading &
                        weights','School settings','Website content'] as $item)
                        <div class="d-flex align-items-center gap-6 mb-6">
                            <i class="ri-checkbox-circle-line" style="color:#16a34a;font-size:13px;flex-shrink:0;"></i>
                            <span class="text-xs text-secondary-light">{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="p-12 radius-8 mb-20" style="background:#fef9ee;border:1px solid #fde68a;">
                    <p class="text-xs mb-0" style="color:#92400e;">
                        <i class="ri-shield-check-line me-4"></i>
                        <strong>Auto-backup runs first.</strong>
                        A database backup is created automatically before any data is removed.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.backup.clear-soft') }}" id="softClearForm">
                    @csrf

                    <div class="mb-16">
                        <label class="form-label fw-semibold text-sm" style="color:#92400e;">
                            Confirm with your password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-neutral-50">
                                <i class="ri-lock-line text-secondary-light"></i>
                            </span>
                            <input type="password" name="soft_password"
                                class="form-control @error('soft_password') is-invalid @enderror"
                                placeholder="Enter your password" autocomplete="current-password">
                            @error('soft_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn w-100 fw-semibold"
                        style="background:#f59e0b;color:#fff;border:none;"
                        onclick="return confirm('Run Soft Clear?\n\nThis will delete all scores, results, attendance records, and logs.\n\nA database backup will be created first.\n\nContinue?')">
                        <i class="ri-eraser-line me-6"></i> Run Soft Clear
                    </button>
                </form>

            </div>
        </div>

        {{-- ── Hard Clear / Full Reset ─────────────────────────────────────── --}}
        <div class="card shadow-1 radius-8" style="border-top:3px solid #dc2626;">
            <div class="card-header py-14 px-24 border-bottom d-flex align-items-center gap-10">
                <span class="w-32 h-32 d-flex align-items-center justify-content-center radius-8"
                    style="background:#fef2f2;">
                    <i class="ri-restart-line" style="font-size:16px;color:#dc2626;"></i>
                </span>
                <div>
                    <h6 class="fw-semibold mb-0" style="color:#991b1b;">Full Reset</h6>
                    <p class="text-xs mb-0" style="color:#b91c1c;">Wipes all operational data. Cannot be undone.</p>
                </div>
            </div>
            <div class="card-body p-24">

                {{-- What is cleared / kept --}}
                <div class="row gy-12 mb-20">
                    <div class="col-6">
                        <p class="text-xs fw-semibold mb-8" style="color:#dc2626;letter-spacing:.4px;">CLEARED</p>
                        @foreach(['All scores & results','All students','All teachers','Teacher assignments','Academic
                        terms','Gallery & news','Hero slides','All website content'] as $item)
                        <div class="d-flex align-items-center gap-6 mb-6">
                            <i class="ri-close-circle-line" style="color:#dc2626;font-size:13px;flex-shrink:0;"></i>
                            <span class="text-xs text-secondary-light">{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                    <div class="col-6">
                        <p class="text-xs fw-semibold mb-8" style="color:#16a34a;letter-spacing:.4px;">KEPT / RESET</p>
                        @foreach(['Admin & principal accounts','School settings','Grading scales (re-seeded)','Score
                        weights (re-seeded)','Classes (re-seeded)','Subjects (re-seeded)'] as $item)
                        <div class="d-flex align-items-center gap-6 mb-6">
                            <i class="ri-checkbox-circle-line" style="color:#16a34a;font-size:13px;flex-shrink:0;"></i>
                            <span class="text-xs text-secondary-light">{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="p-12 radius-8 mb-20" style="background:#fef2f2;border:1px solid #fecaca;">
                    <p class="text-xs mb-0" style="color:#991b1b;">
                        <i class="ri-error-warning-line me-4"></i>
                        <strong>Irreversible.</strong>
                        All students, teachers, and academic records will be permanently deleted.
                        Auto-backup runs before the wipe begins.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.backup.clear-hard') }}" id="hardClearForm">
                    @csrf

                    <div class="mb-16">
                        <label class="form-label fw-semibold text-sm" style="color:#991b1b;">
                            Your password <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-neutral-50">
                                <i class="ri-lock-line text-secondary-light"></i>
                            </span>
                            <input type="password" name="hard_password"
                                class="form-control @error('hard_password') is-invalid @enderror"
                                placeholder="Enter your password" autocomplete="current-password">
                            @error('hard_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-20">
                        <label class="form-label fw-semibold text-sm" style="color:#991b1b;">
                            Type <code
                                style="background:#fef2f2;padding:2px 6px;border-radius:4px;color:#dc2626;">RESET</code>
                            to confirm <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="hard_confirm_text"
                            class="form-control @error('hard_confirm_text') is-invalid @enderror"
                            placeholder="Type RESET here" autocomplete="off" spellcheck="false">
                        @error('hard_confirm_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn w-100 fw-semibold"
                        style="background:#dc2626;color:#fff;border:none;"
                        onclick="return confirm('⚠ FULL RESET\n\nThis will permanently delete ALL students, teachers, scores, results, and website content.\n\nThis action cannot be undone.\n\nA backup will be created first.\n\nAre you absolutely sure?')">
                        <i class="ri-restart-line me-6"></i> Run Full Reset
                    </button>
                </form>

            </div>
        </div>

    </div>

</div>

@endsection

@push('styles')
<style>
.backup-type-card:hover {
    border-color: #9997c5 !important;
    background: #f8f9ff;
}

.backup-type-card.selected {
    border-color: #2A2567 !important;
    background: #EEF0FF;
}

.w-48-px {
    width: 48px;
}

.h-48-px {
    height: 48px;
}

.cursor-pointer {
    cursor: pointer;
}

.btype-full {
    background: #EEF0FF;
    color: #2A2567;
}

.btype-db {
    background: #fef9ee;
    color: #d97706;
}

.btype-files {
    background: #f0fdf4;
    color: #16a34a;
}

.btype-unknown {
    background: #f1f5f9;
    color: #64748b;
}
</style>
@endpush

@push('scripts')
<script>
// Backup type card visual selection
function syncCards() {
    document.querySelectorAll('.backup-type-card').forEach(function(card) {
        var radio = card.querySelector('input[type="radio"]');
        card.classList.toggle('selected', !!(radio && radio.checked));
    });
}

document.querySelectorAll('.backup-type-card input[type="radio"]').forEach(function(radio) {
    radio.addEventListener('change', syncCards);
});

syncCards();

// Disable create button while submitting
document.getElementById('backupCreateForm').addEventListener('submit', function() {
    var btn = document.getElementById('createBackupBtn');
    var label = document.getElementById('createBackupLabel');
    btn.disabled = true;
    label.textContent = 'Creating…';
});
</script>
@endpush