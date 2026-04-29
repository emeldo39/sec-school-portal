@extends('layouts.portal')

@section('title', 'Announcements')
@section('page-title', 'Announcements')
@section('page-subtitle', 'School announcements for you')

@section('content')
<div class="card shadow-1 radius-8">
    <div class="card-body p-0">
        @forelse($announcements as $ann)
        <div class="d-flex align-items-start gap-16 p-20 border-bottom">
            <div class="w-44-px h-44-px radius-8 bg-primary-100 d-flex align-items-center justify-content-center flex-shrink-0">
                <iconify-icon icon="ph:megaphone" class="text-primary-600 text-xl"></iconify-icon>
            </div>
            <div>
                <h6 class="fw-semibold mb-6 text-sm">{{ $ann->title }}</h6>
                <p class="text-sm text-secondary-light mb-8">{{ $ann->body }}</p>
                <div class="d-flex flex-wrap gap-8">
                    <span class="badge bg-neutral-100 text-secondary-light px-8 py-4 radius-4 text-xs">
                        <i class="ri-user-3-line me-1"></i>{{ $ann->postedBy->name ?? 'Admin' }}
                    </span>
                    <span class="badge bg-neutral-100 text-secondary-light px-8 py-4 radius-4 text-xs">
                        <i class="ri-time-line me-1"></i>{{ $ann->created_at->format('d M Y, H:i') }}
                        ({{ $ann->created_at->diffForHumans() }})
                    </span>
                    @if($ann->target === 'class' && $ann->schoolClass)
                    <span class="badge bg-info-100 text-info-600 px-8 py-4 radius-4 text-xs">
                        {{ $ann->schoolClass->name }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="p-32 text-center text-secondary-light">
            No announcements yet.
        </div>
        @endforelse
    </div>
    @if($announcements->hasPages())
    <div class="card-footer px-24 py-12">{{ $announcements->links() }}</div>
    @endif
</div>
@endsection
