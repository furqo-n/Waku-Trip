<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<head>
    <style>
        :root {
            --bs-font-sans-serif: 'Plus Jakarta Sans', sans-serif;
            --primary-red: #f20d0d;
            --bg-light: #f8f5f5;
        }

        /* Scoped styles for the success content to avoid breaking the header/footer */
        .success-content {
            font-family: var(--bs-font-sans-serif);
            color: #1e293b;
        }

        .text-primary-custom {
            color: var(--primary-red) !important;
        }

        .bg-primary-custom {
            background-color: var(--primary-red) !important;
        }

        .border-primary-custom {
            border-color: var(--primary-red) !important;
        }

        .btn-danger-custom {
            background-color: var(--primary-red);
            border-color: var(--primary-red);
            color: white;
        }

        .btn-danger-custom:hover {
            background-color: #d60000;
            border-color: #d60000;
            color: white;
        }

        .sakura-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 10c-1 0-2 1-2 2s1 2 2 2 2-1 2-2-1-2-2-2zm-15 10c-1 0-2 1-2 2s1 2 2 2 2-1 2-2-1-2-2-2zm30 0c-1 0-2 1-2 2s1 2 2 2 2-1 2-2-1-2-2-2zM15 40c-1 0-2 1-2 2s1 2 2 2 2-1 2-2-1-2-2-2zm30 0c-1 0-2 1-2 2s1 2 2 2 2-1 2-2-1-2-2-2zM30 50c-1 0-2 1-2 2s1 2 2 2 2-1 2-2-1-2-2-2z' fill='%23f20d0d' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
            min-height: 80vh;
            /* Ensure it takes up space */
        }

        .success-card {
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border-radius: 1rem;
            background: white;
        }

        .success-icon-wrapper {
            width: 96px;
            height: 96px;
            background-color: var(--primary-red);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 10px 15px -3px rgba(242, 13, 13, 0.2);
            position: relative;
        }

        .success-icon-wrapper::before {
            content: '';
            position: absolute;
            width: 110%;
            height: 110%;
            border: 8px solid rgba(242, 13, 13, 0.1);
            border-radius: 50%;
        }

        .trip-img {
            object-fit: cover;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
            z-index: 10;
        }

        .step-line {
            position: absolute;
            left: 20px;
            top: 20px;
            bottom: 20px;
            width: 2px;
            background-color: #e2e8f0;
            z-index: 1;
        }

        .step-item {
            position: relative;
            z-index: 2;
        }

        .copy-btn {
            background-color: rgba(242, 13, 13, 0.05);
            color: var(--primary-red);
            border: none;
            border-radius: 50rem;
            padding: 0.5rem 1rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .copy-btn:hover {
            background-color: rgba(242, 13, 13, 0.1);
        }
    </style>
</head>

<body class="bg-light">

    <!--================ Header Menu Area =================-->
    @include('partials.header')
    <!--================ Header Menu Area =================-->

    <main class="success-content sakura-pattern d-flex align-items-center py-5">
        <div class="container py-4">
            <div class="row justify-content-center mb-5">
                <div class="col-12 col-lg-8 text-center">
                    <div class="d-inline-block position-relative mb-4">
                        <div class="success-icon-wrapper">
                            <i class="material-icons text-white" style="font-size: 48px;">check_circle</i>
                        </div>
                        <div class="position-absolute bg-primary-custom opacity-25 rounded-circle"
                            style="width: 24px; height: 24px; top: -10px; left: -10px; filter: blur(4px);"></div>
                        <div class="position-absolute bg-primary-custom opacity-10 rounded-circle"
                            style="width: 32px; height: 32px; bottom: -5px; right: -20px; filter: blur(4px);"></div>
                    </div>
                    <h1 class="display-5 fw-bold text-dark mb-3">
                        Omedetō! <span
                            class="text-primary-custom text-decoration-underline text-decoration-color-primary-light">Your
                            Japan adventure is booked.</span>
                    </h1>
                    <p class="lead text-secondary mx-auto" style="max-width: 600px;">
                        Pack your bags! We've secured your reservation. A confirmation email is flying its way to your
                        inbox right now.
                    </p>
                </div>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-12 col-md-7">
                    <div class="success-card mb-4 border-0">
                        <div
                            class="card-body p-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                            <div>
                                <p class="small fw-bold text-primary-custom text-uppercase mb-1 tracking-widest">
                                    Confirmation Number</p>
                                <p class="h3 fw-bold font-monospace text-dark mb-0" id="booking-code">
                                    {{ $booking->booking_code }}
                                </p>
                            </div>
                            <button class="copy-btn d-flex align-items-center gap-2" onclick="copyCode()">
                                <i class="material-icons fs-6">content_copy</i>
                                <span id="copy-text">Copy Code</span>
                            </button>
                        </div>
                    </div>
                    <div class="success-card border-0 overflow-hidden">
                        <div class="position-relative">
                            <img alt="{{ $package->name }}" class="w-100 trip-img" src="{{ $imageUrl }}" />
                            <div class="position-absolute top-0 start-0 m-3">
                                <span
                                    class="badge bg-white text-primary-custom shadow-sm px-3 py-2 rounded-pill fw-bold">KYOTO
                                    SPECIAL</span>
                            </div>
                        </div>
                        <div class="card-body p-4 p-lg-5">
                            <h3 class="card-title h3 fw-bold mb-4">{{ $package->name }}</h3>
                            <div class="row g-4">
                                <div class="col-6">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded p-2 bg-light d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="material-icons text-secondary opacity-50">calendar_today</i>
                                        </div>
                                        <div>
                                            <p class="small text-secondary fw-bold text-uppercase mb-0">Dates</p>
                                            <p class="fw-semibold mb-0">{{ $schedule->start_date->format('M d') }} -
                                                {{ $schedule->end_date->format('M d, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="rounded p-2 bg-light d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            <i class="material-icons text-secondary opacity-50">group</i>
                                        </div>
                                        <div>
                                            <p class="small text-secondary fw-bold text-uppercase mb-0">Travelers</p>
                                            <p class="fw-semibold mb-0">{{ $booking->pax_count }} Adults</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-5">
                    <div class="success-card border-0 h-100">
                        <div class="card-body p-4 p-lg-5 d-flex flex-column">
                            <h4 class="h5 fw-bold mb-4 d-flex align-items-center gap-2">
                                <span class="d-inline-block rounded-pill bg-primary-custom"
                                    style="width: 6px; height: 24px;"></span>
                                Next Steps
                            </h4>
                            <div class="position-relative flex-grow-1">
                                <div class="step-line"></div>
                                <div class="d-flex gap-3 mb-4 step-item">
                                    <div class="step-circle bg-primary-custom text-white">1</div>
                                    <div>
                                        <p class="fw-bold mb-1 text-dark">Check your email</p>
                                        <p class="small text-secondary mb-0">We've sent your official booking voucher
                                            and receipt to your inbox.</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-3 mb-4 step-item">
                                    <div
                                        class="step-circle bg-white border border-2 border-primary-custom text-primary-custom">
                                        2</div>
                                    <div>
                                        <p class="fw-bold mb-1 text-dark">Traveler Details</p>
                                        <p class="small text-secondary mb-0">Add passport details and preferences for
                                            all travelers in your profile.</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-3 step-item">
                                    <div class="step-circle bg-white border border-2 border-light text-secondary">3
                                    </div>
                                    <div>
                                        <p class="fw-bold mb-1 text-dark">Download Guide</p>
                                        <p class="small text-secondary mb-0">Get our curated 'Hidden Kyoto' PDF guide to
                                            start planning your days.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 pt-4 border-top">
                                <div class="d-grid gap-3">
                                    <a href="{{ url('/mybooking') }}"
                                        class="btn btn-danger-custom btn-lg rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2 py-3">
                                        <span>Go to My Bookings</span>
                                        <i class="material-icons">arrow_forward</i>
                                    </a>
                                    <button onclick="window.print()"
                                        class="btn btn-outline-dark btn-lg rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2 py-3">
                                        <i class="material-icons fs-5">print</i>
                                        <span>Print Receipt</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!--================ Footer Area =================-->
    @include('partials.footer')
    <!--================ Footer Area =================-->

    <script>
        function copyCode() {
            const code = document.getElementById('booking-code').textContent;
            navigator.clipboard.writeText(code).then(() => {
                const btn = document.getElementById('copy-text');
                btn.textContent = 'Copied!';
                setTimeout(() => btn.textContent = 'Copy Code', 2000);
            });
        }
    </script>

</body>

</html>