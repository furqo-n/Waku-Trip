<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<style>
    :root {
        --primary-color: #BC002D;
        --text-dark: #2a2a2a;
        --text-muted: #6c757d;
        --bg-light: #f8f9fa;
    }

    body {
        background-color: var(--bg-light);
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* Progress Steps */
    .checkout-progress {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem 0;
        margin-bottom: 2rem;
    }

    .step-item {
        display: flex;
        align-items: center;
        color: var(--text-muted);
        font-weight: 500;
        position: relative;
    }

    .step-item:not(:last-child)::after {
        content: '';
        display: block;
        width: 60px;
        height: 2px;
        background-color: #e9ecef;
        margin: 0 1rem;
    }

    .step-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        margin-right: 0.5rem;
    }

    .step-item.active {
        color: var(--primary-color);
        font-weight: 700;
    }

    .step-item.active .step-circle {
        background-color: var(--primary-color);
        color: white;
    }

    /* Cards */
    .checkout-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card-header-icon {
        color: var(--primary-color);
        margin-right: 0.5rem;
        font-size: 24px;
    }

    /* Form Inputs */
    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border-color: #dee2e6;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(188, 0, 45, 0.1);
    }

    /* Payment Methods */
    .payment-option {
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.2s;
        cursor: pointer;
    }

    .payment-option.selected {
        border-color: var(--primary-color);
        background-color: #fff5f6;
        border-width: 2px;
    }

    .custom-radio {
        width: 20px;
        height: 20px;
        border: 2px solid #adb5bd;
        border-radius: 50%;
        margin-right: 1rem;
        position: relative;
        flex-shrink: 0;
    }

    .payment-option.selected .custom-radio {
        border-color: var(--primary-color);
    }

    .payment-option.selected .custom-radio::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 10px;
        height: 10px;
        background-color: var(--primary-color);
        border-radius: 50%;
    }

    /* Order Summary */
    .summary-image {
        height: 200px;
        object-fit: cover;
        width: 100%;
        border-radius: 16px 16px 0 0;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px dashed #dee2e6;
    }

    .btn-primary-custom {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        padding: 1rem;
        border-radius: 50px;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s;
        border: none;
    }

    .btn-primary-custom:hover {
        background-color: #a00026;
        color: white;
    }

    .guarantee-card {
        background-color: #fff5f6;
        border: 1px solid rgba(188, 0, 45, 0.1);
        border-radius: 16px;
    }

    /* Sticky Sidebar */
    .sticky-summary {
        position: sticky;
        top: 20px;
    }
</style>

