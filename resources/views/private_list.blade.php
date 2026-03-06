<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<body class="bg-light">

    <!--================ Header Menu Area start =================-->
    @include('partials.header')
    <!--===============Header Menu Area =================-->

    <div class="container-fluid px-3 px-md-5 py-4 py-md-5" style="max-width: 1440px;">

        <!-- Header Section -->
        <div class="row align-items-center mb-4 mb-lg-5">
            <div class="{{ $featuredPackage ? 'col-lg-4' : 'col-12' }} mb-4 mb-lg-0">
                <div class="position-relative mb-3 mb-md-4">
                    <h1 class="private-hero-title fw-black text-dark mb-0 ls-tight"
                        style="font-family: 'Plus Jakarta Sans', sans-serif; line-height: 1.0;">
                        Exclusive <br><br>
                        Experiences
                    </h1>
                    <span class="text-japan-red position-absolute private-hero-script"
                        style="font-family: 'Rock Salt', cursive; top: 25%; left: -5px; transform: rotate(-5deg); z-index: 2; pointer-events: none; white-space: nowrap;">
                        PRIVATE
                    </span>
                </div>
                <p class="text-secondary mb-0" style="max-width: 500px; font-size: 0.95rem;">
                    Discover curated journeys designed for the discerning traveler. Uncover the hidden soul of Japan.
                </p>

                <!-- Filter Pills — horizontal scroll on mobile -->
                <div class="private-filter-pills d-flex gap-2 mt-3 mt-md-4">
                    <a href="{{ route('private.list') }}"
                        class="btn rounded-pill px-3 py-2 fw-bold small text-uppercase flex-shrink-0 {{ !request('category') || request('category') == 'all' ? 'btn-japan text-white shadow' : 'bg-white text-secondary border shadow-sm' }}"
                        style="font-size: 10px; letter-spacing: 0.05em;">
                        All
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('private.list', ['category' => $category->id]) }}"
                            class="btn rounded-pill px-3 py-2 fw-bold small text-uppercase flex-shrink-0 {{ request('category') == $category->id ? 'btn-japan text-white shadow' : 'bg-white text-secondary border shadow-sm' }}"
                            style="font-size: 10px; letter-spacing: 0.05em;">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            @if($featuredPackage)
                <div class="col-lg-8">
                    <div class="tour-card shadow-lg rounded-4 overflow-hidden position-relative private-featured-card">
                        <!-- Background Image -->
                        <div class="position-absolute w-100 h-100">
                            <div class="tour-bg-image h-100 w-100"
                                style="background-image: url('{{ $featuredPackage->getFirstMediaUrl('primary_image', app_setting('default_tour_image', 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=2070&auto=format&fit=crop')) }}'); background-size: cover; background-position: center;">
                            </div>
                            <div class="position-absolute top-0 start-0 w-100 h-100"
                                style="background: linear-gradient(to top, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.1) 100%);">
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="position-relative h-100 d-flex flex-column justify-content-end p-3 p-md-5 text-white"
                            style="max-width: 600px;">
                            <div class="mb-2 mb-md-3">
                                <span class="bg-japan-red text-white px-2 px-md-3 py-1 rounded-pill fw-bold text-uppercase"
                                    style="font-size: 9px; letter-spacing: 0.1em;">
                                    Signature Collection
                                </span>
                            </div>
                            <h2 class="private-featured-title fw-bold mb-2 mb-md-3 text-white">{{ $featuredPackage->title }}
                            </h2>
                            <p class="mb-3 mb-md-4 opacity-75 d-none d-md-block" style="font-size: 0.95rem;">
                                {{ \Illuminate\Support\Str::limit(strip_tags($featuredPackage->description), 150) }}
                            </p>

                            <div class="d-flex align-items-center gap-3 gap-md-4 mb-3 mb-md-4 text-uppercase fw-bold opacity-75"
                                style="font-size: 10px; letter-spacing: 0.05em;">
                                <div class="d-flex align-items-center gap-1">
                                    <span class="material-symbols-outlined text-japan-red"
                                        style="font-size: 16px;">location_on</span>
                                    {{ $featuredPackage->location_text }}
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="material-symbols-outlined text-japan-red"
                                        style="font-size: 16px;">schedule</span>
                                    {{ $featuredPackage->duration_days }} Days
                                </div>
                            </div>

                            <a href="{{ route('tour.show', $featuredPackage->slug) }}"
                                class="btn btn-light rounded-pill px-3 px-md-4 py-2 fw-bold text-uppercase d-inline-flex align-items-center gap-2 text-dark shadow-sm"
                                style="width: fit-content; font-size: 12px;">
                                View Experience
                                <span class="material-symbols-outlined" style="font-size: 16px;">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Experience Grid -->
        <div class="row g-3 g-md-4 mb-4 mb-md-5">
            @forelse($packages as $package)
                <div class="col-6 col-md-6 col-lg-6">
                    <div class="tour-card shadow-sm rounded-4 overflow-hidden position-relative private-exp-card"
                        style="max-width: 100%; margin-bottom: 0 !important;">
                        <!-- Background -->
                        <div class="position-absolute w-100 h-100" style="top: 0; left: 0;">
                            <div class="tour-bg-image h-100 w-100"
                                style="background-image: url('{{ $package->getFirstMediaUrl('primary_image', app_setting('default_tour_image')) }}'); background-size: cover; background-position: center;">
                            </div>
                            <div class="tour-overlay"></div>
                        </div>

                        <!-- Content Overlay -->
                        <div class="position-absolute w-100 h-100 p-2 p-md-4 d-flex flex-column justify-content-between"
                            style="top: 0; left: 0; z-index: 2;">

                            <!-- Top Row: Badge & Heart -->
                            <div class="d-flex justify-content-between align-items-start">
                                <span
                                    class="badge {{ $package->type == 'open' ? 'bg-white text-japan-red' : 'bg-dark text-white' }} rounded-pill px-2 py-1 fw-black text-uppercase shadow-sm private-card-badge">
                                    {{ $package->type == 'open' ? 'Open Group' : ($package->type == 'activity' ? 'Activity' : 'Private') }}
                                </span>
                                <button
                                    class="btn glass-badge rounded-circle p-1 d-flex align-items-center justify-content-center shadow-sm text-white border-0 private-card-heart"
                                    style="background: rgba(255,255,255,0.2); backdrop-filter: blur(5px);">
                                    <span class="material-symbols-outlined private-card-heart-icon">favorite</span>
                                </button>
                            </div>

                            <!-- Bottom Content -->
                            <div class="mt-auto">
                                <!-- Categories — hidden on mobile -->
                                <div class="d-none d-md-flex flex-wrap gap-1 mb-2">
                                    @foreach($package->relatedCategories->take(2) as $category)
                                        <div class="glass-badge px-2 py-1 rounded-2 d-flex align-items-center gap-1">
                                            <span class="material-symbols-outlined" style="font-size: 12px;">
                                                @if($category->slug == 'foodie') ramen_dining
                                                @elseif($category->slug == 'cultural' || $category->slug == 'history')
                                                    temple_buddhist
                                                @elseif($category->slug == 'nature') forest
                                                @elseif($category->slug == 'onsen') hot_tub
                                                @else category
                                                @endif
                                            </span>
                                            <span class="fw-bold text-uppercase"
                                                style="font-size: 9px;">{{ $category->name }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Title -->
                                <h3 class="font-accent text-white lh-1 mb-1 private-card-title">
                                    {{ $package->title }}
                                </h3>

                                <!-- Location -->
                                <div
                                    class="d-flex align-items-center gap-1 text-light text-uppercase fw-bold mb-2 opacity-75 private-card-location">
                                    <span
                                        class="material-symbols-outlined text-japan-red private-card-loc-icon">location_on</span>
                                    <span>{{ $package->location_text }} • {{ $package->duration_days }}
                                        {{ $package->duration_days == 1 ? 'Day' : 'Days' }}</span>
                                </div>

                                <!-- Price & Button -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="d-none d-md-block small text-white text-uppercase fw-bold opacity-75"
                                            style="font-size: 10px;">From</span>
                                        <span class="fw-black text-white m-0 private-card-price"
                                            style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                                            {{ convert_currency($package->base_price) }}
                                        </span>
                                    </div>
                                    <a href="{{ route('tour.show', $package->slug) }}"
                                        class="btn btn-light rounded-pill fw-black text-uppercase d-flex align-items-center gap-1 hover-red shadow-sm text-decoration-none private-card-btn">
                                        <span class="d-none d-sm-inline">Details</span>
                                        <span class="material-symbols-outlined private-card-btn-icon">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                @if(!$featuredPackage)
                    <div class="col-12 py-5 text-center">
                        <span class="material-symbols-outlined fs-1 text-secondary mb-3"
                            style="font-size: 60px;">travel_explore</span>
                        <p class="lead text-secondary">No private experiences found.</p>
                    </div>
                @endif
            @endforelse
        </div>

        <!-- Load More Button -->
        <div class="text-center mb-4 mb-md-5">
            <button
                class="btn btn-outline-dark rounded-pill px-4 px-md-5 py-2 py-md-3 fw-bold text-uppercase small shadow-sm"
                style="letter-spacing: 0.1em;">
                Load More <span class="d-none d-sm-inline">Experiences</span>
                <span class="material-symbols-outlined align-middle fs-6 ms-1">expand_more</span>
            </button>
        </div>

    </div>

    <!-- Concierge Service Section -->
    <div class="bg-dark text-white py-4 py-md-5">
        <div class="container-fluid px-3 px-md-5" style="max-width: 1440px;">
            <div class="row align-items-center g-4 g-lg-5">
                <!-- Text Content -->
                <div class="col-lg-6">
                    <span
                        class="bg-japan-red text-white px-2 py-1 rounded-1 fw-bold text-uppercase small mb-3 d-inline-block"
                        style="font-size: 10px; letter-spacing: 0.1em;">
                        Request Trip
                    </span>
                    <h2 class="private-concierge-title fw-bold mb-3 mb-md-4 text-white">Need a Personalized Itinerary?
                    </h2>
                    <p class="opacity-75 mb-3 mb-md-4" style="font-size: 0.95rem;">
                        Can't find exactly what you're looking for? Our specialists can craft a bespoke
                        experience tailored to your interests and schedule.
                    </p>

                    <div class="d-flex flex-column gap-2 gap-md-3 mb-3 mb-md-4">
                        <div class="d-flex align-items-center gap-2 gap-md-3">
                            <span class="material-symbols-outlined text-japan-red bg-white rounded-circle p-1"
                                style="font-size: 18px;">check</span>
                            <span class="fw-medium small">Fully customizable schedules</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 gap-md-3">
                            <span class="material-symbols-outlined text-japan-red bg-white rounded-circle p-1"
                                style="font-size: 18px;">check</span>
                            <span class="fw-medium small">Access to exclusive venues</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 gap-md-3">
                            <span class="material-symbols-outlined text-japan-red bg-white rounded-circle p-1"
                                style="font-size: 18px;">check</span>
                            <span class="fw-medium small">Private multilingual guides</span>
                        </div>
                    </div>
                </div>

                <!-- Request Form -->
                <div class="col-lg-6">
                    <div class="bg-white text-dark rounded-4 p-3 p-md-5 shadow-lg">
                        <h3 class="h5 fw-bold mb-3 mb-md-4">Request a Quote</h3>
                        <form action="#">
                            <div class="row g-2 g-md-3">
                                <div class="col-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-light border-0" id="firstName"
                                            placeholder="First Name">
                                        <label for="firstName" class="text-secondary small">First Name</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-light border-0" id="lastName"
                                            placeholder="Last Name">
                                        <label for="lastName" class="text-secondary small">Last Name</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="email" class="form-control bg-light border-0" id="email"
                                            placeholder="Email Address">
                                        <label for="email" class="text-secondary small">Email Address</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control bg-light border-0"
                                            placeholder="Tell us what you would like to experience..." id="experience"
                                            style="height: 100px"></textarea>
                                        <label for="experience" class="text-secondary small">Dream Experience</label>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <button type="submit"
                                        class="btn btn-japan w-100 py-3 rounded-pill fw-bold text-uppercase shadow">
                                        Send Request
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* === Private List Mobile Responsive === */

        /* Hero title */
        .private-hero-title {
            font-size: 2rem;
        }

        .private-hero-script {
            font-size: 1.8rem;
        }

        /* Featured card */
        .private-featured-card {
            height: 280px;
        }

        .private-featured-title {
            font-size: 1.2rem;
        }

        /* Experience cards — override global .tour-card height: 520px */
        .private-exp-card {
            aspect-ratio: 1/1 !important;
            height: auto !important;
            margin-bottom: 0 !important;
        }

        .private-card-title {
            font-size: 0.95rem;
        }

        .private-card-badge {
            font-size: 7px !important;
            letter-spacing: 0.05em;
        }

        .private-card-heart {
            width: 26px;
            height: 26px;
        }

        .private-card-heart-icon {
            font-size: 14px;
        }

        .private-card-location {
            font-size: 8px;
            letter-spacing: 0.03em;
        }

        .private-card-loc-icon {
            font-size: 11px;
        }

        .private-card-price {
            font-size: 13px;
        }

        .private-card-btn {
            font-size: 0;
            padding: 6px 8px;
        }

        .private-card-btn-icon {
            font-size: 14px;
        }

        /* Concierge */
        .private-concierge-title {
            font-size: 1.4rem;
        }

        /* Filter pills horizontal scroll on mobile */
        .private-filter-pills {
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding-bottom: 4px;
        }

        .private-filter-pills::-webkit-scrollbar {
            display: none;
        }

        @media (min-width: 768px) {
            .private-hero-title {
                font-size: 3.5rem;
            }

            .private-hero-script {
                font-size: 3.5rem;
            }

            .private-featured-card {
                height: 500px;
            }

            .private-featured-title {
                font-size: 2.5rem;
            }

            .private-exp-card {
                aspect-ratio: 4/3;
            }

            .private-card-title {
                font-size: 1.5rem;
            }

            .private-card-badge {
                font-size: 10px !important;
            }

            .private-card-heart {
                width: 32px;
                height: 32px;
            }

            .private-card-heart-icon {
                font-size: 18px;
            }

            .private-card-location {
                font-size: 11px;
                letter-spacing: 0.05em;
            }

            .private-card-loc-icon {
                font-size: 16px;
            }

            .private-card-price {
                font-size: 1.5rem;
            }

            .private-card-btn {
                font-size: 11px;
                padding: 8px 16px;
            }

            .private-card-btn-icon {
                font-size: 16px;
            }

            .private-concierge-title {
                font-size: 2.5rem;
            }

            .private-filter-pills {
                flex-wrap: wrap;
                overflow-x: visible;
            }
        }
    </style>

    <!--================ Footer Area start =================-->
    @include('partials.footer')
    <!--=============== Footer Area end =================-->

</body>

</html>