@extends('layouts.portal')

@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('page-subtitle', $user->name)

@section('content')
<div class="row gy-24">

    {{-- ── Left: Profile Card ── --}}
    <div class="col-lg-4">
        <div class="card shadow-1 radius-8 p-24 text-center">
            @if($user->photo)
                <img src="{{ asset('storage/' . $user->photo) }}"
                     class="w-80-px h-80-px rounded-circle object-fit-cover mx-auto mb-16" alt="">
            @else
                <div class="w-80-px h-80-px rounded-circle bg-primary-100 text-primary-600 d-flex align-items-center justify-content-center mx-auto mb-16"
                     style="font-size:2rem;font-weight:700;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <h6 class="fw-semibold mb-4">{{ $user->name }}</h6>
            <p class="text-sm text-secondary-light mb-4">{{ $user->email }}</p>
            @if($user->is_form_teacher && $user->formClass)
            <span class="badge bg-primary-100 text-primary-600 px-10 py-5 radius-4 text-sm">
                Form Teacher — {{ $user->formClass->name }}
            </span>
            @endif

            <hr class="my-16">
            <ul class="list-unstyled text-start text-sm">
                <li class="d-flex justify-content-between py-6 border-bottom">
                    <span class="text-secondary-light">Phone</span>
                    <strong>{{ $user->phone ?? '—' }}</strong>
                </li>
                <li class="d-flex justify-content-between py-6 border-bottom">
                    <span class="text-secondary-light">Status</span>
                    <span class="badge bg-success-100 text-success-600 px-8 py-4">Active</span>
                </li>
                <li class="d-flex justify-content-between py-6">
                    <span class="text-secondary-light">Member Since</span>
                    <strong>{{ $user->created_at->format('M Y') }}</strong>
                </li>
            </ul>

            {{-- Signature preview (form teachers only) --}}
            @if($user->is_form_teacher && $user->signature)
            <hr class="my-16">
            <p class="text-xs text-secondary-light fw-semibold text-uppercase mb-8" style="letter-spacing:.05em;">Signature on File</p>
            <div style="border:1px dashed #BDBAD8; border-radius:8px; padding:8px; background:#FAFAFE; min-height:60px; display:flex; align-items:center; justify-content:center;">
                <img src="{{ asset('storage/' . $user->signature) }}"
                     style="max-height:56px; max-width:100%; object-fit:contain;" alt="Signature">
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-8">
        {{-- ── Update Profile ── --}}
        <div class="card shadow-1 radius-8 mb-20">
            <div class="card-header py-16 px-24 border-bottom">
                <h6 class="fw-semibold mb-0">Update Profile</h6>
            </div>
            <div class="card-body p-24">
                <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row gy-16">
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-primary-light text-sm">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="form-control @error('name') is-invalid @enderror" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-primary-light text-sm">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="form-control">
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label fw-semibold text-primary-light text-sm">Profile Photo</label>
                            <input type="file" name="photo" accept="image/*"
                                   class="form-control @error('photo') is-invalid @enderror">
                            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mt-20">
                        <button type="submit" class="btn btn-primary px-32">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Signature Upload (form teachers only) ── --}}
        @if($user->is_form_teacher)
        <div class="card shadow-1 radius-8 mb-20">
            <div class="card-header py-16 px-24 border-bottom d-flex align-items-center gap-10">
                <i class="ri-pen-nib-line text-primary-600"></i>
                <h6 class="fw-semibold mb-0">My Signature</h6>
            </div>
            <div class="card-body p-24">
                <p class="text-sm text-secondary-light mb-20">
                    As a Form Teacher, your signature will appear automatically on the
                    <strong>Form Master's Signature</strong> line of student result sheets for
                    <strong>{{ $user->formClass->name ?? 'your class' }}</strong>.
                </p>
                <div class="row gy-16 align-items-center">
                    @if($user->signature)
                    <div class="col-sm-5">
                        <p class="text-xs fw-semibold text-secondary-light text-uppercase mb-8" style="letter-spacing:.05em;">Current Signature</p>
                        <div style="border:1px dashed #BDBAD8; border-radius:8px; padding:12px; background:#FAFAFE; min-height:70px; display:flex; align-items:center; justify-content:center;">
                            <img src="{{ asset('storage/' . $user->signature) }}"
                                 style="max-height:60px; max-width:100%; object-fit:contain;" alt="Signature">
                        </div>
                    </div>
                    @endif
                    <div class="{{ $user->signature ? 'col-sm-7' : 'col-12' }}">
                        <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <input type="hidden" name="name" value="{{ $user->name }}">
                            <input type="hidden" name="phone" value="{{ $user->phone }}">
                            <label class="form-label fw-semibold text-sm" style="color:#2A2567;">
                                {{ $user->signature ? 'Replace Signature' : 'Upload Signature' }}
                            </label>
                            <p class="text-xs text-secondary-light mb-8">
                                PNG with transparent background works best. Max 1 MB.
                            </p>
                            <input type="file" name="signature" accept="image/*"
                                   class="form-control @error('signature') is-invalid @enderror" required>
                            @error('signature')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="mt-12">
                                <button type="submit" class="btn btn-outline-primary px-24">
                                    <i class="ri-upload-2-line me-1"></i>
                                    {{ $user->signature ? 'Update Signature' : 'Upload Signature' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Change Password ── --}}
        <div class="card shadow-1 radius-8">
            <div class="card-header py-16 px-24 border-bottom">
                <h6 class="fw-semibold mb-0">Change Password</h6>
            </div>
            <div class="card-body p-24">
                <form action="{{ route('teacher.password.change') }}" method="POST">
                    @csrf
                    <div class="row gy-16">
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold text-primary-light text-sm">Current Password</label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror" required>
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold text-primary-light text-sm">New Password</label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label fw-semibold text-primary-light text-sm">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <div class="mt-20">
                        <button type="submit" class="btn btn-warning px-32">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
