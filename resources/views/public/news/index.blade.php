@extends('layouts.public')

@section('title', 'Latest News — ' . \App\Models\SchoolSetting::get('school_name'))

@section('content')

<section class="it-breadcrumb-dark" data-background="{{ asset('template/img/breadcrumb/blog-grid.jpg') }}">
    <div class="it-breadcrumb-dark-overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="text-center">
                    <h4 class="it-breadcrumb-title">Latest News</h4>
                    <nav>
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">News</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="it-blog-2-area z-index-1 pt-120 pb-80 fix">
    <img class="it-blog-shape-1" src="{{ asset('template/img/shape/blog-3-1.png') }}" alt="">
    <img class="it-blog-shape-2 d-none d-lg-block" data-parallax='{"x": 200, "smoothness": 30}'
         src="{{ asset('template/img/shape/blog-1-2.png') }}" alt="">
    <div class="container">
        <div class="row justify-content-center mb-60">
            <div class="col-lg-7 text-center">
                <span class="it-section-subtitle yellow-style">School Updates</span>
                <h4 class="it-section-title">Latest News &amp; Announcements</h4>
            </div>
        </div>

        @if($posts->count())
        <div class="row gx-35">
            @foreach($posts as $i => $post)
            <div class="col-xl-4 col-lg-6 col-md-6 wow itfadeUp" data-wow-duration=".9s"
                 data-wow-delay="{{ ($i % 3 * 0.2 + 0.3) }}s">
                <div class="it-blog-2-item mb-35">
                    <div class="it-blog-thumb p-relative">
                        @if($post->image)
                            <img class="w-100" src="{{ asset('storage/' . $post->image) }}"
                                 style="height:230px;object-fit:cover;" alt="{{ $post->title }}">
                        @else
                            <div style="height:230px;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;">
                                <i class="fal fa-newspaper" style="font-size:3rem;color:rgba(255,255,255,.3);"></i>
                            </div>
                        @endif
                    </div>
                    <div class="it-blog-2-content z-index-2">
                        <div class="it-blog-meta mb-25">
                            <span>
                                <i class="fal fa-calendar me-1"></i>
                                {{ $post->published_at?->format('M d, Y') ?? $post->created_at->format('M d, Y') }}
                            </span>
                            @if($post->author)
                            <span>
                                <i class="fal fa-user me-1"></i>{{ $post->author }}
                            </span>
                            @endif
                        </div>
                        <h5 class="it-blog-title mb-15">
                            <a class="border-line" href="{{ route('news.show', $post) }}">
                                {{ $post->title }}
                            </a>
                        </h5>
                        <div class="it-blog-btn">
                            <a href="{{ route('news.show', $post) }}" class="it-btn-yellow">
                                <span>
                                    <span class="text-1">More Details</span>
                                    <span class="text-2">More Details</span>
                                </span>
                                <i><svg width="15" height="15" viewBox="0 0 15 15" fill="none"><path d="M14.6364 8.24235C14.9879 7.89088 14.9879 7.32103 14.6364 6.96956L8.90883 1.242C8.55736 0.890524 7.98751 0.890524 7.63604 1.242C7.28457 1.59347 7.28457 2.16332 7.63604 2.51479L12.7272 7.60596L7.63604 12.6971C7.28457 13.0486 7.28457 13.6184 7.63604 13.9699C7.98751 14.3214 8.55736 14.3214 8.90883 13.9699L14.6364 8.24235ZM0 7.60596V8.50596H14V7.60596V6.70596H0V7.60596Z" fill="currentcolor"/></svg></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($posts->hasPages())
        <div class="d-flex justify-content-center mt-20">
            {{ $posts->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-5">
            <div style="width:80px;height:80px;border-radius:50%;background:var(--it-gray-1);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                <i class="fal fa-newspaper" style="font-size:2rem;color:var(--it-theme-3);"></i>
            </div>
            <h5 style="color:var(--it-common-black);">No news posts yet.</h5>
            <p style="color:var(--it-text-body);">Check back soon for school updates and news.</p>
        </div>
        @endif
    </div>
</section>

@endsection
