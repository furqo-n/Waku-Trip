<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<head>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        /* ── Manage Booking Page ── */
        .mb-hero {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            height: 220px;
        }

        .mb-hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .mb-hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, .75), rgba(0, 0, 0, .2));
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 24px;
        }

        /* Timeline */
        .itinerary-timeline {
            position: relative;
            padding-left: 28px;
        }

        .itinerary-timeline::before {
            content: '';
            position: absolute;
            left: 7px;
            top: 12px;
            bottom: 12px;
            width: 2px;
            background: #e5e7eb;
        }

        .itinerary-item {
            position: relative;
            padding-bottom: 28px;
        }

        .itinerary-item:last-child {
            padding-bottom: 0;
        }

        .itinerary-dot {
            position: absolute;
            left: -28px;
            top: 6px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #BC002D;
            border: 3px solid #fff;
            box-shadow: 0 0 0 2px #BC002D;
        }

        /* Guest avatar circle */
        .guest-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            background: #f3f4f6;
            color: #6b7280;
        }

        /* Payment summary rows */
        .payment-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
        }

        .payment-row.total {
            border-top: 2px solid #e5e7eb;
            padding-top: 12px;
            margin-top: 8px;
        }

        /* Status badge colours */
        .status-paid {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .status-pending {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
        }

        .status-confirmed {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .status-cancelled {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        /* Itinerary tag */
        .itin-tag {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 6px;
            background: #f3f4f6;
            color: #4b5563;
            font-size: 12px;
            font-weight: 500;
            margin-right: 6px;
            margin-top: 6px;
        }

        /* Smooth section card */
        .mb-card {
            background: #fff;
            border: 1px solid #f0eeee;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
        }

        /* Edit pencil icon button */
        .btn-edit-guest {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #9ca3af;
            transition: all .2s;
        }

        .btn-edit-guest:hover {
            border-color: #BC002D;
            color: #BC002D;
        }

        /* Collapsible animation */
        .collapse-itinerary {
            max-height: 0;
            overflow: hidden;
            transition: max-height .4s ease;
        }

        .collapse-itinerary.show {
            max-height: 2000px;
        }
    </style>
</head>

<body class="dashboard-page">

    @include('partials.header')
    @include('partials.sidebar')

    <main class="main-content p-4 p-lg-5" style="min-height: 100vh;">
        <script>
            function toggleSidebar() {
                document.getElementById('sidebar').classList.toggle('show');
            }
        </script>

        @php
            $package = $booking->tripSchedule->package;
            $schedule = $booking->tripSchedule;
            $nights = $schedule->start_date->diffInDays($schedule->end_date);
            $imageUrl = $package->getFirstMediaUrl('primary_image', app_setting('default_tour_image', 'https://images.unsplash.com/photo-1542051841857-5f90071e7989?w=800'));
            $itineraries = $package->itineraries;
        @endphp

        {{-- ─── Back Link ─── --}}
        <a href="{{ route('mybooking') }}"
            class="d-inline-flex align-items-center gap-1 text-decoration-none text-secondary mb-3"
            style="font-size:14px;">
            <span class="material-icons" style="font-size:18px;">arrow_back</span>
            Back to Bookings
        </a>

        {{-- ─── Page Header ─── --}}
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="fw-bold text-dark mb-2" style="font-size:1.75rem;">{{ $package->title }}</h1>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @php
                        $statusClass = match ($booking->status) {
                            'paid' => 'status-paid',
                            'confirmed' => 'status-confirmed',
                            'pending' => 'status-pending',
                            'cancelled' => 'status-cancelled',
                            default => 'status-pending',
                        };
                        $statusLabel = ucfirst($booking->status);
                    @endphp
                    <span class="badge rounded-pill px-3 py-2 {{ $statusClass }}" style="font-size:12px;">
                        ● {{ $statusLabel }}
                    </span>
                </div>
                <p class="text-secondary mt-2 mb-0" style="font-size:14px;">
                    Booking ID: <strong>#{{ $booking->booking_code }}</strong>
                    &nbsp;•&nbsp; Booked on {{ $booking->created_at->format('M d, Y') }}
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="#" class="btn btn-outline-dark rounded-3 px-3 py-2 d-flex align-items-center gap-2"
                    style="font-size:14px;">
                    <span class="material-icons" style="font-size:18px;">headset_mic</span>
                    Contact Support
                </a>
                <a href="{{ route('booking.guests', $booking->id) }}"
                    class="btn btn-outline-dark rounded-3 px-3 py-2 d-flex align-items-center gap-2"
                    style="font-size:14px;">
                    <span class="material-icons" style="font-size:18px;">edit</span>
                    Edit Trip
                </a>
                <a href="#" class="btn text-white rounded-3 px-3 py-2 d-flex align-items-center gap-2"
                    style="font-size:14px; background:#BC002D;">
                    <span class="material-icons" style="font-size:18px;">download</span>
                    Download Voucher
                </a>
            </div>
        </div>

        {{-- ─── Main Content: 2 columns ─── --}}
        <div class="row g-4">
            {{-- ─── LEFT COLUMN ─── --}}
            <div class="col-lg-8">

                {{-- Hero Card --}}
                <div class="mb-card mb-4 overflow-hidden">
                    <div class="mb-hero">
                        <img src="{{ $imageUrl }}" alt="{{ $package->title }}">
                        <div class="mb-hero-overlay">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="material-icons text-white" style="font-size:20px;">place</span>
                                <span class="text-white fw-bold" style="font-size:18px;">Destination & Itinerary</span>
                            </div>
                            <span class="text-white text-opacity-75" style="font-size:14px;">
                                {{ $nights }} Nights • {{ $schedule->start_date->format('M d') }} –
                                {{ $schedule->end_date->format('M d, Y') }}
                            </span>
                        </div>
                    </div>

                    {{-- Itinerary Timeline --}}
                    <div class="p-4">
                        @if($itineraries->count() > 0)
                            @php $showCount = 3; @endphp
                            <div class="itinerary-timeline">
                                @foreach($itineraries->take($showCount) as $itin)
                                    <div class="itinerary-item">
                                        <div class="itinerary-dot"></div>
                                        <h6 class="fw-bold mb-1">Day {{ $itin->day_number }}: {{ $itin->title }}</h6>
                                        <p class="text-secondary mb-2" style="font-size:14px;">
                                            {{ Str::limit($itin->description, 120) }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            @if($itineraries->count() > $showCount)
                                <div class="collapse-itinerary" id="moreItinerary">
                                    <div class="itinerary-timeline">
                                        @foreach($itineraries->skip($showCount) as $itin)
                                            <div class="itinerary-item">
                                                <div class="itinerary-dot"></div>
                                                <h6 class="fw-bold mb-1">Day {{ $itin->day_number }}: {{ $itin->title }}</h6>
                                                <p class="text-secondary mb-2" style="font-size:14px;">
                                                    {{ Str::limit($itin->description, 120) }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <button class="btn btn-link text-decoration-none fw-medium"
                                        style="color:#BC002D; font-size:14px;" onclick="toggleItinerary(this)">
                                        View Full Itinerary
                                        <span class="material-icons align-middle" style="font-size:18px;">expand_more</span>
                                    </button>
                                </div>
                            @endif
                        @else
                            <p class="text-secondary text-center mb-0">No itinerary details available yet.</p>
                        @endif
                    </div>
                </div>

                {{-- Guests Section --}}
                <div class="mb-card mb-4">
                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="material-icons text-secondary" style="font-size:22px;">group</span>
                                <h5 class="fw-bold mb-0">Guests</h5>
                            </div>
                            @if($booking->passengers->count() < $booking->pax_count)
                                <a href="{{ route('booking.guests', $booking->id) }}"
                                    class="btn btn-link text-decoration-none fw-medium p-0"
                                    style="color:#BC002D; font-size:14px;">
                                    + Add Guest
                                </a>
                            @endif
                        </div>

                        @forelse($booking->passengers as $index => $passenger)
                            <div
                                class="d-flex align-items-center justify-content-between {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="guest-avatar">
                                        {{ strtoupper(substr($passenger->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $passenger->name)[1] ?? '', 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="fw-semibold mb-0" style="font-size:15px;">{{ $passenger->name }}</h6>
                                        <small class="text-secondary">
                                            {{ $index === 0 ? 'Primary Contact' : 'Traveler' }}
                                            • {{ $passenger->gender ? ucfirst($passenger->gender) : 'Adult' }}
                                        </small>
                                    </div>
                                </div>
                                <a href="{{ route('booking.guests', $booking->id) }}" class="btn-edit-guest">
                                    <span class="material-icons" style="font-size:16px;">edit</span>
                                </a>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <span class="material-icons text-secondary mb-2" style="font-size:40px;">person_add</span>
                                <p class="text-secondary mb-2">No guests added yet.</p>
                                <a href="{{ route('booking.guests', $booking->id) }}"
                                    class="btn btn-outline-dark rounded-3 px-4" style="font-size:14px;">
                                    Add Guests
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- ─── RIGHT COLUMN ─── --}}
            <div class="col-lg-4">

                {{-- Trip Info Card --}}
                <div class="mb-card mb-4">
                    <div class="p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="material-icons" style="font-size:22px; color:#BC002D;">info</span>
                            <h5 class="fw-bold mb-0">Trip Details</h5>
                        </div>

                        <div class="d-flex align-items-start gap-3 mb-3">
                            <div class="rounded-3 overflow-hidden flex-shrink-0" style="width:56px; height:56px;">
                                <img src="{{ $imageUrl }}" alt="{{ $package->title }}"
                                    style="width:100%; height:100%; object-fit:cover;">
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1" style="font-size:15px;">{{ $package->title }}</h6>
                                <small class="text-secondary">{{ $package->location_text ?? 'Japan' }}</small>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <small class="text-secondary d-block">Start Date</small>
                                <strong style="font-size:14px;">{{ $schedule->start_date->format('M d, Y') }}</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-secondary d-block">End Date</small>
                                <strong style="font-size:14px;">{{ $schedule->end_date->format('M d, Y') }}</strong>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-6">
                                <small class="text-secondary d-block">Duration</small>
                                <strong style="font-size:14px;">{{ $package->duration_days }} Days</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-secondary d-block">Guests</small>
                                <strong style="font-size:14px;">{{ $booking->pax_count }}
                                    {{ Str::plural('Person', $booking->pax_count) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Summary --}}
                <div class="mb-card mb-4">
                    <div class="p-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="material-icons" style="font-size:22px; color:#BC002D;">receipt_long</span>
                            <h5 class="fw-bold mb-0">Payment</h5>
                        </div>

                        @php
                            $basePrice = $schedule->price * $booking->pax_count;
                            $taxes = round($basePrice * 0.10);
                            $total = $booking->total_price;
                        @endphp

                        <div class="payment-row">
                            <span class="text-secondary" style="font-size:14px;">Package Base Price</span>
                            <span class="fw-medium" style="font-size:14px;">{{ convert_currency($schedule->price) }} ×
                                {{ $booking->pax_count }}</span>
                        </div>
                        <div class="payment-row">
                            <span class="text-secondary" style="font-size:14px;">Subtotal</span>
                            <span class="fw-medium" style="font-size:14px;">{{ convert_currency($basePrice) }}</span>
                        </div>
                        <div class="payment-row total">
                            <span class="fw-bold">Total</span>
                            <span class="fw-bold" style="font-size:18px;">{{ convert_currency($total) }}</span>
                        </div>

                        {{-- Payment Status --}}
                        <div
                            class="mt-3 p-3 rounded-3 {{ $booking->status === 'paid' ? 'status-paid' : ($booking->status === 'pending' ? 'status-pending' : 'status-confirmed') }}">
                            @if($booking->status === 'paid')
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="material-icons" style="font-size:20px; color:#059669;">check_circle</span>
                                    <strong style="font-size:14px;">Paid in Full</strong>
                                </div>
                                <p class="mb-0" style="font-size:13px;">Payment completed on
                                    {{ $booking->updated_at->format('M d, Y') }}
                                </p>
                            @elseif($booking->status === 'pending')
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="material-icons" style="font-size:20px; color:#d97706;">schedule</span>
                                    <strong style="font-size:14px;">Awaiting Payment</strong>
                                </div>
                                <p class="mb-0" style="font-size:13px;">Please complete your payment to confirm this
                                    booking.</p>
                            @else
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="material-icons" style="font-size:20px; color:#2563eb;">verified</span>
                                    <strong style="font-size:14px;">{{ ucfirst($booking->status) }}</strong>
                                </div>
                                <p class="mb-0" style="font-size:13px;">Your booking is {{ $booking->status }}.</p>
                            @endif
                        </div>

                        {{-- Download Receipt --}}
                        @if($booking->status === 'paid')
                            <button
                                class="btn btn-outline-dark w-100 rounded-3 mt-3 d-flex align-items-center justify-content-center gap-2"
                                style="font-size:14px;">
                                <span class="material-icons" style="font-size:18px;">receipt</span>
                                Download Receipt
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Inclusions --}}
                @if($package->inclusions->count() > 0)
                    <div class="mb-card mb-4">
                        <div class="p-4">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="material-icons" style="font-size:22px; color:#BC002D;">checklist</span>
                                <h5 class="fw-bold mb-0">What's Included</h5>
                            </div>
                            @foreach($package->inclusions as $inclusion)
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    @if($inclusion->is_included)
                                        <span class="material-icons" style="font-size:18px; color:#059669;">check_circle</span>
                                    @else
                                        <span class="material-icons" style="font-size:18px; color:#dc2626;">cancel</span>
                                    @endif
                                    <span style="font-size:14px;"
                                        class="{{ $inclusion->is_included ? '' : 'text-secondary text-decoration-line-through' }}">
                                        {{ $inclusion->item }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>

    </main>
    @include('partials.script')

    <script>
        function toggleItinerary(btn) {
            const el = document.getElementById('moreItinerary');
            el.classList.toggle('show');
            if (el.classList.contains('show')) {
                btn.innerHTML = 'Hide Itinerary <span class="material-icons align-middle" style="font-size:18px;">expand_less</span>';
            } else {
                btn.innerHTML = 'View Full Itinerary <span class="material-icons align-middle" style="font-size:18px;">expand_more</span>';
            }
        }
    </script>

</body>

</html>