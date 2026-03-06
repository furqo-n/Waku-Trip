<!DOCTYPE html>
<html lang="en">

@include('partials.head')

<body class="bg-shape">

    <!--================ Header Menu Area start =================-->
    @include('partials.header')
    <!--===============Header Menu Area =================-->
    <main class="container py-5">

        <div class="mb-5 text-center text-md-start">
            <div class="d-flex align-items-center gap-2 text-secondary small fw-bold text-uppercase mb-2">
                <span>Home</span>
                <span class="material-symbols-outlined fs-6">chevron_right</span>
                <span class="text-japan-red">Custom Tour Planner</span>
            </div>
            <h2 class="display-5 fw-black mb-4">
                Design Your Dream <span class="text-gradient">Japan Trip</span>
            </h2>

            <div class="bg-white p-4 rounded-4 border shadow-sm d-inline-block w-100" style="max-width: 100%;">
                <div class="row text-center position-relative">
                    <div class="col position-relative z-1">
                        <div class="d-flex flex-column align-items-center">
                            <div class="rounded-circle bg-japan-red text-white d-flex align-items-center justify-content-center fw-bold shadow-sm"
                                style="width: 40px; height: 40px;">1</div>
                            <span class="small fw-bold text-secondary mt-2 text-uppercase"
                                style="font-size: 10px; letter-spacing: 1px;">Destinations</span>
                        </div>
                        <div class="step-connector active"></div>
                    </div>
                    <div class="col position-relative z-1">
                        <div class="d-flex flex-column align-items-center">
                            <div class="rounded-circle bg-white border border-2 text-secondary d-flex align-items-center justify-content-center fw-bold"
                                style="width: 40px; height: 40px;">2</div>
                            <span class="small fw-bold text-secondary mt-2 text-uppercase"
                                style="font-size: 10px; letter-spacing: 1px;">Interests</span>
                        </div>
                        <div class="step-connector"></div>
                    </div>
                    <div class="col position-relative z-1">
                        <div class="d-flex flex-column align-items-center">
                            <div class="rounded-circle bg-white border border-2 text-secondary d-flex align-items-center justify-content-center fw-bold"
                                style="width: 40px; height: 40px;">3</div>
                            <span class="small fw-bold text-secondary mt-2 text-uppercase"
                                style="font-size: 10px; letter-spacing: 1px;">Budget</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5">
            <div class="col-lg-8">

                <section class="glass-panel rounded-4 p-4 p-md-5 mb-5 shadow-sm bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="h4 fw-bold text-dark d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined text-japan-red">map</span> Where to go?
                            </h3>
                            <p class="text-secondary small mb-0">Select regions to highlight on your itinerary.</p>
                        </div>
                        <span class="badge bg-light text-secondary border">Multi-select enabled</span>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="position-relative rounded-4 overflow-hidden border h-100 bg-light"
                                style="min-height: 300px;">
                                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuCMz9z9Mg81iWXgrjHzZEiaNPTfRZ1ZpPsSOLSmMcvrrcsyBHMsay0QwtrrFyuly45FvMDmozjYkoAHPCh7hmRKvTspJGXms6GiRIKAB8QIvKYJEt_vMk4OMbCkRHaAL6iLFB3ZbDhXB9ZtZV6xQEu3Af1UHnidDaD3Ju8HztTYtXKokBJa7IQIQVmbOpliegWyxiZf4RQX7bVpYGjZJxNle86wxLDI9T0_QaHG5VjvbIBcozIxFHlnx4GLxpNbVUWFmDMOpMRRMIg"
                                    class="w-100 h-100 object-fit-cover" alt="Japan Map" style="filter: contrast(1.1);">

                                <div class="position-absolute" style="top: 60%; left: 70%;">
                                    <div class="position-relative">
                                        <div class="marker-pulse"></div>
                                        <span
                                            class="material-symbols-outlined text-japan-red fs-2 position-relative z-1">location_on</span>
                                    </div>
                                    <span
                                        class="badge bg-white text-dark shadow-sm border position-absolute start-50 translate-middle-x mt-1">TOKYO</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="d-grid gap-3">
                                <button
                                    class="glass-card glass-card-active rounded-4 p-3 d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-japan-red text-white d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width: 40px; height: 40px;">
                                        <span class="material-symbols-outlined fs-5">temple_hindu</span>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="fw-bold text-dark">Honshu</span>
                                            <span
                                                class="material-symbols-outlined text-japan-red fs-6">check_circle</span>
                                        </div>
                                        <small class="text-japan-red d-block" style="font-size: 11px;">Tokyo, Kyoto,
                                            Osaka</small>
                                    </div>
                                </button>

                                <button class="glass-card rounded-4 p-3 d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-white border d-flex align-items-center justify-content-center text-secondary flex-shrink-0"
                                        style="width: 40px; height: 40px;">
                                        <span class="material-symbols-outlined fs-5">ac_unit</span>
                                    </div>
                                    <div class="text-start">
                                        <span class="fw-bold text-dark d-block">Hokkaido</span>
                                        <small class="text-secondary" style="font-size: 11px;">Sapporo, Niseko,
                                            Nature</small>
                                    </div>
                                </button>

                                <button class="glass-card rounded-4 p-3 d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-white border d-flex align-items-center justify-content-center text-secondary flex-shrink-0"
                                        style="width: 40px; height: 40px;">
                                        <span class="material-symbols-outlined fs-5">hot_tub</span>
                                    </div>
                                    <div class="text-start">
                                        <span class="fw-bold text-dark d-block">Kyushu</span>
                                        <small class="text-secondary" style="font-size: 11px;">Fukuoka, Beppu
                                            Onsen</small>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="glass-panel rounded-4 p-4 p-md-5 mb-5 shadow-sm bg-white">
                    <h3 class="h4 fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-japan-red">favorite</span> Trip Vibes
                    </h3>

                    <div class="row row-cols-2 row-cols-sm-4 g-3">
                        <div class="col">
                            <button
                                class="glass-card rounded-4 p-4 d-flex flex-col align-items-center justify-content-center text-center h-100">
                                <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center mb-3"
                                    style="width: 50px; height: 50px;">
                                    <span class="material-symbols-outlined fs-4">restaurant</span>
                                </div>
                                <span class="fw-bold text-secondary small">Foodie</span>
                            </button>
                        </div>
                        <div class="col">
                            <button
                                class="glass-card-active rounded-4 p-4 d-flex flex-col align-items-center justify-content-center text-center h-100 position-relative">
                                <div class="rounded-circle bg-japan-red text-white d-flex align-items-center justify-content-center mb-3 shadow-sm"
                                    style="width: 50px; height: 50px;">
                                    <span class="material-symbols-outlined fs-4">castle</span>
                                </div>
                                <span class="fw-bold text-japan-red small">History</span>
                                <span class="position-absolute top-0 end-0 m-2 p-1 bg-japan-red rounded-circle"></span>
                            </button>
                        </div>
                        <div class="col">
                            <button
                                class="glass-card rounded-4 p-4 d-flex flex-col align-items-center justify-content-center text-center h-100">
                                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center mb-3"
                                    style="width: 50px; height: 50px;">
                                    <span class="material-symbols-outlined fs-4">forest</span>
                                </div>
                                <span class="fw-bold text-secondary small">Nature</span>
                            </button>
                        </div>
                        <div class="col">
                            <button
                                class="glass-card rounded-4 p-4 d-flex flex-col align-items-center justify-content-center text-center h-100">
                                <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center mb-3"
                                    style="width: 50px; height: 50px;">
                                    <span class="material-symbols-outlined fs-4">auto_awesome</span>
                                </div>
                                <span class="fw-bold text-secondary small">Anime</span>
                            </button>
                        </div>
                    </div>
                </section>

                <section class="glass-panel rounded-4 p-4 p-md-5 mb-5 shadow-sm bg-white">
                    <h3 class="h4 fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                        <span class="material-symbols-outlined text-japan-red">tune</span> The Essentials
                    </h3>

                    <div class="row g-5">
                        <div class="col-md-6">
                            <label class="form-label text-secondary fw-bold small text-uppercase">Travel Dates</label>
                            <div class="position-relative">
                                <span
                                    class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y ms-3 text-japan-red">calendar_month</span>
                                <input type="text" class="form-control form-control-lg ps-5 rounded-4 bg-light border-0"
                                    placeholder="Select dates...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label text-secondary fw-bold small text-uppercase mb-0">Budget per
                                    person</label>
                                <span class="fw-black text-japan-red h5 mb-0">$3,000 - $5,000</span>
                            </div>
                            <input type="range" class="form-range custom-range" min="1000" max="10000" step="500">
                            <div class="d-flex justify-content-between text-secondary fw-bold"
                                style="font-size: 10px; letter-spacing: 1px;">
                                <span>ECONOMY</span>
                                <span class="text-dark">COMFORT</span>
                                <span>LUXURY</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-lg-4">
                <div class="sticky-top" style="top: 100px; z-index: 10;">
                    <div
                        class="glass-panel rounded-4 p-4 shadow bg-light border border-danger border-opacity-25 position-relative overflow-hidden mb-4">
                        <div class="position-absolute top-0 end-0 bg-danger opacity-10 rounded-circle"
                            style="width: 150px; height: 150px; filter: blur(40px); transform: translate(30%, -30%);">
                        </div>

                        <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom position-relative z-1">
                            <div class="bg-white p-2 rounded-3 border shadow-sm">
                                <span class="material-symbols-outlined text-japan-red">analytics</span>
                            </div>
                            <h4 class="h5 fw-bold text-dark mb-0">Your Waku Trip</h4>
                        </div>

                        <div class="position-relative z-1">
                            <div class="mb-3">
                                <p class="small fw-bold text-secondary text-uppercase mb-2"
                                    style="font-size: 10px; letter-spacing: 1px;">Destinations</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <span
                                        class="badge bg-white text-japan-red border border-danger-subtle rounded-pill py-2 px-3 d-flex align-items-center gap-1 shadow-sm">
                                        Honshu <i class="bi bi-x"></i> <span class="material-symbols-outlined"
                                            style="font-size: 12px; cursor: pointer;">close</span>
                                    </span>
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill fw-bold border-dashed"
                                        style="font-size: 11px;">+ Add</button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <p class="small fw-bold text-secondary text-uppercase mb-2"
                                    style="font-size: 10px; letter-spacing: 1px;">Interests</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <span
                                        class="badge bg-danger text-white rounded-pill py-2 px-3 d-flex align-items-center gap-1 shadow-sm">
                                        History <span class="material-symbols-outlined"
                                            style="font-size: 12px; cursor: pointer;">close</span>
                                    </span>
                                </div>
                            </div>

                            <div class="pt-3 border-top">
                                <div class="d-flex justify-content-between align-items-end mb-4">
                                    <div>
                                        <p class="small fw-bold text-secondary text-uppercase mb-0"
                                            style="font-size: 10px;">Estimated Base</p>
                                        <h3 class="fw-black text-dark mb-0">$3,450<small
                                                class="text-secondary fs-6 fw-medium">/pp</small></h3>
                                    </div>
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success border border-success-subtle">Custom</span>
                                </div>

                                <button
                                    class="btn btn-japan w-100 py-3 rounded-4 fw-bold shadow hover-scale d-flex align-items-center justify-content-center gap-2">
                                    Request Quote <span class="material-symbols-outlined fs-5">send</span>
                                </button>
                                <p class="text-center text-secondary mt-3 small" style="font-size: 11px;">Our experts
                                    will refine your itinerary within 24 hours.</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="card border-0 shadow-sm rounded-4 p-3 d-flex flex-row align-items-center gap-3 cursor-pointer hover-border-red">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center border"
                            style="width: 48px; height: 48px;">
                            <span class="material-symbols-outlined text-secondary">support_agent</span>
                        </div>
                        <div>
                            <p class="fw-bold text-dark mb-0 small">Need assistance?</p>
                            <p class="text-secondary mb-0 small" style="font-size: 11px;">Talk to a Japan Specialist</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
    <!--================ Footer Area start =================-->
    @include('partials.footer')
    <!--=============== Footer Area end =================-->

</body>

</html>