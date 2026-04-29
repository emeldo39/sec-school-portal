@extends('layouts.portal')

@section('title', 'Contact Messages')
@section('page-title', 'Contact Messages')
@section('page-subtitle', 'Messages submitted via the public website contact form')

@section('content')
<div class="card shadow-1 radius-8">
    <div class="card-body p-0">
        @forelse($messages as $msg)
        <div class="d-flex align-items-start gap-16 p-20 border-bottom {{ $msg->is_read ? '' : 'bg-primary-50' }}">
            <div class="w-44-px h-44-px radius-8 {{ $msg->is_read ? 'bg-neutral-100' : 'bg-primary-100' }} d-flex align-items-center justify-content-center flex-shrink-0">
                <iconify-icon icon="ph:envelope{{ $msg->is_read ? '-open' : '' }}"
                              class="{{ $msg->is_read ? 'text-secondary-light' : 'text-primary-600' }} text-xl"></iconify-icon>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-start justify-content-between gap-8">
                    <div>
                        <div class="d-flex align-items-center gap-8 mb-4">
                            <p class="text-sm fw-semibold mb-0">{{ $msg->subject }}</p>
                            @if(!$msg->is_read)
                                <span class="badge bg-primary-100 text-primary-600 px-6 py-3 radius-4 text-xs">New</span>
                            @endif
                        </div>
                        <p class="text-sm text-secondary-light mb-4">
                            From: <strong>{{ $msg->name }}</strong>
                            &lt;{{ $msg->email }}&gt;
                            &middot; {{ $msg->created_at->format('d M Y, H:i') }}
                        </p>
                        <p class="text-sm mb-0">{{ Str::limit($msg->message, 160) }}</p>
                    </div>
                    <div class="d-flex gap-8 flex-shrink-0">
                        <a href="{{ route('admin.messages.show', $msg) }}"
                           class="btn btn-sm btn-outline-primary px-10 py-5">
                            <i class="ri-eye-line me-1"></i>Read
                        </a>
                        <a href="mailto:{{ $msg->email }}?subject=Re: {{ urlencode($msg->subject) }}"
                           class="btn btn-sm btn-outline-success px-10 py-5">
                            <i class="ri-reply-line me-1"></i>Reply
                        </a>
                        <form action="{{ route('admin.messages.destroy', $msg) }}" method="POST" class="m-0">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger px-10 py-5"
                                    onclick="return confirm('Delete this message?')">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-32 text-center text-secondary-light">
            <iconify-icon icon="ph:envelope-open" class="text-4xl mb-8 d-block"></iconify-icon>
            No messages yet.
        </div>
        @endforelse
    </div>
    @if($messages->hasPages())
    <div class="card-footer px-24 py-12">{{ $messages->links() }}</div>
    @endif
</div>
@endsection
