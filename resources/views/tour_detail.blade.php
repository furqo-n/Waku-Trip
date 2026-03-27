<!DOCTYPE html>
<html lang="en">

@php
    $page_title = $package->title . ' | Waku Trip Japan Tours';
    $meta_description = Str::limit(strip_tags($package->description ?? 'Discover ' . $package->title . ' – a curated Japan tour experience by Waku Trip.'), 155);
@endphp
@include('partials.head', ['page_title' => $page_title, 'meta_description' => $meta_description])

<body class="bg-shape">

    <!--================ Header Menu Area start =================-->
    @include('partials.header')
    <!--===============Header Menu Area =================-->

    <main class="container py-4 py-md-5" style="max-width: 1280px;">

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb small fw-bold">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"
                        class="text-japan-red text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/planned_list') }}"
                        class="text-japan-red text-decoration-none">Tours</a></li>
                <li class="breadcrumb-item active text-dark" aria-current="page">{{ $package->title }}</li>
            </ol>
        </nav>

        <div class="d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-4 mb-5">
            <div>
                <h1 class="display-4 fw-black text-dark mb-3 lh-1">{{ $package->title }}</h1>
                <div class="d-flex flex-wrap align-items-center gap-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="d-flex text-japan-red">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($averageRating))
                                    <span class="material-symbols-outlined filled fs-5">star</span>
                                @else
                                    <span class="material-symbols-outlined fs-5">star_outline</span>
                                @endif
                            @endfor
                        </div>
                        <span class="fw-bold text-dark">{{ number_format($averageRating, 1) }}</span>
                        <a href="#reviews" class="text-secondary small text-decoration-underline">({{ $reviewCount }}
                            reviews)</a>
                    </div>
                    <div
                        class="d-flex align-items-center gap-2 px-3 py-1 bg-white border border-soft rounded-pill shadow-sm">
                        <span class="material-symbols-outlined text-japan-red fs-5">location_on</span>
                        <span class="small fw-bold text-secondary">{{ $package->location_text }}</span>
                    </div>
                    <div
                        class="d-flex align-items-center gap-2 px-3 py-1 bg-white border border-soft rounded-pill shadow-sm">
                        <i class="bi bi-cloud-sun text-danger me-2 fs-3"></i>
                        <span class="small fw-bold text-secondary">{{ $package->season }}</span>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-3">
                <button
                    class="btn btn-white border border-soft rounded-circle p-2 d-flex align-items-center justify-content-center shadow-sm"
                    style="width: 48px; height: 48px;">
                    <span class="material-symbols-outlined text-secondary">favorite</span>
                </button>
                <button
                    class="btn btn-white border border-soft rounded-circle p-2 d-flex align-items-center justify-content-center shadow-sm"
                    style="width: 48px; height: 48px;">
                    <span class="material-symbols-outlined text-secondary">ios_share</span>
                </button>
            </div>
        </div>

        <div class="mb-5 shadow-sm rounded-4 overflow-hidden">
            <div class="row g-2 tour-gallery-height">
                <!-- Primary Image -->
                <div class="col-lg-6 h-100">
                    <div class="h-100 w-100 position-relative gallery-item-hover" onclick="openLightbox(this)">
                        @if($primary = $package->getFirstMediaUrl('primary_image'))
                            <img src="{{ $primary }}" class="w-100 h-100 object-fit-cover" alt="{{ $package->title }}"
                                fetchpriority="high" decoding="async">
                        @else
                            <img src="{{ app_setting('default_tour_image') }}" class="w-100 h-100 object-fit-cover"
                                alt="No Image">
                        @endif
                    </div>
                </div>

                <!-- Secondary Images -->
                <div class="col-lg-6 h-100">
                    <div class="row g-2 h-100">
                        @php $galleryCount = $package->getMedia('gallery')->count(); @endphp
                        @foreach($package->getMedia('gallery')->take(4) as $image)
                            <div class="col-6 h-50">
                                <div class="h-100 w-100 position-relative gallery-item-hover" onclick="openLightbox(this)">
                                    <img src="{{ $image->getOptimizedUrl(600, 600) }}" class="w-100 h-100 object-fit-cover"
                                        alt="Gallery Image" loading="lazy" decoding="async">
                                    @if($loop->last && $galleryCount > 4)
                                        <div
                                            class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center">
                                            <button
                                                class="btn btn-light btn-sm rounded-pill fw-bold d-flex align-items-center gap-2"
                                                onclick="event.stopPropagation(); openLightbox(0);">
                                                <span class="material-symbols-outlined fs-6">grid_view</span> Show all photos
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-lg-8">

                <div class="bg-white rounded-4 border border-soft p-4 shadow-sm mb-5">
                    <div class="row row-cols-2 row-cols-md-4 g-3">
                        <div class="col">
                            <span class="d-block text-secondary small fw-bold text-uppercase mb-1">Duration</span>
                            <div class="d-flex align-items-center gap-2 fw-bold text-dark">
                                <span class="material-symbols-outlined text-japan-red">schedule</span>
                                {{ $package->duration_days }} Days
                            </div>
                        </div>
                        <div class="col">
                            <span class="d-block text-secondary small fw-bold text-uppercase mb-1">Group Size</span>
                            <div class="d-flex align-items-center gap-2 fw-bold text-dark">
                                <span class="material-symbols-outlined text-japan-red">group</span>
                                {{ $package->group_size ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col">
                            <span class="d-block text-secondary small fw-bold text-uppercase mb-1">Language</span>
                            <div class="d-flex align-items-center gap-2 fw-bold text-dark">
                                <span class="material-symbols-outlined text-japan-red">translate</span>
                                {{ $package->language ?? 'English' }}
                            </div>
                        </div>
                        <div class="col">
                            <span class="d-block text-secondary small fw-bold text-uppercase mb-1">Type</span>
                            <div class="d-flex align-items-center gap-2 fw-bold text-dark">
                                <span
                                    class="material-symbols-outlined text-japan-red">{{ $package->is_guided ? 'hiking' : 'map' }}</span>
                                {{ $package->is_guided ? 'Guided' : 'Self-Guided' }}
                            </div>
                        </div>
                    </div>
                </div>

                <section class="mb-5">
                    <h2 class="h3 fw-bold mb-4 d-flex align-items-center gap-3">
                        Experience the Magic
                        <span class="flex-fill bg-light rounded" style="height: 2px;"></span>
                    </h2>
                    <div class="text-secondary fs-5 lh-lg">
                        <p class="mb-3">{!! clean($package->description) !!}</p>
                    </div>
                </section>

                <section class="mb-5">
                    <h2 class="h3 fw-bold mb-5 d-flex align-items-center gap-3">
                        Daily Itinerary
                        <span class="flex-fill bg-light rounded" style="height: 2px;"></span>
                    </h2>

                    <div class="accordion accordion-flush" id="itineraryAccordion">
                        @foreach($package->itineraries as $index => $itinerary)
                            <div class="accordion-item border-0 mb-3 bg-transparent itinerary-day-item {{ $index >= 3 ? 'd-none additional-day' : '' }}">
                                <h3 class="accordion-header" id="heading-{{ $itinerary->id }}">
                                    <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }} rounded-4 shadow-sm border border-soft bg-white px-4 py-3 d-flex align-items-center gap-3 hover-bg-light transition-all" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $itinerary->id }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="collapse-{{ $itinerary->id }}">
                                        <div class="d-flex align-items-center gap-3 flex-grow-1">
                                            <span class="badge {{ $index === 0 ? 'bg-japan-red text-white' : 'bg-light text-secondary border' }} fs-6 px-3 py-2 rounded-pill">
                                                Day {{ $itinerary->day_number }}
                                            </span>
                                            <span class="h5 fw-bold mb-0 text-dark">{{ $itinerary->title }}</span>
                                        </div>
                                    </button>
                                </h3>
                                <div id="collapse-{{ $itinerary->id }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" aria-labelledby="heading-{{ $itinerary->id }}" data-bs-parent="#itineraryAccordion">
                                    <div class="accordion-body px-4 py-4 ms-3 ms-md-5 border-start border-2 border-danger border-opacity-25 mt-2">
                                        <div class="row g-4">
                                            <div class="col-md-{{ $itinerary->image_url ? '8' : '12' }}">
                                                <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                                                    @php
                                                        $itineraryItems = $itinerary->items->count() > 0 
                                                            ? $itinerary->items 
                                                            : collect(explode("\n", $itinerary->description))
                                                                ->map(fn($l) => trim($l))
                                                                ->filter()
                                                                ->map(fn($l) => (object)['content' => $l, 'image_url' => null]);
                                                    @endphp
                                                    
                                                    @foreach($itineraryItems as $item)
                                                        <li class="d-flex flex-column gap-2 text-secondary mb-2">
                                                            <div class="d-flex gap-3 align-items-start">
                                                                <span class="material-symbols-outlined text-japan-red fs-5 mt-1">location_on</span>
                                                                <div class="lh-lg itinerary-item-content">{!! clean($item->content) !!}</div>
                                                            </div>
                                                            @if(!empty($item->image_url))
                                                                <div class="ms-5 ps-1 mt-1">
                                                                    <img src="{{ $item->image_url }}" 
                                                                        class="img-fluid rounded-3 shadow-sm border border-light" 
                                                                        style="max-height: 200px; width: auto;" 
                                                                        alt="Activity" loading="lazy">
                                                                </div>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @if($itinerary->image_url)
                                                <div class="col-md-4">
                                                    <img src="{{ $itinerary->image_url }}" class="img-fluid rounded-4 shadow-sm object-fit-cover w-100" style="max-height: 250px;" alt="Day {{ $itinerary->day_number }}" loading="lazy" decoding="async">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($package->itineraries->count() > 3)
                        <div class="ps-3 pt-3 text-center">
                            <button id="toggle-itinerary-btn" class="btn btn-outline-danger fw-bold rounded-pill px-4 py-2 d-inline-flex align-items-center gap-2 transition-all">
                                <span>Show full detailed itinerary</span> <span class="material-symbols-outlined">expand_more</span>
                            </button>
                        </div>
                    @endif
                </section>

                <section class="bg-white rounded-4 border border-soft p-4 shadow-sm mb-5">
                    <h3 class="fw-bold mb-4">What's Included</h3>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                                @foreach($package->inclusions->where('is_included', true) as $inclusion)
                                    <li class="d-flex gap-3 text-secondary">
                                        <span class="material-symbols-outlined text-success">check_circle</span>
                                        {{ $inclusion->item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled d-flex flex-column gap-3 mb-0 opacity-75">
                                @foreach($package->inclusions->where('is_included', false) as $exclusion)
                                    <li class="d-flex gap-3 text-secondary">
                                        <span class="material-symbols-outlined text-danger">cancel</span>
                                        {{ $exclusion->item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </section>

                <section>
                    <h3 class="fw-bold mb-4">Frequently Asked Questions</h3>
                    <div class="accordion" id="faqAccordion">
                        <!-- Placeholder FAQs as these are not in DB yet -->
                        <div class="accordion-item border border-soft rounded-3 mb-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold text-dark collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Is this tour suitable for children?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-secondary">
                                    Yes, children aged 6 and above are welcome on this tour. The walking pace is
                                    moderate.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border border-soft rounded-3 mb-3 overflow-hidden">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold text-dark collapsed" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faq2">
                                    What is the cancellation policy?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-secondary">
                                    Free cancellation up to 30 days before tour start date.
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!--payment card -->
            <aside class="col-lg-4 d-none d-lg-block">
                <div class="sticky-booking bg-white rounded-4 border border-soft p-4 shadow-lg">
                    <div class="mb-4">
                        <div class="d-flex align-items-end gap-2 mb-2">
                            <span class="small text-secondary fw-bold mb-1">From</span>
                            <h2 id="display-price" class="fw-black text-japan-red mb-0">
                                {{ convert_currency($startingPrice) }}
                            </h2>
                            <span class="small text-secondary fw-bold mb-1">{{ $currentCurrency }} / person</span>
                        </div>

                        @if($availableSchedules->count() > 0)
                            <span
                                class="badge bg-success bg-opacity-10 text-success d-inline-flex align-items-center gap-1">
                                <span class="material-symbols-outlined fs-6">savings</span> Best price dates available
                            </span>
                        @endif
                    </div>

                    <div class="d-flex flex-column gap-3 mb-4">
                        {{-- Open Group / Private Toggle --}}
                        <div class="bg-light p-1 rounded-pill d-flex" id="trip-type-toggle">
                            <button type="button" data-type="open"
                                class="btn-trip-type btn {{ $package->type == 'open' ? 'btn-white shadow-sm' : 'btn-transparent text-secondary' }} rounded-pill flex-fill fw-bold small"
                                {{ in_array($package->type, ['private', 'activity']) ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                                Open Group
                            </button>
                            <button type="button" data-type="private"
                                class="btn-trip-type btn {{ in_array($package->type, ['private', 'activity']) ? 'btn-white shadow-sm' : 'btn-transparent text-secondary' }} rounded-pill flex-fill fw-bold small"
                                {{ $package->type == 'open' ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                                {{ $package->type == 'activity' ? 'Activity' : 'Private' }}
                            </button>
                        </div>

                        {{-- Date Selector --}}
                        <div>
                            <label for="schedule-select"
                                class="small fw-bold text-secondary text-uppercase mb-1 ms-1">Select Date</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-soft">
                                    <span class="material-symbols-outlined text-japan-red">calendar_month</span>
                                </span>
                                <select id="schedule-select" class="form-select bg-light border-soft fw-bold text-dark">
                                    @forelse($availableSchedules as $schedule)
                                        <option value="{{ $schedule->id }}" data-price="{{ $schedule->price }}"
                                            data-seats="{{ $schedule->available_seats }}">
                                            {{ $schedule->start_date->format('M d') }} -
                                            {{ $schedule->end_date->format('M d, Y') }}
                                            ({{ convert_currency($schedule->price) }})
                                        </option>
                                    @empty
                                        <option disabled selected>No dates available</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>

                        {{-- Guest Counter --}}
                        <div>
                            <label class="small fw-bold text-secondary text-uppercase mb-1 ms-1">Guests</label>
                            <div
                                class="d-flex align-items-center justify-content-between p-2 px-3 bg-light border border-soft rounded-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="material-symbols-outlined text-japan-red">person</span>
                                    <span id="guest-label" class="fw-bold">1 Adult</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" id="guest-minus"
                                        class="btn btn-sm btn-secondary rounded-circle p-0 d-flex align-items-center justify-content-center"
                                        style="width: 28px; height: 28px; font-size: 16px; line-height: 1;">−</button>
                                    <span id="guest-count" class="fw-bold"
                                        style="min-width: 20px; text-align: center;">1</span>
                                    <button type="button" id="guest-plus"
                                        class="btn btn-sm btn-japan rounded-circle p-0 d-flex align-items-center justify-content-center"
                                        style="width: 28px; height: 28px; font-size: 16px; line-height: 1;">+</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Price Breakdown --}}
                    <div id="price-breakdown" class="mb-2">
                        <div class="d-flex justify-content-between py-2 small text-secondary">
                            <span id="price-detail-label">{{ $currencySymbol }}0 × 1 guest</span>
                            <span id="price-detail-value">{{ $currencySymbol }}0</span>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between py-3 border-top border-soft mb-2">
                        <span class="fw-bold text-secondary">Total</span>
                        <span id="total-price" class="h5 fw-black mb-0">{{ $currencySymbol }}0</span>
                    </div>

                    <a href="{{ url('/order') }}" id="btn-book-now"
                        class="btn btn-japan w-100 py-3 rounded-pill fw-bold fs-5 shadow hover-scale d-flex align-items-center justify-content-center gap-2 {{ $availableSchedules->isEmpty() ? 'disabled' : '' }}"
                        role="button"
                        aria-disabled="{{ $availableSchedules->isEmpty() ? 'true' : 'false' }}">
                        Book Now <span class="material-symbols-outlined">arrow_forward</span>
                    </a>

                    <div class="text-center mt-4 pt-3 border-top border-dashed border-secondary border-opacity-25">
                        <div class="d-flex justify-content-center align-items-center gap-1 mb-1">
                            <span class="material-symbols-outlined text-success fs-6">verified_user</span>
                            <small class="fw-bold text-dark">Waku Guarantee</small>
                        </div>
                        <small class="text-muted" style="font-size: 11px;">Free cancellation up to 7 days before
                            trip.</small>
                    </div>
                    <div class="mt-4 p-3 rounded-4 bg-light border border-soft d-flex gap-3 align-items-start">
                        <div class="bg-white rounded-circle p-2 shadow-sm text-japan-red">
                            <span class="material-symbols-outlined">support_agent</span>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Need help booking?</h6>
                            <p class="small text-secondary mb-1">Call our local expert support team 24/7.</p>
                            <a href="#" class="small fw-bold text-japan-red text-decoration-none">Contact Support</a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>
 
    {{-- Floating Mobile Booking Bar --}}
    <div class="fixed-bottom bg-white border-top d-lg-none p-3 shadow-lg-up z-index-content">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div>
                    <span class="d-block text-secondary small fw-bold text-uppercase" style="font-size: 10px;">{{ in_array($package->type, ['private', 'activity']) ? 'Private/Activity' : 'Open Group' }}</span>
                    <div class="d-flex align-items-baseline gap-1 mt-n1">
                        <h4 class="fw-black text-japan-red mb-0" id="mobile-display-price">
                             {{ convert_currency($startingPrice) }}
                        </h4>
                        <span class="small text-secondary fw-bold" style="font-size: 11px;">/ person</span>
                    </div>
                </div>
                <button type="button" class="btn btn-japan px-4 py-2 rounded-pill fw-bold shadow-sm d-flex align-items-center gap-2" 
                    onclick="document.querySelector('.sticky-booking').scrollIntoView({behavior: 'smooth'});">
                    Book Now <span class="material-symbols-outlined fs-6">expand_less</span>
                </button>
            </div>
        </div>
    </div>

    <!--================ Footer Area start =================-->
    @include('partials.footer')
    <!--=============== Footer Area end =================-->

    {{-- Booking Card JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // === Elements ===
            const scheduleSelect = document.getElementById('schedule-select');
            const guestCount = document.getElementById('guest-count');
            const guestLabel = document.getElementById('guest-label');
            const guestMinus = document.getElementById('guest-minus');
            const guestPlus = document.getElementById('guest-plus');
            const totalPrice = document.getElementById('total-price');
            const displayPrice = document.getElementById('display-price');
            const priceDetailLabel = document.getElementById('price-detail-label');
            const priceDetailValue = document.getElementById('price-detail-value');
            const btnBookNow = document.getElementById('btn-book-now');
            const typeToggle = document.getElementById('trip-type-toggle');

            let guests = 1;
            let selectedType = '{{ $package->type }}';

            // === Get selected schedule data ===
            function getSelectedSchedule() {
                const opt = scheduleSelect.options[scheduleSelect.selectedIndex];
                if (!opt || opt.disabled) return null;
                return {
                    id: opt.value,
                    price: parseFloat(opt.dataset.price) || 0,
                    seats: parseInt(opt.dataset.seats) || 99
                };
            }

            // === Update guest label text ===
            function updateGuestLabel() {
                guestCount.textContent = guests;
                guestLabel.textContent = guests + (guests === 1 ? ' Adult' : ' Adults');
            }

            // === Update booking details, prices, and Book Now link ===
            function updateBooking() {
                const schedule = getSelectedSchedule();

                if (schedule) {
                    const price = schedule.price;
                    const total = price * guests;

                    // Update UI prices
                    const formattedPrice = window.WakuCurrency.format(price);
                    const formattedTotal = window.WakuCurrency.format(total);

                    displayPrice.textContent = formattedPrice;
                    if(document.getElementById('mobile-display-price')) {
                        document.getElementById('mobile-display-price').textContent = formattedPrice;
                    }
                    totalPrice.textContent = formattedTotal;
                    priceDetailLabel.textContent = `${formattedPrice} × ${guests} ${guests === 1 ? 'guest' : 'guests'}`;
                    priceDetailValue.textContent = formattedTotal;

                    // Update Book Now link with query params
                    const orderUrl = `{{ url('/order') }}?schedule_id=${schedule.id}&guests=${guests}`;
                    btnBookNow.href = orderUrl;
                    btnBookNow.classList.remove('disabled');
                    btnBookNow.setAttribute('aria-disabled', 'false');

                    // Update guest button states
                    guestMinus.disabled = (guests <= 1);
                    guestPlus.disabled = (guests >= schedule.seats);
                } else {
                    // No valid schedule selected
                    displayPrice.textContent = window.WakuCurrency.format({{ $startingPrice }});
                    totalPrice.textContent = `${window.WakuCurrency.symbol} 0`;
                    priceDetailLabel.textContent = 'No date selected';
                    priceDetailValue.textContent = `${window.WakuCurrency.symbol} 0`;
                    btnBookNow.href = '#';
                    btnBookNow.classList.add('disabled');
                    btnBookNow.setAttribute('aria-disabled', 'true');
                }
            }

            // === Schedule change ===
            scheduleSelect.addEventListener('change', function () {
                const schedule = getSelectedSchedule();
                if (schedule && guests > schedule.seats) {
                    guests = Math.max(1, schedule.seats);
                    updateGuestLabel();
                }
                updateBooking();
            });

            // === Guest minus ===
            guestMinus.addEventListener('click', function () {
                if (guests > 1) {
                    guests--;
                    updateGuestLabel();
                    updateBooking();
                }
            });

            // === Guest plus ===
            guestPlus.addEventListener('click', function () {
                const schedule = getSelectedSchedule();
                const maxSeats = schedule ? schedule.seats : 99;
                if (guests < maxSeats) {
                    guests++;
                    updateGuestLabel();
                    updateBooking();
                }
            });

            // === Trip type toggle (Open Group / Private) ===
            typeToggle.querySelectorAll('.btn-trip-type').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    selectedType = this.dataset.type;
                    typeToggle.querySelectorAll('.btn-trip-type').forEach(function (b) {
                        b.classList.remove('btn-white', 'shadow-sm');
                        b.classList.add('btn-transparent', 'text-secondary');
                    });
                    this.classList.remove('btn-transparent', 'text-secondary');
                    this.classList.add('btn-white', 'shadow-sm');
                });
            });

            // === Initialize ===
            updateBooking();
        });
    </script>

    <!-- Enhanced Lightbox Modal -->
    <div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" style="max-width: 95vw;">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body p-0 position-relative">
                    <button type="button"
                        class="btn-close btn-close-white position-absolute top-0 end-0 m-3 shadow-none z-3"
                        data-bs-dismiss="modal" aria-label="Close"></button>

                    <div class="container-fluid p-0">
                        <div class="row g-0 align-items-center justify-content-center h-100">
                            <!-- Main Slider -->
                            <div class="col-12 col-md-9 position-relative">
                                <div id="lightboxCarousel" class="carousel slide" data-bs-interval="false">
                                    <div class="carousel-inner rounded-3 shadow-lg">
                                        @foreach($package->media as $index => $image)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                <div class="d-flex align-items-center justify-content-center"
                                                    style="height: 85vh; background: rgba(0,0,0,0.8);">
                                                    <img src="{{ $image->getOptimizedUrl(1920, 1080) }}" class="img-fluid"
                                                        style="max-height: 100%; object-fit: contain;"
                                                        alt="Gallery Image {{ $index + 1 }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <button class="carousel-control-prev" type="button"
                                        data-bs-target="#lightboxCarousel" data-bs-slide="prev">
                                        <span
                                            class="carousel-control-prev-icon bg-dark rounded-circle p-4 bg-opacity-50"
                                            aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button"
                                        data-bs-target="#lightboxCarousel" data-bs-slide="next">
                                        <span
                                            class="carousel-control-next-icon bg-dark rounded-circle p-4 bg-opacity-50"
                                            aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Thumbnail Sidebar -->
                            <div class="col-12 col-md-3 d-none d-md-block ps-md-3">
                                <div class="thumbnail-container d-flex flex-column gap-2 pe-2"
                                    style="max-height: 85vh; overflow-y: auto;">
                                    @foreach($package->media as $index => $image)
                                        <div class="thumbnail-item {{ $index === 0 ? 'active' : '' }} rounded-2 overflow-hidden position-relative"
                                            onclick="goToSlide({{ $index }})" id="thumb-{{ $index }}"
                                            style="height: 100px; flex-shrink: 0;">
                                            <img src="{{ $image->getOptimizedUrl(150, 150) }}"
                                                class="w-100 h-100 object-fit-cover" alt="Thumbnail {{ $index + 1 }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        /**
         * Open lightbox at a specific image or index
         * @param {HTMLElement|number} target - The clicked element or a specific index
         */
        function openLightbox(target) {
            try {
                const bs = window.bootstrap || bootstrap;
                if (!bs) {
                    console.error("Bootstrap JS not found. Please ensure bootstrap.bundle.min.js is loaded.");
                    return;
                }

                let index = 0;
                if (typeof target === 'number') {
                    index = target;
                } else if (target && target.querySelector) {
                    const img = target.querySelector('img');
                    if (img) {
                        const clickedSrc = img.src;
                        const thumbs = document.querySelectorAll('.thumbnail-item img');
                        const allSrcs = Array.from(thumbs).map(i => i.src);
                        index = allSrcs.indexOf(clickedSrc);
                        if (index === -1) index = 0;
                    }
                }

                // Initialize or get Carousel
                const carouselEl = document.getElementById('lightboxCarousel');
                if (carouselEl) {
                    const carousel = bs.Carousel.getOrCreateInstance(carouselEl);
                    carousel.to(index);
                }

                // Initialize or get Modal
                const modalEl = document.getElementById('lightboxModal');
                if (modalEl) {
                    const modal = bs.Modal.getOrCreateInstance(modalEl);
                    modal.show();

                    // Force thumbnail update for zero-index or initial load
                    updateActiveThumb(index);
                }
            } catch (err) {
                console.error("Lightbox Error:", err);
            }
        }

        function goToSlide(index) {
            const bs = window.bootstrap || bootstrap;
            const carouselEl = document.getElementById('lightboxCarousel');
            if (carouselEl && bs) {
                const carousel = bs.Carousel.getOrCreateInstance(carouselEl);
                carousel.to(index);
                updateActiveThumb(index);
            }
        }

        function updateActiveThumb(index) {
            document.querySelectorAll('.thumbnail-item').forEach((el, i) => {
                if (i === index) {
                    el.classList.add('active');
                    el.style.borderColor = '#BC002D';
                    el.style.opacity = '1';
                    el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                } else {
                    el.classList.remove('active');
                    el.style.borderColor = 'transparent';
                    el.style.opacity = '0.6';
                }
            });
        }

        // Sync thumbnails when carousel moves
        document.addEventListener('DOMContentLoaded', function () {
            const carouselEl = document.getElementById('lightboxCarousel');
            if (carouselEl) {
                carouselEl.addEventListener('slide.bs.carousel', function (e) {
                    updateActiveThumb(e.to);
                });
            }

            // Itinerary toggle logic
            const toggleItineraryBtn = document.getElementById('toggle-itinerary-btn');
            if (toggleItineraryBtn) {
                toggleItineraryBtn.addEventListener('click', function() {
                    const additionalDays = document.querySelectorAll('.additional-day');
                    const isExpanded = toggleItineraryBtn.classList.contains('expanded');
                    
                    additionalDays.forEach(day => {
                        if (isExpanded) {
                            day.classList.add('d-none');
                        } else {
                            day.classList.remove('d-none');
                        }
                    });

                    if (isExpanded) {
                        toggleItineraryBtn.classList.remove('expanded');
                        toggleItineraryBtn.innerHTML = '<span>Show full detailed itinerary</span> <span class="material-symbols-outlined">expand_more</span>';
                    } else {
                        toggleItineraryBtn.classList.add('expanded');
                        toggleItineraryBtn.innerHTML = '<span>Show less detailed itinerary</span> <span class="material-symbols-outlined">expand_less</span>';
                    }
                });
            }
        });
    </script>

</body>

</html>