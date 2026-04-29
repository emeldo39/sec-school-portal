@extends('layouts.portal')

@section('title', 'New Post')
@section('page-title', 'New Post')
@section('page-subtitle', 'Write and publish a news or school update')

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
                <form action="{{ route('admin.news-posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('admin.news-posts._form', ['post' => null])
                    <div class="d-flex gap-10 mt-24">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Save Post
                        </button>
                        <a href="{{ route('admin.news-posts.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
