<!DOCTYPE html>
<html lang="en">

@section('page_title', 'Japan Travel News & Stories – Waku Trip')
@section('meta_description', 'Stay informed with the latest Japan travel news, seasonal guides, and insider stories from the Waku Trip blog. Discover hidden gems and travel tips for your next adventure.')
@include('partials.head')

<body class="bg-light">

    @include('partials.header')

    <main class="container py-5" style="max-width: 1280px;">

        @if(isset($featured) && $featured)
            <!-- Featured Article Hero -->
            <!-- Featured Article Hero -->
            <div class="row mb-5 g-0 overflow-hidden rounded-4 shadow-lg hero-card">
                <!-- Text Column -->
                <div class="col-lg-5 order-2 order-lg-1">
                    <div class="h-100 d-flex flex-column justify-content-center p-4 p-lg-5 position-relative"
                        style="background: #fff0f5;">
                        <!-- Mobile fade overlap -->
                        <div class="d-lg-none position-absolute top-0 start-0 w-100"
                            style="height: 40px; background: linear-gradient(to bottom, rgba(255,240,245,0) 0%, #fff0f5 100%); transform: translateY(-39px);">
                        </div>

                        <span
                            class="badge bg-white text-danger fw-bold mb-3 align-self-start shadow-sm d-flex align-items-center gap-2 py-2 px-3 rounded-pill"
                            style="font-size: 11px; letter-spacing: 0.1em; color: #e91e63 !important;">
                            <span class="material-symbols-outlined" style="font-size: 14px;">local_florist</span>
                            FEATURED STORY
                        </span>
                        <h1 class="fw-black mb-3 mb-lg-4 lh-sm hero-title"
                            style="font-family: 'Plus Jakarta Sans', sans-serif; color: #1a1a1a;">
                            <a href="{{ route('news.show', $featured->slug) }}"
                                class="text-decoration-none text-dark">{{ $featured->title }}</a>
                        </h1>
                        <p class="text-dark mb-4 fw-normal opacity-75" style="font-size: 15px; line-height: 1.6;">
                            {{ Str::limit($featured->excerpt ?? strip_tags($featured->content), 150) }}
                        </p>
                        <div class="mt-auto">
                            <a href="{{ route('news.show', $featured->slug) }}"
                                class="btn btn-dark w-100 w-lg-auto rounded-3 px-4 py-3 fw-bold d-inline-flex align-items-center justify-content-center gap-2"
                                style="font-size: 14px; background: #1a1a1a; border: none;">
                                Read Feature Story
                                <span class="material-symbols-outlined fs-6">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Image Column -->
                <div class="col-lg-7 order-1 order-lg-2">
                    <div class="h-100 hero-img-wrapper position-relative" style="background: #000;">
                        <img src="{{ $featured->getFirstMediaUrl('primary_image', app_setting('default_news_image', 'https://images.unsplash.com/photo-1522383225653-ed111181a951?q=80&w=1976&auto=format&fit=crop')) }}"
                            class="w-100 h-100 object-fit-cover opacity-75 opacity-lg-100" alt="{{ $featured->title }}"
                            fetchpriority="high" decoding="async">
                        <!-- Mobile Image Gradient Overlay -->
                        <div class="d-lg-none position-absolute bottom-0 start-0 w-100"
                            style="height: 150px; background: linear-gradient(to top, #fff0f5 0%, transparent 100%);"></div>
                    </div>
                </div>
            </div>

            <style>
                .hero-title {
                    font-size: 2rem;
                }

                .hero-img-wrapper {
                    height: 350px;
                }

                @media (min-width: 992px) {
                    .hero-title {
                        font-size: 48px;
                    }

                    .hero-img-wrapper {
                        height: 100%;
                        min-height: 500px;
                    }

                    .hero-card {
                        aspect-ratio: auto;
                    }
                }
            </style>
        @endif

        <!-- Category Filter -->
        <div class="mb-5 pb-3 border-bottom">
            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-japan rounded-pill px-4 py-2 fw-bold">All Stories</button>
            </div>
        </div>

        <!-- Masonry Grid -->
        <div class="row g-4">
            @foreach($posts as $post)
                <div class="col-md-6 col-lg-4">
                    <article class="card border-0 shadow-sm h-100 overflow-hidden">
                        <div class="position-relative" style="height: 250px;">
                            <img src="{{ $post->getFirstMediaUrl('primary_image', app_setting('default_news_image', 'https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?q=80&w=2127&auto=format&fit=crop')) }}"
                                class="w-100 h-100 object-fit-cover" alt="{{ $post->title }}">
                            <div class="position-absolute bottom-0 start-0 p-3">
                                <span class="badge bg-dark bg-opacity-75 text-white px-3 py-2">NEWS</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h3 class="h5 fw-bold mb-2">
                                <a href="{{ route('news.show', $post->slug) }}"
                                    class="text-dark text-decoration-none">{{ $post->title }}</a>
                            </h3>
                            <p class="text-secondary small mb-2">
                                {{ Str::limit($post->excerpt ?? strip_tags($post->content), 100) }}
                            </p>
                            <div class="d-flex align-items-center gap-2 text-secondary small">
                                <span>{{ $post->published_at?->format('F d, Y') }}</span>
                                <span>•</span>
                                <span>{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read</span>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-5 pt-4 d-flex justify-content-center">
            {{ $posts->links() }}
        </div>

    </main>

    @include('partials.footer')

</body>

</html>