<body class="bg-light">

    <!--================ Header Menu Area =================-->
    @include('partials.header')
    <!--================ Header Menu Area =================-->

    <div class="container py-5">

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                <i class="material-icons align-middle me-2" style="font-size: 20px;">check_circle</i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                <i class="material-icons align-middle me-2" style="font-size: 20px;">error</i>
                <strong>Please fix the following:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Progress Bar -->
        <div class="checkout-progress">
            <div class="step-item active">
                <div class="step-circle">1</div>
                <span>Traveler Information</span>
            </div>
            <div class="step-item">
                <div class="step-circle">2</div>
                <span>Payment Method</span>
            </div>
            <div class="step-item">
                <div class="step-circle">3</div>
                <span>Confirmation</span>
            </div>
        </div>

        <form id="booking-form" method="POST" action="{{ url('/order') }}">
            @csrf
            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
            <input type="hidden" name="guests" value="{{ $guests }}">
            <input type="hidden" name="total_price" value="{{ $totalPrice }}">

            <div class="row g-4">
                <!-- Left Column: Forms -->
                <div class="col-lg-8">

                    <!-- Traveler Information -->
                    <div class="checkout-card p-4">
                        <div class="d-flex align-items-center mb-4 text-danger">
                            <span class="material-icons card-header-icon">person</span>
                            <h4 class="m-0 fw-bold text-dark">Traveler Information</h4>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">First Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control" placeholder="e.g. John"
                                    value="{{ old('first_name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Last Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control" placeholder="e.g. Doe"
                                    value="{{ old('last_name') }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Email Address <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="john@example.com"
                                    value="{{ old('email', auth()->user()->email ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Phone Number <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="phone_code" class="form-select bg-light border-end-0"
                                        style="max-width: 80px;">
                                        <option value="+62" {{ old('phone_code') == '+62' ? 'selected' : '' }}>+62
                                        </option>
                                        <option value="+1" {{ old('phone_code') == '+1' ? 'selected' : '' }}>+1</option>
                                        <option value="+81" {{ old('phone_code') == '+81' ? 'selected' : '' }}>+81
                                        </option>
                                        <option value="+44" {{ old('phone_code') == '+44' ? 'selected' : '' }}>+44
                                        </option>
                                    </select>
                                    <input type="text" name="phone" class="form-control border-start-0"
                                        placeholder="000 000 000" value="{{ old('phone') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Passport Number</label>
                                <input type="text" name="passport_number" class="form-control"
                                    placeholder="e.g. A1234567" value="{{ old('passport_number') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control"
                                    value="{{ old('date_of_birth') }}">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small text-secondary">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select...</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                    </option>
                                    <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-secondary">Special Requests
                                (Optional)</label>
                            <textarea name="special_requests" class="form-control" rows="3"
                                placeholder="Allergies, dietary requirements, or accessible needs...">{{ old('special_requests') }}</textarea>
                        </div>

                        <small class="text-secondary d-block mt-2">We will do our best to accommodate your requests with
                            our local partners.</small>
                    </div>
                </div>

                <!-- Right Column: Summary -->
                <div class="col-lg-4">
                    <div class="sticky-summary">
                        <!-- Order Summary Card -->
                        <div class="checkout-card p-0 shadow-sm border">
                            <div class="position-relative">
                                <img src="{{ $schedule->package->getFirstMediaUrl('primary_image', app_setting('default_tour_image')) }}"
                                    class="summary-image" alt="{{ $schedule->package->name }}">
                                <div
                                    class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-to-t from-black to-transparent">
                                    <!-- Gradient overlay if needed -->
                                </div>
                            </div>
                            <div class="p-4">
                                <h5 class="fw-bold mb-3 text-dark">{{ $schedule->package->name }}</h5>

                                <div class="d-flex align-items-center gap-2 mb-2 text-secondary small">
                                    <i class="material-icons text-danger" style="font-size: 18px;">calendar_today</i>
                                    <span class="fw-medium">{{ $schedule->start_date->format('M d') }} –
                                        {{ $schedule->end_date->format('M d, Y') }}</span>
                                    <span class="text-muted">({{ $durationDays }} Days)</span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-4 text-secondary small">
                                    <i class="material-icons text-danger" style="font-size: 18px;">people</i>
                                    <span class="fw-medium">{{ $guests }} Travelers</span>
                                    <span class="text-muted">(Adult x {{ $guests }})</span>
                                </div>
                                <hr class="border-secondary opacity-25 my-3">
                                <div class="price-row small mb-2">
                                    <span class="text-secondary">Base Price ({{ convert_currency($pricePerPerson) }} x
                                        {{ $guests }})</span>
                                    <span class="fw-bold text-dark">{{ convert_currency($totalPrice) }}</span>
                                </div>
                                <div class="price-row small mb-2">
                                    <span class="text-secondary">PPN (12%)</span>
                                    <span class="fw-bold text-dark">{{ convert_currency($ppn) }}</span>
                                </div>
                                <div class="price-row small mb-2">
                                    <span class="text-secondary">Fee (10%)</span>
                                    <span class="fw-bold text-dark">{{ convert_currency($fee) }}</span>
                                </div>
                                <div class="price-row small text-success mb-2">
                                    <span>Early Bird Discount (7%)</span>
                                    <span class="fw-bold">-{{ convert_currency($discount) }}</span>
                                </div>

                                <div class="total-row mt-3 pt-3 border-top border-dashed">
                                    <h5 class="fw-bold m-0 text-dark">Total</h5>
                                    <div class="text-end">
                                        <h3 class="fw-bold m-0 text-danger">
                                            {{ convert_currency($finalTotal) }}
                                        </h3>
                                        <small class="text-secondary fw-bold"
                                            style="font-size: 0.7rem; letter-spacing: 0.5px;">{{ $currentCurrency }}
                                            TOTAL</small>
                                    </div>
                                </div>

                                <button type="submit" form="booking-form"
                                    class="btn btn-primary-custom mt-4 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                    <span>Continue to Payment</span>
                                    <i class="material-icons" style="font-size: 18px;">arrow_forward</i>
                                </button>
                            </div>
                        </div>

                        <!-- Guarantee Card -->
                        <div class="checkout-card guarantee-card p-3 mb-3 d-flex align-items-start gap-3">
                            <div class="bg-white p-2 rounded-circle shadow-sm d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width: 40px; height: 40px;">
                                <i class="material-icons text-danger" style="font-size: 20px;">verified</i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-danger">Waku Guarantee</h6>
                                <small class="text-secondary lh-base d-block" style="font-size: 12px;">Free cancellation
                                    up
                                    to 7 days before departure and 24/7 on-trip support.</small>
                            </div>
                        </div>

                        <!-- Help Card -->
                        <div
                            class="card border-0 shadow-sm p-3 rounded-4 d-flex flex-row align-items-center justify-content-between bg-white">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-light p-2 rounded-circle d-flex align-items-center justify-content-center text-secondary"
                                    style="width: 40px; height: 40px;">
                                    <i class="material-icons" style="font-size: 20px;">help_outline</i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Need Help?</h6>
                                    <small class="text-secondary" style="font-size: 12px;">Our team is online</small>
                                </div>
                            </div>
                            <a href="#" class="text-danger fw-bold text-decoration-none small">Chat Now</a>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>

    <!--================ Footer Area start =================-->
    @include('partials.footer')
    <!--=============== Footer Area end =================-->

</body>

</html>