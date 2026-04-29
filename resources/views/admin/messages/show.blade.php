@extends('layouts.portal')

@section('title', $message->subject)
@section('page-title', $message->subject)
@section('page-subtitle', 'From ' . $message->name)

@section('breadcrumb-actions')
    <div class="d-flex gap-8">
        <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject) }}"
           class="btn btn-primary btn-sm">
            <i class="ri-reply-line me-1"></i> Reply via Email
        </a>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-outline-secondary btn-sm">
            Back to Inbox
        </a>
    </div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-1 radius-8">
            <div class="card-body p-28">
                {{-- Sender details --}}
                <div class="d-flex align-items-center gap-16 mb-20 pb-16 border-bottom">
                    <div class="w-52-px h-52-px radius-8 bg-primary-100 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="font-size:1.4rem;font-weight:700;color:#2A2567;">
                        {{ strtoupper(substr($message->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="fw-semibold mb-2">{{ $message->name }}</p>
                        <p class="text-sm text-secondary-light mb-0">
                            <i class="ri-mail-line me-1"></i>{{ $message->email }}
                            &nbsp;&middot;&nbsp;
                            <i class="ri-time-line me-1"></i>{{ $message->created_at->format('d M Y, H:i') }}
                            ({{ $message->created_at->diffForHumans() }})
                        </p>
                    </div>
                </div>

                {{-- Message body --}}
                <div class="bg-neutral-50 radius-8 p-20">
                    <p class="text-sm mb-0" style="white-space:pre-wrap;line-height:1.8;">{{ $message->message }}</p>
                </div>

                {{-- Quick reply note --}}
                <div class="mt-20 p-14 bg-primary-50 radius-8 d-flex align-items-center gap-12">
                    <iconify-icon icon="ph:info" class="text-primary-600 text-lg flex-shrink-0"></iconify-icon>
                    <p class="text-sm text-secondary-light mb-0">
                        To reply, use your email client.
                        <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->subject) }}"
                           class="text-primary-600 fw-semibold">Click here to open in your email app.</a>
                    </p>
                </div>

                {{-- Delete --}}
                <div class="mt-20 pt-16 border-top d-flex justify-content-end">
                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="m-0">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                onclick="return confirm('Delete this message permanently?')">
                            <i class="ri-delete-bin-line me-1"></i> Delete Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
