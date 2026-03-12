<footer class="footer-section">
    <div class="container" style="max-width: 1440px;">

        <div class="row gy-5 mb-5">
            <!-- Brand Section -->
            <div class="col-12 col-lg-5 pe-lg-5">
                <a href="{{ url('/') }}" class="d-block mb-4">
                    <img src="{{ asset('img/logo_1.png') }}" alt="Waku Trip" style="height: 40px;">
                </a>
                <p class="text-body-gray mb-4 lh-lg" style="max-width: 380px;">
                    Waku Trip makes discovering Japan easy and unforgettable. From bustling cities to quiet
                    mountains, we help you find your waku waku (excitement).
                </p>
                <div class="d-flex gap-3">
                    <a class="social-btn" href="https://www.arealaptop.online"><i class="bi bi-globe fs-5"></i></a>
                    <a class="social-btn" href="https://www.instagram.com/waku.trip/"><i
                            class="bi bi-instagram fs-5"></i></a>
                    <a class="social-btn" href="#"><i class="bi bi-tiktok fs-5"></i></a>
                </div>
            </div>

            <!-- Mobile Accordion Links (Visible < md) -->
            <div class="col-12 d-md-none mt-4">
                <div class="accordion accordion-flush" id="footerAccordion">
                    <div class="accordion-item border-0 bg-transparent">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed bg-transparent shadow-none px-0 fw-bold text-dark"
                                type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                aria-expanded="false" aria-controls="flush-collapseOne">
                                Company
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingOne" data-bs-parent="#footerAccordion">
                            <div class="accordion-body px-0 pt-0 pb-3 d-flex flex-column gap-3">
                                <a class="footer-link" href="{{ url('/aboutus') }}">About Us</a>
                                <a class="footer-link" href="#">Careers</a>
                                <a class="footer-link" href="#">Press</a>
                                <a class="footer-link" href="{{ url('/news') }}">News</a>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 bg-transparent">
                        <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed bg-transparent shadow-none px-0 fw-bold text-dark"
                                type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                aria-expanded="false" aria-controls="flush-collapseTwo">
                                Support
                            </button>
                        </h2>
                        <div id="flush-collapseTwo" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingTwo" data-bs-parent="#footerAccordion">
                            <div class="accordion-body px-0 pt-0 pb-3 d-flex flex-column gap-3">
                                <a class="footer-link" href="#">Help Center</a>
                                <a class="footer-link" href="#">Terms of Service</a>
                                <a class="footer-link" href="#">Privacy Policy</a>
                                <a class="footer-link" href="#">Contact Us</a>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 bg-transparent">
                        <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed bg-transparent shadow-none px-0 fw-bold text-dark"
                                type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree"
                                aria-expanded="false" aria-controls="flush-collapseThree">
                                Destinations
                            </button>
                        </h2>
                        <div id="flush-collapseThree" class="accordion-collapse collapse"
                            aria-labelledby="flush-headingThree" data-bs-parent="#footerAccordion">
                            <div class="accordion-body px-0 pt-0 pb-3 d-flex flex-column gap-3">
                                <a class="footer-link"
                                    href="{{ route('planned.index', ['destination' => 'Tokyo']) }}">Tokyo</a>
                                <a class="footer-link"
                                    href="{{ route('planned.index', ['destination' => 'Kyoto']) }}">Kyoto</a>
                                <a class="footer-link"
                                    href="{{ route('planned.index', ['destination' => 'Osaka']) }}">Osaka</a>
                                <a class="footer-link"
                                    href="{{ route('planned.index', ['destination' => 'Hokkaido']) }}">Hokkaido</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Grid Links (Visible >= md) -->
            <div class="d-none d-md-block col-md-4 col-lg-2">
                <h4 class="text-heading fw-bold h6 mb-3">Company</h4>
                <div class="d-flex flex-column gap-3">
                    <a class="footer-link" href="{{ url('/aboutus') }}">About Us</a>
                    <a class="footer-link" href="#">Careers</a>
                    <a class="footer-link" href="#">Press</a>
                    <a class="footer-link" href="{{ url('/news') }}">News</a>
                </div>
            </div>

            <div class="d-none d-md-block col-md-4 col-lg-2">
                <h4 class="text-heading fw-bold h6 mb-3">Support</h4>
                <div class="d-flex flex-column gap-3">
                    <a class="footer-link" href="#">Help Center</a>
                    <a class="footer-link" href="#">Terms of Service</a>
                    <a class="footer-link" href="#">Privacy Policy</a>
                    <a class="footer-link" href="#">Contact Us</a>
                </div>
            </div>

            <div class="d-none d-md-block col-md-4 col-lg-2">
                <h4 class="text-heading fw-bold h6 mb-3">Destinations</h4>
                <div class="d-flex flex-column gap-3">
                    <a class="footer-link" href="{{ route('planned.index', ['destination' => 'Tokyo']) }}">Tokyo</a>
                    <a class="footer-link" href="{{ route('planned.index', ['destination' => 'Kyoto']) }}">Kyoto</a>
                    <a class="footer-link" href="{{ route('planned.index', ['destination' => 'Osaka']) }}">Osaka</a>
                    <a class="footer-link"
                        href="{{ route('planned.index', ['destination' => 'Hokkaido']) }}">Hokkaido</a>
                </div>
            </div>

        </div>

        <div class="border-top pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 text-center text-md-start"
            style="border-color: var(--bs-border-light) !important;">
            <p class="small fw-medium text-secondary mb-0">© 2026 Waku Trip Inc. All rights reserved.</p>

            <div class="d-flex gap-4">
                <button class="btn-footer-option">
                    <span class="material-symbols-outlined fs-5">language</span> English (US)
                </button>
                <button class="btn-footer-option">
                    <span class="material-symbols-outlined fs-5">attach_money</span> USD
                </button>
            </div>
        </div>

    </div>
</footer>
@include('partials.script')
<!-- Chatbot Widget -->
@include('partials.chatbot')