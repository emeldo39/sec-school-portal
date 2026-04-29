@extends('layouts.public')

@section('title', $newsPost->title . ' — ' . \App\Models\SchoolSetting::get('school_name'))

@section('content')

<section class="it-breadcrumb-dark"
    data-background="{{ $newsPost->image ? asset('storage/' . $newsPost->image) : asset('template/img/breadcrumb/blog-grid.jpg') }}">
    <div class="it-breadcrumb-dark-overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="text-center">
                    <h4 class="it-breadcrumb-title">{{ Str::limit($newsPost->title, 60) }}</h4>
                    <nav>
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('news.index') }}">News</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Article</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pt-80 pb-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-9">

                {{-- Meta --}}
                <div class="d-flex flex-wrap gap-16 mb-30" style="color:var(--it-text-body);font-size:.85rem;">
                    <span style="margin-right: 1rem;"><i class="fal fa-calendar me-1"
                            style="color:var(--it-theme-2);"></i>
                        {{ $newsPost->published_at?->format('d M Y') ?? $newsPost->created_at->format('d M Y') }}
                    </span>
                    @if($newsPost->author)
                    <span><i class="fal fa-user me-1"
                            style="color:var(--it-theme-2);"></i>{{ $newsPost->author }}</span>
                    @endif
                </div>

                {{-- Title --}}
                <h2 style="font-weight:800;color:var(--it-common-black);margin-bottom:24px;line-height:1.25;">
                    {{ $newsPost->title }}
                </h2>

                {{-- Cover image --}}
                @if($newsPost->image)
                <div class="mb-35" style="border-radius:16px;overflow:hidden;">
                    <img src="{{ asset('storage/' . $newsPost->image) }}" class="w-100"
                        style="max-height:420px;object-fit:cover;" alt="{{ $newsPost->title }}">
                </div>
                @endif

                {{-- Body --}}
                <div style="color:#444;font-size:1rem;line-height:1.85;">
                    @if($newsPost->body)
                    {!! nl2br(e($newsPost->body)) !!}
                    @elseif($newsPost->excerpt)
                    <p>{{ $newsPost->excerpt }}</p>
                    @endif
                </div>

                {{-- Back link --}}
                <div class="mt-40 pt-30" style="border-top:1px solid #eee;">
                    <a href="{{ route('news.index') }}" class="it-btn-yellow border-radius-100">
                        <span><span class="text-1">← All News</span><span class="text-2">← All News</span></span>
                        <i><svg width="16" height="15" viewBox="0 0 16 15" fill="none">
                                <path
                                    d="M15.0544 8.1364C15.4058 7.78492 15.4058 7.21508 15.0544 6.8636L9.3268 1.13604C8.97533 0.784567 8.40548 0.784567 8.05401 1.13604C7.70254 1.48751 7.70254 2.05736 8.05401 2.40883L13.1452 7.5L8.05401 12.5912C7.70254 12.9426 7.70254 13.5125 8.05401 13.864C8.40548 14.2154 8.97533 14.2154 9.3268 13.864L15.0544 8.1364ZM0.417969 7.5V8.4H14.418V7.5V6.6H0.417969V7.5Z"
                                    fill="currentcolor" />
                            </svg></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Related Posts --}}
        @if($related->count())
        <div class="row justify-content-center mt-60">
            <div class="col-12">
                <h5 style="font-weight:700;color:var(--it-common-black);margin-bottom:30px;">More News</h5>
            </div>
            @foreach($related as $i => $rel)
            <div class="col-xl-4 col-lg-4 col-md-6 wow itfadeUp" data-wow-duration=".9s"
                data-wow-delay="{{ ($i * 0.15 + 0.2) }}s">
                <div class="it-blog-2-item mb-35">
                    <div class="it-blog-thumb p-relative">
                        @if($rel->image)
                        <img class="w-100" src="{{ asset('storage/' . $rel->image) }}"
                            style="height:200px;object-fit:cover;" alt="{{ $rel->title }}">
                        @else
                        <div
                            style="height:200px;background:var(--it-theme-1);display:flex;align-items:center;justify-content:center;">
                            <i class="fal fa-newspaper" style="font-size:2.5rem;color:rgba(255,255,255,.3);"></i>
                        </div>
                        @endif
                    </div>
                    <div class="it-blog-2-content z-index-2">
                        <div class="it-blog-meta mb-15">
                            <span><i class="fal fa-calendar me-1"></i>{{ $rel->published_at?->format('M d, Y') }}</span>
                        </div>
                        <h5 class="it-blog-title mb-15">
                            <a class="border-line" href="{{ route('news.show', $rel) }}">{{ $rel->title }}</a>
                        </h5>
                        <div class="it-blog-btn">
                            <a href="{{ route('news.show', $rel) }}" class="it-btn-yellow">
                                <span><span class="text-1">More Details</span><span class="text-2">More
                                        Details</span></span>
                                <i><svg width="15" height="15" viewBox="0 0 15 15" fill="none">
                                        <path
                                            d="M14.6364 8.24235C14.9879 7.89088 14.9879 7.32103 14.6364 6.96956L8.90883 1.242C8.55736 0.890524 7.98751 0.890524 7.63604 1.242C7.28457 1.59347 7.28457 2.16332 7.63604 2.51479L12.7272 7.60596L7.63604 12.6971C7.28457 13.0486 7.28457 13.6184 7.63604 13.9699C7.98751 14.3214 8.55736 14.3214 8.90883 13.9699L14.6364 8.24235ZM0 7.60596V8.50596H14V7.60596V6.70596H0V7.60596Z"
                                            fill="currentcolor" />
                                    </svg></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

@endsection