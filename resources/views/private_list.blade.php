<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<body class="bg-light" style="font-family: 'Inter', sans-serif;">

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
        <div class="row g-4 mb-5">
            @forelse($packages as $package)
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route('tour.show', $package->slug) }}" class="text-decoration-none">
                        <div class="card premium-exp-card border-0 rounded-4 overflow-hidden shadow-sm h-100"
                            style="background: #111;">
                            <!-- Image container -->
                            <div class="premium-exp-img-wrapper position-relative w-100"
                                style="aspect-ratio: 4/5; overflow: hidden;">
                                <div class="premium-exp-bg h-100 w-100"
                                    style="background-image: url('{{ $package->getFirstMediaUrl('primary_image', app_setting('default_tour_image')) }}'); background-size: cover; background-position: center; transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);">
                                </div>
                                <div class="premium-exp-overlay position-absolute w-100 h-100"
                                    style="top:0; left:0; background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0.1) 100%); transition: opacity 0.4s ease;">
                                </div>

                                <!-- Floating Badge -->
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span
                                        class="badge bg-white text-dark rounded-pill px-3 py-2 fw-bold text-uppercase shadow-sm"
                                        style="font-size: 0.7rem; letter-spacing: 0.1em;">
                                        {{ $package->type == 'activity' ? 'Activity' : 'Private Edition' }}
                                    </span>
                                </div>

                                <!-- Floating Heart -->
                                <div class="position-absolute top-0 end-0 m-3">
                                    <button
                                        class="btn btn-light rounded-circle p-2 d-flex align-items-center justify-content-center shadow text-secondary premium-heart-btn"
                                        style="width: 36px; height: 36px; transition: all 0.3s ease;">
                                        <span class="material-symbols-outlined" style="font-size: 18px;">favorite</span>
                                    </button>
                                </div>

                                <!-- Content anchored to bottom -->
                                <div class="position-absolute bottom-0 start-0 w-100 p-4">
                                    <!-- Location & Duration -->
                                    <div class="d-flex align-items-center gap-2 text-white-50 text-uppercase fw-semibold mb-2"
                                        style="font-size: 0.75rem; letter-spacing: 0.05em;">
                                        <span class="material-symbols-outlined text-japan-red"
                                            style="font-size: 14px;">location_on</span>
                                        <span>{{ $package->location_text }}</span>
                                        <span class="mx-1">•</span>
                                        <span>{{ $package->duration_days }}
                                            {{ $package->duration_days == 1 ? 'Day' : 'Days' }}</span>
                                    </div>

                                    <!-- Title -->
                                    <h3 class="text-white fw-bold mb-3 premium-card-title lh-sm"
                                        style="font-family: 'Playfair Display', serif; font-size: 1.6rem;">
                                        {{ $package->title }}
                                    </h3>

                                    <!-- Price and Explore Line -->
                                    <div
                                        class="d-flex justify-content-between align-items-end mt-4 pt-3 border-top border-secondary border-opacity-25">
                                        <div>
                                            <span class="d-block text-white-50 text-uppercase fw-semibold mb-1"
                                                style="font-size: 0.65rem; letter-spacing: 0.1em;">Starting At</span>
                                            <span
                                                class="text-white fw-bold fs-5">{{ convert_currency($package->base_price) }}</span>
                                        </div>
                                        <span
                                            class="premium-explore-link text-white text-uppercase fw-bold d-flex align-items-center gap-2"
                                            style="font-size: 0.75rem; letter-spacing: 0.1em;">
                                            Explore <span
                                                class="material-symbols-outlined fs-5 transition-transform">arrow_forward</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 py-5 my-5 text-center">
                    <span class="material-symbols-outlined text-muted opacity-50 mb-4"
                        style="font-size: 80px;">trip_origin</span>
                    <h3 class="fw-bold text-dark mb-2">No Curated Experiences Found</h3>
                    <p class="text-secondary">Try selecting a different category or contact our concierge.</p>
                </div>
            @endforelse
        </div>

        <!-- Load More Section if needed -->
        @if(count($packages) > 0)
            <div class="text-center pb-5">
                <button
                    class="btn btn-outline-dark rounded-pill px-5 py-3 fw-bold text-uppercase shadow-sm premium-hover-btn"
                    style="letter-spacing: 0.15em; font-size: 0.85rem;">
                    Load More Experiences
                </button>
            </div>
        @endif
    </div>

    <!-- Concierge VIP Section -->
    <div class="vip-concierge-section position-relative py-5 my-5">
        <div class="position-absolute w-100 h-100" style="top:0; left:0; background: #0a0a0a; z-index: -2;"></div>
        <!-- Decorative Japanese Pattern Background (Optional texture) -->
        <div class="position-absolute w-100 h-100 opacity-10"
            style="top:0; left:0; background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px; z-index: -1;">
        </div>

        <div class="container-fluid px-3 px-md-5" style="max-width: 1440px;">
            <div class="row g-5 align-items-center justify-content-between">
                <!-- Text Intro -->
                <div class="col-lg-5 text-white pe-lg-5">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width: 40px; height: 1px; background: #CE1126;"></div>
                        <span class="text-japan-red text-uppercase fw-bold"
                            style="letter-spacing: 0.2em; font-size: 0.8rem;">Waku Concierge</span>
                    </div>
                    <h2 class="display-5 fw-bold mb-4 text-white" style="font-family: 'Playfair Display', serif;">Craft
                        Your Dream Itinerary</h2>
                    <p class="text-white-90 mb-5 fs-5 fw-light lh-lg">
                        Step beyond the ordinary. Share your desires, and our luxury travel designers will sculpt a
                        bespoke journey through Japan that matches your exact pace, interests, and style.
                    </p>

                    <ul class="list-unstyled mb-0 d-flex flex-column gap-3 text-white-90 fw-light">
                        <li class="d-flex align-items-center gap-3">
                            <span class="material-symbols-outlined text-japan-red fs-4">diamond</span>
                            Exclusive access to hidden temples and private estates.
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <span class="material-symbols-outlined text-japan-red fs-4">restaurant_menu</span>
                            Reservations at impossible-to-book Michelin star restaurants.
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <span class="material-symbols-outlined text-japan-red fs-4">directions_car</span>
                            Seamless private transfers and elite bilingual guides.
                        </li>
                    </ul>
                </div>

                <!-- The Form Card -->
                <div class="col-lg-6 col-xl-5">
                    <div class="card border-0 rounded-4 p-4 p-md-5 shadow-lg position-relative overflow-hidden"
                        style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(30px); border: 1px solid rgba(255,255,255,0.1) !important;">
                        <!-- Red glow behind form -->
                        <div class="position-absolute bg-japan-red rounded-circle"
                            style="width: 150px; height: 150px; top: -50px; right: -50px; filter: blur(100px); opacity: 0.5; z-index: -1;">
                        </div>

                        <h3 class="text-white fw-bold mb-4 fs-4">Request a Consultation</h3>
                        <form action="#">
                            <div class="row g-4">
                                <div class="col-sm-6">
                                    <label class="form-label text-white small text-uppercase fw-semibold"
                                        style="letter-spacing: 0.1em;">First Name</label>
                                    <input type="text"
                                        class="form-control premium-input bg-transparent text-white border-bottom border-0 border-secondary border-opacity-50 rounded-0 px-0 shadow-none"
                                        placeholder="John">
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label text-white small text-uppercase fw-semibold"
                                        style="letter-spacing: 0.1em;">Last Name</label>
                                    <input type="text"
                                        class="form-control premium-input bg-transparent text-white border-bottom border-0 border-secondary border-opacity-50 rounded-0 px-0 shadow-none"
                                        placeholder="Doe">
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-white small text-uppercase fw-semibold"
                                        style="letter-spacing: 0.1em;">Email Address</label>
                                    <input type="email"
                                        class="form-control premium-input bg-transparent text-white border-bottom border-0 border-secondary border-opacity-50 rounded-0 px-0 shadow-none"
                                        placeholder="john@example.com">
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-white small text-uppercase fw-semibold"
                                        style="letter-spacing: 0.1em;">Your Vision</label>
                                    <textarea
                                        class="form-control premium-input bg-transparent text-white border-bottom border-0 border-secondary border-opacity-50 rounded-0 px-0 shadow-none"
                                        placeholder="Tell us about your dream trip..."
                                        style="height: 60px; resize: none;"></textarea>
                                </div>
                                <div class="col-12 mt-5">
                                    <button type="submit"
                                        class="btn btn-japan w-100 py-3 rounded-0 fw-bold text-uppercase text-white letter-spacing border-0 premium-hover-btn"
                                        style="letter-spacing: 0.15em;">
                                        Submit Request
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Premium Styles -->
    <style>
        /* Typography specifics */
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&display=swap');

        /* === Private List Mobile Responsive (Restored for Hero) === */

        .private-hero-title {
            font-size: 2rem;
        }

        .private-hero-script {
            font-size: 1.8rem;
        }

        .private-featured-card {
            height: 280px;
        }

        .private-featured-title {
            font-size: 1.2rem;
        }

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

            .private-filter-pills {
                flex-wrap: wrap;
                overflow-x: visible;
            }
        }


        /* Filter Pills Scroll Hiding */
        .premium-filter-bar::-webkit-scrollbar {
            display: none;
        }

        .premium-filter-bar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .hover-dark:hover {
            color: #111 !important;
            background-color: #f8f9fa !important;
        }

        /* Card Animations */
        .premium-exp-card {
            transition: all 0.4s ease;
            transform: translateY(0);
        }

        .premium-exp-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15) !important;
        }

        .premium-exp-card:hover .premium-exp-bg {
            transform: scale(1.1);
        }

        .premium-explore-link span {
            transition: transform 0.3s ease;
        }

        .premium-exp-card:hover .premium-explore-link span {
            transform: translateX(4px);
            color: #CE1126;
            /* Japan Red */
        }

        .premium-heart-btn:hover {
            background-color: #CE1126 !important;
            color: white !important;
            transform: scale(1.1);
        }

        /* Form Inputs */
        .premium-input:focus {
            outline: none !important;
            box-shadow: none !important;
            border-color: #CE1126 !important;
        }

        .premium-input::placeholder {
            color: rgba(255, 255, 255, 0.2) !important;
            font-weight: 300;
        }

        /* Hover Buttons */
        .premium-hover-btn {
            position: relative;
            overflow: hidden;
        }

        .premium-hover-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .premium-hover-btn:hover::after {
            left: 100%;
        }

        /* Hero text animation hook */
        .premium-hero-bg {
            animation: slowZoom 20s infinite alternate linear;
        }

        @keyframes slowZoom {
            0% {
                transform: scale(1);
            }

            100% {
                transform: scale(1.1);
            }
        }
    </style>

    <!--================ Footer Area start =================-->
    @include('partials.footer')
    <!--=============== Footer Area end =================-->

</body>

</html>