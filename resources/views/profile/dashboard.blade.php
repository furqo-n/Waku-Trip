<!DOCTYPE html>

<html lang="en">
@include('partials.head')

<head>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body class="dashboard-page">
    <!--================ Header Menu Area start =================-->
    @include('partials.header')
    <!--===============Header Menu Area =================-->
    <!-- Sidebar Navigation -->
    @include('partials.sidebar')
    <!-- Main Content Area -->
    <main class="main-content p-4 p-lg-5">
        <script>
            function toggleSidebar() {
                document.getElementById('sidebar').classList.toggle('show');
            }
        </script>
        <!-- Greeting Section -->
        <header class="d-flex flex-column flex-sm-row justify-content-between align-items-end mb-4 mb-lg-5">
            <div>
                <p class="text-primary-custom fw-medium mb-1 text-uppercase small tracking-wide">Your Travel Hub</p>
                <h1 class="display-5 fw-bold text-dark mb-1">Kon'nichiwa, {{ $user->name }}
                </h1>
                <p class="text-secondary fw-light mt-2">Your next adventure to Japan is closer than you think.</p>
            </div>
            <!-- Quick Action Buttons (Desktop) -->
            <div class="d-none d-sm-flex gap-2">
                <a href="{{ url('/planned_list') }}"
                    class="btn btn-light border bg-white d-flex align-items-center gap-2 fw-medium shadow-sm py-2 px-3">
                    <span class="material-icons fs-6">add</span>
                    New Trip
                </a>
                <a href="{{ url('/planned_list') }}"
                    class="btn btn-dark d-flex align-items-center gap-2 fw-medium shadow-sm py-2 px-3">
                    <span class="material-icons fs-6">search</span>
                    Explore
                </a>
            </div>
        </header>

        <!-- Hero Card: Upcoming Trip -->
        @if($nextTrip)
            @php
                $heroPackage = $nextTrip->tripSchedule->package;
                $heroSchedule = $nextTrip->tripSchedule;
                $heroImageUrl = $heroPackage->getFirstMediaUrl('primary_image', app_setting('default_tour_image', 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=1200'));
                $daysUntil = now()->diffInDays($heroSchedule->start_date, false);
                $hoursUntil = now()->diffInHours($heroSchedule->start_date) % 24;
                $tripDuration = $heroSchedule->start_date->diffInDays($heroSchedule->end_date);
            @endphp
            <section class="hero-card mb-5 bg-dark">
                <img alt="{{ $heroPackage->title }}" class="hero-img position-absolute top-0 start-0"
                    src="{{ $heroImageUrl }}" />
                <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
                <div
                    class="position-absolute bottom-0 start-0 w-100 p-4 p-lg-5 text-white z-2 d-flex flex-column flex-md-row align-items-end justify-content-between gap-4">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span
                                class="badge bg-light bg-opacity-25 backdrop-blur text-uppercase tracking-wider border border-white border-opacity-25 px-2 py-1">
                                {{ ucfirst($nextTrip->status) }}
                            </span>
                            <span class="text-light small fw-medium d-flex align-items-center gap-1">
                                <span class="material-icons" style="font-size: 16px;">flight_takeoff</span>
                                {{ $heroSchedule->start_date->format('M d') }} -
                                {{ $heroSchedule->end_date->format('M d, Y') }}
                            </span>
                        </div>
                        <h2 class="display-4 fw-bold mb-2 lh-1 text-white">{{ $heroPackage->title }}</h2>
                        <p class="text-white-50" style="max-width: 450px;">
                            {{ $tripDuration }} days exploring Japan.
                            {!! Str::limit($heroPackage->description, 80) !!}
                        </p>
                    </div>
                    <div class="bg-white bg-opacity-10 backdrop-blur rounded-4 p-4 border border-white border-opacity-10"
                        style="min-width: 280px;">
                        <p class="text-light text-uppercase small mb-2 fw-medium"
                            style="letter-spacing: 2px; font-size: 10px;">
                            @if($daysUntil > 0)
                                Time until takeoff
                            @else
                                Trip in progress
                            @endif
                        </p>
                        <div class="d-flex align-items-baseline gap-1 text-white">
                            @if($daysUntil > 0)
                                <span class="display-4 fw-light">{{ floor($daysUntil) }}</span>
                                <span class="small fw-medium me-3">days</span>
                                <span class="display-4 fw-light">{{ str_pad($hoursUntil, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="small fw-medium">hours</span>
                            @else
                                <span class="display-4 fw-light">🎉</span>
                                <span class="small fw-medium">Enjoy your trip!</span>
                            @endif
                        </div>
                        <a href="{{ route('booking.manage', $nextTrip->id) }}"
                            class="btn btn-primary-custom w-100 mt-3 py-2 fw-medium d-flex align-items-center justify-content-center gap-2 shadow-sm">
                            Manage Booking <span class="material-icons" style="font-size: 18px;">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </section>
        @else
            <!-- No Upcoming Trip - Show Explore CTA -->
            <section class="hero-card mb-5 bg-dark">
                <img alt="Explore Japan" class="hero-img position-absolute top-0 start-0"
                    src="{{ app_setting('default_tour_image', 'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=1200') }}" />
                <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
                <div
                    class="position-absolute bottom-0 start-0 w-100 p-4 p-lg-5 text-white z-2 d-flex flex-column flex-md-row align-items-end justify-content-between gap-4">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span
                                class="badge bg-light bg-opacity-25 backdrop-blur text-uppercase tracking-wider border border-white border-opacity-25 px-2 py-1">Start
                                Planning</span>
                        </div>
                        <h2 class="display-4 fw-bold mb-2 lh-1 text-white">Your Japan<br />Adventure Awaits</h2>
                        <p class="text-white-50" style="max-width: 450px;">Browse our curated trips and find the
                            perfect journey for you.</p>
                    </div>
                    <div class="bg-white bg-opacity-10 backdrop-blur rounded-4 p-4 border border-white border-opacity-10"
                        style="min-width: 280px;">
                        <p class="text-light text-uppercase small mb-2 fw-medium"
                            style="letter-spacing: 2px; font-size: 10px;">Ready to explore?</p>
                        <div class="d-flex align-items-baseline gap-1 text-white mb-2">
                            <span class="h4 fw-light mb-0">Discover amazing tours across Japan</span>
                        </div>
                        <a href="{{ url('/planned_list') }}"
                            class="btn btn-primary-custom w-100 mt-3 py-2 fw-medium d-flex align-items-center justify-content-center gap-2 shadow-sm">
                            Explore Tours <span class="material-icons" style="font-size: 18px;">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </section>
        @endif

        <!-- Dashboard Grid -->
        <section class="row row-cols-1 row-cols-md-3 g-4">

            <!-- Upcoming Bookings Card -->
            <div class="col">
                <div class="dashboard-card position-relative group">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="p-3 rounded-4 bg-danger bg-opacity-10 text-danger">
                            <span class="material-icons">flight_takeoff</span>
                        </div>
                        <a href="{{ route('mybooking') }}" class="btn btn-link text-secondary p-0 icon-link-hover">
                            <span class="material-icons">arrow_forward</span>
                        </a>
                    </div>
                    <h3 class="h5 fw-bold text-dark mb-1">Upcoming Trips</h3>
                    <p class="small text-secondary mb-4">
                        {{ $upcomingBookings->count() }} {{ Str::plural('trip', $upcomingBookings->count()) }} planned
                    </p>

                    <div class="d-flex flex-column gap-3">
                        @forelse($upcomingBookings->take(3) as $booking)
                            @php
                                $pkg = $booking->tripSchedule->package;
                                $imgUrl = $pkg->getFirstMediaUrl('primary_image', app_setting('default_tour_image', 'https://images.unsplash.com/photo-1480796927426-f609979314bd?w=200'));
                            @endphp
                            <div class="d-flex align-items-center gap-3 cursor-pointer">
                                <div class="rounded-3 overflow-hidden bg-light"
                                    style="width: 48px; height: 48px; flex-shrink: 0;">
                                    <img class="w-100 h-100 object-fit-cover" src="{{ $imgUrl }}" alt="{{ $pkg->title }}" />
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <h4 class="small fw-bold text-dark mb-0 text-truncate">{{ $pkg->title }}</h4>
                                    <p class="text-secondary mb-0" style="font-size: 11px;">
                                        {{ $booking->tripSchedule->start_date->format('M d') }} -
                                        {{ $booking->tripSchedule->end_date->format('M d, Y') }}
                                        • {{ $booking->pax_count }}
                                        {{ Str::plural('guest', $booking->pax_count) }}
                                    </p>
                                </div>
                                <span
                                    class="badge bg-{{ $booking->status == 'confirmed' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $booking->status == 'confirmed' ? 'success' : 'warning' }} small"
                                    style="font-size: 10px;">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-3">
                                <span class="material-icons text-secondary mb-2"
                                    style="font-size: 40px; opacity: 0.3;">flight_takeoff</span>
                                <p class="small text-secondary mb-2">No upcoming trips yet</p>
                                <a href="{{ url('/planned_list') }}"
                                    class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                    Browse Tours
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Past Trips Card -->
            <div class="col">
                <div class="dashboard-card position-relative">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="p-3 rounded-4 bg-warning bg-opacity-10 text-warning">
                            <span class="material-icons text-warning text-opacity-75">history_edu</span>
                        </div>
                        <a href="{{ route('mybooking') }}" class="btn btn-link text-secondary p-0 icon-link-hover">
                            <span class="material-icons">arrow_forward</span>
                        </a>
                    </div>
                    <h3 class="h5 fw-bold text-dark mb-1">Past Adventures</h3>
                    <p class="small text-secondary mb-4">
                        {{ $pastBookings->count() > 0 ? 'Relive your memories' : 'Your journey starts soon' }}
                    </p>

                    <div class="position-relative ps-3 ms-1 py-1">
                        <div class="position-absolute start-0 top-0 bottom-0 bg-light" style="width: 2px;"></div>

                        @forelse($pastBookings->take(3) as $index => $pastBooking)
                            @php
                                $pastPkg = $pastBooking->tripSchedule->package;
                                $pastImgUrl = $pastPkg->getFirstMediaUrl('primary_image');
                            @endphp
                            <div
                                class="position-relative {{ $index > 0 ? 'opacity-50' : '' }} {{ !$loop->last ? 'mb-4' : '' }}">
                                <span
                                    class="position-absolute translate-middle rounded-circle bg-{{ $index === 0 ? 'warning' : 'secondary' }} border border-4 border-white"
                                    style="width: 12px; height: 12px; left: -9px; top: 8px;"></span>
                                <h4 class="small fw-bold text-dark mb-0">{{ $pastPkg->title }}</h4>
                                <p class="text-secondary mb-2" style="font-size: 11px;">
                                    {{ $pastPkg->location_text ?? 'Japan' }} •
                                    {{ $pastBooking->tripSchedule->start_date->format('M Y') }}
                                </p>
                                @if($index === 0 && $pastImgUrl)
                                    <div class="d-flex position-relative" style="margin-left: 8px;">
                                        <img alt="{{ $pastPkg->title }}"
                                            class="rounded-circle border border-2 border-white object-fit-cover shadow-sm"
                                            style="width: 24px; height: 24px; margin-left: -8px;"
                                            src="{{ $pastImgUrl ?: app_setting('default_tour_image', 'https://images.unsplash.com/photo-1480796927426-f609979314bd?w=200') }}" />
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="position-relative">
                                <span
                                    class="position-absolute translate-middle rounded-circle bg-secondary border border-4 border-white"
                                    style="width: 12px; height: 12px; left: -9px; top: 8px;"></span>
                                <p class="small text-secondary mb-0">No past trips yet. Book your first adventure!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Reward Points Card -->
            <div class="col">
                <div class="dashboard-card position-relative overflow-hidden">
                    <!-- Background Decoration -->
                    <div class="position-absolute opacity-25 pe-none" style="right: -40px; bottom: -40px;">
                        <span class="material-icons text-primary-custom"
                            style="font-size: 180px; opacity: 0.1;">Savings</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-start mb-4 position-relative z-1">
                        <div class="p-3 rounded-4 bg-primary-custom bg-opacity-10 text-primary-custom">
                            <span class="material-icons text-white">card_giftcard</span>
                        </div>
                    </div>

                    <h3 class="h5 fw-bold text-dark mb-1 position-relative z-1">Waku Points</h3>
                    <p class="small text-secondary mb-4 position-relative z-1">Current Tier: {{ $tier }}</p>

                    <div class="d-flex align-items-end gap-2 mb-3 position-relative z-1">
                        <span
                            class="display-6 fw-bold text-primary-custom lh-1">{{ number_format($rewardPoints) }}</span>
                        <span class="small fw-medium text-secondary mb-1">pts</span>
                    </div>

                    <div class="progress mb-3 position-relative z-1"
                        style="height: 8px; border-radius: 99px; background-color: #f3f4f6;">
                        <div class="progress-bar bg-primary-custom" role="progressbar"
                            style="width: {{ $tierProgress }}%; border-radius: 99px;"
                            aria-valuenow="{{ $tierProgress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <p class="text-secondary d-flex align-items-center gap-1 position-relative z-1"
                        style="font-size: 11px;">
                        <span class="material-icons text-primary-custom" style="font-size: 14px;">info</span>
                        {{ number_format($pointsToNextTier) }} more points to reach {{ $nextTier }} status
                    </p>

                    <a href="{{ route('rewards') }}"
                        class="btn btn-outline-secondary w-100 mt-4 py-2 small fw-medium position-relative z-1 border-light-subtle bg-white">
                        Redeem Rewards
                    </a>
                </div>
            </div>
        </section>
    </main>
    @include('partials.script')
</body>

</html>