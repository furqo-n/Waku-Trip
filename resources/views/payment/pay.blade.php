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

    /* Progress Steps - same as order.blade.php */
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

    .step-item.completed .step-circle {
        background-color: #e9ecef;
        color: var(--text-muted);
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
        border: 2px solid #dee2e6;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.2s;
        cursor: pointer;
    }

    .payment-option:hover {
        border-color: #adb5bd;
    }

    .payment-option.selected {
        border-color: var(--primary-color);
        background-color: rgba(188, 0, 45, 0.02);
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

        <!-- Progress Bar -->
        <div class="checkout-progress">
            <div class="step-item completed">
                <div class="step-circle">
                    <i class="material-icons" style="font-size: 16px;">check</i>
                </div>
                <span>Traveler Information</span>
            </div>
            <div class="step-item active">
                <div class="step-circle">2</div>
                <span>Payment Method</span>
            </div>
            <div class="step-item">
                <div class="step-circle">3</div>
                <span>Confirmation</span>
            </div>
        </div>

        <div class="row g-4">
            <!-- Left Column: Payment Form -->
            <div class="col-lg-8">

                <!-- Traveler Summary Card -->
                <div class="checkout-card p-4 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="material-icons text-secondary" style="font-size: 20px;">person</i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">{{ $travelerName }}</h6>
                            <small class="text-secondary">{{ $travelerEmail }} • {{ $travelerPhone }}</small>
                        </div>
                    </div>
                    <a href="javascript:history.back()" class="text-decoration-none fw-bold small"
                        style="color: var(--primary-color);">Edit</a>
                </div>

                <!-- Payment Method Card -->
                <div class="checkout-card p-4">
                    <div class="d-flex align-items-center mb-4 text-danger">
                        <span class="material-icons card-header-icon">payments</span>
                        <h4 class="m-0 fw-bold text-dark">Payment Method</h4>
                    </div>

                    <!-- Express Checkout -->
                    <div class="mb-4">
                        <label class="form-label small text-uppercase fw-bold text-secondary mb-3"
                            style="letter-spacing: 1px;">Quick Checkout</label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <button
                                    class="btn btn-dark w-100 py-3 d-flex align-items-center justify-content-center gap-2"
                                    style="border-radius: 12px;">
                                    <span class="fw-bold">Apple Pay</span>
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button
                                    class="btn btn-outline-dark w-100 py-3 d-flex align-items-center justify-content-center gap-2"
                                    style="border-radius: 12px;">
                                    <img alt="Google"
                                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuCzUpFkwSLI1q4FvG9dF2qD6-h6svKBy0UDHHxWWNi9oIp9XY-W2SqBpg_O-9cXoqDNd636BCnwtDmVPa8FKbtWFMXimOXXLiDqp4V_hQXDYNZGoHgePBYgZ18ANzbUvkZ5uC_yFQK5v4NRIPKcjOUdyHJ82PcG78glQ6qtyZRak4Yu91whQWCNGZLlFTpomfDS0alVLvXVoY7y_e55mmRFjieKVpQ8jKDE8xYgdP3X8cK4VReDmmt5mUv6KuYktrRR75rKrB74YH8"
                                        style="height: 18px;" />
                                    <span class="fw-bold">Pay</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="position-relative my-4">
                        <hr class="border-secondary opacity-25">
                        <span
                            class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-secondary text-uppercase fw-bold"
                            style="letter-spacing: 1px;">Or pay with card</span>
                    </div>

                    <!-- Credit Card Option -->
                    <div class="payment-option selected mb-3" onclick="selectPayment(this)">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="d-flex align-items-center">
                                <div class="custom-radio"></div>
                                <span class="fw-bold fs-5">Credit or Debit Card</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 bg-light px-3 py-1 rounded-3 border">
                                <img alt="Visa"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuBJvMLwg8K7QDuvdXfuGLSo4OA7RVYGOfdyo2qlVn0npsIOAVx_BRYlL6eWG2uZSWPJj4qYF3AtbhbgUDJDiaf_JVainzDGMukrRmb43sq2XSXOzKIHI85hagjbLvAJICoDoZsHbTIHD1wwU1DxZh9dM_bAGyC4mtYdgdbPYKS1e5F_qLit79vYQ7pnSabLIMSUpGMo9jXjqHuVhrmibvJvD4_EfpRy4ijo-kR7U8q01J0cnZiC7FpCOL_3af9yl3oyvCJQJSot6BI"
                                    style="height: 20px;" />
                                <img alt="Mastercard"
                                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuA0mD7pJO-K1Jiv3ugQfICUBzjJhlZlX1ohNWHZtoDXA2OtPCk7bBobgkue77K2ZZuAzQmTaUjQ9goHFb5lpxbpadIyBr45g-lFTGWMY9GNnaCogtlkrw-bFKNdoZC5dZaUqOSLT1vdzsKRATY1jUQaNAFS_Shn1i1VuM4sfIzC5lUhsKTm3HBghdXcxZCvg8ixuhZzRTtnDsafqpvV63uqPWYRjSYFC0EjsgWyCV_kU2N7mMwYSIGacIwBf2dgZoYmiWgWcFsOIAE"
                                    style="height: 20px;" />
                                <div class="text-white rounded px-2 d-flex align-items-center fw-bold"
                                    style="font-size: 9px; background-color: #1a4399;">JCB</div>
                            </div>
                        </div>

                        <!-- Card Fields -->
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small text-uppercase fw-bold text-secondary">Card
                                    Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="material-icons"
                                            style="font-size: 20px; color: var(--primary-color);">credit_card</i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0"
                                        placeholder="4242 4242 4242 4242" value="4242 4242 4242 4242">
                                    <span class="input-group-text bg-white">
                                        <i class="material-icons text-success" style="font-size: 18px;">check_circle</i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-uppercase fw-bold text-secondary">Expiry
                                    Date</label>
                                <input type="text" class="form-control" placeholder="MM / YY">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-uppercase fw-bold text-secondary">CVC</label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" placeholder="•••">
                                    <i class="material-icons text-secondary position-absolute"
                                        style="font-size: 18px; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer;">help</i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Other Wallets Option -->
                    <div class="payment-option mb-3" onclick="selectPayment(this)">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="custom-radio"></div>
                                <span class="fw-bold text-secondary">Other Digital Wallets</span>
                            </div>
                            <div class="d-flex gap-2 opacity-50">
                                <span class="badge bg-primary px-2 py-1 fw-bold" style="font-size: 10px;">PayPal</span>
                                <span class="badge bg-dark px-2 py-1 fw-bold" style="font-size: 10px;">Alipay</span>
                            </div>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="d-flex align-items-start gap-3 p-3 bg-light rounded-3 mt-4">
                        <i class="material-icons text-success" style="font-size: 22px;">verified_user</i>
                        <p class="small text-secondary mb-0">Your payment information is encrypted and securely
                            processed. We do not store your full card details.</p>
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary -->
            <div class="col-lg-4">
                <div class="sticky-summary">
                    <!-- Order Summary Card -->
                    <div class="checkout-card p-0 shadow-sm border">
                        <div class="position-relative">
                            <img src="{{ $imageUrl }}" class="summary-image" alt="{{ $schedule->package->name }}">
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
                                <span class="fw-bold text-dark">{{ convert_currency($basePrice) }}</span>
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
                                        {{ convert_currency($totalPrice) }}
                                    </h3>
                                    <small class="text-secondary fw-bold"
                                        style="font-size: 0.7rem; letter-spacing: 0.5px;">{{ $currentCurrency }}
                                        TOTAL</small>
                                </div>
                            </div>

                            <form id="payment-form" method="POST" action="{{ url('/pay') }}">
                                @csrf
                            </form>
                            <button type="submit" form="payment-form"
                                class="btn btn-primary-custom mt-4 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                <span>Complete Booking</span>
                                <i class="material-icons" style="font-size: 18px;">lock</i>
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
                                up to 7 days before departure and 24/7 on-trip support.</small>
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
    </div>

    <!--================ Footer Area =================-->
    @include('partials.footer')
    <!--================ Footer Area =================-->

    <script>
        function selectPayment(el) {
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
            el.classList.add('selected');
        }
    </script>

</body>

</html>