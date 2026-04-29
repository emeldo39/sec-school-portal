@extends('layouts.portal')

@section('title', 'Edit Post')
@section('page-title', 'Edit Post')
@section('page-subtitle', $newsPost->title)

@section('breadcrumb-actions')
    <a href="{{ route('admin.news-posts.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="ri-arrow-left-line me-1"></i> Back to Posts
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9 col-lg-10">
        <div class="card shadow-1 radius-8">
            <div class="card-body p-28">
                <form action="{{ route('admin.news-posts.update', $newsPost) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    @include('admin.news-posts._form', ['post' => $newsPost])
                    <div class="d-flex gap-10 mt-24">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Update Post
                        </button>
                        <a href="{{ route('admin.news-posts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
