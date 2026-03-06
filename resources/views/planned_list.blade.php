<!DOCTYPE html>
<html lang="en">

@section('page_title', 'Japan Tours & Itineraries – Waku Trip')
@section('meta_description', 'Browse all Japan tours and itineraries on Waku Trip. Filter by destination, season, category, and trip type. Find the perfect open group or private tour for your adventure.')
@include('partials.head')

<body class="bg-shape">

    <!--================ Header Menu Area start =================-->
    @include('partials.header')
    <!--===============Header Menu Area =================-->
    <div class="container-fluid px-3 px-md-5 py-4 py-md-5" style="max-width: 1600px;">

        <div class="mb-4 mb-md-5">
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="bg-japan-red" style="width: 40px; height: 2px;"></span>
                <span class="text-japan-red fw-bold text-uppercase small" style="letter-spacing: 0.2em;">Unforgettable
                    Journeys</span>
            </div>
            <h1 class="display-5 display-md-3 fw-black text-dark mb-3 ls-tight">
                Crafted Japan <span class="text-japan-red">Tours</span>
            </h1>
            <p class="lead text-secondary fw-medium mb-0" style="max-width: 700px;">
                Choose from our meticulously planned itineraries or book a private guide for an exclusive discovery of
                the Land of the Rising Sun.
            </p>
        </div>

        {{-- Mobile Filter Toggle Button --}}
        <div class="d-lg-none mb-3">
            <button
                class="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2 w-100 justify-content-center"
                type="button" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas"
                aria-controls="filterOffcanvas">
                <span class="material-symbols-outlined fs-5">tune</span> Filters & Sort
            </button>
        </div>

        <div class="row g-4 g-lg-5">

            {{-- Desktop Sidebar (visible >= lg) --}}
            <aside class="col-lg-3 d-none d-lg-block">
                <form method="GET" action="{{ route('planned.index') }}" id="filterForm">
                    <div class="glass-panel rounded-5 p-4 sticky-top" style="top: 100px; z-index: 10;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-extrabold mb-0 d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined text-japan-red">tune</span> Filters
                            </h5>
                            <a href="{{ route('planned.index') }}"
                                class="text-japan-red text-uppercase fw-bold text-decoration-none"
                                style="font-size: 0.7rem; letter-spacing: 0.1em;">Reset</a>
                        </div>

                        @include('partials._filter_fields')

                        <button type="submit" class="btn btn-japan w-100 py-3 rounded-4 fw-black text-uppercase small"
                            style="letter-spacing: 0.15em;">Apply Filters</button>
                    </div>
                </form>
            </aside>

            {{-- Mobile Offcanvas Filter (visible < lg) --}} <div
                class="offcanvas offcanvas-bottom rounded-top-5 d-lg-none" tabindex="-1" id="filterOffcanvas"
                aria-labelledby="filterOffcanvasLabel" style="height: 85vh;">
                <div class="offcanvas-header border-bottom px-4 pt-4 pb-3">
                    <h5 class="fw-extrabold mb-0 d-flex align-items-center gap-2" id="filterOffcanvasLabel">
                        <span class="material-symbols-outlined text-japan-red">tune</span> Filters
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body px-4 pb-4 pt-3">
                    <form method="GET" action="{{ route('planned.index') }}" id="filterFormMobile">
                        @include('partials._filter_fields')
                        <div class="d-flex gap-3 mt-4">
                            <a href="{{ route('planned.index') }}"
                                class="btn btn-outline-secondary rounded-4 fw-bold flex-fill py-3">Reset</a>
                            <button type="submit"
                                class="btn btn-japan rounded-4 fw-black text-uppercase small flex-fill py-3"
                                style="letter-spacing: 0.15em;">Apply</button>
                        </div>
                    </form>
                </div>
        </div>

        <main class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <p class="mb-0 fw-black text-secondary text-uppercase small" style="letter-spacing: 0.1em;">
                    {{ $packages->count() }}
                    Selected Experiences
                </p>
                <div class="d-none d-sm-flex align-items-center gap-2">
                    <label for="sort-select" class="text-secondary fw-bold text-uppercase small mb-0">Sort:</label>
                    <select id="sort-select"
                        class="form-select border-0 bg-transparent fw-black text-japan-red text-uppercase small py-0 ps-1 pe-4 shadow-none"
                        style="width: auto; cursor: pointer;">
                        <option>Most Popular</option>
                        <option>Newest First</option>
                        <option>Price: Low to High</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 g-md-4 g-lg-5 mb-5">
                @forelse($packages as $package)
                    <div class="col-6 col-md-6 col-xl-4">
                        <div class="tour-card group shadow-sm rounded-4 overflow-hidden planned-tour-card"
                            style="position: relative; max-width: 100%;">
                            <div class="position-absolute w-100 h-100" style="top: 0; left: 0;">
                                <div class="tour-bg-image"
                                    style="background-image: url('{{ $package->getFirstMediaUrl('primary_image', app_setting('default_tour_image')) }}');">
                                </div>
                                <div class="tour-overlay"></div>
                            </div>

                            <div class="position-absolute w-100 h-100 p-2 p-md-3 d-flex flex-column"
                                style="top: 0; left: 0; z-index: 2;">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span
                                        class="badge {{ $package->type == 'open' ? 'bg-white text-japan-red' : 'bg-dark text-white' }} rounded-pill px-2 py-1 fw-black text-uppercase shadow-sm"
                                        style="font-size: 7px; letter-spacing: 0.08em;">
                                        {{ $package->type == 'open' ? 'Open Group' : 'Private Tour' }}
                                    </span>
                                    <button
                                        class="btn glass-badge rounded-circle p-1 d-flex align-items-center justify-content-center shadow-sm"
                                        style="width: 26px; height: 26px;">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">favorite</span>
                                    </button>
                                </div>
                                <div class="mt-auto">
                                    <div class="d-none d-md-flex flex-wrap gap-1 mb-2">
                                        @foreach($package->relatedCategories->take(2) as $category)
                                            <div class="glass-badge px-2 py-1 rounded-2 d-flex align-items-center gap-1">
                                                <span class="material-symbols-outlined" style="font-size: 12px;">
                                                    @if($category->slug == 'foodie')
                                                        ramen_dining
                                                    @elseif($category->slug == 'cultural' || $category->slug == 'history')
                                                        temple_buddhist
                                                    @elseif($category->slug == 'nature')
                                                        forest
                                                    @elseif($category->slug == 'onsen')
                                                        hot_tub
                                                    @else
                                                        category
                                                    @endif
                                                </span>
                                                <span class="fw-bold text-uppercase"
                                                    style="font-size: 8px;">{{ $category->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                    <h3 class="font-accent text-white lh-1 mb-1 planned-card-title">{{ $package->title }}
                                    </h3>
                                    <div class="d-flex align-items-center gap-1 text-light text-uppercase fw-bold mb-2 opacity-75"
                                        style="letter-spacing: 0.05em; font-size: 9px;">
                                        <span class="material-symbols-outlined text-japan-red"
                                            style="font-size: 12px;">location_on</span>
                                        <span>{{ $package->location_text }} • {{ $package->duration_days }}
                                            {{ $package->duration_days == 1 ? 'Day' : 'Days' }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="bg-japan-red px-2 py-1 rounded-pill skew-box shadow">
                                            <p class="text-white fw-black m-0 unskew-text planned-card-price">
                                                {{ convert_currency($package->base_price) }}</p>
                                        </div>
                                        <a href="{{ route('tour.show', $package->slug) }}"
                                            class="btn btn-light rounded-pill px-2 py-1 fw-black text-uppercase d-flex align-items-center gap-1 hover-red shadow-sm text-decoration-none planned-card-btn">
                                            Reserve
                                            <span
                                                class="material-symbols-outlined planned-card-btn-icon">arrow_forward</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <span class="material-symbols-outlined fs-1 text-secondary mb-3"
                                style="font-size: 80px;">travel_explore</span>
                            <h3 class="text-secondary">No tours available at the moment</h3>
                            <p class="text-secondary">Check back soon for new adventures!</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4 mt-md-5 gap-2">
                <button
                    class="btn glass-panel rounded-circle d-flex align-items-center justify-content-center shadow-sm text-secondary"
                    style="width: 44px; height: 44px;">
                    <span class="material-symbols-outlined">chevron_left</span>
                </button>
                <button
                    class="btn btn-japan rounded-circle fw-black d-flex align-items-center justify-content-center shadow"
                    style="width: 44px; height: 44px;">1</button>
                <button
                    class="btn glass-panel rounded-circle fw-black text-secondary d-flex align-items-center justify-content-center shadow-sm"
                    style="width: 44px; height: 44px;">2</button>
                <button
                    class="btn glass-panel rounded-circle d-flex align-items-center justify-content-center shadow-sm text-secondary"
                    style="width: 44px; height: 44px;">
                    <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </main>
    </div>
    </div>

    <style>
        /* Planned List Mobile Responsive — override global .tour-card height: 520px */
        .planned-tour-card {
            aspect-ratio: 1/1 !important;
            height: auto !important;
            margin-bottom: 0 !important;
        }

        .planned-card-title {
            font-size: 0.95rem;
        }

        .planned-card-price {
            font-size: 11px;
        }

        .planned-card-btn {
            font-size: 8px;
        }

        .planned-card-btn-icon {
            font-size: 11px;
        }

        @media (min-width: 768px) {
            .planned-tour-card {
                aspect-ratio: 3/4;
            }

            .planned-card-title {
                font-size: 1.4rem;
            }

            .planned-card-price {
                font-size: 14px;
            }

            .planned-card-btn {
                font-size: 10px;
            }

            .planned-card-btn-icon {
                font-size: 14px;
            }
        }

        /* Offcanvas bottom sheet styling */
        .offcanvas-bottom.rounded-top-5 {
            border-top-left-radius: 1.5rem !important;
            border-top-right-radius: 1.5rem !important;
        }
    </style>

    <!--================ Footer Area start =================-->
    @include('partials.footer')
    <!--=============== Footer Area end =================-->

</body>

</html>