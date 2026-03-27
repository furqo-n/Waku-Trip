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
    <main class="main-content p-4 p-lg-5" style="min-height: 100vh;">

        <script>
            function toggleSidebar() {
                document.getElementById('sidebar').classList.toggle('show');
            }
        </script>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                <i class="material-icons align-middle me-2" style="font-size: 20px;">check_circle</i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                <i class="material-icons align-middle me-2" style="font-size: 20px;">error</i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <p class="text-uppercase fw-bold mb-2" style="color: #BC002D; font-size: 12px; letter-spacing: 1.5px;">
                    YOUR JOURNEYS</p>
                <h1 class="text-white fw-bold mb-2" style="font-size: 36px;">My Bookings</h1>
                <p class="text-secondary">Manage your upcoming adventures and view past trips.</p>
            </div>
            <div class="d-none d-md-flex gap-2">
                <button class="btn btn-outline-dark px-4 py-2 rounded-3">
                    <i class="material-icons align-middle me-2" style="font-size: 18px;">help_outline</i>
                    Help
                </button>
                <a href="{{ url('/planned_list') }}" class="btn btn-danger px-4 py-2 rounded-3"
                    style="background-color: #BC002D; border: none;">
                    <i class="material-icons align-middle me-2" style="font-size: 18px;">add</i>
                    New Booking
                </a>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <style>
            .nav-tabs .nav-link {
                color: #6c757d;
                border: none;
                border-bottom: 3px solid transparent;
                transition: all 0.3s ease;
            }

            .nav-tabs .nav-link:hover {
                color: #BC002D;
                border-color: rgba(188, 0, 45, 0.1);
            }

            .nav-tabs .nav-link.active {
                color: #BC002D !important;
                border-bottom: 3px solid #BC002D !important;
                font-weight: 600;
                background: transparent;
            }
        </style>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs border-0 mb-4" id="bookingTabs" role="tablist" style="gap: 2rem;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active px-0 pb-3 d-flex align-items-center gap-2" id="upcoming-tab"
                    data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming"
                    aria-selected="true">
                    <i class="material-icons" style="font-size: 20px;">flight_takeoff</i>
                    <span>Upcoming</span>
                    @if($upcomingBookings->count() > 0)
                        <span class="badge rounded-pill ms-1"
                            style="background-color: #BC002D; font-size: 11px; padding: 4px 10px;">{{ $upcomingBookings->count() }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-0 pb-3 d-flex align-items-center gap-2" id="past-tab" data-bs-toggle="tab"
                    data-bs-target="#past" type="button" role="tab" aria-controls="past" aria-selected="false">
                    <i class="material-icons" style="font-size: 20px;">history</i>
                    Past
                    @if($pastBookings->count() > 0)
                        <span class="badge rounded-pill ms-1 bg-secondary"
                            style="font-size: 11px; padding: 4px 10px;">{{ $pastBookings->count() }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link px-0 pb-3 d-flex align-items-center gap-2" id="cancelled-tab"
                    data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled"
                    aria-selected="false">
                    <i class="material-icons" style="font-size: 20px;">cancel</i>
                    Cancelled
                    @if($cancelledBookings->count() > 0)
                        <span class="badge rounded-pill ms-1 bg-danger bg-opacity-75"
                            style="font-size: 11px; padding: 4px 10px;">{{ $cancelledBookings->count() }}</span>
                    @endif
                </button>
            </li>
        </ul>

        <div class="tab-content" id="bookingTabsContent">
            <!-- Upcoming Bookings Tab -->
            <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                @forelse($upcomingBookings as $booking)
                    @php
                        $package = $booking->tripSchedule->package;
                        $schedule = $booking->tripSchedule;
                        $imageUrl = $package->getFirstMediaUrl('primary_image', app_setting('default_tour_image', 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=400'));
                        $nights = $schedule->start_date->diffInDays($schedule->end_date);
                        $daysUntil = now()->diffInDays($schedule->start_date, false);

                        // Status styling
                        $statusColor = match ($booking->status) {
                            'confirmed' => 'success',
                            'pending' => 'warning',
                            default => 'secondary'
                        };
                        $statusIcon = match ($booking->status) {
                            'confirmed' => 'check_circle',
                            'pending' => 'schedule',
                            default => 'info'
                        };
                        $statusLabel = match ($booking->status) {
                            'confirmed' => 'Confirmed',
                            'pending' => 'Pending confirm',
                            default => ucfirst($booking->status)
                        };
                    @endphp
                    <div class="card mb-4 border-0 rounded-4 overflow-hidden shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-3">
                                <div class="position-relative h-100">
                                    <img src="{{ $imageUrl }}" class="w-100 h-100 object-fit-cover"
                                        alt="{{ $package->title }}" style="min-height: 300px;">
                                    <span
                                        class="position-absolute top-0 start-0 m-3 badge bg-{{ $statusColor }} {{ $booking->status == 'pending' ? 'text-dark' : '' }} px-3 py-2 rounded-3">
                                        <i class="material-icons align-middle me-1"
                                            style="font-size: 14px;">{{ $statusIcon }}</i>
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="card-body p-4 bg-white">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h3 class="fw-bold mb-3 text-dark">{{ $package->title }}</h3>
                                            <div class="d-flex gap-4 text-secondary small flex-wrap">
                                                <span>
                                                    <i class="material-icons align-middle me-1"
                                                        style="font-size: 16px;">calendar_today</i>
                                                    {{ $schedule->start_date->format('M d') }} -
                                                    {{ $schedule->end_date->format('M d, Y') }}
                                                </span>
                                                <span>
                                                    <i class="material-icons align-middle me-1"
                                                        style="font-size: 16px;">nights_stay</i>
                                                    {{ $nights }} Nights
                                                </span>
                                                <span>
                                                    <i class="material-icons align-middle me-1"
                                                        style="font-size: 16px;">people</i>
                                                    {{ $booking->pax_count }}
                                                    {{ Str::plural('Adult', $booking->pax_count) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-secondary d-block mb-1">BOOKING ID</small>
                                            <strong class="text-dark">{{ $booking->booking_code }}</strong>
                                        </div>
                                    </div>

                                    @if($booking->status === 'confirmed' && $daysUntil > 0)
                                        <!-- Flight Info Banner -->
                                        <div
                                            class="alert mb-3 d-flex justify-content-between align-items-center rounded-3 bg-light border-0">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle d-flex justify-content-center align-items-center"
                                                    style="width: 64px; height: 64px; background-color: #e6f2ff; color: #0d6efd;">
                                                    <span class="material-icons" style="font-size: 32px;">flight</span>
                                                </div>
                                                <div>
                                                    <strong class="text-dark d-block">Trip starts in {{ floor($daysUntil) }}
                                                        {{ Str::plural('day', $daysUntil) }}</strong>
                                                    <small class="text-secondary">
                                                        {{ $package->location_text ?? 'Japan' }} •
                                                        {{ convert_currency($booking->total_price) }}
                                                    </small>
                                                </div>
                                            </div>
                                            <a href="{{ route('tour.show', $package->slug) }}"
                                                class="text-decoration-none fw-medium" style="color: #BC002D !important;">
                                                View Route →
                                            </a>
                                        </div>
                                    @endif

                                    @if($booking->status === 'pending')
                                        <!-- Payment Warning -->
                                        <div class="alert mb-3 rounded-3 bg-warning bg-opacity-10 border-0">
                                            <div class="d-flex align-items-start gap-3">
                                                <i class="material-icons text-warning"
                                                    style="font-size: 24px;">error_outline</i>
                                                <div>
                                                    <strong class="text-dark d-block mb-1">Confirm your
                                                        booking</strong>
                                                    <small class="text-secondary">Please Confirm payment to secure
                                                        your
                                                        reservation. Total:
                                                        {{ convert_currency($booking->total_price) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Passenger Info -->
                                    @if($booking->passengers->count() > 0)
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <i class="material-icons text-secondary" style="font-size: 18px;">person</i>
                                            <small class="text-secondary">
                                                {{ $booking->passengers->pluck('name')->join(', ') }}
                                            </small>
                                        </div>
                                    @endif

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2 flex-wrap">
                                        @if($booking->status === 'pending')
                                            <!-- Pay Button Trigger -->
                                            <button type="button" class="btn btn-danger px-4 rounded-3 text-white"
                                                style="background-color: #BC002D; border: none;" data-bs-toggle="modal"
                                                data-bs-target="#payModal{{ $booking->id }}">
                                                Confirm Payment
                                            </button>

                                            <form action="{{ route('booking.cancel', $booking->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary px-4 rounded-3">
                                                    Cancel Request
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('booking.manage', $booking->id) }}"
                                                class="btn btn-danger px-4 rounded-3 text-white"
                                                style="background-color: #BC002D; border: none;">
                                                Manage Booking
                                            </a>
                                        @endif
                                        <a href="{{ route('booking.guests', $booking->id) }}"
                                            class="btn btn-link text-danger" style="color: #BC002D !important;">Manage
                                            Guests</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Modal -->
                    <div class="modal fade" id="payModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 border-0 shadow-lg text-start">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="modal-title fw-bold text-dark">Secure Payment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="text-center mb-4">
                                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                            style="width: 64px; height: 64px;">
                                            <i class="material-icons text-success"
                                                style="font-size: 32px;">verified_user</i>
                                        </div>
                                        <h4 class="mb-1 fw-bold text-dark">{{ convert_currency($booking->total_price) }}
                                        </h4>
                                        <p class="text-secondary small mb-0">Total Amount due</p>
                                    </div>

                                    <div class="card bg-light border-0 rounded-3 mb-4">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-secondary small">Package</span>
                                                <span class="fw-bold text-dark text-end"
                                                    style="max-width: 60%;">{{ Str::limit($package->title, 25) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-secondary small">Booking Code</span>
                                                <span class="fw-bold text-dark">{{ $booking->booking_code }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-secondary small">Date</span>
                                                <span
                                                    class="fw-bold text-dark">{{ $schedule->start_date->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <form action="{{ route('booking.pay', $booking->id) }}" method="POST">
                                        @csrf

                                        <div class="mb-4">
                                            <label class="form-label small fw-bold text-secondary text-uppercase"
                                                style="letter-spacing: 0.5px;">Confirmation Status</label>
                                            <div class="p-3 bg-light rounded-3 border border-dashed text-center">
                                                <i class="material-icons text-primary mb-2"
                                                    style="font-size: 32px;">info_outline</i>
                                                <p class="small text-dark mb-0">You have completed your payment via
                                                    Midtrans. Our team will verify the transaction and confirm your booking
                                                    shortly.</p>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <p class="small text-secondary mb-3">If you have already paid but your status is
                                                still "Pending confirm", please click the button below to complete the
                                                confirmation process.</p>
                                            <button type="submit"
                                                class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2"
                                                style="border: none; background-color: #BC002D;">
                                                <i class="material-icons">check_circle</i>
                                                Confirm Payment
                                            </button>
                                        </div>

                                        <div class="text-center">
                                            <button type="button" class="btn btn-outline-secondary w-100 py-2 rounded-3"
                                                data-bs-dismiss="modal">
                                                Got it
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Empty State - Upcoming -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light"
                                style="width: 80px; height: 80px;">
                                <i class="material-icons text-secondary" style="font-size: 40px;">flight_takeoff</i>
                            </div>
                        </div>
                        <h4 class="text-white mb-3">No upcoming trips</h4>
                        <p class="text-secondary mb-4">You don't have any upcoming bookings yet.<br>Start planning your
                            next adventure!</p>
                        <a href="{{ url('/planned_list') }}" class="btn btn-outline-light px-5 py-2 rounded-3">
                            Explore Destinations
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Past Bookings Tab -->
            <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                @forelse($pastBookings as $booking)
                    @php
                        $package = $booking->tripSchedule->package;
                        $schedule = $booking->tripSchedule;
                        $imageUrl = $package->getFirstMediaUrl('primary_image', app_setting('default_tour_image', 'https://images.unsplash.com/photo-1542051841857-5f90071e7989?w=400'));
                        $nights = $schedule->start_date->diffInDays($schedule->end_date);
                    @endphp
                    <div class="card mb-4 border-0 rounded-4 overflow-hidden shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-3">
                                <div class="position-relative h-100">
                                    <img src="{{ $imageUrl }}" class="w-100 h-100 object-fit-cover"
                                        alt="{{ $package->title }}" style="min-height: 300px;">
                                    <span
                                        class="position-absolute top-0 start-0 m-3 badge bg-secondary px-3 py-2 rounded-3">
                                        <i class="material-icons align-middle me-1"
                                            style="font-size: 14px;">check_circle</i>
                                        Completed
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="card-body p-4 bg-white">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h3 class="fw-bold mb-3 text-dark">{{ $package->title }}</h3>
                                            <div class="d-flex gap-4 text-secondary small flex-wrap">
                                                <span>
                                                    <i class="material-icons align-middle me-1"
                                                        style="font-size: 16px;">calendar_today</i>
                                                    {{ $schedule->start_date->format('M d') }} -
                                                    {{ $schedule->end_date->format('M d, Y') }}
                                                </span>
                                                <span>
                                                    <i class="material-icons align-middle me-1"
                                                        style="font-size: 16px;">nights_stay</i>
                                                    {{ $nights }} Nights
                                                </span>
                                                <span>
                                                    <i class="material-icons align-middle me-1"
                                                        style="font-size: 16px;">people</i>
                                                    {{ $booking->pax_count }}
                                                    {{ Str::plural('Adult', $booking->pax_count) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-secondary d-block mb-1">BOOKING ID</small>
                                            <strong class="text-dark">{{ $booking->booking_code }}</strong>
                                        </div>
                                    </div>

                                    <!-- Review Prompt -->
                                    <div class="alert mb-3 rounded-3 bg-light border-0">
                                        <div class="d-flex align-items-center gap-3">
                                            <div
                                                class="rounded-circle bg-warning bg-opacity-10 p-2 d-flex align-items-center justify-content-center">
                                                <i class="material-icons text-warning" style="font-size: 24px;">star</i>
                                            </div>
                                            <div>
                                                <strong class="text-dark d-block">How was your trip?</strong>
                                                <small class="text-secondary">Share your experience to earn 500 reward
                                                    points.</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button class="btn btn-outline-danger px-4 rounded-3"
                                            style="color: #BC002D; border-color: #BC002D;" data-bs-toggle="modal"
                                            data-bs-target="#reviewModal" data-trip-name="{{ $package->title }}"
                                            data-booking-id="{{ $booking->id }}">
                                            Write a Review
                                        </button>
                                        <a href="{{ route('tour.show', $package->slug) }}"
                                            class="btn btn-primary px-4 rounded-3 text-white"
                                            style="background-color: #BC002D; border: none;">
                                            Book Again
                                        </a>
                                        <a href="{{ route('tour.show', $package->slug) }}" class="btn btn-link text-danger"
                                            style="color: #BC002D !important;">View
                                            Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light"
                                style="width: 80px; height: 80px;">
                                <i class="material-icons text-secondary" style="font-size: 40px;">history</i>
                            </div>
                        </div>
                        <h4 class="text-white mb-3">No past trips yet</h4>
                        <p class="text-secondary mb-0">Your completed trips will appear here.</p>
                    </div>
                @endforelse
            </div>

            <!-- Cancelled Bookings Tab -->
            <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                @forelse($cancelledBookings as $booking)
                    @php
                        $package = $booking->tripSchedule->package;
                        $schedule = $booking->tripSchedule;
                        $imageUrl = $package->getFirstMediaUrl('primary_image', app_setting('default_tour_image', 'https://images.unsplash.com/photo-1542051841857-5f90071e7989?w=400'));
                        $nights = $schedule->start_date->diffInDays($schedule->end_date);
                    @endphp
                    <div class="card mb-4 border-0 rounded-4 overflow-hidden shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-3">
                                <div class="position-relative h-100">
                                    <img src="{{ $imageUrl }}" class="w-100 h-100 object-fit-cover"
                                        alt="{{ $package->title }}" style="min-height: 300px; filter: grayscale(50%);">
                                    <span class="position-absolute top-0 start-0 m-3 badge bg-danger px-3 py-2 rounded-3">
                                        <i class="material-icons align-middle me-1" style="font-size: 14px;">cancel</i>
                                        Cancelled
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="card-body p-4 bg-white">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <h3 class="fw-bold mb-3 text-dark">{{ $package->title }}</h3>
                                            <div class="d-flex gap-4 text-secondary small flex-wrap">
                                                <span>
                                                    <i class="material-icons align-middle me-1"
                                                        style="font-size: 16px;">calendar_today</i>
                                                    {{ $schedule->start_date->format('M d') }} -
                                                    {{ $schedule->end_date->format('M d, Y') }}
                                                </span>
                                                <span>
                                                    <i class="material-icons align-middle me-1"
                                                        style="font-size: 16px;">nights_stay</i>
                                                    {{ $nights }} Nights
                                                </span>
                                                <span>
                                                    <i class="material-icons align-middle me-1"
                                                        style="font-size: 16px;">people</i>
                                                    {{ $booking->pax_count }}
                                                    {{ Str::plural('Adult', $booking->pax_count) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-secondary d-block mb-1">BOOKING ID</small>
                                            <strong class="text-dark">{{ $booking->booking_code }}</strong>
                                        </div>
                                    </div>

                                    <div class="alert mb-3 rounded-3 bg-danger bg-opacity-10 border-0">
                                        <div class="d-flex align-items-start gap-3">
                                            <i class="material-icons text-danger" style="font-size: 24px;">info</i>
                                            <div>
                                                <strong class="text-dark d-block mb-1">Booking Cancelled</strong>
                                                <small class="text-secondary">
                                                    This booking was cancelled on
                                                    {{ $booking->updated_at->format('M d, Y') }}.
                                                    Total: {{ convert_currency($booking->total_price) }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ route('tour.show', $package->slug) }}"
                                            class="btn btn-primary px-4 rounded-3 text-white"
                                            style="background-color: #BC002D; border: none;">
                                            Book Again
                                        </a>
                                        <a href="{{ route('tour.show', $package->slug) }}" class="btn btn-link text-danger"
                                            style="color: #BC002D !important;">View Tour</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light"
                                style="width: 80px; height: 80px;">
                                <i class="material-icons text-secondary" style="font-size: 40px;">cancel_presentation</i>
                            </div>
                        </div>
                        <h4 class="text-white mb-3">No cancelled trips</h4>
                        <p class="text-secondary mb-0">You haven't cancelled any bookings.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Bottom CTA -->
        <div class="text-center py-5 mt-5">
            <div class="mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-secondary bg-opacity-25"
                    style="width: 80px; height: 80px;">
                    <i class="material-icons text-secondary" style="font-size: 40px;">card_travel</i>
                </div>
            </div>
            <h4 class="text-white mb-3">Plan your next trip</h4>
            <p class="text-secondary mb-4">Explore our curated Japanese<br>experiences and find your perfect
                adventure.</p>
            <a href="{{ url('/planned_list') }}" class="btn btn-outline-light px-5 py-2 rounded-3">
                Explore Destinations
            </a>
        </div>

    </main>

    @include('partials.review_modal')
    @include('partials.script')
    <script>
        // This script fixes the "Dark Screen" issue
        document.addEventListener("DOMContentLoaded", function () {
            // Find all modals on the page
            var modals = document.querySelectorAll('.modal');

            // Move them to the very end of the <body> tag
            // This takes them out of the "Card Trap" so they sit on top of the dark screen
            modals.forEach(function (modal) {
                document.body.appendChild(modal);
            });
        });
    </script>
</body>

</html>