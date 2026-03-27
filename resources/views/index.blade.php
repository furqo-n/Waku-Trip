<!DOCTYPE html>
<html lang="en">

@section('page_title', 'Waku Trip – Discover the Soul of Japan | Tours & Experiences')
@section('meta_description', 'Explore Japan like a local with Waku Trip. Book curated open group tours, private experiences, and seasonal adventures across Tokyo, Kyoto, Osaka, Hokkaido, and more.')
@include('partials.head')

<body class="bg-shape overflow-x-hidden">

    <!--================ Header Menu Area start =================-->
    @include('partials.header')
    <!--===============Header Menu Area =================-->



    <main id="main-content">

        <!--================Hero Banner Area Start =================-->
        <section
            class="position-relative vh-100 d-flex align-items-center justify-content-center overflow-hidden bg-dark">

            <div class="position-absolute top-0 start-0 w-100 h-100"
                style="background: url('{{ app_setting('home_hero_bg', asset('img/torii.jpg')) }}') center/cover no-repeat; opacity: 0.6;">
            </div>

            <div class="container position-relative text-center text-white z-1 py-5">

                <span class="badge rounded-pill bg-danger py-2 px-4 mb-4 text-uppercase tracking-widest shadow">
                    Explore The Unseen
                </span>

                <h1 class="display-1 fw-bold mb-3" style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    Discover the <br> Soul of <span class="text-danger fw-bold"
                        style="font-family: 'Rock Salt', cursive;">Japan</span>
                </h1>

                <p class="lead mb-5 mx-auto text-light" style="max-width: 700px;">
                    Immerse yourself in a land where ancient traditions melt into neon futures.
                    Your journey starts here.
                </p>

                <form action="{{ route('planned.index') }}" method="GET"
                    class="bg-white p-3 rounded-5 shadow-lg mx-auto text-dark" style="max-width: 900px;">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-4 text-start border-end border-secondary">
                            <div class="d-flex align-items-center px-2">
                                {!! site_icon('geo_alt_icon', '<i class="bi bi-geo-alt-fill text-danger me-3 fs-3"></i>') !!}
                                <div class="w-100">
                                    <label class="small fw-bold text-muted text-uppercase d-block mb-0"
                                        style="font-size: 10px;">Destination</label>
                                    <input type="text" name="destination"
                                        class="form-control border-0 bg-transparent p-0 fw-bold shadow-none"
                                        placeholder="Where to go?" value="{{ request('destination') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 text-start border-end border-secondary border-opacity-25">
                            <div class="d-flex align-items-center px-2">
                                {!! site_icon('season_icon', '<i class="bi bi-cloud-sun text-danger me-2 fs-3"></i>') !!}
                                <div class="w-100">
                                    <label for="search-season"
                                        class="small fw-bold text-muted text-uppercase d-block mb-0"
                                        style="font-size: 10px;">Season</label>
                                    <select id="search-season" name="seasons[]"
                                        class="form-select border-0 bg-transparent p-0 fw-bold shadow-none text-dark"
                                        style="cursor: pointer;">
                                        <option value="">All Seasons</option>
                                        <option value="Spring" {{ request('seasons.0') == 'Spring' ? 'selected' : '' }}>
                                            Spring
                                        </option>
                                        <option value="Summer" {{ request('seasons.0') == 'Summer' ? 'selected' : '' }}>
                                            Summer
                                        </option>
                                        <option value="Autumn" {{ request('seasons.0') == 'Autumn' ? 'selected' : '' }}>
                                            Autumn
                                        </option>
                                        <option value="Winter" {{ request('seasons.0') == 'Winter' ? 'selected' : '' }}>
                                            Winter
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 text-start">
                            <div class="d-flex align-items-center px-2">
                                {!! site_icon('interest_icon', '<i class="bi bi-columns-gap text-danger me-2 fs-3"></i>') !!}
                                <div class="w-100">
                                    <label for="search-interest"
                                        class="small fw-bold text-muted text-uppercase d-block mb-0"
                                        style="font-size: 10px;">Interest</label>
                                    <select id="search-interest" name="categories[]"
                                        class="form-select border-0 bg-transparent p-0 fw-bold shadow-none">
                                        <option value="">All Interests</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ request('categories.0') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-danger w-100 rounded-pill py-3 shadow">
                                {!! site_icon('search_button_icon', '<span class="material-icons align-middle">search</span>') !!}
                            </button>
                        </div>
                    </div>
                </form>
                <div class="container align-items-center py-3">
                    {!! site_icon('hero_scroll_icon', '<i class="bi bi-chevron-down fs-1"></i>') !!}
                </div>
            </div>
        </section>
        <!--================Hero Banner Area End =================-->
        <section id="seasonal-section" class="py-5 position-relative overflow-hidden"
            style="background: {{ $seasonData['bg_gradient'] }}; transition: background 0.5s ease; --season-accent: {{ $seasonData['accent_color'] }};">
            <div class="position-absolute top-0 end-0 pe-none" style="opacity: 0.1;">
                <span id="seasonal-icon" class="material-symbols-outlined icon-bg"
                    style="transition: all 0.5s ease; color: var(--season-accent);">{{ $seasonData['icon'] }}</span>
            </div>

            <div class="container position-relative">
                <div class="d-flex flex-column flex-md-row align-items-md-end justify-content-between mb-5 gap-4">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap position-relative"
                            style="z-index: 2;">
                            <span id="seasonal-label"
                                class="d-flex align-items-center gap-2 fw-bold text-uppercase small me-1 user-select-none"
                                style="cursor: default; color: var(--season-accent); transition: color 0.5s ease;">
                                <span class="dot"
                                    style="background-color: var(--season-accent); transition: background 0.5s ease;"></span>
                                Season
                            </span>

                            @php
                                $seasonButtons = [
                                    'Spring' => 'spa',
                                    'Summer' => 'wb_sunny',
                                    'Autumn' => 'eco',
                                    'Winter' => 'ac_unit',
                                ];
                            @endphp

                            @foreach($seasonButtons as $season => $icon)
                                <button type="button" onclick="switchSeason('{{ $season }}', this)"
                                    class="season-btn btn btn-sm rounded-pill px-3 py-1 d-flex align-items-center gap-1 fw-bold text-uppercase shadow-sm user-select-none {{ $currentSeason == $season ? 'active' : 'bg-white text-dark border' }}"
                                    style="font-size: 0.7rem; letter-spacing: 0.05em; text-decoration: none; transition: all 0.3s; cursor: pointer;
                                                                                                                                                        {{ $currentSeason == $season ? 'background-color: var(--season-accent); color: white; border-color: var(--season-accent);' : '' }}">

                                    <span class="material-symbols-outlined"
                                        style="font-size: 14px; pointer-events: none;">{{ $icon }}</span>
                                    <span style="pointer-events: none;">{{ $season }}</span>
                                </button>
                            @endforeach
                        </div>

                        <div id="seasonal-text-content" style="transition: opacity 0.3s ease;">
                            <h2 class="fw-bold display-5 mb-3 text-dark">
                                <span id="seasonal-title">{{ $seasonData['title'] }}</span>
                                <span id="seasonal-subtitle"
                                    style="font-family: 'Rock Salt', cursive; color: var(--season-accent); transition: color 0.5s ease;">{{ $seasonData['subtitle'] }}</span>
                            </h2>

                            <p id="seasonal-description" class="text-muted fs-5" style="max-width: 520px;">
                                {{ $seasonData['description'] }}
                            </p>
                        </div>
                    </div>

                    <a id="seasonal-explore-btn" href="{{ route('planned.index', ['seasons' => [$currentSeason]]) }}"
                        class="btn rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 text-decoration-none"
                        style="transition: all 0.3s ease; border: 2px solid var(--season-accent); color: var(--season-accent);">
                        <span id="seasonal-button-text">{{ $seasonData['button_text'] }}</span>
                        <span class="material-symbols-outlined arrow-hover">arrow_forward</span>
                    </a>
                </div>

                <!-- Cards -->
                <div id="seasonal-cards-container" class="row g-4" style="transition: opacity 0.3s ease;">
                    @include('partials.seasonal_cards')
                </div>
            </div>
        </section>

        @vite(['resources/css/pages/index.css', 'resources/js/season-switcher.js'])

        <!--================category Area Start =================-->
        <section class="py-5 container">
            <h2 class="fw-bold mb-5" style="color: #0f172a;">Explore by Category</h2>

            <div class="row g-4 row-cols-3 row-cols-md-6 justify-content-center">
                @foreach($categories as $category)
                    <div class="col">
                        <a href="{{ route('planned.index', ['categories' => [$category->id]]) }}"
                            class="text-decoration-none group">
                            <div class="category-item text-center">
                                <div class="category-icon-wrapper mx-auto mb-3">
                                    <span class="material-symbols-outlined">{{ $category->icon ?? 'category' }}</span>
                                </div>
                                <span class="category-name fw-bold">{{ $category->name }}</span>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
        <!--================category Area End =================-->
        <section class="py-5">
            <div class="container">
                <div class="d-flex align-items-end justify-content-between mb-4">
                    <div>
                        <h2 class="display-6 fw-black text-dark mb-1" style="font-weight: 900;">Trending Open Trips</h2>
                        <p class="text-secondary mb-0">Join small groups of travelers on these curated adventures.</p>
                    </div>
                    <a href="{{ route('planned.index', ['trip_types' => ['open']]) }}"
                        class="d-none d-md-flex btn btn-light rounded-pill px-4 py-2 align-items-center fw-bold text-decoration-none flex-shrink-0 ms-3">
                        View all tours
                        <span class="material-symbols-outlined ms-2 fs-6">arrow_forward_ios</span>
                    </a>
                </div>
            </div>

            {{-- Mobile: Horizontal swipeable slider --}}
            <div class="d-md-none">
                <div class="trending-slider px-3">
                    @foreach($trendingTours as $index => $tour)
                        <a href="{{ route('tour.show', $tour->slug) }}" class="text-decoration-none trending-slide-item">
                            <div class="ratio ratio-4x5 tour-card shadow-lg rounded-4 overflow-hidden">
                                <div class="tour-bg" style="background-image: url('{{ $tour->primary_image_url }}'); content-visibility: auto;">
                                </div>
                                <div class="gradient-overlay"></div>
                                <div class="position-absolute w-100 h-100 d-flex flex-column justify-content-between p-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        @if($tour->is_trending)
                                            <div
                                                class="badge-trend px-3 py-2 rounded-pill small fw-bold shadow d-flex align-items-center gap-1">
                                                <span class="material-symbols-outlined"
                                                    style="font-size: 14px;">local_fire_department</span> Trending
                                            </div>
                                        @else
                                            <div></div>
                                        @endif
                                        <button
                                            class="btn glass-btn rounded-circle p-2 d-flex align-items-center justify-content-center shadow"
                                            style="width: 40px; height: 40px;" onclick="event.preventDefault();">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">favorite</span>
                                        </button>
                                    </div>
                                    <div class="card-content text-white">
                                        @if($tour->review_count > 0)
                                            <div class="d-flex align-items-center gap-1 text-warning mb-1">
                                                <span class="material-symbols-outlined fs-6 fill-icon">star</span>
                                                <span
                                                    class="text-white fw-bold small">{{ number_format($tour->average_rating, 1) }}
                                                    ({{ $tour->review_count }})</span>
                                            </div>
                                        @endif
                                        <h3 class="fw-bolder lh-sm mb-1 fs-5">{{ $tour->title }}</h3>
                                        <p class="text-white-50 small fw-medium mb-2">{{ $tour->location_text }}</p>
                                        <div
                                            class="d-flex justify-content-between align-items-center border-top border-light border-opacity-25 pt-2">
                                            <div class="d-flex align-items-center gap-1 text-white-50 fw-medium small">
                                                <span class="material-symbols-outlined fs-6">schedule</span>
                                                {{ $tour->duration_days }} {{ $tour->duration_days == 1 ? 'Day' : 'Days' }}
                                            </div>
                                            <div class="glass-btn px-3 py-1 rounded-pill fw-bold small">
                                                From {{ convert_currency($tour->base_price) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                {{-- Mobile View-all link --}}
                <div class="text-center mt-4 px-3">
                    <a href="{{ route('planned.index', ['trip_types' => ['open']]) }}"
                        class="btn btn-outline-dark rounded-pill px-5 py-2 fw-bold text-decoration-none w-100">
                        View all tours
                        <span class="material-symbols-outlined ms-1 fs-6 align-middle">arrow_forward_ios</span>
                    </a>
                </div>
            </div>

            {{-- Desktop: Standard grid --}}
            <div class="container d-none d-md-block">
                <div class="row g-4">
                    @foreach($trendingTours as $index => $tour)
                        <div class="col-md-6 col-lg-4">
                            <a href="{{ route('tour.show', $tour->slug) }}" class="text-decoration-none">
                                <div class="ratio ratio-4x5 tour-card shadow-lg">
                                    <div class="tour-bg" style="background-image: url('{{ $tour->primary_image_url }}'); content-visibility: auto;">
                                    </div>
                                    <div class="gradient-overlay"></div>
                                    <div
                                        class="position-absolute w-100 h-100 d-flex flex-column justify-content-between p-4">
                                        <div class="d-flex justify-content-between align-items-start">
                                            @if($tour->is_trending)
                                                <div
                                                    class="badge-trend px-3 py-2 rounded-pill small fw-bold shadow d-flex align-items-center gap-1">
                                                    <span class="material-symbols-outlined"
                                                        style="font-size: 14px;">local_fire_department</span> Trending
                                                </div>
                                            @else
                                                <div></div>
                                            @endif
                                            <button
                                                class="btn glass-btn rounded-circle p-2 d-flex align-items-center justify-content-center shadow"
                                                style="width: 45px; height: 45px;" onclick="event.preventDefault();">
                                                <span class="material-symbols-outlined">favorite</span>
                                            </button>
                                        </div>
                                        <div class="card-content text-white">
                                            @if($tour->review_count > 0)
                                                <div class="d-flex align-items-center gap-1 text-warning mb-1">
                                                    <span class="material-symbols-outlined fs-5 mb-1 fill-icon">star</span>
                                                    <span
                                                        class="text-white fw-bold small">{{ number_format($tour->average_rating, 1) }}
                                                        ({{ $tour->review_count }}
                                                        {{ $tour->review_count == 1 ? 'review' : 'reviews' }})</span>
                                                </div>
                                            @endif
                                            <h3 class="fw-bolder lh-sm mb-1">{{ $tour->title }}</h3>
                                            <p class="text-white-50 small fw-medium mb-3">{{ $tour->location_text }}</p>
                                            <div
                                                class="d-flex justify-content-between align-items-center border-top border-light border-opacity-25 pt-3">
                                                <div class="d-flex align-items-center gap-1 text-white-50 fw-medium">
                                                    <span class="material-symbols-outlined fs-5">schedule</span>
                                                    {{ $tour->duration_days }}
                                                    {{ $tour->duration_days == 1 ? 'Day' : 'Days' }}
                                                </div>
                                                <div class="glass-btn px-4 py-2 rounded-pill fw-bold">
                                                    From {{ convert_currency($tour->base_price) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="section-padding position-relative">
            <div class="bg-decor-icon d-none d-lg-block">
                <span class="material-symbols-outlined text-secondary">temple_buddhist</span>
            </div>

            <div class="container position-relative z-1" style="max-width: 1440px;">
                <div class="row align-items-center gy-5">
                    <div class="col-12 col-lg-4 pe-lg-5">
                        <div
                            class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill badge-premium fw-bold text-uppercase small mb-4">
                            <span class="material-symbols-outlined" style="font-size: 16px;">diamond</span> Premium
                        </div>
                        <h2 class="display-4 fw-black mb-4 text-dark lh-1"
                            style="font-weight: 900; letter-spacing: -1px;">
                            Exclusive Private Experiences
                        </h2>
                        <p class="text-secondary fs-5 mb-4 lh-lg">
                            Go beyond the guidebooks. Immerse yourself in authentic Japanese traditions with our
                            private,
                            curated experiences designed for discerning travelers who seek the extraordinary.
                        </p>
                        {{-- Desktop Button --}}
                        <button class="d-none d-lg-inline-flex btn btn-dark-custom align-items-center gap-2 group">
                            Explore All Private Tours
                            <span class="material-symbols-outlined transition-transform">arrow_forward</span>
                        </button>
                    </div>

                    <div class="col-12 col-lg-8">
                        {{-- Mobile: Horizontal Slider / Desktop: Flex Container --}}
                        <div class="experience-slider">
                            @forelse($experiences as $experience)
                                @php
                                    $categoryName = $experience->relatedCategories->first()?->name ?? 'Activity';
                                    $tagClass = match (strtolower($categoryName)) {
                                        'cultural', 'culture', 'history' => 'tag-cultural',
                                        'sports', 'sport', 'adventure' => 'tag-sports',
                                        'culinary', 'food' => 'tag-culinary',
                                        default => 'tag-cultural'
                                    };
                                @endphp
                                <div class="experience-card flex-shrink-0">
                                    <a href="{{ route('tour.show', $experience->slug) }}" class="text-decoration-none">
                                        <div class="card-image-wrapper"
                                            style="background-image: url('{{ $experience->primary_image_url }}'); content-visibility: auto;">
                                            <div class="gradient-overlay"></div>
                                            <div class="position-absolute bottom-0 start-0 p-4 text-white w-100">
                                                <p class="small fw-bold text-uppercase opacity-75 mb-1 ls-widest">
                                                    {{ Str::limit($experience->location_text ?? 'Japan', 15) }}
                                                </p>
                                                <h4 class="fw-bold mb-0 text-white">{{ Str::limit($experience->title, 25) }}
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="px-2 pb-1 mt-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span
                                                    class="{{ $tagClass }} px-3 py-1 rounded-3 small fw-bold text-uppercase">{{ $categoryName }}</span>
                                                <span
                                                    class="fs-5 fw-black text-dark">{{ convert_currency($experience->base_price) }}
                                                    <span class="small fw-medium text-secondary">/ person</span></span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @empty
                                <div class="w-100 text-center py-5">
                                    <p class="text-secondary fw-bold fs-5 mb-0">More experiences coming soon!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Mobile Button (Bottom) --}}
                    <div class="col-12 d-lg-none text-center">
                        <button
                            class="btn btn-dark-custom d-inline-flex align-items-center gap-2 group w-100 justify-content-center">
                            Explore All Private Tours
                            <span class="material-symbols-outlined transition-transform">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 mb-5 mt-3 container text-center" style="max-width: 1440px;">

            <h2 class="display-5 fw-black text-dark mb-4 mb-lg-5 pb-3">Why Choose Waku Trip</h2>

            <div class="row row-cols-1 row-cols-md-3 gy-5 gx-lg-5 mobile-feature-slider justify-content-center">

                <div class="col">
                    <div
                        class="feature-card d-flex flex-column align-items-center h-100 p-3 p-md-0 rounded-4 bg-white-mobile">
                        <div class="icon-box rotate-cw mb-4">
                            <span class="material-symbols-outlined fs-1" style="font-size: 3rem;">travel_explore</span>
                        </div>
                        <h3 class="h3 fw-bold mb-3 text-dark">Expert Local Guides</h3>
                        <p class="text-secondary fs-5 mx-auto" style="max-width: 320px;">
                            Our guides are locals who know the hidden gems and stories behind every corner.
                        </p>
                    </div>
                </div>

                <div class="col">
                    <div
                        class="feature-card d-flex flex-column align-items-center h-100 p-3 p-md-0 rounded-4 bg-white-mobile">
                        <div class="icon-box rotate-ccw mb-4">
                            <span class="material-symbols-outlined fs-1" style="font-size: 3rem;">support_agent</span>
                        </div>
                        <h3 class="h3 fw-bold mb-3 text-dark">24/7 Support</h3>
                        <p class="text-secondary fs-5 mx-auto" style="max-width: 320px;">
                            We are here for you around the clock, ensuring a smooth and worry-free journey.
                        </p>
                    </div>
                </div>

                <div class="col">
                    <div
                        class="feature-card d-flex flex-column align-items-center h-100 p-3 p-md-0 rounded-4 bg-white-mobile">
                        <div class="icon-box rotate-cw mb-4">
                            <span class="material-symbols-outlined fs-1" style="font-size: 3rem;">edit_note</span>
                        </div>
                        <h3 class="h3 fw-bold mb-3 text-dark">Fully Customizable</h3>
                        <p class="text-secondary fs-5 mx-auto" style="max-width: 320px;">
                            Tailor any itinerary to fit your schedule, interests, and pace perfectly.
                        </p>
                    </div>
                </div>

            </div>
        </section>
    </main>{{-- /#main-content --}}
    @include('partials.footer')
</body>

</html>