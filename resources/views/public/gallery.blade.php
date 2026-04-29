@extends('layouts.public')

@section('title', 'Gallery — ' . \App\Models\SchoolSetting::get('school_name'))

@section('content')

{{-- ── Breadcrumb ──────────────────────────────────────────────────────── --}}
<section class="it-breadcrumb-dark" data-background="{{ asset('template/img/breadcrumb/blog-grid.jpg') }}">
    <div class="it-breadcrumb-dark-overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="text-center">
                    <h4 class="it-breadcrumb-title">Gallery</h4>
                    <nav>
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Gallery</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Gallery Grid ─────────────────────────────────────────────────────── --}}
<section class="pt-120 pb-80">
    <div class="container">
        <div class="row justify-content-center mb-50">
            <div class="col-lg-7 text-center">
                <span class="it-section-subtitle yellow-style">School Life</span>
                <h4 class="it-section-title">Our Gallery</h4>
                <p style="color:var(--it-text-body);">A glimpse into life at our school — learning, events, and community moments.</p>
            </div>
        </div>

        @if($items->count())
        <div class="row g-3" id="gallery-grid">
            @foreach($items as $item)
            <div class="col-lg-4 col-md-6 col-6 wow itfadeUp" data-wow-duration=".9s" data-wow-delay="{{ ($loop->index % 6 * 0.08) }}s">
                <a href="{{ asset('storage/' . $item->image_path) }}" class="gallery-popup d-block overflow-hidden" style="border-radius:12px;position:relative;">
                    <img src="{{ asset('storage/' . $item->image_path) }}"
                         alt="{{ $item->caption ?? 'Gallery' }}"
                         style="width:100%;aspect-ratio:4/3;object-fit:cover;transition:transform .4s;"
                         onmouseover="this.style.transform='scale(1.06)'" onmouseout="this.style.transform='scale(1)'">
                    <div style="position:absolute;inset:0;background:rgba(42,37,103,0);transition:background .3s;border-radius:12px;display:flex;align-items:center;justify-content:center;"
                         onmouseover="this.style.background='rgba(42,37,103,.55)';this.querySelector('i').style.opacity='1'"
                         onmouseout="this.style.background='rgba(42,37,103,0)';this.querySelector('i').style.opacity='0'">
                        <i class="fal fa-search-plus" style="color:#fff;font-size:1.6rem;opacity:0;transition:opacity .3s;"></i>
                    </div>
                    @if($item->caption)
                    <div style="position:absolute;bottom:0;left:0;right:0;padding:10px 12px;background:linear-gradient(transparent,rgba(0,0,0,.7));border-radius:0 0 12px 12px;">
                        <p style="color:#fff;font-size:.8rem;margin:0;">{{ $item->caption }}</p>
                    </div>
                    @endif
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <div style="width:80px;height:80px;border-radius:50%;background:var(--it-gray-1);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
                <i class="fal fa-images" style="font-size:2rem;color:var(--it-theme-3);"></i>
            </div>
            <h5 style="color:var(--it-common-black);">No gallery photos yet.</h5>
            <p style="color:var(--it-text-body);">Check back soon — photos will be added here by the school administration.</p>
        </div>
        @endif
    </div>
</section>

@endsection

@push('page-scripts')
<script>
$(function () {
    $('.gallery-popup').magnificPopup({
        type: 'image',
        gallery: { enabled: true },
        image: { titleSrc: function (item) {
            return item.el.find('img').attr('alt') || '';
        }},
        removalDelay: 300,
        mainClass: 'mfp-fade',
    });
});
</script>
@endpush
