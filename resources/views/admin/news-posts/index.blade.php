@extends('layouts.portal')

@section('title', 'News Posts')
@section('page-title', 'News Posts')
@section('page-subtitle', 'Manage latest news and school updates')

@section('breadcrumb-actions')
    <a href="{{ route('admin.news-posts.create') }}" class="btn btn-primary btn-sm">
        <i class="ri-add-line me-1"></i> New Post
    </a>
@endsection

@section('content')


<div class="card shadow-1 radius-8">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr style="background:#f8f9fa;border-bottom:2px solid #e9ecef;">
                        <th class="px-20 py-14 text-sm fw-semibold" style="width:60px;">Cover</th>
                        <th class="px-20 py-14 text-sm fw-semibold">Title</th>
                        <th class="px-20 py-14 text-sm fw-semibold">Author</th>
                        <th class="px-20 py-14 text-sm fw-semibold">Status</th>
                        <th class="px-20 py-14 text-sm fw-semibold">Date</th>
                        <th class="px-20 py-14 text-sm fw-semibold text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr>
                        <td class="px-20 py-12">
                            @if($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}"
                                     style="width:52px;height:40px;object-fit:cover;border-radius:6px;" alt="">
                            @else
                                <div style="width:52px;height:40px;border-radius:6px;background:#f1f3f5;display:flex;align-items:center;justify-content:center;">
                                    <i class="ri-image-line text-secondary-light"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-20 py-12">
                            <p class="fw-semibold text-sm mb-2" style="max-width:320px;">{{ $post->title }}</p>
                            @if($post->excerpt)
                            <p class="text-xs text-secondary-light mb-0" style="max-width:320px;">{{ Str::limit($post->excerpt, 90) }}</p>
                            @endif
                        </td>
                        <td class="px-20 py-12 text-sm text-secondary-light">
                            {{ $post->author ?: '—' }}
                        </td>
                        <td class="px-20 py-12">
                            @if($post->is_published)
                                <span class="badge" style="background:#dcfce7;color:#166534;font-size:.72rem;">Published</span>
                            @else
                                <span class="badge" style="background:#f1f5f9;color:#64748b;font-size:.72rem;">Draft</span>
                            @endif
                        </td>
                        <td class="px-20 py-12 text-sm text-secondary-light">
                            {{ $post->published_at?->format('d M Y') ?? $post->created_at->format('d M Y') }}
                        </td>
                        <td class="px-20 py-12 text-end">
                            <div class="d-flex justify-content-end gap-8">
                                <a href="{{ route('admin.news-posts.edit', $post) }}"
                                   class="btn btn-sm btn-outline-primary px-10">
                                    <i class="ri-edit-line"></i>
                                </a>
                                <form action="{{ route('admin.news-posts.destroy', $post) }}" method="POST"
                                      onsubmit="return confirm('Delete this post?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger px-10">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-20 py-32 text-center text-secondary-light">
                            <i class="ri-article-line" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                            No posts yet. <a href="{{ route('admin.news-posts.create') }}">Create your first post.</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($posts->hasPages())
<div class="mt-20">{{ $posts->links() }}</div>
@endif

@endsection
